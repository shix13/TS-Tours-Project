<!DOCTYPE html>
<html lang="en">

<head>
    @php
    use Carbon\Carbon;
    @endphp
    

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #ffffff;
        }
        .header {
            background-color: midnightblue;
            color: white;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
            }
            .header {
                font-size: 20px;
            }
            table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>TS Tours Services</h2>
            <h4>Booking Approved!</h4>
       
        </div>
        <div class="content">
            <p>
                Dear {{ $booking->cust_first_name }} {{ $booking->cust_last_name }},<br>
                Your payment under tracking ID: <strong>{{ $booking->reserveID }}</strong> has been approved.
            </p>
            <p>Thank you for your booking with TS Tours Services. Below are the details of your booking:</p>
 
         <div class="details">
            <h3>Booking Details:</h3>
            <table>
                <tr>
                    <td><strong>Booking Type:</strong></td>
                    <td>{{ $booking->bookingType }}</td>
                </tr>
                <tr>
                    <td><strong>Schedule Date:</strong></td>
                    <td>{{ $booking->startDate }}</td>
                </tr>
                <tr>
                    <td><strong>Return Date:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($booking->endDate)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td><strong>Location:</strong></td>
                    <td>{{ $tariff->location }}</td>
                </tr>
            </table>
        </div>

        <div class="details">
            <h3>Vehicle Information:</h3>
            <table>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td><strong>License Plate Number:</strong></td>
                    <td>{{ $vehicle->registrationNumber }}</td>
                </tr>
                <tr>
                    <td><strong>Model:</strong></td>
                    <td>{{ $vehicle->unitName }}</td>
                </tr>
                <tr>
                    <td><strong>Passenger Capacity:</strong></td>
                    <td>{{ $vehicle->pax }}</td>
                </tr>
                <tr>
                    <td><strong>Color:</strong></td>
                    <td>{{ $vehicle->color }}</td>
                </tr>
                <tr>
                    <td><strong>Year Model:</strong></td>
                    <td>{{ $vehicle->yearModel }}</td>
                </tr>
                <tr>
                    <td><strong>Vehicle Type:</strong></td>
                    <td>{{ $vehicle->vehicleType->vehicle_Type }}</td>
                </tr>                
                @endforeach
            </table>
        </div>

        <div class="details">
            <h3>Driver Information:</h3>
            <table>
                @foreach($drivers as $driver)
                <tr>
                    <td><strong>First Name:</strong></td>
                    <td>{{ $driver->firstName }}</td>
                </tr>
                <tr>
                    <td><strong>Last Name:</strong></td>
                    <td>{{ $driver->lastName }}</td>
                </tr>
                <tr>
                    <td><strong>Mobile Number:</strong></td>
                    <td>{{ $driver->mobileNum }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        </div>
        
        <a href="{{ route('home') }}" class="cta-button" style="text-decoration: none;">Explore More</a>

        <div class="footer text-center">
            <p>&copy; 2023 TS Tours Services. All rights reserved.</p>
            <p>Dumaguete, Negros Oriental, Philippines</p>
            <p>Follow us: <a href="https://www.facebook.com/TSTOURSSERVICES" style="color: #007bff; text-decoration: none;">Facebook</a></p>
        </div>
    </div>
</body>

</html>
