<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisitorController extends Controller
{
    //
    public function tsdefault()
    {
        return view('visitors/tsdefault');
    }
    public function tsabout()
    {
        return view('visitors/tsabout');
    }
    public function tsfleet()
    {
        return view('visitors/tsfleet');
    }
    public function tscontact()
    {
        return view('visitors/tscontact');
    }
}
