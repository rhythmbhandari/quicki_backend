<?php

namespace App\Http\Controllers\Api\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

//requests
use App\Http\Requests\Api\Notification\EventRequest;

use App\Events\SosCreated;
use App\Events\EventCreated;

//services
use App\Modules\Services\Notification\NotificationService;
use App\Modules\Services\Notification\EventService;
use App\Modules\Services\Notification\SosService;
use App\Modules\Services\User\UserService;
use App\Modules\Services\Booking\BookingService;

//models
use App\Modules\Models\User;
use App\Modules\Models\Booking;
use App\Modules\Models\Rider;
use App\Modules\Models\Notification;
use App\Modules\Models\Sos;


class EventController extends Controller
{

    protected $document, $user_service, $notification_service, $sos_service;

    public function __construct(EventService $event, SosService $sos_service, UserService $user_service, NotificationService $notification_service)
    {
        $this->event = $event;
        $this->sos_service = $sos_service;
        $this->user_service = $user_service;
        $this->notification_service = $notification_service;
    }




    /**
    * @OA\Post(
    *   path="/api/user/sos/event/create",
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
    *                  "message":"The rider still looks suspicius!",
    *                  "sos_id":1,
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
    *                       "message": "Event created successfully!",
    *                       "event": {
    *                           "message": "The rider still looks suspicius!",
    *                           "sos_id": 1,
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
    function user_event_store(EventRequest $request)
    {
     

        $user = Auth::user();
        $user_id = $user->id;

        $request['created_by_id'] = $user_id;
        $request['created_by_type'] = "customer";



        //CREATE EVENT
        return DB::transaction(function () use ($user,$request)
        {
            $sos = Sos::find($request->sos_id);
            $createdEvent = $this->event->create($request->all());
    
            if($createdEvent)
            {

                //Send pusher/echo broadcast notification
                event(
                    new EventCreated( 
                        'Sos: '.$sos->title, 
                        $request->message, 
                        $user->name, 
                        $request->created_by_type,
                        $sos->id,
                        $createdEvent->id 
                        )
                    );

                //Create Notification sent via pusher broadcast
                $this->notification_service->create(
                    [
                        'recipient_id'=>null,
                        'recipient_type'=>'admin',
                        'recipient_device_token'=>null,
                        'recipient_quantity_type'=>'all',
                        'notification_type'=>'event_create',
                        'title'=> 'Sos: '.$sos->title, 
                        'message'=>  $request->message, 
                    ]
                );


                $response = ['message' => 'Event created and sent successfully!',  "event"=>$createdEvent];
                return response($response, 201);
            }
            return response("Internal Server Error!", 500);
        });


    }



    /**
    * @OA\Post(
    *   path="/api/rider/sos/event/create",
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
    *                  "message":"The customer is still a wanted criminal!",
    *                   "sos_id":1
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
    *                       "message": "Event created successfully!",
    *                       "event": {
    *                           "message": "The customer still is a wanted criminal!",
    *                           "sos_id": 1,
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
    function rider_event_store(EventRequest $request)
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



         //CREATE EVENT
         return DB::transaction(function () use ($user,$request)
         {
             $sos = Sos::find($request->sos_id);
             $createdEvent = $this->event->create($request->all());
     
             if($createdEvent)
             {
 
                 //Send pusher/echo broadcast notification
                 event(
                     new EventCreated( 
                         'Sos: '.$sos->title, 
                         $request->message, 
                         $user->name, 
                         $request->created_by_type,
                         $sos->id,
                         $createdEvent->id 
                         )
                     );
 
                 //Create Notification sent via pusher broadcast
                 $this->notification_service->create(
                     [
                         'recipient_id'=>null,
                         'recipient_type'=>'admin',
                         'recipient_device_token'=>null,
                         'recipient_quantity_type'=>'all',
                         'notification_type'=>'event_create',
                         'title'=> 'Sos: '.$sos->title, 
                         'message'=>  $request->message, 
                     ]
                 );
 
 
                 $response = ['message' => 'Event created and sent successfully!',  "event"=>$createdEvent];
                 return response($response, 201);
             }
             return response("Internal Server Error!", 500);
         });
 
        }

    }
