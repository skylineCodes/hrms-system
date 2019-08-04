<?php

namespace App\Model\Employee;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable, SoftDeletes;

    protected $guard = 'employeeapi';

    protected $guard_name = 'adminapi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email', 
        'password', 
        'region', 
        'employee_code',
        'firstname',
        'lastname',
        'gender',
        'nationality',
        'job_title',
        'address',
        'city',
        'country',
        'province',
        'work_phone',
        'employment_status',
        'work_email',
        'joined_date',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getHumanCreatedAt()
    {
        return $this->created_at->diffForHumans();
    }

    public function profile() {
        return $this->hasOne('App\Model\Employee\Profile');
    }

    public function department()
    {
       return $this->belongsTo('App\Model\Department\Department');
    }

    /**
     * Get the employee leaves
     *
     * @return HasMany
     */
    public function leaves()
    {
        return $this->hasMany('App\Model\Leave\Leave');
    }

    /**
     * Get the employee attendance
     * 
     * @return HasOne
     */
    public function attendance()
    {
        return $this->hasMany('App\Model\Attendance\Attendance');
    }

    /**
     * Get the employee salary
     * 
     * @return HasOne
     */
    public function salary()
    {
        return $this->hasOne('App\Model\Salary\Salary');
    }
}
