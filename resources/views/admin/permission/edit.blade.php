@extends('layouts.admin.app')

@section('title', 'Edit Permission')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.permission.index')}}" class="text-muted">Permissions</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Edit</a>
    </li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.permission.update',$permission->id)}}" method="post" class="custom-validation"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.permission.form')
</form>
@endsection