<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Department\DepartmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DeactivatedEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'code' => $this->employee_code,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'gender' => $this->gender,
            'department' => new DepartmentResource($this->department),
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'work_phone' => $this->work_phone,
            'work_email' => $this->work_email,
            'mobile_phone' => $this->profile->mobile_phone,
            'birthday' => $this->profile->birthday,
            'marital_status' => $this->profile->marital_status,
            'postal_code' => $this->profile->postal_code,
            'home_phone' => $this->profile->home_phone,
            'emergency_contact' => $this->profile->emergency_contact,
            'private_email' => $this->profile->private_email,
            'created' => $this->getHumanCreatedAt()
        ];
    }
}
