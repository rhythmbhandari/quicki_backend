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

    .btn-label {
        background-color: white;
        border-radius: 10px;
        padding: 5px 10px;
        border: 1px solid rgba(128, 128, 128, 0.398);
    }

    .label-check {
        margin-right: 3px;
        font-size: 15px;
    }

    /** style for checkbox **/

    .radio-input {
        visibility: hidden;
    }

    .radio-label {
        position: relative;
        padding-left: 35px;
    }

    .radio-label:after {
        content: "";
        display: block;
        width: 12px;
        height: 12px;
        position: absolute;
        left: 4px;
        top: 4px;
        border-radius: 50%;
    }

    .radio-border {
        width: 20px;
        height: 20px;
        display: inline-block;
        outline: solid 3px #d449e3;
        border-radius: 50%;
        position: absolute;
        left: 0px;
        top: 0px;
    }

    .radio-border.pending {
        outline: solid 3px #E3B809;
    }

    .radio-border.accepted {
        outline: solid 3px #FA3AA2;
    }

    .radio-border.running {
        outline: solid 3px blue;
    }

    .radio-border.completed {
        outline: solid 3px #57FA0D;
    }

    .radio-border.cancelled {
        outline: solid 3px red;
    }


    .radio-input:checked+.radio-label.pending:after {
        transition: all 0.5s;
        background-color: #E3B809;
    }

    .radio-input:checked+.radio-label.accepted:after {
        transition: all 0.5s;
        background-color: #FA3AA2;
    }

    .radio-input:checked+.radio-label.running:after {
        transition: all 0.5s;
        background-color: blue;
    }

    .radio-input:checked+.radio-label.completed:after {
        transition: all 0.5s;
        background-color: #57FA0D;
    }

    .radio-input:checked+.radio-label.cancelled:after {
        transition: all 0.5s;
        background-color: red;
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
                <form class="my-10">
                    <input type=radio id="rdo1" checked class="radio-input" value="pending" name="radio-group"
                        onchange='handleChange(this);'>
                    <label for="rdo1" class="radio-label pending"> <span class="radio-border pending"></span>
                        Pending
                    </label>

                    <input type=radio id="rdo2" class="radio-input" value="accepted" name="radio-group"
                        onchange='handleChange(this);'>
                    <label for="rdo2" class="radio-label accepted"><span class="radio-border accepted"></span>Accepted
                    </label>

                    <input type=radio id="rdo3" class="radio-input" value="running" name="radio-group"
                        onchange='handleChange(this);'>
                    <label for="rdo3" class="radio-label running"><span
                            class="radio-border running"></span>Running</label>

                    <input type=radio id="rdo4" class="radio-input" value="completed" name="radio-group"
                        onchange='handleChange(this);'>
                    <label for="rdo4" class="radio-label completed"><span
                            class="radio-border completed"></span>Completed</label>

                    <input type=radio id="rdo5" class="radio-input" value="cancelled" name="radio-group"
                        onchange='handleChange(this);'>
                    <label for="rdo5" class="radio-label cancelled"><span
                            class="radio-border cancelled"></span>Cancelled</label>

                </form>
                <div class="row">
                    <div class="col" style="height: 80vh;" id="googleBookingMap"></div>
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

<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>

<script async
    src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyDBm6mwigSesFoEw7fa1eoe9RohWkKYb50&libraries=visualization&callback=initMap"
    type="text/javascript"></script>

<script>
    // let map, heatmap;

    // let pendingBooking = [];
    let map , infoWindow ;
    let markerClusterer = null, markers = {};
    let checkedStatus = "pending" //first status to be shown is pending!
    let locations = {}

    function initMap() {

        map = new google.maps.Map(document.getElementById("googleBookingMap"), 
        {
            zoom: 10,
            center: { lat: 27.6731828, lng: 85.406599 },
        })

        // console.log("testing")

        getBookingByType();

    }

    // function initiateVisualization() {

    // 

    var getGoogleClusterInlineSvg = function (color) {
        var encoded = window.btoa('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-100 -100 200 200"><defs><g id="a" transform="rotate(45)"><path d="M0 47A47 47 0 0 0 47 0L62 0A62 62 0 0 1 0 62Z" fill-opacity="0.7"/><path d="M0 67A67 67 0 0 0 67 0L81 0A81 81 0 0 1 0 81Z" fill-opacity="0.5"/><path d="M0 86A86 86 0 0 0 86 0L100 0A100 100 0 0 1 0 100Z" fill-opacity="0.3"/></g></defs><g fill="' + color + '"><circle r="42"/><use xlink:href="#a"/><g transform="rotate(120)"><use xlink:href="#a"/></g><g transform="rotate(240)"><use xlink:href="#a"/></g></g></svg>');

        return ('data:image/svg+xml;base64,' + encoded);
    }

    function getBookingByType() {
        $.ajax({
            url:"/admin/booking_location_by_type",
            data:{
                "status": checkedStatus
            },
            success: function(res) {
                //clear markers
                if(markerClusterer != null)
                        markerClusterer.clearMarkers()

                locations = {...res.booking_loc}

                Object.keys(locations).forEach(function(k) {
                    // markers[] =
                    // console.log(k, "Printing the keys in object"); 
                    markers[k] = locations[k].map((position) => {
                        // console.log(position, "printing the position of marker")
                        let marker = new google.maps.Marker({
                            position: position,
                            icon: markerIcons[k]
                        })

                        marker.addListener("click", ()=> {
                            console.log("Marker was clicked do something!!!!")
                        })
                        return marker
                    })
                })
                // console.log(markers, "testing markers after data fetch")
                Object.keys(markers).forEach(function(marker_type) {
                    if(markers[marker_type].length) {
                        let cluster_styles = [
                                            {
                                                width: 60,
                                                height: 60,
                                                url: getGoogleClusterInlineSvg(colorCodes[marker_type]),
                                                textColor: 'white',
                                                textSize: 12
                                            }
                                        ];


                        markerClusterer = new MarkerClusterer(map, markers[marker_type], {styles: cluster_styles});
                    }
                })
            }

        })
    }

    function getPendingBookingLatLng() {
        $.ajax({
        url: "/admin/nearest_pending_ajax",
        data: {
        "center_point": {lat: map.getCenter().lat(), lng: map.getCenter().lng()}
        },
        success: function(result){
        result.nearest_booking.forEach(location => {
        // console.log(location.lat, "latitude", location.lng, "longitude")
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


    function handleChange() {
        checkedStatus = $('.radio-input:radio:checked').val()
        getBookingByType();
    }
</script>



@endsection