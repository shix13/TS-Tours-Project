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
use App\Models\Feedback;
use Illuminate\Support\Facades\Storage;
use PDF;

class ReportsController extends Controller
{   

    public function processReports() {
    
        return view('employees.reports');
    }
    
    public function fetchData(Request $request) {
        $filter = $request->input('filter');
        $now = now();
        $now->startOfDay();
       
        // Define variables to hold the data
        $totalMaintenance = 0;
        $totalRentals = 0;
        $totalRevenue = 0;
        $downpaymentReceived = 0;
        $moneyRemitted = 0; 
        $totalBookings = 0;  
        $ratingAverage=0;

        if ($filter === '1D') {
            $totalMaintenance = Maintenance::whereDate('created_at', today())->count();
            $totalRentals = Rent::whereDate('created_at', today())->count();
            $totalBookings = Booking::whereDate('created_at', today())->count();
            $totalRevenue = Remittance::whereDate('created_at', today())->sum('amount') + Booking::whereDate('created_at', today())->sum('downpayment_fee');
            $downpaymentReceived = Booking::whereDate('created_at', today())->sum('downpayment_fee');
            $moneyRemitted = Remittance::whereDate('created_at', today())->sum('amount');
            $ratingAverage = Feedback::whereDate('created_at', today())->avg('rating');
        } elseif ($filter === '5D') {
            $totalMaintenance = Maintenance::where('created_at', '>=', now()->subDays(5))->count();
            $totalRentals = Rent::where('created_at', '>=', now()->subDays(5))->count();
            $totalBookings = Booking::where('created_at', '>=', now()->subDays(5))->count();
            $totalRevenue = Remittance::where('created_at', '>=', now()->subDays(5))->sum('amount') + Booking::where('created_at', '>=', now()->subDays(5))->sum('downpayment_fee');
            $downpaymentReceived = Booking::where('created_at', '>=', now()->subDays(5))->sum('downpayment_fee');
            $moneyRemitted = Remittance::where('created_at', '>=', now()->subDays(5))->sum('amount');
            $ratingAverage = Feedback::where('created_at', '>=', now()->subDays(5))->avg('rating');
        } elseif ($filter === '1M') {
            $totalMaintenance = Maintenance::whereMonth('created_at', now()->month)->count();
            $totalRentals = Rent::whereMonth('created_at', now()->month)->count();
            $totalBookings = Booking::whereMonth('created_at', now()->month)->count();
            $totalRevenue = Remittance::whereMonth('created_at', now()->month)->sum('amount') + Booking::whereMonth('created_at', now()->month)->sum('downpayment_fee');
            $downpaymentReceived = Booking::whereMonth('created_at', now()->month)->sum('downpayment_fee');
            $moneyRemitted = Remittance::whereMonth('created_at', now()->month)->sum('amount');
            $ratingAverage = Feedback::where('created_at', '>=', now()->subMonth())->avg('rating');
        } elseif ($filter === '6M') {
            $totalMaintenance = Maintenance::where('created_at', '>=', now()->subMonths(6))->count();
            $totalRentals = Rent::where('created_at', '>=', now()->subMonths(6))->count();
            $totalBookings = Booking::count();
            $totalRevenue = Remittance::where('created_at', '>=', now()->subMonths(6))->sum('amount') + Booking::where('created_at', '>=', now()->subMonths(6))->sum('downpayment_fee');
            $downpaymentReceived = Booking::where('created_at', '>=', now()->subMonths(6))->sum('downpayment_fee');
            $moneyRemitted = Remittance::where('created_at', '>=', now()->subMonths(6))->sum('amount');
            $ratingAverage = Feedback::where('created_at', '>=', now()->subMonths(6))->avg('rating');
        } elseif ($filter === '1Y') {
            $totalMaintenance = Maintenance::whereYear('created_at', now()->year)->count();
            $totalRentals = Rent::whereYear('created_at', now()->year)->count();
            $totalBookings = Booking::whereYear('created_at', now()->year)->count();
            $totalRevenue = Remittance::whereYear('created_at', now()->year)->sum('amount') + Booking::whereYear('created_at', now()->year)->sum('downpayment_fee');
            $downpaymentReceived = Booking::whereYear('created_at', now()->year)->sum('downpayment_fee');
            $moneyRemitted = Remittance::whereYear('created_at', now()->year)->sum('amount');
            $ratingAverage = Feedback::where('created_at', '>=', now()->subYear())->avg('rating');
        } elseif ($filter === 'MAX') {
            $totalMaintenance = Maintenance::count();
            $totalRentals = Rent::count();
            $totalBookings = Booking::count();
            $totalRevenue = Remittance::sum('amount') + Booking::sum('downpayment_fee');
            $downpaymentReceived = Booking::sum('downpayment_fee');
            $moneyRemitted = Remittance::sum('amount');
            $ratingAverage = DB::table('feedbacks')->avg('rating');
        }
    
            // Calculate percentages for the first filter
            $total= $totalBookings+ $totalMaintenance+ $totalRentals;
            $maintenancePercentage = $totalMaintenance > 0 ? ($totalMaintenance / $total) * 100 : 0;
            $rentalsPercentage = $totalRentals > 0 ? ($totalRentals / $total) * 100 : 0;
            $bookingPercentage = $totalBookings > 0 ? ($totalBookings / $total) * 100 : 0;

        
        // Call the method for the second filter and merge its results
        $secondFilterData = $this->fetchDataSecondFilter($request, $filter);
       // Call the getTopVehicleAssignments function with filter and now parameters
        $topVehicleAssignments = $this->getTopVehicleAssignments($filter, $now);
        
    // Merge the data from both filters
        $response = [
            'totalMaintenance' => $totalMaintenance,
            'maintenancePercentage' => $maintenancePercentage,
            'totalRentals' => $totalRentals,
            'rentalsPercentage' => $rentalsPercentage,
            'totalBookings' => $totalBookings,
            'bookingPercentage' => $bookingPercentage,
            'totalRevenue' => number_format($totalRevenue, 2),
            'downpaymentReceived' => number_format($downpaymentReceived, 2),
            'moneyRemitted' =>  number_format($moneyRemitted, 2),
            'secondFilterData' => $secondFilterData, // Include the second filter data
            'topVehicleAssignments' => $topVehicleAssignments, // Include top vehicle assignments
            'ratingAverage' => number_format($ratingAverage, 2),
            'filter' => $filter,
        ];
       
        return response()->json($response);
    }

    public function fetchDataSecondFilter(Request $request, $filter) {
        $filter = $request->input('filter');
            
        $now = now(); // Get the current date and time
        $now->startOfDay();
        
        $topLocations = Booking::select('tariffs.location')
            ->selectRaw('COUNT(*) as booking_count')
            ->join('tariffs', 'booking.tariffID', '=', 'tariffs.tariffID')
            ->where(function ($query) use ($filter, $now) {
                if ($filter === '1D') {
                    $query->where('booking.created_at', '>=', $now->today());
                } elseif ($filter === '5D') {
                    $query->where('booking.created_at', '>=', $now->subDays(5));
                } elseif ($filter === '1M') {
                    $query->where('booking.created_at', '>=', $now->subMonth());
                } elseif ($filter === '6M') {
                    // Filter for the last 6 months
                    $query->where('booking.created_at', '>=', $now->subMonths(6));
                } elseif ($filter === '1Y') {
                    // Filter for the last 1 year
                    $query->where('booking.created_at', '>=', $now->subYear());
                }
            })
            ->groupBy('tariffs.location')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();

        $mergedTopLocations = [];

        foreach ($topLocations as $location) {
            $locationName = $location->location;
            $bookingCount = $location->booking_count;

            // Generate a unique key for each location
            $key = $locationName;

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
               if ($filter === '1D') {
                    // Filter for the last 5 days
                    $query->where('vehicles_Assigned.created_at', '>=', $now->today());
                } elseif ($filter === '5D') {
                    // Filter for the last 5 days
                    $query->where('vehicles_Assigned.created_at', '>=', $now->subDays(5));
                } elseif ($filter === '1M') {
                    // Filter for the last 1 month
                    $query->where('vehicles_Assigned.created_at', '>=', $now->subMonth());
                } elseif ($filter === '6M') {
                    // Filter for the last 6 months
                    $query->where('vehicles_Assigned.created_at', '>=', $now->subMonths(6));
                } elseif ($filter === '1Y') {
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
    
        return $data;
    }    

    public function getTopVehicleAssignments($filter, $now)
    {   
        $topVehicleAssignments = Vehicle::select('unitName', 'registrationNumber', 'vehicles.unitID')
        ->withCount('vehicleAssignments as assignment_count')
        ->withCount('maintenances as maintenance_count')
        ->leftJoin('vehicles_assigned', 'vehicles.unitID', '=', 'vehicles_assigned.unitID')
        ->leftJoin('maintenances', 'vehicles.unitID', '=', 'maintenances.unitID')
        ->where(function ($query) use ($filter, $now) {
            if ($filter === '1D') {
                $query->where('vehicles_assigned.created_at', '>=', $now->subDay());
                $query->orWhere('maintenances.created_at', '>=', $now->subDay());
            } elseif ($filter === '5D') {
                $query->where('vehicles_assigned.created_at', '>=', $now->subDays(5));
                $query->orWhere('maintenances.created_at', '>=', $now->subDays(5));
            } elseif ($filter === '1M') {
                $query->where('vehicles_assigned.created_at', '>=', $now->subMonth());
                $query->orWhere('maintenances.created_at', '>=', $now->subMonth());
            } elseif ($filter === '6M') {
                $query->where('vehicles_assigned.created_at', '>=', $now->subMonths(6));
                $query->orWhere('maintenances.created_at', '>=', $now->subMonths(6));
            } elseif ($filter === '1Y') {
                $query->where('vehicles_assigned.created_at', '>=', $now->subYear());
                $query->orWhere('maintenances.created_at', '>=', $now->subYear());
            }
        })
        ->where('vehicles.status', 'active')
        ->orderBy('assignment_count', 'desc')
        ->get();
    
    $mergedTopVehicleAssignments = [];
    
    foreach ($topVehicleAssignments as $vehicle) {
        $unitName = $vehicle->unitName;
        $registrationNumber = $vehicle->registrationNumber;
        $unitID = $vehicle->unitID;

        // Generate a unique key for each vehicle based on unitID
        $key = $unitID;
    
        // If the key exists in the merged result, update the counts
        if (isset($mergedTopVehicleAssignments[$key])) {
            $mergedTopVehicleAssignments[$key]['assignment_count'] += $vehicle->assignment_count;
            $mergedTopVehicleAssignments[$key]['maintenance_count'] += $vehicle->maintenance_count;
        } else {
            // If the key doesn't exist in the merged result, create a new entry
            $mergedTopVehicleAssignments[$key] = [
                'unitName' => $unitName,
                'registrationNumber' => $registrationNumber,
                'assignment_count' => $vehicle->assignment_count,
                'maintenance_count' => $vehicle->maintenance_count,
            ];
        }
    }
    
    // Convert the merged result back to a numeric array
    $mergedTopVehicleAssignments = array_values($mergedTopVehicleAssignments);
    
    // Now $mergedTopVehicleAssignments contains merged and summarized data for each vehicle    
    
        $totalMaintenanceCount = $topVehicleAssignments->sum('maintenance_count');
        $totalAssignmentCount = $topVehicleAssignments->sum('assignment_count');

        return [
            'topVehicleAssignments' => $mergedTopVehicleAssignments,
            'totalMaintenanceCount' => $totalMaintenanceCount,
            'totalAssignmentCount' => $totalAssignmentCount,
        ];
    }
    
    

    public function generatePDF(Request $request)
{    
    // Check if the 'data' parameter exists in the request
    if ($request->has('data')) {
        // Get the 'data' parameter value
        $dataParam = $request->input('data');
        
        // Decode and parse the data from the URL parameter
        $data = json_decode(urldecode($dataParam), true);

        // Format the current date and time
        $now = now();
        $nowFormatted = $now->format('Y-m-d H:i:s');

        // Combine the formatted date and time with the filename
        $filename = "TSReport_$nowFormatted.pdf";

        // Load the 'GenerateReport' view with the data
        $pdf = PDF::loadView('pdf.GenerateReport', compact('data'));

        return $pdf->stream($filename);
    } else {
        // 'data' parameter is not present in the request
        return response()->json(['error' => 'Data not provided in the request'], 400);
    }
}

}
