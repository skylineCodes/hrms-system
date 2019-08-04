<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Model\Department\Department;
use App\Repositories\Criteria\CriterionInterface;

class IsParent implements CriterionInterface
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function apply($entity)
    {
        return $entity->whereNull('parent_id');
    }
}