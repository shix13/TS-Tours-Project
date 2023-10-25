<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\Employee;


class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

    // Retrieve the user by email using the "employee" guard
    $user = Employee::where('email', $request->email)->first();

    // Use the "employee" guard explicitly
    $token = $this->broker()->createToken($user);
        
    // Send the email using your custom template
    Mail::to($user->email)->send(new ResetPasswordMail($token));

    return back()->with('status', 'Password reset email sent successfully.');
        
    }
    

protected function broker()
{
    return Password::broker('employee'); // Use the "employee" guard for password reset
}

}

