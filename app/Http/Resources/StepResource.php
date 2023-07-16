<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StepResource extends JsonResource
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
            'rideId'=>$this->ride_id,
            'designation'=>$this->designation,
            'createdAt'=>$this->createdAt,
            'updatedAt'=>$this->updatedAt,
        ];
    }
}
