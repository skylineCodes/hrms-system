<?php

namespace App\Repositories\Eloquent\Attendance;

use App\Model\Attendance\Attendance;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Attendance\AttendanceRepository;

class EloquentAttendanceRepository extends RepositoryAbstract implements AttendanceRepository
{
    public function entity()
    {
        return Attendance::class;
    }
}