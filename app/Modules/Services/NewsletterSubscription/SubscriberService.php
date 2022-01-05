<?php

namespace App\Modules\Services\NewsletterSubscription;

use Illuminate\Http\Request;
use App\Modules\Services\Service;

use Yajra\DataTables\Facades\DataTables;
use App\Modules\Models\Subscriber;

//services
use App\Modules\Services\User\RiderService;

class SubscriberService extends Service
{
    protected $subscriber;

    function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }



        /*For DataTable*/
        public function  getAllData($filter = null)
        {
            $query = $this->subscriber->all();
    
            return DataTables::of($query)
                ->addIndexColumn()
    
                ->addColumn('email', function (Subscriber $subscriber) {
                    return $subscriber->email;
                })
                ->addColumn('created_at', function (Subscriber $subscriber) {
                    // return "test 1";
                    return prettyDate($subscriber->created_at);
                })
                ->addColumn('subscribed', function (Subscriber $subscriber) {
                    // return "test 1";
                    if($subscriber->subscribed){
                        return '<div class="bg bg-light-success px-2 rounded text-dark mx-auto" style="width:fit-content">subscribed</div>';
                    }
                    else{
                        return '<div class="bg bg-light-danger px-2 rounded text-dark mx-auto" style="width:fit-content">unsubscribed</div>';
                    }
                    
                })
                ->editColumn('actions', function (Subscriber $subscriber) {
                    $editRoute = '';//route('admin.subscriber.edit', $subscriber->id);
                    $deleteRoute = '';
                    $optionRoute = '';
                    $mapRoute = '';
                    $optionRouteText = '';
                    return getTableHtml($subscriber, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText, "", $mapRoute);
                })->rawColumns(['email','created_at', 'actions', 'subscribed'])
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
