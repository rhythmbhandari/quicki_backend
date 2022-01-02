<label class="font-weight-bold text-muted col-auto my-auto ">Eligibilities based on price spent and distance travelled:
</label>

<div class="card card-custom">
    <div class="card-header">
        <div class="card-toolbar">
            <ul class="nav nav-light-success nav-bold nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_5_1">
                        <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <span class="nav-text">Price Spent</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_5_2">
                        <span class="nav-icon"><i class="
                            fas fa-biking"></i></span>
                        <span class="nav-text">Distance Travelled</span>
                    </a>
                </li>
           
            </ul>
        </div>

    </div>
    <div class="card-body">
        <div class="tab-content eligibilitySection">

            <div class="tab-pane fade show active" id="kt_tab_pane_5_1" role="tabpanel"
                aria-labelledby="kt_tab_pane_5_1">
                {{-- <h3 class="text-muted">Price Spent</h3> --}}

                @if(isset($promotion_voucher->price_eligibility))

                    @foreach($promotion_voucher->price_eligibility as  $price_eligibility)
                    <div  class="row priceWorthRow mb-2" >
                        <div class="col-auto row my-1">
                            <span class="text-primary font-weight-bold font-size-h1 col-auto"> > </span>  
                            <div class="col-auto">
                                <input type="number" class="form-control eligible_price priceValue" step="0.01" 
                                value="{{ $price_eligibility['price'] }}"
                                placeholder="Enter Threshold Price" /> 
                            </div>
                            <span class="my-auto font-weight-bold font-size-h3 text-primary"> : </span>
                        </div>
                       
                        <div class="col-auto my-1">
                            <div class="input-group">
                                <div class="input-group-prepend worthPrepend" ><span
                                    class="input-group-text text-info ">Rs.</span></div>
                                <input type="number" class="form-control priceWorth @error('worth') is-invalid @enderror"
                                    placeholder="Enter Worth"  value="{{ $price_eligibility['worth'] }}" required />
                                <div class="input-group-append worthAppend" ><span
                                        class="input-group-text text-info">%</span>
                                </div>
                            </div>
                        </div>
                        <button  type="button" class="btn btn-transparent btnRemovePriceWorth "><i class="text-danger flaticon-delete font-size-h4"></i></button>
                    </div>

                    @endforeach

                @endif

                
                <button type="button" id="btnAddPriceWorth" class="btn btn-success my-5 shadow"> <i class="flaticon2-add"></i> Add Price Worth</button>
                
              
                
               


            </div>

            <div class="tab-pane fade" id="kt_tab_pane_5_2" role="tabpanel" aria-labelledby="kt_tab_pane_5_2">
                @if(isset($promotion_voucher->distance_eligibility))

                @foreach($promotion_voucher->distance_eligibility as  $distance_eligibility)
                <div  class="row distanceWorthRow mb-2" >
                    <div class="col-auto row my-1">
                        <span class="text-primary font-weight-bold font-size-h1 col-auto"> > </span>  
                        <div class="col-auto">
                            <input type="number" class="form-control eligible_distance distanceValue" step="0.01" 
                            value="{{ $distance_eligibility['distance'] }}"
                            placeholder="Enter Threshold Distance" /> 
                        </div>
                        <span class="my-auto font-weight-bold font-size-h3 text-primary"> : </span>
                    </div>
                   
                    <div class="col-auto my-1">
                        <div class="input-group">
                            <div class="input-group-prepend worthPrepend" ><span
                                class="input-group-text text-info ">Rs.</span></div>
                            <input type="number" class="form-control distanceWorth @error('worth') is-invalid @enderror"
                                placeholder="Enter Worth"  value="{{ $distance_eligibility['worth'] }}" required />
                            <div class="input-group-append worthAppend" ><span
                                    class="input-group-text text-info">%</span>
                            </div>
                        </div>
                    </div>
                    <button  type="button" class="btn btn-transparent btnRemoveDistanceWorth "><i class="text-danger flaticon-delete font-size-h4"></i></button>
                </div>

                @endforeach

            @endif

            
            <button type="button" id="btnAddDistanceWorth" class="btn btn-success my-5 shadow"> <i class="flaticon2-add"></i> Add Distance Worth</button>
            
          

            </div>
            
        </div>
    </div>
</div>


<input type="hidden" name="price_eligibility" id="priceEligibilityInput" />
<input type="hidden" name="distance_eligibility"  id="distanceEligibilityInput" />