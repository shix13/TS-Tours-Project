<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Pre-Approval Notification</title>
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
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
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
    <div class="container" style="border: 1px solid black">
        <div class="header">
            <img src="{{ asset('images/TsTours.jpg') }}" alt="TS Tours Logo" width="100px">
            <h2 style="color: #007bff;">Booking Approved!</h2>
        </div>

        <div class="content">
            <p>
                Dear {{ $customerFirstName }} {{ $customerLastName }},<br>
                Thank you for choosing TS Tours Services. Your booking with ID: <strong>{{ $reserveID }}</strong> has been approved!
                To proceed, please click the following link to pay the down payment fee.
            </p>

            <p>
                <a href="{{ route('checkbookingstatus', ['booking' => $reserveID]) }}" class="button" style="background: orangered;color:black">Pay Down Payment Fee</a>
            </p>

            <p>
                If you have any questions or need further assistance, feel free to contact us at 0995-132-0184 or
            </p>
            <p>
                <a href="mailto:tstoursduma@gmail.com" style="color: #007bff; text-decoration: none;">tstoursduma@gmail.com</a>.
            </p>
            
        </div>

        <div class="footer">
            Thank you for choosing our services!<br>
            Best regards,<br>
            TS Tours Services
        </div>
    </div>
</body>

</html>
