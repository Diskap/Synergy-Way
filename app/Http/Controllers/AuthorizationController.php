<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:60', 'min:2'],
            'last_name' => ['required', 'string', 'max:60', 'min:2'],
            'login' => ['required', 'max:25', 'min:2', 'unique:users', 'regex:/^\S*$/u'],
            'password' => ['required', 'string', 'min:8'],
            'country_id' => ['required']
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->login = $request->login;
        $user->password = Hash::make($request->password);
        $user->country_id = $request->country_id;
        if (isset($request->roles_id)) {
            $user->roles_id = $request->roles_id;
        }
        elseif (count(User::all()) === 0) {
            $user->roles_id = 1;
        }
        else {
            $user->roles_id = 2;
        }
        $user->save();
    }

    public function login(Request $request)
    {

        $request->validate([
            'login' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($request->only('login', 'password'))) {
            return response()->json(Auth::user(), 200);
        }
        throw ValidationException::withMessages([
            'login' => ['The provided credential are incorrect.']
        ]);
    }

    public function logout()
    {
        Auth::logout();
    }
}
