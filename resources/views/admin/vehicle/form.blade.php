@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@csrf
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="row" data-sticky-container="">
    <div class="col-lg-9 col-xl-9">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Number Plate
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror"
                            placeholder="Enter Number Plate" name="vehicle_number"
                            value="{{old('vehicle_number',isset($vehicle->vehicle_number) ? $vehicle->vehicle_number : '')}}"
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
                            @isset($vehicle->vehicle_type)
                            <option value="{{$vehicle->vehicle_type->id}}" selected>{{$vehicle->vehicle_type->name}}
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
                        <label>Vehicle Rider <span class="text-danger">*</span></label>
                        <select style="width: 100%" class="form-control @error('rider_id') is-invalid @enderror"
                            name="rider_id" id="rider" required>
                            <option></option>
                            @isset($rider)
                            <option value="{{$rider->id}}" selected>{{$rider->user->name}}</option>
                            @endisset
                        </select>
                        @error('rider_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="col mt-5">
                        <label>Make Year
                            <span class="text-danger">*</span></label>
                        <input type="text" max="{{ date(" Y") }}"
                            class="form-control  @error('make_year') is-invalid @enderror"
                            placeholder="Select Vehicle Make Year" id="vehicle_year" name="make_year"
                            value="{{old('make_year',isset($vehicle->make_year) ? $vehicle->make_year : '')}}" required
                            autocomplete="off" />

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
                        <input type="text" min="1" class="form-control @error('vehicle_color') is-invalid @enderror"
                            max="50" placeholder="Enter vehicle color" name="vehicle_color"
                            value="{{old('vehicle_color',isset($vehicle->vehicle_color) ? $vehicle->vehicle_color : '4')}}"
                            required autocomplete="off" />
                        @error('vehicle_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col mt-5">
                        <label>Brand<span class="text-danger">*</span></label>
                        <input class="form-control @error('brand') is-invalid @enderror" type="text" min="1"
                            id="milage-input" name="brand" placeholder="Enter Brand of the Vehicle"
                            value="{{old('brand',isset($vehicle->brand) ? $vehicle->brand : '')}}" required
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
                        <input class="form-control @error('model') is-invalid @enderror" type="text" min="1"
                            id="milage-input" name="model" placeholder="Enter model of the Vehicle"
                            value="{{old('model',isset($vehicle->model) ? $vehicle->model : '')}}" required
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
                        <label>Insurance Issue Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('insurance_issue_date') is-invalid @enderror"
                                id="insurance-start" readonly="readonly" name="insurance_issue_date"
                                placeholder="Start date"
                                value="{{old('insurance_issue_date',isset($vehicle->insurance_issue_date) && isset($vehicle->insurance_issue_date) ? $vehicle->insurance_issue_date : '')}}"
                                autocomplete="off" data-parsley-errors-container="#insurance-issue-errors">
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
                        <div class="row" style="width: 100% !important">

                            <label>Insurance Expire Date<span class="text-danger">*</span></label>
                            <div class="input-group date">
                                <input type="text"
                                    class="form-control @error('insurance_expiry_date') is-invalid @enderror"
                                    id="insurance-expire" name="insurance_expiry_date" readonly="readonly"
                                    placeholder="Select date"
                                    value="{{old('insurance_expiry_date',isset($vehicle->insurance_expiry_date) ? $vehicle->insurance_expiry_date : '')}}"
                                    autocomplete="off" data-parsley-errors-container="#insurance-expire-errors" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="insurance-expire-errors"></div>

                        @error('insurance_expiry_date')
                        <div class="text-danger">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mt-5">
                        <label>Insurance Upload<span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error ('insurance_file') is-invalid @enderror"
                                name="insurance_file" id="customFile"
                                value="@if(isset($vehicle) && isset($vehicle->insurance_file)){{$vehicle->insurance_file}} @else{{old('insurance_file')}}@endif">
                            <label class="custom-file-label" for="customFile">Choose File</label>
                            @error('insurance_file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        @if(isset($vehicle) && isset($vehicle->insurance_file) && $vehicle->insurance_file !=
                        "noimage.png")
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
                                    <a href="{{asset($vehicle->insurance_path)}}" data-toggle="lightbox"
                                        data-gallery="example-gallery">
                                        <div class="symbol symbol-50 flex-shrink-0">
                                            <img src="{{asset($vehicle->insurance_path)}}" alt="photo">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="border-bottom w-100 my-5"></div>
                <div class="form-group row">
                    <div class="col-6">
                        <label>Bluebook Issue Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('bluebook_issue_date') is-invalid @enderror"
                                id="bluebook-issue" name="bluebook_issue_date" readonly="readonly"
                                placeholder="Select date"
                                value="{{old('bluebook_issue_date',isset($vehicle->bluebook_issue_date) ? $vehicle->bluebook_issue_date : '')}}"
                                autocomplete="off" data-parsley-errors-container="#bluebook-issue-errors" />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="bluebook-issue-errors"></div>
                        @error('bluebook_issue_date')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label>Bluebook Expire Date<span class="text-danger">*</span></label>
                        <div class="input-group date">
                            <input type="text" class="form-control @error('bluebook_expiry_date') is-invalid @enderror"
                                id="bluebook-expire" name="bluebook_expiry_date" readonly="readonly"
                                placeholder="Select date"
                                value="{{old('bluebook_expiry_date',isset($vehicle->bluebook_expiry_date) ? $vehicle->bluebook_expiry_date :'')}}"
                                autocomplete="off" data-parsley-errors-container="#bluebook-expire-errors" />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <div id="bluebook-expire-errors"></div>
                        @error('bluebook_expire_date')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mt-5">
                        <label>Bluebook Upload<span class="text-danger">*</span></label>

                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('bluebook_file') is-invalid @enderror"
                                name="bluebook_file" id="bluebook"
                                value="@if(isset($vehicle) && $vehicle['bluebook_file']){{$vehicle->bluebook_file}}@else {{old('bluebook_file')}} @endif">
                            <label class="custom-file-label" for="bluebook">Choose file</label>
                            @error('bluebook_file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @if(isset($vehicle) && isset($vehicle->bluebook_file) && $vehicle->bluebook_file!=
                        "noimage.png")
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
                                    <a href="{{asset($vehicle->bluebook_path)}}" data-toggle="lightbox"
                                        data-gallery="example-gallery">
                                        <div class="symbol symbol-50 flex-shrink-0">
                                            <img src="{{asset($vehicle->bluebook_path)}}" alt="photo">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-6 col-form-label">Status</label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="status" checked="checked" {{ old('status',
                                    isset($vehicle->status) ? $vehicle->status : '')=='active' ? 'checked':'' }} {{
                                (old('status') == 'on') ? 'checked':'' }} />
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                {{-- <div class="form-group row">
                    <label class="col-6 col-form-label">Block</label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-danger">
                            <label>
                                <input type="checkbox" name="visibility" {{ old('visibility',
                                    isset($vehicle->visibility) ? $vehicle->visibility : '')=='invisible' ? 'checked':''
                                }} {{ (old('visibility') == 'on') ? 'checked':'' }}/>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div> --}}
                <div class="form-group row">
                    <label class="col-xl-12 col-lg-12 col-form-label text-left">Image</label>
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="col-lg-12 col-xl-12">

                        <div class="image-input image-input-empty image-input-outline" id="kt_image_1">
                            <div class="image-input-wrapper" @if(isset($vehicle->image))
                                style="background-image: url({{asset($vehicle->image_path) }})"
                                @else
                                style="background-image: url({{asset('assets/admin/media/users/blank.png')}}"
                                @endif
                                ></div>
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

<script>
    $('#vehicle_type').select2({
        width: 'resolve',
        placeholder: "Select Vehicle Type",
        ajax: {
            'url' : '{{route('admin.vehicle_type.ajax')}}',
            'dataType': 'json'
        }
    });

    $('#rider').select2({
        width: 'resolve',
        placeholder: "Select Rider",
        ajax: {
            'url' : '{{route('admin.rider.ajax')}}',
            'dataType': 'json'
        }
    });
</script>
@endsection