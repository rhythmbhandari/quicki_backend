@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.user.index')}}" class="text-muted">Users</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Edit</a>
    </li>
</ul>
@endsection

@section('content')
    <form action="{{route('admin.user.update',$getdata->id)}}" method="post" class="custom-validation" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        @include('admin.users.form')
    </form>
@endsection