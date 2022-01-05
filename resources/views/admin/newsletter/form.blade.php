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
                
                @if (isset($newsletter->code))
                <div class="form-group row">
                    <div class="col mt-5">
                        <label class="font-weight-bold text-muted">Code:</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text"
                            id="milage-input" name="title" disabled
                            value="{{$newsletter->code}}"
                            autocomplete="off" />
                    </div>
                </div>
                @endif

                <div class="form-group row">
                    <div class="col mt-5">
                        <label  class="font-weight-bold text-muted">Title:<span class="text-danger">*</span></label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text" min="1"
                            id="milage-input" name="title" placeholder="Enter title of the newsletter"
                            value="{{old('title',isset($newsletter->title) ? $newsletter->title : '')}}" required
                            autocomplete="off" />
                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


                <!-- Body Content CKEDITOR: begin -->
                <div class="form-group row">
                    <div class="col mt-5">
                    <label for="size" class="font-weight-bold col-form-label text-muted ">Body: </label>
                    <div class="col-12">
                        <textarea class="form-control tox-target" style="height:500px;" id="body" placeholder="Body" 
                        name="body">{{ old('body', isset($newsletter->body) ? $newsletter->body : '') }}</textarea>
                        @error('body')<span class="text-danger"><i 
                            class="ki ki-outline-info text-danger ml-2  mr-2"></i>{{ $message }}</span>@enderror 
                    </div>
                    </div>
                </div>
                <!--  Body Content CKEDITOR: ends -->


            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">
                {{-- <div class="form-group row">
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
                </div> --}}

                <div class="form-group row">
                    <label class="col-xl-12 col-lg-12 col-form-label text-left font-weight-bold text-muted">Cover Image</label>
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="col-lg-12 col-xl-12">

                        <div class="image-input image-input-empty image-input-outline" id="kt_image_1">
                            <div class="image-input-wrapper" @if(isset($newsletter->image))
                                style="background-image: url({{asset($newsletter->image_path) }})"
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
<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    /*********** CKEDITOR:begins ****************/
    initializeCKEditor();
    function initializeCKEditor(){
        // ClassicEditor
        // .create( document.querySelector( '#descriptionck' ) )
        // .catch( error => {
        //     console.error( error );
        // } );
        CKEDITOR.replace('body', {
            height:'200px',
            filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    }
    /*********** CKEDITOR:ends ****************/
</script>
@endsection