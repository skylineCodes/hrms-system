<?php

namespace App\Repositories\Contracts\Password;

interface PasswordResetRepository
{
    public function updateOrCreate(array $identifier, array $properties);
    public function passwordReset(array $passwordInfo);
}