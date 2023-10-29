<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Password Reset</h2>
    <p>Hello!</p>
    <p>You are receiving this email because we received a password reset request for your account with the email: {{ $email }}.</p>
    <p>
        <a href="{{ $resetUrl . '?token=' . $token }}">
            <button style="background-color: #4CAF50; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Reset Password
            </button>
        </a>
    </p>
    <p>This password reset link will expire in {{ $count }} minutes. Click the following link to reset your password:</p>
    <p><a href="{{ $resetUrl . '?token=' . $token }}">{{ $resetUrl . '?token=' . $token }}</a></p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,<br>TS Tours Services!</p>
</body>
</html>
