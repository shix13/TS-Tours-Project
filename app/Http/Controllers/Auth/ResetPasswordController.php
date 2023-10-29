<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Notifications\ResetPasswordNotification; 
use Illuminate\Support\Facades\Password; // Import the Password facade
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    // Show the password reset form with the token
    use ResetsPasswords;

    protected $redirectTo = '/employee';

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    public function reset(Request $request)
    {   
        // 1. Validate the request data
        $this->validate($request, $this->rules(), $this->validationErrorMessages());
    
        // 2. Retrieve the user based on the email provided in the request
        $user = Employee::where('email', $request->email)->first();
    
        if (!$user) {
            // User with the provided email doesn't exist
            return back()->withErrors(['email' => 'Email not found.']); // Customize the error message as needed
        }
    
        // 3. Generate a password reset token
        $token = Password::createToken($user);
    
        // 4. Send the password reset email using the appropriate password broker
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        dd($response);
        return redirect($this->redirectPath())->with('status', trans($response));
    
        // 5. Check the response and handle it
        if ($response == Password::RESET_LINK_SENT) {
            // Password reset link sent successfully
            return redirect($this->redirectPath())->with('status', trans($response));
        } else {
            // Handle the case where sending the reset link failed
            return back()->withInput($request->only('email'))->withErrors(['email' => trans($response)]);
        }
    }
    


}




