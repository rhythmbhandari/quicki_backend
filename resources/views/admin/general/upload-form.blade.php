<div class="uk-modal uk-modal-card-fullscreen uploadModal" data-uk-grid-margin id="uploadModal">
    <div class="uk-modal-dialog uk-modal-dialog-blank">
        <div class="md-card uk-height-viewport">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                </div>
                <span class="md-icon material-icons uk-modal-close">&#xE5C4;</span>
                <h3 class="md-card-toolbar-heading-text">
                    Upload Image
                </h3>
            </div>
            <form method="post" enctype="multipart/form-data">


                @php $ratio = getImageRatio($request->get('type'))@endphp
                <div class="md-card-content">

                    <div class=uk-text-right">

                        <div class="uk-form-file md-btn md-btn-primary uk-margin-large-top">
                            Select
                            <input id="form-file" onchange="triggerChange(event,'{{$ratio[0]}}','{{$ratio[1]}}')" name="file" type="file">
                        </div>
                        <button type="button" onclick="rotate(-90)" class="md-btn md-btn uk-margin-large-top hidebtn"><i class="uk-icon-rotate-left"></i></button>
                        <button type="button" onclick="rotate(90)" class="md-btn md-btn uk-margin-large-top hidebtn"><i class="uk-icon-rotate-right"></i></button>
                        <button type="button" class="md-btn md-btn-success uk-margin-large-top uploadbtn">Upload</button>

                    </div>


                    <input type="hidden" name="id" id="attributeId" value="{{$request->get('attributeId')}}">
                    <!-- leftbox -->
                    <div class="uk-grid data-uk-grid-margin">
                        @if(str_contains($request->get('image_path'),'.'))
                            <div class="uk-width-medium-1-2 uk-margin-top">
                                <h3 class="uk-text-bold uk-text-center">Existing Image</h3>
                                <img style="max-height: 480px;" src="{{asset($request->get('image_path'))}}">
                            </div>
                        @endif
                        <div class="uk-width-medium-1-2 uk-margin-top">
                            <h3 class="uk-text-bold uk-text-center">New  Image</h3>
                            <div class="result" style="max-height: 480px;"></div>
                        </div>




                    </div>

                    <!-- input file -->
                    <div class="box">
                        <div class="options hide">

                        </div>
                    </div>




                </div>
            </form>


        </div>
    </div>
</div>

<script type="text/javascript">
    $('.uploadbtn').hide();
    $('.hidebtn').hide();
    $('#form-file').change(function () {
        if ($('#form-file').val() != '') {
            $('.uploadbtn').show();
            $('.hidebtn').show();
        }
        else {
            $('.uploadbtn').hide();
            $('.hidebtn').hide();
        }
    });

    $('.uploadbtn').click(function () {
        $('.uploadbtn').attr('disabled',true);
        uploadImage('{{$request->get('upload_route')}}', $('#attributeId').val());
    });
</script>
