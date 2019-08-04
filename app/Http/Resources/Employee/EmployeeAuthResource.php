<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAuthResource extends JsonResource
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
            'email' => $this->email,
            'code' => $this->employee_code,
            'job_title' => $this->job_title,
            'region' => ($this->region === 'NG') ? "Nigeria" : "United Kingdom",
            'role' => ($this->getRoleNames()) ? $this->getRoleNames() : "None assigned",
            'created' => $this->getHumanCreatedAt()
        ];
    }
}
