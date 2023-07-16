<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\RideResource;


class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'=>$this->id,
            'userId'=>$this->user_id,
            'suggestedPrice'=>$this->suggestedPrice,
            'rideId'=>$this->ride_id,
            'validatedAt'=>$this->validatedAt,
            'payment'=>$this->payment,
            'paidAt'=>$this->paidAt,
            'fee'=>$this->fee,
            'isValidated'=>$this->isValidated,
            'status'=>$this->status,
            'createdAt'=>$this->created_at,
            'updatedAt'=>$this->updated_at,

            'user'=>new UserResource($this->whenLoaded('user')),
            'ride'=>new RideResource($this->whenLoaded('ride')),
        ];
    }
}
