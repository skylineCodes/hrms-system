<?php

namespace App\Http\Resources\Leave;

use App\Http\Resources\Leave\LeaveTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Employee\EmployeeProfileResource;

class LeaveResource extends JsonResource
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
            'leave_type' => new LeaveTypeResource($this->leaveType),
            'employee' => new EmployeeProfileResource($this->employee),
            'start_date' => $this->getHumanStartedAt(),
            'end_date' => $this->getHumanEndedAt(),
            'reasons' => $this->description,
            'approved_by' => ($this->approved_by) ? $this->approved_by : 'Not approved yet',
            'approved_at' => ($this->approved_at) ? $this->getHumanApprovedAt() : 'Not approved yet',
            'no_of_days' => $this->no_of_days,
            'status' => $this->getStatus(),
            'applied_on' => $this->getHumanAppliedOn(),
            'created' => $this->getHumanCreatedAt()
        ];
    }
}
