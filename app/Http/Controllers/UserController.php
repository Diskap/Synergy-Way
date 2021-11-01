<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\Country;
use App\Models\Roles;


class UserController extends Controller
{
    public function editUser(Request $request)
    {

        $id = $request->id;

        $user = User::findOrFail($id);

        $toValidate = [
            'first_name' => ['required', 'string', 'max:60', 'min:2'],
            'last_name' => ['required', 'string', 'max:60', 'min:2'],
            'country_id' => ['required'],
            'roles_id' => ['required']
        ];

        if ($request->input('login') !== $user->login) {
            $toValidate['login'] = ['required', 'string', 'max:25', 'min:2', 'unique:users', 'regex:/^\S*$/u'];
        }

        $this->validate($request, $toValidate);

        try {

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->login = $request->login;
            $user->password = Hash::make($request->password);
            $user->country_id = $request->country_id;
            $user->roles_id = $request->roles_id;
            $user->save();

            return response()->json(['user' => $user, 'message' => 'UPDATED'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User updating Failed!'], 409);
        }
    }

    public function deleteUser(Request $request)
    {
        $id = $request->id;
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json(['user' => $user, 'message' => 'DELETED'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User Delete Failed!'], 409);
        }
    }

    public function getAllUser()
    {
        $user = User::with('country', 'roles')->get();

        return response()->json(['user' => $user], 200);
    }

    public function getCurrentUser()
    {

        try {
            $user = User::with('country', 'roles')->where('id', Auth::id())->first();
            return response()->json(['user' => $user, 'message' => 'User'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}
