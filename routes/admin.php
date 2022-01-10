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
use App\Http\Controllers\Admin\NewsletterSubscription\NewsletterController;
use App\Http\Controllers\Admin\NewsletterSubscription\SubscriberController;
use App\Http\Controllers\Admin\Ckeditor\CkeditorController;

use App\Http\Controllers\Admin\Notification\NotificationController;
use App\Http\Controllers\Admin\Setting\SettingController;

use App\Http\Controllers\Admin\PromotionVoucher\PromotionVoucherController;
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
    
    $router->post('ckeditor/upload',  [CkeditorController::class,'upload'])->name('ckeditor.upload');

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
    $router->get('active_rider_data', [RiderController::class, 'riderActiveLocationAjax'])->name('rider.active.location.data');
    $router->get('rider/{rider_id}/detail', [RiderController::class, 'getRiderDetail'])->name('rider.detail');
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
    $router->get('nearest_pending_ajax', [BookingController::class, 'getNearestPendingBookingAjax'])->name('pending_booking.ajax');
    $router->post('/booking/change_status', [BookingController::class, 'changeStatusAjax'])->name('booking.change.status');
    $router->get('/booking_location_by_type', [BookingController::class, 'getBookingByType'])->name('booking_by_type.ajax');

    //SOS AND NOTIFICATION
    $router->resource('/sos', SosController::class);
    $router->get('sos_data', [SosController::class, 'getAllData'])->name('sos.data');
    $router->get('/sos/event/{id}', [SosController::class, 'eventcreate'])->name('sos-detail.create');
    $router->post('/sos/event/{id}', [SosController::class, 'eventstore'])->name('sos-detail.store');
    $router->get('/notification/latest/{notification_type}', [NotificationController::class, 'getLatestNotification'])->name('notification.latest');
    // $router->get('vehicle_type_ajax', [VehicleTypeController::class, 'vehicleTypeAjax'])->name('vehicle_type.ajax');
    
    //PUSH NOTIFICATION
    $router->resource('/notification',  NotificationController::class);
    $router->get('push_notification_data', [NotificationController::class, 'getPushNotificationData'])->name('push_notification.data');
    $router->get('notification/{notification_id}/push', [NotificationController::class, 'push_notification'])->name('notification.push');
    $router->get('all_notification', [NotificationController::class, 'all_notification_index'])->name('notification.all');
    $router->get('all_notification_data', [NotificationController::class, 'getAllNotificationData'])->name('all_notification.data');
    //Push Promotion Voucher Notification

    //Read Notifications
    $router->get('/notification/{notification_id}/read', [NotificationController::class, 'read_booking_notification'])->name('notification.read');
    $router->get('/sos/{sos_id}/read', [NotificationController::class, 'read_sos'])->name('sos.read');
    $router->get('/event/{event_id}/read', [NotificationController::class, 'read_event'])->name('event.read');

    //transaction
    $router->resource('/transaction', TransactionController::class);
    $router->get('transaction_data', [TransactionController::class, 'getAllData'])->name('transaction.data');

    //heatmap routes
    $router->get('/map/heatmap', [HeatmapController::class, 'heatmapShow'])->name('map.heatmap');
    $router->get('/map/dispatcher', [HeatmapController::class, 'dispatcherShow'])->name('map.dispatcher');
    $router->get('/map/dispatcher/booking_detail/{booking_id}', [HeatmapController::class, 'getBookingData'])->name('heatmap.dispatcher.booking_data');

    //promotion_voucher
    $router->get('promotion_voucher/generate', [PromotionVoucherController::class, 'getGeneratedCode'])->name('voucher_code.generate');
    $router->get('promotion_voucher/{promotion_voucher_id}/notification', [PromotionVoucherController::class, 'get_voucher_notification'])->name('promotion_voucher.notification.get');
    $router->put('promotion_voucher/{promotion_voucher_id}/notification/save', [PromotionVoucherController::class, 'save_voucher_notification'])->name('promotion_voucher.notification.save');
    $router->post('promotion_voucher/{promotion_voucher_id}/notification/update', [PromotionVoucherController::class, 'update_voucher_notification'])->name('promotion_voucher.notification.update');
    $router->get('promotion_voucher/{promotion_voucher_id}/notification/push', [PromotionVoucherController::class, 'push_voucher_notification'])->name('promotion_voucher.notification.push');
    $router->post('promotion_voucher/{promotion_voucher_id}/notification/save_push', [PromotionVoucherController::class, 'save_push_voucher_notification'])->name('promotion_voucher.notification.save_push');
    $router->post('promotion_voucher/{promotion_voucher_id}/notification/update_push', [PromotionVoucherController::class, 'update_push_voucher_notification'])->name('promotion_voucher.notification.update_push');
    $router->resource('/promotion_voucher', PromotionVoucherController::class);
    $router->get('promotion_voucher_data', [PromotionVoucherController::class, 'getAllData'])->name('promotion_voucher.data');


    //---------------------------------------------------------------------------------------------------------
    //  SETTING RESOURCE ROUTES
    //---------------------------------------------------------------------------------------------------------
    $router->get('/settings/{group}/loadSettingForms', [SettingController::class, 'loadSettingForms'])->name('setting.loadSettingForms');
    $router->resource('setting', SettingController::class);


     //---------------------------------------------------------------------------------------------------------
    //  NEWSLETTER and SUBSCRIBERS RESOURCE ROUTES
    //---------------------------------------------------------------------------------------------------------
    $router->resource('newsletter', NewsletterController::class);
    $router->get('newsletter_data', [NewsletterController::class, 'getAllData'])->name('newsletter.data');
    $router->resource('subscriber', SubscriberController::class);
    $router->get('subscriber_data', [SubscriberController::class, 'getAllData'])->name('subscriber.data');
    $router->patch('newsletter/{newsletter_id}/send', [NewsletterController::class, 'send_newsletter'])->name('newsletter.send');

    
    $router->get('/newsletter/templates/{template_number}',function($template_number){
        switch($template_number)
        {
            case "1":
                return view('admin.email.templates.1')->render();
                break;
            case "2":
                return view('admin.email.templates.2')->render();
                break;
            case "3":
                return view('admin.email.templates.3')->render();
                break;
            default:
                return null;
        }
    });
    // $router->resource('sent_newsletter', SentNewsletterController::class);
    // $router->get('newsletter/{rider_id}/sent', [SentNewsletterController::class, 'history'])->name('newsletter.sent');
    // $router
});
