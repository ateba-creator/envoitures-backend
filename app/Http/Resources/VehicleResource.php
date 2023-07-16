<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class VehicleResource extends JsonResource
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
            'designation'=>$this->designation,
            'description'=>$this->description,
            'imageName'=>env('APP_HOST_NAME')."/".$this->imageName,
            'isMusicAllowed'=>$this->isMusicAllowed,
            'isAnimalAllowed'=>$this->isAnimalAllowed,
            'isBagAllowed'=>$this->isBagAllowed,
            'isFoodAllowed'=>$this->isFoodAllowed,
            'createdAt'=>$this->created_at,
            'updatedAt'=>$this->updated_at,
            // 'user'=>new UserResource($this->user),
        ];
    }
}
