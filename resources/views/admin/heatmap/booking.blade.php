@extends('layouts.admin.app')

@section('title', 'Booking Heatmap')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Booking Heatmap</a>
    </li>
</ul>
@endsection

{{-- @section('actionButton')
<a href="{{ route('admin.permission.create') }}" class="btn btn-primary font-weight-bolder fas fa-plus">
    Create Permission
</a>
@endsection --}}

@section('page-specific-styles')
<link href="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/admin/plugins/custom/lightbox/lightbox.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-title">
                    <h3 class="card-title">Heatmap</h3>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="form-group row">
                    <div class="col">
                        <label>Booking ID:</label>
                        <select class="form-control" name="booking_id" id="booking_id">
                            <option value="">Select Booking ID</option>
                            @if (isset($booking_id))
                            <option value="{{$booking_id}}" selected>{{$booking_id}}</option>
                            @endif
                        </select>
                    </div>
                </div>
                {{-- <button id="search" class="form-group btn btn-primary ml-4">Search</button> --}}


                <div class="row">
                    <div class="col" style="height: 80vh;" id="googleBookingMap"></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card card-custom gutter-b example example-compact">

                            <div class="card-body">
                                "hlw from the card"
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->
@endsection


@section('page-specific-scripts')
<script
    src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyDBm6mwigSesFoEw7fa1eoe9RohWkKYb50"
    type="text/javascript">
</script>

<script>
    //map initialization
    const map = new google.maps.Map(document.getElementById("googleBookingMap"), {
        center: { lat: 27.6731828, lng: 85.406599 },
        zoom: 13,
    });

    // direction render and service
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    $('#booking_id').select2({
        width: 'resolve',
        placeholder: "Select Booking",
        ajax: {
            'url' : '{{route('admin.booking.ajax')}}',
            'dataType': 'json'
        }
    });
</script>
@endsection