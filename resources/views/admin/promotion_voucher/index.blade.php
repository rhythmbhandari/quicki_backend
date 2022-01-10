@extends('layouts.admin.app')

@section('title', 'Promotion Voucher')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Promotion Voucher</a>
    </li>
</ul>
@endsection

@section('actionButton')
<a href="{{ route('admin.promotion_voucher.create') }}" class="btn btn-primary font-weight-bolder fas fa-plus">
    Add Promotion Voucher
</a>
@endsection


@section('page-specific-styles')
<link href="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/admin/plugins/custom/lightbox/lightbox.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/dropify.css')}}" rel="stylesheet" type="text/css" />
<style>
    #tableData tbody tr:hover {
        background: #cff5ff;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
  
<div class="d-flex flex-column-fluid">


    <!--begin::Container-->
    <div class="container">
        <div class="card card-custom">

            {{-- @if($errors->any())
            <div class="text-danger shadow p-2 mx-auto my-2  rounded">
                <div class="text-danger">Couldn't save/update notification because of following validation fails:</div> --}}
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        
            {{-- </div>
            @endif --}}

            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label">Promotion Voucher List</h3>
                </div>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i>Export</button>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <ul class="navi flex-column navi-hover py-2">
                                <li
                                    class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">
                                    Export Tools</li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link" id="export_print">
                                        <span class="navi-icon">
                                            <i class="la la-print"></i>
                                        </span>
                                        <span class="navi-text">Print</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link" id="export_copy">
                                        <span class="navi-icon">
                                            <i class="la la-copy"></i>
                                        </span>
                                        <span class="navi-text">Copy</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link" id="export_excel">
                                        <span class="navi-icon">
                                            <i class="la la-file-excel-o"></i>
                                        </span>
                                        <span class="navi-text">Excel</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link" id="export_csv">
                                        <span class="navi-icon">
                                            <i class="la la-file-text-o"></i>
                                        </span>
                                        <span class="navi-text">CSV</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link" id="export_pdf">
                                        <span class="navi-icon">
                                            <i class="la la-file-pdf-o"></i>
                                        </span>
                                        <span class="navi-text">PDF</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom table-checkable" id="tableData">
                    <thead>
                        <tr>
                            <th class="notexport">ID</th>
                            <th>S.No.</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Code</th>
                            <th>User Type</th>
                            <th>Type</th>
                            <th>Worth</th>
                            <th>Remaining Uses</th>
                            <th>Start Date</th> 
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>



<!-- NOTIFICATION DETAIL MODAL: begins -->
<div class="modal fade" id="modalVoucherNotification" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center text-muted" id="exampleModalLabel"><i class="flaticon-bell font-size-h2 text-danger mr-2"></i> Promotion Voucher Notification Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="550">
                  <div id="voucherNotificationSection"></div>
                <div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-light-danger font-weight-bold text-denger" data-dismiss="modal"><i class="ki ki-close mr-2 text-danget"></i>Close</button>
                <button type="button" class="btn btn-primary font-weight-bold"><i class="fas fa-save text-light mr-2"></i>  Save Notification</button>
                <button type="button" class="btn btn-success font-weight-bold"><i class="fas fa-paper-plane text-light mr-2"></i>Save and Send Notification</button>
            </div> --}}
        </div>
    </div>
</div>
<!-- NOTIFICATION DETAIL MODAL: ends -->


@endsection
@section('page-specific-scripts')
<script src="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/custom/parsleyjs/parsley.min.js') }}"></script>
<script src="{{asset('assets/admin/plugins/custom/lightbox/lightbox.js')}}"></script>
<script src="{{ asset('assets/admin/js/pages/crud/file-upload/image-input.js') }}"></script>
<script src="{{ asset('/js/libs/dropify/dropify.min.js') }}"></script>
<script>
    /******/
    (() => { // webpackBootstrap
        /******/
        "use strict";
        var __webpack_exports__ = {};
        /*!************************************************************!*\
          !*** ../demo1/src/js/pages/crud/datatables/basic/basic.js ***!
          \************************************************************/

        var KTDatatablesBasicBasic = function() {

            var initTable1 = function() {
                var table = $('#tableData');

                // begin first table
                var table1 = table.DataTable({
                    responsive: false,
                    searchDelay: 500,
                    processing: true,
                    scrollX:true,
                    serverSide: true,
                    order: [
                        [0, 'desc']
                    ],
                    stateSave: true,
                    ajax: {
                        url: "{{ route('admin.promotion_voucher.data') }}",
                    },
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(.notexport)',
                            }
                        },
                        {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(.notexport)',
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(.notexport)',
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(.notexport)',
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(.notexport)',
                            }
                        }
                    ],
                    columns: [
                        {
                            "data": "id",
                            'visible': false
                        },
                        {
                            "data": "DT_RowIndex",
                            orderable: true,
                            searchable: false
                        },
                        {
                            "data": "name"
                        },
                        {
                            "data": "image"
                        },
                        {
                            "data": "code"
                        },
                        {
                            "data": "user_type"
                        },
                        {
                            "data": "type"
                        },
                        {
                            "data": "worth"
                        },
                        {
                            "data": "remaining_uses"
                        },
                        {
                            "data": "starts_at"
                        },
                        {
                            "data": "expires_at"
                        },
                        {
                            "data": "status"
                        },
                        {
                            "data": "actions",
                            orderable: false,
                            searchable: false
                        },
                    ],
                    columnDefs: [{
                        targets: -1,
                        className: 'float-end'
                    }],

                });

                $('#export_print').on('click', function(e) {
                    e.preventDefault();
                    table1.button(0).trigger();
                });

                $('#export_copy').on('click', function(e) {
                    e.preventDefault();
                    table1.button(1).trigger();
                });

                $('#export_excel').on('click', function(e) {
                    e.preventDefault();
                    table1.button(2).trigger();
                });

                $('#export_csv').on('click', function(e) {
                    e.preventDefault();
                    table1.button(3).trigger();
                });

                $('#export_pdf').on('click', function(e) {
                    e.preventDefault();
                    table1.button(4).trigger();
                });
            };
            return {
                //main function to initiate the module
                init: function() {
                    initTable1();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesBasicBasic.init();
        });

        /******/
    })();
    //# sourceMappingURL=basic.js.map



    $(document).ready(function() {
            $('.dropify').dropify();
        });


    $('body').on('click','#btnPushVoucherNotification',function(e){
        e.preventDefault();
        
        var id =  $(this).data('id');
        var url = '{{ route('admin.promotion_voucher.notification.get', ':id' ) }}';
        url = url.replace(':id',id);

        $.ajax({
            type: 'GET',
            url: url,
            beforeSend: function() {},
            success: function(data) {
                $('#voucherNotificationSection').html(data.voucher_notification_section);
                $('.dropify').dropify();
            },
            error: function(data) {
                console.log('ERROR refreshing voucher code!')
            },
        });

        //Open a modal for displaying push notification infos

        // var recipient_type =  $(this).data('recipient_type');

        // var url = '{{ route('admin.notification.push', ':id') }}';
        // url = url.replace(":id", id);
        // console.log('URL: ',url);
      

        // var text = "All users will be notified and the notification will no longer be editable!";
        // if(recipient_type != "all");
        // text = "All "+recipient_type+"s will be notified and the notification will no longer be editable!";

        // Swal.fire({
        //     title: 'Are you sure?',
        //     text: text,
        //     // icon: 'warning',
        //     customClass: {
        //         icon: 'icon-right'
        //     },
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, push notification!'
        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         window.location.href = url;
        //     }
        // })


    });

    $('body').on('click','#btnSaveSendNotification',function(e){
        e.preventDefault();
        $('#sendNotification').val(1);
        $('#btnSaveNotification').click();

    });


</script>
@endsection