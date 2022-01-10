

 <form action="{{route('admin.promotion_voucher.notification.save',$promotion_voucher->id)}}" method="post" class="custom-validation"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')   



        <div class="card-body">
        @if(!isset($related_notification))
        <div class="row">
            <div class="col-xl-9 col-lg-9 col-sm-6 col-xs-12 ">
                <div class="form-group row mr-3">
                    <label for="" class="text-muted font-weight-bold">Recipient Type:</label>
                    <input type="text" class="form-control" disabled value="{{$promotion_voucher->user_type}}">
               </div>
               <div class="form-group row mr-3">
                    <label for="" class="text-muted font-weight-bold">Title:</label>
                    <input type="text" name="title" class="form-control"   required  value="" placeholder="Get Discount">
                </div>
              
            </div>
            <div class="col-xl-3 col-lg-3 col-sm-6  col-xs-12 ">
                {{-- <div class="image-input image-input-empty image-input-outline" id="kt_image_1">
                    <div class="image-input-wrapper" @if(isset($promotion_voucher->image))
                        style="background-image: url({{asset($promotion_voucher->image_path) }})"
                        @else
                        style="background-image: url({{asset('assets/admin/media/users/blank.png')}}"
                        @endif
                        ></div>
                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                        data-action="change" data-toggle="tooltip" title="" data-original-title="Change image">
                        <i class="fa fa-pen icon-sm text-muted"></i>
                        <input type="file" name="image" accept=".png, .jpg, .jpeg" @if(isset($promotion_voucher->image)) value="{{asset($promotion_voucher->image_path)}}" @endif />
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
                </div> --}}


                <label for="image" class="font-weight-bold text-muted ">
                    Image: 
                </label>
                @if (isset($promotion_voucher->image))
                    @if (!empty($promotion_voucher->image))
                        <input type="file" name="image" class="dropify"  accept=".png, .jpg, .jpeg .webp" 
                            data-default-file="{{ asset($promotion_voucher->thumbnail_path) }}" />
                    @else
                        <input type="file" name="image" class="dropify" accept=".png, .jpg, .jpeg .webp"  />
                    @endif
                @else
                    <input type="file" name="image" class="dropify"  accept=".png, .jpg, .jpeg .webp"  />
                @endif


            </div>

        </div>

        <div class="form-group row">
            <label for="" class="text-muted font-weight-bold">Message:</label>
            <textarea  name="message" maxlength="255"  required  style="min-height:150px;" class="form-control"    value="" placeholder="Use Promo Code {{$promotion_voucher->code}}"></textarea>
        </div>
    

        @else
        <div class="row ">
            <div class="col-lg-9 col-sm-6 col-xs-12 ">
                <div class="form-group row mr-3">
                    <label for="" class="text-muted font-weight-bold">Recipient Type:</label>
                    <input type="text" class="form-control" disabled value="{{$related_notification->recipient_type}}">
               </div>
               <div class="form-group row mr-3">
                    <label for="" class="text-muted font-weight-bold">Title:</label>
                    <input type="text" name="title" class="form-control"    required   value="{{$related_notification->title}}" placeholder="Get Discount">
                </div>
              
            </div>
            <div class="col-lg-3 col-sm-6  col-xs-12 ">
                {{-- <div class="image-input image-input-empty image-input-outline" id="kt_image_1">
                    <div class="image-input-wrapper" @if(isset($related_notification->image))
                        style="background-image: url({{asset($related_notification->image_path) }})"
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
                </div> --}}

                <label for="image" class="text-muted font-weight-bold">
                    Image: 
                </label>
                @if (isset($related_notification->image))
                    @if (!empty($related_notification->image))
                        <input type="file" name="image" class="dropify"  accept=".png, .jpg, .jpeg .webp" 
                            data-default-file="{{ asset($related_notification->thumbnail_path) }}" />
                    @else
                        <input type="file" name="image" class="dropify" accept=".png, .jpg, .jpeg .webp"  />
                    @endif
                @else
                    <input type="file" name="image" class="dropify"  accept=".png, .jpg, .jpeg .webp"  />
                @endif

            </div>

        </div>

        <div class="form-group row">
            <label for="" class="text-muted font-weight-bold">Message:</label>
            <textarea   required  name="message" maxlength="255"  style="min-height:150px;" class="form-control"     
              placeholder="Use Promo Code {{$related_notification->code}}">{{$related_notification->message}}</textarea>
        </div>
        @endif
    
        <input type="hidden" name="send_notification" id="sendNotification" value="0" /> 

    </div>
   
<hr>
<div class=" float-right mt-2">

    <button type="submit" id="btnSaveNotification" class="btn btn-primary font-weight-bold" data-promotion_voucher_id="{{$promotion_voucher->id}}" >
        <i class="fas fa-save text-light mr-2"></i>  {{ (isset($related_notification))?"Update":"Save" }} Notification
    </button>
    <button type="button"  id="btnSaveSendNotification"class="btn btn-success font-weight-bold"  data-promotion_voucher_id="{{$promotion_voucher->id}}" >
        <i class="fas fa-paper-plane text-light mr-2"></i>  {{ (isset($related_notification))?"Update":"Save" }} and Send 
    </button>
 
    <button type="button" class="btn btn-light-danger font-weight-bold text-denger" data-dismiss="modal"><i class="ki ki-close mr-2 text-danget"></i>Close</button>

</div>

 </form>

