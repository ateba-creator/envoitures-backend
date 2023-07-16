<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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

        if ($method == 'PUT'){
            return [
                'fname'=>['required','string'],
                'lname'=>['required','string'],
                'username'=>['required','unique:users'],
                'birthDate'=>['required','date'],
                'imageName'=>['nullable','file'],
                'phoneNumber'=>['required','string','max:15'],
                'sex'=>['required','string'],
                'role'=>['nullable'],
                'isAcceptedAutomatically'=>['nullable','numeric'],
                'isDetourPossible'=>['nullable','numeric'],
                'paymentAccount'=>['nullable','string'],
                'facebookId'=>['nullable'],
                'googleId'=>['nullable'],
                'password'=>['required','string']
            ];
        }else{
            return [
                'fname'=>['sometimes','required','string'],
                'lname'=>['sometimes','required','string'],
                'username'=>['sometimes','required','unique:users'],
                'birthDate'=>['sometimes','required','date'],
                'imageName'=>['sometimes','nullable','file'],
                'phoneNumber'=>['sometimes','required','string','max:15'],
                'sex'=>['sometimes','required','string'],
                'role'=>['sometimes','nullable'],
                'isAcceptedAutomatically'=>['sometimes','nullable','numeric'],
                'isDetourPossible'=>['sometimes','nullable','numeric'],
                'paymentAccount'=>['sometimes','nullable','string'],
                'facebookId'=>['sometimes','nullable'],
                'googleId'=>['sometimes','nullable'],
                'password'=>['sometimes','required','string']
            ];
            }
    }

    // protected function prepareForValidation(){
    //     if($this->postalCode){
    //         $this->merge([
    //             'postal_code'=>$this->postalCode
    //         ]);
    //     }
    // }
}
