<?php

namespace App\Modules\Services\User;

use App\Modules\Models\RoleUser;
use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\File;
use Throwable;
use Intervention\Image\Facades\Image;

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

            //Cast co-ordinates to double
            $data['location']['home']['latitude'] = floatval($data['location']['home']['latitude'] );
            $data['location']['home']['longitude'] = floatval($data['location']['home']['longitude'] );
            $data['location']['work']['latitude'] = floatval($data['location']['work']['latitude'] );
            $data['location']['work']['longitude'] = floatval($data['location']['work']['longitude'] );
            // dd($data);

            $updatedUser = $user->update($data);
            
            return $updatedUser;
        } catch (Exception $e) {
            return null;
        }
    }
   
    function uploadFile($file)
    {    dd('reached',!empty($file), $file);
        if (!empty($file)) { //dd('uploadFile', $file);
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



    public function uploadSocialImage($user, $url)
    {
        try{
            
            if (!is_dir('uploads'))  mkdir('uploads');
            $upload_path = 'uploads/user';
            $thumb_path = 'uploads/user/thumb';
            if (!is_dir( $upload_path ))  mkdir( $upload_path ); 
            if (!is_dir($thumb_path)) mkdir($thumb_path); 

            $old_image_file = $user->image;

            $path_info = pathinfo($url);                                            //Break the url into paths and base names
            $fileNameToStore = sha1($path_info['basename']) . time() . ".webp";      //the name of the image file to be stored temporarily
            $file_path = public_path('uploads\user\\' . $fileNameToStore );          //path for storing the image file content to the temporary directo
            $file_thumb_path = public_path('uploads\user\thumb\\' . $fileNameToStore );  
            $contents = file_get_contents($url);                                    //get image file content from the url
            $contents = imagecreatefromstring( $contents );
            
            //Save the new image to server/drive
            $img = Image::make($contents);
            $img_save = $img->save($file_path);
            $img->fit(320, 320);             //NEW THUMBNAIL CREATION
            $thumb_save = $img->save($file_thumb_path);

            if($img_save && $thumb_save)
            {
                //Save file name in user model
                $user->image = $fileNameToStore;
                $user->save();
                
                //Remove old image and thumbnail
                if($old_image_file)
                {
                    $old_img_path = $upload_path.'/'.$old_image_file;
                    $old_thumb_path = $thumb_path.'/'.$old_image_file;
                    if(is_file($old_img_path)) unlink($old_img_path);
                    if(is_file($old_thumb_path)) unlink($old_thumb_path);
                }
            } 
        }
        catch(Throwable $e)
        {
            //log ... social image couldn't be uploaded
            //dd("ERROR While Uploading social image! => ",$e);
        }
    }


}
