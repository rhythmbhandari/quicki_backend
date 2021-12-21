<?php

namespace App\Http\Controllers\Api\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

//requests
use App\Http\Requests\Api\Notification\SosRequest;

use App\Events\SosCreated;

//services
use App\Modules\Services\Notification\NotificationService;
use App\Modules\Services\Notification\SosService;
use App\Modules\Services\User\UserService;
use App\Modules\Services\Booking\BookingService;

//models
use App\Modules\Models\User;
use App\Modules\Models\Booking;
use App\Modules\Models\Rider;
use App\Modules\Models\Notification;


class SosController extends Controller
{

    protected $document, $user_service, $notification_service;

    public function __construct(SosService $sos, UserService $user_service, NotificationService $notification_service)
    {
        $this->sos = $sos;
        $this->user_service = $user_service;
        $this->notification_service = $notification_service;
    }




    /**
    * @OA\Post(
    *   path="/api/user/sos/create",
    *   tags={"Notification and Sos"},
    *   summary="Create Customer's Sos",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "title":"Help!",
    *                  "message":"The rider looks suspicius!",
    *                  "booking_id":1,
    *                  "location":{
    *                       "name":"Sanepa, Lalitpur",
    *                       "latitude":27.1234,
    *                       "longitude":85.3434,
    *                   },
    *                }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Sos created successfully!",
    *                       "sos": {
    *                           "title": "Help!",
    *                           "message": "The rider looks suspicius!",
    *                           "booking_id": 1,
    *                           "location": {
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.1234,
    *                               "longitude": 85.3434
    *                           },
    *                           "created_by_id": 3,
    *                           "created_by_type": "customer",
    *                           "updated_at": "2021-12-21T05:55:18.000000Z",
    *                           "created_at": "2021-12-21T05:55:18.000000Z",
    *                           "id": 1
    *                       }
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    function user_sos_store(SosRequest $request)
    {
     

        $user = Auth::user();
        $user_id = $user->id;

        $request['created_by_id'] = $user_id;
        $request['created_by_type'] = "customer";



        //CREATE SOS
        return DB::transaction(function () use ($user,$request)
        {
            $createdSos = $this->sos->create($request->all());
    
            if($createdSos)
            {

                //Send pusher/echo broadcast notification
                event(
                    new SosCreated( 
                        $request->title, 
                        $request->message, 
                        $user->name, 
                        $request->created_by_type,
                        $createdSos->id )
                    );

                //Create Notification sent via pusher broadcast
                // $this->notification_service->store(

                // );


                $response = ['message' => 'Sos created and sent successfully!',  "sos"=>$createdSos];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });


    }



        /**
    * @OA\Post(
    *   path="/api/rider/sos/create",
    *   tags={"Notification and Sos"},
    *   summary="Create Rider's Sos",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                  "title":"Help!",
    *                  "message":"The customer is a wanted criminal!",
    *                  "booking_id":1,
    *                  "location":{
    *                       "name":"Sanepa, Lalitpur",
    *                       "latitude":27.1234,
    *                       "longitude":85.3434,
    *                   },
    *                }
    *         )
    *     )
    *   ),
    *
    *      @OA\Response(
    *        response=201,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                       "message": "Sos created successfully!",
    *                       "sos": {
    *                           "title": "Help!",
    *                           "message": "The customer is a wanted criminal!",
    *                           "booking_id": 1,
    *                           "location": {
    *                               "name": "Sanepa, Lalitpur",
    *                               "latitude": 27.1234,
    *                               "longitude": 85.3434
    *                           },
    *                           "created_by_id": 1,
    *                           "created_by_type": "rider",
    *                           "updated_at": "2021-12-21T05:55:18.000000Z",
    *                           "created_at": "2021-12-21T05:55:18.000000Z",
    *                           "id": 1
    *                       }
    *                   }
    *                 )
    *           )
    *      ),
    *
    *      @OA\Response(
    *          response=422,
    *          description="Validation Fail",
    *             @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    function rider_sos_store(SosRequest $request)
    {
     


        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        $rider_id = $user->rider->id;
      
        $request['created_by_id'] = $rider_id;
        $request['created_by_type'] = "rider";



        //CREATE SOS
        return DB::transaction(function () use ($request)
        {
            $createdSos = $this->sos->create($request->all());
    
            if($createdSos)
            {
                
                $response = ['message' => 'Sos created successfully!',  "sos"=>$createdSos];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });


    }




}
