<?php

namespace App\Repositories\Eloquent\Acl;

use Spatie\Permission\Models\Role;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Acl\RoleRepository;

class EloquentRoleRepository extends RepositoryAbstract implements RoleRepository
{
    public function entity()
    {
        return Role::class;
    }
}