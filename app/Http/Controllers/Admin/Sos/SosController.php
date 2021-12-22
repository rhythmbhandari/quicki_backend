<?php

namespace App\Http\Controllers\Admin\Sos;

use App\Http\Controllers\Controller;
use App\Modules\Models\Booking;
use App\Modules\Models\Sos;
use App\Modules\Models\User;
use App\Modules\Models\Rider;
use App\Modules\Models\Event;
use App\Modules\Services\Sos\SosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Services\Notification\NotificationService;

class SosController extends Controller
{
    protected $sos, $notification_service;
    function __construct(
        SosService $sos,
        NotificationService $notification_service
    ) {
        $this->sos = $sos;
        $this->notification_service = $notification_service;
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

    public function eventcreate($id)
    {
        $sos = SOS::with('booking')->find($id);

        if ($sos->created_by_type == 'rider')
            $sos_creator = Rider::find($sos->created_by_id)->user;
        else
            $sos_creator = User::find($sos->created_by_id);

        $sos->user = $sos_creator;

        $events = Event::where('sos_id', $id)->get();

        foreach ($events as $event) {
            if ($event->created_by_type == 'rider')
                $event_creator = Rider::find($event->created_by_id);
            else
                $event_creator = User::find($event->created_by_id);

            $event->user = $event_creator;
        }


        return view('admin.sos.detail', compact('sos', 'events'));
    }

    public function store()
    {
    }

    public function eventstore(Request $request, $id)
    {
        // dd($request->all(), $id);
        $this->validate($request, [
            'message' => 'required|string|max:255',
            'status' => 'required|string',
        ]);
        $sos = SOS::find($id);
        if (request('status') == 'closed') {
            $sos->status = 'closed';
            $sos->save();
        }

        $sosevent = new Event();
        $sosevent->message = request('message');
        $sosevent->sos_id = $id;
        $sosevent->created_by_id = Auth::user()->id;
        $sosevent->created_by_type = 'admin';
        $sosevent->save();

        //SEND PUSH NOTIFICATION HERE
        //Send Notification
        $response = $this->notification_service->send_firebase_notification(
            [
                [$sos->created_by_type, $sos->created_by_id],
            ],
            "sos_event",
            "individual",
            [
                'title' => 'Sos Event : ' . $sos->title,
                'body' => $request->message
            ]

        );

        // dd($response);

        if ($response) {
            return redirect()->route('admin.sos-detail.create', $sos->id)->with("status", "Action notification Sent");
        } else {
            return redirect()->route('admin.sos-detail.create', $sos->id)->with("error", "Error while sending action notification");
        }
    }
}
