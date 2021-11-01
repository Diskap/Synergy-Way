<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function getAllRoles()
    {
        return response()->json(Roles::all(), 200);
    }

    public function addrole(Request $request)
    {
        Roles::create([
            'name' => $request->name
        ]);
    }
}
