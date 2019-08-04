<?php

namespace App\Observers;

use App\Model\Employee\Employee;

class EmployeeObserver
{
    /**
     * Handle the Employee "creating" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function creating(Employee $employee)
    {
        if (isset($employee->region))
        {
            $employee->employee_code = $this->generatePIN('AC', $employee->region);
            $employee->employment_status = 'active';
            $employee->joined_date = now();
        }
    }

    /**
     * Handle the Employee "updating" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function updating(Employee $employee)
    {
        //
    }

    /**
     * Handle the Employee "deleting" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function deleting(Employee $employee)
    {
        //
    }

    public function generatePIN($prefix, $region, $digits = 4){
        $i = 0;
        $pin = "";
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin = $prefix.$region.mt_rand(1000,9999);
            $i++;
        }
        return $pin;
    }
}
