<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmployeeController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\TariffController;
use app\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BookingRentalController;



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

Route::get('/browsevehicles', [App\Http\Controllers\CustomerController::class, 'getVehicles'])->name('browsevehicles');














Route::prefix('employee')->group(function(){
    //LOGIN ACCOUNT
     Route::get('/login', [App\Http\Controllers\Auth\EmployeeController::class, 'showLoginForm'])->name('employee.login');
     Route::post('/login', [App\Http\Controllers\Auth\EmployeeController::class, 'login'])->name('employee.login.submit');

    //Route::middleware('auth:employee')->group(function () {
    //ACCOUNTS
        //Route::middleware(['manager'])->group(function (){
           Route::get('/register', [EmployeeController::class, 'showRegisterForm'])->name('employee.register');
           Route::get('/account/{empID}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
           Route::put('/account/{empID}', [EmployeeController::class, 'update'])->name('employee.update');
           Route::get('/account', [AccountsController::class, 'accountIndex'])->name('employee.accounts');
           Route::delete('/employee/{empID}', [EmployeeController::class, 'delete'])->name('employee.delete');
        //});

        Route::post('/register', [App\Http\Controllers\Auth\EmployeeController::class, 'register'])->name('employee.register.submit');
        Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    
    //LOGOUT
        Route::get('/logout', [App\Http\Controllers\Auth\EmployeeController::class, 'logout'])->name('employee.logout');

    //PAGES
    //--Vehicle
        Route::get('/vehicles', [VehicleController::class, 'vehicleIndex'])->name('employee.vehicle');
        Route::get('/vehiclescreate', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles/save', [VehicleController::class, 'store'])->name('vehicles.save');
        Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
        Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');

          

    //--Tariffs
        Route::get('/tariff', [TariffController::class, 'tariffIndex'])->name('employee.tariff'); 
        Route::get('/tariffcreate', [TariffController::class, 'create'])->name('tariffs.create');
        Route::post('/tariff/save', [TariffController::class, 'store'])->name('tariffs.save');
        Route::get('/tariff/{id}/edit', [TariffController::class, 'edit'])->name('tariff.edit');
        Route::delete('/tariff/{id}', [TariffController::class, 'destroy'])->name('tariff.destroy');
        Route::put('/tariff/{id}', [TariffController::class, 'update'])->name('tariffs.update');

    //--Maintenance
        
        // Routes for viewing the maintenance records
        Route::get('/maintenance', [MaintenanceController::class, 'maintenanceIndex'])->name('employee.maintenance');
        Route::get('/maintenanceCreate', [MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('/maintenance/store', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::put('/maintenance/{id}/update', [MaintenanceController::class, 'update'])->name('maintenance.update');
        Route::delete('/maintenance/{id}/delete', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy');

    //BOOKING AND RENTAL
        Route::get('/booking', [BookingRentalController::class, 'bookIndex'])->name('employee.booking'); //BOOKING LIST
        Route::get('/rents', [BookingRentalController::class, 'rentIndex'])->name('employee.rental');
        Route::put('/approve-booking/{bookingId}', [BookingRentalController::class, 'approveBooking'])->name('employee.approveBooking');
        Route::put('/deny-booking/{bookingId}', [BookingRentalController::class, 'denyBooking'])->name('employee.denyBooking');
        Route::get('/rentalView/{id}', [BookingRentalController::class, 'rentalView'])->name('employee.rentalView');
        Route::put('/rental/{id}', [BookingRentalController::class, 'rentalView'])->name('rental.update');

    //PAYMENT
        Route::get('/payment', [PaymentController::class, 'payIndex'])->name('employee.pay');

    //DASHBOARD
        Route::get('/', [App\Http\Controllers\Auth\EmployeeController::class, 'showDashboard'])->name('employee.dashboard');

    //});
});
