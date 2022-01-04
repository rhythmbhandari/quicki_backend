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

@section('page-specific-styles')
<style>
    .rider_info_img {
        width: 50px;
        height: 50px;
        border-radius: 50px;
    }
</style>
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
                <div class="row">
                    <div class="col" style="height: 80vh;" id="googleBookingMap"></div>
                </div>
                <div class="row">

                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->
@endsection


@section('page-specific-scripts')
{{-- <script
    src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyDBm6mwigSesFoEw7fa1eoe9RohWkKYb50"
    type="text/javascript">
</script> --}}

<script async
    src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyDBm6mwigSesFoEw7fa1eoe9RohWkKYb50&libraries=visualization&callback=initMap"
    type="text/javascript"></script>

<script>
    let map, heatmap;

    let pendingBooking = [];

    function initMap() {
        /* Data points defined as an array of LatLng objects */
        //map initialization
        map = new google.maps.Map(document.getElementById("googleBookingMap"), {
            center: { lat: 27.6731828, lng: 85.406599 },
            zoom: 13,
        });

        // //get pending booking locations
        getPendingBookingLatLng()

    }


    function getPendingBookingLatLng() {
        $.ajax({
            url: "/admin/nearest_pending_ajax",
            data: {
                "center_point": {lat: map.getCenter().lat(), lng: map.getCenter().lng()} 
            },
            success: function(result){
                result.nearest_booking.forEach(location => {
                    console.log(location.lat, "latitude", location.lng, "longitude")
                    pendingBooking.push(new google.maps.LatLng(location.lat, location.lng))
                });

                heatmap = new google.maps.visualization.HeatmapLayer({
                    data: pendingBooking,
                    map: map,
                });
                // console.log(pendingBooking, "pending booking")
            }
        });
    }





    
    
</script>



@endsection