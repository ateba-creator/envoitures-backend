<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Filters\UserFilters;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserCollection;
use App\Http\Resources\RideCollection;
use App\Http\Resources\BookingCollection;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
        /**
     * Get List of users, for filters use: <<attributeName>>[<<operator>>]=<<value>>, operatorList=[eq,lt,lte,gt,gte] e.g username[eq]=3,
     * And to include other properties like rides or bookings in the response, use : include<<propertyName>>=true, e.g includeRides=true, includeBookings=true
     * @OA\Get (
     *     path="/api/users",
     *     tags={"Users"},
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
     *                     @OA\Property(property="username",type="string",example="example@gmail.com"),
     *                     @OA\Property(property="fname",type="string",example="ateba otabela"),
     *                     @OA\Property(property="lname",type="string",example="hermann ryan"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *                     @OA\Property(property="birthDate",type="date",example="2001-12-11"),
     *                     @OA\Property(property="age",type="date",example="20"),
     *                     @OA\Property(property="phoneNumber",type="string",example="+33 6756778234"),
     *                     @OA\Property(property="sex",type="string",example="m"),
     *                     @OA\Property(property="paymentAccount",type="string",example="stripe_asdless124"),
     *                     @OA\Property(property="role",type="string",example="[ROLE_USER]"),
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
        // foreach(User::all() as $user){
        //     $user->imageName = 'storage/userImages/'.$user->imageName;
        //     $user->save();
        // }

        $filter = new UserFilters();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $users = User::where($filterItems);

        return new UserCollection($users->paginate()->appends($request->query()));
    }
    


    /**
     * Get User Details
     * @OA\Get (
     *     path="/api/users/{id}",
     *     tags={"Users"},
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
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="username", type="string", example="example@gmail.com"),
     *              @OA\Property(property="fname",type="string",example="ateba otabela"),
     *              @OA\Property(property="lname",type="string",example="hermann ryan"),
     *              @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *              @OA\Property(property="birthDate",type="string",example="2021-12-11"),
     *              @OA\Property(property="age",type="number",example="20"),
     *              @OA\Property(property="phoneNumber",type="string",example="+33 6202112101"),
     *              @OA\Property(property="sex",type="string",example="m"),
     *              @OA\Property(property="role",type="string",example="[ROLE_USER]"),
     *              @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
     *              @OA\Property(property="isDetourPossible",type="nummber",example="1"),

     *              @OA\Property(property="receivingNewsPapers",type="nummber",example="1"),
     *              @OA\Property(property="licenseImageRecto",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *              @OA\Property(property="licenseRectoUpdated",type="string",example="2001-12-11"),
     *              @OA\Property(property="licenseImageVerso",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *              @OA\Property(property="licenseVersoUpdated",type="string",example="2001-12-11"),
     *              @OA\Property(property="idCardImageRecto",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *              @OA\Property(property="idCardRectoUpdated",type="string",example="2001-12-11"),
     *              @OA\Property(property="idCardImageVerso",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *              @OA\Property(property="idCardVersoUpdated",type="string",example="2001-12-11"),
     * 
     * 
     *              @OA\Property(
     *                    type="array",
     *                    property="bookings",
     *                    @OA\Items(
     *                        type="object",
     *                        @OA\Property(property="id",type="number",example="1"),
    *                         @OA\Property(property="rideId",type="number",example="1"),
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
     *                @OA\Property(
     *                      type="array",
     *                       property="rides",
     *                       @OA\Items(
     *                            type="object",
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
     *                 @OA\Property(
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
     *                   ),
     * 
     *                 @OA\Property(
     *                          type="object",
     *                          property="vehicle",
     *                           
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
     *                  @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                  @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(User $user, Request $request)
    {
        return new UserResource($user);
    }

    /**
     * Update User
     * @OA\Put (
     *     path="/api/users/{id}",
     *     tags={"Users"},
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
     *                          @OA\Property(property="username", type="string", example="example@gmail.com"),
     *                          @OA\Property(property="fname",type="string",example="ateba otabela"),
     *                          @OA\Property(property="lname",type="string",example="hermann ryan"),
     *                          @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *                          @OA\Property(property="birthDate",type="string",example="2021-12-11"),
     *                          @OA\Property(property="age",type="number",example="20"),
     *                          @OA\Property(property="phoneNumber",type="string",example="+33 6202112101"),
     *                          @OA\Property(property="sex",type="string",example="m"),
     *                          @OA\Property(property="role",type="string",example="[ROLE_USER]"),
     *                          @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
     *                          @OA\Property(property="isDetourPossible",type="nummber",example="1"),
     * 
     *                         @OA\Property(property="receivingNewsPapers",type="nummber",example="1"),
     * 
     *                 ),
     *                 example={
     *                     "username":"username",
     *                     "fname":"first name",
     *                     "lname":"last name",
     *                     "imageName":"http://127.0.0.1:8000/storage/userImages/example.png",
     *                     "birthDate":"2001-12-03",
     *                     "phoneNumber":"+33 6763245239",
     *                     "sex":"m",
     *                     "role":"[ROLE_USER]",
     *                     "isAcceptedAutomatically":"0",
     *                     "isDetourPossible":"1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                          @OA\Property(property="username", type="string", example="example@gmail.com"),
     *                          @OA\Property(property="fname",type="string",example="ateba otabela"),
     *                          @OA\Property(property="lname",type="string",example="hermann ryan"),
     *                          @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *                          @OA\Property(property="birthDate",type="string",example="2021-12-11"),
     *                          @OA\Property(property="phoneNumber",type="string",example="+33 6202112101"),
     *                          @OA\Property(property="sex",type="string",example="m"),
     *                          @OA\Property(property="role",type="string",example="[ROLE_USER]"),
     *                          @OA\Property(property="isAcceptedAutomatically",type="number",example="0"),
     *                          @OA\Property(property="isDetourPossible",type="nummber",example="1"),
     *          )
     *      )
     * )
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
    }

    
       /**
     * Update User Profile Image
     * @OA\Post (
     *     path="/api/users/{id}/image",
     *     tags={"Users"},
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
     *                          @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *                 ),
     *                 example={
     *                     "imageName":"http://127.0.0.1:8000/storage/userImages/example.png",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/userImages/example.png"),
     *          )
     *      )
     * )
     */
    public function setUserImage(Request $request, $id) {
        try{
            $user = User::find($id);
            if($request->hasFile('imageName')){
                
                $path = Storage::putFile('public/userImages', $request->imageName);
  
                $user->imageName = substr_replace($path, 'storage', 0, 6);
                $user->save();
                return response()->json(['path' =>env('APP_HOST_NAME').'/'. $user->imageName]);

            }
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }


           /**
     * Update User Driver's License Recto Image
     * @OA\Post (
     *     path="/api/users/{id}/license/recto",
     *     tags={"Users"},
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
     *                          @OA\Property(property="licenseImageRecto",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *                 ),
     *                 example={
     *                     "licenseImageRecto":"http://127.0.0.1:8000/storage/licenseImages/example.png",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="licenseImageRecto",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *          )
     *      )
     * )
     */
    public function setLicenseImageRecto(Request $request, $id) {
        try{
            $user = User::find($id);
            if($request->hasFile('licenseImageRecto')){
                
                $path = Storage::putFile('public/licenseImages', $request->licenseImageRecto);
  
                $user->licenseImageRecto = substr_replace($path, 'storage', 0, 6);
                $user->licenseRectoUpdated = now();

                $user->save();
                return response()->json(['licenseImageRecto' =>env('APP_HOST_NAME').'/'. $user->licenseImageRecto]);

            }
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }


    
           /**
     * Update User Driver's Licence Verso Image
     * @OA\Post (
     *     path="/api/users/{id}/license/verso",
     *     tags={"Users"},
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
     *                          @OA\Property(property="licenseImageVerso",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *                 ),
     *                 example={
     *                     "licenseImageVerso":"http://127.0.0.1:8000/storage/licenseImages/example.png",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="licenseImageVerso",type="string",example="http://127.0.0.1:8000/storage/licenseImages/example.png"),
     *          )
     *      )
     * )
     */
    public function setLicenseImageVerso(Request $request, $id) {
        try{
            $user = User::find($id);
            if($request->hasFile('licenseImageVerso')){
                
                $path = Storage::putFile('public/licenseImages', $request->licenseImageVerso);
                
                $user->licenseImageVerso = substr_replace($path, 'storage', 0, 6);
                $user->licenseVersoUpdated = now();
                $user->save();
                return response()->json(['licenseImageVerso' =>env('APP_HOST_NAME').'/'. $user->licenseImageVerso]);

            }
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }


    /**
     * Update User ID card's recto Image
     * @OA\Post (
     *     path="/api/users/{id}/idcard/recto",
     *     tags={"Users"},
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
     *                          @OA\Property(property="idCardImageRecto",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *                 ),
     *                 example={
     *                     "idCardImageRecto":"http://127.0.0.1:8000/storage/idCardImages/example.png",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="idCardImageRecto",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *          )
     *      )
     * )
     */
    public function setIdCardImageRecto(Request $request, $id) {
        try{
            $user = User::find($id);
            if($request->hasFile('idCardImageRecto')){
                
                $path = Storage::putFile('public/idCardImages', $request->idCardImageRecto);
  
                $user->idCardImageRecto = substr_replace($path, 'storage', 0, 6);
                $user->idCardRectoUpdated = now();

                $user->save();
                return response()->json(['idCardImageRecto' =>env('APP_HOST_NAME').'/'. $user->idCardImageRecto]);

            }
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }


    
           /**
     * Update User ID card's verso Image
     * @OA\Post (
     *     path="/api/users/{id}/idcard/verso",
     *     tags={"Users"},
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
     *                          @OA\Property(property="idCardImageVerso",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *                 ),
     *                 example={
     *                     "idCardImageVerso":"http://127.0.0.1:8000/storage/idCardImages/example.png",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="idCardImageVerso",type="string",example="http://127.0.0.1:8000/storage/idCardImages/example.png"),
     *          )
     *      )
     * )
     */
    public function setIdCardImageVerso(Request $request, $id) {
        try{
            $user = User::find($id);
            if($request->hasFile('idCardImageVerso')){
                
                $path = Storage::putFile('public/idCardImages', $request->idCardImageVerso);
  
                $user->idCardImageVerso = substr_replace($path, 'storage', 0, 6);
                $user->idCardVersoUpdated = now();

                $user->save();
                return response()->json(['idCardImageVerso' =>env('APP_HOST_NAME').'/'. $user->idCardImageVerso]);

            }
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }



    /**
     * Delete User
     * @OA\Delete (
     *     path="/api/users/{id}",
     *     tags={"Users"},
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
     *             @OA\Property(property="msg", type="string", example="delete user success")
     *         )
     *     )
     * )
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(["msg"=>"delete user success"]);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }


    // function getUserRides($id){
    //     return new RideCollection(User::find($id)->rides);
    // }

    // function getUserBookings($id){
    //     return new BookingCollection(User::find($id)->bookings);
    // }

}
