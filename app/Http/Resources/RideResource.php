<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\BookingResource;
use App\Http\Resources\StepResource;

class RideResource extends JsonResource
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
            'start'=>$this->start,
            'end'=>$this->end,
            'price'=>$this->price,
            'startAt'=>$this->startAt,
            'status'=>$this->status,
            'type'=>$this->type,
            'placesNumber'=>$this->placesNumber,
            'passengerNumber'=>$this->passengerNumber,
            'twoPlaces'=>$this->twoPlaces,
            'acceptAuctions'=>$this->acceptAuctions,
            'isDetourAllowed'=>$this->isDetourAllowed,
            'isMusicAllowed'=>$this->isMusicAllowed,
            'isAnimalAllowed'=>$this->isAnimalAllowed,
            'isFoodAllowed'=>$this->isFoodAllowed,
            'isBagesAllowed'=>$this->isBagageAllowed,
            'steps'=>StepResource::collection($this->steps),
            'canBook'=>$this->canBook,
            'views'=>$this->views,
            'canBook'=>$this->canBook,
            'createdAt'=>$this->created_at,
            'updatedAt'=>$this->updated_at,

            'steps'=>StepResource::collection($this->steps),
            'user'=>[
                'id'=>$this->user->id,
                'username'=>$this->user->username,
                'fname'=>$this->user->fname,
                'lname'=>$this->user->lname,
                'birthDate'=>$this->user->birthDate,
                'age'=>$this->user->age,
                'phoneNumber'=>$this->user->phoneNumber,
                'sex'=>$this->user->sex,
                'paymentAccount'=>$this->user->paymentAccount,
                'role'=>$this->user->role,
                'imageName'=>env('APP_HOST_NAME')."/".$this->user->imageName,
                'licenseImageRecto'=>env('APP_HOST_NAME')."/".$this->user->licenseImageRecto,
                'licenseRectoUpdated'=>env('APP_HOST_NAME')."/".$this->user->licenseRectoUpdated,
                'licenseImageVerso'=>env('APP_HOST_NAME')."/".$this->user->licenseImageVerso,
                'licenseVersoUpdated'=>env('APP_HOST_NAME')."/".$this->user->licenseVersoUpdated,
                'idCardImageRecto'=>env('APP_HOST_NAME')."/".$this->user->idCardImageRecto,
                'idCardRectoUpdated'=>env('APP_HOST_NAME')."/".$this->user->idCardRectoUpdated,
                'idCardImageVerso'=>env('APP_HOST_NAME')."/".$this->user->idCardImageVerso,
                'idCardVersoUpdated'=>env('APP_HOST_NAME')."/".$this->user->idCardVersoUpdated,
                'receivingNewsPapers'=>$this->user->receivingNewsPapers,
                'isAcceptedAutomatically'=>$this->user->isAcceptedAutomatically,
                'isDetourPossible'=>$this->user->isDetourPossible,
            ],
            'bookings'=>BookingResource::collection($this->bookings),
            'reviews'=>BookingResource::collection($this->bookings),

        ];
    }
}
