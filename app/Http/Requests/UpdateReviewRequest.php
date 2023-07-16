<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
                'rideId'=>['required','numeric'],
                'isPrivate'=>['required','numeric'],
                'note'=>['required','numeric'],
                'content'=>['required','string'],
            ];
        }else{
            return [
                'userId'=>['sometimes','required','numeric'],
                'rideId'=>['sometimes','required','numeric'],
                'isPrivate'=>['sometimes','required','numeric'],
                'note'=>['sometimes','required','numeric'],
                'content'=>['sometimes','required','string'],
            ];
        }
    }

    protected function prepareForValidation(){
        
        $this->merge([
            'user_id'=>$this->userId,
            'review_id'=>$this->reviewId
        ]);
        
    }
}
