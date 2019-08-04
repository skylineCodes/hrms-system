<?php

namespace App\Model\Leave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'leave_type_id',
        'employee_id',
        'started_at',
        'ended_at',
        'description',
        'approved_by',
        'approved_at',
        'no_of_days',
        'status',
        'applied_on'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $dates = ['started_at', 'ended_at', 'applied_on', 'approved_at'];

    public function employee()
    {
        return $this->belongsTo('App\Model\Employee\Employee');
    }

    public function leaveType()
    {
        return $this->belongsTo('App\Model\Leave\LeaveType');
    }

    public function getHumanCreatedAt()
    {
        return $this->created_at->diffForHumans();
    }

    public function getHumanStartedAt()
    {
        return $this->started_at->format('d-M-Y');
    }

    public function getHumanEndedAt()
    {
        return $this->ended_at->format('d-M-Y');
    }

    public function getHumanApprovedAt()
    {
        return $this->approved_at->format('d-M-Y');
    }

    public function getHumanAppliedOn()
    {
        return $this->applied_on->format('d-M-Y');
    }

    public function getStatus()
    {
        if ($this->status === "1")
        {
            return "Accepted";
        } elseif ($this->status === "2")
        {
            return "Rejected";
        } else {
            return "Pending";
        }
    }
}
