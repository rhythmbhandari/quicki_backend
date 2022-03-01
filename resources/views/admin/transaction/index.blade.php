@extends('layouts.admin.app')

@section('title', 'Transaction')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Transaction</a>
    </li>
</ul>
@endsection

{{-- @section('actionButton')
<a href="{{ route('admin.booking.create') }}" class="btn btn-primary font-weight-bolder fas fa-plus">
    Create Transaction
</a>
@endsection --}}

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
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label">Transaction List</h3>
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
                <div class="mt-4 mb-12">
                    <div class="row mb-8">
                        <div class="col-md-4">
                            <label>Filter Date:</label>
                            <div class="input-daterange input-group" id="kt_datepicker">
                                <input type='text' class="form-control" id="datefilter" readonly
                                    placeholder="Select date and time" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-8">
                        <div class="col-lg-12">
                            <button class="btn btn-secondary btn-secondary--icon" id="clear">
                                <span>
                                    <i class="la la-close"></i>
                                    <span>Clear</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom table-checkable" id="tableData">
                    <thead>
                        <tr>
                            <th class="notexport">ID</th>
                            <th>S.No.</th>
                            <th class="notexport">Transaction Date</th>
                            <th>Creditor First Name</th>
                            <th>Creditor Last Name</th>
                            <th class="notexport">Creditor</th>
                            <th class="notexport">Creditor Type</th>
                            <th>Debtor First Name</th>
                            <th>Debtor Last Name</th>
                            <th class="notexport">Debtor</th>
                            <th class="notexport">Debtor Type</th>
                            <th>Payment Mode</th>
                            <th>Amount</th>
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
                        url: "{{ route('admin.transaction.data') }}",
                        data: function(d) {
                            d.datefilter = $('#datefilter').val();
                        }
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
                            "data": "transaction_date"
                        },
                        {
                            "data": "creditor_first_name",
                            name:'creditor.first_name',
                            visible:false
                        },
                        {
                            "data": "creditor_last_name",
                            name:'creditor.last_name',
                            visible:false
                        },
                        {
                            "data": "creditor",
                            render: function(data, type, row, meta) {
                                return row.creditor_first_name + ' ' + row.creditor_last_name;

                            }
                        },
                        {
                            "data": "creditor_type"
                        },
                        {
                            "data": "debtor_first_name",
                            name:'debtor.first_name',
                            visible: false
                        },
                        {
                            "data": "debtor_last_name",
                            name:'debtor.last_name',
                            visible: false
                        },
                        {
                            "data": "debtor",
                            render: function(data, type, row, meta) {
                                return row.debtor_first_name + ' ' + row.debtor_last_name;
                            }
                        },
                        {
                            "data": "debtor_type"
                        },
                        {
                            "data": "payment_mode"
                        },
                        {
                            "data": "amount"
                        }
                        // {
                        //     "data": "actions",
                        //     orderable: false,
                        //     searchable: false
                        // },
                    ],
                    columnDefs: [{
                        targets: -1,
                        className: 'float-end'
                    }
                ],

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

                $('#datefilter').daterangepicker({
                    buttonClasses: ' btn',
                    applyClass: 'btn-primary',
                    cancelClass: 'btn-secondary',

                    timePicker: true,
                    timePickerIncrement: 30,
                    locale: {
                        format: 'YYYY/MM/DD H:mm:ss'
                    }
                }, function(start, end, label) {
                    $('#datefilter .form-control').val(start.format('MM/DD/YYYY h:mm A') + ' / ' + end.format('MM/DD/YYYY h:mm A'));
                });

                $("#datefilter").on('change', function() {
                    table1.draw();
                });

                jQuery('#datefilter').val(null);

                $('#clear').on('click', () => {
                    jQuery('#datefilter').val(null).trigger('change');
                })
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
</script>
@endsection