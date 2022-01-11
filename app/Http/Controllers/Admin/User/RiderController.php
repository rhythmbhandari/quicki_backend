<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Models\Role;
use Illuminate\Support\Facades\DB;
use Kamaln7\Toastr\Facades\Toastr;
use App\Modules\Services\Document\DocumentService;
use App\Modules\Services\Payment\TransactionService;
use Carbon\Carbon;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;
use App\Http\Requests\Admin\Rider\RiderRequest;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\RiderLocation;
use App\Modules\Models\User;
use App\Modules\Services\Payment\PaymentService;
use Auth;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\Builder;

class RiderController extends Controller
{


    protected $rider, $user_service, $document_service, $transaction_service, $payment_service;

    public function __construct(RiderService $rider, UserService $user_service, DocumentService $document_service, TransactionService $transaction_service, PaymentService $payment_service)
    {
        $this->rider = $rider;
        $this->user_service = $user_service;
        $this->document_service = $document_service;
        $this->transaction_service = $transaction_service;
        $this->payment_service = $payment_service;
    }

    public function index()
    {
        return view('admin.rider.index');
    }

    public function show()
    {
        dd("hlw");
    }

    public function history($rider_id)
    {
        $rider = Rider::with('user')->findOrFail($rider_id);
        return view('admin.rider.history', compact('rider'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllData()
    {
        return $this->rider->getAllData();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->with('rider')->first()->toArray();
        $rider = $user['rider'];
        $rider = getDocuments($rider);
        // $rider->document = $rider->documents[0];
        $vehicle = $rider['vehicle'];

        $vehicle = getDocuments($vehicle);        // $vehicle->document = $vehicle->documents[0];

        return view('admin.rider.edit', compact('user', 'rider', 'vehicle'));
    }


    function riderAjax(Request $request)
    {
        $query = Rider::with(['user' => function ($q) {
            $q->select('id', 'first_name', 'last_name');
        }])->whereRelation('user', 'first_name', 'LIKE', '%' . $request->q . '%')->simplePaginate(10);
        // dd($query->toArray());
        $results = array();
        foreach ($query as $object) {
            array_push($results, [
                'id' => $object['id'],
                'text' => $object->user->first_name . ' ' . $object->user->last_name,
                'user_id' => $object->user_id
            ]);
        }

        $morePages = true;
        $pagination_obj = json_encode($query);
        if (empty($query->nextPageUrl())) {
            $morePages = false;
        }

        $pagination = array(
            "more" => !is_null($query->toArray()['next_page_url'])
        );

        // $pagination = [
        //     'more' => !is_null($query->toArray()['next_page_url'])
        // ];
        return compact('results', 'pagination');
    }

    function riderActiveLocationAjax(Request $request)
    {
        // $query = RiderLocation::with('rider.vehicle.vehicle_type:id,name')->select('rider_id', 'longitude', 'latitude')
        //     ->where('availability', 'available')->where('status', 'active');

        $query = DB::table('rider_locations')
            ->join('riders', 'riders.id', '=', 'rider_locations.rider_id')
            ->join('vehicles', 'vehicles.rider_id', '=', 'riders.id')
            ->select('rider_locations.rider_id', 'rider_locations.longitude', 'rider_locations.latitude', 'vehicles.vehicle_type_id')
            ->where('rider_locations.availability', 'available')->where('rider_locations.status', 'active')->distinct();

        $total_available = RiderLocation::where('availability', 'available')->count();
        $total_active = RiderLocation::where('status', 'active')->count();

        //if rider_id is set fetch only rider_id and return...
        if (isset($request->rider_id)) {
            $nearest_rider = [];
            $active_rider = $query->where('rider_locations.rider_id', $request->rider_id)->first();
            if ($active_rider == null)
                $nearest_rider = null;
            else
                array_push($nearest_rider, $active_rider);

            return compact('nearest_rider', 'total_available', 'total_active');
        }

        $active_riders = [];

        if ($request->has('cust_id')) {
            //not fetching rider if cust is rider himself!!
            $cust_rider = User::find($request->cust_id)->rider;
            if ($cust_rider) {
                $query->where('rider_locations.rider_id', '<>', $cust_rider->id);
            }
        }

        if ($request->has('vehicle_type')) {
            $query->where('vehicles.vehicle_type_id', $request->vehicle_type);
            // $data = $query->get();
            // $param = $request->all();
            // return compact('data', 'param');
        }

        $active_riders = $query->get();

        $nearest_rider = [];
        $centerPoint = ['lat' => 27.687169, 'lng' => 85.304219]; //default center_point
        //if center_point present fetch all riders near to that rider.
        if ($request->has('center_point')) {
            $centerPoint = $request->center_point;
        }

        foreach ($active_riders as $rider) {
            if ($this->rider->arePointsNear(
                $centerPoint,
                ['lat' => $rider->latitude, 'lng' => $rider->longitude]
            )) {
                array_push($nearest_rider, $rider);
            }
        }

        if (sizeof($nearest_rider) == 0) {
            $nearest_rider = null;
        }

        return compact('nearest_rider', 'total_available', 'total_active');
    }

    function getRiderDetail($rider_id)
    {
        $rider = Rider::select('experience', 'trained', 'id', 'user_id')->with(['user' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'image', 'phone');
        }])->find($rider_id);

        return compact('rider');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.rider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RiderRequest $request)
    {
        $data = $request->except('image');
        $data['rider'] = $request->rider;
        $data['vehicle'] = $request->only('vehicle_type_id', 'vehicle_number', 'brand', 'model', 'vehicle_color', 'make_year');

        $data['license'] = $request->license;
        $data['bluebook'] = $request->bluebook;
        $data['insurance'] = $request->insurance;

        if (
            isset($data['home']['name']) && isset($data['home']['latitude']) && isset($data['home']['longitude']) &&
            isset($data['work']['name']) && isset($data['work']['latitude']) && isset($data['work']['longitude'])
        ) {
            $data['location']['home'] = $data['home'];
            $data['location']['work'] = $data['work'];
        }

        return DB::transaction(function () use ($request, $data) {
            if ($user = $this->rider->riderCreate($data)) {
                // dd($user);
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $user);
                }
                if (isset($user->rider->license) && $request->hasFile('license_image')) {
                    $this->uploadDocument($request, $user->rider->license);
                }
                if (isset($user->rider->vehicle->bluebook) && $request->hasFile('bluebook_image')) {
                    $this->uploadDocument($request, $user->rider->vehicle->bluebook);
                }
                if (isset($user->rider->vehicle->insurance) && $request->hasFile('insurance_image')) {
                    $this->uploadDocument($request, $user->rider->vehicle->insurance);
                }

                if (isset($data['location']))
                    $this->user_service->update_location($user->id, $data);

                Toastr::success('Rider created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.rider.index');
            }
            Toastr::error('Rider cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.rider.index');
        });
    }

    public function update(RiderRequest $request, $user_id)
    {

        $data = $request->except('image');
        $data['rider'] = $request->rider;
        // $data['rider']['status'] = $request->rider['status'];
        $data['vehicle'] = $request->only('vehicle_type_id', 'vehicle_number', 'brand', 'model', 'vehicle_color', 'make_year');
        $data['license'] = $request->license;
        $data['bluebook'] = $request->bluebook;
        $data['insurance'] = $request->insurance;

        if (
            isset($data['home']['name']) && isset($data['home']['latitude']) && isset($data['home']['longitude']) &&
            isset($data['work']['name']) && isset($data['work']['latitude']) && isset($data['work']['longitude'])
        ) {
            $data['location']['home'] = $data['home'];
            $data['location']['work'] = $data['work'];
        }

        //UPDATE USER

        // dd($data);
        return DB::transaction(function () use ($data, $user_id, $request) {
            $updatedUser = $this->rider->riderUpdate($data, $user_id);

            if ($updatedUser) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $updatedUser);
                }

                if (isset($updatedUser->rider->license) && $request->hasFile('license_image')) {
                    $this->uploadDocument($request, $updatedUser->rider->license);
                }

                if (isset($updatedUser->rider->vehicle->bluebook) && $request->hasFile('bluebook_image')) {
                    $this->uploadDocument($request, $updatedUser->rider->vehicle->bluebook);
                }
                if (isset($updatedUser->rider->vehicle->insurance) && $request->hasFile('insurance_image')) {
                    $this->uploadDocument($request, $updatedUser->rider->vehicle->insurance);
                }

                if (isset($data['location']))
                    $this->user_service->update_location($updatedUser->id, $data);

                Toastr::success('Rider updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.rider.index');
            }
            Toastr::error('Rider could not be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.rider.index');
        });
    }


    //Image for user 
    function uploadFile(Request $request, $user)
    {
        // dd($user, $request);
        $file = $request->file('image');
        $fileName = $this->user_service->uploadFile($file);

        if (!empty($user->image))
            $this->user_service->__deleteImages($user);

        $data['image'] = $fileName;
        // dd($user);
        $this->user_service->updateImage($user->id, $data);
    }

    //Rider Documents
    function uploadDocument(Request $request, $document)
    {

        $file = $request->file($document->type . '_image');
        // dd($file);

        $fileName = $this->document_service->uploadFile($file);
        if (!empty($document->image))
            $this->document_service->__deleteImages($document);

        $data['image'] = $fileName;
        $this->document_service->updateImage($document->id, $data);
    }

    function riderCommissionData()
    {
        return $this->rider->getCommissionData();
    }

    function riderCommission()
    {
        return view('admin.rider.commission');
    }

    function makePaymentModal($rider_id)
    {
        $rider = Rider::with('user')->find($rider_id);

        $result = [];
        $result['content'] = view('admin.rider.includes.make_payment', compact('rider'))->render();

        return $result;
    }

    function clearCommission($rider_id)
    {
        $rider = Rider::with('user', 'completed_trips')->find($rider_id);
        $total_commission = getTotalCommissions($rider);
        $total_paid = getTotalPaid($rider->user);

        $commission_due = $total_commission - $total_paid;
        if ($commission_due > 0) {
            $data['amount'] = $commission_due;
            $data['trasaction_date'] = Carbon::now();
            $data['creditor_type'] = 'rider';
            $data['creditor_id'] = $rider->user->id;
            $data['debtor_type'] = 'admin';
            $data['debtor_id'] = Auth::id();
            $data['payment_mode'] = 'offline';

            return DB::transaction(function () use ($data, $rider) {
                $createdTransaction = $this->transaction_service->create($data);

                if ($createdTransaction) {
                    $this->payment_service->clearRiderCommission($rider);
                    Toastr::success('Commission cleared.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                    return redirect()->route('admin.rider.commission');
                }

                Toastr::error('Commission failed to clear.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.rider.commission');
            });
        }
        // dd("hlw rider commission wil be cleared!");
    }
}
