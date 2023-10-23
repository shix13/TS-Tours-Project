<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Denied</title>
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
            <h4>Booking Denied</h4>
        </div>

        <div class="content">
            <p>
                Dear {{ $booking->cust_first_name }} {{ $booking->cust_last_name }},<br>
                We regret to inform you that your booking with ID: <strong>{{ $booking->reserveID }}</strong> has been denied.
                <br>Please contact us for further details or to discuss alternative options.
            </p>

            <p>
                If you have any questions or need further assistance, feel free to contact us at 0995-132-0184 or
                <a href="mailto:tstoursduma@gmail.com">tstoursduma@gmail.com</a>.
            </p>
        </div>

        <div class="footer">
            Thank you for considering our services!<br>
            Best regards,<br>
            TS Tours Services
        </div>
    </div>
</body>

</html>
