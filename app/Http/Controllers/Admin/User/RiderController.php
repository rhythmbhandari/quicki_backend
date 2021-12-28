<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Models\Role;
use Illuminate\Support\Facades\DB;
use Kamaln7\Toastr\Facades\Toastr;
use App\Modules\Services\Document\DocumentService;

//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\User\RiderService;
use App\Http\Requests\Admin\Rider\RiderRequest;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\User;

class RiderController extends Controller
{


    protected $rider, $user_service, $document_service;

    public function __construct(RiderService $rider, UserService $user_service, DocumentService $document_service)
    {
        $this->rider = $rider;
        $this->user_service = $user_service;
        $this->document_service = $document_service;
    }

    public function index()
    {
        return view('admin.rider.index');
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
        }])->simplePaginate(10);
        // dd($query->toArray());
        $results = array();
        foreach ($query as $object) {
            array_push($results, [
                'id' => $object['id'],
                'text' => $object->user->first_name . ' ' . $object->user->last_name,
                'user_id' => $object->user_id
            ]);
        }
        // $pagination = [
        //     'more' => !is_null($query->toArray()['next_page_url'])
        // ];
        return compact('results');
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
        // dd($request->all());
        $data['rider'] = $request->only('experience', 'trained', 'status');
        $data['vehicle'] = $request->only('vehicle_type_id', 'vehicle_number', 'brand', 'model', 'vehicle_color', 'make_year');

        $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
        $data['rider']['status'] = (isset($data['rider']['status']) ?  $data['rider']['status'] : '') == 'on' ? 'active' : 'in_active';

        if ($request->has(['license_issue_date', 'license_expiry_date', 'license_number'])) {
            $data['license']['document_number'] = $request->license_number;
            $data['license']['type'] = "license";
            $data['license']['issue_date'] = $request->license_issue_date;
            $data['license']['expiry_date'] = $request->license_expiry_date;
        }
        if ($request->has(['bluebook_issue_date', 'bluebook_expiry_date', 'bluebook_number'])) {
            $data['bluebook']['document_number'] = $request->bluebook_number;
            $data['bluebook']['type'] = "bluebook";
            $data['bluebook']['issue_date'] = $request->bluebook_issue_date;
            $data['bluebook']['expiry_date'] = $request->bluebook_expiry_date;
        }
        if ($request->has(['insurance_issue_date', 'insurance_expiry_date', 'insurance_number'])) {
            $data['insurance']['document_number'] = $request->insurance_number;
            $data['insurance']['type'] = "insurance";
            $data['insurance']['issue_date'] = $request->insurance_issue_date;
            $data['insurance']['expiry_date'] = $request->insurance_expiry_date;
        }

        if (
            isset($data['home']['name']) && isset($data['home']['latitude']) && isset($data['home']['longitude']) &&
            isset($data['work']['name']) && isset($data['work']['latitude']) && isset($data['work']['longitude'])
        ) {
            $data['location']['home'] = $data['home'];
            $data['location']['work'] = $data['work'];
        }

        // dd($request->all());

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

        // dd($request->all());
        $data = $request->except('image');
        $data['rider'] = $request->only('experience', 'trained', 'status');
        $data['vehicle'] = $request->only('vehicle_type_id', 'vehicle_number', 'brand', 'model', 'vehicle_color', 'make_year');

        $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
        $data['rider']['status'] = (isset($data['rider']['status']) ?  $data['rider']['status'] : '') == 'on' ? 'active' : 'in_active';

        if ($request->has(['license_issue_date', 'license_expiry_date', 'license_number'])) {
            $data['license']['document_number'] = $request->license_number;
            $data['license']['type'] = "license";
            $data['license']['issue_date'] = $request->license_issue_date;
            $data['license']['expiry_date'] = $request->license_expiry_date;
        }
        if ($request->has(['bluebook_issue_date', 'bluebook_expiry_date', 'bluebook_number'])) {
            $data['bluebook']['document_number'] = $request->bluebook_number;
            $data['bluebook']['type'] = "bluebook";
            $data['bluebook']['issue_date'] = $request->bluebook_issue_date;
            $data['bluebook']['expiry_date'] = $request->bluebook_expiry_date;
        }
        if ($request->has(['insurance_issue_date', 'insurance_expiry_date', 'insurance_number'])) {
            $data['insurance']['document_number'] = $request->insurance_number;
            $data['insurance']['type'] = "insurance";
            $data['insurance']['issue_date'] = $request->insurance_issue_date;
            $data['insurance']['expiry_date'] = $request->insurance_expiry_date;
        }

        if (
            isset($data['home']['name']) && isset($data['home']['latitude']) && isset($data['home']['longitude']) &&
            isset($data['work']['name']) && isset($data['work']['latitude']) && isset($data['work']['longitude'])
        ) {
            $data['location']['home'] = $data['home'];
            $data['location']['work'] = $data['work'];
        }

        //UPDATE USER
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
        $rider = Rider::with('user')->find($rider_id);
        dd("hlw rider commission wil be cleared!");
    }
}
