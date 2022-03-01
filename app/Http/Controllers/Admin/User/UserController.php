<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//services
use App\Modules\Services\User\UserService;

//models
use App\Modules\Models\User;
use App\Modules\Models\CompletedTrip;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }
    
    public function getAllData()
    {
        return $this->user->getAllData();
    }


    public function inertia_test()
    {
        //dd('s');   
        return Inertia::render('Test', [
            // 'event' => $event->only(
            //     'id',
            //     'title',
            //     'start_date',
            //     'description'
            // ),
                'test'=>'Guts',
        ]);
    }
    
    public function getDetails(){
        $user = Auth::user();

        //ROLE CHECK FOR CUSTOMER
        // if( ! $this->user->hasRole($user, 'customer') )
        // {
        //     $response = ['message' => 'Forbidden Access!'];
        //     return response($response, 403);
        // }

        $response = ['message' => 'Success!',  "user"=>$user];
        return response($response, 200);
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
          //  'dob' => 'nullable',
          //  'gender' => 'nullable',
          //  'google_id' => 'nullable|unique:users',
          //  'facebook_id' => 'nullable|unique:users'
        ]);
        if ($validator->fails()) {
            return response(['message' => 'Validation error', 'errors' => $validator->errors()->all()], 422);
        }

        //ROLE CHECK FOR CUSTOMER
        if( ! $this->user->hasRole($user, 'customer') )
        {
            $response = ['message' => 'Forbidden Access!'];
            return response($response, 403);
        }

        //UPDATE USER
        return DB::transaction(function () use ($request,$user)
        {
            $updatedUser = $this->user->update($user->id,$request->all());
    
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



    function uploadFile(Request $request, $user)
    {
        $file = $request->file('image');
        $fileName = $this->user->uploadFile($file);
        if (!empty($user->image))
            $this->user->__deleteImages($user);

        $data['image'] = $fileName;
        $this->user->updateImage($user->id, $data);
    }


    



}
