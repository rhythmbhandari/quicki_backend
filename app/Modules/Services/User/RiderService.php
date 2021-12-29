<?php

namespace App\Modules\Services\User;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;


//services
use App\Modules\Services\User\UserService;
use App\Modules\Services\Document\DocumentService;
use App\Modules\Services\Vehicle\VehicleTypeService;
use App\Modules\Services\Vehicle\VehicleService;
use App\Modules\Services\Payment\PaymentService;

//models
use App\Modules\Models\Rider;
use App\Modules\Models\User;
use App\Modules\Models\Document;
use App\Modules\Models\Payment;

class RiderService extends Service
{
    protected  $rider, $user_service, $user, $vehicle_type_service, $vehicle_service, $document_service, $payment;

    function __construct(
        UserService $user_service,
        VehicleTypeService $vehicle_type_service,
        VehicleService $vehicle_service,
        DocumentService $document_service,
        PaymentService $payment,
        Rider $rider,
        User $user
    ) {
        $this->user_service = $user_service;
        $this->vehicle_service = $vehicle_service;
        $this->vehicle_type_service = $vehicle_type_service;
        $this->document_service = $document_service;
        $this->rider = $rider;
        $this->user = $user;
        $this->payment = $payment;
    }

    function getRider()
    {
        return $this->rider;
    }

    public function getAllData()
    {
        $query = $this->user->whereRelation('roles', 'name', 'rider')->get();
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
            ->editColumn('status', function (User $user) {
                return getTableHtml($user, 'status');
            })
            ->editColumn('actions', function (User $user) {
                $editRoute = route('admin.rider.edit', $user->id);
                $deleteRoute = '';
                // $deleteRoute = route('admin.vendor.destroy',$customer->id);
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($user, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    public function getCommissionData()
    {
        // dd("test");
        $query = Rider::all();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function (Rider $rider) {
                // return getTableHtml($rider->user, 'image');
                return "test";
            })
            ->editColumn('name', function (Rider $rider) {
                return $rider->user->name;
            })
            ->editColumn('total_commissions', function (Rider $rider) {
                return getTotalCommissions($rider);
            })
            ->editColumn('total_paid', function (Rider $rider) {
                return getTotalPaid($rider->user);
            })
            ->editColumn('amount_due', function () {
                return "N/A";
            })
            ->editColumn('status', function (Rider $rider) {
                return getTableHtml($rider, 'status');
            })
            ->editColumn('actions', function (Rider $rider) {
                return '<button type="button" id="makePayment" data-toggle="modal" data-target="#makePaymentForm" class="btn btn-success" style="width: 200px;"
                data-placement="top" data-id="' . $rider->id . '" data-original-title="Make Payment">Make Payment</button>
                <a href="' . route('admin.rider.history', $rider->id) . '"class="btn btn-warning" style="width: 200px;" data-toggle="tooltip"
                data-placement="top" data-original-title="Transaction History">Show History</a>
                ';
            })->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    function getAllowedRidersQuery()
    {
        $allowed_rider = new Rider();
        $allowed_riders = $allowed_rider->newQuery();
        $allowed_riders = $allowed_riders->where('status', 'active');
        $allowed_riders = $allowed_riders->whereNotNull('approved_at');
        $allowed_riders = $allowed_rider->whereHas('vehicle', function (Builder $query) {
            $query->whereRelation('vehicle_type', 'status', '!=', 'in_active');
        });
        return $allowed_riders;
    }

    function all()
    {
        return $this->rider->all();
    }

    function getAllowedRiders()
    {
        $allowed_rider = new Rider();
        $allowed_riders = $allowed_rider->newQuery();
        $allowed_riders = $allowed_riders->where('status', 'active');
        $allowed_riders = $allowed_riders->whereNotNull('approved_at');
        $allowed_riders = $allowed_rider->whereHas('vehicle', function (Builder $query) {
            $query->whereRelation('vehicle_type_id', 'status', '!=', 'in_active');
        })->get();
        return $allowed_riders;
    }
    function getAllowedRidersIds()
    {
        $allowed_rider = new Rider();
        $allowed_riders = $allowed_rider->newQuery();
        $allowed_riders = $allowed_riders->where('status', 'active');
        $allowed_riders = $allowed_riders->whereNotNull('approved_at');
        $allowed_riders = $allowed_rider->whereHas('vehicle', function (Builder $query) {
            $query->whereRelation('vehicle_type_id', 'status', '!=', 'in_active');
        })->pluck('id');
        return $allowed_riders;
    }

    function create(array $data, $user = null)
    {
        try {

            //CREATE USER
            if ($user == null)
                $createdUser = $this->user_service->create($data);
            else
                $createdUser = $user;   //Not newly created, but old user being upgraded
            //dd($createdUser, 'creating rider user');
            if ($createdUser) {
                $data['rider']['user_id'] = intval($createdUser->id);
                $data['rider']['status'] = isset($data['rider']['status']) ? $data['rider']['status'] : 'in_active';
                //CREATE RIDER
                $createdRider = $this->rider->create($data['rider']);
                if ($createdRider) {
                    $createdRider->user->roles()->attach(2);

                    //CREATE DOCUMENT
                    $data['document']['documentable_id'] = intval($createdRider->id);
                    $data['document']['documentable_type'] = 'App\Modules\Models\Rider';
                    $createdDocument = $this->document_service->create($data['document']);
                    $createdRider->latest_document =  $createdDocument;

                    //CREATE VEHICLE
                    $data['vehicle']['rider_id'] = intval($createdRider->id);
                    $createdVehicle = $this->vehicle_service->create($data['vehicle']);
                    $createdRider->vehicle =  $createdVehicle;


                    $createdRider->roles = $createdRider->user->roles();

                    //dd("RIDER CREATED: ",$createdRider, $createdVehicle, $createdDocument);
                    return $createdRider;
                }
            }
        } catch (Exception $e) {
            return null;
        }
        return null;
    }

    function riderCreate(array $data, $user = null)
    {
        try {
            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';

            //CREATE USER
            $createdUser = $this->user_service->create($data);

            //dd($createdUser, 'creating rider user');
            if ($createdUser) {
                $data['rider']['user_id'] = intval($createdUser->id);
                $data['rider']['status'] = (isset($data['rider']['status']) ?  $data['rider']['status'] : '') == 'on' ? 'active' : 'in_active';
                //CREATE RIDER
                $createdRider = $this->rider->create($data['rider']);
                if ($createdRider) {
                    $createdRider->user->roles()->attach(2);

                    //CREATE License
                    if (isset($data['license'])) {
                        $data['license']['documentable_id'] = intval($createdRider->id);
                        $data['license']['documentable_type'] = 'App\Modules\Models\Rider';
                        $createdDocument = $this->document_service->create($data['license']);
                        $createdRider->license =  $createdDocument;
                    }


                    //CREATE VEHICLE
                    $data['vehicle']['rider_id'] = intval($createdRider->id);
                    $createdVehicle = $this->vehicle_service->create($data['vehicle']);

                    if ($createdVehicle) {
                        //create insurance
                        if (isset($data['insurance'])) {
                            $data['insurance']['documentable_id'] = intval($createdVehicle->id);
                            $data['insurance']['documentable_type'] = 'App\Modules\Models\Vehicle';
                            $createdDocument = $this->document_service->create($data['insurance']);
                            $createdVehicle->insurance =  $createdDocument;
                        }

                        //create bluebook
                        if (isset($data['bluebook'])) {
                            $data['bluebook']['documentable_id'] = intval($createdVehicle->id);
                            $data['bluebook']['documentable_type'] = 'App\Modules\Models\Vehicle';
                            $createdDocument = $this->document_service->create($data['bluebook']);
                            $createdVehicle->bluebook =  $createdDocument;
                        }
                    }
                    $createdRider->vehicle =  $createdVehicle;
                    $createdUser->rider = $createdRider;
                    return $createdUser;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            return null;
        }
        return null;
    }

    function riderUpdate(array $data, $id)
    {
        try {
            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';

            //CREATE USER
            $updatedUser = $this->user_service->update($id, $data);

            //dd($updatedUser, 'creating rider user');
            if ($updatedUser) {
                $data['rider']['user_id'] = intval($updatedUser->id);
                $data['rider']['status'] = (isset($data['rider']['status']) ?  $data['rider']['status'] : '') == 'on' ? 'active' : 'in_active';
                //CREATE RIDER
                $rider = $updatedUser->rider;

                if ($rider) {
                    $rider->update($data['rider']);
                    $rider = $rider->find($rider->id);
                    $riderDocuments = $rider->documents;

                    foreach ($riderDocuments as $document) {
                        if ($document->type == "license" && isset($data['license'])) {
                            $document->update($data['license']);
                            $rider->license =  $document;
                        }
                    }

                    //UPDATE VEHICLE
                    $vehicle = $rider->vehicle;

                    if ($vehicle) {
                        $vehicle->update($data['vehicle']);

                        $vehicleDocuments = $vehicle->documents;

                        foreach ($vehicleDocuments as $document) {
                            if ($document->type == "insurance" && isset($data['insurance'])) {
                                $document->update($data['insurance']);
                                $vehicle->insurance =  $document;
                            }
                            if ($document->type == "bluebook" && isset($data['bluebook'])) {
                                $document->update($data['bluebook']);
                                $vehicle->bluebook =  $document;
                            }
                        }
                    }
                    $rider->vehicle =  $vehicle;
                    $updatedUser->rider = $rider;

                    return $updatedUser;
                }
            }
        } catch (Exception $e) {
            return null;
        }
        return null;
    }

    function find($id)
    {
        return $this->user->with('rider')->find($id);
    }


    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/rider';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($rider)
    {
        try {
            if (is_file($rider->image_path))
                unlink($rider->image_path);

            if (is_file($rider->thumbnail_path))
                unlink($rider->thumbnail_path);
        } catch (Exception $e) {
        }
    }

    public function updateImage($riderId, array $data)
    {
        try {
            $rider = $this->rider->find($riderId);
            $rider = $rider->update($data);

            return $rider;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }
}
