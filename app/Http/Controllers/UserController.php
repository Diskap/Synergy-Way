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
    public function register (Request $request)
    {
        $request->validate([
            'first_name' =>['required', 'string', 'max:60', 'min:2'],
            'last_name' =>['required', 'string', 'max:60', 'min:2'],
            'login' =>['required', 'max:25', 'min:2', 'unique:users', 'regex:/^\S*$/u'],
            'password' =>['required', 'string', 'min:8'],
            'country_id' =>['required']
            ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->login = $request->login;
        $user->password = Hash::make($request->password);
        $user->country_id = $request->country_id;
        if (isset($request->roles_id)) {
            $user->roles_id = $request->roles_id;
            } elseif (count(User::all()) === 0) {
                $user->roles_id = 1;
            } else {
                $user->roles_id = 2;
            }
        $user->save();

    }

    public function editUser (Request $request)
    {

        $id = $request->id;

        $user = User::findOrFail($id);

        $toValidate =[
            'first_name' =>['required', 'string', 'max:60', 'min:2'],
            'last_name' =>['required', 'string', 'max:60', 'min:2'],
            'country_id' =>['required'],
            'roles_id' =>['required']
        ];

        if ($request->input('login') !== $user->login ) {
            $toValidate['login'] = ['required', 'string', 'max:25', 'min:2', 'unique:users', 'regex:/^\S*$/u'];
        }

        $this->validate($request,$toValidate);

        try {

            $user->first_name = $request ->first_name;
            $user->last_name = $request ->last_name;
            $user->login = $request ->login;
            $user->password = Hash::make($request->password);
            $user->country_id = $request ->country_id;
            $user->roles_id = $request -> roles_id;
            $user->save();

            return response()->json(['user' => $user, 'message' => 'UPDATED'], 201);
        } catch (\Exception $e) {
             return response()->json(['message' => 'User updating Failed!'], 409);
        }
    }

    public function deleteUser (Request $request)
    {
        $id = $request->id;
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json(['user' => $user, 'message' => 'DELETED'],201);
        } catch (\Exception $e){
            return response()->json(['message' => 'User Delete Failed!'], 409);
        }
    }

    public function login (Request $request)
    {

        $request->validate([
            'login' =>['required'],
            'password' =>['required']
            ]);

        if (Auth::attempt($request->only('login','password'))){
            return response()->json(Auth::user(),200);
        }
        throw ValidationException::withMessages([
            'login' => ['The provided credential are incorrect.']
        ]);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function getAllCountry()
    {
        return response()->json(Country::all(),200);

    }

    public function getAllRoles()
    {
        return response()->json(Roles::all(),200);
    }

    public function getAllUser()
    {
        $user = User::with('country','roles')->get();

        return response()->json(['user' => $user], 200);
    }


    public function getCurrentUser(Request $request)
    {

        try {
            $user = User::with('country','roles')->where('id',Auth::id())->first();

            return response()->json(['user' => $user, 'message' => 'User'],201);
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function addcountry(Request $request)
    {
        Country::create([
            'name'=> $request-> name
            ]);
    }

    public function addrole(Request $request)
    {
        Roles::create([
            'name'=> $request-> name
            ]);
    }
}
