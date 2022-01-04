<!--begin::Nav-->
<div class="navi navi-hover scroll my-4" data-scroll="true" data-height="500" data-mobile-height="300">
    
    @foreach ($events as $event)
        
    
    <!--begin::Item-->
    <a href="#" class="navi-item">
        <div class="navi-link">
            <div class="navi-icon mr-2">
                <i class="flaticon2-line-chart text-success"></i>
            </div>
            <div class="navi-text">
                <div class=" @if(!$event->read_at )) font-weight-bolder @endif @if($event->sos->status != 'closed') text-danger @endif ">{{$event->message}}</div>
                <div class="text-muted">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$event->updated_at)->diffForHumans() }}</div>
            </div>
        </div>
    </a>
    <!--end::Item-->

    @endforeach

</div>
<!--begin::Action-->
<div class="d-flex flex-center pt-7">
    <a href="{{route('admin.sos.index')}}" class="btn btn-light-primary font-weight-bold text-center">See All</a>
</div>
<!--end::Action-->