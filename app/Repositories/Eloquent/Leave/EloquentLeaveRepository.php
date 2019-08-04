<?php

namespace App\Repositories\Eloquent\Leave;

use App\Model\Leave\Leave;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Leave\LeaveRepository;

class EloquentLeaveRepository extends RepositoryAbstract implements LeaveRepository
{
    public function entity()
    {
        return Leave::class;
    }
}