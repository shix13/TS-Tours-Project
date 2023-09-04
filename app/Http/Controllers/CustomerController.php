<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
class CustomerController extends Controller
{
    //
    public function browse(){
        return view('customers.browsevehicles');
    }

    public function getVehicles(){
        $vehicles = Vehicle::all();

        return view('customers.browsevehicles', compact('vehicles'));
    }
}
