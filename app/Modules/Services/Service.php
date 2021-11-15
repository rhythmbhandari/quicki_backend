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

    public function upload(UploadedFile $file, $width = 1170, $height = 559)
    {
        if(!is_dir('uploads'))
            mkdir('uploads');

        if(!is_dir($this->uploadPath))
            mkdir($this->uploadPath);

        $destination = $this->uploadPath;
        if ($file->isValid()) {
            $fileName = $file->getClientOriginalName();
            $file_type = $file->getClientOriginalExtension();
            $newFileName = sprintf("%s.%s", sha1($fileName . time()), $file_type);
            try {
                $image = $file->move($destination, $newFileName);
                if(substr($file->getClientMimeType(), 0, 5) == 'image')
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
            } catch(Exception $e) {
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
        try{
            $img = Image::make($image->getPathname());
            $img->fit($width, $height);
            $path = sprintf('%s/thumb/%s', $image->getPath(), $image->getFilename());
            $directory = sprintf('%s/thumb', $image->getPath());
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            return $img->save($path);
        }catch (\Exception $e){
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

    public function uploadExcel($file) {
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

    public function uploadFile($file) {
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

    public function generateCode($base = '') {
        return sha1($base . rand() . time());
    }

}
