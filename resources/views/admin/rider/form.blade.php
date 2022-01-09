@section('page-specific-style')

<style>
    #rider_status {
        width: 100% !important;
    }
</style>
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<?php 
$statuses = ['active', 'in_active', 'blacklisted'];
?>
<div class="row" data-sticky-container>
    <div class="col-lg-6 col-xl-8">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <h5>User Details</h5>
                <div class="border-bottom w-100 my-5"></div>
                <div class="form-group row">
                    <div class="col">
                        <label>First Name
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                            placeholder="Enter First Name" name="first_name"
                            value="@if(isset($user)){{$user['first_name']}}@else{{old('first_name')}}@endif" required />
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
                            value="@if(isset($user)){{$user['last_name']}}@else{{old('last_name')}}@endif" required />
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
                            value="@if(isset($user)){{$user['email']}}@else{{old('email')}}@endif" />
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
                            name="phone" value="@if(isset($user)){{$user['phone']}}@else{{old('phone')}}@endif" />
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
                        value="@if(isset($user)){{$user['username']}}@else{{old('username')}}@endif" required />
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @if(isset($user))
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
                            value="@if(isset($user)){{$user['password']}}@else{{old('password')}}@endif">
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
                            value="@if(isset($user)){{$user['password']}}@else{{old('password_confirmation')}}@endif">
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
                                    // dd($user->location);
                                    ?>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="home[name]"
                                        placeholder="Enter Home Address" id="home_address"
                                        value="{{ old('home[name]', isset($user['location']['home']['name']) ? $user['location']['home']['name'] : '')}}"
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
                                        value="{{ old('work[name]', isset($user['location']['work']['name']) ? $user['location']['work']['name'] : '')}}"
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
                            value="{{old('home[latitude]',isset($user['location']['home']['latitude']) ? $user['location']['home']['latitude'] : '')}}"
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
                            value="{{old('home[longitude]',isset($user['location']['home']['longitude']) ? $user['location']['home']['longitude'] : '')}}"
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
                            value="{{old('work[latitude]',isset($user['location']['work']['latitude']) ? $user['location']['work']['latitude'] : '')}}"
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
                            value="{{old('work[longitude]',isset($user['location']['work']['longitude']) ? $user['location']['work']['longitude'] : '')}}"
                            id="work_longitude" required readonly autocomplete="off" />
                        @error('work.longitude')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <h5>Rider Details</h5>
                <div class="border-bottom w-100 my-5"></div>

                <div class="form-group row">
                    <div class="col">
                        <label>Experience</label>

                        <input type="number" class="form-control @error('rider[experience]') is-invalid @enderror"
                            placeholder="Enter Experience" name="rider[experience]"
                            value="@if(isset($rider['experience'])){{$rider['experience']}}@else{{old('rider[experience]')}}@endif" />
                        @error('experience')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-6">
                        <label>License Issue Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('license[issue_date]') is-invalid @enderror"
                                id="license-start" readonly="readonly" name="license[issue_date]"
                                placeholder="Start date"
                                value="{{old('license[issue_date]',isset($rider['license']['issue_date']) ? $rider['license']['issue_date'] : '')}}"
                                autocomplete="off" data-parsley-errors-container="#license-issue-errors" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="license-issue-errors"></div>
                        @error('license[issue_date]')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <div class="row" style="width: 100% !important">

                            <label>License Expire Date<span class="text-danger">*</span></label>
                            <div class="input-group date">
                                <input type="text"
                                    class="form-control @error('license[expiry_date]') is-invalid @enderror"
                                    id="license-expire" name="license[expiry_date]" readonly="readonly"
                                    placeholder="Select date"
                                    value="{{old('license[expiry_date]',isset($rider['license']['expiry_date']) ? $rider['license']['expiry_date'] : '')}}"
                                    autocomplete="off" data-parsley-errors-container="#license-expire-errors"
                                    required />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="license-expire-errors"></div>

                        @error('license[expiry_date]')
                        <div class="text-danger">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 mt-5">
                        <label>License Upload<span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error ('license_image') is-invalid @enderror"
                                name="license_image" id="customFile" value="">
                            <!-- chrome doesn't allow value of file to be preset -->
                            <label class="custom-file-label" for="customFile">Choose File</label>
                            @error('license_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        @if(isset($rider['license']))
                        <div class="card card-custom gutter-b">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">
                                        License Documents
                                    </h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <a href="{{asset($rider['license']['image_path'])}}" data-toggle="lightbox"
                                        data-gallery="example-gallery">
                                        <div class="symbol symbol-50 flex-shrink-0">
                                            <img src="{{asset($rider['license']['thumbnail_path'])}}" alt="photo">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-6 mt-5">
                        <label>License Number<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('license[document_number]') is-invalid @enderror"
                            placeholder="License number" name="license[document_number]"
                            value="@if(isset($rider['license'])){{$rider['license']['document_number']}}@else{{old('license[document_number]')}}@endif"
                            required />
                        @error('license[document_number]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <!-- Vehicle Form Starts -->
                <h5>Vehicle Details</h5>
                <div class="border-bottom w-100 my-5"></div>
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Number Plate
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror"
                            placeholder="Enter Number Plate" name="vehicle_number"
                            value="{{old('vehicle_number',isset($vehicle['vehicle_number']) ? $vehicle['vehicle_number'] : '')}}"
                            required autocomplete="off" />
                        @error('vehicle_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col mt-5">
                        <label>Vehicle Type <span class="text-danger">*</span></label>
                        <select style="width: 100%" class="form-control @error('vehicle_type_id') is-invalid @enderror"
                            name="vehicle_type_id" id="vehicle_type" required>
                            <option></option>
                            @isset($vehicle['vehicle_type'])
                            <option value="{{$vehicle['vehicle_type']['id']}}" selected>
                                {{$vehicle['vehicle_type']['name']}}
                            </option>
                            @endisset
                        </select>
                        @error('vehicle_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Make Year
                            <span class="text-danger">*</span></label>
                        <input type="text" max="{{ date(" Y") }}"
                            class="form-control  @error('make_year') is-invalid @enderror"
                            placeholder="Select Vehicle Make Year" id="vehicle_year" name="make_year"
                            value="{{old('make_year',isset($vehicle['make_year']) ? $vehicle['make_year'] : '')}}"
                            required autocomplete="off" />

                        @error('make_year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Vehicle Color
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('vehicle_color') is-invalid @enderror"
                            placeholder="Enter vehicle color" name="vehicle_color"
                            value="{{old('vehicle_color',isset($vehicle['vehicle_color']) ? $vehicle['vehicle_color'] : '4')}}"
                            required autocomplete="off" />
                        @error('vehicle_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col mt-5">
                        <label>Brand<span class="text-danger">*</span></label>
                        <input class="form-control @error('brand') is-invalid @enderror" type="text" id="milage-input"
                            name="brand" placeholder="Enter Brand of the Vehicle"
                            value="{{old('brand',isset($vehicle['brand']) ? $vehicle['brand'] : '')}}" required
                            autocomplete="off" />
                        @error('brand')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Model<span class="text-danger">*</span></label>
                        <input class="form-control @error('model') is-invalid @enderror" type="text" id="milage-input"
                            name="model" placeholder="Enter model of the Vehicle"
                            value="{{old('model',isset($vehicle['model']) ? $vehicle['model'] : '')}}" required
                            autocomplete="off" />
                        @error('model')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="border-bottom w-100 my-5"></div>
                <div class="form-group row">
                    <div class="col-6">
                        <label>Bluebook Issue Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('bluebook[issue_date]') is-invalid @enderror"
                                id="bluebook-issue" name="bluebook[issue_date]" readonly="readonly"
                                placeholder="Select date"
                                value="{{old('bluebook[issue_date]',isset($vehicle['bluebook']['issue_date']) ? $vehicle['bluebook']['issue_date'] : '')}}"
                                autocomplete="off" data-parsley-errors-container="#bluebook-issue-errors" required />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="bluebook-issue-errors"></div>
                        @error('bluebook[issue_date]')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label>Bluebook Expire Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('bluebook[expiry_date]') is-invalid @enderror"
                                id="bluebook-expire" name="bluebook[expiry_date]" readonly="readonly"
                                placeholder="Select date"
                                value="{{old('bluebook[expiry_date]',isset($vehicle['bluebook']) ? $vehicle['bluebook']['expiry_date'] :'')}}"
                                autocomplete="off" data-parsley-errors-container="#bluebook-expire-errors" required />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="bluebook-expire-errors"></div>
                        @error('bluebook[expiry_date]')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 mt-5">
                        <label>Bluebook Upload<span class="text-danger">*</span></label>

                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('bluebook_image') is-invalid @enderror"
                                name="bluebook_image" id="bluebook"
                                value="@if(isset($vehicle['bluebook'])){{$vehicle['bluebook']['thumbnail_path']}}@else {{old('bluebook_image')}} @endif">
                            <label class="custom-file-label" for="bluebook">Choose image</label>
                            @error('bluebook_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @if(isset($vehicle) && isset($vehicle['bluebook']))
                        <div class="card card-custom gutter-b">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">
                                        BlueBook Documents
                                    </h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <a href="{{asset($vehicle['bluebook']['image_path'])}}" data-toggle="lightbox"
                                        data-gallery="example-gallery">
                                        <div class="symbol symbol-50 flex-shrink-0">
                                            <img src="{{asset($vehicle['bluebook']['thumbnail_path'])}}" alt="photo">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-6 mt-5">
                        <label>Bluebook Number<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('bluebook[document_number]') is-invalid @enderror"
                            placeholder="Bluebook number" name="bluebook[document_number]"
                            value="@if(isset($vehicle['bluebook'])){{$vehicle['bluebook']['document_number']}}@else{{old('bluebook[document_number]')}}@endif"
                            required />
                        @error('bluebook[document_number]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="border-bottom w-100 my-5"></div>
                <div class="form-group row">
                    <div class="col-6">
                        <label>Insurance Issue Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('insurance[issue_date]') is-invalid @enderror"
                                id="insurance-issue" name="insurance[issue_date]" readonly="readonly"
                                placeholder="Select date"
                                value="{{old('insurance[issue_date]',isset($vehicle['insurance']['issue_date']) ? $vehicle['insurance']['issue_date'] : '')}}"
                                autocomplete="off" data-parsley-errors-container="#insurance-issue-errors" required />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="insurance-issue-errors"></div>
                        @error('insurance_issue_date')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label>Insurance Expire Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text"
                                class="form-control @error('insurance[expiry_date]') is-invalid @enderror"
                                id="insurance-expire" name="insurance[expiry_date]" readonly="readonly"
                                placeholder="Select date"
                                value="{{old('insurance[expiry_date]',isset($vehicle['insurance']) ? $vehicle['insurance']['expiry_date'] : '')}}"
                                autocomplete="off" data-parsley-errors-container="#insurance-expire-errors" required />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="insurance[expiry_date]"></div>
                        @error('insurance_expire_date')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6 mt-5">
                        <label>Insurance Upload<span class="text-danger">*</span></label>

                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('insurance_image') is-invalid @enderror"
                                name="insurance_image" id="insurance" value="">
                            <label class="custom-file-label" for="insurance">Choose image</label>
                            @error('insurance_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @if(isset($vehicle['insurance']))
                        <div class="card card-custom gutter-b">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">
                                        Insurance Documents
                                    </h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <a href="{{asset($vehicle['insurance']['image_path'])}}" data-toggle="lightbox"
                                        data-gallery="example-gallery">
                                        <div class="symbol symbol-50 flex-shrink-0">
                                            <img src="{{asset($vehicle['insurance']['thumbnail_path'])}}" alt="photo">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-6 mt-5">
                        <label>Insurance Number<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits"
                            class="form-control @error('insurance[document_number]') is-invalid @enderror"
                            placeholder="Bluebook number" name="insurance[document_number]"
                            value="@if(isset($vehicle['insurance'])){{$vehicle['insurance']['document_number']}}@else{{old('insurance[document_number]')}}@endif"
                            required />
                        @error('insurance[document_number]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


            </div>
        </div>
    </div>

    <?php
    // dd($vehicle);
    ?>
    <div class="col-lg-4 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-4 col-form-label">Status</label>
                    <div class="col-8">
                        <select name="rider[status]" id="rider_status" style="width: 100%">
                            <option value="{{old('rider[status]', isset($rider['status']) ?
                                    $rider['status']: '')}}">
                                {{ucwords(str_replace('_', '', old('rider[status]', isset($rider['status']) ?
                                $rider['status']: '')))}}
                            </option>
                            @foreach ($statuses as $status)
                            @if ($status != old('rider[status]', isset($rider['status']) ?
                            $rider['status']: ''))
                            <option value="{{$status}}">{{ucwords(str_replace('_', '', $status))}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-6 col-form-label">Approve</label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="rider[approved_at]" {{ old('rider[approved_at]',
                                    isset($rider['approved_at']) ? $rider['approved_at'] : null )==null ? '' :'checked'
                                    }} {{ (old('rider[approved_at]')=='on' ) ? 'checked' :'' }} />
                                <span></span>
                            </label>
                        </span>
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
                            <div class="image-input-wrapper" @if(isset($user['image']))
                                style="background-image:url({{asset($user['thumbnail_path']) }})" @else
                                style="background-image:url({{asset('assets/admin/media/users/blank.png') }})" @endif>
                            </div>
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
<script src="{{asset('assets/admin/js/pages/crud/forms/widgets/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/admin/plugins/custom/lightbox/lightbox.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Google Maps API KEY:begins -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ config('app.map_key') }}&libraries=places&callback=initMap&v=weekly&channel=2">
</script>
<!-- Google Maps API KEY:ends -->
<script src="{{asset('js/maps/map.js')}}" defer></script>

<script>
    $('.custom-validation').parsley();

    $('#vehicle_type').select2({
        width: 'resolve',
        placeholder: "Select Vehicle Type",
        ajax: {
            'url' : '{{route('admin.vehicle_type.ajax')}}',
            'dataType': 'json'
        }
    });

    $("#rider_status").select2({
        placeholder: "Select status",
        minimumResultsForSearch: -1,
        width: 'resolve'
    });

    $("#license-start, #license-expire ,#bluebook-issue, #bluebook-expire, #insurance-issue, #insurance-expire").datepicker({
        // startDate: new Date,
        format: 'yyyy-mm-dd'
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