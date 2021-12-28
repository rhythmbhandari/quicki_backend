@extends('layouts.admin.app')

@section('title')
Payments
@endsection

@section('page-specific-styles')

<!--begin::Page Vendors Styles(used by this page)-->
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection

@section('breadcrumb-title')
Payment
@endsection

@section('breadcrumb-content')
Payment

<span class="svg-icon svg-icon-dark-50 svg-icon-sm">
    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Angle-double-right.svg--><svg
        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
        viewBox="0 0 24 24" version="1.1">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <polygon points="0 0 24 0 24 24 0 24" />
            <path
                d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                fill="#000000" fill-rule="nonzero" />
            <path
                d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                fill="#000000" fill-rule="nonzero" opacity="0.3"
                transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
        </g>
    </svg>
    <!--end::Svg Icon-->
</span>

Show All Data
@endsection

@section('content')

<!--begin::Container-->
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <span class="svg-icon svg-icon-dark-50">
                    <!--begin::Svg Icon | path:/media/svg/icons/Layout/Layout-left-panel-2.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path
                                d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z"
                                fill="#000000" />
                            <rect fill="#000000" opacity="0.3" x="2" y="4" width="5" height="16" rx="1" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </span>
            <h3 class="card-label">
                Payments
            </h3>
        </div>
        <div class="card-toolbar">

            <!--begin::Export Dropdown-->
            <!--end::Export Dropdown-->

            <!--begin::Button-->
            <a href="{{ route('admin.payment.create') }}" class="btn btn-primary font-weight-bolder">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <circle fill="#000000" cx="9" cy="15" r="6" />
                            <path
                                d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>New Payment</a>
            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable table-striped" id="kt_datatable"
            style="margin-top: 13px !important">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>SN</th>
                    <th>Comission Amount</th>
                    <th>Payment Type</th>
                    <th>Payment Status</th>
                    <th>Comission Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->




@endsection

@section('page-specific-scripts')

<!--begin::Page Vendors(used by this page)-->
<script src="{{ asset('/plugins/custom/datatables/datatables.bundle.js') }}"></script>

<script>
    //DATATABLE
        $(document).ready(function() {

            //$('#tableData').DataTable({
            var orderDataTable = $('#kt_datatable').DataTable({
                "responsive": false,
                //"searchDelay": 500,
                "processing": true,
                "scrollX": true,
                "serverSide": true,
                "ajax": '{{ route('admin.payment.data') }}',
                "columns": [
                    {
                        orderable:      false,
                        searchable:     false,
                        "data":           'expand'
                    },
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
                        "data": "comission_amount",
                        orderable: true,
                        searchable: true
                    },
                    {
                        "data": "customer_name",
                        orderable: true,
                        searchable: true
                    },
                    {
                        "data": "rider_name",
                        orderable: true,
                        searchable: true
                    },
                    {
                        "data": "payment_type",
                        orderable: true,
                        searchable: true
                    },
                    {
                        "data": "payment_status",
                        orderable: true,
                        searchable: true
                    },
                    {
                        "data": "comission_payment_status",
                        orderable: true,
                        searchable: true
                    },
                    {
                        "data": "actions"
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                "createdRow": function(row, data, dataIndex) {
                    // console.log(data['status_text'].toLowerCase());
                    $(row).find('td').last().addClass(
                        'pl-3 pr-3 align-items-center justify-content-center ');

                }
            });

            /************ Add Child Rows to datatable: begins *****************/ 
           
            // Add event listener for opening and closing details
            $('#kt_datatable').on('click', '.details-control', function (e) {
                e.preventDefault();
                console.log('fun called');
                var tr = $(this).parents('tr');
                var row = orderDataTable.row(tr);
                console.log(row.data());
                var tableId = 'orderDetail-' + row.data().id;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    tr.addClass('shown');
                    tr.next().find('td').addClass('p-0 bg-secondary');
                }
            });
            /************ Add Child Rows to datatable: begins *****************/ 

            function initTable(tableId, data) {
                console.log('Init Table Function');
                console.log('details_url: ');
                console.log(data.details_url);
                console.log('details table ID: ');
                console.log(tableId);
            

                var orderDetailsDataTable = $('#' + tableId).DataTable({
                    "responsive": false,
                    //"searchDelay": 500,
                    "processing": true,
                    "scrollX": true,
                    "serverSide": true,
                    "ajax": data.details_url,
                    columnDefs: [{
                        targets: -1,
                        className: 'text-right'
                    }],
                    "columns": [
                        {
                            "data": "id",
                            'visible': false,
                            className:"border border-warning",
                        },
                        {
                            "data": "DT_RowIndex",
                            orderable: true,
                            searchable: true,
                            className:" border border-warning",
                        },
                        {
                            "data": "product_name",
                            orderable: true,
                            searchable: true,
                            className:" border border-warning",
                        },
                        {
                            "data": "quantity",
                            orderable: true,
                            searchable: true,
                            className:" border border-warning",
                        },
                        {
                            "data": "sub_total",
                            orderable: true,
                            searchable: true,
                            className:" border border-warning",
                        },
                        {
                            "data": "status",
                            orderable: true,
                            searchable: true,
                            className:" border border-warning",
                        },
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    "createdRow": function(row, data, dataIndex) {

                    }
                });



            }
        
            /********** Child Row Definition: begins ******************/
            function template(data)
            {   console.log('Child Row:');
                //console.log(data);
                return '<div class="card card-custom"> \
                            <div class="card-header  bg bg-dark "> \
                                <div class="card-title "> \
                                    <span class="font-weight-bold text-light "  >Showing order details for order number: <span class="text-warning font-weight-boldest">'+data.order_number+'</span></span> \
                                </div> \
                            </div> \
                            <div class="card-body " >\
                                <table class="table details-table table-bordered border-warning " id="orderDetail-'+data.id+'"> \
                                <thead class="thead-dark  mt-4  border-warning"> \
                                <tr class=" border-light"  > \
                                    <th  class="text-light  border border-warning  "     >Id</th> \
                                    <th class=" text-light border border-warning  "     >SN.</th> \
                                    <th  class=" text-light  border border-warning  "    >Product</th> \
                                    <th  class=" text-light  border border-warning  "    >Quantity</th> \
                                    <th  class=" text-light  border border-warning  "    >Sub-Total</th> \
                                    <th  class="text-light  border border-warning  "    >Status</th> \
                                </tr> \
                                </thead> \
                            </table> \
                            </div> \
                            </div>';
                        

            }
            /********** Child Row Definition: begins ******************/




        });



        /****** Delete Row:begins *****/
        $('body').on('click', '.btnDelete ', function(event) {

            var slug = $(this).data('slug');
            var id = $(this).data('id');
            var url = '{{ route('admin.order.destroy', ':slug') }}';
            url = url.replace(":slug", slug);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    //Make Delete Request via ajax call
                    deleted = deleteRow(url, id);
                    if (deleted) {
                        Swal.fire(
                            'Deleted!',
                            'Row has been deleted.',
                            'success'
                        )
                        $('.btnDelete[data-slug=' + slug + ']').parents('tr').remove();
                    } else {
                        Swal.fire('Failed!', 'Row could not be deleted.', 'error', )
                    }
                }
            })

        });
        /****** Delete Row:ends *****/

        /****** Delete Row Request:begins *****/
        function deleteRow(url, id) {
            var result = false;
            $.ajax({
                type: 'delete',
                url: url,
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                },
                beforeSend: function() {},
                success: function(data) {
                    result = data;
                },
                error: function(data) {
                    result = false;
                }
            });
            return result;
        }
        /****** Delete Row Request:ends *****/


        /******* Modal Content: begins *****/
        $('body').on('click', '#btnShowModal', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            var slug = $(this).data('slug');
            // console.log(pid);
            var url = '{{ route('admin.order.show', ':slug') }}';
            url = url.replace(':slug', slug);
            $.ajax({
                type: 'GET',
                url: url,
                before: function() {

                },
                success: function(data) {

                    console.log('show order detail success');
                    $('#modalContent').html(data);

                },
                error: function(data) {
                    console.log('Failed Ajax Request for Show Data!');
                    console.log(data);
                }
            });
        });
    

</script>

@endsection