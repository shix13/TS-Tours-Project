<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Tariff;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function getVehicles(){
        $vehicleTypes = VehicleType::all();

        return view('tests.selectvehicles', compact('vehicleTypes'));
    }

    public function proceedBooking(Request $request){
        
        return view('tests.selectvehicles', compact('vehicleTypes'));
    }
}
