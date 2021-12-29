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
use App\Http\Controllers\Admin\Payment\TransactionController;
use App\Http\Controllers\Admin\Heatmap\HeatmapController;
use App\Http\Controllers\Admin\Sos\SosController;
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
    $router->get('rider_commission', [RiderController::class, 'riderCommission'])->name('rider.commission');
    $router->get('rider_commission_data', [RiderController::class, 'riderCommissionData'])->name('rider.commission.data');
    $router->get('rider/{rider_id}/clear_commission', [RiderController::class, 'clearCommission'])->name('rider.commission_clear');
    $router->get('rider/{rider_id}/make_payment', [RiderController::class, 'makePaymentModal'])->name('rider.make_payment_modal');
    $router->get('rider/{rider_id}/history', [RiderController::class, 'history'])->name('rider.history');
    // $router->get('rider/{user_id}/transaction_data', [TransactionController::class, 'getRiderData'])->name('rider.transaction.data');

    //vehicle type
    $router->resource('/vehicle_type', VehicleTypeController::class);
    $router->get('vehicle_type_data', [VehicleTypeController::class, 'getAllData'])->name('vehicle_type.data');
    $router->get('vehicle_type_ajax', [VehicleTypeController::class, 'vehicleTypeAjax'])->name('vehicle_type.ajax');

    //booking
    $router->resource('/booking', BookingController::class);
    $router->get('booking_data', [BookingController::class, 'getAllData'])->name('booking.data');
    $router->get('estimated_price', [BookingController::class, 'estimatedPriceAjax'])->name('booking.price');
    $router->get('booking_ajax', [BookingController::class, 'bookingAjax'])->name('booking.ajax');

    //SOS
    $router->resource('/sos', SosController::class);
    $router->get('sos_data', [SosController::class, 'getAllData'])->name('sos.data');
    $router->get('/sos/event/{id}', [SOSController::class, 'eventcreate'])->name('sos-detail.create');
    $router->post('/sos/event/{id}', [SOSController::class, 'eventstore'])->name('sos-detail.store');
    // $router->get('vehicle_type_ajax', [VehicleTypeController::class, 'vehicleTypeAjax'])->name('vehicle_type.ajax');

    //transaction
    $router->resource('/transaction', TransactionController::class);
    $router->get('transaction_data', [TransactionController::class, 'getAllData'])->name('transaction.data');

    //heatmap routes
    $router->get('/heatmap/booking', [HeatmapController::class, 'bookingHeatMap'])->name('heatmap.booking');
    $router->get('/heatmap/booking/{booking_id}', [HeatmapController::class, 'getBookingInfo'])->name('heatmap.booking.info');
    // $router->get('/heatmap/booking/', [HeatmapController::class, 'getRiderInfo'])->name('heatmap.booking');
});
