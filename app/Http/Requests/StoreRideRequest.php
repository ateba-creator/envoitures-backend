<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'userId'=>['required','numeric'],
            'start'=>['required','string'],
            'end'=>['required','string'],
            'price'=>['required','numeric'],
            'startAt'=>['required','string'],
            'status'=>['nullable','numeric'],
            'placesNumber'=>['required','numeric'],
            'passengerNumber'=>['required','numeric'],
            'twoPlaces'=>['required','numeric'],
            'acceptAuctions'=>['required','numeric'],
            'isDetourAllowed'=>['required','numeric'],
            'canBook'=>['nullable','numeric'],
        ];
    }


    protected function prepareForValidation(){
        
        $this->merge([
            'user_id'=>$this->userId,
        ]);
    }
}
