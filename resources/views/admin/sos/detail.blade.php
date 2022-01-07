@extends('layouts.admin.app')

@section('title', 'SOS Detail')

@section('page-specific-styles')
<style>
    .msg_cotainer {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 10px;
        border-radius: 25px;
        color: white;
        background-color: rgb(3, 128, 240);
        padding: 10px 15px;
        position: relative;

        /* border-radius: 50px;
        /* background: #ffffff; */
        /* box-shadow: inset 5px 5px 10px #b5b5b5,
            inset -5px -5px 10px #000;
        color: #fff; */
        */
    }

    .msg_head {
        position: relative;
    }

    .img_cont {
        position: relative;
        height: 70px;
        width: 70px;
    }

    .img_cont_msg {
        height: 40px;
        width: 40px;
    }

    .msg_cotainer_send {
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 10px;
        border-radius: 25px;
        color: white;
        background-color: #78e08f;
        padding: 10px;
        position: relative;
    }

    .msg_time {
        position: absolute;
        right: 10px;
        bottom: -20px;
        color: rgba(24, 23, 23, 0.5);
        font-size: 10px;
    }

    .initiator {
        position: absolute;
        left: 10px;
        top: -15px;
        color: rgba(0, 0, 0, 0.8);
        font-size: 10px;
    }

    .msg_time_send {
        position: absolute;
        left: 10px;
        bottom: -20px;
        color: rgba(9, 9, 9, 0.5);
        font-size: 10px;
    }

    .msg_info {
        color: rgba(9, 9, 9, 0.5) !important;
        font-size: 10px !important;
        margin: 0px 5px 0px 5px;
    }

    .respondant {
        position: absolute;
        right: 10px;
        top: -15px;
        color: rgba(9, 9, 9, 0.8);
        font-size: 10px;
    }

    .user_img {
        height: 70px;
        width: 70px;
        border: 1.5px solid #f5f6fa;

    }

    .user_img_msg {
        height: 40px;
        width: 40px;
        border: 1.5px solid #f5f6fa;

    }

    .img_cont {
        position: relative;
        height: 70px;
        width: 70px;
    }

    .img_cont_msg {
        height: 40px;
        width: 40px;
    }
</style>

@endsection
@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.sos.index')}}" class="text-muted">SOS List</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="{{ route('admin.sos.create')}}" class="text-active">SOS Add</a>
    </li>
</ul>
@endsection
@section('content')
<div class="card card-custom">
    <!--begin::Form-->
    <div class="card-body">
        <div class="card card-custom card-stretch gutter-b">

            <!--begin::SOS MAP-->
            {{-- <div class="card card-custom mb-2 p-1">
                <div class="card-body p-0">
                    <div class="col bg bg-light-success" style="height: 300px;" id="sosMap"></div>
                </div>
            </div> --}}
              <!--end::SOS MAP-->

            <!--begin::Header-->
            <div class="card-header align-items-center border-0 mt-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">SOS Activity</span>
                    <span
                        class="text-muted mt-3 font-weight-bold font-size-sm">{{$sos->created_at->toDayDateTimeString()}}</span>
                </h3>
            </div>
            <!-- Message body -->
            <div class="card-body msg_card_body">
                <div class="d-flex flex-wrap justify-content-start mb-4">
                    <div class="img_cont_msg">
                        <img src="{{asset($sos->user->thumbnail_path)}}" class="rounded-circle user_img_msg">
                    </div>
                    <div class="msg_cotainer">
                        {{-- <span class="initiator">Initiator Name</span> --}}
                        {{$sos->message}}
                        {{-- <span class="msg_time">8:40 AM, Today</span> --}}
                    </div>
                    <div style="flex-basis: 100%; height: 0"></div>
                    <div class="msg_info mt-2">{{$sos->created_at. ' by '. $sos->user->name}}</div>
                </div>

                @if($events != null)
                @foreach($events as $event)
                @if ($event->created_by_type == "admin")
                <div class="d-flex flex-wrap justify-content-end mb-4">
                    <div class="msg_cotainer_send">
                        {{-- <span class="respondant">Respondant Name</span> --}}
                        {{$event->message}}
                        {{-- <span class="msg_time_send">8:55 AM, Today</span> --}}
                    </div>
                    <div class="img_cont_msg">
                        <img src="{{asset($event->user->thumbnail_path)}}" class="rounded-circle user_img_msg">
                    </div>
                    <div style="flex-basis: 100%; height: 0"></div>
                    <div class="msg_info mt-2">{{$event->created_at. ' by '. $event->user->name}}</div>
                </div>
                @else
                <div class="d-flex flex-wrap justify-content-start mb-4">
                    <div class="img_cont_msg">
                        <img src="{{asset($event->user->thumbnail_path)}}" class="rounded-circle user_img_msg">
                    </div>
                    <div class="msg_cotainer">
                        {{-- <span class="initiator">Initiator Name</span> --}}
                        {{$event->message}}
                        {{-- <span class="msg_time_send">8:55 AM, Today</span> --}}
                    </div>
                    <div style="flex-basis: 100%; height: 0"></div>
                    <div class="msg_info mt-2">{{$event->created_at. ' by '. $event->user->name}}</div>

                </div>
                @endif
                @endforeach
                @endif
            </div>

        </div>

        <!-- Message BOdy ends -->

        <div class="card card-custom gutter-b">
            @if ($sos->status == 'active')
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        Action
                        <!-- <small>sub title</small> -->
                    </h3>
                </div>
            </div>
            <div class="card-body">

                <form method="post" action="{{route('admin.sos-detail.store', $sos->id, )}}">
                    @csrf
                    <div class="card-body">
                        {{-- <div class="form-group" style="display: none;">
                            <label>Action Date <span class="text-danger">*</span></label>
                            <input type="input" name="action_date" value="{{date('Y-m-d h:i:s')}}" class="form-control"
                                readonly>

                        </div> --}}

                        <div class="form-group">
                            <label>SOS Comment</label>
                            <input class="form-control @error('message') is-invalid @enderror" name="message"
                                value="{{old('message')}}" />
                            <span class="form-text text-muted">Message</span>
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label>Handler <span class="text-danger">*</span></label>
                            <select class="form-control @error('handler') is-invalid @enderror" name="handler"
                                id="handler_id">
                                <option value="{{Auth::user()->employee->id}}" selected="selected">
                                    {{Auth::user()->name}}</option>
                            </select>
                            @error('handler')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div> --}}
                        <div class="form-group">
                            <label for="">Sos Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="">
                                <option value="active" {{old('status')=='active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="closed" {{old('status')=='closed' ? 'selected' : '' }}>Closed
                                </option>

                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>


                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <a href="{{route('admin.sos.index')}}"><button type="button"
                                class="btn btn-secondary">Cancel</button></a>
                    </div>
                </form>
            </div>
            @else
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        SOS already resoved!!
                    </h3>
                </div>
            </div>
            @endif
        </div>
        <!--end::Form-->
    </div>
    @endsection



    @section('page-specific-scripts')

    <script async
    src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key={{config('app.map_key')}}&callback=initMap"
    type="text/javascript"></script>

    
    <script>
        SOS_ID = " {{  isset($sos) ? $sos->id : "" }} ";
/*
        //MAP INITIALIZATION
            
        let bookingData = {
        id: null,
            origin: null,
            destination: null,
            status: null,
            vehicle_type: null,
            rider_id: null
        }
        let riderData = {
            id: null,
            name: null,
            thumbnail_path: null,
            phone: null,
            vehicle_type: null
        }

        let map, directionService, directionsRenderer, markers = {}, infowindow, infowindowContent = "";


        function initMap() {
            //map initialization
            map = new google.maps.Map(document.getElementById("sosMap"), {
                center: { lat: 27.6731828, lng: 85.406599 },
                zoom: 13,
            });

            plotRiderData(map)
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            infowindow = new google.maps.InfoWindow({
                content: infowindowContent
            })


            map.addListener("dragend", () => {
                plotRiderData(map)
            });

            $('#booking_id').trigger('change')
        }
*/
    </script>
    @endsection