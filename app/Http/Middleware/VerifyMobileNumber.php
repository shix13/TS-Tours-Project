<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Booking; 
class VerifyMobileNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        public function handle($request, Closure $next)
    {     
        $reserveID = $request->input('reserveID');
        $mobile = $request->input('mobile');

        $booking = Booking::where('reserveID', $reserveID)
            ->where('mobileNum', $mobile)
            ->first();
       
        if (!$booking) {
            return redirect()->route('search')->with('error', 'Booking not found, check the details you inputted');
        }

        return $next($request);
    }

}
