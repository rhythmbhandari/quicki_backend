@extends('layouts.admin.app')

@section('title', 'Edit Vehicle Type')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.vehicle_type.index')}}" class="text-muted">Vehicle Types</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Edit</a>
    </li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.vehicle_type.update',$vehicleType->id)}}" method="post" class="custom-validation"
    enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('admin.vehicle_type.form')
</form>
@endsection