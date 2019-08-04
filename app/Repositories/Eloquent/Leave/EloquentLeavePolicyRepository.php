<?php

namespace App\Repositories\Eloquent\Leave;

use App\Model\Leave\LeavePolicy;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Leave\LeavePolicyRepository;

class EloquentLeavePolicyRepository extends RepositoryAbstract implements LeavePolicyRepository
{
    public function entity()
    {
        return LeavePolicy::class;
    }
}