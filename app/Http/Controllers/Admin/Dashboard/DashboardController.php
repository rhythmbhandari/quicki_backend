<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboard;
    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        return view('admin.dashboard.index');
    }


    public function getTransactionData()
    {
        // return $this->dashboard->getLatestTransaction();
    }
}
