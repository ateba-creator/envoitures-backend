<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\BookingCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Filters\BookingFilters;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;

use Stripe;

class BookingController extends Controller
{
    /**
     * Get List of bookings, for filters use: <<attributeName>>[<<operator>>]=<<value>>, operatorList=[eq,lt,lte,gt,gte] e.g userId[eq]=3,
     * And to include other properties like user or ride in the response, use : include<<propertyName>>=true, e.g includeRide=true, includeUser=true
     * @OA\Get (
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="suggestedPrice",type="number",example="32"),
     *                     @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="payment",type="string",example="pending"),
     *                     @OA\Property(property="paidAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="fee",type="number",example="10"),
     *                     @OA\Property(property="isValidated",type="number",example="0"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        $filter = new BookingFilters();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $bookings = Booking::where($filterItems);

        $includeUser = $request->query('includeUser');
        $includeRide = $request->query('includeRide');

        if($includeUser){
            $bookings = $bookings->with('user');
        }
        if($includeRide){
            $bookings = $bookings->with('ride');
        }
        return new BookingCollection($bookings->paginate());
    }


        /**
     * Create Booking
     * @OA\Post (
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="price",type="nullable",example="32"),
     *                 ),
     *                 example={
     *                     "userId":"1",
     *                     "rideId":"1",
     *                     "price":"32",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="suggestedPrice",type="number",example="32"),
     *                     @OA\Property(property="price",type="nullable",example="32"),
     *                     @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="payment",type="string",example="pending"),
     *                     @OA\Property(property="paidAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="fee",type="number",example="10"),
     *                     @OA\Property(property="isValidated",type="number",example="0"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function store(StoreBookingRequest $request)
    {
        
        if(!$request->rideId){
            return response()->json(["response"=>"Vous devez préciser le trajet."]);
        }

        if(!$request->price){
            return response()->json(["response"=>"Vous devez préciser le prix."]);
        }

        if(!$request->userId){
            return response()->json(["response"=>"Vous devez préciser l'utilisateur qui execute la fonction."]);
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $rideId = $request->rideId;

        $ride = Ride::where('id', '=', $rideId)->where('status', '=', 1)->first();

        if ($ride == NULL) {
            return response()->json(["response"=>"Impossible de trouver le trajet."]);
        }
        $driverConnectedAccountId = $ride->user->paymentAccount;
        // return response()->json([$driverConnectedAccountId]);
        if ($driverConnectedAccountId == NULL) {
            return response()->json(["response"=>"Impossible de réaliser cette action. Des informations supplémentaires sont encore demandées au conducteur."]);
        }

        $price = floatval($request->price) == -1 ? $ride->price : $request->price;
        $platformFees = $price * floatval(env('APPLICATION_FEE_PERCENTAGE'));
        $amountToTransfer = $price - $platformFees;

        $user = User::find($request->userId);

        $bookings = Booking::where('bookedBy','=',$user->id)->get();
        
        if (sizeof($bookings) > 0) {
            // $booking->save();
            //throw new BadRequestHttpException("Vous avez déjà une réservation.");
        }
             
        try {
            if($ride->placesNumber > 0){
                $paymentIntent = $stripe->paymentIntents->create([
                    'amount' => $price * 100,
                    'currency' => 'eur',
                    'payment_method_types' => ['card'],
                    'capture_method' => 'manual',
                    'transfer_data' => [
                        'amount' => $amountToTransfer * 100,
                        'destination' => $driverConnectedAccountId,
                    ],
                ]);

                $booking = Booking::create([
                    'user_id'=>$ride->user->id,
                    'bookedBy'=>$user->id,
                    'fee'=>$platformFees,
                    'isValidated'=>False,
                    'suggestedPrice'=>$price,
                    'payment'=>$paymentIntent->id,
                    'ride_id'=>$ride->id,
                    'createdAt'=>now(),
                    'validatedAt'=>now(),
                    'paidAt'=>now(),
                    'views'=>0,
                    'canBook'=>1
                ]);
            }else{
                return response()->json(['response'=>"sits have been completely bought, car is already full"]);
            }
            if($booking && $ride->placesNumber>0){
                $ride->placesNumber -= $ride->placesNumber;
                $ride->save();
            }
            return new BookingResource($booking);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(["response"=>$e->getMessage()]);
        }        
    }

    /**
     * Get Booking Details
     * @OA\Get (
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="suggestedPrice",type="number",example="32"),
     *                     @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="payment",type="string",example="pending"),
     *                     @OA\Property(property="paidAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="fee",type="number",example="10"),
     *                     @OA\Property(property="isValidated",type="number",example="0"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(Booking $booking)
    {
        return new BookingResource($booking);
    }

    /**
     * Update Booking
     * @OA\Put (
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="suggestedPrice",type="number",example="32"),
     *                     @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="payment",type="string",example="pending"),
     *                     @OA\Property(property="paidAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="fee",type="number",example="10"),
     *                     @OA\Property(property="isValidated",type="number",example="0"),
     *                 ),
     *                 example={
     *                     "userId":"1",
     *                     "rideId":"1",
     *                     "suggestedPrice":"35",
     *                     "validatedAT":"2021-12-11",
     *                     "payment":"pending",
     *                     "paidAt":"2021-12-11",
     *                     "fee":"12",
     *                     "isValidated":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="suggestedPrice",type="number",example="32"),
     *                     @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="payment",type="string",example="pending"),
     *                     @OA\Property(property="paidAt",type="date",example="2001-12-11"),
     *                     @OA\Property(property="fee",type="number",example="10"),
     *                     @OA\Property(property="isValidated",type="number",example="0"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update($request->all());
    }

    /**
     * Delete Booking
     * @OA\Delete (
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="delete booking success")
     *         )
     *     )
     * )
     */
    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();
            return response()->json(["msg"=>"delete booking success"]);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }
}
