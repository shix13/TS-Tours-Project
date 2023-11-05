<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TS Tours Services</title>
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
        <h1>TS Tours Services</h1>
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
            <p style="text-align: center">This is the System Report for Today <br> ({{ $startDateFormatted }}).</p>
        @elseif ($data['filter'] == '5D')
            <p style="text-align: center">This is the System Report for the Last 5 Days <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == '1M')
            <p style="text-align: center">This is the System Report for the Last Month <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == '6M')
            <p style="text-align: center">This is the System Report for the Last 6 Months <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == '1Y')
            <p style="text-align: center">This is the System Report for the Last 1 Year <br> ({{ $startDateFormatted }} to {{ $endDateFormatted }}).</p>
        @elseif ($data['filter'] == 'MAX')
            <p style="text-align: center">This is the System Report for the Maximum Available Range.</p>
        @endif
    
        <div class="section" style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border: 1px solid #ddd;">
            <div class="section-title" style="font-size: 28px; font-weight: bold; color: #333; margin-bottom: 20px;">Summary Report</div>
            <hr style="border: none; height: 2px; background-color: #3498db; margin-bottom: 20px;">
            
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #e74c3c; font-size: 30px;"><i class="fas fa-tools"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Total Maintenance</div>
                <div class="item-value" style="font-size: 24px; color: #333;">{{ $data['totalMaintenance'] }}</div>
            </div>
        
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #3498db; font-size: 30px;"><i class="fas fa-car"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Total Rentals</div>
                <div class="item-value" style="font-size: 24px; color: #333;">{{ $data['totalRentals'] }}</div>
            </div>
        
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #27ae60; font-size: 30px;"><i class="fas fa-book"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Total Bookings</div>
                <div class="item-value" style="font-size: 24px; color: #333;">{{ $data['totalBookings'] }}</div>
            </div>
        
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #f1c40f; font-size: 30px;"><i class="fas fa-star"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Average Star Rating</div>
                <div class="item-value" style="font-size: 24px; color: #333;">
                    @if (is_numeric($data['ratingAverage']))
                        {{ $data['ratingAverage'] }}/5 
                    @else
                        {{ $data['ratingAverage'] }}
                    @endif
                </div>
                
            </div>
        
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #9b59b6; font-size: 30px;"><i class="fas fa-dollar-sign"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Total Revenue</div>
                <div class="item-value" style="font-size: 24px; color: #333;">{{ $data['totalRevenue'] }} Pesos</div>
            </div>
        
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #e67e22; font-size: 30px;"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Money Remitted</div>
                <div class="item-value" style="font-size: 24px; color: #333;">{{ $data['moneyRemitted'] }} Pesos</div>
            </div>
        
            <div class="summary-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
                <div class="item-icon" style="color: #e74c3c; font-size: 30px;"><i class="fas fa-coins"></i></div>
                <div class="item-label" style="font-weight: bold; color: #333;">Downpayment Received</div>
                <div class="item-value" style="font-size: 24px; color: #333;">{{ $data['downpaymentReceived'] }} Pesos</div>
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
            <div class="section-title">Overall Individual Fleet Record</div>
            <table>
                <tr>
                    <th>Unit Name</th>
                    <th>Registration Number</th>
                    <th>Maintenance Count</th>
                    <th>Assignment Count</th>
                </tr>
                @if (!empty($data['topVehicleAssignments']) && is_array($data['topVehicleAssignments']))
                    @php
                        $totalMaintenanceCount = 0;
                        $totalAssignmentCount = 0;
                    @endphp
                    @foreach ($data['topVehicleAssignments'] as $assignment)
                        @foreach ($assignment as $singleAssignment)
                            @php
                                $totalMaintenanceCount += $singleAssignment['maintenance_count'];
                                $totalAssignmentCount += $singleAssignment['assignment_count'];
                            @endphp
                            <tr>
                                <td>{{ $singleAssignment['unitName'] }}</td>
                                <td>{{ $singleAssignment['registrationNumber'] }}</td>
                                <td>{{ $singleAssignment['maintenance_count'] }}</td>
                                <td>{{ $singleAssignment['assignment_count'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th>{{ $totalMaintenanceCount }}</th>
                        <th>{{ $totalAssignmentCount }}</th>
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
