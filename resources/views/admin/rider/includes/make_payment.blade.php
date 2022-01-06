<?php
$totalCommission = getTotalCommissions($rider);
$totalPaid = getTotalPaid($rider->user);
?>
{{--
Total Commission: {{$totalCommission}}<br>

Paid Commission : {{$totalPaid}}<br>

Commission Due : {{$totalCommission - $totalPaid}}<br>

@if (($totalCommission - $totalPaid) > 0)
<a href="{{route('admin.rider.commission_clear', $rider->id)}}" class="btn btn-success">Clear Commission</a>
@endif --}}

<!--begin::Invoice-->

<div class="container">
    <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
        <div class="col-md-9">
            <!--begin::Invoice header-->
            <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-40">
                <h1 class="display-3 font-weight-boldest text-white mb-5 mb-md-0">INVOICE</h1>
                <div class="d-flex flex-column px-0 text-right">
                    <span
                        class="d-flex flex-column font-size-h5 font-weight-bold text-white align-items-center align-items-md-end">
                        <span class="mb-2">Puryaideu V2</span>
                        <span>{{Carbon\Carbon::now()->toDateString()}}</span>
                    </span>
                </div>
            </div>
            <!--end::Invoice header-->

            <div class="border-bottom w-100 my-13 opacity-15"></div>
            <!--begin::Invoice total-->
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex flex-column text-white mb-5 mb-md-0">
                        <div class="font-weight-boldest font-size-h4 mb-3 title-color">Account Details</div>
                        <div class="table-responsive">
                            <table class="table font-size-h5">
                                <tbody>
                                    <tr class="text-white">
                                        <td class="font-weight-boldest border-0 pl-0 w-50">Account Name:</td>
                                        <td class="border-0">{{$rider->user->name}}</td>
                                    </tr>
                                    <tr class="text-white">
                                        <td class="font-weight-boldest border-0 pl-0 w-50">Phone Number:</td>
                                        <td class="border-0">{{$rider->user->phone}}</td>
                                    </tr>
                                    {{-- <tr class="text-white">
                                        <td class="font-weight-boldest border-0 pl-0 w-50">Code:</td>
                                        <td class="border-0">BARC0032UK</td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table text-md-right font-weight-boldest">
                            <tbody>
                                <tr>
                                    <td
                                        class="align-middle title-color text-white font-size-lg border-0 pt-0 pl-0 w-50">
                                        Total Commission</td>
                                    <td class="align-middle text-primary font-size-h3 border-0 pt-0">
                                        {{$totalCommission}}</td>
                                </tr>
                                <tr>
                                    <td
                                        class="align-middle title-color text-white font-size-h4 border-0 py-7 pl-0 w-50">
                                        Total Paid
                                    </td>
                                    <td class="align-middle text-primary font-size-h3 border-0 py-7">{{$totalPaid}}</td>
                                </tr>
                                <tr>
                                    <td class="align-middle title-color text-white font-size-h4 border-0 pl-0 w-50">
                                        Total Due
                                    </td>
                                    <td class="align-middle text-primary font-size-h2 border-0">{{$totalCommission -
                                        $totalPaid}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Invoice total-->
            <!--begin::Invoice note-->
            <div class="d-flex flex-wrap align-items-end mt-30">
                <div>
                    <div class="font-size-h4 font-weight-boldest title-color mb-3">Remarks</div>
                    <div class="font-size-h6 font-weight-bold note-color max-w-375px">Lorem ipsum dolor sit amet,
                        consectetur magna aliqua. Ut enim ad minim veniam, quis Duis aute irure dolor in
                        reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla</div>
                </div>
                @if (($totalCommission - $totalPaid) > 0)
                <a href="{{route('admin.rider.commission_clear', $rider->id)}}"
                    class="btn btn-danger font-weight-bolder font-size-lg ml-sm-auto mt-5 px-5 py-4">Pay
                    Now</a>
                {{-- <a href="{{route('admin.rider.commission_clear', $rider->id)}}" class="btn btn-success">Clear
                    Commission</a> --}}
                @endif

            </div>
            <!--end::Invoice note-->
        </div>
    </div>
</div>
<!--end::Invoice-->