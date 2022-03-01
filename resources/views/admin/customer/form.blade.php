@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<?php 
$statuses = ['active', 'in_active', 'blacklisted'];
?>

<div class="row" data-sticky-container>
    <div class="col-lg-6 col-xl-8">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col">
                        <label>First Name
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                            placeholder="Enter First Name" name="first_name"
                            value="@if(isset($customer)){{$customer->first_name}}@else{{old('first_name')}}@endif"
                            required />
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Last Name
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                            placeholder="Enter Last Name" name="last_name"
                            value="@if(isset($customer)){{$customer->last_name}}@else{{old('last_name')}}@endif"
                            required />
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label>Email</label>

                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter Email" name="email"
                            value="@if(isset($customer)){{$customer->email}}@else{{old('email')}}@endif" required />
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Phone<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('phone') is-invalid @enderror" placeholder="9800000000"
                            name="phone" value="@if(isset($customer)){{$customer->phone}}@else{{old('phone')}}@endif"
                            required />
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label>Username
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                        placeholder="Enter Username" name="username"
                        value="@if(isset($customer)){{$customer->username}}@else{{old('username')}}@endif" required />
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @if(isset($customer))
                {{-- <div class="form-group row">
                    <div class="col-md-6">
                        <label>Old Password</label>
                        <span class="text-danger">*</span>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="old-password"
                            placeholder="Old Password" value="">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label>New Password</label>
                        <span class="text-danger">*</span>
                        <input id="password-confirm" type="password" class="form-control" name="new_password"
                            placeholder="New Password" value="">
                    </div>
                </div> --}}

                @else
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Password</label>
                        <span class="text-danger">*</span>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password" placeholder="Password"
                            value="@if(isset($customer)){{$customer->password}}@else{{old('password')}}@endif">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label>Confirm Password</label>
                        <span class="text-danger">*</span>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password" placeholder="Confirm Password"
                            value="@if(isset($customer)){{$customer->password}}@else{{old('password_confirmation')}}@endif">
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="card card-custom gutter-b">
                            <div class="card-header bg-light p-0">
                                <div class="col-12 pt-3 pb-3">
                                    <label>Home Address</label>
                                    <?php 
                                    // dd($customer->location);
                                    ?>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="home[name]"
                                        placeholder="Enter Home Address" id="home_address"
                                        value="{{ old('home[name]', isset($customer->location['home']['name']) ? $customer->location['home']['name'] : '')}}"
                                        required autocomplete="off" />
                                </div>
                            </div>
                            <div class="card-body" id="wrapper">
                                <div>
                                    <div class="bg-light" id="homeMap" style="height:50vh; width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="card card-custom gutter-b">
                            <div class="card-header bg-light p-0">
                                <div class="col-12 pt-3 pb-3">
                                    <label>Work Address</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="work[name]"
                                        placeholder="Enter Work Address" id="work_address"
                                        value="{{ old('work[name]', isset($customer->location['work']['name']) ? $customer->location['work']['name'] : '')}}"
                                        required autocomplete="off" />
                                </div>
                            </div>
                            <div class="card-body" id="wrapper">
                                <div>
                                    <div class="bg-light" id="workMap" style="height:50vh; width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- hidden coordinates of work and home locations -->
                    <div>
                        <input type="hidden" step="any"
                            class="form-control @error('home.latitude') is-invalid @enderror"
                            placeholder="Latitude Coordinates : 27.720805" name="home[latitude]"
                            value="{{old('home[latitude]',isset($customer->location['home']['latitude']) ? $customer->location['home']['latitude'] : '')}}"
                            id="home_latitude" required readonly autocomplete="off" />
                        @error('home.latitude')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <input type="hidden" step="any"
                            class="form-control @error('home.longitude') is-invalid @enderror"
                            placeholder="Longitude Coordinates : 27.720805" name="home[longitude]"
                            value="{{old('home[longitude]',isset($customer->location['home']['longitude']) ? $customer->location['home']['longitude'] : '')}}"
                            id="home_longitude" required readonly autocomplete="off" />
                        @error('home.longitude')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-3">
                        <input type="hidden" step="any"
                            class="form-control @error('work.latitude') is-invalid @enderror"
                            placeholder="Latitude Coordinates : 27.720805" name="work[latitude]"
                            value="{{old('work[latitude]',isset($customer->location['work']['latitude']) ? $customer->location['work']['latitude'] : '')}}"
                            id="work_latitude" required readonly autocomplete="off" />
                        @error('work.latitude')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-3">
                        <input type="hidden" step="any"
                            class="form-control @error('work.longitude') is-invalid @enderror"
                            placeholder="Longitude Coordinates : 27.720805" name="work[longitude]"
                            value="{{old('work[longitude]',isset($customer->location['work']['longitude']) ? $customer->location['work']['longitude'] : '')}}"
                            id="work_longitude" required readonly autocomplete="off" />
                        @error('work.longitude')
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
                    <label class="col-4 col-form-label">Status</label>
                    <div class="col-8">

                        {{-- {{dd($rider['status'])}} --}}
                        {{-- <input type="checkbox" name="rider[status]" {{ old('rider[status]', isset($rider['status'])
                            ? $rider['status'] : '' )=='active' ? 'checked' :'' }} {{ (old('rider[status]')=='on' )
                            ? 'checked' :'' }} />
                        <span></span> --}}
                        <select name="status" id="customer_status" style="width: 100%">
                            <option value="{{old('status', isset($customer->status) ?
                                    $customer->status: '')}}">
                                {{ucwords(str_replace('_', '', old('status', isset($customer->status) ?
                                $customer->status: '')))}}
                            </option>
                            @foreach ($statuses as $status)
                            @if ($status != old('status', isset($customer->status) ?
                            $customer->status: ''))
                            <option value="{{$status}}">{{ucwords(str_replace('_', '', $status))}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-12 col-lg-12 col-form-label text-left">Profile Image</label>
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="col-lg-12 col-xl-12">

                        <div class="image-input image-input-empty image-input-outline" id="kt_image_1">
                            <div class="image-input-wrapper" @if(isset($customer->image))
                                style="background-image:url({{asset($customer->image_path) }})"
                                @else
                                style="background-image:url({{asset('assets/admin/media/users/blank.png') }})"
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

    $("#customer_status").select2({
        placeholder: "Select status",
        minimumResultsForSearch: -1,
        width: 'resolve'
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