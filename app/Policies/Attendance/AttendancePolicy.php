<?php

namespace App\Policies\Attendance;

use App\Model\Admin\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view the attendance.
     *
     * @param  \App\Model\Admin\Admin  $admin
     * @param  \App\Attendance  $attendance
     * @return mixed
     */
    public function view(Admin $admin)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }
}
