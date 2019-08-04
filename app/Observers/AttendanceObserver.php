<?php

namespace App\Observers;

use App\Model\Attendance\Attendance;

class AttendanceObserver
{
        /**
     * Handle the Attendance "creating" event.
     *
     * @param  \App\Attendance  $Attendance
     * @return void
     */
    public function creating(Attendance $attendance)
    {
        if(!empty($attendance->time_in))
        {
            $attendance->status = 1;
        }
    }

    /**
     * Handle the Attendance "updating" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function updating(Attendance $attendance)
    {
        if (!empty($attendance->time_out))
        {
            $attendance->total_hours = $attendance->time_in->diffInHours($attendance->time_out);
        }
    }

    /**
     * Handle the Attendance "deleting" event.
     *
     * @param  \App\Attendance  $attendance
     * @return void
     */
    public function deleting(Admin $attendance)
    {
        //
    }
}
