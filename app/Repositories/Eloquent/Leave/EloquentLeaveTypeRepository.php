<?php

namespace App\Repositories\Eloquent\Leave;

use App\Model\Leave\LeaveType;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Leave\LeaveTypeRepository;

class EloquentLeaveTypeRepository extends RepositoryAbstract implements LeaveTypeRepository
{
    public function entity()
    {
        return LeaveType::class;
    }
}