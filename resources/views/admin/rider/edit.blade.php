@extends('layouts.admin.app')

@section('title', 'Edit Rider')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.rider.index')}}" class="text-muted">Riders</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Edit</a>
    </li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.rider.update',$user['id'])}}" method="post" class="custom-validation"
    enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('admin.rider.form')
</form>
@endsection