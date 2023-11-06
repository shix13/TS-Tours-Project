<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Tariff;
use Illuminate\Support\Facades\Storage;

class TariffController extends Controller
{
    public function __contruct()
    {
        $this->middleware('employee')->except('logout');
    }

    public function tariffIndex()
    {
        
    $tariffs = Tariff::paginate(10); // Retrieve all tariffs from the database
    return view('employees.tariff', compact('tariffs'));
    }

    public function create()
    {
        return view('employees.tariffCreate');
    }

    public function store(Request $request)
    {  
        // Validate the form data
        $request->validate([
            'location' => 'required', 
            'rate_Per_Day' => 'required|numeric|min:1',
            'rentPerDayHrs' => 'nullable|numeric|min:1',
            'rent_Per_Hour' => 'nullable|numeric|min:1',
            'do_pu_rate' => 'nullable|numeric|min:1',
            'note' => 'nullable|string|max:255',
        ]);
        
        // Create a new Tariff instance and set its attributes
        $tariff = new Tariff();
        $tariff->location = $request->input('location');
        $tariff->rate_Per_Day = $request->input('rate_Per_Day');
        $tariff->rentPerDayHrs = $request->input('rentPerDayHrs');
        $tariff->rent_Per_Hour =  $request->input('rent_Per_Hour');
        $tariff->do_pu = $request->input('do_pu_rate');
        $tariff->note = $request->input('note');
     
        // Save the tariff to the database
        $tariff->save();
    
        // Redirect back with a success message 
        return redirect()->route('employee.tariff')->with('success', 'Tariff created successfully');
    }
    



    public function edit($id)
    {
        $tariff = Tariff::findOrFail($id);
        // You can pass the $tariff object to your edit view for editing the tariff.
        return view('employees.tariffEdit', compact('tariff'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data as needed
        $request->validate([
            'location' => 'required',
            'rate_Per_Day' => 'required|numeric',
            'rentPerDayHrs' => 'required|numeric',
            'rent_Per_Hour' => 'nullable|numeric',
            'do_pu_rate' => 'nullable|numeric',
            'note' => 'nullable|string', // You can adjust this validation rule based on your requirements
        ]);
    
        // Find the tariff by ID
        $tariff = Tariff::findOrFail($id);
       
        // Update the tariff with the validated data
        $tariff->update([
            'location' => $request->input('location'),
            'rate_Per_Day' => $request->input('rate_Per_Day'),
            'rentPerDayHrs' => $request->input('rentPerDayHrs'),
            'rent_Per_Hour' => $request->input('rent_Per_Hour'),
            'do_pu' => $request->input('do_pu_rate'),
            'note' => $request->input('note'),
        ]);
    
        // Redirect back to the tariffs list page with a success message
        return redirect()->route('employee.tariff')->with('success', 'Tariff updated successfully');
    }
    

    public function destroy($id)
    {   
        $tariff = Tariff::findOrFail($id);
        $tariff->delete();

        // Redirect back to the tariffs list page with a success message
        return redirect()->route('employee.tariff')->with('success', 'Tariff deleted successfully');
    }
}
