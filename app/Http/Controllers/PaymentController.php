<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Ride;

use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


use Stripe;

// $res = $stripe->tokens->create([
    // 'card' => [
    //     'number' => '4242424242424242',
    //     'exp_month' => 7,
    //     'exp_year' => 2024,
    //     'cvc' => '314',
    // ],
    // ]);
    
    // Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    // $response = $stripe->charges->create([
    // 'amount' => 200,
    // 'currency' => 'eur',
    // 'source' => $res->id,
    // 'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
    // ]);

class PaymentController extends Controller
{
    public function stripePost(Request $request) {
        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $account = $stripe->accounts->create(['type' => 'express']);

            $stripe->accountLinks->create([
                'account' => $account->id,
                'refresh_url' => 'https://example.com/reauth',
                'return_url' => 'https://example.com/return',
                'type' => 'account_onboarding',
              ]);

            return response()->json([$stripe->url], 201);

        }catch(Exception $e){
            return response()->json(['response'=>$e], 500);
        }
    }


    /**
     * Create User Stripe Account
     * @OA\Post (
     *     path="/api/account",
     *     tags={"Payment"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="url",type="string",example="http://localhost:4200/me/profil/subscriptions"),
     *                 ),
     *                 example={
     *                     "userId":"1",
     *                     "url":"http://localhost:4200/me/profil/subscriptions"
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="https://connect.stripe.com/setup/e/acct_1NT6vlQlorGC8SEB/MnxyuG2EcdXt")
     *         )
     *     )
     * )
     */
    public function account(Request $request){

        if(!$request->userId){
            return response()->json(['response'=>"Enter a userId in the request"], 500);

        }
        if(!$request->url){
            return response()->json(['response'=>"Enter a valid redirect url in the request"], 500);
        }

        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $path = Storage::path('public\\logo.png');

            $fp = fopen($path, 'r');
            $business_logo = $stripe->files->create([
                'file' => $fp,
                'purpose' => 'business_logo',
            ]);
            $business_icon = $stripe->files->create([
                'file' => $fp,
                'purpose' => 'business_icon',
            ]);

            // Get previous account id if existing
            $user = User::find($request->userId);
            
            $previousAccountId = $user->paymentAccount;

            if ($previousAccountId != NULL) {
                $stripe->accounts->retrieve($previousAccountId);
            }

            // Create a new account for the user
            $paymentAccount = $stripe->accounts->create([
                'country' => 'fr',
                'type' => 'express',
                'email' => $user->username,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true]
                ],
                'business_type' => 'individual',
                'settings' => [
                    'branding' => [
                        'icon' => $business_icon,
                        'logo' => $business_logo,
                        'primary_color' => '#000000',
                        'secondary_color' => '#ffffff'
                    ]
                ],
                'business_profile' => [
                    'name' => $user->username.' - envoitures.fr',
                    'support_email' => $user->username,
                ]
            ]);
            $user->paymentAccount = $paymentAccount->id;
            $user->save();

            // If previous account is not null, delete it
            if ($previousAccountId != NULL) {
                $stripe->accounts->delete($user->paymentAccount);
            }

            // Generate AccountLink
            $accountLink = $stripe->accountLinks->create([
                'account' => $user->paymentAccount,
                'refresh_url' => $request->url,
                'return_url' => $request->url,
                'type' => 'account_onboarding',
            ]);

            return response()->json([$accountLink->url], 201);
        }
        catch(ApiErrorException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

        /**
     * Delete User Stripe Account
     * @OA\Delete (
     *     path="/api/account/{id}",
     *     tags={"Payment"},
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
     *             @OA\Property(property="msg", type="string", example="Connected stripe account has been deleted")
     *         )
     *     )
     * )
     */
    public function deleteUserAccount($id){
        $user = User::find($id);
        if(!$user){
           return response()->json(['response'=>"User not found"]);
        }

        if ($user->paymentAccount == NULL) {
            return response()->json(['reponse'=>"User doesn't have a connected stripe account"]);
        }
        
        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $stripe->accounts->delete($user->paymentAccount);

            $user->paymentAccount = NULL;
            $user->save();

            return response()->json(['response'=>"Connected stripe account has been deleted"]);
        } catch(ApiErrorException $e) {
            return response()->json(["response"=>e], 400);
        }

    }


    public function createPayment(Request $request){
        
        if(!$request->bookingId){
            return response()->json(["response"=>"Les données soumises sont invalides."]);
        }
        $booking = Booking::find($request->bookingId);
        if ($booking == NULL) {
            return response()->json(["response"=>"Impossible de trouver le trajet"]);
        }

        $connected_stripe_account_id = $booking->user->paymentAccount;

        if ($connected_stripe_account_id == NULL) {
            return response()->json(["response"=>"Impossible de procéder au paiement. Le chauffeur ne possède pas de compte."]);
        }

        try {
            $amount = $booking->suggestedPrice * 100;
            $application_fee_amount = floatval(env('PERCENTAGE')) * $amount;
            
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'application_fee_amount' => $application_fee_amount,
            ], ['stripe_account' => $connected_stripe_account_id]);

            $booking->payment = $payment_intent->id;
            $booking->paidAt = now();
            $booking->save();
            
            return response()->json(["response"=>"Paiement du chauffeur éxecuté avec succes "]);

        } catch (ApiErrorException $e) {
            return response()->json(["response"=>$e]);
        }
    }


    public function getPublicKey() {
        return response()->json(['public_key' => env('STRIPE_PUBLIC_KEY')]);
    }




    public function calculatePrice(Request $request){
        $datas = json_decode($request->getContent(), true);

        if (!array_key_exists("start", $datas) 
            || !array_key_exists('end', $datas)
            || !array_key_exists('longitude', $datas['start'])
            || !array_key_exists('latitude', $datas['start'])
            || !array_key_exists('longitude', $datas['end'])
            || !array_key_exists('latitude', $datas['end'])
            ) {
            throw new BadRequestHttpException("Les données soumises ne sont pas valides.");
        }

            $distance = $this->distance(
                floatval($datas['start']['latitude']),
                floatval($datas['start']['longitude']),
                floatval($datas['end']['latitude']),
                floatval($datas['end']['longitude']),
                "K"
            );
    
            $recommanded_price = $distance * 0.95 * 0.1;
            $recommanded_price = ($recommanded_price - floor($recommanded_price)) > 0.5 ? ceil($recommanded_price) : floor($recommanded_price);
    
            return response()->json([
                'recommanded' => $recommanded_price,
                'max' => $recommanded_price * 1.3,
                'min' => $recommanded_price * 0.7,
                'percentage' => env('APPLICATION_FEE_PERCENTAGE')
            ]);
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit) : float {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
            return ($miles * 1.609344);
            } else if ($unit == "N") {
            return ($miles * 0.8684);
            } else {
            return $miles;
            }
        }
    }

}
