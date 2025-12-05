<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function accessPanel(User $user)
    {
        return $user->role === 'admin';
    }
}
