<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Address $address)
    {
        return (int) $user->id === (int) $address->user_id;
    }

}
