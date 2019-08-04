<?php

namespace App\Repositories\Eloquent\Password;

use App\Model\Password\PasswordReset;
use App\Repositories\RepositoryAbstract;
use App\Repositories\Contracts\Password\PasswordResetRepository;

class EloquentPasswordResetRepository extends RepositoryAbstract implements PasswordResetRepository
{
    public function entity()
    {
        return PasswordReset::class;
    }

    public function updateOrCreate(array $identifier, array $properties)
    {
        return $this->entity->updateOrCreate($identifier, $properties);
    }

    public function passwordReset(array $value)
    {
        return $this->entity->where($value)->first();
    }
}