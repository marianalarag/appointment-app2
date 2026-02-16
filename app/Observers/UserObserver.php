<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function updated(User $user): void
    {
        if ($user->hasRole('patient') && !$user->patient) {
            $user->patient()->create();
        }
    }
}
