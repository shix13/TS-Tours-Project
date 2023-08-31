<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use app\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\ManagerMiddleware;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/logout', [App\Http\Controllers\Auth\LoginController::class, 'userlogout'])->name('user.logout');


Route::prefix('employee')->group(function(){
    //LOGIN ACCOUNT
     Route::get('/login', [App\Http\Controllers\Auth\EmployeeController::class, 'showLoginForm'])->name('employee.login');
     Route::post('/login', [App\Http\Controllers\Auth\EmployeeController::class, 'login'])->name('employee.login.submit');

    Route::middleware('auth:employee')->group(function () {
    //REGISTER ACCOUNT
        //Route::middleware([App\Http\Middleware\ManagerMiddleware::class, 'Manager'])->group(function () {
           Route::get('/register', [App\Http\Controllers\Auth\EmployeeController::class, 'showRegisterForm'])->name('employee.register');
        //});

        Route::post('/register', [App\Http\Controllers\Auth\EmployeeController::class, 'register'])->name('employee.register.submit');
   
    
    //LOGOUT
        Route::get('/logout', [App\Http\Controllers\Auth\EmployeeController::class, 'logout'])->name('employee.logout');
    
    //DASHBOARD
        Route::get('/', [App\Http\Controllers\Auth\EmployeeController::class, 'showDashboard'])->name('employee.dashboard');

    });
});
