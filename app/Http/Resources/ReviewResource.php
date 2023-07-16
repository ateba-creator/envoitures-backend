<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class ReviewResource extends JsonResource
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
            'rideId'=>$this->ride_id,
            'isPrivate'=>$this->isPrivate,
            'note'=>$this->note,
            'content'=>$this->content,
            'createdAt'=>$this->created_at,
            'updatedAt'=>$this->updated_at,
            'user'=>new UserResource($this->whenLoaded('user')),
        ];
    }
}
