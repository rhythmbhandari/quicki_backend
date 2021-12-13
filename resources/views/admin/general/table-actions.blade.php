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
<a href="{{$optionalMenuRoute}}"><button type="button" class="btn btn-icon-toggle" data-toggle="tooltip"
                data-placement="top" data-original-title="Manage"><i class="fas fa-paper-plane"></i></button></a>
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

@if(!empty($switch))
<button type="button" class="btn btn-primary switch_btn" style="width: 200px;" data-toggle="tooltip"
        data-placement="top" data-original-title="Switch" data-url="{{$switch}}">Switch To Vendor</button>
@endif