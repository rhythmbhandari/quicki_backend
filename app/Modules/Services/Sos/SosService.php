<?php

namespace App\Modules\Services\Sos;


use App\Modules\Models\Sos;
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Services\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class SosService extends Service
{
    protected $sos;

    public function __construct(Sos $sos)
    {
        $this->sos = $sos;
    }

    /*For DataTable*/
    public function getAllData()
    {

        $query = $this->sos->with('booking')->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('sos_by', function (Sos $sos) {
                if ($sos->created_by_type == "customer" || $sos->created_by_type == "admin")
                    return User::find($sos->created_by_id)->name;

                if ($sos->created_by_type == "rider") {
                    return Rider::find($sos->created_by_id)->user->name;
                }
            })
            ->editColumn('title', function (Sos $sos) {
                return $sos->title;
            })
            ->editColumn('booking', function (Sos $sos) {
                return "<span>" . $sos->booking->origin . " to " . $sos->booking->destination . "</span>";
            })
            ->editColumn('created_at', function (Sos $sos) {
                return prettyDate($sos->created_at);
            })
            ->editColumn('status', function (Sos $sos) {
                return getTableHtml($sos, 'status');
            })
            ->editColumn('actions', function (Sos $sos) {
                $editRoute = route('admin.sos-detail.create', $sos->id);
                $deleteRoute = '';
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($sos, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['booking', 'title', 'status', 'sos_by', 'actions'])
            ->make(true);
    }


    public function create(array $data)
    {
        // dd($data['address_coordinates']);
        try {
            // $data['status'] = (isset($data['status']) ?  $data['status'] : '')=='on' ? 'active' : 'in_active';
            // $data['visibility'] = (isset($data['visibility']) ?  $data['visibility'] : '')=='on' ? 'invisible' : 'visible';
            // $data['created_by']= Auth::user()->id;
            $sos = $this->sos->create($data);
            return $sos;
        } catch (Exception $e) {
            return null;
        }
    }
    public function all()
    {
        return $this->sos->get();
    }
    public function find($sosId)
    {
        try {
            return $this->sos->find($sosId);
        } catch (Exception $e) {
            return null;
        }
    }

    // public function getByBooking($booking_id) {
    //     try {
    //         return $this->sos->with('events')->where('booking_id', $booking_id)->get();
    //     } catch (Exception $e) {
    //         return null;
    //     }
    // }


    public function update($sosId, array $data)
    {
        try {
            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
            $data['visibility'] = (isset($data['visibility']) ?  $data['visibility'] : '') == 'on' ? 'invisible' : 'visible';
            $data['last_updated_by'] = Auth::user()->id;
            $sos = $this->sos->find($sosId);
            $sos = $sos->update($data);
            return $sos;
        } catch (Exception $e) {
            return false;
        }
    }

    // public function delete($SosId)
    // {
    //     try {
    //         // $data['last_deleted_by']= Auth::user()->id;
    //         // $data['deleted_at']= Carbon::now();
    //         $Sos = $this->Sos->find($SosId);
    //         // $data['is_deleted']='yes';
    //         return $Sos = $Sos->update($data);

    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }
    // function uploadFile($file)
    // {
    //     if (!empty($file)) {
    //         $this->uploadPath = 'uploads/vendor';
    //         return $fileName = $this->uploadFromAjax($file);
    //     }
    // }

    // public function __deleteImages($subCat)
    // {
    //     try {
    //         if (is_file($subCat->image_path))
    //             unlink($subCat->image_path);

    //         if (is_file($subCat->thumbnail_path))
    //             unlink($subCat->thumbnail_path);
    //     } catch (\Exception $e) {

    //     }
    // }

    // public function updateImage($SosId, array $data)
    // {
    //     try {

    //         $Sos = $this->Sos->find($SosId);
    //         $Sos = $Sos->update($data);

    //         return $Sos;
    //     } catch (Exception $e) {
    //         //$this->logger->error($e->getMessage());
    //         return false;
    //     }
    // }

    // public function createNotification(Sos $sos, $message) {
    //     return $sos->notify(new EventNotify(compact('message')));
    // }
}
