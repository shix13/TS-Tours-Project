<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Password;
use App\Notifications\ResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    // Show the password reset request form
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    
        $response = $this->broker()->sendResetLink($request->only('email'));
    
        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset email sent.')
            : back()->withErrors(['email' => trans($response)]);
    }
    

 
    
    
}
