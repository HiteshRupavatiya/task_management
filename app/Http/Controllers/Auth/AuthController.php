<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:30',
            'email'    => 'required|email|unique:users,email|max:40',
            'password' => 'required|min:5|max:20'
        ]);

        $user = User::create($request->only(
            [
                'name',
                'email'
            ]
        ) + [
            'password' => Hash::make($request->password)
        ]);

        return ok('User registered successfully', $user);
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($user)) {
            return error('Invalid user credentials', type: 'notfound');
        }

        $token = auth()->user()->createToken('Api token')->plainTextToken;

        return ok('Logged in successfully', $token);
    }
}
