<?php

namespace App\Modules\Services\Booking;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use App\Modules\Models\PriceDetail;

class PriceDetailService extends Service
{
    
    protected $price_detail;

    function __construct(PriceDetail $price_detail)
    {
        $this->price_detail = $price_detail;
    }

    function getPriceDetail(){
        return $this->price_detail;
    }
    
    function create(array $data)
    {   //dd("price detail: ", $data);
        try {
            $data['booking_id'] = isset($data['booking_id'])?intval($data['booking_id']):null;
            $data['completed_trip_id'] = isset($data['completed_trip_id'])?intval($data['completed_trip_id']):null;
            
            $data['minimum_charge'] = floatval( number_format( $data['minimum_charge'] ,2)   );
            $data['price_per_km'] = floatval( number_format( $data['price_per_km'] ,2) );
            $data['price_after_distance'] = floatval( number_format( $data['price_after_distance'] ,2) );
            $data['surge_rate'] = floatval( number_format( $data['surge_rate'] ,2) );
            $data['surge'] = floatval( number_format( $data['surge'] ,2) );
            $data['price_after_surge'] = floatval( number_format($data['price_after_surge']  ,2) );
            $data['app_charge_percent'] = floatval( number_format( $data['app_charge_percent'] ,2) );
            $data['app_charge'] = floatval( number_format( $data['app_charge'] ,2) );
            $data['price_after_app_charge'] = floatval( number_format( $data['price_after_app_charge'] ,2) );
            $data['price_per_min'] = floatval( number_format(  $data['price_per_min'] ,2));
            $data['duration_charge'] = floatval( number_format( $data['duration_charge'] ,2) );
            $data['price_after_duration'] = floatval( number_format( $data['price_after_duration'] ,2) );
            $data['total_price'] = floatval( number_format(  $data['total_price'],2) );
            
            $createdPriceDetail = $this->price_detail->create($data);
            
            if($createdPriceDetail)
                return $createdPriceDetail;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }



    public function update($price_detailId,array $data)
    {
        try {
        
            $price_detail= PriceDetail::findOrFail($price_detailId);
            $updatedPriceDetail = $price_detail->update($data);
            return $price_detail;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }
   



    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/price_detail';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($price_detail)
    {
        try {
            if (is_file($price_detail->image_path))
                unlink($price_detail->image_path);

            if (is_file($price_detail->thumbnail_path))
                unlink($price_detail->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($price_detailId, array $data)
    {
        try {
            $price_detail = $this->price_detail->find($price_detailId);
            $price_detail = $price_detail->update($data);

            return $price_detail;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }


}
