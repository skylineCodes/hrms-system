<?php

namespace App\Policies\Salary;

use App\Model\Admin\Admin;
use App\Salary;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any salaries.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @return mixed
     */
    public function viewAny(Admin $admin)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view the salary.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Salary  $salary
     * @return mixed
     */
    public function view(Admin $admin)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create salaries.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @return mixed
     */
    public function create(Admin $admin)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the salary.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Salary  $salary
     * @return mixed
     */
    public function update(Admin $admin)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the salary.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Salary  $salary
     * @return mixed
     */
    public function delete(Admin $user, Salary $salary)
    {
        //
    }

    /**
     * Determine whether the user can restore the salary.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Salary  $salary
     * @return mixed
     */
    public function restore(Admin $user, Salary $salary)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the salary.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Salary  $salary
     * @return mixed
     */
    public function forceDelete(Admin $admin)
    {
        if ($admin->getRoleNames()->first() == "superadmin" || $admin->getRoleNames()->first() == "admin" || $admin->getRoleNames()->first() == "hr")
        {
            return true;
        } else {
            return false;
        }
    }
}
