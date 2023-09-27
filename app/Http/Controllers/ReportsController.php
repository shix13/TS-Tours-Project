<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Rent;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    /*For monthly time frame:
        We are assuming that employees would want to see the revenue for each month.
        Here's possible steps:
            1. Create an associative array where key represents the months

    */

    public function processReports(){
        $collectedReports = [];

        for($i = 1; $i <= 12; $i++){
            // Define the time frame
            $year = 2023;
            $month = $i;

            // Calculate the start date
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();

            // Calculate the end date
            $endDate = $startDate->copy()->endOfMonth()->endOfDay();

            //Do the queries
            $rentals = Rent::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_Status', '=', 'Paid')
                ->get();

            //Do the calculation
            $count = 0;
            $revenue = 0;

            foreach($rentals as $r){
                $count++;
                $revenue += $r -> total_Price;
            }

            $report = [
                'units_rented' => $count,
                'monthly_revenue' => $revenue,
            ];

            $collectedReports[] = $report;
        }
        //dd($collectedReports);
        return view('employees.reports');
    }
}
