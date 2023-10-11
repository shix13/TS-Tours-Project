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
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            display: block;
            margin: 0 auto;
        }

        .content {
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        .details {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .details h3 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .details ul {
            list-style-type: none;
            padding: 0;
        }

        .cta-button {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container" style="border: 1px solid black">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/TsTours.jpg') }}" alt="TS Tours Logo" width="150px">
            </div>
            <h2 style="color: #007bff;">Booking Approved!</h2>
        </div>

        <div class="content">
            <p>
                Dear {{ $booking->cust_first_name }} {{ $booking->cust_last_name }},<br>
                Your payment under booking  ID: <strong>{{ $booking->reserveID }}</strong> has been approved.
                Thank you for choosing our services.
            </p>

            <div class="details">
                <h3>Booking Details:</h3>
                <ul>
                    <li><strong>Booking Type:</strong> {{ $booking->bookingType }}</li>
                    <li><strong>Schedule Date:</strong> {{ $booking->startDate }}</li>
                    <li><strong>Return Date:</strong> {{ \Carbon\Carbon::parse($booking->endDate)->format('Y-m-d') }}</li>
                    <li><strong>Location:</strong> {{ $tariff->location }}</li>
                </ul>
            </div>

            <div class="details">
                <h3>Vehicle Information:</h3>
                @foreach($vehicles as $vehicle)
                    <ul>
                        <li><strong>License Plate Number:</strong> {{ $vehicle->registrationNumber }}</li>
                        <li><strong>Model:</strong> {{ $vehicle->unitName }}</li>
                        <li><strong>Passenger Capacity:</strong> {{ $vehicle->pax }}</li>
                    </ul>
                @endforeach
            </div>

            <div class="details">
                <h3>Driver Information:</h3>
                @foreach($drivers as $driver)
                    
                    <ul>
                        <li><strong>First Name:</strong> {{ $driver->firstName }}</li>
                        <li><strong>Last Name:</strong> {{ $driver->lastName }}</li>
                        <li><strong>Mobile Number:</strong> {{ $driver->mobileNum }}</li>
                    </ul>
                @endforeach
            </div>            
        </div>
        
        <a href="{{ route('home') }}" class="cta-button" style="color: black;background:orangered">Explore More</a>
    </div>
</body>

</html>
