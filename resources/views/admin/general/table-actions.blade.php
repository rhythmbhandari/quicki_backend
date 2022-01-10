@if(!empty($optional2MenuRoute))
@if($optional2MenuText == 'resume')
<a href="{{$optional2MenuRoute}}" target="_blank"><button type="button" class="btn btn-icon-toggle"
                data-toggle="tooltip" data-placement="top" data-original-title="Manage"><i
                        class="fas fa-file"></i></button></a>
@else
<a href="{{$optional2MenuRoute}}"><button type="button" class="btn btn-icon-toggle" data-toggle="tooltip"
                data-placement="top" data-original-title="Manage"><i class="fas fa-eye"></i></button></a>
@endif
@endif

@if(!empty($optionalMenuRoute))
@if(isset($object->notification_type) && $object->notification_type=="push_notification")
<a href="{{$optionalMenuRoute}}"><button type="button" id="btnPushNotification" class="btn btn-icon-toggle" data-toggle="tooltip"  title="Push Notification"
        data-placement="top" data-original-title="Send Notification"
        data-id="{{$object->id}}"     data-recipient_type="{{$object->recipient_type}}"
        ><i class="fas fa-paper-plane"></i></button></a>
@elseif($optionalMenuRoute=="promotion_voucher_notification")
<a href="#"><button type="button" id="btnPushVoucherNotification" class="btn btn-icon-toggle" title="Push Voucher Notification"
        data-toggle="modal" data-target="#modalVoucherNotification"
        data-placement="top" data-original-title="Push Voucher  Notification"
        data-id="{{$object->id}}"  
        ><i class="flaticon-bell"></i></button></a>
@else
<a href="{{$optionalMenuRoute}}"><button type="button" class="btn btn-icon-toggle" data-toggle="tooltip"
                data-placement="top" data-original-title="Manage"><i class="fas fa-paper-plane"></i></button></a>
@endif
@endif

@if(!empty($optionalMenuText))
@if($object->status != 'cancelled' && $object->status != 'completed')
<button type="button" class="cancel_booking btn btn-icon-toggle" model_name="#cancel{{$object->id}}" data-toggle="modal"
        data-target="#cancel" geofence_id="{{$object->geofence_id}}" booking_id="{{$object->id}}" title="Cancel"><i
                class="fas fa-times"></i></button>
@endif
@endif

@if(!empty($showRoute))
<a href="{{$showRoute}}"><button type="button" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"
                data-original-title="Manage" title="Show"><i class="far fa-eye font-12"></i></button></a>
@endif
@if(!empty($editRoute))
<a href="{{$editRoute}}">
        <button type="button" class="btn btn-icon-toggle" data-toggle="tooltip" title="Edit"><i
                        class="far fa-edit font-12"></i>
        </button>
</a>
@endif
@if(!empty($deleteRoute))
<button type="button" class="btn btn-icon-toggle item-delete" data-toggle="tooltip" data-placement="top"
        data-original-title="Delete row" data-url="{{$deleteRoute}}"><i class="fas fa-trash font-12"></i></button>
@endif


@if(!empty($optional3MenuText))
<a href="{{$optional3MenuText}}"><button type="button" class="btn btn-icon-toggle bg-success" data-toggle="tooltip"
                data-placement="top" data-original-title="Manage">names</button></a>
@endif

@if(!empty($makePayment))
<button type="button" id="makePayment" class="btn btn-success" style="width: 200px;" data-toggle="tooltip"
        data-placement="top" data-original-title="Switch">Make Payment</button>
@endif

@if(!empty($history))
<button type="button" id="showHistory" class="btn btn-warning" style="width: 200px;" data-toggle="tooltip"
        data-placement="top" data-original-title="Switch">Show History</button>
@endif

@if(!empty($mapRoute))
<a href="{{$mapRoute}}">
        <button type="button" class="btn btn-icon-toggle" data-toggle="tooltip" title="Map"><i
                        class="far fa-map-marked-alt"></i>
        </button>
</a>
@endif