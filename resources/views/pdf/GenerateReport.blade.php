<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TS Tours Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #e4e4e4;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            color: #333;
        }
        .summary-item {
            display: flex;
            align-items: center;
        }
        .item-label {
            flex: 1;
            font-size: 18px;
            color: #333;
        }
        .item-value {
            flex: 1;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .bar-graph {
            background-color: #f2f2f2;
            height: 20px;
            margin: 5px 0;
        }
    
    .bar {
        background-color: #007bff;
        height: 100%;
    }

    </style>
</head>
<body>
    <div class="container">
        <h1>TS Tours Services Report</h1>
        @php
        $now = now();
        $startDate = $now; // Initialize the start date with the current date
        $endDate = $now->endOfDay(); // Initialize the end date with the current date
        $endDateFormatted = $endDate->format('F j, Y');

        if ($data['filter'] == '1D') {
            $startDate = $startDate->startOfDay();
        } elseif ($data['filter'] == '5D') {
            $startDate = $startDate->subDays(5)->startOfDay();
        } elseif ($data['filter'] == '1M') {
            // Set the start date to the first day of the previous month
            $startDate = $startDate->subMonth()->startOfDay();
        } elseif ($data['filter'] == '6M') {
            $startDate = $startDate->subMonths(6)->startOfDay();
        } elseif ($data['filter'] == '1Y') {
            $startDate = $startDate->subYear()->startOfDay();
        }
        // Format the dates
        $startDateFormatted = $startDate->format('F j, Y');
        @endphp

        @if ($data['filter'] == '1D')
            <p style="text-align: center">This is the financial report for Today <br> ({{ $startDateFormatted }}).</p>
        @elseif ($data['filter'] == '5D')
            <p style="text-align: center">This is the financial report for the last 5 days <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == '1M')
            <p style="text-align: center">This is the financial report for the last month <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == '6M')
            <p style="text-align: center">This is the financial report for the last 6 months <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == '1Y')
            <p style="text-align: center">This is the financial report for the last 1 year <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == 'MAX')
            <p style="text-align: center">This is the financial report for the maximum available range.</p>
        @endif
    
        <div class="section">
            <div class="section-title">Summary</div>
            <hr>
        
            <div class="summary-item">
                <div class="item-label">Total Maintenance:</div>
                <div class="item-value" style="font-size: 20px">{{ $data['totalMaintenance'] }}</div>
                <div class="bar-graph">
                    <div class="bar" style="width: {{ $data['maintenancePercentage'] }}%;"></div>
                </div>
            </div>
        
            <div class="summary-item">
                <div class="item-label">Total Rentals:</div>
                <div class="item-value" style="font-size: 20px">{{ $data['totalRentals'] }}</div>
                <div class="bar-graph">
                    <div class="bar" style="width: {{ $data['rentalsPercentage'] }}%;"></div>
                </div>
            </div>
        
            <div class="summary-item">
                <div class="item-label">Total Bookings:</div>
                <div class="item-value" style="font-size: 20px" >{{ $data['totalBookings'] }}</div>
                <div class="bar-graph">
                    <div class="bar" style="width: {{ $data['bookingPercentage'] }}%;"></div>
                </div>
            </div>

            <div class="summary-item">
                <div class="item-label">Average Rating:</div>
                <div class="item-value" style="font-size: 20px">{{ $data['ratingAverage'] }}</div>
                @php
                $ratingPercentage = ($data['ratingAverage'] / 5) * 100;
                @endphp
                <div class="bar-graph">
                    <div class="bar" style="width: {{ $ratingPercentage > 100 ? 100 : $ratingPercentage }}%;"></div>
                </div>
            </div>
            
        
            <div class="summary-item">
                <div class="item-label">Total Revenue:</div>
                <div class="item-value" style="font-size: 20px">{{ $data['totalRevenue'] }} Pesos</div>
            </div>
        
            <!-- Add a bar graph to show the difference -->
            <div class="bar-graph">
                @php
                    $totalRevenue = (float)str_replace('₱', '', str_replace(',', '', $data['totalRevenue']));
                    $moneyRemitted = (float)str_replace('₱', '', str_replace(',', '', $data['moneyRemitted']));
            
                    // Calculate the percentage
                    $percentage = $totalRevenue > 0 ? number_format(($moneyRemitted / $totalRevenue) * 100, 2) : 0;
                    $unfilledPercentage = 100 - $percentage;
                @endphp
            
                @if ($percentage > 0)
                    <div class="bar" style="width: {{ $unfilledPercentage }}%;">
                        <div class="percentage-text" style="text-align: center;">
                            {{ $unfilledPercentage }}%
                        </div>
                        <div class="bar-header" style="text-align: center;">
                            Downpayment Received: {{ $data['downpaymentReceived'] }} 
                        </div>
                    </div>
                @endif
            
                @if ($moneyRemitted > 0)
                    @if ($percentage > 0)
                        <div class="unfilled-bar" style="width: {{ $percentage }}%; background-color: orange; float: right; height: 20px; height: 100%; margin-top: -20px;">
                    @else
                        <div class="unfilled-bar" style="width: 100%; background-color: orange; float: right; height: 20px; height: 100%; margin-top: -20px;">
                    @endif
                        <div class="percentage-text" style="text-align: center;">
                            {{ $percentage }}%
                        </div>
                        <div class="bar-header" style="text-align: center;">
                            Money Remitted: {{ $data['moneyRemitted'] }} 
                        </div>
                    </div>
                @endif
            </div>
            
        </div>
        

        <div class="section" style="page-break-before: left;">
            <div class="section-title">Top Locations</div>
            <table>
                <tr>
                    <th>Location</th>
                    <th>Booking Count</th>
                </tr>
                @if (!empty($data['secondFilterData']['topLocations']))
                    @php
                        $totalBookingCount = 0; // Initialize the total booking count
                    @endphp
                    @foreach ($data['secondFilterData']['topLocations'] as $location => $count)
                        <tr>
                            <td>{{ $location }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                        @php
                            $totalBookingCount += $count; // Add to the total booking count
                        @endphp
                    @endforeach
                    <tr>
                        <th>Total</th>
                        <th>{{ $totalBookingCount }}</th> <!-- Display the total booking count -->
                    </tr>
                @else
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endif
            </table>
        </div>
        
        
        <div class="section" style="page-break-before: left;">
            <div class="section-title">Top Active Vehicle Assigned</div>
            <table>
                <tr>
                    <th>Vehicle</th>
                    <th>Registration Number</th>
                    <th>Assignment Count</th>
                </tr>
                @if (!empty($data['secondFilterData']['topVehicles']))
                    @php
                        $totalAssignmentCount = 0; // Initialize the total assignment count
                    @endphp
                    @foreach ($data['secondFilterData']['topVehicles'] as $vehicle)
                        <tr>
                            <td>{{ $vehicle['unitName'] }}</td>
                            <td>{{ $vehicle['registrationNumber'] }}</td>
                            <td>{{ $vehicle['assignment_count'] }}</td>
                        </tr>
                        @php
                            $totalAssignmentCount += $vehicle['assignment_count']; // Add to the total assignment count
                        @endphp
                    @endforeach
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th>{{ $totalAssignmentCount }}</th> <!-- Display the total assignment count -->
                    </tr>
                @else
                    <tr>
                        <td colspan="3">No data available</td>
                    </tr>
                @endif
            </table>
        </div>
        
        
        <div class="section" style="page-break-before: always;">
            <div class="section-title">Individual Fleet Record</div>
            <table>
                <tr>
                    <th>Unit Name</th>
                    <th>Registration Number</th>
                    <th>Assignment Count</th>
                    <th>Maintenance Count</th>
                </tr>
                @if(!empty($data['topVehicleAssignments']['topVehicleAssignments']))
                    @foreach($data['topVehicleAssignments']['topVehicleAssignments'] as $assignment)
                        <tr>
                            <td>{{ $assignment['unitName'] }}</td>
                            <td>{{ $assignment['registrationNumber'] }}</td>
                            <td>{{ $assignment['assignment_count'] }}</td>
                            <td>{{ $assignment['maintenance_count'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th>{{ $data['topVehicleAssignments']['totalAssignmentCount'] }}</th>
                        <th>{{ $data['topVehicleAssignments']['totalMaintenanceCount'] }}</th>
                    </tr>
                @else
                    <tr>
                        <td colspan="4">No data available</td>
                    </tr>
                @endif
            </table>
        </div>
        
    
</body>
</html>
