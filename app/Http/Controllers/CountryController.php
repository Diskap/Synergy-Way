<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function addcountry(Request $request)
    {
        Country::create([
            'name' => $request->name
        ]);
    }

    public function getAllCountry()
    {
        return response()->json(Country::all(), 200);

    }
}
