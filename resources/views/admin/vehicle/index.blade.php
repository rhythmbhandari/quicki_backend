@extends('layouts.admin.app')

@section('title', 'Vehicle')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
	<li class="breadcrumb-item text-muted">
		<a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
	</li>
	<li class="breadcrumb-item text-active">
		<a href="#" class="text-active">Vehicle</a>
	</li>
</ul>
@endsection

@section('actionButton')
@can('vehicle-add')
<a href="{{ route('admin.vehicle.create') }}" class="btn btn-primary font-weight-bolder fas fa-plus">
	Add Vehicle
</a>
@endcan

@endsection

@section('page-specific-styles')
<link href="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet"
	type="text/css" />
<link href="{{asset('assets/admin/plugins/custom/lightbox/lightbox.css')}}" rel="stylesheet" type="text/css" />
<style>
	.availability-label:hover {
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
					<h3 class="card-label">Vehicle List</h3>
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
							<th class="notexport"></th>
							<th class="notexport">ID</th>
							<th>S.No.</th>
							<th class="notexport">Image</th>
							<th>Vehicle</th>
							<th>Status</th>
							<th class="notexport">Action</th>
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
<!--end::Entry-->

<!--end::Content-->
@endsection
@section('page-specific-scripts')
<script src="{{asset('assets/admin/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('assets/admin/plugins/custom/lightbox/lightbox.js')}}"></script>
<script>
	function format(d) {
		// return '<b>From</b> : '+d.origin+' <br><b>To :</b>  '+d.destination+' <br>'+
		//  '<b>Payable amount</b> : '+d.payable_amount+ '<br>'+
		//  '<b>Receivable amount</b> : '+d.receivable_amount;
		return '<table class="table table-hover"><thead><tr><th>File Type</th><th>File</th><th>Issued Date</th><th>Expiry Date</th></tr></thead><tbody><tr><td>Insurance</td><td>' + d.insurance + '</td><td>' + d.insurance_issue_date + '</td><td>' + d.insurance_expiry_date + '</td></tr><tr><td>Bluebook</td><td>' + d.bluebook + '</td><td>' + d.bluebook_issue_date + '</td><td>' + d.bluebook_expiry_date + '</td></tr></tbody>';
	}
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
					// responsive: true,
					searchDelay: 500,
					processing: true,
					serverSide: true,
					order: [
						[0, 'desc']
					],
                    pageLength: 50,
                    lengthMenu: [
                        [50, 100, -1],
                        [50, 100, "All"]
                    ],
					ajax: {
						url: "{{ route('admin.vehicle.data') }}",
					},
					buttons: [{
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
					columns: [{
							"className": 'details-control',
							"orderable": false,
							"searchable": false,
							"defaultContent": ''
						},
						{
							"data": "id",
							'visible': false
						},
						{
							"data": "DT_RowIndex",
							"name": "id",
							orderable: true,
							searchable: false
						},
						{
							"data": "image",
							"orderable": false,
							"searchable": false
						},
						{
							"data": "vehicle",
						},
						{
							"data": "status"
						}
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
					// "fnDrawCallback": function(settings) {
					// 	$('.availability-label').click(function() {
					// 		let availabilityLabel = $(this);
					// 		$.ajax({
					// 			url: "/admin/vehicle/availability/" + $(this).data('id'),
					// 			success: function(result) {
					// 				if (result === "success") {
					// 					availabilityLabel.toggleClass("label-light-success");
					// 					availabilityLabel.toggleClass("label-light-danger");
					// 					availabilityLabel.html() == "Available" ? availabilityLabel.html('Not Available') : availabilityLabel.html('Available');

					// 					toastr.success("Availability Changed Successfully");
					// 				} else {
					// 					toastr.danger("Something went wrong!!");
					// 				}
					// 			}
					// 		});
					// 	});
					// }
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

				var detailRows = [];
				$('#tableData tbody').on('click', 'tr td.details-control', function() {
					var tr = $(this).closest('tr');
					// console.log()
					var row = $('#tableData').DataTable().row(tr);
					// var row = table.row( tr );
					var idx = $.inArray(tr.attr('id'), detailRows);
					if (row.child.isShown()) {
						tr.removeClass('details');
						row.child.hide();
						// Remove from the 'open' array
						detailRows.splice(idx, 1);
					} else {
						tr.addClass('details');
						row.child(format(row.data())).show();
						// Add to the 'open' array
						if (idx === -1) {
							detailRows.push(tr.attr('id'));
						}
					}
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
</script>
@endsection