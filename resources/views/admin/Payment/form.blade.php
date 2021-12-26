@section('page-specific-styles')
<link href="{{ asset('resources/admin/css/libs/dropify/dropify.min.css') }}" rel="stylesheet" />
<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }
    input[type=number]{
        -moz-appearance: textfield;
    }
</style>
@endsection


@csrf


<!--  FORM START -->
<div class="row mb-2" data-sticky-container="">

    <!--- SPINNER : Begins -->
 {{-- <div class="overlay-layer rounded bg-primary-o-20 d-none" id="spinner" style="z-index:9;position:fixed;top:50%;left:50%">
    <div class="spinner spinner-danger spinner-lg"></div>
</div> --}}
<!--- SPINNER : Ends -->


    <!--  LEFT NON-STICKY PANEL -->
    <div class="col-lg-8 col-xl-9">

        <!-- FORM CARD BEGINS -->
        <div class="card card-custom  ">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        <header>{!! $header !!} </header>
                    </h3>
                </div>
            </div>
            
            <div class="card-body">

                @if(isset($order))
                <input type="hidden" name="order_slug" id="order_slug" value="{{$order->slug}}" />
                @endif

              
                <!-- USER SECTION : begins -->
                <h3 class="text-muted mb-2">CUSTOMER</h3>
                <div id="customerSection" class="container p-0">
                    @include('admin.order.includes.customerSection')
                </div>
                <!-- USER SECTION : ends -->

                <hr />

                <!-- PRODUCT SECTION : begins -->
                <h3 class="text-muted my-2">PRODUCTS</h3>
                <div class="container p-0 ">
                    <!-- Filters : begins -->
                    {{-- <div class="card p-3">
                        <span class="text-muted font-weight-bold"><i class="fas fa-filter"></i> Filters</span>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 mt-2">
                                <div id="categorySection">
                                    <label for="category" class="text-muted">Category:</label>
                                    <select name="category"  class="form-control select2 m-0" id="category_select" style="opacity: 1 !important">
                                        <option value="" >Select a Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->slug}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 mt-2">
                                <div id="subCategorySection">
                                    @include('admin.order.includes.subCategorySection')
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 mt-2">
                                <div id="brandSection">
                                    <label for="brand" class="text-muted">Brand:</label>
                                    <select name="brand"  class="form-control select2 m-0" id="brand_select" style="opacity: 1 !important">
                                        <option value="" >Select a Brand</option>
                                        @foreach($brands as $brand)
                                        <option value="{{$brand->slug}}">{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Filters: ends -->

                    
                    <!-- Product Dropdown : begins -->
                    <div class="card p-3 my-2">
                        <div id="productListSection">
                            @include('admin.order.includes.productListSection')
                        </div>
                    </div>
                    <!-- Product Dropdown : ends -->

                    <hr />
                    <!-- Product Cart Section:begins -->
                    <h3 class="text-muted  mt-4 mb-0">
                        {{ !isset($order) ? "CUSTOMER's CART" : "CUSTOMER's ORDER ITEMS"}}

                    </h3>

                        <div class="card p-3 my-2">  <div id="productCartSection" class="mt-0 pt-0">
                              
                        @error('cart_products')<span class="text-danger"><i
                            class="ki ki-outline-info text-danger ml-2  mr-2"></i>{{ $message }}</span>@enderror

                            @if(!isset($order))
                            @include('admin.order.includes.productCartSection')
                            @else 
                            @include('admin.order.includes.productOrderSection')
                            @endif
                        
                        </div>
                    </div>
                    <!-- Product Cart Section:ends -->

                </div>
                <!-- PRODUCT SECTION : ends -->

                <hr />

                <!-- SHIPPING & BILLING INFO: begins -->
                <h3 class="text-muted my-2">BILLING INFO</h3>
                <div class="card p-3">
                    <div class="row">
                        <!-- Shipping Address:begins -->
                        <div class="col-12 mb-2">
                            <div class="form-grp" id="shippingAddressSection">
                                @include('admin.order.includes.shipping_address')
                            </div>
                        </div>
                        <!-- Shipping Address:ends -->

                        <!-- Billing Address:begins -->
                        <div class="col-12 mb-4 ">
                            <div class="form-grp" id="billingAddressSection">
                                @include('admin.order.includes.billing_address')
                            </div>
                        </div>
                        <!-- Billing Address:ends -->
                    </div>
                </div>
                <!-- SHIPPING & BILLING INFO: ends -->
                
               

            </div>
        </div>
        <!-- FORM CARD ENDS -->
    </div>
    <!-- LEFT NON-STICKY PANEL ENDS -->


    
    <!--  RIGHT STICKY PANEL BEGINS-->
    <div class="col-lg-4 col-xl-3 ">

        <div class="row mb-4">
            <!--  RIGHT STICKY PANEL TOP SUBMTI -->
            <div class="col-lg-12 card card-custom sticky" data-sticky="true" data-margin-top="145px"
                data-sticky-for="1023" data-sticky-class="sticky">
                <div class="card-body">


                    <div id="totalSection">
                        @include('admin.order.includes.totalSection')
                    </div>

                </div>
                <div class="card-footer col-12 align-items-center">
                    <button type="submit" class="btn btn-success mr-2 " id="btnPlaceOrder">
                        {{isset($order)?'Update Order':'Place Order'}}
                    </button>
                    <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>

 



    </div>
    <!--  RIGHT STICKY PANEL ENDS -->


</div>
<!--  FORM ENDS -->

@include('admin.order.includes.newCustomerModal')
@include('admin.general.address.show_map_modal')
@include('admin.general.address.new_map_modal')


@section('page-specific-scripts')



<script src="{{ asset('resources/admin/js/libs/dropify/dropify.min.js') }}"></script>
<!--begin::Page Vendors(used by this page)-->
<script src="{{ asset('/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{ asset('/js/pages/crud/forms/editors/tinymce.js') }}"></script>
{{-- <script src="{{ asset('/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script> --}}


{{-- <script src="{{asset('js/maps/map.js')}}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{asset('js/maps/map.js')}}"></script>
<!-- Google Maps API KEY:begins -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ config('app.map_key') }}&libraries=places&callback=initMap&v=weekly&channel=2">
</script>
<!-- Google Maps API KEY:ends -->

{{-- <script src="{{asset('/js/pages/crud/forms/widgets/select2.js')}}"></script> --}}
<!--end::Page Scripts-->
<script type="text/javascript">

    //document.getElementById("status").value = "active";

    $('body').on('click','#btnPlaceOrder',function(e){
        // e.preventDefault(); e.returnValue = true;
        // console.log('PLACE ORDER...START');
        // $('#btnPlaceOrder').prop('disabled',true);
        // $('#btnPlaceOrder').removeClass('bg-success');
        // $('#btnPlaceOrder').addClass('bg-dark');
        // setTimeout(() => {
        //     console.log('PLACE ORDER...COMPLETED');
        //     $('#btnPlaceOrder').prop('disabled',false);
        //     $('#btnPlaceOrder').removeClass('bg-dark');
        //     $('#btnPlaceOrder').addClass('bg-success');
        // }, 10000);
            
    });


    $(document).ready(function() {
        var customerId = $('#customerId');
        customerId.select2();
        $('#category_select').select2();
        $('#sub_category_select').select2();
        $('#brand_select').select2();
        $('#product_select').select2();
        $('#order_product_select').select2();
        $('#billing_address').select2();
        $('#shipping_address').select2();
        // $('#product_select').select2({
        //     templateResult: formatState
        //     });
        
        updateOrderDeliveryPrice($('#shipping_address option:selected').val());
    });

    // function formatState (state) {
    //     console.log('STATE: ',state);
    //     console.log('IMG: ',state.dataset);
    //     if (!state.id) { return state.text; }
    //     var $state = $(
    //     '<span ><img sytle="display: inline-block;height:120px;width:auto;" src="Product Thumbnail" /> ' + state.text + '</span>'
    //     );
    //     return $state;
    // }
    function removeCustomerValidationErrors()
    {
        $('#newCustomerForm').find('.is-invalid').removeClass('is-invalid');
        $('.customer-error').remove();
    }

    $('#btnNewCustomer').on('click', function(e){
        e.preventDefault();
        removeCustomerValidationErrors();
        console.log('new cust btn clicked!');
        e.returnValue = true;
    });

    $('body').on('change','#customerId', function(e){
        var customer_id = $('#customerId option:selected').val();
        updateAddressDropdown(customer_id);
        updateProductCart("update",customer_id);
    });



    $('#btnSaveNewCustomer').on('click', function(e){
        e.preventDefault();
        removeCustomerValidationErrors();


        var firstName = $('#first_name').val();
        var middleName = $('#middle_name').val();
        var lastName = $('#last_name').val();
        var userName = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var passwordConfirmation = $('#password_confirmation').val();
        var gender = $('.gender:checked').val();
        var dob = $('#dob').val();
        var contact = $('#customer_contact').val();
        var secondary_contact = $('#secondary_contact').val();
        console.log('FORM FIELDS: ', firstName, middleName, lastName, userName, email, password,  passwordConfirmation, gender, dob, contact, secondary_contact);



        var url = '{{ route('admin.customer.ajaxStore') }}'; 
        $.ajax({
            url: url,
            async:false,
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "first_name":firstName,
                "middle_name":middleName,
                "last_name":lastName,
                "username":userName,
                "email":email,
                "password":password,
                "password_confirmation":passwordConfirmation,
                "gender":gender,
                "dob":dob,
                "contact":contact,
                "secondary_contact":secondary_contact,
            },
            beforeSend: function() {
                // setting a timeout
                console.log('Saving Customer!');
            },
            success: function(response) {
                console.log('New Customer: ', response);
                if(response)
                {
                    console.log('New customer added successfully!');
                    $('#customerSection').html(getCustomerList());
                    
                    $('#closeNewCustomerModal').click();
                    swal.fire({
                        title: 'SUCCESS!',
                        text: "New customer added successfully!",
                        icon: 'success',
                    });
                }
                else{
                    console.log('Failed to add new customer!');
                    swal.fire({
                        title: 'ERROR!',
                        text: "Failed to add new customer!",
                        icon: 'error',
                    });
                }
            },
            error: function(response) {
                console.log('Failed to store customer!');
                swal.fire({
                        title: 'VALIDATION FAILED!',
                        text: "Invalid data encountered!",
                        icon: 'error',
                    });
                if (response.status == 422) { // when status code is 422, it's a validation issue
                    console.log('ERROR WHILE STORING CUSTOMER!');
                    console.log(response.responseJSON);
                    showCustomerFormErrors(response);
                }
            }
        });

       
        function showCustomerFormErrors(response)
        {
            var message = "";
            if(response.responseJSON.errors.first_name != undefined)
            {
                $('#first_name').addClass('is-invalid');
                message = response.responseJSON.errors.first_name;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#first_name');
            }
            if(response.responseJSON.errors.last_name != undefined)
            {
                $('#last_name').addClass('is-invalid');
                message = response.responseJSON.errors.last_name;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#last_name');
            }
            if(response.responseJSON.errors.username != undefined)
            {
                $('#username').addClass('is-invalid');
                message = response.responseJSON.errors.username;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#username');
            }
            if(response.responseJSON.errors.email != undefined)
            {
                $('#email').addClass('is-invalid');
                message = response.responseJSON.errors.email;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#email');
            }
            if(response.responseJSON.errors.password != undefined)
            {
                $('#password').addClass('is-invalid');
                message = response.responseJSON.errors.password;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#password');
            }
           
            if(response.responseJSON.errors.gender != undefined)
            {
                $('#gender').addClass('is-invalid');
                message = response.responseJSON.errors.gender;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#gender');
            }
            if(response.responseJSON.errors.dob != undefined)
            {
                $('#dob').addClass('is-invalid');
                message = response.responseJSON.errors.dob;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#dob');
            }
            if(response.responseJSON.errors.contact != undefined)
            {
                $('#customer_contact').addClass('is-invalid');
                message = response.responseJSON.errors.contact;
                $('<span class="text-danger customer-error"><i class="ki ki-outline-info text-danger ml-2  mr-2"></i>'+message+'</span>').insertAfter('#customer_contact');
            }
        }

    });


    $('body').on('change','#category_select', function(e){
        var selectedOption = $('#category_select option:selected').val();
        $('#subCategorySection').html(getSubCategoryList(selectedOption));
        $('#sub_category_select').select2();
        //   console.log('asa'+$('#sub_category_select option:selected').val());
        updateProductList();
    });

    $('body').on('change','#brand_select', function(e){
        updateProductList();
    });
    
    $('body').on('change','#sub_category_select', function(e){
        updateProductList();
    });

    $('body').on('change','#product_select', function(e){
        var selectedProductId = $('#product_select option:selected').val();   
        var customer_id = $('#customerId option:selected').val();
        if(customer_id != "")
            updateProductCart("update", customer_id, selectedProductId);
    });

   
    
    $('body').on('click', '.btnRemoveCartItem', function(e){
        e.preventDefault();
        console.log('Remove Cart Item BTN CLICKED!');
        var customer_id = $('#customerId option:selected').val();
        var cart_product_id = $(this).data('id');
        updateProductCart("remove", customer_id, "", cart_product_id);
    });

    //EDIT CART PRODUCTS
    $('body').on('click', '.btnQuantityIncrement', function(e){
        e.preventDefault();
        var customer_id = $('#customerId option:selected').val();
        var cart_product_id = $(this).data('id');
        updateProductCart( "increment", customer_id, "", cart_product_id)
    });
    $('body').on('click', '.btnQuantityDecrement', function(e){
        e.preventDefault();
        var customer_id = $('#customerId option:selected').val();
        var cart_product_id = $(this).data('id');
        updateProductCart("decrement", customer_id, "", cart_product_id, )
    });


    //EDIT ORDER PRODUCTS
    $('body').on('change','#order_product_select', function(e){
        var selectedProductId = $('#order_product_select option:selected').val();   
        var customer_id = $('#customerId option:selected').val();
        if(customer_id != "")
        updateProductOrder("update", customer_id, selectedProductId);
    });
    $('body').on('click', '.btnRemoveOrderItem', function(e){
        e.preventDefault();
        console.log('Remove Order Item BTN CLICKED!');
        var customer_id = $('#customerId option:selected').val();
        var order_product_id = $(this).data('id');
        updateProductOrder("remove", customer_id, "", order_product_id);
    });
    $('body').on('click', '.btnOrderQuantityIncrement', function(e){
        e.preventDefault();
        var customer_id = $('#customerId option:selected').val();
        var order_product_id = $(this).data('id');
        updateProductOrder( "increment", customer_id, "", order_product_id)
    });
    $('body').on('click', '.btnOrderQuantityDecrement', function(e){
        e.preventDefault();
        var customer_id = $('#customerId option:selected').val();
        var order_product_id = $(this).data('id');
        updateProductOrder("decrement", customer_id, "", order_product_id, )
    });




    $('body').on('change', '.product_quantity', function(e){
        e.preventDefault();
        var customer_id = $('#customerId option:selected').val();
        var cart_product_id = $(this).data('id');
       // console.log('Quantity Changed of '+cart_product_id);return false; 
        var old_quantity = $('#product_quantity_'+cart_product_id).val();
       // if(old_quantity==1) return;
        updateProductCart("update_quantity", customer_id, "", cart_product_id, old_quantity)
    });


    /***** Ajax Call for updated and rendered customer list:begins ****/
    function getCustomerList()
    {
        var result = '';
        var url = '{{ route('admin.order.customerList') }}';
        $.ajax({
            type: 'get',
            url: url,
            async: false,
            beforeSend: function() {},
            success: function(data) {
                result = data;
            },
            error: function(data) {
                result = false;
            }
        });
        return result;
    }
    /***** Ajax Call for updated and rendered customer list:ends ****/

    /***** Ajax Call for updated and rendered sub_categories list:begins ****/
    function getSubCategoryList(category_id)
    {
        var result = '';
        var url = '{{ route('admin.order.subCategoryList', ':category_id') }}'.replace(':category_id', category_id);
        $.ajax({
            type: 'get',
            url: url,
            async: false,
            beforeSend: function() {},
            success: function(data) {
                result = data;
            },
            error: function(data) {
                console.log('ERROR FETCHING SUBCATEGORY!');
                result = false;
            }
        });
        return result;
    }
    /***** Ajax Call for updated and rendered sub_categories list:ends ****/

    /***** Updates the product dropdown list :begins ****/
    function updateProductList()
    {
        var result = '';
        var url = '{{ route('admin.order.productList') }}';
        $.ajax({
            type: 'post',
            url: url,
            data: {
                "_token": "{{ csrf_token() }}",
                "category_id" : $('#category_select option:selected').val(),
                "sub_category_id" : $('#sub_category_select option:selected').val(),
                "brand_id" : $('#brand_select option:selected').val(),
            },
            async: false,
            beforeSend: function() {},
            success: function(data) {
                result = data;
                $('#productListSection').html(data);
                $('#product_select').select2();
            },
            error: function(data) {
                result = false;
            }
        });
        return result;
    }
    /***** Updates the product dropdown list :ends ****/


    /***** Fetches a product info from id/slug:begins ****/
    function getProductInfo(product_id)
    {
        var result = '';
        var url = '{{ route('admin.order.productInfo', ':product_id') }}'.replace(':product_id', product_id);
        $.ajax({
            type: 'get',
            url: url,
            async: false,
            beforeSend: function() {},
            success: function(data) {
                result = data;
            },
            error: function(data) {
                console.log('ERROR FETCHING PRODUCT INFO!');
                result = false;
            }
        });
        return result;
    }
    /*****  Fetches a product info from id/slug:ends ****/

    /***** Updates product cart:begins ****/
    function updateProductCart(action, customer_id=undefined, product_id=undefined, cart_product_id=undefined, new_quantity=undefined)
    {
        //var customer_id = $('#customerId option:selected').val();
        if(customer_id==undefined || customer_id=="") {customer_id = '0';}
        if(product_id==undefined || product_id=="") {product_id = '0';}
        if(cart_product_id==undefined || cart_product_id=="") {cart_product_id = '0';}
        if(new_quantity==undefined || new_quantity=="") {new_quantity = '0';}


        var url = '{{ route('admin.order.updateProductCart') }}';
        $.ajax({
            type: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "action" : action,
                "customer_id": customer_id,
                "product_id": product_id,
                "cart_product_id": cart_product_id,
                "new_quantity": new_quantity                                                            
            },
            url: url,
            //async: false,
            beforeSend: function() {
                if(new_quantity != 0)
                {
                    $('.btnQuantityIncrement[data-id='+cart_product_id+']').prop('disabled', true);
                    $('.btnQuantityDecrement[data-id='+cart_product_id+']').prop('disabled', true);
                }
            },
            success: function(response) {
                if(new_quantity != 0)
                {
                    $('.btnQuantityIncrement[data-id='+cart_product_id+']').prop('disabled', false);
                    $('.btnQuantityDecrement[data-id='+cart_product_id+']').prop('disabled', false);
                }
                console.log('Fetched Product Cart Info Successfully!');
                $('#productCartSection').html(response.product_cart_section);
                $('#totalSection').html(response.total_section);
                updateDeliveryPrice($('#shipping_address option:selected').val());
            },
            error: function(response) {
                if(new_quantity != 0)
                {
                    $('.btnQuantityIncrement[data-id='+cart_product_id+']').prop('disabled', false);
                    $('.btnQuantityDecrement[data-id='+cart_product_id+']').prop('disabled', false);
                }
                console.log('ERROR UPDATING PRODUCT CART INFO!');
            }
        });
    }
    /*****  Updates product cart:ends ****/


     /***** Updates product cart:begins ****/
     function updateProductOrder(action, customer_id=undefined, product_id=undefined, order_product_id=undefined, new_quantity=undefined)
    {
        //var customer_id = $('#customerId option:selected').val();
        if(customer_id==undefined || customer_id=="") {customer_id = '0';}
        if(product_id==undefined || product_id=="") {product_id = '0';}
        if(order_product_id==undefined || order_product_id=="") {order_product_id = '0';}
        if(new_quantity==undefined || new_quantity=="") {new_quantity = '0';}

        console.log("SHIPPING ADDRESS: ",$('#shipping_address option:selected').val());
        var shipping_address = $('#shipping_address option:selected').val();
        if(shipping_address == undefined)
        {
            Swal.fire({
                title: 'ERROR!',
                text: "Select a shipping address before proceeding!",
                icon: 'error',
            });
            return;  
        }

        var url = '{{ route('admin.order.updateProductOrder', ':order_slug' ) }}'.replace(':order_slug',$('#order_slug').val());
        $.ajax({
            type: 'post',
            async:false,
            data: {
                "_token": "{{ csrf_token() }}",
                "action" : action,
                "customer_id": customer_id,
                "product_id": product_id,
                "order_product_id": order_product_id,
                "new_quantity": new_quantity                                                            
            },
            url: url,
            //async: false,
            beforeSend: function() {
                if(new_quantity != 0)
                {
                    $('.btnOrderQuantityIncrement[data-id='+order_product_id+']').prop('disabled', true);
                    $('.btnOrderQuantityIncrement[data-id='+order_product_id+']').prop('disabled', true);
                }
            },
            success: function(response) {
                if(response)
                {
                    if(new_quantity != 0)
                {
                    $('.btnOrderQuantityIncrement[data-id='+order_product_id+']').prop('disabled', false);
                    $('.btnOrderQuantityDecrement[data-id='+order_product_id+']').prop('disabled', false);
                }
                console.log('Fetched Order Order Product  Info Successfully!');

               
                $('#productCartSection').html(response.product_order_section);
             


                $('#totalSection').html(response.total_section);
                updateOrderDeliveryPrice($('#shipping_address option:selected').val());
                }
               
            },
            error: function(response) {
                if(new_quantity != 0)
                {
                    $('.btnOrderQuantityIncrement[data-id='+order_product_id+']').prop('disabled', false);
                    $('.btnOrderQuantityDecrement[data-id='+order_product_id+']').prop('disabled', false);
                }
                console.log('ERROR UPDATING PRODUCT ORDER INFO!');
            }
        });
    }
    /*****  Updates product cart:ends ****/



    /**** BILLING INFO : begins *******/

    var addressInputElem = document.getElementById('addressName');
    var showMapElem = document.getElementById('showMap');
    var addMElem = document.getElementById('addMap');

    var latElem = document.getElementById('latitude');
    var lngElem = document.getElementById('longitude');
    var districtElem = document.getElementById('district');
    var provinceElem = document.getElementById('province');
    var cityElem = document.getElementById('city');
    var subLocalityElem = document.getElementById('sub_locality');
    var postalCodeElem = document.getElementById('postal_code');
    var routeElem = document.getElementById('route');
    var countryElem = document.getElementById('country');
    var addressElem = document.getElementById('addressName');
    var addressCheckElem = document.getElementById('address_name_check');

    var modalMapDetails;
    var showMap, addMap; 
    var defaultMapLocation = {lat: 27.683772,lng: 85.309353};
    var defaultMapOptions = {center:defaultMapLocation, zoom:16};

    $('#shipping_address').on('change',function(e){
        console.log('Shipping Address Changed to '+ e.target.value +'!!');
        var newAddressId = e.target.value;
        //var addressId = $('#shipping_address option:selected').val();
        // updateDeliveryPrice(newAddressId);
        var customer_id = $('#customerId option:selected').val();
        updateProductCart("update",customer_id);
    })

    $('#billing_address').on('change',function(e){
        console.log('Billing Address Changed to '+ e.target.value +'!!');
        var newAddressId = e.target.value;
    })



    /******* On Click Event of Show Shipping Address Map  *******/
    $('body').on('click','#btnShowShippingMapModal', function(e){
        console.log('btn show shipping modal');
        var addressId = $('#shipping_address option:selected').val();
        showMapModal(addressId);
        $('#myModal').modal('toggle')
    } );

    /******* On Click Event of  Show Billing Address Map    *******/
    $('body').on('click','#btnShowBillingMapModal', function(e){
        console.log('btn show billing modal');
        var addressId = $('#billing_address option:selected').val();
        showMapModal(addressId);
    } );

     /**** Shows New Map Modal *****/
    $('body').on('click','.btnNewAddress', function(e) {
        e.preventDefault();
        console.log(' New Map Modal Clicked! ');
        
        //Change Map's Center and its marker position to its default Address!
        addMap.panTo(addMap.defaultLocation);
        addMap.setMarkerPosition(addMap.defaultLocation);
        addMap.marker.setDraggable(true);

        //Empty the input fields with the address details
        $('#addressName').val('');
        $('#address_name_check').val('');
        $('#province').val('');
        $('#district').val('');
        $('#latitude').val('');
        $('#longitude').val('');
        $('#city').val('');
        $('#sub_locality').val('');
        $('#route').val('');
        $('#postal_code').val('');
        $('#contact').val('');
        $('#contact_person').val('');
        $('#country').val('');
        
        $('#saveTitle').html('Add New Address');
        $('#btnSaveAddress').removeClass('d-none');
        $('#btnUpdateAddress').addClass('d-none');
        e.returnValue = true;
    });


    function updateAddressDropdown(customer_id=undefined)
    {
        customer_id = (customer_id==undefined) ? $('#customerId option:selected').val() : customer_id;
        var result = '';
        var url = '{{ route('admin.order.updateAddressList', ':customer_id') }}'.replace(':customer_id', customer_id);
        $.ajax({
            type: 'get',
            url: url,
            async: false,
            beforeSend: function() {},
            success: function(response) {
                //console.log('ADDRESS DROPDOWN UPDATED SUCCESSFULLY!', response);
                $('#shippingAddressSection').html(response.shipping_address_list);
                $('#billingAddressSection').html(response.billing_address_list);
                $('#totalSection').html(response.total_section);
                
                $('#billing_address').select2();
                $('#shipping_address').select2(); 

                var addressId = $('#shipping_address option:selected').val();
                console.log("Selected Address Id:",addressId); 
                // 
                if(addressId != undefined && addressId != "")
                    updateDeliveryPrice(addressId);
                //updateProductCart("update",customer_id);
            },
            error: function(response) {
                console.log('FAILED UPDATING ADDRESSES DROPDOWN!');
                result = false;
            }
        });
        return result;
    }


    function initMap(){
        showMap = new Map(showMapElem, defaultMapOptions );
        addMap = new Map(addMElem, defaultMapOptions );
        addMap.initializeElements(addressElem, addressCheckElem, latElem, lngElem, provinceElem, districtElem, countryElem, cityElem, postalCodeElem, subLocalityElem, routeElem);
        addMap.addAutoCompleteListener(addressInputElem);
        addMap.addMarkerDragListener();
    }

   

    function showMapModal(selectedAddress){
        console.log('Show Map Modal Clicked!, Selected Address: '+selectedAddress);
        var addressDetails = modalMapDetails = fetchJsonAddress(selectedAddress);

        //Fill Address Details To Modal!
        $('#modalAddress').html(' Address: <span class="text-dark">'+addressDetails.name+'</span>');
        $('#modalProvince').html(' Province: <span class="text-dark">'+addressDetails.province+'</span>');
        $('#modalDistrict').html(' District: <span class="text-dark">'+addressDetails.district+'</span>');
        $('#modalLatitude').html(' Latitude: <span class="text-dark">'+addressDetails.latitude+'° </span>');
        $('#modalLongitude').html(' Longitude: <span class="text-dark">'+addressDetails.longitude+'° </span>');
        $('#modalCity').html(' City: <span class="text-dark">'+( (addressDetails.city!="" && addressDetails.city!=null && addressDetails.city!=undefined ) ? addressDetails.city : '')+'</span>');
        $('#modalSubLocality').html(' Sub Locality: <span class="text-dark">'+( (addressDetails.sub_locality!="" && addressDetails.sub_locality!=null && addressDetails.sub_locality!=undefined  ) ? addressDetails.sub_locality : '')+'</span>');
        $('#modalRoute').html(' Route: <span class="text-dark">'+( (addressDetails.route!="" && addressDetails.route!=null && addressDetails.route!=undefined  ) ? addressDetails.route : '')+'</span>');
        $('#modalPostalCode').html(' PostalCode: <span class="text-dark">'+( (addressDetails.postal_code!=""  && addressDetails.postal_code!=null && addressDetails.postal_code!=undefined ) ? addressDetails.postal_code : '')+'</span>');
        $('#modalName').html(' Name: <span class="text-dark">'+( (addressDetails.contact_person!="" && addressDetails.contact_person!=null && addressDetails.contact_person!=undefined  ) ? addressDetails.contact_person : '')+'</span>');
        $('#modalContact').html(' Contact: <span class="text-dark">'+(( addressDetails.country!="" && addressDetails.country!=null && addressDetails.country!=undefined  ) ? addressDetails.country : '')+'</span>');
        $('#modalCountry').html(' Country: <span class="text-dark">'+addressDetails.country+'</span>');
        
        //Change Map's Center and its marker position to selected Address!
        showMap.panTo({lat:addressDetails.latitude, lng:addressDetails.longitude});
        showMap.setMarkerPosition({lat:addressDetails.latitude, lng:addressDetails.longitude});
        showMap.updateInfoWindowContent(addressDetails.name);
        showMap.updateMapTitle(addressDetails.name);
    }


    //Ajax call that fetches address in a json format via ajax call!
    function fetchJsonAddress(addressId){
        var result;
        var url = '{{ route('admin.address.json', ':id') }}'; 
        url = url.replace(':id', addressId);
        $.ajax({
            url: url,
            async:false,
            method: "GET",
            beforeSend: function() {
                // setting a timeout
            },
            success: function(response) {
                console.log('Fetched Address JSON: ', response);
                result = response;
            },
            error: function(response) {
                console.log('Failed to fetch Address JSON!');
            }
        });
        return result;
    }


    /**** Shows New Map Modal *****/
    $('body').on('click', '.btnNewAddress', function(e){
        e.preventDefault();
        console.log(' New Map Modal Clicked! ');
        
        //Change Map's Center and its marker position to its default Address!
        addMap.panTo(addMap.defaultLocation);
        addMap.setMarkerPosition(addMap.defaultLocation);
        addMap.marker.setDraggable(true);

        //Empty the input fields with the address details
        $('#addressName').val('');
        $('#address_name_check').val('');
        $('#province').val('');
        $('#district').val('');
        $('#latitude').val('');
        $('#longitude').val('');
        $('#city').val('');
        $('#sub_locality').val('');
        $('#route').val('');
        $('#postal_code').val('');
        $('#contact').val('');
        $('#contact_person').val('');
        $('#country').val('');
        
        $('#saveTitle').html('Add New Address');
        $('#btnSaveAddress').removeClass('d-none');
        $('#btnUpdateAddress').addClass('d-none');
        e.returnValue = true;
    });

    $('#btnSaveAddress').on('click', function(e){
        e.preventDefault();
        console.log('Save New Address BTN Clicked!');
        $(this).addClass('d-none');
        saveAddress();
        $(this).removeClass('d-none');
    })

    $('body').on('change', '#shipping_address', function(){
        updateDeliveryPrice($('#shipping_address option:selected').val());
        updateOrderDeliveryPrice($('#shipping_address option:selected').val());
    });

    //Ajax call to save a new address!
    function saveAddress(){
        console.log("Saving Address FOR CUST SLUG: ", $('#customerId option:selected').val() );
        var url = '{{ route('admin.address.store') }}';
        $.ajax({
            url: url,
            async:false,
            method: "POST",
            data: {
                    "_token": "{{ csrf_token() }}",
                    "name": $('#address_name_check').val(),
                    "latitude": $('#latitude').val(),
                    "longitude": $('#longitude').val(),
                    "province": $('#province').val(),
                    "district": $('#district').val(),
                    "city": $('#city').val(),
                    "sub_locality": $('#sub_locality').val(),
                    "route": $('#route').val(),
                    "postal_code": $('#postal_code').val(),
                    "country": $('#country').val(),
                    "contact": $('#contact').val(),
                    "contact_person": $('#contact_person').val(),
                    "customer_slug": $('#customerId option:selected').val(),
                },
            beforeSend: function() {
                // setting a timeout
                $(this).addClass('d-none');
            },
            success: function(response) {
                console.log('New Address Created! ', response);
                if(response)
                {
                    console.log('Address Saved');
                    //Append the new address to the select2 dropdowns
                   // $('#addressContainer').html(getAddressList());
                   updateAddressDropdown();
                  
                    swal.fire({
                        title: 'SUCCESS!',
                        text: "New address added successfully!",
                        icon: 'success',
                    }); } 
                else {
                    Swal.fire({
                        title: 'ERROR!',
                        text: "Failed to create new address!",
                        icon: 'error',
                    });  
                }
                
                $(this).removeClass('d-none');
                $('#closeNewAddressModal').click();
            },
            error: function(response) {
                console.log('Failed to create new address!');
                Swal.fire({
                        title: 'ERROR!',
                        text: "Failed to create new address!",
                        icon: 'error',
                    });
                $(this).removeClass('d-none');
            }
        });
    }

    /*** Update Delivery Price ***/
    function updateDeliveryPrice(addressId){
      if($('#shipping_address option:selected').val()== '' || $('#shipping_address option:selected').val() == undefined)
        return;
        var timer;
        clearTimeout(timer)
        timer = setTimeout(() => {
            url = "{{route('admin.order.delivery_price')}}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    "_token": '{{ csrf_token() }}',
                    // "lat": lat ,
                    // "lng": lng ,
                    "address_id": addressId,
                    "customer_slug": $('#customerId option:selected').val(),
                },
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(response) {
                    console.log('response delv price:');
                    console.log(response); 
                    $('#delivery_price').html(response.delivery_price);
                    
                    $('#total_amount').html(response.total_amount);
                },
                error(response){
                }
            });
        }, 1000); 
    }
    /**** BILLING INFO : ends *******/


    function updateOrderDeliveryPrice(addressId){
    if($('#order_slug').val() == '' || $('#order_slug').val() == undefined || $('#shipping_address option:selected').val()== '' || $('#shipping_address option:selected').val() == undefined )
        return;
        console.log('UPDATING ORDER DELIVERY PRICE!');
      var timer;
      clearTimeout(timer)
      timer = setTimeout(() => {
          url = "{{route('admin.order.order_delivery_price',':order_slug' ) }}".replace(':order_slug',$('#order_slug').val());
          $.ajax({
              url: url,
              method: "POST",
              data: {
                  "_token": '{{ csrf_token() }}',
                  // "lat": lat ,
                  // "lng": lng ,
                  "address_id": addressId,
                  "customer_slug": $('#customerId option:selected').val(),
              },
              dataType: 'json',
              beforeSend: function() {
              },
              success: function(response) {
                  console.log('response delv price:');
                  console.log(response); 
                  $('#order_delivery_price').html(response.delivery_price);
                  
                  $('#order_total_amount').html(response.total_amount);
              },
              error(response){
              }
          });
      }, 1000); 
  }


</script>
@endsection
