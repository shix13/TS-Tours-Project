<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmployeeController;
use App\Http\Controllers\Auth\RegisterController;
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
use App\Http\Controllers\MailController;
use App\Http\Controllers\Auth\DriverLoginController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\VehicleTracking;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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

Route::middleware(['auth.guard:employee','manager'])->group(function (){
     Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
});

Route::post('/contact', [TestController::class, 'sendEmail'])->name('contact.send');
//VISITOR
//Route::get('/home', [App\Http\Controllers\VisitorController::class, 'tsdefault'])->name('home');
Route::get('/', [App\Http\Controllers\VisitorController::class, 'tsdefault'])->name('home');
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
     

     //Route::get('/register', [EmployeeController::class, 'showRegisterForm'])->name('employee.register');
     //Route::post('/register', [App\Http\Controllers\Auth\EmployeeController::class, 'register'])->name('employee.register.submit');
    
    Route::middleware('auth.guard:employee')->group(function () {
    //ACCOUNTS
        Route::middleware(['manager'])->group(function (){
        Route::get('/register', [EmployeeController::class, 'showRegisterForm'])->name('employee.register');
        Route::get('/account/{empID}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::put('/account/{empID}', [EmployeeController::class, 'update'])->name('employee.update');
        Route::get('/account', [AccountsController::class, 'accountIndex'])->name('employee.accounts');
        Route::delete('/employee/{empID}', [EmployeeController::class, 'delete'])->name('employee.delete');
        Route::get('/reports', [ReportsController::class, 'processReports'])->name('employee.reports');      
        
        //REPORTS
        Route::match(['get', 'post'], '/fetch-data', [ReportsController::class, 'fetchData'])->name('fetchData');
        
        });

        Route::post('/register', [App\Http\Controllers\Auth\EmployeeController::class, 'register'])->name('employee.register.submit');
        Route::get('/change-password', [EmployeeController::class, 'profile'])->name('employee.password');
        Route::post('/change-password', [EmployeeController::class, 'changePassword'])->name('password.update');
        

    
    //LOGOUT
        Route::get('/logout', [App\Http\Controllers\Auth\EmployeeController::class, 'logout'])->name('employee.logout');

    //PAGES
    //--Vehicle
        Route::get('/vehicles', [VehicleController::class, 'vehicleIndex'])->name('employee.vehicle');
        Route::get('/retiredvehicles', [VehicleController::class, 'retiredIndex'])->name('vehicles.vehicleRetired');
        Route::get('/vehiclescreate', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles/save', [VehicleController::class, 'store'])->name('vehicles.save');
        Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
        Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::get('/vehicle-typesView', [VehicleController::class, 'newVType'])->name('vehicleTypes.view');
        Route::get('/vehicle-typesCreate', [VehicleController::class, 'VtypeCreate'])->name('vehicleTypes.create');
        Route::post('/vehicle-types/store', [VehicleController::class, 'VtypeStore'])->name('vehicleTypes.store');
        Route::delete('/vehicle-types/{vehicle_type}', [VehicleController::class, 'VtypeDestroy'])->name('vehicleTypes.destroy');
        Route::get('/vehicle-types/{vehicle_type}/edit', [VehicleController::class, 'VtypeEdit'])->name('vehicleTypes.edit');
        Route::put('/vehicles-types/{vehicle_type}', [VehicleController::class, 'VtypeUpdate'])->name('vehiclesTypes.update');


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
        
        Route::get('/maintenance/updateMechanic/{id}/{mechanic_id}', [MaintenanceController::class, 'updateMechanic'])->name('maintenance.updateMechanic');
        Route::get('/maintenanceHistory', [MaintenanceController::class, 'history'])->name('maintenance.history');
        Route::get('/maintenanceCreate', [MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('/maintenance/store', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::put('/maintenance/{id}/update', [MaintenanceController::class, 'update'])->name('maintenance.update');
        Route::delete('/maintenance/{id}/delete', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy');

    //BOOKING AND RENTAL
        Route::get('/booking', [BookingRentalController::class, 'bookIndex'])->name('employee.booking'); //BOOKING LIST
        Route::get('/rents', [BookingRentalController::class, 'rentIndex'])->name('employee.rental');
        Route::get('/rentbookinghistory', [BookingRentalController::class, 'rentHistory'])->name('employee.rentalHistory');
        Route::get('/approve-booking/{bookingId}', [BookingRentalController::class, 'approveBooking'])->name('employee.approveBooking');
        Route::put('/deny-booking/{bookingId}', [BookingRentalController::class, 'denyBooking'])->name('employee.denyBooking');
        Route::get('/rentalView{id}', [BookingRentalController::class, 'rentalView'])->name('employee.rentalView');
        Route::put('/rental/{id}', [BookingRentalController::class, 'update'])->name('rental.update');
        Route::get('/PreApproved', [BookingRentalController::class, 'preApproved'])->name('employee.preapproved');
        Route::get('/payment-history', [BookingRentalController::class, 'paymentHistory'])->name('employee.paymentHistory');
        Route::post('/payment-history', [BookingRentalController::class, 'uploadQR'])->name('employee.uploadQR');
        Route::get('/bookingAssign{bookingId}', [BookingRentalController::class, 'bookAssign'])->name('booking.Assign');
        Route::post('/storeAssign}', [BookingRentalController::class, 'storeAssigned'])->name('booking.storeAssign');

    //VEHICLE TRACKING
        Route::get('/vehicle-tracking', [VehicleTracking::class, 'vehicleIndex'])->name('employee.vehicleTracking');
        Route::get('/vehicle-tracking-update', [VehicleTracking::class, 'vehicleIndex'])->name('get.updated.data');
    //REMITTANCE
        Route::get('/remittance', [RemittanceController::class, 'remittanceIndex'])->name('employee.remittance');
        Route::get('/remittance/newremittance/selectrent', [RemittanceController::class, 'rentIndex'])->name('remittance.select-rent');
        //Route::get('/remittance/cashreturns/selectrent', [RemittanceController::class, 'returnIndex'])->name('remittance.select-return');
        Route::get('/remittance/{id}/remittancecreate', [RemittanceController::class, 'create'])->name('remittance.create');
        Route::post('/remittance/store', [RemittanceController::class, 'store'])->name('remittance.store');

    
    //Feedback
        Route::get('/feedback', [BookingRentalController::class, 'feedbackIndex'])->name('view.feedback');
    //DASHBOARD
        Route::get('/', [App\Http\Controllers\Auth\EmployeeController::class, 'showDashboard'])->name('employee.dashboard');

    });
});
Route::prefix('driver')->group(function(){
    Route::get('/login', [DriverLoginController::class, 'showLoginForm'])->name('driver.login');
    Route::post('/loginsubmit', [DriverLoginController::class, 'login'])->name('driver.login.submit');

    //auth:employee is temporary, may need to find a way to only let drivers in
    Route::middleware('auth:driver')->group(function () {
        Route::get('/activeTask', [DriverController::class, 'showActive'])->name('driver.active');
        Route::post('/complete-rent', [DriverController::class, 'completeRent'])->name('driver.complete');
        Route::post('/store-geolocation', [GeolocationController::class, 'store'])->name('driver.store');

        Route::get('/upcomingTasks', [DriverController::class, 'showUpcoming'])->name('driver.upcoming');
        Route::post('/start-rent', [DriverController::class, 'startRent'])->name('driver.start');
        
        Route::get('/logout', [DriverLoginController::class, 'logout'])->name('driver.logout');
    });
});
//Route::prefix('test')->group(function(){
    Route::get('/selectvehicles', [TestController::class, 'getVehicles'])->name('selectvehicles');
    Route::post('/createbooking', [TestController::class, 'proceedBooking'])->name('createbooking');
    Route::post('/processbookingreq', [TestController::class, 'processBooking'])->name('processbookingreq');
    Route::get('/search', [TestController::class, 'searchView'])->name('search');
    Route::post('/search/booking', [TestController::class, 'processSearch'])->name('searchbooking');
    Route::get('/checkbookingstatus{booking}', [TestController::class, 'bookingStatus'])->name('checkbookingstatus') ; //->middleware('verifyMobileNumber');
    Route::post('/checkoutbooking', [TestController::class, 'checkout'])->name('checkoutbooking');
    Route::get('/feedback{id}', [TestController::class, 'feedback'])->name('create.feedback');
    Route::post('/feedbackstore', [TestController::class, 'store'])->name('feedback.store');
    //});

    //Dont move this! AJAX Query 
    Route::get('/get-available-schedules/{vehicleId}', [MaintenanceController::class, 'getAvailableSchedules'])->name('get-available-schedules');
    

    Route::get('generate-pdf', [ReportsController::class, 'generatePDF'])->name('generate-pdf');

    //forgot password
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.change');
    

