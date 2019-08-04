<?php

namespace App\Http\Resources\Department;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Employee\EmployeeProfileResource;

class EmployeeDepartmentResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'created' => $this->getHumanCreatedAt(),
            'employees' => EmployeeProfileResource::collection($this->employee)
        ];
    }
}
