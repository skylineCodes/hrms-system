<?php

namespace App\Repositories\Eloquent\Employee;

use App\Model\Employee\Profile;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Employee\ProfileRepository;

class EloquentProfileRepository extends RepositoryAbstract implements ProfileRepository
{
    public function entity()
    {
        return Profile::class;
    }
}