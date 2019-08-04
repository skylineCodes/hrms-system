<?php

namespace App\Repositories\Eloquent\Department;

use App\Model\Department\Department;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Department\DepartmentRepository;

class EloquentDepartmentRepository extends RepositoryAbstract implements DepartmentRepository
{
    public function entity()
    {
        return Department::class;
    }
}