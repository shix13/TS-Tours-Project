<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    //
    public function store(Request $request){
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $driverID = $request->input('driverID');
        $unitID = $request->input('unitID');

        dd($latitude, $longitude, $driverID, $unitID);

        Geolocation::create($data);
        return response()->json(['message' => 'Geolocation data saved.']);
    }
}
