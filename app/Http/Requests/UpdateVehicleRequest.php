<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
                'designation'=>['required','string'],
                'description'=>['required','string'],
                'isMusicAllowed'=>['required','numeric'],
                'isAnimalAllowed'=>['required','numeric'],
                'isBagAllowed'=>['required','numeric'],
                'isFoodAllowed'=>['required','numeric'],
            ];
        }else{
            return [
                'userId'=>['sometimes','required','numeric'],
                'designation'=>['sometimes','required','string'],
                'description'=>['sometimes','required','string'],
                'isMusicAllowed'=>['sometimes','required','numeric'],
                'isAnimalAllowed'=>['sometimes','required','numeric'],
                'isBagAllowed'=>['sometimes','required','numeric'],
                'isFoodAllowed'=>['sometimes','required','numeric'],
            ];
        }
    }

    protected function prepareForValidation(){
        
        $this->merge([
            'user_id'=>$this->userId,
        ]);
    }
}
