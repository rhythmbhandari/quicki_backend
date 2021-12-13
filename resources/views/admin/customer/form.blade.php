@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<div class="row" data-sticky-container>
    <div class="col-lg-6 col-xl-8">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="form-group">
                    <label>Vendor
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('vendor_id') is-invalid @enderror" name="vendor_id" id="vendor" required>
                        <option></option>
                        {{-- @isset($vendors)
                        @foreach($vendors as $vendor)
                        <option value="{{$vendor->id}}" {{ isset($getdata) ? ($getdata->vendor_id == $vendor->id)? 'selected': '' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                        @endisset --}}
                        @isset($vendors)
                        <option value="{{$vendors->id}}" selected>{{ $vendors->name }}</option>
                        @endisset
                    </select>
                    @error('vendor_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group row">
                    <div class="col">
                    <label>First Name
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="Enter First Name" name="first_name" value="@if(isset($getdata)){{$getdata->first_name}}@else{{old('first_name')}}@endif" required />
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
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Enter Last Name" name="last_name" value="@if(isset($getdata)){{$getdata->last_name}}@else{{old('last_name')}}@endif" required />
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

                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" name="email" value="@if(isset($getdata)){{$getdata->email}}@else{{old('email')}}@endif" />
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Phone<span class="text-danger">*</span>
                        </label>
                        <input type="number" data-parsley-type="digits" class="form-control @error('phone') is-invalid @enderror" placeholder="9800000000" name="phone" value="@if(isset($getdata)){{$getdata->phone}}@else{{old('phone')}}@endif" />
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
                    <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Enter Username" name="username" value="@if(isset($getdata)){{$getdata->username}}@else{{old('username')}}@endif" required />
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @if(isset($getdata))
                {{-- <div class="form-group row">
                    <div class="col-md-6">
                        <label>Old Password</label>
                        <span class="text-danger">*</span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="old-password"  placeholder="Old Password" value="">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-6">
                <label>New Password</label>
                <span class="text-danger">*</span>
                <input id="password-confirm" type="password" class="form-control" name="new_password" placeholder="New Password" value="">
            </div>
        </div> --}}

        @else
        <div class="form-group row">
            <div class="col-md-6">
                <label>Password</label>
                <span class="text-danger">*</span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password" value="@if(isset($getdata)){{$getdata->password}}@else{{old('password')}}@endif">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-md-6">
                <label>Confirm Password</label>
                <span class="text-danger">*</span>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" value="@if(isset($getdata)){{$getdata->password}}@else{{old('password_confirmation')}}@endif">
            </div>
        </div>
        @endif

        <div class="form-group">
                <label >Roles</label>
                            <select class="form-control kt_select2" id="payment_as_per" name="roles[]" multiple>
                                <option>Assign Roles</option>
                                @foreach ($roles as $role)
                                <option value="{{$role->id}}"  @isset($assignedRoles) {{ in_array($role->id, $assignedRoles) ? "selected" : '' }} @endisset>{{$role->name}}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </label>
            </div>


    </div>
</div>
</div>
<div class="col-lg-4 col-xl-3">
    <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023" data-sticky-class="stickyjs">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-6 col-form-label">Status</label>
                <div class="col-6">
                    <span class="switch switch-outline switch-icon switch-success">
                        <label>
                            <input type="checkbox" name="status" checked {{ old('status', isset($getdata->status) ? $getdata->status : '')=='active' ? 'checked':'' }} {{ (old('status') ==  'on') ?  'checked':'' }} />
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

                    <div class="image-input image-input-empty image-input-outline" id="kt_image_1" style="background-image: url({{asset('assets/admin/media/users/blank.png')}}">
                        <div class="image-input-wrapper" @if(isset($getdata->photo)) style="background-image: url({{ asset($getdata->thumbnail_path) }})" @endif></div>
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change image">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" name="photo" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="image_remove" />
                        </label>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel image">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove image">
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
<script src="{{asset('assets/admin/plugins/custom/tinymce/tinymce.bundle.js')}}"></script>
<script src="{{asset('assets/admin/plugins/custom/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/crud/file-upload/image-input.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/features/miscellaneous/sticky-panels.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('.custom-validation').parsley();

    tinymce.init({
        selector: '#kt_docs_tinymce_basic'
    });

    $("#vendor").select2({
        placeholder: 'Select Vendor',
        ajax: {
            'url': '{{route('admin.vendor.ajax')}}',
            'dataType': 'json'
        }
    });

    $(".kt_select2").select2({
        placeholder: 'Assign Roles'
    });
</script>
@endsection