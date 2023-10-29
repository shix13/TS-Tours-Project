<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <div>
        <div>
            <h2 style="color: #333; font-size: 24px; font-weight: bold;">New Feedback Received</h2>
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                You have received feedback for a rental with the following details:
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Tracking ID:</strong> {{ $rent->reserveID }}
            </p>

            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Rating:</strong> {{ $feedback->rating }}
            </p>
            
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                <strong>Feedback Message:</strong>
                <br>
                {{ $feedback->feedback_Message }}
            </p>
            
            <p style="color: #555; font-size: 16px; line-height: 1.6;">
                Your organization has received valuable feedback from a customer. Please review the details below and take appropriate actions as necessary.
            </p>
        </div>
    </div>
</body>
</html>
