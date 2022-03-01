@extends('layouts.admin.app')

@section('title', 'Edit Promotion Voucher')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.promotion_voucher.index')}}" class="text-muted">Promotion Vouchers</a>
    </li>
    <li class="breadcrumb-item text-active">
        <a href="#" class="text-active">Edit</a>
    </li>
</ul>
@endsection

@section('content')
<form action="{{route('admin.promotion_voucher.update',$promotion_voucher->id)}}" method="post" class="custom-validation promoForm"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.promotion_voucher.form')
</form>
@endsection