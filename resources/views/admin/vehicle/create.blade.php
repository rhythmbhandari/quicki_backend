@extends('layouts.admin.app')

@section('title', 'Create Vehicles')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
	<li class="breadcrumb-item text-muted">
		<a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
	</li>
	<li class="breadcrumb-item text-muted">
		<a href="{{ route('admin.vehicle.index')}}" class="text-muted">Vehicles</a>
	</li>
	<li class="breadcrumb-item text-active">
		<a href="#" class="text-active">Add</a>
	</li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.vehicle.store')}}" class="custom-validation" method="post" enctype="multipart/form-data">
	@include('admin.vehicle.form')
</form>
@endsection