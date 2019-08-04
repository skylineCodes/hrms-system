<?php

namespace App\Http\Resources\Attendance;

use App\Http\Resources\Attendance\AttendanceResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => AttendanceResource::collection($this->collection)
        ];
    }
}
