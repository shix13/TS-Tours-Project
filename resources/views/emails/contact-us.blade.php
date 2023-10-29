<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <div>
        <div>
            <h2 style="color: #333; font-size: 24px; font-weight: bold;">New Contact Form Submission</h2>
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                You have received a new contact form submission with the following details:
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Name:</strong> {{ $data['name'] }}
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Email:</strong> {{ $data['email'] }}
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Phone:</strong> {{ $data['phone'] }}
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Message:</strong>
                <br>
                {{ $data['message'] }}
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                Please follow up with the sender as necessary.
            </p>
        </div>
    </div>
</body>
</html>
