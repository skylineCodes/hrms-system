<?php

namespace App\Model\Attendance;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    // use softDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'total_hours',
        'status',
        'month',
        'year',
        'location',
        'ip_address',
    ];

    protected $dates = ['time_in', 'time_out'];

    /**
     * Get the employee
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Model\Employee\Employee');
    }
}
