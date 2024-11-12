<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can upload files.
     *
     * @return mixed
     */
    public function uploadFiles(?User $user)
    {
        return true;
    }
}
