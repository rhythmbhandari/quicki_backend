@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<?php
//status decalred here--
$status = ['pending', 'accepted', 'running', 'completed', 'cancelled'];
?>




<div class="row" data-sticky-container>
    <div class="col-lg-6 col-xl-8">
        @error('status')
        <div class="alert alert-danger" role="alert">
            {{$message;}}
        </div>
        @enderror

        <div class="card card-custom gutter-b example example-compact">

            <div class="card-body">
                <div class="form-group row">
                    <div class="col mt-5" id="rider_select">
                        <label>Select Rider <span class="text-danger">*</span></label>
                        <select style="width: 100%" class="form-control @error('rider_id') is-invalid @enderror"
                            name="rider_id" id="rider">
                            <option></option>
                            @if(isset($booking->rider))
                            <option value="{{$booking->rider->id}}" selected>{{$booking->rider->user->name}}</option>
                            @elseif(old('rider_id'))
                            <option value="{{old('rider_id')}}">Old value {{old('rider_id')}}</option>
                            @endif
                        </select>
                        <span class="text-danger" id="rider_selection_error"></span>
                        @error('rider_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col mt-5">
                        <label>Select Customer <span class="text-danger">*</span></label>
                        <select style="width: 100%" class="form-control @error('user_id') is-invalid @enderror"
                            name="user_id" id="customer" required>

                            @if(isset($booking->user))
                            <option value="{{$booking->user->id}}" selected>{{$booking->user->name}}</option>
                            @elseif(old('user_id'))
                            {{-- {{dd(old('user_id'))}} --}}
                            <option value="{{old('user_id')}}"> {{\App\Modules\Models\User::find(old('user_id'))->name}}
                            </option>
                            @else
                            <option></option>
                            @endif
                        </select>
                        <span class="text-danger" id="customer_selection_error"></span>
                        @error('user_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Select Vehicle Type <span class="text-danger">*</span></label>
                        <select style="width: 100%" class="form-control @error('vehicle_type_id') is-invalid @enderror"
                            name="vehicle_type_id" id="vehicle_type" required>

                            @if(isset($booking->vehicle_type))
                            <option value="{{$booking->vehicle_type->id}}" selected>{{$booking->vehicle_type->name}}
                            </option>
                            @elseif(old('vehicle_type_id'))
                            <option value="{{old('vehicle_type_id')}}">
                                {{\App\Modules\Models\VehicleType::find(old('vehicle_type_id'))->name}}</option>
                            @else
                            <option></option>
                            @endif
                        </select>
                        @error('vehicle_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col" id="start_time">
                        <label>Start Time: <span class="text-danger">*</span></label>
                        <input type="text" id="book_start_date"
                            class="form-control @error('start_time') is-invalid @enderror"
                            placeholder="Enter Start Time" name="start_time"
                            value="{{old('start_time', isset($booking) ? $booking->start_time : null)}}"
                            autocomplete="off" />
                        @error('start_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col" id="end_time">
                        <label>End Time: <span class="text-danger">*</span></label>
                        <input type="text" id="book_end_date"
                            class="form-control @error('end_time') is-invalid @enderror" placeholder="Enter End Time"
                            name="end_time" value="{{old('end_time', isset($booking) ? $booking->end_time : null)}}"
                            autocomplete="off" />
                        @error('end_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col">
                        <label>Origin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('start_location') is-invalid @enderror"
                            placeholder="Enter Start Location" name="start_location"
                            value="{{old('start_location', isset($booking) ? $booking->origin : '')}}" id="origin"
                            required />

                        <input type="text" class="form-control d-none" placeholder="Pick Up Location"
                            name="start_coordinate[latitude]" id="latitude_origin"
                            value="{{old('start_coordinate.latitude', isset($booking) ? $booking->location->latitude_origin : '')}}"
                            readonly />

                        <input type="text" class="form-control d-none" placeholder="Pick Up Location"
                            name="start_coordinate[longitude]" id="longitude_origin"
                            value="{{old('start_coordinate.longitude', isset($booking) ? $booking->location->longitude_origin : '')}}"
                            readonly />

                        @error('start_location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Destination <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('end_location') is-invalid @enderror"
                            placeholder="Enter End Location" name="end_location"
                            value="{{old('end_location', isset($booking) ? $booking->destination : '')}}"
                            id="destination" required />

                        <input type="text" class="form-control d-none" placeholder="Pick Up Location"
                            name="end_coordinate[latitude]" id="latitude_destination"
                            value="{{old('end_coordinate.latitude', isset($booking) ? $booking->location->latitude_destination: '')}}"
                            readonly />

                        <input type="text" class="form-control d-none" placeholder="Pick Up Location"
                            name="end_coordinate[longitude]" id="longitude_destination"
                            value="{{old('end_coordinate.longitude', isset($booking) ? $booking->location->longitude_destination : '')}}"
                            readonly />

                        @error('end_location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <input type="text" id="waypoints" name="waypoints" value="{{old('waypoints')}}" hidden>
                </div>

                <div id="wrapper">
                    <div>
                        <div id="map" style="height: 500px; width: 100%">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" id="distance" name="distance"
                        value="@if(isset($booking->distance)) {{$booking->distance}} @endif" />
                    <input type="hidden" id="duration" name="duration"
                        value="@if(isset($booking->duration)) {{$booking->duration}} @endif">
                </div>

                <div class="form-group mt-8">
                    <h3>Stoppage</h3>
                    <div id="sortable">
                        {{-- <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1
                        </li> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-6 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-6">
                        <select class="form-control" name="status" id="booking_status">
                            <!-- status variable is declare in line 7 -->
                            <!-- if old status and booking isset true slice status value present after booking status 
                                and display old status value that is selected.
                            -->
                            @if(old('status'))
                            <?php
                            if(isset($booking))
                                $status = array_slice($status, array_search($booking->status, $status));
                            foreach($status as $item) {
                                if($item == old('status')) {
                                ?>
                            <option value="{{$item}}" selected>{{ucfirst($item)}}</option>
                            <?php
                                }
                                else {?>
                            <option value="{{$item}}">{{ucfirst($item)}}</option>
                            <?php
                                }
                            }
                            ?>
                            <!-- if booking present slice value after the current status and make previus status selected -->
                            @elseif(isset($booking))
                            <option value="{{$booking->status}}" selected>{{ucfirst($booking->status)}}</option>
                            <?php
                                foreach(array_slice($status, array_search($booking->status, $status) + 1) as $item) {
                            ?>
                            <option value="{{$item}}">{{ucfirst($item)}}</option>
                            <?php
                                }
                            ?>
                            @else
                            @foreach ($status as $item)
                            <option value="{{$item}}">{{ucfirst($item)}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-6 col-form-label">Passenger Number</label>
                    <div class="col-6">
                        <input class="form-control" type="number" name="passenger_number"
                            value="{{old('passenger_number', isset($booking->passenger_number) ? $booking->passenger_number : '')}}" />
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <label class="">Estimated Duration</label>
                    <span id="estimated_duration"> 0</span>
                </div>



                <div class="d-flex justify-content-between">
                    <label class="">Estimated Price</label>
                    <span id="estimated_price"> 0</span>
                </div>

                <div class="border-bottom w-100 my-2"></div>
                <div class=" text-center my-5">
                    <small class="col-12 font-weight-lighter">Estimated price is calculated after vehicle
                        type,
                        origin,
                        and destination is selected!</small>
                </div>


                <div class="card-footer">

                    <button type="submit" class="btn btn-primary form-control" style="">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


@section('page-specific-scripts')
<script src="{{asset('assets/admin/plugins/custom/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/crud/file-upload/image-input.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/features/miscellaneous/sticky-panels.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Google Maps API KEY:begins -->
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('app.map_key') }}&libraries=places&callback=initMap&v=weekly&channel=2">
</script>

<script>
    //PARSLEY initializations
    let formData = {
        'vehicle_type_id': null,
        'origin_latitude': null,
        'origin_longitude': null,
        'distance': null,
        'duration': null

    }

    $('.custom-validation').parsley();
    $("#sortable").sortable({
        update: function(event, ui) { 
            finalStopPoints(); 
        }
    });
    $("#sortable").disableSelection();
    //parsley initialization ends

    $(".kt_select2").select2({
        placeholder: "Assign Role"
    });
    $("#booking_status").select2({
        placeholder: "Select booking status",
        minimumResultsForSearch: -1
    });

    $(document).ready(function(){
        //hide form fields according to status

        $("#booking_status").change(function(){
            
            switch($("#booking_status option").filter(":selected").val()) {
                case 'pending':
                    $('#rider_select, #start_time, #end_time').hide()
                    $('#rider_select select, #start_time input, #end_time input').prop("disabled", true)
                    break

                case 'accepted':
                    $('#rider_select').show();
                    $('#start_time, #end_time').hide();
                    $('#rider_select select').prop("disabled", false)
                    break

                case 'running':
                    $('#rider_select, #start_time').show();
                    $('#end_time').hide();
                    $('#rider_select select, #start_time input').prop("disabled", false)
                    break
                
                case 'completed':
                    $('#rider_select, #start_time, #end_time').show();
                    $('#rider_select select, #start_time input, #end_time input').prop("disabled", false)
                    break
            }
        }).change();

        //on vehicle type change update price
    });

    $('#vehicle_type').select2({
        width: 'resolve',
        placeholder: "Select Vehicle Type",
        ajax: {
            'url' : '{{route('admin.vehicle_type.ajax')}}',
            'dataType': 'json'
        }
    }).on('select2:select', function (e) {
            formData.vehicle_type_id = e.params.data.id
            updatePrice()
            //make an ajax call here if data complete
            console.log("after vehicle type select", formData);
        });

    $('#rider').select2({
        width: 'resolve',
        placeholder: "Select Rider",
        ajax: {
            'url' : '{{route('admin.rider.ajax')}}',
            'dataType': 'json'
        }
    }).on('select2:select', e => {
        console.log("selected rider ID ", e.params.data.user_id)
        console.log($('#customer').find(':selected'), " customer selected!")
        if(e.params.data.user_id == $('#customer').find(':selected').val()) {
            console.log("hlw?")
            $('#rider_selection_error').html("Rider cannot be same as customer")
            $('#rider').val(null).trigger('change')
        }
        else 
        $('#rider_selection_error').html("")

    });
    $('#customer').select2({
        width: 'resolve',
        placeholder: "Select Customer",
        ajax: {
            'url' : '{{route('admin.customer.ajax')}}',
            'dataType': 'json'
        }
    }).on('select2:select', (e) => {
        if(e.params.data.rider_id == $('#rider').find(':selected').val()) {
            $('#customer_selection_error').html("Customer cannot be same as rider")
            $('#customer').val(null).trigger('change')
        }
        else 
        $('#customer_selection_error').html("")

    });


    


    //initialize date picker
    $('#book_start_date, #book_end_date').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePickerIncrement: 15,
        locale: {
            format: 'YYYY/MM/DD H:mm:ss'
        }
    })

    /** GOOGLE MAP CODES **/
    let start = {
        lat: null,
        lng: null
    };
    let end = {
        lat: null,
        lng: null
    };

    const input = document.getElementById("origin");
    const inputdestination = document.getElementById("destination");

    const options = {
        componentRestrictions: {
            country: "np"
        },
        fields: ["address_components", "geometry", "icon", "name"],
        strictBounds: false,
    };
    const autocomplete = new google.maps.places.Autocomplete(input, options);
    const autocompletedestination = new google.maps.places.Autocomplete(inputdestination, options);
    // console.log(autocomplete);
    // end autocomplete setup
    // Show google map
    const map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: 27.6731828,
            lng: 85.406599
        },
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    // direction render and service
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
    // end direction render
    // Bind autocomplete to map
    autocomplete.bindTo("bounds", map);
    autocompletedestination.bindTo("bounds", map);

    // const infowindow = new google.maps.InfoWindow();
    // const infowindowContent = document.getElementById("infowindow-content");
    //     infowindow.setContent(infowindowContent);
    const marker = new google.maps.Marker({
        map,
        anchorPoint: new google.maps.Point(0, -29),
    });

    //origin autocomplete!
    autocomplete.addListener("place_changed", () => {
        console.log("autocomplete triggered!");
        // infowindow.close();
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        $('#latitude_origin').val(place.geometry.location.lat());
        $('#longitude_origin').val(place.geometry.location.lng());

        start = {
            lat: parseFloat($('#latitude_origin').val()),
            lng: parseFloat($('#longitude_origin').val())
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        finalStopPoints(); 
    });
    
    const markerTwo = new google.maps.Marker({
        map,
        anchorPoint: new google.maps.Point(0, -29),
    });

    //destination autocomplete!
    autocompletedestination.addListener("place_changed", () => {
        console.log("place changed!!");
        // infowindowTo.close();
        markerTwo.setVisible(false);
        const place = autocompletedestination.getPlace();
        if (!place.geometry || !place.geometry.location) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        $('#latitude_destination').val(place.geometry.location.lat())
        $('#longitude_destination').val(place.geometry.location.lng())
        end = {
            lat: parseFloat($('#latitude_destination').val()),
            lng: parseFloat($('#longitude_destination').val())
        }

        markerTwo.setPosition(place.geometry.location);
        markerTwo.setVisible(false);
        finalStopPoints();
    });

    let waypts = []


    var markers = [];
    var uniqueId = 1;
    const geocoder = new google.maps.Geocoder();

    listenClickMark();

    function listenClickMark() {
        google.maps.event.addListener(map, 'click', function(e) {
            createMarker(e.latLng);
        });
    }

    /*** Breaks the response of reverse geocoder into country, province, district, formatted and optionally city(locality), sub_locality and route!  **/
    function getFormattedAddress(response)
    {   
        
        console.log('Geocoder Response:',response);

       // var country = province = district = formatted_address = city = sub_locality = route = postal_code = '';
        var addressJSON = {
            country:"",province:"",district:"",formatted_address:"",city:"",sub_locality:"",route:"",postal_code:""
        };
        var parent = this;
        $.each(response.results, (function(index, result){
            
            //Formatted Address 
            addressJSON.formatted_address = result.formatted_address;

            //If any of country, province or district is empty or not in proper english
            if(addressJSON.province == "" || addressJSON.country == "" || addressJSON.district == "" || !parent.isEnglish(addressJSON.province) || !parent.isEnglish(addressJSON.district) || !parent.isEnglish(addressJSON.country) || addressJSON.locality == "" || addressJSON.sub_locality == "" || addressJSON.route == "" || addressJSON.postal_code == "" ||  addressJSON.route.toLowerCase().includes("unnamed road") || (addressJSON.country.toLocaleLowerCase().trim() != "nepal") )
            {
                $.each(result.address_components, function(index, component){
                    if(component.types.includes('administrative_area_level_1') && ( addressJSON.province=="" || !parent.isEnglish(addressJSON.province) )  )//&& component.types[1] == 'political' )
                        addressJSON.province = component.long_name;
                    if(component.types.includes('administrative_area_level_2') && ( addressJSON.district==""  || !parent.isEnglish(addressJSON.district) )   ) // && component.types[1] == 'political')
                        addressJSON.district = component.long_name;
                    if(component.types.includes('country') && (addressJSON.country==""  || !parent.isEnglish(addressJSON.country) )    ) // && component.types[1] == 'political' )
                        addressJSON.country = component.long_name;
                    if(component.types.includes('locality') &&  (addressJSON.city=="" || !parent.isEnglish(addressJSON.city)  )  ) 
                        addressJSON.city = component.long_name;
                    if(component.types.includes('sublocality') &&  ( addressJSON.sub_locality == ""  || !parent.isEnglish(addressJSON.sub_locality)   )   ) 
                        addressJSON.sub_locality = component.long_name;
                    if(component.types.includes('route') &&  (addressJSON.route == "" || addressJSON.route.toLowerCase().includes("unnamed road") || !parent.isEnglish(addressJSON.route)  )) 
                        addressJSON.route = component.long_name;
                    if(component.types.includes('postal_code') &&  ( addressJSON.postal_code == "" || !parent.isEnglish(addressJSON.postal_code) ) ) 
                        addressJSON.postal_code = component.long_name;
                });
            }

            //Verify Country Nepal 
            if(addressJSON.country != "" && parent.isEnglish(addressJSON.country) && addressJSON.country.toLocaleLowerCase().trim() != "nepal")
            {  
                addressJSON.country = "";
                return false;
            }

            //formatted_address = result.formatted_address;
            console.log('Generating Intermediate Formatted Address: ', addressJSON);
            //Check if the above method failed to fetch any of the above fields country, province, district or formattedAddress
            //If it did, loop through another element of the results array
            if( !addressJSON.formatted_address.toLowerCase().includes("unnamed road") && !addressJSON.formatted_address.toLowerCase().includes("+") && addressJSON.province != "" && addressJSON.district != "" && addressJSON.country != "" && parent.isEnglish(addressJSON.province) && parent.isEnglish(addressJSON.district) && parent.isEnglish(addressJSON.country) ) 
            {
                if(addressJSON.route.toLowerCase().includes("unnamed road"))
                    addressJSON.route = "";
                console.log('Generating Final Formatted Address: ', addressJSON);
                return false;
            }
        }).bind(parent)  );
        return addressJSON;
    }
  
    /*** Determine allowed characters for the address deduced by the geocoder! **/
    function isEnglish(str){
        var english = /^[A-Za-z0-9._ ]*$/;
        console.log("Check IsEnglish for "+str+", result: "+english.test(str.toLowerCase()) );
        return ((english.test(str.toLowerCase())) ?  true :  false);
    }

    function createMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: map,
        });

        marker.id = uniqueId;
        uniqueId += 1;
        var infowindow = new google.maps.InfoWindow();
        google.maps.event.addListener(marker, "click", function(e) {
            var content = 'Latitude: ' + location.lat() + '<br />Longitude: ' + location.lng();
            content += "<br /><input type = 'button' onclick = 'DeleteMarker(" + marker.id + ");' value = 'Delete' />";
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });

        marker.setVisible(false);
        markers.push(marker);
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                lat = marker.getPosition().lat();
                lng = marker.getPosition().lng();
                locationName = results[0].address_components[1] ? results[0].address_components[1].long_name : "null";
            }
        }).then((response) => {
            console.log("hlw");
            //validate if country is nepal.
            var addressJSON  = getFormattedAddress(response);
            console.log(addressJSON, "PRINTING FORMATTED RESPONSE FROM GEOCODER!");
            if(addressJSON.country.toLocaleLowerCase().trim() != "nepal")
            {
                console.log("marker out of nepal");
                //Alert user about out of bounds
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Address out of bounds!',
                    footer: 'Please select an address within Nepal!'
                });
            }
            else {
            validateWayPoint({lat: lat, lng: lng}, start, end).then(response => {
                console.log("response from waypoint validation!!!", response);
                if(response) {
                    content = "<li class='d-flex justify-content-between location_list bg-light' lat=" + lat + " lng=" + lng + " name=" + locationName + " id=location" + marker.id + "><div>" + locationName + "</br>";
                    content += "lat: " + lat + " lng: " + lng + "</br></div>";
                    content += "<div><input class='btn btn-danger mt-1' type = 'button' onclick = 'DeleteMarker(" + marker.id + ");' value = 'Delete' />" + "</br> </div>";
                    content += "</li>";

                    $('#sortable').append(content);
                    finalStopPoints();
                }
            })
            }


        }).catch(e=> console.log(e));
    }

    function DeleteMarker(id) {
        $('#location' + id).remove();
        //Find and remove the marker from the Array
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].id == id) {
                //Remove the marker from Map                  
                markers[i].setMap(null);

                //Remove the marker from array.
                markers.splice(i, 1);
                finalStopPoints();
                return;
            }
        }
    };

    function finalStopPoints(options) {
        console.log("inside finalStopPoints!!!!");
        waypts = [];
        var matches = [];
        var target;
        var searchEles = document.getElementById("sortable").children;
        let reqWaypts = [];
        for (var i = 0; i < searchEles.length; i++) {
            if (searchEles[i].tagName == 'LI') {
                lat = searchEles[i].getAttribute('lat');
                lng = searchEles[i].getAttribute('lng');
                name = searchEles[i].getAttribute('name');
                reqWaypts.push({
                    latitude: parseFloat(lat),
                    longitude: parseFloat(lng),
                    location: name
                })
                waypts.push({
                    location: {
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    }
                })
            }
        }
        console.log("Backup waypoint", reqWaypts);
        console.log("actual waypoint", waypts);

        $('#waypoints').val(JSON.stringify(reqWaypts));

        if(start.lat != null && start.lng != null &&
        end.lat != null && end.lng != null) {
            // directionsRenderer.setMap(map);
            directionsService.route({
                origin: start,
                destination: end,
                waypoints: waypts,
                optimizeWaypoints: false,
                travelMode: google.maps.TravelMode.DRIVING,
            })
            .then((response) => {
                directionsRenderer.setDirections(response);
                
                let totalDistance = 0;
                let totalDuration = 0; //stored in sec
                response.routes[0].legs.forEach(leg => {
                    console.log("printing the leg ", leg)
                    totalDistance += leg.distance.value / 1000;
                    totalDuration += leg.duration.value;
                });
                formData.distance = totalDistance
                formData.duration = totalDuration

                $("#distance").val(totalDistance)
                $("#duration").val(totalDuration)

                $("#estimated_duration").html(millisecondsToStr(totalDuration*1000))

                formData.origin_latitude = start.lat
                formData.origin_longitude = start.lng

                console.log(formData, "printing form data after direction changed!")

                updatePrice();

            })
            .catch((e) => {        
                console.log("Directions request failed due to " + e)
            });
        }
        return true;
    }

    async function validateWayPoint(latLng, start, end) {
        let wp = JSON.parse($('#waypoints').val());
        let parsedWaypoints = []; 
        console.log("testing wp", wp);
        wp.forEach(item => parsedWaypoints.push({
                    location: {
                        lat: parseFloat(item.latitude),
                        lng: parseFloat(item.longitude)
                    }
                }))
        parsedWaypoints.push({
                location: {
                        lat: parseFloat(latLng.lat),
                        lng: parseFloat(latLng.lng)
                }});
        console.log(latLng, "current latitude and longitude!!");
            // directionsRenderer.setMap(map);
        return await directionsService.route({
            origin: start,
            destination: end,
            waypoints: parsedWaypoints,
            optimizeWaypoints: false,
            travelMode: google.maps.TravelMode.DRIVING,
        })
        .then((response) => {
            return true;
        })
        .catch((e) => {        
            console.log("CUrrent waypoints!!", waypts);
            
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: "Path cannot be traced to that location!",
            });
            return false;
        });
        console.log("parsed latlong is: ", parsedWaypoints);
        
    }
    @if(old('waypoints') || isset($booking))
        start = {
            lat: parseFloat($('#latitude_origin').val()),
            lng: parseFloat($('#longitude_origin').val())
        };
        end = {
            lat: parseFloat($('#latitude_destination').val()),
            lng: parseFloat($('#longitude_destination').val())
        };

        @if(old('waypoints'))
            let waypoints = JSON.parse("{{old('waypoints', '[]')}}");
        @else
            let waypoints = @if(is_null($booking->waypoints)) [] @else @json($booking->waypoints) @endif;
        @endif

        waypoints.forEach(waypoint => {
            // console.log("testing waypoint ", waypoint);
            var temp_marker = createEditMarker(new google.maps.LatLng(waypoint.latitude, waypoint.longitude));
            content = "<li class='d-flex justify-content-between location_list bg-light' lat=" + waypoint.latitude + " lng=" + waypoint.longitude + " name=" + waypoint.location + " id=location" + temp_marker.id + "><div>" + waypoint.location + "</br>";
            content += "lat: " + waypoint.latitude + " lng: " + waypoint.longitude;
            content += "</div><div><input class='btn btn-danger mt-1' type = 'button' onclick = 'DeleteMarker(" + temp_marker.id + ");' value = 'Delete' />" + "</br> </div>";
            content += "</li>";

            $('#sortable').append(content);
        });

        function createEditMarker(location) {
            //Create a marker and placed it on the map.
            var marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            //Set unique id
            marker.id = uniqueId;
            uniqueId += 1;
            var infowindow = new google.maps.InfoWindow();

            //Attach click event handler to the marker.
            google.maps.event.addListener(marker, "click", function(e) {

                var content = 'Latitude: ' + location.lat() + '<br />Longitude: ' + location.lng();
                content += "<br /><input type = 'button' onclick = 'DeleteMarker(" + marker.id + ");' value = 'Delete' />";
                infowindow.setContent(content);
                infowindow.open(map, marker);
            });
            marker.setVisible(false);
            markers.push(marker);
            return marker;
        }

        finalStopPoints();
    @endif

    //make ajax call and get estimated price
    function updatePrice() {
        if($('#vehicle_type').val()) {
            formData.vehicle_type_id = $('#vehicle_type').val();
        }
        // console.log($('#vehicle_type').val(), "value of vehicle type that is selected probably!!")
        if(formData.vehicle_type_id != null && formData.origin_latitude != null &&
        formData.origin_longitude != null && formData.distance != null && formData.duration != null) {
            $.ajax({
                method: "GET", 
                url: "{{route('admin.booking.price')}}",
                data: formData
            }).done(function(response) {
                console.log("response from estimated price ", response.estimatedPrice.price_breakdown.total_price)
                // $("#estimated_duration").html(formData.duration)
                $("#estimated_price").html("Rs " + response.estimatedPrice.price_breakdown.total_price)
            }).fail(function() {
                console.log("Failed to get estimated price!")
            })
        }
    }

    function millisecondsToStr (milliseconds) {
        // TIP: to find current time in milliseconds, use:
        // var  current_time_milliseconds = new Date().getTime();

        function numberEnding (number) {
            return (number > 1) ? 's' : '';
        }

        var temp = Math.floor(milliseconds / 1000);
        var years = Math.floor(temp / 31536000);
        if (years) {
            return years + ' year' + numberEnding(years);
        }
        //TODO: Months! Maybe weeks? 
        var days = Math.floor((temp %= 31536000) / 86400);
        if (days) {
            return days + ' day' + numberEnding(days);
        }
        var hours = Math.floor((temp %= 86400) / 3600);
        if (hours) {
            return hours + ' hour' + numberEnding(hours);
        }
        var minutes = Math.floor((temp %= 3600) / 60);
        if (minutes) {
            return minutes + ' minute' + numberEnding(minutes);
        }
        var seconds = temp % 60;
        if (seconds) {
            return seconds + ' second' + numberEnding(seconds);
        }
        return 'less than a second'; //'just now' //or other string you like;
    }

</script>
@endsection