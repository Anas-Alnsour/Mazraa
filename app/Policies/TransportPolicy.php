<?php

namespace App\Policies;

use App\Models\Transport;
use App\Models\User;

class TransportPolicy
{
    public function view(User $user, Transport $transport)
    {
        return $user->id === $transport->user_id;
    }

    public function update(User $user, Transport $transport)
    {
        return $user->id === $transport->user_id;
    }

    public function delete(User $user, Transport $transport)
    {
        return $user->id === $transport->user_id;
    }
}
