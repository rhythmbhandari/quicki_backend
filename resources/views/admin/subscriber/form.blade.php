@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@csrf
{{-- @if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif --}}
<div class="row" data-sticky-container="">
    <div class="col-lg-9 col-xl-9">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                
                <div class="form-group row">
                    <div class="col mt-5">
                        <label>Email<span class="text-danger">*</span></label>
                        <input class="form-control @error('email') is-invalid @enderror" type="text" min="1"
                            id="milage-input" name="email" placeholder="Enter email of the newsletter"
                            value="{{old('email',isset($subscriber->email) ? $subscriber->email : '')}}" required
                            autocomplete="off" />
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">
                <div class="form-group  row">
                    <label class="font-weight-bold text-muted col-6 my-auto">Subscribed: </label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="hidden" name="subscribed" value="0" />
                                <input type="checkbox" name="subscribed" id="subscribed" 
                                    {{ old('subscribed', isset($subscriber->subscribed) ? $subscriber->subscribed : 0) == 1 ? 'checked' : '' }}
                                    data-switchery>
                                <span></span>
                            </label>
                        </span>
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
    document.getElementById("subscribed").value = "1";
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