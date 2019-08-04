<?php

namespace App\Providers;

use App\Observers\AdminObserver;
use App\Observers\AttendanceObserver;
use App\Observers\EmployeeObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \App\Model\Department\Department::creating(function ($department) {
            $department->slug = str_slug($department->name);
        });
        \App\Model\Department\Department::updating(function ($department) {
            $department->slug = str_slug($department->name);
        });
        \App\Model\Admin\Admin::observe(AdminObserver::class);
        \App\Model\Attendance\Attendance::observe(AttendanceObserver::class);
        \App\Model\Employee\EMployee::observe(EmployeeObserver::class);
    }
}
