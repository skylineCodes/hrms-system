<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'nationality' => $this->nationality,
            'job_title' => $this->job_title,
            'province' => $this->province,
            'department' => $this->department_id,
            'created' => $this->getHumanCreatedAt()
        ];
    }
}
