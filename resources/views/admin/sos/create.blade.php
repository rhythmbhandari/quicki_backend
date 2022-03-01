@extends('layouts.admin.app')

@section('title', 'SOS')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
  <li class="breadcrumb-item text-muted">
    <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
  </li>
  <li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.vendor.index')}}" class="text-muted"> SOS </a>
  </li>
  <li class="breadcrumb-item text-active">
    <a href="#" class="text-active">Create</a>
  </li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.sos.store')}}" class="custom-validation" method="post" enctype="multipart/form-data">
  @include('admin.sos.form')
</form>
@endsection