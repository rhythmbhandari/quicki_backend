@extends('layouts.admin.app')

@section('title', 'Booking Dispatcher')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Booking Dispatcher</a>
    </li>
</ul>
@endsection

@section('page-specific-styles')
<style>
    .select2 {
        width: 100% !important;
    }

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
                    <h3 class="card-title">Dispatcher</h3>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="form-group row align-items-end">
                    <div class="col-10">
                        <label>Booking ID:</label>
                        <select class="form-control" name="booking_id" id="booking_id">
                            <option value="">Select Booking ID</option>
                            @if (isset($_GET['booking_id']))
                            {{$selectedBooking = \App\Modules\Models\Booking::with(['user' => function ($q) {
                            $q->select('id', 'first_name', 'last_name');
                            }, 'rider.user' => function ($q) {
                            $q->select('id', 'first_name', 'last_name');
                            }, 'vehicle_type' => function ($q) {
                            $q->select('id', 'name');
                            }])->find($_GET['booking_id'])}}
                            <option value="{{$_GET['booking_id']}}" selected>{{'ID: ' . $selectedBooking->id . ' /
                                Origin: ' . $selectedBooking->location['origin']['name'] . ' / Destination: ' .
                                $selectedBooking->location['origin']['name'] . ' / Status: ' . $selectedBooking->status
                                . ' / Vehicle Type: ' . $selectedBooking->vehicle_type->name . ' / Customer: ' .
                                $selectedBooking->user->first_name . ' ' . $selectedBooking->user->last_name . ' /
                                Rider: ' . $selectedBooking->rider->user->first_name . ' ' .
                                $selectedBooking->rider->user->last_name}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col" style="display: flex;">
                        <button onclick="clearSelection()" class="btn btn-danger"
                            style="margin-left: auto !important; width: 100%"><i
                                class="fa fa-trash mr-2"></i>Clear</button>
                    </div>
                </div>
                {{-- <button id="search" class="form-group btn btn-primary ml-4">Search</button> --}}


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
    src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyDBm6mwigSesFoEw7fa1eoe9RohWkKYb50&callback=initMap"
    type="text/javascript"></script>

<script>
    /** Variable Initializations **/
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
        map = new google.maps.Map(document.getElementById("googleBookingMap"), {
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



    //updating active riders every 5 seconds..
    window.setInterval(function(){
        plotRiderData(map)
    }, 10000);

    /** Event Listeners **/
    $("#booking_id").on('change', initiateMaping);


    /** Ajax call functions **/
    function assignRider(booking_id, rider_id) {
        console.log(booking_id, rider_id, "printing booking and rider_id")
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        // icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Assign Rider!'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "/admin/booking/change_status",
                data: {
                    "_token": "{{csrf_token()}}",
                    "booking_id": bookingData.id,
                    "new_status": "accepted",
                    "optional_data": {
                        "rider_id": riderData.id
                    }
                },
                success: function(res) {
                    //re-render booking data
                    initiateMaping()
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    }).fire({
                        icon: 'success',
                        title: 'Booking Updated Successfully!'
                    })

                },
                fail: function(e) {
                    console.log(e);
                }
            });

        }
        })
    }

    function initiateMaping() {
        //close any infowindow incase open
        // console.log("something triggered!")
        infowindow.close()

        booking_id = $('#booking_id').val();

        if(booking_id) {
            $.ajax({
                url: "/admin/map/dispatcher/booking_detail/" + booking_id, success: function(result){
                    let booking = result.booking
                    console.log(result.booking.rider_id, "printing rider data for verification!")
                    bookingData.origin = {lat: booking.location.origin.latitude, lng: booking.location.origin.longitude}
                    bookingData.destination = {lat: booking.location.destination.latitude, lng: booking.location.destination.longitude}
                    bookingData.status = booking.status
                    bookingData.id = booking.id
                    bookingData.rider_id = booking.rider_id
                    bookingData.vehicle_type = booking.vehicle_type

                    console.log(bookingData, "updated bookingData")
                    // bookingData.vehicle_type = 

                    calculateAndDisplayRoute(directionsService, directionsRenderer)
                }
            });
        }
        else {
        //reseting bookingData
            bookingData = {
                id: null,
                origin: null,
                destination: null,
                status: null,
                vehicle_type: null,
                rider_id: null
            }

            directionsRenderer.setMap(null)
            directionsRenderer = new google.maps.DirectionsRenderer()
            directionsRenderer.setMap(map);
            plotRiderData(map)
        }
        
        // console.log(booking_id, "printing booking id")

        
    }

    async function getRiderInfo(rider_id) {
        return new Promise(function (resolve, reject){
            $.ajax({
            url: "/admin/rider/" + rider_id + "/detail",
            success: function(res){
                // console.log(res.rider)
                riderData.name = res.rider.user.name
                riderData.thumbnail_path = res.rider.user.thumbnail_path
                riderData.phone = res.rider.user.phone
                riderData.id = res.rider.id

                resolve(res.rider);
                // console.log(result.rider.user, result.rider)
            },
            fail: (e) => {
                reject(e);
            }
        });
        })
    }


   

    function plotRiderData(map) {
        // console.log("rider data is being fetched!!!!")
        let data = {}
        
        if(bookingData.status == null) {
            data['center_point'] = {lat: map.getCenter().lat(), lng: map.getCenter().lng()}
        }
        if(bookingData.status == "pending") {
            data['center_point'] = bookingData.origin
            data['vehicle_type'] = bookingData.vehicle_type
        }
        if(bookingData.status == "accepted") {
            data['rider_id'] = bookingData.rider_id 
        }
        $.ajax({
            url: "/admin/active_rider_data",
            data: data,
            success: function(result){
                console.log(result, "rider location fetched!!")
                let new_riders = {};

                console.log("printing rider locations", result)

                //create marker of rider_id that are new
                result.nearest_rider.map(new_rider=> {
                    new_riders[new_rider.rider_id] = {lat: new_rider.latitude, lng: new_rider.longitude};
                    let vehicle_icon = (new_rider.rider.vehicle.vehicle_type_id == 1) ? ICONS.bike : ICONS.car
                    if(!(new_rider.rider_id in markers)) {
                        markers[new_rider.rider_id] = new google.maps.Marker({
                            position: {lat: new_rider.latitude, lng: new_rider.longitude}, 
                            map,
                            icon: vehicle_icon
                        })

                        markers[new_rider.rider_id].addListener("click", () => {
                            getRiderInfo(new_rider.rider_id).then(response => {
                                // console.log(response)
                                if(response) {
                                    infowindow.setContent(generateRiderContent())
                                    infowindow.open({
                                        anchor: markers[new_rider.rider_id],
                                        map,
                                        shouldFocus: false
                                    })
                                } else {
                                    infowindow.setContent("Failed to retrieve driver info!");
                                }
                            }).catch((e)=> console.log(e))

                        })
                    }

                })

                //update marker that are still present in new riderlist and delete those
                //that aren't
                for(rider_id in markers) {
                    if(rider_id in new_riders) {
                        markers[rider_id].setPosition(new google.maps.LatLng(new_riders[rider_id].lat, new_riders[rider_id].lng));

                    }
                    else {
                        markers[rider_id].setMap(null)
                        delete markers[rider_id]
                    }
                }

                // console.log("rider data fetch completed!")
            }
        });
    }

    // direction service
    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        if(bookingData.origin != null || bookingData.destination != null) {
            console.log(bookingData.origin, bookingData.destination, "Testing origin and destination")
            directionsService.route({
                origin: bookingData.origin,
                destination:bookingData.destination,
                travelMode: google.maps.TravelMode.DRIVING,
            })
            .then((response, status) => {
                // $('#distance').val(response.routes[0].legs[0].distance.text);
                directionsRenderer.setDirections(response);
                plotRiderData(map)
            }).catch((e) => console.log("Directions request failed due to " + e));
        }
    }


    function generateRiderContent() {

        // console.log(riderData.thumbnail_path, "printing thumail path")
        let content = '<div id="content">' +
        '<div class=""><img class="rider_info_img" src="/'+ riderData.thumbnail_path +'" /></div>'+
        '<div id="siteNotice">' +
        "</div>" +
        '<h1 id="firstHeading" class="firstHeading">'+ riderData.name+'</h1>' +
        '<div id="bodyContent">' +
        "<p>Phone: " + riderData.phone + "</p>" +
        "<p> Aliqua voluptate deserunt sint esse cupidatat irure nisi amet cupidatat fugiat tempor reprehenderit duis.</p>" +
        "</div>" +
        "</div>"


        if(bookingData.status && bookingData.status == "pending") {
            content += `<div><button id="btnAssignRider" onclick="assignRider(${bookingData.id}, ${riderData.id})" class="btn btn-success">
        Assign Rider</button></div> `
        }
        // console.log("hlw?")
        return content
    }

    $('#booking_id').select2({
        width: 'resolve',
        placeholder: "Select Booking",
        ajax: {
            'url' : '{{route('admin.booking.ajax')}}',
            'dataType': 'json'
        }
    })

    function clearSelection() {
        console.log("data cleared!")
        $('#booking_id').val(null).trigger('change')
    }
</script>



@endsection