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
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
        }

        .header h2 {
            color: #ff6347; /* Tomato Red */
            font-size: 28px;
            margin-top: 10px;
        }

        .content {
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        .content p {
            margin-bottom: 15px;
        }

        .content a {
            color: #007bff;
            text-decoration: none;
        }

        .footer {
            font-size: 14px;
            color: #555;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/TsTours.jpg') }}" alt="TS Tours Logo">
            <h2>Booking Denied</h2>
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
