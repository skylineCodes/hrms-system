<?php

namespace App\Repositories\Eloquent\Salary;

use App\Model\Salary\Salary;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Salary\SalaryRepository;

class EloquentSalaryRepository extends RepositoryAbstract implements SalaryRepository
{
    public function entity()
    {
        return Salary::class;
    }
}