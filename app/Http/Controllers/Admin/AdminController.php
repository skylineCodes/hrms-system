<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Employee\EmployeeRepository;

class AdminController extends Controller
{
    public $employee;

    public function __construct(EmployeeRepository $employee)
    {
        $this->employee = $employee;
    }

    public function employees()
    {
        dd($this->employee->all());
    }
}
