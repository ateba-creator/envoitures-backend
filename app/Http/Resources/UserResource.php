<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RideResource;
use App\Http\Resources\VehicleResource;
use App\Http\Resources\ReviewResource;

class UserResource extends JsonResource
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
            'username'=>$this->username,
            'fname'=>$this->fname,
            'lname'=>$this->lname,
            'birthDate'=>$this->birthDate,
            'age'=>$this->age,
            'phoneNumber'=>$this->phoneNumber,
            'sex'=>$this->sex,
            'paymentAccount'=>$this->paymentAccount,
            'role'=>$this->role,
            'imageName'=>env('APP_HOST_NAME')."/".$this->imageName,
            
            'licenseImageRecto'=>env('APP_HOST_NAME')."/".$this->licenseImageRecto,
            'licenseRectoUpdated'=>env('APP_HOST_NAME')."/".$this->licenseRectoUpdated,
            'licenseImageVerso'=>env('APP_HOST_NAME')."/".$this->licenseImageVerso,
            'licenseVersoUpdated'=>env('APP_HOST_NAME')."/".$this->licenseVersoUpdated,

            'idCardImageRecto'=>env('APP_HOST_NAME')."/".$this->idCardImageRecto,
            'idCardRectoUpdated'=>env('APP_HOST_NAME')."/".$this->idCardRectoUpdated,
            'idCardImageVerso'=>env('APP_HOST_NAME')."/".$this->idCardImageVerso,
            'idCardVersoUpdated'=>env('APP_HOST_NAME')."/".$this->idCardVersoUpdated,

            'receivingNewsPapers'=>$this->receivingNewsPapers,
            'isAcceptedAutomatically'=>$this->isAcceptedAutomatically,
            'isDetourPossible'=>$this->isDetourPossible,
            'createdAt'=>$this->created_at,
            'updatedAt'=>$this->updated_at,

            'bookings'=>BookingResource::collection($this->bookings),
            'rides'=>RideResource::collection($this->rides),
            'reviews'=>ReviewResource::collection($this->reviews),
            'vehicle'=>new VehicleResource($this->vehicle)
        ];
        
        // 'vehicle'=>new VehicleResource($this->vehicle),
        // 'bookings'=>BookingResource::collection($this->bookings),
        // 'rides'=>RideResource::collection($this->rides),
        // 'reviews'=>ReviewResource::collection($this->reviews),
        
    }
}
