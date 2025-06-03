<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user is a provider.
     */
    public function provider(User $user): bool
    {
        return $user->role === 'provider';
    }
} 