<?php

namespace App\Http\Controllers\Admin\Vehicle;

use App\Modules\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Services\Vehicle\VehicleTypeService;
use Illuminate\Support\Facades\DB;
use Kamaln7\Toastr\Facades\Toastr;

class VehicleTypeController extends Controller
{
    protected $vehicleType;

    public function __construct(VehicleTypeService $vehicleType)
    {
        $this->vehicleType = $vehicleType;
    }


    public function get_all_data()
    {
        return response()->json(VehicleType::all()->toArray());
    }

    public function getAllData(Request $request)
    {
        return $this->vehicleType->getAllData($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.vehicle_type.index');
    }



    public function vehicleTypeAjax(Request $request)
    {
        $query = VehicleType::select('id', 'name')
            ->when($request->q, function ($query) use ($request) {
                $q = $request->q;
                return $query->where('name', 'LIKE', "%" . $q . "%");
            })
            ->where('status', 'active')->simplePaginate(10);

        $results = array();
        foreach ($query as $object) {
            array_push($results, [
                'id' => $object['id'],
                'text' => $object['name']
            ]);
        }
        $pagination = [
            'more' => !is_null($query->toArray()['next_page_url'])
        ];
        return compact('results', 'pagination');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vehicle_type.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicleType = $this->vehicleType->find($id);
        $vehicleType->surge_rate = ($vehicleType->surge_rate - 1) * 100;
        // dd($vehicle->bluebook_path);
        return view('admin.vehicle_type.edit', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        return DB::transaction(function () use ($request) {
            if ($vehicleType = $this->vehicleType->create($request->except('image'))) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $vehicleType);
                }

                Toastr::success('Vehicle Type created successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.vehicle_type.index');
            }
            Toastr::error('Vehicle Type cannot be created.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.vehicle_type.index');
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($vehicleType = $this->vehicleType->update($id, $request->all())) {
                if ($request->hasFile('image')) {
                    $this->uploadFile($request, $vehicleType);
                }
                Toastr::success('Vehicle Type updated successfully.', 'Success !!!', ["positionClass" => "toast-bottom-right"]);
                return redirect()->route('admin.vehicle_type.index');
            }
            Toastr::error('Vehicle Type cannot be updated.', 'Oops !!!', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('admin.vehicle_type.index');
        });
    }

    //Image for user 
    function uploadFile(Request $request, $vehicleType)
    {
        // dd($vehicleType, $request);
        $file = $request->file('image');
        $fileName = $this->vehicleType->uploadFile($file);

        if (!empty($vehicleType->image))
            $this->vehicleType->__deleteImages($vehicleType);

        $data['image'] = $fileName;
        // dd($vehicleType);
        $this->vehicleType->updateImage($vehicleType->id, $data);
        // dd($data, $vehicleType);
    }
}
