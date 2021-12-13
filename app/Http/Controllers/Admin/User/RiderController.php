<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\User;

class RiderController extends Controller
{
    
    
    protected $rider, $user_service;

    public function __construct(RiderService $rider, UserService $user_service)
    {
        $this->rider = $rider;
        $this->user_service = $user_service;
    }
    

    public function getDetails(){
        $user = Auth::user();
       
        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }
        
        $user = User::where('id',$user->id)->with('rider')->with('documents')->first();

        $response = ['message' => 'Success!',  "user"=>$user];
        return response($response, 200);
    }

    function riderAjax(Request $request){
        // dd($request->all());

        $query = Rider::select('id', 'first_name')
            ->when($request->q, function($query) use ($request) {
                $q = $request->q;
                $query = $query->where('name', 'LIKE', "%".$q."%");
                return $query;
            })->where('status','active')->simplePaginate(10);
            $results = array();
            foreach ($query as $object) {
                array_push($results, [
                    'id' => $object['id'],
                    'text' => $object['name']
                ]);
            }
            $pagination = [
                'more' => !is_null($query->toArray()['next_page_url'])
            ];
            return compact('results', 'pagination');
    }

   
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        //VALIDATIONS
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'username' => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,'.$user->id,
            'dob' => 'nullable',
            'gender' => 'nullable',
            //'password' => 'nullable|string|min:6|confirmed',
           // 'phone' => 'nullable|string|min:10|unique:users,phone,'.$user->id,
          //  'google_id' => 'nullable|unique:users',
          //  'facebook_id' => 'nullable|unique:users'
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //ROLE CHECK FOR RIDER
        if( ! $this->user_service->hasRole($user, 'rider') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //UPDATE USER
        return DB::transaction(function () use ($request,$user)
        {
            $updatedUser = $this->user_service->update($user->id,$request->all());
    
            if($updatedUser)
            {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $updatedUser);
                }
                $response = ['message' => 'User Profile Updated Successful!',  "user"=>Auth::user()];
                return response($response, 200);
            }
            return response("Internal Server Error!", 500);
        });

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
