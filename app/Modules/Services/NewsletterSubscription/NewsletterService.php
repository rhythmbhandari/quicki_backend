<?php

namespace App\Modules\Services\NewsletterSubscription;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Yajra\DataTables\Facades\DataTables;

use App\Modules\Models\Newsletter;

//services
use App\Modules\Services\User\RiderService;
class NewsletterService extends Service
{
    protected $newsletter;

    function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }



        /*For DataTable*/
        public function  getAllData($filter = null)
        {
            $query = $this->newsletter->all();
    
            return DataTables::of($query)
                ->addIndexColumn()
    
                ->addColumn('title', function (Newsletter $newsletter) {
                    return $newsletter->email;
                })
                ->addColumn('created_at', function (Newsletter $newsletter) {
                    // return "test 1";
                    return prettyDate($newsletter->user->name);
                })
                ->editColumn('image', function(Newsletter $newsletter){
                    return getTableHtml($newsletter, 'image');
                })
                ->editColumn('actions', function (Newsletter $newsletter) {
                    $editRoute = route('admin.newsletter.edit', $newsletter->id);;
                    $deleteRoute = '';
                    $optionRoute = '';
                    $mapRoute = '';
                    $optionRouteText = '';
                    return getTableHtml($newsletter, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText, "", $mapRoute);
                })->rawColumns(['image','created_at', 'actions', 'title'])
                ->make(true);
        }
    



    function create($data)
    {
        try {
            $data['status'] = isset($data['status'])?$data['status'] : 'active';
            $createdRiderLocation = $this->rider_location->create($data);
            if($createdRiderLocation)
                return $createdRiderLocation;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }

    function update($riderLocationId, $data)
    {
        try {
        
            $data['status'] = isset($data['status'])?$data['status'] : 'active';
            $rider_location= RiderLocation::findOrFail($riderLocationId);
            $updatedRiderLocation = $rider_location->update($data);
            //dd($updatedRiderLocation);
            return $updatedRiderLocation;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }


}
