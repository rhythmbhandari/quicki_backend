@section('page-specific-style')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<div class="row" data-sticky-container>
    <div class="col-lg-12 col-xl-12">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="form-group">
                    <label>Permission Name
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Enter First Name" name="name"
                        value="@if(isset($permission)){{$permission->name}}@else{{old('name')}}@endif" required />
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="col-form-label">Assign Permisssion</label>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-primary float-right" id="checkAll">Check All</button>
                        <button class="btn btn-warning float-right" id="uncheckAll">Uncheck All</button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12 col-form-label">
                        <div class="checkbox-inline row">
                            @foreach ($roles as $role)
                            <div class="col-3 mb-5">
                                <label class="checkbox checkbox-success">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{
                                        (is_array(old('roles')) && in_array($role->id, old('roles')))
                                    ? ' checked' : '' }} @isset($savedrole) {{in_array($role->id,
                                    $savedrole)? "checked": ''}} @endisset>
                                    <span></span>{{ucfirst($role->name)}}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('roles')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
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
    $('#uncheckAll').hide();
    $('#checkAll').on('click', function(e) {
        e.preventDefault();
        $(":input[type='checkbox']").prop('checked', true);
        $('#checkAll').hide();
        $('#uncheckAll').show();
    });
    $('#uncheckAll').on('click', function(e) {
        e.preventDefault();
        $(":input[type='checkbox']").prop('checked', false);
        $('#checkAll').show();
        $('#uncheckAll').hide();
    });
</script>
@endsection