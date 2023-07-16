<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'rideId'=>['required','numeric'],
            'isPrivate'=>['required','numeric'],
            'note'=>['required','numeric'],
            'content'=>['required','string'],
        ];
    }
    protected function prepareForValidation(){
        
        $this->merge([
            'user_id'=>$this->userId,
            'ride_id'=>$this->rideId
        ]);
        
    }
}
