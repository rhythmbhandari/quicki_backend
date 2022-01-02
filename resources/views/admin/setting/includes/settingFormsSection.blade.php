@if(isset($settings))

{{-- @error('value')<span class="text-danger text-center"><i
    class="ki ki-outline-info text-danger ml-2  mr-2"></i>{{ $message }}</span>@enderror
 --}}



@foreach($settings as $setting)


<h4 class="text-muted  d-flex justify-content-between ">
    <span >{!! getSpacedTextAttribute($setting->key)  !!}
     <span class="font-weight-light font-size-h6"> ({{ucwords($setting->data_type)}}) </span> 
     @if($setting->required == "1") <sup class="text-danger font-size-h6 font-weight-bold pt-2">*</sup>  @endif
    </span>
<button type="button" class="btn p-0  ml-1" data-container="body" data-offset="60px 0px" data-toggle="popover" data-placement="top" 
    data-content="{{$setting->description}}">
    <i class="fas fa-info-circle icon-md  text-muted"></i>
</button>
</h4>

<form action="{{ route('admin.setting.update', $setting->id ) }}" class="settingForm mt-2" enctype="multipart/form-data" method="POST">
@csrf
@method('PATCH')

@error('value')<span class="text-danger text-center"><i
    class="ki ki-outline-info text-danger ml-2  mr-2"></i>{{ $message }}</span>@enderror
{{-- <input type="hidden" name="id" value="{{ $setting->id }}"  class=""  /> --}}

<div class="input-group mb-2">
    @if($setting->data_type == "image" || $setting->data_type == "icon")
        @if (isset($setting->value))
            @if (!empty($setting->value))
                <input type="file" name="value" class="dropify form-control"    data-type="{{$setting->data_type}}"
                    data-default-file="{{ asset($setting->image_path) }}" />
            @else
                <input type="file" name="value" class="dropify form-control"  data-type="{{$setting->data_type}}" />
            @endif
        @else
            <input type="file" name="value" class="dropify form-control"   data-type="{{$setting->data_type}}" />
        @endif
    @elseif($setting->key=="base_district")
    <label for="base_district" class="mr-5">Base District: </label>
    <select name="value" id="base_district" class="select2" data-placeholder="Select a district">
        @foreach($districts as $district)
            <option value="{{strtolower($district)}}" @if(strtolower($district) == $setting->value) selected @endif >{{strtoupper($district)}}</option>
        @endforeach
    </select>

    @else
    <input type="text" name="value" value="{{ $setting->value }}" class="form-control" data-type="{{$setting->data_type}}"  />

    @endif



    <div class=" @if(!($setting->data_type == "image" || $setting->data_type == "icon"))  input-group-append @else m-3 @endif  ">
        <button class="btn btn-success mx-auto font-weight-bold" type="submit">Update</button>
    </div>
</div>
{{-- RATIO: {{$setting->image_ratio}} --}}
@if(!empty($setting->image_ratio))
    <?php 
        $allowed_error = 0.1;
        $min_allowed_image_ratio = number_format($setting->image_ratio - $allowed_error, 2); 
        $max_allowed_image_ratio = number_format($setting->image_ratio + $allowed_error, 2); 
    ?>
    <div class="text-info">
        The  {!! getSpacedTextAttribute($setting->key)  !!} image should have a image ratio ranging from {{ $min_allowed_image_ratio}} to {{ $max_allowed_image_ratio}}.
        <br /> You can calculate image ratio by dividing the width over height of the image.
    </div>
@endif


<hr>


</form>

@endforeach


{{-- {{isset($errors)?$errors:"NO ERRORS"}} --}}
{{-- @if (!empty($errors->first('value')))
ERROR: {{ $errors->first('value') }}
@endif --}}



@endif

