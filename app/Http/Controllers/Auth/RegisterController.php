<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;

class RegisterController extends Controller
{
    public function create(RegisterRequest $request)
    {
        $user = User::create($request->only(['email', 'name', 'password']));
        return new PrivateUserResource($user);
    }
}
