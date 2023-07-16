<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Filters\ReviewFilters;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewCollection;
use App\Models\User;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;


class ReviewController extends Controller
{
    /**
     * Get List of Reviews
     * @OA\Get (
     *     path="/api/reviews",
     *     tags={"Reviews"},
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
     *                     @OA\Property(property="isPrivate",type="number",example="2"),
     *                     @OA\Property(property="note",type="number",example="4"),
     *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
     *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    
    public function index(Request $request)
    {
        $filter = new ReviewFilters();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $reviews = Review::where($filterItems);
        $includeUser = $request->query('includeUser');
        $includeBooking = $request->query('includeBooking');

        if($includeUser){
            $reviews = $reviews->with('user');
        }
        if($includeBooking){
            $reviews = $reviews->with('booking');
        }
        return new ReviewCollection($reviews->paginate()->appends($request->query()));
    }


    /**
     * Create Review
     * @OA\Post (
     *     path="/api/reviews",
     *     tags={"Reviews"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                     @OA\Property(property="id",type="number",example="1"),
     *                     @OA\Property(property="userId",type="number",example="1"),
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="isPrivate",type="number",example="2"),
     *                     @OA\Property(property="note",type="number",example="4"),
     *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
     *                 ),
     *                 example={
     *                     "userId":"2",
     *                     "rideId":"2",
     *                     "isPrivate":"0",
     *                     "note":"2",
     *                     "content":"This ride was absolutely fabulouse",
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
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="isPrivate",type="number",example="2"),
     *                     @OA\Property(property="note",type="number",example="4"),
     *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
     *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
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
    public function store(StoreReviewRequest $request)
    {
        return new ReviewResource(Review::create($request->all()));
    }


    /**
     * Get Review Details
     * @OA\Get (
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
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
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="isPrivate",type="number",example="2"),
     *                     @OA\Property(property="note",type="number",example="4"),
     *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
     *                     @OA\Property(property="updatedAt",type="string",example="2021-12-11T09:25:53.000000Z"),
     *                     @OA\Property(property="createdAt",type="string",example="2021-12-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show(Review $review)
    {
        return new ReviewResource($review);
    }


    /**
     * Update Review
     * @OA\Put (
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
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
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="isPrivate",type="number",example="2"),
     *                     @OA\Property(property="note",type="number",example="4"),
     *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
     *                 ),
     *                 example={
     *                     "userId":"2",
     *                     "rideId":"2",
     *                     "isPrivate":"0",
     *                     "note":"2",
     *                     "content":"This ride was absolutely fabulouse",
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
     *                     @OA\Property(property="rideId",type="number",example="1"),
     *                     @OA\Property(property="isPrivate",type="number",example="2"),
     *                     @OA\Property(property="note",type="number",example="4"),
     *                     @OA\Property(property="content",type="string",example="This ride was absolutely fabulouse"),
     *          )
     *      )
     * )
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        return new ReviewResource($review->update($request->all()));
    }


    /**
     * Delete Review
     * @OA\Delete (
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
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
     *             @OA\Property(property="msg", type="string", example="delete review success")
     *         )
     *     )
     * )
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();
            return response()->json(["msg"=>"delete review success"]);
        }catch (ModelNotFoundException $exception){
            return response()->json(["msg"=>$exception->getMessage()],404);
        }
    }
}
