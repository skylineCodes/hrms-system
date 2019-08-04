<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Employee\EmployeeProfileResource;

class AttendanceResource extends JsonResource
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
            'employee' => new EmployeeProfileResource($this->employee),
            'date' => $this->date,
            'time_in' => $this->time_in->format('h:i:s'),
            'time_out' => ($this->time_out) ? $this->time_out->format('h:i:s') : "Not time out yet",
            'total_hours' => ($this->total_hours != NULL) ? $this->total_hours : "Not available",
            'month' => date('M', mktime(0, 0, 0, $this->month, 10)),
            'year' => $this->year,
            'status' => ($this->status) ? "Active" : "Inactive",
            'Ip_address' => $this->ip_address,
            'clock_in_location' => $this->location
        ];
    }
}
