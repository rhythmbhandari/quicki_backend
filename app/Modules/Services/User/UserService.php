<?php

namespace App\Modules\Services\User;

use App\Modules\Models\RoleUser;
use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Hash;

use App\Modules\Models\User;

class UserService extends Service
{
    protected $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    function getUser(){
        return $this->user;
    }

    function hasRole(User $user, $checkRole)
    {
        foreach($user->roles as $role){
            if($role->name == $checkRole) 
                return true;
        }
        return false;
        
    }


    function create(array $data)
    {
        //return response($data,200);
        try {
            
            if (!isset($data['password'])) $data['password'] = Hash::make('password');
            else $data['password'] =  Hash::make($data['password']);
            
            $createdUser = $this->user->create($data);
            if($createdUser){
                //dd("created user", $createdUser);
                $createdUser->roles()->attach([3]);

                $createdUser->roles = $createdUser->roles();
               return $createdUser;
            }
            else return NULL;
        } catch (Exception $e) {
            return null;
        }
        return null;
    }


    public function update($userId,array $data)
    {
        try {
        
            $user= User::findOrFail($userId);
            $old_email =  $user->email;
            $updatedUser = $user->update($data);
            
            //case for email change
            if( $old_email != $user->email)
            {
                $user = User::find($userId);
                $user->email_verified_at = NULL;
                $user->save();
                return $user;
            }
            //TODO: case for phone number change

            return $user;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }


    function update_location($userId, array $data)
    {
        try {
        
            $user= User::findOrFail($userId);
            $updatedUser = $user->update($data);
            
            return $updatedUser;
        } catch (Exception $e) {
            return null;
        }
    }
   
    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/user';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($user)
    {
        try {
            if (is_file($user->image_path))
                unlink($user->image_path);

            if (is_file($user->thumbnail_path))
                unlink($user->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($userId, array $data)
    {
        try {
            $user = $this->user->find($userId);
            $user = $user->update($data);

            return $user;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }

}
