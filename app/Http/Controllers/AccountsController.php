<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class AccountsController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }

    public function accountIndex()
    {
        $employees = Employee::paginate(20, ['*'], 'employees'); // Fetch all accounts from the database

       

        return view('employees.accounts', compact('employees'));
    }

    public function create()
    {
        return view('accounts.create');
    }

}
