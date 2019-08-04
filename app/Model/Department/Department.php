<?php

namespace App\Model\Department;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function parent()
    {
        return $this->hasOne(Department::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id', 'id');
    }

    public function employee()
    {
        return $this->hasMany('App\Model\Employee\Employee');
    }

    public function getHumanCreatedAt()
    {
        return $this->created_at->diffForHumans();
    }
}
