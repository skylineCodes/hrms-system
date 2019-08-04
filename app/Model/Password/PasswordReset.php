<?php

namespace App\Model\Password;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{    
    protected $fillable = [
        'email', 'token',
    ];
}
