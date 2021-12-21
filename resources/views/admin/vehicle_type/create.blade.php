@extends('layouts.admin.app')

@section('title', 'Add Vehicle Type')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
	<li class="breadcrumb-item text-muted">
		<a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
	</li>
	<li class="breadcrumb-item text-muted">
		<a href="{{ route('admin.vehicle_type.index')}}" class="text-muted">Vehicle Types</a>
	</li>
	<li class="breadcrumb-item text-active">
		<a href="#" class="text-active">Add</a>
	</li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.vehicle_type.store')}}" class="custom-validation" method="post"
	enctype="multipart/form-data">
	@csrf
	@include('admin.vehicle_type.form')
</form>
@endsection