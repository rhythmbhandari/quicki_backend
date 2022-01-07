@extends('layouts.admin.app')

@section('title', 'Show Newsletter')

@section('page-specific-styles')
<style>
.btnSend:hover{
    background:#B3541E !important;
    border-color:#B3541E !important;
    transition:.3s ease;
    /* box-shadow: inset 0 0 10px 5px rgba(0,0,0,0.6); */
}
</style>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.newsletter.index') }}" class="text-muted">Newsletters</a>
        </li>
        <li class="breadcrumb-item text-active">
            <a href="#" class="text-active">Show</a>
        </li>
    </ul>
@endsection

@section('content')


    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            {{-- <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label mx-auto"> <span class="text-muted"> NEWSLETTER:</span> {{$newsletter->title}} ({{$newsletter->code}})</h3>
                </div>
            </div> --}}


            <div class="row" data-sticky-container="">
                <div class="col-lg-8 col-xl-8">
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header flex-wrap py-5">
                            <div class="card-title">
                                <h3 class="card-label mx-auto"> <span class="text-muted"> NEWSLETTER:</span>
                                    {{ $newsletter->title }} ({{ $newsletter->code }})</h3>
                            </div>
                        </div>
                        <div class="card-body">

                          

                            <div class="mt-5 row bg-light rounded-lg rounded-bottom-0 p-2 mb-2 shadow-lg text-light">
                                <h3
                                    class="col-12 font-weight-bold text-center bg bg-light-danger text-dark rounded-lg py-2 rounded-bottom-0">
                                    MAIL PREVIEW:</h3>

                                <div class="card card-custom">
                                    <div class="card-body text-dark">
                                        {!! $newsletter->body !!}

                                    </div>
                                </div>

                            </div>


                           






                        </div>

                    </div>

                </div>
                <!--end::Container-->

                <div class="col-lg-4 col-xl-4">
                    <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
                        data-sticky-class="stickyjs">
                        <div class="card-body p-0">
                           
                            <div class="card-header p-0">
                                <form action="{{route('admin.newsletter.send',$newsletter->id)}}" id="formSend" method="post" class="custom-validation"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" id="btnSend" class="btnSend btn btn-dark font-weight-bold font-size-h3 btn-lg btn-block my-1">
                                        Send Mails
                                        <span class="svg-icon svg-icon-light svg-icon-3x icon-lg ">
                                            <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Communication\Sending.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M8,13.1668961 L20.4470385,11.9999863 L8,10.8330764 L8,5.77181995 C8,5.70108058 8.01501031,5.63114635 8.04403925,5.56663761 C8.15735832,5.31481744 8.45336217,5.20254012 8.70518234,5.31585919 L22.545552,11.5440255 C22.6569791,11.5941677 22.7461882,11.6833768 22.7963304,11.794804 C22.9096495,12.0466241 22.7973722,12.342628 22.545552,12.455947 L8.70518234,18.6841134 C8.64067359,18.7131423 8.57073936,18.7281526 8.5,18.7281526 C8.22385763,18.7281526 8,18.504295 8,18.2281526 L8,13.1668961 Z"
                                                        fill="#000000" />
                                                    <path
                                                        d="M4,16 L5,16 C5.55228475,16 6,16.4477153 6,17 C6,17.5522847 5.55228475,18 5,18 L4,18 C3.44771525,18 3,17.5522847 3,17 C3,16.4477153 3.44771525,16 4,16 Z M1,11 L5,11 C5.55228475,11 6,11.4477153 6,12 C6,12.5522847 5.55228475,13 5,13 L1,13 C0.44771525,13 6.76353751e-17,12.5522847 0,12 C-6.76353751e-17,11.4477153 0.44771525,11 1,11 Z M4,6 L5,6 C5.55228475,6 6,6.44771525 6,7 C6,7.55228475 5.55228475,8 5,8 L4,8 C3.44771525,8 3,7.55228475 3,7 C3,6.44771525 3.44771525,6 4,6 Z"
                                                        fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </button>
                                </form>
                            </div>

                            <div class="card-footer">
                                <div class="row bg-light rounded-lg rounded-bottom-0 p-2 my-1 shadow-lg text-dark">
                                    <h3
                                        class="col-12 font-weight-bold text-center bg bg-light-danger text-dark rounded-lg rounded-bottom-0 py-2">
                                        RECIPIENT EMAILS:</h3>
                                    @foreach ($recipient_emails as $recipient_email)
                                        <span class="col-auto border border-warning font-size-h6">
                                            {{ $recipient_email }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>





@endsection


@section('page-specific-scripts')

<script>
    // $('#btnSend').click(function(e){
    $('body').on('click','#btnSend',function(e){
        e.preventDefault();
        console.log('clicked');
        $('#btnSend').prop('disabled',true);
        $('#formSend').submit();
    })
</script>

@endsection
