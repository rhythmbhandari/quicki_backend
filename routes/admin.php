<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Permission\PermissionController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\Vehicle\VehicleController;
use App\Http\Controllers\Admin\Vehicle\VehicleTypeController;
use App\Http\Controllers\Admin\User\CustomerController;
use App\Http\Controllers\Admin\User\RiderController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Booking\BookingController;
use Inertia\Inertia;

Route::get('/test', [UserController::class, 'inertia_test'])->name('test');
// Route::get('/test',function(){
//     // return inertia('Pages/Test');
//     return Inertia::render('Test',[
//         "name"=>"Honj Eod"
//     ]);
// });

Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::get('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::group([
    'as' => 'admin.', 'middleware' =>  ['admin'], 'prefix' => 'admin' // 'middleware' => ['role:ROLE_CANDIDATE'],
], function ($router) {
    $router->get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //user
    $router->resource('/user', UserController::class);
    $router->get('user-data', [UserController::class, 'getAllData'])->name('user.data');

    //role
    $router->resource('/role', RoleController::class);
    $router->get('role_data', [RoleController::class, 'getAllData'])->name('role.data');

    //permission
    $router->resource('/permission', PermissionController::class);
    $router->get('permission_data', [PermissionController::class, 'getAllData'])->name('permission.data');

    //vehicle
    $router->resource('/vehicle', VehicleController::class);
    $router->get('vehicle_data', [VehicleController::class, 'getAllData'])->name('vehicle.data');

    //customer
    $router->resource('/customer', CustomerController::class);
    $router->get('customer_data', [CustomerController::class, 'getAllData'])->name('customer.data');
    $router->get('customer_ajax', [CustomerController::class, 'customerAjax'])->name('customer.ajax');

    //rider
    $router->resource('/rider', RiderController::class);
    $router->get('rider_data', [RiderController::class, 'getAllData'])->name('rider.data');
    $router->get('rider_ajax', [RiderController::class, 'riderAjax'])->name('rider.ajax');

    //vehicle type
    $router->resource('/vehicle_type', VehicleTypeController::class);
    $router->get('vehicle_type_data', [VehicleTypeController::class, 'getAllData'])->name('vehicle_type.data');
    $router->get('vehicle_type_ajax', [VehicleTypeController::class, 'vehicleTypeAjax'])->name('vehicle_type.ajax');

    //booking
    $router->resource('/booking', BookingController::class);
    $router->get('booking_data', [BookingController::class, 'getAllData'])->name('booking.data');
    $router->get('estimated_price', [BookingController::class, 'estimatedPriceAjax'])->name('booking.price');
    // $router->get('vehicle_type_ajax', [VehicleTypeController::class, 'vehicleTypeAjax'])->name('vehicle_type.ajax');

    //SOS
    $router->resource('/sos', SosController::class);
    $router->get('sos_data', [SosController::class, 'getAllData'])->name('sos.data');
    // $router->get('vehicle_type_ajax', [VehicleTypeController::class, 'vehicleTypeAjax'])->name('vehicle_type.ajax');
});
