<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePostRequest extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:employees',
            'region' => 'required|string',
            'gender' => 'required|string',
            'nationality' => 'required',
            'job_title' => 'required',
            'work_email' => 'required|email|unique:employees',
            'department_id' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'The firstname is required!',
            'lastname.required' => 'The lastname is required!',
            'email.required' => 'The email is required!',
            'email.email' => 'Not a valid email format!',
            'email.unique' => 'The email has to be unique!',
            'region.required' => 'The region is required!',
            'region.string' => 'The region has to be string format!',
            'gender.required' => 'The gender is required!',
            'gender.string' => 'The gender has to be string format!',
            'nationality.required' => 'The nationality field is required!',
            'job_title.required' => 'The job_title field is required!',
            'work_email.required' => 'The work_email is required!',
            'work_email.email' => 'Not a valid work_email format!',
            'work_email.unique' => 'The work_email has to be unique!',
            'department_id.required' => 'No department has been specified yet!'
        ];
    }
}
