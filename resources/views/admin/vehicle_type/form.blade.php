@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<div class="row" data-sticky-container>
    <div class="col-lg-6 col-xl-8">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col">
                        <label>Vehicle Type Name
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter Vehicle  Type Name" name="name"
                            value="@if(isset($vehicleType)){{$vehicleType->name}}@else{{old('name')}}@endif" required />
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Comission
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control @error('commission') is-invalid @enderror"
                            placeholder="Enter Commission" name="commission"
                            value="@if(isset($vehicleType)){{$vehicleType->commission}}@else{{old('commission')}}@endif"
                            required />
                        @error('commission')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label>Price Per KM</label>
                        <span class="text-danger">*</span>
                        <input type="number" class="form-control @error('price_km') is-invalid @enderror"
                            placeholder="Enter Price Per KM" name="price_km"
                            value="@if(isset($vehicleType)){{$vehicleType->price_km}}@else{{old('price_km')}}@endif"
                            required />
                        @error('price_km')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Price Per Minute<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('price_min') is-invalid @enderror"
                            placeholder="Enter Price Per Minute" name="price_min"
                            value="@if(isset($vehicleType)){{$vehicleType->price_min}}@else{{old('price_min')}}@endif"
                            required />
                        @error('price_min')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Base Fare<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('base_fare') is-invalid @enderror" placeholder="Enter Base Fare"
                            name="base_fare"
                            value="@if(isset($vehicleType)){{$vehicleType->base_fare}}@else{{old('base_fare')}}@endif"
                            required />
                        @error('base_fare')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
                    <label class="col-6 col-form-label">Status</label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="status" checked {{ old('status',
                                    isset($vehicleType->status) ?
                                $vehicleType->status : '')=='active' ? 'checked':'' }} {{ (old('status') == 'on') ?
                                'checked':'' }} />
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-12 col-lg-12 col-form-label text-left">Vehicle Type Image</label>
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="col-lg-12 col-xl-12">

                        <div class="image-input image-input-empty image-input-outline" id="kt_image_1">
                            <div class="image-input-wrapper" @if(isset($vehicleType->image))
                                style="background-image:url({{asset($vehicleType->image_path) }})"
                                @else
                                style="background-image:url({{asset('assets/admin/media/misc/noimage.png') }})"
                                @endif></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="change" data-toggle="tooltip" title="" data-original-title="Change image">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="image_remove" />
                            </label>
                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="cancel" data-toggle="tooltip" title="Cancel image">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                data-action="remove" data-toggle="tooltip" title="Remove image">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12">
                        <label>Price Surge<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('surge_rate') is-invalid @enderror"
                            placeholder="Enter Price Surge" name="surge_rate"
                            value="@if(isset($vehicleType)){{$vehicleType->surge_rate}}@else{{old('surge_rate')}}@endif"
                            required />
                        @error('surge_rate')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 mt-3">
                        <label>Capacity<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('capacity') is-invalid @enderror" placeholder="Enter Capacity"
                            name="capacity"
                            value="@if(isset($vehicleType)){{$vehicleType->capacity}}@else{{old('capacity')}}@endif"
                            required />
                        @error('capacity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


@section('page-specific-scripts')
<script src="{{asset('assets/admin/plugins/custom/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/crud/file-upload/image-input.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/features/miscellaneous/sticky-panels.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Google Maps API KEY:begins -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ config('app.map_key') }}&libraries=places&callback=initMap&v=weekly&channel=2">
</script>
<!-- Google Maps API KEY:ends -->
<script src="{{asset('js/maps/map.js')}}" defer></script>

<script>
    $('.custom-validation').parsley();

    $(".kt_select2").select2({
        placeholder: "Assign Role"
    });

    //initialzing dom elements into variables
    var homeMapElem = document.getElementById('homeMap');
    const homeAddressInputElem = document.getElementById("home_address");
    const homeLatElem = document.getElementById('home_latitude');
    const homeLngElem = document.getElementById('home_longitude');

    var workMapElem = document.getElementById('workMap');
    const workAddressInputElem = document.getElementById("work_address");
    const workLatElem = document.getElementById('work_latitude');
    const workLngElem = document.getElementById('work_longitude');

    var defaultMapLocation = {lat: 27.683772,lng: 85.309353};
    var defaultMapOptions = {center:defaultMapLocation, zoom:16};
    
    var workMap, homeMap

    window.onload = (event) => {

        try{
            homeMap = new Map(homeMapElem, defaultMapOptions );
            workMap = new Map(workMapElem, defaultMapOptions);
        }
        catch(e) {
            console.log(e, "from init map");
        }


        homeMap.initializeElements(homeLatElem, homeLngElem);
        homeMap.addAutoCompleteListener(homeAddressInputElem);
        homeMap.addMarkerDragListener();
        homeMap.marker.setDraggable(true);

        workMap.initializeElements(workLatElem, workLngElem);
        workMap.addAutoCompleteListener(workAddressInputElem);
        workMap.addMarkerDragListener();
        workMap.marker.setDraggable(true);

    }
</script>
@endsection