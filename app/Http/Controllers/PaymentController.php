<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Employee;
use App\Models\Rent;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }

    
}
