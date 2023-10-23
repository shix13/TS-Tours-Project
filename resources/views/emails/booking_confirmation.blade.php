<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            <h4>Booking Under Review</h4>
        </div>
        <p><strong>Dear {{ $bookingData['cust_first_name'] }} {{ $bookingData['cust_last_name'] }},</strong></p>
        <p>Thank you for your booking with TS Tours Services. Below are the details of your booking:</p>
        <table>
            <tr>
                <td><strong>Booking Type:</strong></td>
                <td>{{ $bookingData['bookingType'] }}</td>
            </tr>
            <tr>
                <td><strong>Pick-Up Address:</strong></td>
                <td>{{ $bookingData['pickUp_Address'] }}</td>
            </tr>
            <tr>
                <td><strong>Pick-up Date:</strong></td>
                <td>{{ \Carbon\Carbon::parse($bookingData['startDate'])->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Return Date:</strong></td>
                <td>{{ \Carbon\Carbon::parse($bookingData['endDate'])->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Pickup Time:</strong></td>
                <td>{{ \Carbon\Carbon::parse($bookingData['startDate'])->format('g:i A') }}</td>
            </tr>
            <tr>
                <td><strong>Number of Days:</strong></td>
                <td>
                    @php
                        $startDate = \Carbon\Carbon::parse($bookingData['startDate']);
                        $endDate = \Carbon\Carbon::parse($bookingData['endDate']);
                        $numberOfDays = $startDate->diffInDays($endDate);
                        if ($numberOfDays == 0 && $startDate->diffInHours($endDate) < 24) {
                            $numberOfDays = 1;
                        }
                    @endphp
                    {{ $numberOfDays }} day{{ $numberOfDays != 1 ? 's' : '' }}
                </td>
            </tr>
        </table>
        <p style="text-align:center;">If you have any questions or need further assistance, feel free to contact us at <a href="mailto:tstoursduma@gmail.com" style="color: #007bff; text-decoration: none;">tstoursduma@gmail.com</a>.</p>
        <p style="text-align:center;">Thank you for choosing TS Tours Services!</p>
        <p style="text-align:center;">Best regards,<br>TS Tours Services Team</p>
    </div>
    <div class="footer">
        <p>&copy; 2023 TS Tours Services. All rights reserved.</p>
        <p>Dumaguete, Negros Oriental, Philippines</p>
        <p>Follow us: <a href="https://www.facebook.com/TSTours" style="color: #007bff; text-decoration: none;">Facebook</a></p>
    </div>
</body>
</html>
