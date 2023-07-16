<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Ride;
use App\Http\Requests\StoreRideRequest;
use App\Http\Requests\UpdateRideRequest;

use App\Http\Resources\RideResource;
use App\Http\Resources\RideCollection;
use App\Filters\RideFilters;
use App\Models\User;
use App\Models\Step;


use Stripe;

class RideController extends Controller
{
    /**
     * Get List of rides, for filters use: <<attributeName>>[<<operator>>]=<<value>>, operatorList=[eq,lt,lte,gt,gte] e.g userId[eq]=3,
     * And to include other properties like user in the response, use : include<<propertyName>>=true, e.g includeUser=true
     * @OA\Get (
     *     path="/api/rides",
     *     tags={"Rides"},
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
     *                     @OA\Property(property="start",type="string",example="Marseille "),
     *                     @OA\Property(property="end",type="string",example="Paris"),
     *                     @OA\Property(property="price",type="number",example="45"),
     *                     @OA\Property(property="startAt",type="string",example="2021-12-11"),
     *                     @OA\Property(property="status",type="number",example="0"),
     *                     @OA\Property(property="placesNumber",type="number",example="4"),
     *                     @OA\Property(property="passengerNumber",type="number",example="4"),
     * 
     *                     @OA\Property(property="twoPlaces",type="number",example="0"),
     *                     @OA\Property(property="acceptAuctions",type="number",example="0"),
     *                     @OA\Property(property="isDetourAllowed",type="number",example="0"),
     *                     @OA\Property(property="isMusic",type="number",example="0"),
     *                     @OA\Property(property="isAnimal",type="number",example="0"),
     *                     @OA\Property(property="isBag",type="number",example="1"),
     *                     @OA\Property(property="isFood",type="number",example="0"),
     * 
     *                      @OA\Property(
     *                          property="user",
    *                           type="array",
    *                               @OA\Items(
    *                                        @OA\Property(property="id",type="number",example="1"),
    *                                         @OA\Property(property="username",type="string",example="example@gmail.com"),
    *                                         @OA\Property(property="fname",type="string",example="ateba otabela"),
    *                                         @OA\Property(property="lname",type="string",example="hermann ryan"),
    *                                         @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
    *                                         @OA\Property(property="birthDate",type="date",example="2001-12-11"),
    *                                         @OA\Property(property="phoneNumber",type="string",example="+33 6756778234"),
    *                                         @OA\Property(property="sex",type="string",example="m"),
    *                                         @OA\Property(property="role",type="string",example="[ROLE_USER]"),
    *                                         @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
    *                                         @OA\Property(property="isDetourPossible",type="nummber",example="1"),
     *                                         @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
      *                                        @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                     ),
     * 
     *                     @OA\Property(
     *                          property="bookings",
    *                           type="array",
    *                               @OA\Items(
                *                     @OA\Property(property="userId",type="number",example="1"),
                *                     @OA\Property(property="rideId",type="number",example="1"),
                *                     @OA\Property(property="suggestedPrice",type="number",example="32"),
                *                     @OA\Property(property="price",type="nullable",example="32"),
                *                     @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
                *                     @OA\Property(property="payment",type="string",example="pending"),
                *                     @OA\Property(property="paidAt",type="date",example="2001-12-11"),
                *                     @OA\Property(property="fee",type="number",example="10"),
                *                     @OA\Property(property="isValidated",type="number",example="0"),
                *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
                *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                     ),
     * 
     *                      @OA\Property(
     *                          property="reviews",
    *                           type="array",
    *                               @OA\Items(
                    *                     @OA\Property(property="id",type="number",example="1"),
                    *                     @OA\Property(property="userId",type="number",example="1"),
                    *                     @OA\Property(property="rideId",type="number",example="1"),
                    *                     @OA\Property(property="isPrivate",type="number",example="2"),
                    *                     @OA\Property(property="note",type="number",example="4"),
                    *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
                    *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
                    *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                     ),
     * 
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
        $filter = new RideFilters();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $rides = Ride::where($filterItems);

        $includeUser = $request->query('includeUser');
        $includeBookings = $request->query('includeBookings');

        return new RideCollection($rides->paginate()->appends($request->query()));

    }

    /**
     * Create Ride
     * @OA\Post (
     *     path="/api/rides",
     *     tags={"Rides"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="start",type="string",example="Marseille "),
     *                     @OA\Property(property="end",type="string",example="Paris"),
     *                     @OA\Property(property="price",type="number",example="45"),
     *                     @OA\Property(property="startAt",type="string",example="2021-12-11"),
     *                     @OA\Property(property="status",type="number",example="0"),
     *                     @OA\Property(property="placesNumber",type="number",example="4"),
     *                     @OA\Property(property="passengerNumber",type="number",example="4"),
     *                     @OA\Property(property="twoPlaces",type="number",example="0"),
     *                     @OA\Property(property="acceptAuctions",type="number",example="0"),
     *                     @OA\Property(property="isDetourAllowed",type="number",example="0")
     *                 ),
     *                 example={
     *                     "userId":"1",
     *                     "startAt":"Marseille",
     *                     "start":"Reim",
     *                     "end":"Paris",
     *                     "price":"45",
     *                     "startAt":"2021-12-11",
     *                     "status":"1",
     *                     "placesNumber":"4",
     *                     "passengerNumber":"4",
     *                     "twoPlaces":"1",
     *                     "acceptAuctions":"0",
     *                     "isDetourAllowed":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="start",type="string",example="Marseille "),
     *                     @OA\Property(property="end",type="string",example="Paris"),
     *                     @OA\Property(property="price",type="number",example="45"),
     *                     @OA\Property(property="startAt",type="string",example="2021-12-11"),
     *                     @OA\Property(property="status",type="number",example="0"),
     *                     @OA\Property(property="placesNumber",type="number",example="4"),
     *                     @OA\Property(property="passengerNumber",type="number",example="4"),
     *                     @OA\Property(property="twoPlaces",type="number",example="0"),
     *                     @OA\Property(property="acceptAuctions",type="number",example="0"),
     *                     @OA\Property(property="isDetourAllowed",type="number",example="0"),
     *                     @OA\Property(property="isMusic",type="number",example="0"),
     *                     @OA\Property(property="isAnimal",type="number",example="0"),
     *                     @OA\Property(property="isBag",type="number",example="1"),
     *                     @OA\Property(property="isFood",type="number",example="0"),
     *                     @OA\Property(property="canBook",type="number",example="0"),
     *                     @OA\Property(property="views",type="number",example="0"),
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

    public function store(StoreRideRequest $request)
    {
        $user = User::find($request->userId);
        if($user->vehicle){
            $ride = Ride::create($request->all());
            
            $ride->isFoodAllowed = $user->vehicle->isFoodAllowed;
            $ride->isBagAllowed = $user->vehicle->isBagAllowed;
            $ride->isAnimalAllowed = $user->vehicle->isAnimalAllowed;
            $ride->isMusicAllowed = $user->vehicle->isMusicAllowed;
            $ride->save();

            if($request->steps && sizeof($request->steps) > 0){
                foreach($request->steps as $step){
                    $newStep = Step::create([
                        'designation'=>$step,
                        'ride_id'=>$ride->id
                    ]);
                    $newStep->save();
                }
            }
            return new RideResource($ride);
        }else{
            return response()->json(['response'=>"User must provide vehicle information"]);
        }
    }

    /**
     * Get Booking Details
     * @OA\Get (
     *     path="/api/rides/{id}",
     *     tags={"Rides"},
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
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="start",type="string",example="Marseille "),
     *                     @OA\Property(property="end",type="string",example="Paris"),
     *                     @OA\Property(property="price",type="number",example="45"),
     *                     @OA\Property(property="startAt",type="string",example="2021-12-11"),
     *                     @OA\Property(property="status",type="number",example="0"),
     *                     @OA\Property(property="placesNumber",type="number",example="4"),
     *                     @OA\Property(property="twoPlaces",type="number",example="0"),
     *                     @OA\Property(property="acceptAuctions",type="number",example="0"),
     *                     @OA\Property(property="isDetourAllowed",type="number",example="0"),
     * 
     *                     @OA\Property(
     *                          property="user",
    *                           type="array",
    *                               @OA\Items(
    *                                        @OA\Property(property="id",type="number",example="1"),
    *                                         @OA\Property(property="username",type="string",example="example@gmail.com"),
    *                                         @OA\Property(property="fname",type="string",example="ateba otabela"),
    *                                         @OA\Property(property="lname",type="string",example="hermann ryan"),
    *                                         @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
    *                                         @OA\Property(property="birthDate",type="date",example="2001-12-11"),
    *                                         @OA\Property(property="phoneNumber",type="string",example="+33 6756778234"),
    *                                         @OA\Property(property="sex",type="string",example="m"),
    *                                         @OA\Property(property="role",type="string",example="[ROLE_USER]"),
    *                                         @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
    *                                         @OA\Property(property="isDetourPossible",type="nummber",example="1"),
    *                                         @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
    *                                        @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                     ),
     * 
     *                     @OA\Property(
     *                          property="bookings",
    *                           type="array",
    *                               @OA\Items(
    *                                   @OA\Property(property="userId",type="number",example="1"),
    *                                   @OA\Property(property="rideId",type="number",example="1"),
    *                                   @OA\Property(property="suggestedPrice",type="number",example="32"),
    *                                   @OA\Property(property="price",type="nullable",example="32"),
    *                                   @OA\Property(property="validatedAt",type="date",example="2001-12-11"),
    *                                   @OA\Property(property="payment",type="string",example="pending"),
    *                                   @OA\Property(property="paidAt",type="date",example="2001-12-11"),
    *                                   @OA\Property(property="fee",type="number",example="10"),
    *                                   @OA\Property(property="isValidated",type="number",example="0"),
    *                                   @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
    *                                   @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                     ),
     * 
     *                      @OA\Property(
     *                          property="reviews",
    *                           type="array",
    *                               @OA\Items(
                    *                     @OA\Property(property="id",type="number",example="1"),
                    *                     @OA\Property(property="userId",type="number",example="1"),
                    *                     @OA\Property(property="rideId",type="number",example="1"),
                    *                     @OA\Property(property="isPrivate",type="number",example="2"),
                    *                     @OA\Property(property="note",type="number",example="4"),
                    *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
                    *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
                    *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                     ),
     * 
     * 
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(Ride $ride)
    {
        return new RideResource($ride);
    }
        /**
     * Update Ride
     * @OA\Put (
     *     path="/api/rides/{id}",
     *     tags={"Rides"},
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
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="start",type="string",example="Marseille "),
     *                     @OA\Property(property="end",type="string",example="Paris"),
     *                     @OA\Property(property="price",type="number",example="45"),
     *                     @OA\Property(property="startAt",type="string",example="2021-12-11"),
     *                     @OA\Property(property="status",type="number",example="0"),
     *                     @OA\Property(property="placesNumber",type="number",example="4"),
     *                     @OA\Property(property="twoPlaces",type="number",example="0"),
     *                     @OA\Property(property="acceptAuctions",type="number",example="0"),
     *                     @OA\Property(property="isDetourAllowed",type="number",example="0"),
     *                 ),
     *                 example={
     *                     "userId":"1",
     *                     "startAt":"Marseille",
     *                     "end":"Paris",
     *                     "price":"45",
     *                     "startAt":"2021-12-11",
     *                     "status":"1",
     *                     "placesNumber":"4",
     *                     "twoPlaces":"1",
     *                     "acceptAuctions":"0",
     *                     "isDetourAllowed":"0",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="start",type="string",example="Marseille "),
     *                     @OA\Property(property="end",type="string",example="Paris"),
     *                     @OA\Property(property="price",type="number",example="45"),
     *                     @OA\Property(property="startAt",type="string",example="2021-12-11"),
     *                     @OA\Property(property="status",type="number",example="0"),
     *                     @OA\Property(property="placesNumber",type="number",example="4"),
     *                     @OA\Property(property="twoPlaces",type="number",example="0"),
     *                     @OA\Property(property="acceptAuctions",type="number",example="0"),
     *                     @OA\Property(property="isDetourAllowed",type="number",example="0"),
     *          )
     *      )
     * )
     */
    public function update(UpdateRideRequest $request, Ride $ride)
    {
        $ride->update($request->all());
    }

        /**
     * Delete Ride
     * @OA\Delete (
     *     path="/api/rides/{id}",
     *     tags={"Rides"},
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
    public function destroy(Ride $ride)
    {
        try {
            $ride->delete();
            return response()->json(["msg"=>"delete ride success"]);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }
}
