<?php

namespace App\Http\Controllers\Admin\Sos;

use App\Http\Controllers\Controller;
use App\Modules\Models\Booking;
use App\Modules\Models\Sos;
// use App\Modules\Models\SosEvent;
use App\Modules\Services\Sos\SosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SosController extends Controller
{
    protected $sos;
    function __construct(SosService $sos)
    {
        $this->sos = $sos;

        $this->middleware('permission:sos-view|sos-add|sos-edit|sos-delete', ['only' => ['index', 'show']]);

        $this->middleware('permission:sos-add', ['only' => ['create', 'store']]);

        $this->middleware('permission:sos-edit', ['only' => ['edit', 'update']]);

        $this->middleware('permission:sos-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sos.index');
    }

    public function getAllData()
    {
        return $this->sos->getAllData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bookings = Booking::get();
        return view('admin.sos.create', compact('bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sos = Sos::findOrFail($id);
        return view('admin.sos.edit', compact('sos'));
    }

    // public function eventcreate($id)
    // {
    //     $sos = SOS::with('booking')->find($id);
    //     $events = SosEvent::where('sos_id', $id)->get();
    //     return view('admin.sos.detail', compact('sos', 'events'));
    // }

    public function store()
    {
    }

    // public function eventstore(Request $request, $id, FirebaseNotificationService $sendNotification)
    // {

    //     $this->validate($request, [
    //         'action_taken' => 'required|string|max:255',
    //         'sos_status' => 'required|string',
    //     ]);
    //     $sos = SOS::with('customer')->find($id);
    //     if (request('sos_status') == 'resolved') {
    //         $sos->status = 'resolved';
    //         $sos->save();
    //     }

    //     $sosevent = new SosEvent();
    //     $sosevent->status = request('sos_status');
    //     $sosevent->action_taken = request('action_taken');
    //     $sosevent->sos_id = $id;
    //     $sosevent->user_id = Auth::user()->id;
    //     $sosevent->save();

    //     $response = $sendNotification->send([
    //         "title" => "SOS Action Taken",
    //         "body" => request('action_taken')
    //     ], [$sos->booking->customer->device_token],  [
    //         'type' => 'sos_action'
    //     ]);

    //     if (str_contains($response, '"success":1')) {
    //         return redirect()->route('admin.sos.index')->with("status", "Action notification Sent");
    //     } else {
    //         return redirect()->route('admin.sos.index')->with("error", "Error while sending action notification");
    //     }
    // }
}
