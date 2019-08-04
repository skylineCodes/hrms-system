<?php

namespace App\Providers;

use App\Model\Salary\Salary;
use Laravel\Passport\Passport;
use App\Model\Employee\Employee;
use App\Model\Attendance\Attendance;
use Illuminate\Support\Facades\Gate;
use App\Policies\Salary\SalaryPolicy;
use App\Policies\Employee\EmployeePolicy;
use App\Policies\Attendance\AttendancePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        Attendance::class => AttendancePolicy::class,
        Salary::class => SalaryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::personalAccessClientId('1');

        Passport::tokensExpireIn(now()->addDays(2));

        Passport::refreshTokensExpireIn(now()->addDays(2));

        Passport::personalAccessTokensExpireIn(now()->addDays(2));
        
        Gate::before(function ($admin, $ability) {
            return $admin->hasRole('superadmin') ? true : null;
        });
    }
}
