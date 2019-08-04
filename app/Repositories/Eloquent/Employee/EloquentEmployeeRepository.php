<?php

namespace App\Repositories\Eloquent\Employee;

use App\Model\Employee\Employee;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Employee\EmployeeRepository;

class EloquentEmployeeRepository extends RepositoryAbstract implements EmployeeRepository
{
    public function entity()
    {
        return Employee::class;
    }
}