<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'admins_profile';

    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'phone',
        'sex',
        'nationality',
        'admin_id',
        'city',
        'address',
        'description',
        'status',
        'dob'
    ];

    public function admin()
    {
        return $this->belongsTo('App\Model\Admin\Admin');
    }

    public function getHumanCreatedAt()
    {
        return $this->created_at->diffForHumans();
    }
}
