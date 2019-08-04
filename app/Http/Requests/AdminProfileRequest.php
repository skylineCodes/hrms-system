<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'sometimes|required|max:100',
            'email' => 'sometimes|required|email',
            'firstname' => 'sometimes|required|string|max:100',
            'lastname' => 'sometimes|required|string|max:100',
            'middlename' => 'sometimes|required|string|max:100',
            'phone' => 'sometimes|numeric',
            'sex' => 'sometimes|required|string',
            'nationality' => 'sometimes|required|string',
            'city' => 'sometimes|required',
            'address' => 'sometimes|required',
            'dob' => 'sometimes|required|date_format:d-M-Y'
        ];
    }
}
