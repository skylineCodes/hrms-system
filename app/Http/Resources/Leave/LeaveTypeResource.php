<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveTypeResource extends JsonResource
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
            'leave_type' => $this->leave_type,
            'leaves_per_day' => ($this->leaves_per_day) ? $this->leaves_per_day : "Not specified"
        ];
    }
}
