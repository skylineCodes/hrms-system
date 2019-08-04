<?php

namespace App\Repositories\Eloquent\Admin;

use App\Model\Admin\Profile;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Admin\ProfileRepository;

class EloquentProfileRepository extends RepositoryAbstract implements ProfileRepository
{
    public function entity()
    {
        return Profile::class;
    }
}