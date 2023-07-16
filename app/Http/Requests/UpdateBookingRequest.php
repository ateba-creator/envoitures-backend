<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
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
                'price'=>['required','numeric'],
                'userId'=>['required','numeric'],
                'rideId'=>['required','numeric'],
            ];
        }else{
            return [
                'price'=>['sometimes','required','numeric'],
                'userId'=>['sometimes','required','numeric'],
                'rideId'=>['sometimes','required','numeric'],
            ];
        }
    }

    protected function prepareForValidation(){
        
        $this->merge([
            'user_id'=>$this->userId,
            'ride_id'=>$this->rideId
        ]);
    }
}
