<?php 

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\User\UserController;
use Inertia\Inertia;

 Route::get('/test', [UserController::class, 'inertia_test'])->name('test');
// Route::get('/test',function(){
//     // return inertia('Pages/Test');
//     return Inertia::render('Test',[
//         "name"=>"Honj Eod"
//     ]);
// });

Route::group(['as' => 'admin.', 'middleware' =>  ['admin','role:admin'], 'prefix' => 'admin' // 'middleware' => ['role:ROLE_CANDIDATE'],
    ], function ($router) {


});