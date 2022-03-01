

<div class="nav-links">
    <ul class="nav nav-tabs">
        @foreach($setting_groups as $setting_group)
            <li><button type="button" data-group="{{$setting_group->group}}" class="btn bg bg-transparent btnSettingTab nav-link  @if($loop->first) active @endif">{!! strtoupper(getSpacedTextAttribute($setting_group->group)) !!}</button></li>
        @endforeach
    </ul>
    <div class="d-flex w-100 pt-5 pb-3 px-auto">
        @error('value')<span class="text-danger text-center"><i
            class="ki ki-outline-info text-danger ml-2  mr-2"></i>{{ $message }}</span>@enderror
        
    </div>
   
    
</div>