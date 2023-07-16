<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'designation'=>['required','string'],
            'description'=>['required','string'],
            'imageName'=>['required','file'],
            'isMusicAllowed'=>['required','numeric'],
            'isAnimalAllowed'=>['required','numeric'],
            'isBagAllowed'=>['required','numeric'],
            'isFoodAllowed'=>['required','numeric'],
        ];
    }

    protected function prepareForValidation(){
        
        $this->merge([
            'user_id'=>$this->userId,
        ]);
    }
}
