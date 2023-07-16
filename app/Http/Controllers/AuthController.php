<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function __construct(User $user)
    {
        // model as dependency injection
        $this->user = $user;
    }

        /**
     * User Registration
     * @OA\Post (
     *     path="/api/register",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="username",type="string",example="example@gmail.com"),
     *                     @OA\Property(property="fname",type="string",example="ateba otabela"),
     *                     @OA\Property(property="lname",type="string",example="hermann ryan"),
     *                     @OA\Property(property="birthDate",type="date",example="2001-12-11"),
     *                     @OA\Property(property="phoneNumber",type="string",example="+33 6756778234"),
     *                     @OA\Property(property="sex",type="string",example="m"),
     *                     @OA\Property(property="password",type="string",example="password"),
     *                 ),
     *                 example={
     *                     "username":"username",
     *                     "fname":"first name",
     *                     "lname":"last name",
     *                     "birthDate":"2001-12-03",
     *                     "phoneNumber":"+33 6763245239",
     *                     "sex":"m?f:null",
     *                     "password":"password",
     *
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="username",type="string",example="example@gmail.com"),
     *                     @OA\Property(property="fname",type="string",example="ateba otabela"),
     *                     @OA\Property(property="lname",type="string",example="hermann ryan"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *                     @OA\Property(property="birthDate",type="string",example="2001-12-11"),
     *                     @OA\Property(property="age",type="number",example="20"),
     *                     @OA\Property(property="phoneNumber",type="string",example="+33 6756778234"),
     *                     @OA\Property(property="sex",type="string",example="m"),
     *                     @OA\Property(property="role",type="string",example="[ROLE_USER]"),
     *                     @OA\Property(property="paymentAccount",type="string",example="stripe_asdless124"),
     * 
     *                     @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
     *                     @OA\Property(property="isDetourPossible",type="nummber",example="1"),
     * 
     *                     @OA\Property(property="receivingNewsPapers",type="nummber",example="1"),
     *                     @OA\Property(property="licenseImageRecto",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *                     @OA\Property(property="licenseRectoUpdated",type="string",example="2001-12-11"),
     *                     @OA\Property(property="licenseImageVerso",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *                     @OA\Property(property="licenseVersoUpdated",type="string",example="2001-12-11"),
     *                     @OA\Property(property="idCardImageRecto",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *                     @OA\Property(property="idCardRectoUpdated",type="string",example="2001-12-11"),
     *                     @OA\Property(property="idCardImageVerso",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *                     @OA\Property(property="idCardVersoUpdated",type="string",example="2001-12-11"),
     *                      
     *                     @OA\Property(
     *                          type="array",
     *                          property="bookings",
     *                          @OA\Items(
     *                              type="object",
     *                                     @OA\Property(property="id",type="number",example="1"),
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
     *                              )
     *                      ),
     *                      @OA\Property(
     *                          type="array",
     *                          property="rides",
     *                          @OA\Items(
     *                              type="object",
     *                                       @OA\Property(property="id",type="number",example="1"),
    *                                       @OA\Property(property="start",type="string",example="Marseille "),
    *                                       @OA\Property(property="end",type="string",example="Paris"),
    *                                       @OA\Property(property="price",type="number",example="45"),
    *                                       @OA\Property(property="startAt",type="string",example="2021-12-11"),
    *                                       @OA\Property(property="status",type="number",example="0"),
    *                                       @OA\Property(property="placesNumber",type="number",example="4"),
    *                                       @OA\Property(property="twoPlaces",type="number",example="0"),
    *                                       @OA\Property(property="acceptAuctions",type="number",example="0"),
    *                                       @OA\Property(property="isDetourAllowed",type="number",example="0"),
     *                                      @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                                      @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                      ),
     *                      @OA\Property(
     *                          type="array",
     *                          property="reviews",
     *                          @OA\Items(
     *                              type="object",
     *                                      @OA\Property(property="id",type="number",example="1"),
                        *                     @OA\Property(property="rideId",type="number",example="1"),
                        *                     @OA\Property(property="isPrivate",type="number",example="2"),
                        *                     @OA\Property(property="note",type="number",example="4"),
                        *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
                        *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
                        *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                      ),
     * 
     *                      @OA\Property(
     *                          type="object",
     *                          property="vehicle",
     *                                       @OA\Property(property="id",type="number",example="1"),
                        *                     @OA\Property(property="designation",type="string",example="Toyato"),
                        *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
                        *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
                        *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
                        *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
                        *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
                        *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
                        *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
                        *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                             
     *                      ),
     * 
     *                     @OA\Property(property="token",type="string",example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"),
     *                     @OA\Property(property="expires_in",type="string",example="3600"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                      
     *                      
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
    public function register(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        if($request->imageName){
            $path = Storage::putFile('public/userImages', $request->imageName);
            $user->imageName = substr_replace($path, 'storage', 0, 6);
            $user->save();
        }
        $token = auth()->login($user);
        return response()->json([
            "user"=>new UserResource($user),
            "token"=>$token,
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
        
    }


            /**
     * User Login
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="username",type="string",example="example@gmail.com"),
     *                     @OA\Property(property="password",type="string",example="password"),
     *                 ),
     *                 example={
     *                     "username":"username",
     *                     "password":"password",
     *
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="username",type="string",example="example@gmail.com"),
     *                     @OA\Property(property="fname",type="string",example="ateba otabela"),
     *                     @OA\Property(property="lname",type="string",example="hermann ryan"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *                     @OA\Property(property="birthDate",type="string",example="2001-12-11"),
     *                     @OA\Property(property="age",type="number",example="20"),
     *                     @OA\Property(property="phoneNumber",type="string",example="+33 6756778234"),
     *                     @OA\Property(property="sex",type="string",example="m"),
     *                     @OA\Property(property="role",type="string",example="[ROLE_USER]"),
     *                     @OA\Property(property="paymentAccount",type="string",example="stripe_asdless124"),
     * 
     *                     @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
     *                     @OA\Property(property="isDetourPossible",type="nummber",example="1"),
     * 
     *                     @OA\Property(property="receivingNewsPapers",type="nummber",example="1"),
     *                     @OA\Property(property="licenseImageRecto",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *                     @OA\Property(property="licenseRectoUpdated",type="string",example="2001-12-11"),
     *                     @OA\Property(property="licenseImageVerso",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *                     @OA\Property(property="licenseVersoUpdated",type="string",example="2001-12-11"),
     *                     @OA\Property(property="idCardImageRecto",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *                     @OA\Property(property="idCardRectoUpdated",type="string",example="2001-12-11"),
     *                     @OA\Property(property="idCardImageVerso",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *                     @OA\Property(property="idCardVersoUpdated",type="string",example="2001-12-11"),
     *                      
     *                     @OA\Property(
     *                          type="array",
     *                          property="bookings",
     *                          @OA\Items(
     *                              type="object",
     *                                     @OA\Property(property="id",type="number",example="1"),
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
     *                              )
     *                      ),
     *                      @OA\Property(
     *                          type="array",
     *                          property="rides",
     *                          @OA\Items(
     *                              type="object",
     *                                       @OA\Property(property="id",type="number",example="1"),
    *                                       @OA\Property(property="start",type="string",example="Marseille "),
    *                                       @OA\Property(property="end",type="string",example="Paris"),
    *                                       @OA\Property(property="price",type="number",example="45"),
    *                                       @OA\Property(property="startAt",type="string",example="2021-12-11"),
    *                                       @OA\Property(property="status",type="number",example="0"),
    *                                       @OA\Property(property="placesNumber",type="number",example="4"),
    *                                       @OA\Property(property="twoPlaces",type="number",example="0"),
    *                                       @OA\Property(property="acceptAuctions",type="number",example="0"),
    *                                       @OA\Property(property="isDetourAllowed",type="number",example="0"),
     *                                      @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                                      @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                      ),
     *                      @OA\Property(
     *                          type="array",
     *                          property="reviews",
     *                          @OA\Items(
     *                              type="object",
     *                                      @OA\Property(property="id",type="number",example="1"),
                        *                     @OA\Property(property="rideId",type="number",example="1"),
                        *                     @OA\Property(property="isPrivate",type="number",example="2"),
                        *                     @OA\Property(property="note",type="number",example="4"),
                        *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
                        *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
                        *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                              )
     *                      ),
     * 
     *                      @OA\Property(
     *                          type="object",
     *                          property="vehicle",
     *                                       @OA\Property(property="id",type="number",example="1"),
                        *                     @OA\Property(property="designation",type="string",example="Toyato"),
                        *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
                        *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
                        *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
                        *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
                        *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
                        *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
                        *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
                        *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                             
     *                      ),
     * 
     *                     @OA\Property(property="token",type="string",example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"),
     *                     @OA\Property(property="expires_in",type="string",example="3600"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *                      
     *                      
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

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // attempt a login (validate the credentials provided)
        $token = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if($token){
            $user = User::where('username','=',$request->username)->get()[0];
            return response()->json([
                "user"=>new UserResource($user),
                "token"=>$token,
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
        }else{
            return response()->json([
                "response"=>"Login Failed",
            ]);
        }

    }


    /**
     * User Logout
     * @OA\Post (
     *     path="/api/logout",
     *     tags={"Users"},
     *     @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="code", type="string", example="200"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="logout success"),
     * 
     *          )
     *      )
     * )
     * */

    public function logout()
    {
        // get token
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);

        if($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
        }
    }

}
