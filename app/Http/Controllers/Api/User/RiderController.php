<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



//requests
use App\Http\Requests\Api\User\RiderProfileRequest;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\CompletedTrip;
use App\Modules\Models\Payment;
use App\Modules\Models\Transaction;


class RiderController extends Controller
{
    
    
    protected $rider, $user_service;

    public function __construct(RiderService $rider, UserService $user_service)
    {
        $this->rider = $rider;
        $this->user_service = $user_service;
    }
    

    /**
    * @OA\Get(
    *   path="/api/rider/details",
    *   tags={"Details"},
    *   summary="Rider Details",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\Parameter(
    *      name="device_token",
    *      in="header",
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(      
    *             example=	{
    *               "message": "Success!",
    *               "user": {
    *                   "id": 4,
    *                   "slug": "gintama-d-luffy",
    *                   "first_name": "Gintama",
    *                   "middle_name": "D.",
    *                   "last_name": "Luffy",
    *                   "image": "file()",
    *                   "dob": "2000-01-01",
    *                   "gender": null,
    *                   "google_id": null,
    *                   "facebook_id": null,
    *                   "username": null,
    *                   "phone": "9816810976",
    *                   "email": "gintama@gmail.com",
    *                    "emergency_contacts": {
    *                        {
    *                          "name": "Guts",
    *                          "contact": "9816810976"
    *                        },
    *                        {
    *                          "name": "Naruto",
    *                          "contact": "9816810977"
    *                        },
    *                        {
    *                          "name": "Luffy",
    *                          "contact": "9816810978"
    *                        }
    *                       },
    *                   "status": null,
    *                   "email_verified_at": null,
    *                   "last_logged_in": null,
    *                   "no_of_logins": null,
    *                   "avatar": null,
    *                   "deleted_at": null,
    *                   "last_updated_by": null,
    *                   "last_deleted_by": null,
    *                   "created_at": "2021-11-16T08:09:03.000000Z",
    *                   "updated_at": "2021-11-16T08:09:03.000000Z",
    *                   "name": "Gintama D. Luffy",
    *                   "rider": {
    *                     "id": 2,
    *                     "user_id": 4,
    *                     "experience": 5,
    *                     "trained": "YES",
    *                     "status": "in_active",
    *                     "approved_at": null,
    *                     "deleted_at": null,
    *                     "last_deleted_by": null,
    *                     "last_updated_by": null,
    *                     "created_at": "2021-11-16T08:09:03.000000Z",
    *                     "updated_at": "2021-11-16T08:09:03.000000Z",
    *                     "vehicle": {
    *                       "id": 1,
    *                       "slug": "ba-99-pa-5544",
    *                       "rider_id": 2,
    *                       "vehicle_type_id": 1,
    *                       "vehicle_number": "BA 99 PA 5544",
    *                       "image": null,
    *                       "make_year": "2016",
    *                       "vehicle_color": "black",
    *                       "brand": "CF MOTO",
    *                       "model": "NK 250",
    *                       "status": "in_active",
    *                       "deleted_at": null,
    *                       "last_deleted_by": null,
    *                       "last_updated_by": null,
    *                       "created_at": "2021-11-16T08:09:03.000000Z",
    *                       "updated_at": "2021-11-16T08:09:03.000000Z",
    *                       "thumbnail_path": "assets/media/noimage.png",
    *                       "image_path": "assets/media/noimage.png",
    *                       "documents": {}
    *                     },
    *                     "documents": {
    *                       {
    *                         "id": 1,
    *                         "documentable_type": "App\\Modules\\Models\\Rider",
    *                         "documentable_id": 2,
    *                         "type": "license",
    *                         "document_number": "L12345345234",
    *                         "issue_date": "2018-01-01",
    *                         "expire_date": null,
    *                         "image": "file()",
    *                         "deleted_at": null,
    *                         "created_at": "2021-11-16T08:09:03.000000Z",
    *                         "updated_at": "2021-11-16T08:09:03.000000Z",
    *                         "thumbnail_path": "uploads/document/license/thumb/file()",
    *                         "image_path": "uploads/document/license/file()"
    *                       }
    *                     }
    *                   },
    *                   "documents": {}
    *                 }
    *             }             
    *           )
    *      )
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   )
    *)
    **/
    /**
    * @OA\Get(
    *   path="/api/rider/{rider_id}/details",
    *   tags={"Details"},
    *   summary="Rider Details",
    *   security={{"bearerAuth":{}}},
    *      @OA\Parameter(
    *         name="rider_id",
    *         in="path",
    *         description="Rider ID",
    *         required=true,
    *      ),
    *
    *   @OA\Response(
    *      response=200,
    *       description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(      
    *             example=	{
    *               "message": "Success!",
    *               "user": {
    *                   "id": 4,
    *                   "slug": "gintama-d-luffy",
    *                   "first_name": "Gintama",
    *                   "middle_name": "D.",
    *                   "last_name": "Luffy",
    *                   "image": "file()",
    *                   "dob": "2000-01-01",
    *                   "gender": null,
    *                   "location": {
    *                     "home": {
    *                       "name": "Chapagaun, Kathmandu",
    *                       "latitude": 27.691153232923103,
    *                       "longitude": 86.33177163310808
    *                     },
    *                     "work": {
    *                       "name": "Thapagaun, Lalitpur",
    *                       "latitude": 28.687052088825897,
    *                       "longitude": 85.30439019937253
    *                     }
    *                   },
    *                   "google_id": null,
    *                   "facebook_id": null,
    *                   "username": null,
    *                   "phone": "9816810976",
    *                   "email": "gintama@gmail.com",
    *                    "emergency_contacts": {
    *                        {
    *                          "name": "Guts",
    *                          "contact": "9816810976"
    *                        },
    *                        {
    *                          "name": "Naruto",
    *                          "contact": "9816810977"
    *                        },
    *                        {
    *                          "name": "Luffy",
    *                          "contact": "9816810978"
    *                        }
    *                       },
    *                   "status": null,
    *                   "email_verified_at": null,
    *                   "last_logged_in": null,
    *                   "no_of_logins": null,
    *                   "avatar": null,
    *                   "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
    *                   "deleted_at": null,
    *                   "last_updated_by": null,
    *                   "last_deleted_by": null,
    *                   "created_at": "2021-11-16T08:09:03.000000Z",
    *                   "updated_at": "2021-11-16T08:09:03.000000Z",
    *                   "name": "Gintama D. Luffy",
    *                   "rider": {
    *                     "id": 2,
    *                     "user_id": 4,
    *                     "experience": 5,
    *                     "trained": "YES",
    *                     "status": "in_active",
    *                     "approved_at": null,
    *                     "deleted_at": null,
    *                     "last_deleted_by": null,
    *                     "last_updated_by": null,
    *                     "created_at": "2021-11-16T08:09:03.000000Z",
    *                     "updated_at": "2021-11-16T08:09:03.000000Z",
    *                     "vehicle": {
    *                       "id": 1,
    *                       "slug": "ba-99-pa-5544",
    *                       "rider_id": 2,
    *                       "vehicle_type_id": 1,
    *                       "vehicle_number": "BA 99 PA 5544",
    *                       "image": null,
    *                       "make_year": "2016",
    *                       "vehicle_color": "black",
    *                       "brand": "CF MOTO",
    *                       "model": "NK 250",
    *                       "status": "in_active",
    *                       "deleted_at": null,
    *                       "last_deleted_by": null,
    *                       "last_updated_by": null,
    *                       "created_at": "2021-11-16T08:09:03.000000Z",
    *                       "updated_at": "2021-11-16T08:09:03.000000Z",
    *                       "thumbnail_path": "assets/media/noimage.png",
    *                       "image_path": "assets/media/noimage.png",
    *                       "documents": "[]"
    *                     },
    *                     "documents": {
    *                       {
    *                         "id": 1,
    *                         "documentable_type": "App\\Modules\\Models\\Rider",
    *                         "documentable_id": 2,
    *                         "type": "license",
    *                         "document_number": "L12345345234",
    *                         "issue_date": "2018-01-01",
    *                         "expire_date": null,
    *                         "image": "file()",
    *                         "deleted_at": null,
    *                         "created_at": "2021-11-16T08:09:03.000000Z",
    *                         "updated_at": "2021-11-16T08:09:03.000000Z",
    *                         "thumbnail_path": "uploads/document/license/thumb/file()",
    *                         "image_path": "uploads/document/license/file()"
    *                       }
    *                     }
    *                   },
    *                   "documents": "[]"
    *                 }
    *             }             
    *           )
    *      )
    *   ),
    *   @OA\Response(
    *      response=403,
    *       description="Forbidden Access!",
    *   ),
    *   @OA\Response(
    *      response=404,
    *       description="User Not Found!",
    *   )
    *)
    **/
    public function getDetails(Request $request,$rider_id=null)
    {

        // $user = ($rider_id != null) ? Rider::findOrFail($rider_id)->user : Auth::user();

        $user = null;
        if($rider_id == null)
        {
            $user = Auth::user();
            //ROLE CHECK FOR RIDER
            if( ! $this->user_service->hasRole($user, 'rider') )
            {
                $response = ['message' => 'Forbidden Access!'];
                return response($response, 403);
            }
            $rider = $user->rider;
            $rider->device_token = $request->header('device_token');
            $rider->save();
        }
        else{
            $rider = Rider::find($rider_id);
            if($rider) {    
                $user = $rider->user; 
                //ROLE CHECK FOR RIDER
                if( ! $this->user_service->hasRole($user, 'rider') )
                {
                    $response = ['message' => 'Forbidden Access!'];
                    return response($response, 403);
                }
            }
            else {
                $response = ['message' => 'User not found!'];
                return response($response, 404);
            }
        }

        //$user = Auth::user();
       
    
        $user = User::where('id',$user->id)->with('rider')->with('documents')->first();

        $response = ['message' => 'Success!',  "user"=>$user];
        return response($response, 200);
    }



    /**
    * @OA\Post(
    *   path="/api/rider/profile/update",
    *   tags={"Profile"},
    *   summary="Update Rider Profile",
    *   security={{"bearerAuth":{}}},
    *
    *   @OA\RequestBody(
    *      @OA\MediaType(
    *          mediaType="application/json",
    *         @OA\Schema(
    *             
    *             example={
    *                 "first_name": "Monkey",
    *                 "middle_name": "D.",
    *                 "last_name": "Luffy",
    *                 "image": "file()",
    *                 "email": "luffy@gmail.com",
    *                  "emergency_contacts": {
    *                        {
    *                          "name": "Guts",
    *                          "contact": "9816810976"
    *                        },
    *                        {
    *                          "name": "Naruto",
    *                          "contact": "9816810977"
    *                        },
    *                        {
    *                          "name": "Luffy",
    *                          "contact": "9816810978"
    *                        }
    *                       },
    *                 "username": "luffy",
    *                 "dob": "2000/01/01",
    *                 "gender": "male",
    *                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
    *              }
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
    *                           "message":"User Registration Successful!",
    *                           "user":{
    *                                 "id": 4,
    *                                 "slug": "gintama-d-luffy",
    *                                 "first_name": "Gintama",
    *                                 "middle_name": "D.",
    *                                 "last_name": "Luffy",
    *                                 "image": "file()",
    *                                 "dob": "2000-01-01",
    *                                 "gender": null,
    *                                 "location": {
    *                                      "home": {
    *                                        "name": "Chapagaun, Kathmandu",
    *                                        "latitude": 27.691153232923103,
    *                                        "longitude": 86.33177163310808
    *                                      },
    *                                      "work": {
    *                                        "name": "Thapagaun, Lalitpur",
    *                                        "latitude": 28.687052088825897,
    *                                        "longitude": 85.30439019937253
    *                                      }
    *                                    },
    *                                 "google_id": null,
    *                                 "facebook_id": null,
    *                                 "username": "luffy",
    *                                 "phone": "9816810976",
    *                                 "email": "gintama@gmail.com",
    *                                  "emergency_contacts": {
    *                                    {
    *                                      "name": "Guts",
    *                                      "contact": "9816810976"
    *                                    },
    *                                    {
    *                                      "name": "Naruto",
    *                                      "contact": "9816810977"
    *                                    },
    *                                    {
    *                                      "name": "Luffy",
    *                                      "contact": "9816810978"
    *                                    }
    *                                   },
    *                                 "status": null,
    *                                 "email_verified_at": null,
    *                                 "last_logged_in": null,
    *                                 "no_of_logins": null,
    *                                 "avatar": null,
    *                                 "social_image_url":"https://images.unsplash.com/photo-1607335614551-3062bf90f30e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80",
    *                                 "deleted_at": null,
    *                                 "last_updated_by": null,
    *                                 "last_deleted_by": null,
    *                                 "created_at": "2021-11-16T08:09:03.000000Z",
    *                                 "updated_at": "2021-11-16T08:09:03.000000Z",
    *                                 "name": "Gintama D. Luffy"
    *                               },
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
    *
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *             @OA\MediaType(
     *              mediaType="application/json",
     *          )
    *      ),
    *)
    **/
    public function updateProfile(RiderProfileRequest $request)
    {
        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //UPDATE USER
        return DB::transaction(function () use ($request,$user)
        {
            $updatedUser = $this->user_service->update($user->id,$request->except('username','image'));
    
            if($updatedUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $updatedUser);
                }
                else if (isset($request->social_image_url) && !is_null($request->social_image_url)) {
                   
                    $url = $request->social_image_url;
                    $this->user_service->uploadSocialImage($updatedUser, $url);

                } else {
                    //$fileNameToStore1 = 'no-image.png';
                }

                $response = ['message' => 'User Profile Updated Successful!',  "user"=>User::find($updatedUser->id)];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });

    }




    /**
    * @OA\Get(
    *   path="/api/rider/income_details",
    *   tags={"Details"},
    *   summary="Get Rider's Income details",
    *   security={{"bearerAuth":{}}},
    *
    *
    *      @OA\Response(
    *        response=200,
    *        description="Success",
    *          @OA\MediaType(
    *               mediaType="application/json",
    *                   @OA\Schema(      
    *                   example={
    *                           "message":"Success!",
    *                           "data":{
    *                               "total_income":2000,
    *                               "total_commission":500,
    *                               "paid":200,
    *                               "dues":300,
    *                           }
    *                           
    *                   }
    *                 )
    *           )
    *      ),
    *
    *     @OA\Response(
    *          response=403,
    *          description="Forbidden Access",
    *      ),
    *)
    **/
    function getIncomeDetails()
    {
        $user = Auth::user();

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }


        $rider = $user->rider;

        $data = [];

        $total_ride_sales = CompletedTrip::where('rider_id',$rider->id)->where('status','completed')->sum('price');
        $total_commission = Payment::whereRelation('completed_trip','rider_id',$rider->id)->sum('commission_amount');
        $paid = Transaction::where('debtor_type', 'admin')->where('creditor_type','rider')->where('creditor_id',$user->id)->sum('amount');
        $dues = $total_commission - $paid;
        $total_income = $total_ride_sales - $total_commission;

        $data = [
            "total_income"=>$total_income,
            "total_commission"=>$total_commission,
            "paid"=>$paid,
            "dues"=>$dues
        ];

        $response = ['message' => 'Success!',"data"=>$data];
        return response($response, 200);

    }





    //Image for user 
    function uploadFile(Request $request, $user)
    {
        $file = $request->file('image');
        $fileName = $this->user_service->uploadFile($file);
        if (!empty($user->image))
            $this->user_service->__deleteImages($user);

        $data['image'] = $fileName;
        $this->user_service->updateImage($user->id, $data);
    }





}
