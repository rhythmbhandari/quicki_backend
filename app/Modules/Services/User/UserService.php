<?php

namespace App\Modules\Services\User;

use App\Modules\Models\RoleUser;
use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\File;
use Throwable;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Modules\Models\User;

class UserService extends Service
{
    protected $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllData()
    {
        $query = $this->user->whereRelation('roles', 'name', 'customer')->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function (User $user) {
                return getTableHtml($user, 'image');
            })
            ->editColumn('name', function (User $user) {
                return $user->name;
            })
            ->editColumn('username', function (User $user) {
                return $user->username;
            })
            ->editColumn('email', function (User $user) {
                return $user->email;
            })
            ->editColumn('phone', function (User $user) {
                return $user->phone;
            })
            ->editColumn('gender', function (User $user) {
                return $user->gender;
            })
            ->editColumn('status', function (User $user) {
                return getTableHtml($user, 'status');
            })
            ->editColumn('actions', function (User $user) {
                $editRoute = route('admin.customer.edit', $user->id);
                $deleteRoute = '';
                // $deleteRoute = route('admin.vendor.destroy',$customer->id);
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($user, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['image', 'status', 'gender', 'actions'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->user::find($id);
    }

    function getUser()
    {
        return $this->user;
    }

    function hasRole(User $user, $checkRole)
    {
        foreach ($user->roles as $role) {
            if ($role->name == $checkRole)
                return true;
        }
        return false;
    }


    function create(array $data)
    {  // dd($data);
        //return response($data,200);
        try {
            if (!isset($data['password'])) $data['password'] = Hash::make('password');
            else $data['password'] =  Hash::make($data['password']);

            $data['username'] =  $this->generate_random_username($data['first_name']);

            $createdUser = $this->user->create($data);
            if ($createdUser) {
                //dd("created user", $createdUser);
                $createdUser->roles()->attach(3);

                // $createdUser->roles = $createdUser->roles();
                return $createdUser;
            } else return NULL;
        } catch (Exception $e) {
            return null;
        }
        return null;
    }


    function generate_random_username($name)
    {

        $username = substr(str_replace(' ', '', strtolower($name)), 0, 5);

        $rand = substr(md5(microtime()), rand(0, 26), 5);


        $seed = str_split('@0123456789'); // and any other characters
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];


        return $username . '_' . $rand;
    }

    public function all()
    {
        return $this->user->whereNotNull('deleted_at')->get();
    }


    public function update($userId, array $data)
    {
        try {

            $user = User::findOrFail($userId);
            $old_email =  $user->email;

            // if(isset($data['location']))
            // {
            //     $data['location']
            // }

            $user->update($data);

            //case for email change
            if ($old_email != $user->email) {
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

            $user = User::findOrFail($userId);

            //Cast co-ordinates to double
            $data['location']['home']['latitude'] = floatval($data['location']['home']['latitude']);
            $data['location']['home']['longitude'] = floatval($data['location']['home']['longitude']);
            $data['location']['work']['latitude'] = floatval($data['location']['work']['latitude']);
            $data['location']['work']['longitude'] = floatval($data['location']['work']['longitude']);
            // dd($data);

            $updatedUser = $user->update($data);

            return $updatedUser;
        } catch (Exception $e) {
            return null;
        }
    }

    function uploadFile($file)
    {   // dd('reached',!empty($file), $file);
        if (!empty($file)) { //dd('uploadFile', $file);
            $this->uploadPath = 'uploads/user';
            return $this->uploadFromAjax($file);
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
            // dd($user, $userId, $data);
            return $user;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }



    public function uploadSocialImage($user, $url)
    {
        $this->uploadPath = 'uploads/user';
        try {

            if (!is_dir('uploads'))  mkdir('uploads');
            $upload_path = 'uploads/user';
            $thumb_path = 'uploads/user/thumb';
            $temp_path = 'uploads/temp';
            if (!is_dir($upload_path))  mkdir($upload_path);
            if (!is_dir($thumb_path)) mkdir($thumb_path);
            if (!is_dir($temp_path)) mkdir($temp_path);

            $old_image_file = $user->image;

            $path_info = pathinfo($url);                                            //Break the url into paths and base names
            $fileNameToStore = sha1($path_info['basename'] . time()) . ".webp";      //the name of the image file to be stored temporarily
            // $file_path = public_path( $upload_path .'\\'. $fileNameToStore );          //path for storing the image file content to the temporary directo
            // $file_thumb_path = public_path( $thumb_path .'\\'. $fileNameToStore );  

            $file_path = public_path() . '/' . $upload_path . '/' . $fileNameToStore;
            $file_thumb_path = public_path() . '/' . $thumb_path . '/' . $fileNameToStore;
            $file_temp_path = public_path() . '/' . $temp_path . '/' . $fileNameToStore;

            // dd($file_path, $file_thumb_path);
            $contents = file_get_contents($url);                                    //get image file content from the url
            //$contents = imagecreatefromstring( $contents );
            //dd($file_path, $file_thumb_path);
            $temp_file_save = file_put_contents($file_temp_path, $contents);
            $img_save = copy($file_temp_path, $file_path);
            $thumb_save = copy($file_temp_path, $file_thumb_path);
            unlink($file_temp_path);
            // $img_save = file_put_contents($file_path, $contents);
            // $thumb_save = file_put_contents($file_thumb_path, $contents);

            //Save the new image to server/drive
            // $img = Image::make($contents);
            // $img_save = Storage::disk('public')->putFile($file_path, $img);
            // $img_save = $img->save($file_path);
            //Put file with own name
            //Storage::put($fileNameToStore, $img);
            //Move file to your location 
            // $img_save  = Storage::move($fileNameToStore, $upload_path .'/'. $fileNameToStore);


            //$img->fit(320, 320);             //NEW THUMBNAIL CREATION
            //$thumb_save = $img->save($file_thumb_path);
            //$thumb_save  = Storage::disk('public')->putFile($file_thumb_path, $img);
            // Storage::put($fileNameToStore, $img);
            //Move file to your location 
            //$thumb_save  = Storage::move($fileNameToStore, $thumb_path .'/' . $fileNameToStore);


            if ($img_save && $thumb_save) {
                //Save file name in user model
                $user->image = $fileNameToStore;
                // dd($user->toArray());  

                $user->save();
                //Remove old image and thumbnail
                if ($old_image_file) {
                    $old_img_path = $upload_path . '/' . $old_image_file;
                    $old_thumb_path = $thumb_path . '/' . $old_image_file;
                    if (is_file($old_img_path)) unlink($old_img_path);
                    if (is_file($old_thumb_path)) unlink($old_thumb_path);
                }
            }
        } catch (Throwable $e) {
            //log ... social image couldn't be uploaded
            dd("ERROR While Uploading social image! => ", $e);
        }
    }
}
