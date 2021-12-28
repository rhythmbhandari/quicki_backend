@extends('layouts.admin.app')

{{-- {{dd($rider)}} --}}

@section('title', 'Rider Commissions')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Rider Commissions</a>
    </li>
</ul>
@endsection

@section('page-specific-styles')
<link href="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/admin/plugins/custom/lightbox/lightbox.css')}}" rel="stylesheet" type="text/css" />
<style>
    #tableData tbody tr:hover {
        background: #cff5ff;
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<!--begin::Container-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label">Rider Commissions</h3>
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
                            <th>SN</th>
                            <th>Image</th>
                            <th>Rider Name</th>
                            <th>Total Commission(NPR.)</th>
                            <th>Paid(NPR.)</th>
                            {{-- <th>Dues(NPR.)</th> --}}
                            <th>Actions</th>
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


<!-- Modal-->
<div class="modal fade" id="makePaymentForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <!-- Modal Dialog:begins -->
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="modalDialog">
        <!-- Modal Content:begins -->
        <div class="modal-content" id="modalContent">
            <!--- SPINNER : Begins -->
            <div class="overlay-layer rounded bg-primary-o-20 d-none">
                <div class="spinner spinner-track spinner-success spinner-lg"></div>
            </div>
            <!--- SPINNER : Ends -->
            <!-- Modal Header:ends -->
            <div class="modal-header">
                <h5 class="modal-title " id="modalLabel">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <!-- Modal Header:ends -->
            <!-- Modal Body:begins -->
            <div class="modal-body" id="modalBody">

                <!--- SPINNER : Begins -->
                {{-- <div class="overlay-layer rounded bg-primary-o-20 d-none">
                    <div class="spinner spinner-track spinner-success spinner-lg"></div>
                </div> --}}
                <!--- SPINNER : Ends -->

                <div id="showModalContent">
                    Test content
                </div>


            </div>
            <!-- Modal Body:ends -->

            <!-- Modal Footer:ends -->
            <div class="modal-footer" id="modalFooter">
                <button type="button" class="btn btn-light-primary font-weight-bold" id="closeModal"
                    data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary font-weight-bold">Save changes</button> --}}
            </div>
            <!-- Modal Footer:ends -->
        </div>
        <!-- Modal Content:ends -->
    </div>
    <!-- Modal Dialog:ends -->
</div>
<!-- Modal: ends -->



@endsection

@section('page-specific-scripts')

<script src="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('assets/admin/plugins/custom/lightbox/lightbox.js')}}"></script>
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
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    order: [
                        [0, 'desc']
                    ],
                    stateSave: true,
                    ajax: {
                        url: "{{ route('admin.rider.commission.data') }}",
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
                            searchable: true
                        },
                        {
                            "data": "image",
                            orderable: true,
                            searchable: true
                        },
                        {
                            "data": "name",
                            orderable: true,
                            searchable: true
                        },
                        {
                            "data": "total_commissions",
                            orderable: true,
                            searchable: true
                        },
                        {
                            "data": "total_paid",
                            orderable: true,
                            searchable: true
                        },
                        // {
                        //     "data": null,
                        //     "render": function(data, type, row) {
                        //         return row.total_commissions - row.total_paid;
                        //     }
                        // },
                        {
                            "data": "actions",
                            orderable: true,
                            searchable: true
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


    /** Make payment modal show **/
    /******* Make Payment Modal Content: begins *****/
    $('body').on('click', '#makePayment', function(event) {
            console.log("Hlw")
            event.preventDefault();
            // removePaymentValidationErrors();
            var id = $(this).data('id');
            console.log("hellow world wooo!", id);
            // console.log(pid);
            var url = '{{ route('admin.rider.make_payment_modal', ':id') }}';
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                before: function() {
                    $('#test').addClass('show');
                    $('#test').style.display = "block";
                    console.log("hlw?")
                },
                success: function(data) {
                    console.log("hlw friend")
                    // console.log($('#modal').classList)
                    // console.log("successfully fetched modal form content ", data);
                    // $('#modalBody').removeClass('overlay overlay-block rounded  overlay-wrapper');
                    // $('#modalBody').find(".overlay-layer").addClass('d-none');

                    // $('#modalLabel').html(' <h2 class="text-info"> Make Payment </h2> ');

                    // //All the Details goes here!
                    // //console.log('Make Payment',data);

                    $('#showModalContent').html(data.content);
                },
                error: function(data) {
                    $('#modalBody').removeClass('overlay overlay-block rounded  overlay-wrapper');
                    $('#modalBody').find(".overlay-layer").addClass('d-none');
                }
            });
        });
        /******* Make Payment  Modal Content: ends *****/
</script>

@endsection