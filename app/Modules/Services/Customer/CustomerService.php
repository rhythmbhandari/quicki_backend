<?php

namespace App\Modules\Services\Customer;

use App\Modules\Services\Service;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Modules\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CustomerService extends Service
{


    protected $customer;

    function __construct(User $customer)
    {
        $this->customer = $customer;
    }

    public function getAllData()
    {
        $query = $this->customer->whereRelation('roles', 'name', 'customer')->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image', function (User $user) {
                return getTableHtml($user, 'image');
            })
            ->editColumn('name', function (User $user) {
                return $user->full_name;
            })
            ->editColumn('status', function (User $user) {
                return getTableHtml($user, 'status');
            })
            ->editColumn('actions', function (User $user) {
                $editRoute = route('admin.customer.edit', $user->id);
                $deleteRoute = '';
                // $deleteRoute = route('admin.vendor.destroy',$customer->id);
                $optionRoute = '';
                $optionRouteText = '';
                return getTableHtml($user, 'actions', $editRoute, $deleteRoute, $optionRoute, $optionRouteText);
            })->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->customer::find($id);
    }

    public function create(array $data)
    {
        try {
            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
            //            $data['has_sub_content'] = (isset($data['has_subuser']) ?  $data['has_subuser'] : '')=='on' ? 'yes' : 'no';
            // $data['password'] =  bcrypt('password');
            $data['password'] =  Hash::make($data['password']);
            $data['created_by'] = Auth::user()->id;
            $user = $this->customer->create($data);
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    public function update($customerId, array $data)
    {
        try {
            $data['status'] = (isset($data['status']) ?  $data['status'] : '') == 'on' ? 'active' : 'in_active';
            $data['last_updated_by'] = Auth::user()->id;
            // $data['password'] =  bcrypt('password');
            $customer = $this->customer->find($customerId);
            $customer = $customer->update($data);
            return $customer;
        } catch (Exception $e) {
            return false;
        }
    }

    public function all()
    {
        return $this->customer->whereNotNull('deleted_at')->get();
    }

    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/user';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($customer)
    {
        try {
            if (is_file($customer->image_path))
                unlink($customer->image_path);

            if (is_file($customer->thumbnail_path))
                unlink($customer->thumbnail_path);
        } catch (\Exception $e) {
        }
    }

    public function updateImage($customerId, array $data)
    {
        try {

            $customer = $this->customer->find($customerId);
            $customer = $customer->update($data);

            return $customer;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }
}
