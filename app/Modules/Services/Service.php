<?php

namespace App\Modules\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Service
 *
 * @package App\Services
 */
abstract class Service
{

    protected $uploadPath = '/uploads';

    /*
    public function uploadSocialImage($user, $url)
    {
        try{
            
           
            if (!is_dir('uploads'))  mkdir('/uploads');
            $upload_path = '/uploads/user';
            $thumb_path = '/uploads/user/thumb';
            if (!is_dir( $upload_path ))  mkdir( $upload_path ); 
            $directory = sprintf('%s/thumb', $image->getPath());
            if (!is_dir($thumb_path)) mkdir($thumb_path); 

          //  $destination = $this->uploadPath;

            $old_image_file = $user->image;

            $path_info = pathinfo($url);                                            //Break the url into paths and base names
            $fileNameToStore = sha1($path_info['basename']) . time() . ".webp";      //the name of the image file to be stored temporarily
            $file_path = public_path( $upload_path . $fileNameToStore );          //path for storing the image file content to the temporary directo
            $file_thumb_path = public_path( $thumb_path . $fileNameToStore );  
            dd($file_path, $file_thumb_path);
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
            dd("ERROR While Uploading social image! => ",$e);
        }
    } */


    public function upload(UploadedFile $file, $width = 1170, $height = 559)
    {
        if (!is_dir('uploads'))
            mkdir('uploads');

        if (!is_dir($this->uploadPath))
            mkdir($this->uploadPath);

        $destination = $this->uploadPath;
        if ($file->isValid()) {
            $fileName = $file->getClientOriginalName();
            $file_type = $file->getClientOriginalExtension();
            $newFileName = sprintf("%s.%s", sha1($fileName . time()), $file_type);
            try {
                $image = $file->move($destination, $newFileName);
                if (substr($file->getClientMimeType(), 0, 5) == 'image')
                    $this->createThumb($image, $width, $height);
                return $image->getFilename();
            } catch (Exception $e) {
                return $e->getMessage();
                $this->logger->error(sprintf('File could not be uploaded : %s', $e->getMessage()));
            }
            return false;
        }
        return false;
    }

    function arePointsNear($checkPoint, $centerPoint)
    {
        // dd($checkPoint, $centerPoint["lat"]);
        $ky = 40000 / 360;
        $kx = cos(pi() * $centerPoint['lat'] / 180.0) * $ky;
        $dx = abs($centerPoint['lng'] - $checkPoint['lng']) * $kx;
        $dy = abs($centerPoint['lat'] - $checkPoint['lat']) * $ky;
        $scan_radius =  !empty(config('settings.scan_radius')) ? config('settings.scan_radius') : 5000;
        return sqrt($dx * $dx + $dy * $dy) <= $scan_radius;
    }

    public function uploadFromAjax(UploadedFile $file, $width = 320, $height = 320)
    {
        if (!is_dir('uploads'))
            mkdir('uploads');

        if (!is_dir($this->uploadPath))
            mkdir($this->uploadPath);

        // dd($file);
        $destination = $this->uploadPath;
        if ($file->isValid()) {
            $fileName = $file->getClientOriginalName();
            $file_type = $file->extension();
            $newFileName = sprintf("%s.%s", sha1($fileName . time()), $file_type);
            try {
                $image = $file->move($destination, $newFileName);

                if (substr($file->getClientMimeType(), 0, 5) == 'image')
                    $this->createThumb($image, $width, $height);
                return $image->getFilename();
            } catch (Exception $e) {
                return $e->getMessage();
            }
            return false;
        }
        return false;
    }
    /**
     * Create Image thumb
     *
     * @param File $image
     * @param int $width
     * @param int $height
     * @return boolean
     */
    public function createThumb(File $image, $width = 320, $height = 320)
    {
        try {
            $img = Image::make($image->getPathname());
            $img->fit($width, $height);
            $path = sprintf('%s/thumb/%s', $image->getPath(), $image->getFilename());
            $directory = sprintf('%s/thumb', $image->getPath());
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            return $img->save($path);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Delete Image
     *
     * @param $image
     * @return bool
     */
    public function deleteImage($image)
    {
        $path = $this->uploadPath;
        try {
            $large = $path . '/' . $image;
            unlink($large);
            $thumb = sprintf('%s/thumb/%s', $path, $image);
            unlink($thumb);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteMultipleImages($images)
    {
        $path = $this->uploadPath;
        try {
            foreach ($images as $image) {
                $large = $path . '/' . $image;
                unlink($large);
                $thumb = sprintf('%s/thumb/%s', $path, $image);
                unlink($thumb);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function uploadExcel($file)
    {
        $destinationPath = base_path() . '/public/uploads';

        $fileName = $file->getClientOriginalName();
        $file_type = $file->getClientOriginalExtension();

        $newFileName = sprintf("%s.%s", sha1($fileName . time()), $file_type);

        try {
            return $file->move($destinationPath, $newFileName);
        } catch (Exception $e) {
            $this->logger->error(sprintf('File could not be uploaded : %s', $e->getMessage()));
        }
    }

    public function uploadFile($file)
    {
        $destination = $this->uploadPath;

        $fileName = $file->getClientOriginalName();
        $file_type = $file->getClientOriginalExtension();

        $newFileName = sprintf("%s.%s", sha1($fileName . time()), $file_type);

        try {
            $file->move($destination, $newFileName);

            return $newFileName;
        } catch (Exception $e) {
            $this->logger->error(sprintf('File could not be uploaded : %s', $e->getMessage()));
        }
    }

    public function deleteFile($file)
    {
        $path = $this->uploadPath;
        try {
            $large = $path . '/' . $file;
            unlink($large);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function generateCode($base = '')
    {
        return sha1($base . rand() . time());
    }
}
