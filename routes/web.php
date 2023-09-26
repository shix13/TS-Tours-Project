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
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\RemittanceController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TestController;

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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();

//VISITOR
Route::get('/home', [App\Http\Controllers\VisitorController::class, 'tsdefault'])->name('home');
Route::get('/aboutus', [App\Http\Controllers\VisitorController::class, 'tsabout'])->name('aboutus');
Route::get('/fleet', [App\Http\Controllers\VisitorController::class, 'tsfleet'])->name('fleet');
Route::get('/contactus', [App\Http\Controllers\VisitorController::class, 'tscontact'])->name('contactus');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/logout', [App\Http\Controllers\Auth\LoginController::class, 'userlogout'])->name('user.logout');

Route::get('/browsevehicles', [App\Http\Controllers\CustomerController::class, 'getVehicles'])->name('browsevehicles');
Route::get('/bookvehicle{vehicle}', [App\Http\Controllers\CustomerController::class, 'book'])->name('bookvehicle');

Route::post('/processbooking', [App\Http\Controllers\CustomerController::class, 'processBooking'])->name('processbooking');
Route::post('/checkout', [App\Http\Controllers\CustomerController::class, 'storeBooking'])->name('checkout');
Route::get('/bookingstatus{booking}', [App\Http\Controllers\CustomerController::class, 'bookingStatus'])->name('bookingstatus');

Route::get('/approvedbookingstatus{booking}', [App\Http\Controllers\CustomerController::class, 'bookingApproved'])->name('bookingapproved');
Route::get('/deniedbookingstatus{booking}', [App\Http\Controllers\CustomerController::class, 'bookingDenied'])->name('bookingdenied');

Route::get('/TShome', [App\Http\Controllers\CustomerController::class, 'customerHome'])->name('tshome');
Route::get('/Profile', [App\Http\Controllers\CustomerController::class, 'profile'])->name('profile');

Route::get('/bookingdashboard', [App\Http\Controllers\CustomerController::class, 'bookingIndex'])->name('bookingdashboard');


Route::prefix('employee')->group(function(){
    //LOGIN ACCOUNT
     Route::get('/login', [App\Http\Controllers\Auth\EmployeeController::class, 'showLoginForm'])->name('employee.login');
     Route::post('/loginsubmit', [App\Http\Controllers\Auth\EmployeeController::class, 'login'])->name('employee.login.submit');

    Route::middleware('auth:employee')->group(function () {
    //ACCOUNTS
        //Route::middleware(['manager'])->group(function (){
           Route::get('/register', [EmployeeController::class, 'showRegisterForm'])->name('employee.register');
           Route::get('/account/{empID}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
           Route::put('/account/{empID}', [EmployeeController::class, 'update'])->name('employee.update');
           Route::get('/account', [AccountsController::class, 'accountIndex'])->name('employee.accounts');
           Route::delete('/employee/{empID}', [EmployeeController::class, 'delete'])->name('employee.delete');
       // });

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
        Route::get('/vehicle-typesView', [VehicleController::class, 'newVType'])->name('vehicleTypes.view');
        Route::get('/vehicle-typesCreate', [VehicleController::class, 'VtypeCreate'])->name('vehicleTypes.create');
        Route::post('/vehicle-types/store', [VehicleController::class, 'VtypeStore'])->name('vehicleTypes.store');
        Route::delete('/vehicle-types/{vehicle_type}', [VehicleController::class, 'VtypeDestroy'])->name('vehicleTypes.destroy');


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
        Route::get('/approve-booking/{bookingId}', [BookingRentalController::class, 'approveBooking'])->name('employee.approveBooking');
        Route::put('/deny-booking/{bookingId}', [BookingRentalController::class, 'denyBooking'])->name('employee.denyBooking');
        Route::get('/rentalView{id}', [BookingRentalController::class, 'rentalView'])->name('employee.rentalView');
        Route::put('/rental/{id}', [BookingRentalController::class, 'update'])->name('rental.update');
        Route::get('/PreApproved', [BookingRentalController::class, 'preApproved'])->name('employee.preapproved');


        Route::get('/bookingAssign{bookingId}', [BookingRentalController::class, 'bookAssign'])->name('booking.Assign');
        Route::post('/storeAssign}', [BookingRentalController::class, 'storeAssigned'])->name('booking.storeAssign');

    //REMITTANCE
        Route::get('/remittance', [RemittanceController::class, 'remittanceIndex'])->name('employee.remittance');
        Route::get('/remittance/selectrent', [RemittanceController::class, 'rentIndex'])->name('remittance.select-rent');
        Route::get('/remittance/{id}/remittancecreate', [RemittanceController::class, 'create'])->name('remittance.create');
        Route::post('/remittance/store', [RemittanceController::class, 'store'])->name('remittance.store');

    //REPORTS
        Route::get('/reports', [ReportsController::class, 'processReports'])->name('employee.reports');      

    //DASHBOARD
        Route::get('/', [App\Http\Controllers\Auth\EmployeeController::class, 'showDashboard'])->name('employee.dashboard');

    });
});

//Route::prefix('test')->group(function(){
    Route::get('/selectvehicles', [TestController::class, 'getVehicles'])->name('selectvehicles');
    Route::post('/createbooking', [TestController::class, 'proceedBooking'])->name('createbooking');
    Route::post('/processbookingreq', [TestController::class, 'processBooking'])->name('processbookingreq');
    Route::get('/search', [TestController::class, 'searchView'])->name('search');
    Route::post('/search/booking', [TestController::class, 'processSearch'])->name('searchbooking');
    Route::get('/checkbookingstatus{booking}', [TestController::class, 'bookingStatus'])->name('checkbookingstatus');
    Route::post('/checkoutbooking', [TestController::class, 'checkout'])->name('checkoutbooking');
    //});
