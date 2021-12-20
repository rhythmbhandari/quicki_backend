<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\RiderController;
use App\Http\Controllers\Api\Location\RiderLocationController;
use App\Http\Controllers\Api\Vehicle\VehicleTypeController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Booking\CompletedTripController;
use App\Http\Controllers\Api\Review\ReviewController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Vehicle\VehicleController;
use App\Http\Controllers\Api\Suggestion\SuggestionController;
use App\Http\Controllers\Api\Notification\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->get('/notification/test', [NotificationController::class, 'test_notification'])->name('notification.test');


Route::group(['as' => 'api.', ], function ($router) {
  //test
  $router->post('test',[ApiAuthController::class, 'test'])->name('test'); 

  //---------------------------------------------------------------------------------------------------------
  //  REGISTER/AUTHENTICATION and SMS/OTP VERIFICATION ROUTES
  //---------------------------------------------------------------------------------------------------------
  $router->post('/user/register', [ApiAuthController::class, 'register'])->name('user.register'); //stores the user (customer) data 
  $router->post('/user/login', [ApiAuthController::class, 'login'])->name('user.login');         //logins the user (customer)

  $router->post('/rider/register', [ApiAuthController::class, 'rider_register'])->name('rider.register'); //stores the rider data 
  $router->post('/rider/login', [ApiAuthController::class, 'rider_login'])->name('rider.login');         //logins the rider

  $router->post('/social/login', [ApiAuthController::class, 'social_login'])->name('social.login');         //social login

  $router->post('/sms/send',[ApiAuthController::class, 'send_otp'])->name('sms.send');   //Sends SMS to the provided number
  $router->post('/sms/verify_user',[ApiAuthController::class, 'verify_user_otp'])->name('sms.verify_user');   //Sends SMS to the provided number
  $router->post('/sms/verify_rider',[ApiAuthController::class, 'verify_rider_otp'])->name('sms.verify_rider');   //Sends SMS to the provided number
  //Route::post('/social/login', [ApiAuthController::class, 'socialLogin'])->name('socialLogin.api');
  //Route::post('/verify-customer', [ApiAuthController::class, 'verifyCutomerAttributes'])->name('verifyCustomer.api');


  $router->get('/vehicle_type/get_all_data', [VehicleTypeController::class, 'get_all_data'])->name('vehicle_type.get_all_data');
    
 

});

//Requires valid token
Route::group(['as' => 'api.', 'middleware' => 'auth:api'], function ($router) {

  
  
  //---------------------------------------------------------------------------------------------------------
  //  USER UPGRADE TO RIDER
  //---------------------------------------------------------------------------------------------------------
  $router->post('/user/upgrade_to_rider', [ApiAuthController::class, 'upgrade_to_rider'])->name('user.upgrade_to_rider');


  //---------------------------------------------------------------------------------------------------------
  //  BOOKING and COMPLETED TRIP ROUTES
  //---------------------------------------------------------------------------------------------------------
  // $router->get('/test', [BookingController::class, 'testFunction'])->name('booking.test');
  
  $router->post('/booking/create', [BookingController::class, 'store'])->name('booking.store');
  $router->post('/booking/change_status', [BookingController::class, 'change_status'])->name('booking.change_status');
  $router->get('/booking/{booking_id}', [BookingController::class, 'getBooking'])->name('user.booking.show');         ///TO BE 
  $router->get('/user/booking/active', [BookingController::class, 'getActiveUserBooking'])->name('user.booking.active');
  $router->get('/rider/booking/active', [BookingController::class, 'getActiveRiderBooking'])->name('rider.booking.active');
  $router->get('/user/booking/history', [CompletedTripController::class, 'getUserTrips'])->name('user.booking.history');
  $router->get('/rider/booking/history', [CompletedTripController::class, 'getRiderTrips'])->name('rider.booking.history');

  $router->get('/{user_type}/total_distance', [CompletedTripController::class, 'getTotalDistance'])->name('completed_trip.total_distance');
  $router->get('/{user_type}/total_trips', [CompletedTripController::class, 'getTotalTrips'])->name('completed_trip.total_trips');

  $router->post('/booking/estimated_price', [BookingController::class, 'getEstimatedPrice'])->name('booking.estimated_price');

  
  $router->get('/user/vehicle_type/{vehicle_type_id}/booking/{booking_status}/history', [CompletedTripController::class, 'getUserVehicleTypeBookingHistory'])->name('user.booking.vehicle_type_history');
  $router->get('/rider/vehicle_type/{vehicle_type_id}/booking/{booking_status}/history', [CompletedTripController::class, 'getRiderVehicleTypeBookingHistory'])->name('rider.booking.vehicle_type_history');

  //---------------------------------------------------------------------------------------------------------
  //  AVAILABLE AND ONLINE/OFFLINE RIDERS/USERS
  //---------------------------------------------------------------------------------------------------------
  $router->post('/riders/available', [RiderLocationController::class, 'getAvailableRiders'])->name('rider.available');
  $router->get('/users/available', [RiderLocationController::class, 'getAvailableUsers'])->name('users.available');
  $router->post('/rider/online', [RiderLocationController::class, 'getRiderOnline'])->name('rider.online');
  $router->post('/rider/offline', [RiderLocationController::class, 'getRiderOffline'])->name('rider.online');



  
  //---------------------------------------------------------------------------------------------------------
  //  REVIEWS
  //---------------------------------------------------------------------------------------------------------
  $router->post('/review/create', [ReviewController::class, 'store'])->name('review.store');
  $router->get('/user/{user_id}/reviews', [ReviewController::class, 'getUserReviews'])->name('review.user.reviews');
  $router->get('/rider/{rider_id}/reviews', [ReviewController::class, 'getRiderReviews'])->name('review.rider.reviews');

  //---------------------------------------------------------------------------------------------------------
  //  USER ROUTES
  //---------------------------------------------------------------------------------------------------------
  $router->get('/user/details', [UserController::class, 'getDetails'])->name('user.details');
  $router->get('/user/{user_id}/details', [UserController::class, 'getDetails'])->name('user.specific.details');
  $router->post('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
  $router->post('/user/location/update', [UserController::class, 'update_location'])->name('user.location.update');



  //---------------------------------------------------------------------------------------------------------
  //  RIDER ROUTES
  //---------------------------------------------------------------------------------------------------------
  $router->get('/rider/details', [RiderController::class, 'getDetails'])->name('rider.details');
  $router->get('/rider/{rider_id}/details', [RiderController::class, 'getDetails'])->name('rider.specific.details');
  $router->post('/rider/profile/update', [RiderController::class, 'updateProfile'])->name('rider.profile.update');
  $router->get('/rider/{rider_id}/location', [RiderLocationController::class, 'getRiderLocation'])->name('rider.specific.location');


  //---------------------------------------------------------------------------------------------------------
  //  EMERGENCY CONTACTS
  //---------------------------------------------------------------------------------------------------------
  $router->get('/user/emergency_contacts',[UserController::class, 'getEmergencyContacts'])->name('user.emergency_contacts.show');
  $router->get('/user/{user_id}/emergency_contacts',[UserController::class, 'getUserEmergencyContacts'])->name('user.emergency_contacts.get');
  $router->post('/user/emergency_contacts/update',[UserController::class, 'updateEmergencyContacts'])->name('user.emergency_contacts.update');


  //---------------------------------------------------------------------------------------------------------
  //  DOCUMENT
  //---------------------------------------------------------------------------------------------------------
  $router->post('/document/create', [DocumentController::class, 'store'])->name('document.create');
  $router->post('/document/{document_id}/update', [DocumentController::class, 'update'])->name('document.update');
  $router->get('/document/{document_id}/details', [DocumentController::class, 'getDocument'])->name('document.details');


  //---------------------------------------------------------------------------------------------------------
  //  VEHICLE
  //---------------------------------------------------------------------------------------------------------
  $router->post('/vehicle/create', [VehicleController::class, 'store'])->name('vehicle.create');
  $router->post('/vehicle/{vehicle_id}/update', [VehicleController::class, 'update'])->name('vehicle.update');
  $router->get('/vehicle/{vehicle_id}/details', [VehicleController::class, 'getVehicle'])->name('vehicle.details');
  $router->get('/rider/vehicle_details', [VehicleController::class, function(){return response(['a'=>'b'],500);}])->name('rider.vehicle.details');


  //---------------------------------------------------------------------------------------------------------
  //  SUGGESTION
  //---------------------------------------------------------------------------------------------------------
  $router->get('/suggestion/{suggestion_type}/', [SuggestionController::class, 'getSuggestions'])->name('suggestion.suggestions');



  //---------------------------------------------------------------------------------------------------------
  //  AUTH ROUTES
  //---------------------------------------------------------------------------------------------------------
  $router->post('/logout', [ApiAuthController::class, 'logout'])->name('logout');

  



  
 
  // $router->post('/edit/profile', [ApiAuthController::class, 'editProfile'])->name('edit.profile');
  // $router->get('/show/profile', [ApiAuthController::class, 'showProfile'])->name('show.profile');
  // $router->post('/forgot-password', [ApiAuthController::class, 'forgotPassword'])->name('forgot.password');

  // $router->get('/auth_test', [VehicleTypeController::class, 'get_all_data'])->name('auth_test');


    
    //Booking
    // $router->post('/booking/create', [BookingController::class, 'bookingCreate'])->name('booking.create');
    // $router->get('/booking/list', [BookingController::class, 'bookingStatusList'])->name('booking.list');
    // $router->get('/booking/list/upcoming', [BookingController::class, 'bookingUpcomingList'])->name('booking.list');
    // $router->get('/booking/list/completed', [BookingController::class, 'bookingCompletedList'])->name('booking.list');
    // $router->get('/booking/list/cancelled', [BookingController::class, 'bookingCancelledList'])->name('booking.list');
    // $router->get('/booking/detail/{id}', [BookingController::class, 'bookingDetail'])->name('booking.detail');
    // $router->post('/booking/change_status/start', [BookingController::class, 'changeBookingStatusToStart'])->name('booking.status.start');
    // $router->post('/booking/change_status/cancel', [BookingController::class, 'changeBookingStatusToCancel'])->name('booking.status.cancel');
    // $router->post('/booking/sos/create', [BookingController::class, 'createSos'])->name('booking.sos.create');
    // $router->get('/booking/sos/{id}', [BookingController::class, 'getSos'])->name('booking.sos.get');
    // $router->get('/booking/extra_drivers/{id}', [BookingController::class, 'getExtraDrivers'])->name('booking.extraDrivers.get');
    // $router->post('/booking/extend', [BookingController::class, 'bookingExtend'])->name('booking.extend');

});