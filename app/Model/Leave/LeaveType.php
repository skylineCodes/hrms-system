<?php

namespace App\Model\Leave;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['leave_type', 'leaves_per_day'];

    public function leave()
    {
        return $this->hasMany('App\Model\Leave\Leave');
    }
}
