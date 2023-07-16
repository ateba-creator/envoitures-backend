<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'fname'=>['required','string'],
            'lname'=>['required','string'],
            'username'=>['required','unique:users'],
            'birthDate'=>['required','date'],
            'imageName'=>['nullable','file'],
            'phoneNumber'=>['required','string','max:20'],
            'isAcceptedAutomatically'=>['nullable','numeric'],
            'isDetourPossible'=>['nullable','numeric'],
            'sex'=>['required','string'],
            'role'=>['nullable'],
            'facebookId'=>['nullable'],
            'googleId'=>['nullable'],
            'password'=>['required','string']

        ];
    }

    
}
