<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Rent;
use App\Models\Tariff;
use App\Models\Vehicle;
use App\Models\VehicleTypeBooked;
use App\Models\VehicleAssigned;
use App\Models\Maintenance;
use App\Models\Remittance;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{   

    public function processReports() {
    
        return view('employees.reports');
    }
    
    public function fetchData(Request $request) {
        $filter = $request->input('filter');
        
        // Define variables to hold the data
        $totalMaintenance = 0;
        $totalRentals = 0;
        $totalRevenue = 0;
        $downpaymentReceived = 0;
        $moneyRemitted = 0; 
        
        if ($filter === '1D') {
            $totalMaintenance = Maintenance::whereDate('created_at', today())->count();
            $totalRentals = Rent::whereDate('created_at', today())->count();
            $totalRevenue = Remittance::whereDate('created_at', today())->sum('amount')+Booking::whereDate('created_at', today())->sum('downpayment_fee');
            $downpaymentReceived = Booking::whereDate('created_at', today())->sum('downpayment_fee');
            $moneyRemitted = Remittance::whereDate('created_at', today())->sum('amount');
            $totalBookings= Booking::whereDate('created_at', today())->count();
            $total=Maintenance::whereDate('created_at', today())->count()+Rent::whereDate('created_at', today())->count()+Booking::whereDate('created_at', today())->count();
        } elseif ($filter === '5D') {
            $totalMaintenance = Maintenance::where('created_at', '>=', now()->subDays(5))->count();
            $totalRentals = Rent::where('created_at', '>=', now()->subDays(5))->count();
            $totalRevenue = Remittance::where('created_at', '>=', now()->subDays(5))->sum('amount') + Booking::where('created_at', '>=', now()->subDays(5))->sum('downpayment_fee');
            $downpaymentReceived =  Booking::where('created_at', '>=', now()->subDays(5))->sum('downpayment_fee');
            $moneyRemitted = Remittance::where('created_at', '>=', now()->subDays(5))->sum('amount');
            $totalBookings=Booking::where('created_at', '>=', now()->subDays(5))->count();
            $total=Maintenance::where('created_at', '>=', now()->subDays(5))->count()+Rent::where('created_at', '>=', now()->subDays(5))->count()+Booking::where('created_at', '>=', now()->subDays(5))->count();
        } elseif ($filter === '1M') {
            $totalMaintenance = Maintenance::whereMonth('created_at', now()->month)->count();
            $totalRentals = Rent::whereMonth('created_at', now()->month)->count();
            $totalRevenue = Remittance::whereMonth('created_at', now()->month)->sum('amount') + Booking::whereMonth('created_at', now()->month)->sum('downpayment_fee');
            $downpaymentReceived = Booking::whereMonth('created_at', now()->month)->sum('downpayment_fee');
            $moneyRemitted = Remittance::whereMonth('created_at', now()->month)->sum('amount');
            $totalBookings=Booking::whereMonth('created_at', now()->month)->count();
            $total=Maintenance::whereMonth('created_at', now()->month)->count()+Rent::whereMonth('created_at', now()->month)->count()+Booking::whereMonth('created_at', now()->month)->count();
        } elseif ($filter === '6M') {
            $totalMaintenance = Maintenance::where('created_at', '>=', now()->subMonths(6))->count();
            $totalRentals = Rent::where('created_at', '>=', now()->subMonths(6))->count();
            $totalRevenue = Remittance::where('created_at', '>=', now()->subMonths(6))->sum('amount') + Booking::where('created_at', '>=', now()->subMonths(6))->sum('downpayment_fee');
            $downpaymentReceived = Booking::where('created_at', '>=', now()->subMonths(6))->sum('downpayment_fee');
            $moneyRemitted =Remittance::where('created_at', '>=', now()->subMonths(6))->sum('amount');
            $totalBookings=Booking::where('created_at', '>=', now()->subMonths(6))->count();
            $total=Maintenance::where('created_at', '>=', now()->subMonths(6))->count()+Rent::where('created_at', '>=', now()->subMonths(6))->count()+Booking::where('created_at', '>=', now()->subMonths(6))->count();
        } elseif ($filter === '1Y') {
            $totalMaintenance = Maintenance::whereYear('created_at', now()->year)->count();
            $totalRentals = Rent::whereYear('created_at', now()->year)->count();
            $totalRevenue = Remittance::whereYear('created_at', now()->year)->sum('amount') + Booking::whereYear('created_at', now()->year)->sum('downpayment_fee');
            $downpaymentReceived = Booking::whereYear('created_at', now()->year)->sum('downpayment_fee');
            $moneyRemitted=Remittance::whereYear('created_at', now()->year)->sum('amount');
            $totalBookings=Booking::whereYear('created_at', now()->year)->count();
            $total=Maintenance::whereYear('created_at', now()->year)->count()+Rent::whereYear('created_at', now()->year)->count()+Booking::whereYear('created_at', now()->year)->count();
        } elseif ($filter === 'MAX') {
            $totalMaintenance = Maintenance::count();
            $totalRentals = Rent::count();
            $totalRevenue = Remittance::sum('amount') + Booking::sum('downpayment_fee');
            $downpaymentReceived = Booking::sum('downpayment_fee');
            $moneyRemitted = Remittance::sum('amount');
            $totalBookings=Booking::count();
            $total=Maintenance::count()+Rent::count()+Booking::count();
        }

        // Calculate percentages
        $maintenancePercentage = $totalMaintenance > 0 ? ($totalMaintenance / $total) * 100 : 0;
        $rentalsPercentage = $totalRentals > 0 ? ($totalRentals / $total) * 100 : 0;
        $bookingPercentage = $totalBookings > 0 ? ($totalBookings / $total) * 100 : 0;
        //$revenuePercentage = $totalRevenue > 0 ? ($totalRevenue / $totalRevenue) * 100 : 0;
        
        // Return the data as JSON
        return response()->json([
            'totalMaintenance' => $totalMaintenance,
            'maintenancePercentage' => $maintenancePercentage,
            'totalRentals' => $totalRentals,
            'rentalsPercentage' => $rentalsPercentage,
            'totalBookings' => $totalBookings,
            'bookingPercentage' => $bookingPercentage,
            'totalRevenue' => '₱ ' . number_format($totalRevenue, 2), // Format as currency with two decimal places
            //'revenuePercentage' => '₱ ' . number_format($revenuePercentage, 2), // Format as currency with two decimal places
            'downpaymentReceived' => '₱ ' . number_format($downpaymentReceived, 2), // Format as currency with two decimal places
            'moneyRemitted' => '₱ ' . number_format($moneyRemitted, 2), // Format as currency with two decimal places
        ]);
        
    }

    public function fetchDataSecondFilter(Request $request) {
        $filter = $request->input('filter');
    
        $now = now(); // Get the current date and time
    
        $topLocations = Tariff::select('location', 'bookingType')
            ->selectRaw('COUNT(*) as booking_count')
            ->join('booking', 'tariffs.tariffID', '=', 'booking.tariffID')
            ->where(function ($query) use ($filter, $now) {
                if ($filter === '1Month') {
                    // Filter for the last 1 month
                    $query->where('booking.created_at', '>=', $now->subMonth());
                } elseif ($filter === '6Months') {
                    // Filter for the last 6 months
                    $query->where('booking.created_at', '>=', $now->subMonths(6));
                } elseif ($filter === '1Year') {
                    // Filter for the last 1 year
                    $query->where('booking.created_at', '>=', $now->subYear());
                }
            })
            ->groupBy('location', 'bookingType')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();
    
        $mergedTopLocations = [];
    
        foreach ($topLocations as $location) {
            $locationName = $location->location;
            $bookingType = $location->bookingType;
            $bookingCount = $location->booking_count;
    
            // Generate a unique key for each location and booking type combination
            $key = $locationName . '|' . $bookingType;
    
            // If the key exists in the merged result, update the count
            if (isset($mergedTopLocations[$key])) {
                $mergedTopLocations[$key] += $bookingCount;
            } else {
                // If the key doesn't exist in the merged result, create an entry
                $mergedTopLocations[$key] = $bookingCount;
            }
        }
    
        $topVehicleAssignments = VehicleAssigned::select('unitName', 'registrationNumber','vehicles_Assigned.unitID', \DB::raw('COUNT(*) as assignment_count'))
            ->join('vehicles', 'vehicles_Assigned.unitID', '=', 'vehicles.unitID')
            ->where(function ($query) use ($filter, $now) {
                if ($filter === '1Month') {
                    // Filter for the last 1 month
                    $query->where('vehicles_Assigned.created_at', '>=', $now->subMonth());
                } elseif ($filter === '6Months') {
                    // Filter for the last 6 months
                    $query->where('vehicles_Assigned.created_at', '>=', $now->subMonths(6));
                } elseif ($filter === '1Year') {
                    // Filter for the last 1 year
                    $query->where('vehicles_Assigned.created_at', '>=', $now->subYear());
                }
            })
            ->where('vehicles.status', 'active')
            ->groupBy('unitName', 'registrationNumber','vehicles_Assigned.unitID')
            ->orderBy('assignment_count', 'desc')
            ->limit(10)
            ->get();
            
        $mergedTopVehicles = $topVehicleAssignments->pluck('registrationNumber')->toArray();
    
        $vehicleData = Vehicle::whereIn('registrationNumber', $mergedTopVehicles)->get();
    
        $data = [
            'topLocations' => $mergedTopLocations,
            'topVehicles' => $topVehicleAssignments,
        ];
    
        return response()->json($data);
    }    

    public function showReport($fleetId)
    {
        // You can use the $fleetId parameter to retrieve data related to the selected fleet
        // and generate the report or perform any other actions you need.
        
        return view('employees.fleetreport', ['fleetId' => $fleetId]);
    }
    
}
