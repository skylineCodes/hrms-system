<?php

namespace App\Model\Salary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'salary_amount'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Model\Employee\Employee');
    }
}
