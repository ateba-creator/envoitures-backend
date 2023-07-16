<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRideRequest extends FormRequest
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
        $method = $this->method();
        if($method == 'PUT'){
            return [
                'userId'=>['required','numeric'],
                'start'=>['required','string'],
                'end'=>['required','string'],
                'price'=>['required','numeric'],
                'startAt'=>['required','string'],
                'status'=>['required','numeric'],
                'placesNumber'=>['required','numeric'],
                'twoPlaces'=>['required','numeric'],
                'acceptAuctions'=>['required','numeric'],
                'isDetourAllowed'=>['required','numeric'],
                'canBook'=>['required','numeric'],
                'views'=>['required','numeric'],


            ];
        }else{
            return [
                'userId'=>['sometimes','required','numeric'],
                'start'=>['sometimes','required','string'],
                'end'=>['sometimes','required','string'],
                'price'=>['sometimes','required','numeric'],
                'startAt'=>['sometimes','required','string'],
                'status'=>['sometimes','required','numeric'],
                'placesNumber'=>['sometimes','required','numeric'],
                'twoPlaces'=>['sometimes','required','numeric'],
                'acceptAuctions'=>['sometimes','required','numeric'],
                'isDetourAllowed'=>['sometimes','required','numeric'],
                'canBook'=>['sometimes','required','numeric'],
                'views'=>['sometimes','required','numeric'],

            ];
        }
    }


    protected function prepareForValidation(){
        if($this->userId){
            $this->merge([
                'user_id'=>$this->userId,
            ]);
        }
    }
}
