<?php

namespace App\Model\Leave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePolicy extends Model
{
    use softDeletes;

    Protected $fillable = [
        'name',
        'description'
    ];

    public function getHumanCreatedAt()
    {
        return $this->created_at->diffForHumans();
    }
}
