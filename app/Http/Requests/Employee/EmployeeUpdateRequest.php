<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
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
            'username' => 'sometimes|required',
            'firstname' => 'sometimes|required',
            'lastname' => 'sometimes|required',
            'email' => 'sometimes|email',
            'address' => 'sometimes|required',
            'city' => 'sometimes|required|string',
            'country' => 'sometimes|required',
            'work_phone' => 'sometimes|required|numeric',
            'work_email' => 'sometimes|email',
            'mobile_phone' => 'sometimes',
            'birthday' => 'sometimes|date_format:d-M-Y',
            'marital_status' => 'sometimes',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'This email format is incorrect!',
            'work_phone.numeric' => 'The work_phone field should be numeric',
            'work_email.email' => 'This work_email format is incorrect!',
            'birthday.date_format' => 'The birthday should be in this format 08-Dec-2000'
        ];
    }
}
