<?php

namespace App\Model\Employee;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'employees_profile';

    protected $fillable = [
        'employee_id',
        'mobile_phone',
        'birthday',
        'marital_status',
        'driver_license',
        'postal_code',
        'home_phone',
        'emergency_contact',
        'private_email',
    ];

    public function employee()
    {
        return $this->belongsTo('App\Model\Employee\Employee');
    }
}
