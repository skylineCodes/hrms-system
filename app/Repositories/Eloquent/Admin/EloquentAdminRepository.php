<?php

namespace App\Repositories\Eloquent\Admin;

use App\Model\Admin\Admin;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Admin\AdminRepository;

class EloquentAdminRepository extends RepositoryAbstract implements AdminRepository
{
    public function entity()
    {
        return Admin::class;
    }

    // public function createAddress($adminId, array $properties)
    // {
    //     return $this->find($adminId)->addresses()->create($properties);
    // }

    // public function deleteAddress($adminId, $addressId)
    // {
    //     return $this->find($adminId)->addresses()->findOrFail($addressId)->delete();
    // }
}