@extends('layouts.admin.app')

@section('title', 'Settings')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Settings</a>
    </li>
</ul>
@endsection





@section('page-specific-styles')

<!-- DROPIFY -->
{{-- <link href="{{ asset('resources/admin/css/libs/dropify/dropify.min.css') }}" rel="stylesheet" /> --}}
<link rel="stylesheet" href="{{ asset('css/dropify.css') }}" />

@endsection

@section('breadcrumb-title')
    Site Settings
@endsection

@section('breadcrumb-content')
    Show All Data
@endsection

@section('content')

    
    
    <div class="card">
        <div class="card-header">
            <h3 class="text-muted">Update Site Settings</h3>
        </div>
        <div class="card-body" id="settingContainerSection">
            @include('admin.setting.includes.settingTabsSection')
           
            <div class="container p-0 m-0 w-100 ">
                <div class="d-flex justify-content-center w-100 align-middle my-8">
                <div class="spinner spinner-track spinner-lg spinner-primary mx-auto " id="settingFormsSpinner"></div>
                </div>
                {{-- @include('admin.setting.includes.settingSection') --}}
                <div  id="settingFormsSection" class="mt-3 px-5"></div>
            </div>

        </div>
        {{-- <div class="card-footer" id="contentSection">

        </div> --}}
    </div>

@endsection

@section('page-specific-scripts')
<!-- DROPIFY -->
<script src="{{ asset('/js/libs/dropify/dropify.min.js') }}"></script>

<!-- SPINNERS -->
<script src="{{ asset('/js/libs/spinner/spinners.js') }}"></script>

<!-- SELECT 2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script type="text/javascript">



$(document).ready(function(){
    // $(function () {
    //     $('[data-toggle="popover"]').popover()
    // })
    setTimeout( function(){ console.log('Page Loaded!'); $('.btnSettingTab').first().click(); }, 300);
    $('#base_district').select2();
});

var selected_group = "";

$('body').on('click', '.btnSettingTab', function(e){
    e.preventDefault();
    $('.btnSettingTab').removeClass('active');
    $(this).addClass('active');
    var group = selected_group = $(this).data('group');
    console.log('Tab Clicked GROUP:'+group);
    loadSettingFormsSection(group);
    $('#base_district').select2();
    
});

function loadSettingFormsSection(group)
{
    $('#settingFormsSpinner').show();
    var url = '{{ route('admin.setting.loadSettingForms', ':group') }}'.replace(':group', group);
    $.ajax({
        type: 'get',
        url: url,
        async: false,
        beforeSend: function() {
            $('#settingFormsSpinner').show();
        },
        success: function(response) {
            $('#settingFormsSpinner').hide();
            console.log('Fetched setting info successfully!');
            $('#settingFormsSection').html(response);
            $('.dropify').dropify();
            $('[data-toggle="popover"]').popover();
        },
        error: function(data) {
            $('#settingFormsSpinner').addClass('d-none');
            console.log(' Failed to fetch setting info!');
            result = "";
        }
    });
    $('#settingFormsSpinner').hide();

}












</script>

@endsection
