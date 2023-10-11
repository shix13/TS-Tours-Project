<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; color: black;border: 1px solid black"">

    <table role="presentation" cellspacing="0" cellpadding="0" width="100%" style="background-color: #f4f4f4; margin: 0; padding: 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" width="600" style="margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #ffffff;">
                    <tr>
                        <td align="center">
                            <img src="{{ asset('images/TsTours.jpg') }}" alt="TS Tours Logo" width="100px">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 style="color: #007bff;">Booking Under Review!</h2>
                            
                            <p><strong>Dear {{ $bookingData['cust_first_name'] }} {{ $bookingData['cust_last_name'] }},</strong></p> 
                            <p>Thank you for your booking with TS Tours Services. Below are the details of your booking:</p> 
                            <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 10px; background-color: #f2f2f2; border: 1px solid #ddd;"><strong>Booking Type:</strong></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $bookingData['bookingType'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; background-color: #f2f2f2; border: 1px solid #ddd;"><strong>Pick-Up Address:</strong></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $bookingData['pickUp_Address'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; background-color: #f2f2f2; border: 1px solid #ddd;"><strong>Pick up Date:</strong></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($bookingData['startDate'])->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; background-color: #f2f2f2; border: 1px solid #ddd;"><strong>Return Date:</strong></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($bookingData['endDate'])->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; background-color: #f2f2f2; border: 1px solid #ddd;"><strong>Pickup Time:</strong></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($bookingData['startDate'])->format('g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; background-color: #f2f2f2; border: 1px solid #ddd;"><strong>Number of Days:</strong></td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">
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
                            
                            <p>If you have any questions or need further assistance, feel free to contact us at <a href="mailto:tstoursduma@gmail.com" style="color: #007bff; text-decoration: none;">tstoursduma@gmail.com</a>.</p>

                            <p>Thank you for choosing TS Tours Services!</p>

                            <p>Best regards,<br>
                            TS Tours Services Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
