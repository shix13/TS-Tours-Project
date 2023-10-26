<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Geolocation;
class GeolocationController extends Controller
{
    //
    public function store(Request $request){
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $assignmentID = $request->input('assignmentID');

        //dd($latitude, $longitude, $assignmentID);

        $geolocation = new Geolocation();
        $geolocation->longitude = $longitude;
        $geolocation->latitude = $latitude;
        $geolocation->assignedID = $assignmentID;
        $geolocation->save();

        return redirect()->route('driver.active')->with('success', 'Location sent successfully');
    }
}
