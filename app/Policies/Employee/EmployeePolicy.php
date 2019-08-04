<?php

namespace App\Policies\Employee;

use App\Model\Admin\Admin;
use App\Model\Employee\Employee;
use App\Model\Employee\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view the employee.
     *
     * @param  \App\Model\Admin\Admin  $admin
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function view(Admin $admin, Employee $employee)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } elseif ($employee->profile->contains( $profile->id )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the admin can create employees.
     *
     * @param  \App\Model\Admin\Admin  $admin
     * @return mixed
     */
    public function store(Admin $admin)
    {
        $authorize = $admin->hasRole("hr");
    }

    /**
     * Determine whether the admin can update the employee.
     *
     * @param  \App\Model\Admin\Admin  $admin
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function update(Admin $admin, Employee $employee)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the admin can update the employee.
     *
     * @param  \App\Model\Admin\Admin  $admin
     * @param  \App\Model\Employee\Employee  $employee
     * @return mixed
     */
    public function employeeUpdate(Employee $employee)
    {
        return $employee->id === $employee->profile->employee_id;
    }

    /**
     * Determine whether the user can delete the employee.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function delete(Admin $user, Employee $employee)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the employee.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function restore(Admin $user, Employee $employee)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the employee.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function forceDelete(Admin $user, Employee $employee)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }
}
