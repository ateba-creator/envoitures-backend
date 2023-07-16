<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Filters\VehicleFilters;
use App\Http\Resources\VehicleCollection;
use App\Http\Resources\VehicleResource;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Get List of Vehicles
     * @OA\Get (
     *     path="/api/vehicles",
     *     tags={"Vehicles"},
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
     *                     @OA\Property(property="designation",type="string",example="Toyato"),
     *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
     *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
     *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
     *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
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
        $filter = new VehicleFilters();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $vehicles = Vehicle::where($filterItems);

        $includeUser = $request->query('includeUser');

        if($includeUser){
            $vehicles = $vehicles->with('user');
        }

        return new VehicleCollection($vehicles->paginate()->appends($request->query()));
    }

    /**
     * Create Vehicle
     * @OA\Post (
     *     path="/api/vehicles",
     *     tags={"Vehicles"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="designation",type="string",example="Toyato"),
     *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
     *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
     *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
     *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
     *                 ),
     *                 example={
     *                     "userId":"username",
     *                     "designation":"first name",
     *                     "description":"last name",
     *                     "imageName":"http://127.0.0.1:8000/storage/userImages/example.png",
     *                     "isMusicAllowed":"0",
     *                     "isAnimalAllowed":"1",
     *                     "isBagAllowed":"0",
     *                     "isFoodAllowed":"isFoodAllowed",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="designation",type="string",example="Toyato"),
     *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
     *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
     *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
     *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
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
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->all());

        if($request->imageName){
            $path = Storage::putFile('public/vehicleImages', $request->imageName);
            $vehicle->imageName = substr_replace($path, 'storage', 0, 6);
            $vehicle->save();
        }
        return (new VehicleResource($vehicle));
    }


        /**
     * Get Vehicle Details
     * @OA\Get (
     *     path="/api/vehicles/{id}",
     *     tags={"Vehicles"},
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
     *                     @OA\Property(property="designation",type="string",example="Toyato"),
     *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
     *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
     *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
     *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
     *                     @OA\Property(property="updated_at",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="created_at",type="string",example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(Vehicle $vehicle, Request $request)
    {
        return new VehicleResource($vehicle);
    }

    /**
     * Update Vehicle
     * @OA\Put (
     *     path="/api/vehicles/{id}",
     *     tags={"Vehicles"},
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
     *                     @OA\Property(property="designation",type="string",example="Toyato"),
     *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
     *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
     *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
     *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
     *                 ),
     *                 example={
     *                     "userId":"username",
     *                     "designation":"first name",
     *                     "description":"last name",
     *                     "imageName":"http://127.0.0.1:8000/storage/userImages/example.png",
     *                     "isMusicAllowed":"0",
     *                     "isAnimalAllowed":"1",
     *                     "isBagAllowed":"0",
     *                     "isFoodAllowed":"isFoodAllowed",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="designation",type="string",example="Toyato"),
     *                     @OA\Property(property="description",type="string",example="Toyota Avensis 2011"),
     *                     @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *                     @OA\Property(property="isMusicAllowed",type="number",example="0"),
     *                     @OA\Property(property="isAnimalAllowed",type="number",example="0"),
     *                     @OA\Property(property="isBagAllowed",type="number",example="1"),
     *                     @OA\Property(property="isFoodAllowed",type="number",example="0"),
     *          )
     *      )
     * )
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->all());
    }

    /**
     * Delete Vehicle
     * @OA\Delete (
     *     path="/api/vehicles/{id}",
     *     tags={"Vehicles"},
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
     *             @OA\Property(property="msg", type="string", example="delete vehicle success")
     *         )
     *     )
     * )
     */
    public function destroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();
            return response()->json(["msg"=>"delete vehicle success"]);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }


       /**
     * Update Vehicle Image
     * @OA\Post (
     *     path="/api/vehicles/{id}/setVehicleImage",
     *     tags={"Vehicles"},
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
     *                          @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
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
     *              @OA\Property(property="imageName",type="string",example="http://127.0.0.1:8000/storage/vehicleImages/example.png"),
     *          )
     *      )
     * )
     */
    public function setVehicleImage(Request $request, $id){
        $vehicle = Vehicle::find($id);
        if($request->hasFile('imageName')){

            $path = Storage::putFile('public/vehicleImages', $request->imageName);

            $vehicle->imageName = substr_replace($path, 'storage', 0, 6);
            $vehicle->save();
            return response()->json(['path' => env('APP_HOST_NAME').'/'. $vehicle->imageName]);
        }else{
            return response()->json(['msg'=>"Specify an image file"]);
        }
    }


    /**
     * Get Vehicle User
     * @OA\Get (
     *     path="/api/vehicles/{id}/user",
     *     tags={"Vehicles"},
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
     *              @OA\Property(property="phoneNumber",type="string",example="+33 6202112101"),
     *              @OA\Property(property="sex",type="string",example="m"),
     *              @OA\Property(property="role",type="string",example="[ROLE_USER]"),
     *              @OA\Property(property="password", type="string", example="password"),
     *         )
     *     )
     * )
     */

    public function getUser(Request $request, $id){
        $vehicle = Vehicle::find($id);
    
        return new UserResource($vehicle->user);
    }

}
