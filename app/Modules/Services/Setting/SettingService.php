<?php

namespace App\Modules\Services\Setting;

use App\Modules\Models\User;
use App\Modules\Models\Setting;
use App\Modules\Models\Cart;
use App\Modules\Services\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Rules\ValidImageRatio;

use Validator;


class SettingService extends Service
{
    protected $customer,$setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    
    public function create(array $data)
   {}

    public function update($settingId, array $data)
    { 
        try {
            
            $setting= $this->setting->find($settingId);
            $updatedSetting = $setting->update($data);

            return $updatedSetting;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }

   
  


    /**
     * Get all users with supervisor type
     *
     * @return Collection
     */


    public function find($settingId)
    {
        try {
            return $this->setting->find($settingId);
        } catch (Exception $e) {
            return null;
        }
    }

  

    /**
     * Delete a User
     *
     * @param Id
     * @return bool
     */
    public function delete($settingId)
    {

    }

 



    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/setting';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($setting)
    {
        //The Setting Images will be in use by the webapp when being changed..so the (resource remporarily unavailable) error is inevitable.. OR ( TO BE SOLVED LATER )
        /*
        try {
            if (is_file($setting->image_path))
                unlink($setting->image_path);

            if (is_file($setting->thumbnail_path))
                unlink($setting->thumbnail_path);
        } catch (Exception $e) {
            dd("IMAGE UNLINK FAIL ",$e);
        } */
    }

    public function updateImage($settingId, array $data)
    {
        try {
            $setting = $this->setting->find($settingId);
            $setting = $setting->update($data);

            return $setting;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }


    public function getValidationString( $setting ,$value)
    {
        $data_type = $setting->data_type;
        $required = $setting->required;
        $image_ratio = $setting->image_ratio;
        $validation_string = "";
        $required = ($required == 1)?"required|":"";
        if($data_type == "text")
        {
            $validation_string =  $required."";
        }
        else if($data_type == "integer" )
        {
            $validation_string = $required."integer";
        }
        else if($data_type == "double" || $data_type == "number" || $data_type == "numeric")
        {
            $validation_string = $required."numeric";   
        }
        else if($data_type == "image")
        {
            //|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'
            // $min_height = (isset($setting->image_min_height) && !empty($setting->image_min_height)) ?$setting->image_min_height:'100';
            // $min_width = (isset($setting->image_min_width) && !empty($setting->image_min_width)) ?$setting->image_min_width:'100';
            // $max_height = (isset($setting->image_max_height ) && !empty($setting->image_max_height)) ?$setting->image_max_height:'2500';
            // $max_width = (isset($setting->image_max_width) && !empty($setting->image_max_width))?$setting->image_max_width:'2500';
            // $image_height = (isset($setting->image_height) && !empty($setting->image_height)) ?$setting->image_height:'2500';
            // $image_width = (isset($setting->image_width) && !empty($setting->image_width))?$setting->image_width:'2500';

            if(isset($setting->image_ratio))
            {
                $img_required = ($setting->required == 1)?"required":"";
                $uploaded_image_size = getimagesize($value->path());
                $uploaded_image_ratio = number_format($uploaded_image_size[0]/$uploaded_image_size[1], 2);
                //dd($uploaded_image_ratio);
                //Allowed margin = +-0.1
                $allowed_error = 0.1;
                $min_allowed_image_ratio = number_format($setting->image_ratio - $allowed_error, 2); 
                $max_allowed_image_ratio = number_format($setting->image_ratio + $allowed_error, 2); 
                
                $image_ratio = $setting->image_ratio;
                $validation_string = [$img_required,"image","mimes:jpg,png,jpeg,gif,svg","max:2048",new ValidImageRatio(getSpacedTextAttribute($setting->key), $min_allowed_image_ratio ,$max_allowed_image_ratio, $uploaded_image_ratio )  ];    //dimensions:ratio=".$allowed_image_ratio."";
            }
           else{
                $validation_string = $required."image|mimes:jpg,png,jpeg,gif,svg|max:2048";
           }

            
           // dd("image_ratio:",$image_ratio);
           
      
            //$validation_string = $required."image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=".$min_width.",min_height=".$min_height.",max_width=".$max_width.",max_height=".$max_height."";
        }
        else if($data_type == "icon")
        {
            $validation_string = $required; //."image|mimes:ico|max:1048|dimensions:min_width=15,min_height=15,max_width=100,max_height=100";
        }
        else if($data_type == "link")
        {
            $validation_string = $required."";
        }
        else if($data_type == "email")
        {
            $validation_string = $required."";
        }
        else if($data_type == "json")
        {
            $validation_string = $required."json";
        }
        else{
            //do nothin
        }

        return $validation_string;

    }
}
