<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Contracts
 */
use App\Repositories\Contracts\Admin\{
    AdminRepository,
    ProfileRepository
};
use App\Repositories\Contracts\Acl\{
    RoleRepository
};
use App\Repositories\Contracts\Employee\{
    EmployeeRepository,
    ProfileRepository as EmployeeProfileRepository
};
use App\Repositories\Contracts\Department\DepartmentRepository;
use App\Repositories\Contracts\Password\PasswordResetRepository;
use App\Repositories\Contracts\Attendance\AttendanceRepository;
use App\Repositories\Contracts\Salary\SalaryRepository;
use App\Repositories\Contracts\Leave\{
    LeaveRepository,
    LeaveTypeRepository,
    LeavePolicyRepository
};

/**
 * Eloquent
 */
use App\Repositories\Eloquent\Admin\{
    EloquentAdminRepository,
    EloquentProfileRepository
};
use App\Repositories\Eloquent\Acl\{
    EloquentRoleRepository
};
use App\Repositories\Eloquent\Employee\{
    EloquentEmployeeRepository,
    EloquentProfileRepository as EloquentEmployeeProfileRepository
};
use App\Repositories\Eloquent\Salary\EloquentSalaryRepository;
use App\Repositories\Eloquent\Department\EloquentDepartmentRepository;
use App\Repositories\Eloquent\Password\EloquentPasswordResetRepository;
use App\Repositories\Eloquent\Attendance\EloquentAttendanceRepository;
use App\Repositories\Eloquent\Leave\{
    EloquentLeaveRepository,
    EloquentLeaveTypeRepository,
    EloquentLeavePolicyRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Admin Repository
         */
        $this->app->bind(AdminRepository::class, EloquentAdminRepository::class);
        $this->app->bind(ProfileRepository::class, EloquentProfileRepository::class);

        /**
         * Acl - Role
         */
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);

        /**
         * Employee Repository
         */
        $this->app->bind(EmployeeRepository::class, EloquentEmployeeRepository::class);
        $this->app->bind(EmployeeProfileRepository::class, EloquentEmployeeProfileRepository::class);

        /**
         * Password Reset (Employee) Repository
         */
        $this->app->bind(PasswordResetRepository::class, EloquentPasswordResetRepository::class);

        /**
         * Department Repository
         */
        $this->app->bind(DepartmentRepository::class, EloquentDepartmentRepository::class);

        /**
         * Leave Repository
         */
        $this->app->bind(LeaveRepository::class, EloquentLeaveRepository::class);
        $this->app->bind(LeaveTypeRepository::class, EloquentLeaveTypeRepository::class);
        $this->app->bind(LeavePolicyRepository::class, EloquentLeavePolicyRepository::class);

        /**
         * Attendance Repository
         */
        $this->app->bind(AttendanceRepository::class, EloquentAttendanceRepository::class);

        /**
         * Salary Repository
         */
        $this->app->bind(SalaryRepository::class, EloquentSalaryRepository::class);
    }
}
