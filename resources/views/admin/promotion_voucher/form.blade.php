@section('page-specific-style')
    <link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<div class="row" data-sticky-container>
    <div class="col-lg-8 col-xl-9">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">

                <div class="row">

                    <div class="form-group col-auto row ">
                        <label class="font-weight-bold text-muted col-auto my-auto">Eligible User Type:
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-auto  my-auto">
                            @if (!isset($promotion_voucher))
                                <select name="user_type" id="user_type" class="form-control select2" @error('user_type')
                                    is-invalid @enderror>
                                    <option value="customer" @if (old('user_type') == 'customer') selected @endif>Customer</option>
                                    <option value="rider" @if (old('user_type') == 'rider') selected @endif>Rider</option>
                                </select>
                            @else
                                <input type="text" disabled class="form-control"
                                    value="{{ ucwords($promotion_voucher->user_type) }}" />
                                {{-- <span class="px-3 py-2 rounded bg bg-light-info"> {{ ucwords($promotion_voucher->user_type) }} </span> --}}
                            @endif

                            @error('user_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group  col-auto row">
                        <label class="font-weight-bold text-muted col-auto my-auto">Title:
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-auto">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter First Name" name="name" value="{{ old('name', isset($promotion_voucher->name) ? $promotion_voucher->name : '') }}" required />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group  col-auto row">
                        <label class="font-weight-bold text-muted col-auto my-auto">Type:
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-auto">
                            <input type="text" class="form-control @error('type') is-invalid @enderror"
                                placeholder="Enter Type" name="type" value="{{ old('type', isset($promotion_voucher->type) ? $promotion_voucher->type : '') }}" required />
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>


                </div>

                <hr>

                <div class="form-group  row ">
                    <label class="font-weight-bold text-muted col-2 my-auto">Voucher Code:
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-10">
                        <div class="input-group">
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                                placeholder="{{ !isset($promotion_voucher) ? $suggested_code : '' }}" name="code"
                                @if (isset($promotion_voucher)) disabled @endif value="{{ old('name', isset($promotion_voucher->code) ? $promotion_voucher->code : '') }}" required />
                            @if(!isset($promotion_voucher))
                            <div class="input-group-append">
                                <button class="btn btn-warning" id="btnRefreshVoucherCode" type="button">
                                    <i class="flaticon2-refresh text-wight font-weight-bold"></i>
                                </button>
                            </div>
                            @endif
                        </div>


                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <div class="col-6">
                        <label>Activation Date</label>
                        <div class="input-group date">
                            @if(isset($promotion_voucher))
                            <input type="text" readonly class="form-control @error('starts_at') is-invalid @enderror" id="activation-date"  name="starts_at" placeholder="Select date" value="{!! \Carbon\Carbon::createFromTimestamp($promotion_voucher->starts_at)->format('Y-m-d') !!}"/>
                            @else
                            <input type="text" class="form-control @error('starts_at') is-invalid @enderror" id="activation-date"  name="starts_at" value="{{old('starts_at')}}"  placeholder="Select date"/>
                            @endif
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                            @error('starts_at')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                        </div>
                        
                    </div>
                    <div class="col-6">
                        <label>Expire Date</label>
                        <div class="input-group date">
                            @if(isset($promotion_voucher))
                            <input type="text" readonly class="form-control @error('expires_at') is-invalid @enderror" id="expire-date" name="expires_at" placeholder="Select date" value="{!! \Carbon\Carbon::createFromTimestamp($promotion_voucher->expires_at)->format('Y-m-d') !!}" />
                            @else
                            <input type="text" class="form-control @error('expires_at') is-invalid @enderror" id="expire-date" name="expires_at" placeholder="Select date" value="{{old('expires_at')}}"/>
                            @endif
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                            @error('expires_at')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                        </div>
                    </div>
                </div>


                <hr>

                <div class="row">

                    <div class="form-group  col-auto row">
                        <label class="font-weight-bold text-muted col-auto my-auto">Max Uses:
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-auto">
                            <input type="number" class="form-control @error('max_uses') is-invalid @enderror"
                                placeholder="Enter Max Uses" name="max_uses" value="@if (isset($promotion_voucher)){{ $promotion_voucher->max_uses }}@else{{ old('max_uses') }}@endif"
                                required />
                            @error('max_uses')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group  col-auto row">
                        <label class="font-weight-bold text-muted col-auto my-auto">Max Uses Per User:
                        </label>
                        <div class="col-auto my-auto">
                            <input type="text" disabled class="form-control"
                                value=" {{ isset($promotion_voucher) ? $promotion_voucher->max_uses_user : 1 }} " />
                            {{-- <span class="px-4 py-1 rounded bg bg-light-info"> 
                                {{ isset($promotion_voucher) ? $promotion_voucher->max_uses_user : 1 }} 
                            </span> --}}
                        </div>
                    </div>

                    @if (isset($promotion_voucher))
                        <div class="form-group  col-auto row">
                            <label class="font-weight-bold text-muted col-auto my-auto">Times Used:
                            </label>
                            <div class="col-auto my-auto">
                                <input type="text" disabled class="form-control"
                                    value="{{ $promotion_voucher->uses }}" />
                                {{-- <span class="px-4 py-1 rounded bg bg-light-info"> 
                                    {{ $promotion_voucher->uses }}
                                </span> --}}
                            </div>
                        </div>
                    @endif

                </div>

                <hr />

                <div class="form-group col-auto row ">
                    <label class="font-weight-bold text-muted col-auto my-auto">Eligible Users (Optional):
                    </label>
                    <div class="col-auto  my-auto">
                        
                        @include('admin.promotion_voucher.includes.eligible_user_id_section')
                       
                    </div>
                </div>


                <hr>
           
                @include('admin.promotion_voucher.includes.eligibilities_section')
                


                <hr>
                <div class="form-group  ">
                    <label class="font-weight-bold text-muted my-auto">Description:
                        <span class="text-danger">*</span>
                    </label>


                    <textarea style="" class="form-control @error('description') is-invalid @enderror"
                        id="exampleTextarea" rows="3" placeholder="About the Promotion Voucher..."
                        name="description">@if (isset($promotion_voucher)){{ $promotion_voucher->description }}@else{{ old('description') }}@endif</textarea>

                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>



            </div>
            {{-- <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div> --}}
        </div>
    </div>

    <div class="col-lg-4 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">

                <div class="form-group  row">
                    <label class="font-weight-bold text-muted col-auto my-auto">Worth:
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-auto">

                        <div class="input-group">
                            <div class="input-group-prepend worthPrepend" ><span
                                    class="input-group-text text-info">Rs.</span></div>
                            <input type="number" class="form-control @error('worth') is-invalid @enderror"
                                placeholder="Enter Worth" name="worth" value="@if (isset($promotion_voucher)){{ $promotion_voucher->worth }}@else{{ old('worth') }}@endif" required />
                            <div class="input-group-append worthAppend"><span
                                    class="input-group-text text-info">%</span></div>
                        </div>

                        @error('worth')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="form-group  row">
                    <label class="font-weight-bold text-muted col-6 my-auto">Fixed Worth: </label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="hidden" name="is_fixed" value="0" />
                                <input type="checkbox" name="is_fixed" id="is_fixed" @if (isset($promotion_voucher)) @if ($promotion_voucher->user_type == 'rider') checked disabled @endif @endif
                                    {{ old('is_fixed', isset($promotion_voucher->is_fixed) ? $promotion_voucher->is_fixed : 0) == 1 ? 'checked' : '' }}
                                    data-switchery>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>

                <div class="form-group  row">
                    <label class="font-weight-bold text-muted col-6 my-auto">Status: </label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="hidden" name="status" value="in_active" />
                                <input type="checkbox" name="status" id="status"
                                    {{ old('status', isset($promotion_voucher->status) ? $promotion_voucher->status : '') == 'active' ? 'checked' : '' }}
                                    data-switchery>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>




                <div class="card-footer">

                    <button type="submit" id="btnFormSubmit"  class="btn btn-primary form-control" style="">Submit</button>

                </div>
            </div>
        </div>
    </div>

</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@section('page-specific-scripts')
    <script src="{{ asset('assets/admin/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/custom/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/features/miscellaneous/sticky-panels.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script>
        document.getElementById("is_fixed").value = "1";
        document.getElementById("status").value = "active";
        // $('.custom-validation').parsley();
        // $('#uncheckAll').hide();
        // $('#checkAll').on('click', function(e) {
        //     e.preventDefault();
        //     $(":input[type='checkbox']").prop('checked', true);
        //     $('#checkAll').hide();
        //     $('#uncheckAll').show();
        // });
        // $('#uncheckAll').on('click', function(e) {
        //     e.preventDefault();
        //     $(":input[type='checkbox']").prop('checked', false);
        //     $('#checkAll').show();
        //     $('#uncheckAll').hide();
        // });


        $('#user_type').select2();
        $('#eligible_user_ids').select2();

        $('#user_type').change(function(e) {
            if ($('#user_type option:selected').val() == 'rider') {
                if (!$("#is_fixed").is(":checked")) {
                    $("#is_fixed").click();
                    // $('#is_fixed').prop('disabled', true);
                }
                $('#is_fixed').prop('disabled', true);
            }else{
                $('#is_fixed').prop('disabled', false);
            } 

        });



        $("#is_fixed").click(function() {
            checkIsFixed();
        });


        function checkIsFixed() {
            if ($("#is_fixed").is(":checked")) {
                $('.worthAppend').hide()
                $('.worthPrepend').show()
            } else {
                $('.worthPrepend').hide()
                $('.worthAppend').show()
            }

        }

        checkIsFixed();





        var priceWorthContent = '<div  class="row priceWorthRow mb-2" > \
                    <div class="col-auto row my-1"> \
                        <span class="text-primary font-weight-bold font-size-h1 col-auto"> > </span>  \
                        <div class="col-auto"> \
                            <input required type="number" class="form-control eligible_price priceValue" step="0.01" placeholder="Enter Threshold Price" />  \
                        </div> \
                        <span class="my-auto font-weight-bold font-size-h3 text-primary"> : </span> \
                    </div> \
                    <div class="col-auto my-1"> \
                        <div class="input-group"> \
                            <div class="input-group-prepend worthPrepend" ><span \
                                class="input-group-text text-info ">Rs.</span></div> \
                            <input type="number" class="form-control priceWorth @error('worth') is-invalid @enderror" \
                                placeholder="Enter Worth"  value="" required /> \
                            <div class="input-group-append worthAppend" ><span \
                                    class="input-group-text text-info">%</span> \
                            </div> \
                        </div> \
                    </div>  \
                    <button  type="button" class="btn btn-transparent btnRemovePriceWorth "><i class="text-danger flaticon-delete font-size-h4"></i></button> \
                </div>';

            var distanceWorthContent = '<div  class="row distanceWorthRow mb-2" > \
                <div class="col-auto row my-1"> \
                    <span class="text-primary font-weight-bold font-size-h1 col-auto"> > </span>  \
                    <div class="col-auto"> \
                        <input required type="number" class="form-control eligible_distance distanceValue" step="0.01" placeholder="Enter Threshold Distance" />  \
                    </div> \
                    <span class="my-auto font-weight-bold font-size-h3 text-primary"> : </span> \
                </div> \
                <div class="col-auto my-1"> \
                    <div class="input-group"> \
                        <div class="input-group-prepend worthPrepend" ><span \
                            class="input-group-text text-info ">Rs.</span></div> \
                        <input type="number" class="form-control distanceWorth @error('worth') is-invalid @enderror" \
                            placeholder="Enter Worth"  value="" required /> \
                        <div class="input-group-append worthAppend" ><span \
                                class="input-group-text text-info">%</span> \
                        </div> \
                    </div> \
                </div>  \
                <button  type="button" class="btn btn-transparent btnRemoveDistanceWorth "><i class="text-danger flaticon-delete font-size-h4"></i></button> \
            </div>';

        $('#btnAddPriceWorth').click(function(e) {
            e.preventDefault();
            console.log('click');
            $(priceWorthContent).insertBefore('#btnAddPriceWorth');
            checkIsFixed();
        });

        $('body').on('click', '.btnRemovePriceWorth',function(e){
            e.preventDefault();
           
            $(this).parent().remove();
        });

        $('#btnAddDistanceWorth').click(function(e) {
            e.preventDefault();
            console.log('click');
            $(distanceWorthContent).insertBefore('#btnAddDistanceWorth');
            checkIsFixed();
        });

        $('body').on('click', '.btnRemoveDistanceWorth',function(e){
            e.preventDefault();
           
            $(this).parent().remove();
        });



        $('#btnFormSubmit').click(function(e){
            e.preventDefault();
            $('.eligibilityErrors').remove();
            let price_eligibility = {};
            let distance_eligibility = {};
            var validated = true;
            var errorElem = ' <span class="eligibilityErrors invalid-feedback" role="alert"> \
                                <strong>Please fill all the fields of eligibility!</strong> \
                            </span> \
            ';

                            var i = 0;
            $( ".priceWorthRow" ).each(function( index, value ) {
                
                var priceValue = $(value).find('.priceValue').val();
                var priceWorth = $(value).find('.priceWorth').val();

                if(!priceValue || !priceWorth)
                    validated = false;
                else{
                    var temp = {
                        "price" : priceValue, "worth":priceWorth
                    }
                    price_eligibility[i++] = temp;
                }

            });
            // console.log("DATA: ",price_eligibility);
            i=0;
            $( ".distanceWorthRow" ).each(function( index, value ) {
             
                var distanceValue = $(value).find('.distanceValue').val();
                var distanceWorth = $(value).find('.distanceWorth').val();

                if(!distanceValue || !distanceWorth)
                    validated = false;
                else{
                    var temp = {
                        "distance" : distanceValue, "worth":distanceWorth
                    }
                    distance_eligibility[i++] = temp;
                }

            });
            // console.log("DATA: ",distance_eligibility);

            $('#priceEligibilityInput').val(JSON.stringify(price_eligibility));
            $('#distanceEligibilityInput').val(JSON.stringify(distance_eligibility));

            if(!validated)
            {
                $(errorElem).insertAfter('.eligibilitySection');
                Swal.fire({
                    title: 'Validation Error!',
                    text: 'Please fill all the eligibility fields!',
                    })
                return;
            }

            $('.promoForm').submit();

        });


        jQuery(document).ready(function() {		
		$('#activation-date, #expire-date').datepicker({
			// rtl: KTUtil.isRTL(),
				todayHighlight: true,
				orientation: "bottom left",
				// templates: arrows,
				format: 'yyyy-mm-dd',
				startDate : new Date 
		});
        
	});   

    </script>

@if(!isset($promotion_voucher))
<script>
    $('body').on('click', '#btnRefreshVoucherCode', function(e) {
        e.preventDefault();
        var url = '{{ route('admin.voucher_code.generate') }}';
        $.ajax({
            type: 'GET',
            url: url,
            beforeSend: function() {},
            success: function(data) {
                console.log('GENERATED CODE: ' + data);
                $('#code').val(data);
            },
            error: function(data) {
                console.log('ERROR refreshing voucher code!')
            },
        });
    });
</script>
@endif


@endsection
