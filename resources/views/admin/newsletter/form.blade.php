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
{{-- <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    /*********** CKEDITOR:begins ***************
    initializeCKEditor();
    function initializeCKEditor(){
        CKEDITOR.replace('body', {
            height:'200px',
            filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    }
    ********** CKEDITOR:ends ****************/
</script>


<script src="https://cdn.tiny.cloud/1/nnd7pakaxqr7isf3oqefsdlew1jsidgl78umfeus6tg21ng0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>

    // var templates =[
    //         {
    //         title: 'My Template 1',
    //         description: 'This is my template1.',
    //         content: ' <header class="bg bg-info text-light text-center" style="height:200px;width:100%"> \
    //                     Header1 \
    //                     Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quidem voluptatibus earum at placeat accusantium pariatur non maxime, dolore debitis numquam? \
    //                 </header>\
    //                 <footer class="bg bg-danger text-light text-center" style="height:200px;width:100%"> \
    //                     Footer1 \
    //                     Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eum similique repudiandae minus modi, suscipit mollitia pariatur provident possimus exercitationem ad labore numquam ullam tempora! Necessitatibus voluptatem ullam optio ratione sapiente. \
    //                 </footer2> \
    //             '
    //         },
    //         {
    //             title: 'My Template 2',
    //             description: 'This is my template2.',
    //             content: ' <header class="bg bg-info text-light text-center" style="height:200px;width:100%"> \
    //                         Header2 \
    //                     </header> \
    //                     <footer class="bg bg-danger text-light text-center" style="height:200px;width:100%"> \
    //                         Footer2 \
    //                     </footer2> \
    //                 '
    //         },
    //         {
    //             title: 'My Template 3',
    //             description: 'This is my template3.',
    //             content: ' <header class="bg bg-info text-light text-center" style="height:200px;width:100%"> \
    //                             Header3 \
    //                         </header> \
    //                     <footer class="bg bg-danger text-light text-center" style="height:200px;width:100%"> \
    //                         Footer3 \
    //                     </footer2> \
    //                 '
    //         }
    //     ];

        tinymce.init({
            selector: '#body',

            image_class_list: [
            {title: 'img-responsive', value: 'img-responsive'},
            ],
            height: 500,
            setup: function (editor) {
                editor.on('init change', function () {
                    editor.save();
                });
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor ",
                "searchreplace visualblocks code  fullscreen",
                "insertdatetime template  media table contextmenu paste imagetools",
                "a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker"
            ],
            toolbar: ["insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
            "a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table" ],
            image_title: true,
            automatic_uploads: true,
            images_upload_url: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}", //'/uploads/ckeditor',
            file_picker_types: 'image',
            relative_urls : false,
            remove_script_host : false,
            convert_urls : true,
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    // reader.readAsDataURL(file);
                    // reader.onload = function () {
                    //     var id = 'blobid' + (new Date()).getTime();
                    //     var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    //     var base64 = reader.result.split(',')[1];
                    //     var blobInfo = blobCache.create(id, file, base64);
                    //     blobCache.add(blobInfo);
                    //     cb(blobInfo.blobUri(), { title: file.name });
                    // };
                    reader.readAsDataURL(file);
                    // console.log('BASE URL: ',getBase64(file));
                };
                input.click();
            },
            templates: '/assets/admin/js/newsletter/templates.json'
        });
</script>


@endsection