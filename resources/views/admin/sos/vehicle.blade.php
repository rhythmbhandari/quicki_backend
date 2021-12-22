										<!--begin::List Widget 11-->
										<div class="card card-custom card-stretch gutter-b">
											<!--begin::Header-->
											<div class="card-header">
											  <div class="card-title">
											            <span class="card-icon">
											                <i class="flaticon2-chat-1 text-primary"></i>
											            </span>
											   
											  </div>
											        <div class="card-toolbar">
											        	@if(isset($vendor))
												            <a href="{{route('vehicle.create')}}" class="btn btn-sm btn-success font-weight-bold">
												                <i class="flaticon2-cube"></i> Add A Vehicle
												            </a>
											        	@endif
											        </div>
													
											 </div>
											<!--end::Header-->
											<!--begin::Body-->
											<div class="card-body pt-0">
												@if(isset($vendor) && $vendor->vehicles)
													@foreach($vendor->vehicles as $vehicle)
												<div class="d-flex align-items-center mb-9 bg-light-warning rounded p-5">
													<!--begin::Icon-->
													<span class="svg-icon svg-icon-warning mr-5">
														<span class="svg-icon svg-icon-lg">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect x="0" y="0" width="24" height="24" />
																	<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
																	<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</span>
													<!--end::Icon-->
													<!--begin::Title-->
													<div class="d-flex flex-column flex-grow-1 mr-2">
														{{--<a href="{{route('vehicle-edit', $vehicle->id)}}" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1" target="_blank">{{$vehicle->number_plate}}</a>--}}
														<span class="text-muted font-weight-bold">
														{{$vehicle->vehicle_number}}
														@if($vehicle->vehicleType)
															{{$vehicle->vehicleType->name}}
														@endif
														</span>
													</div>
													<!--end::Title-->
													<!--begin::Lable-->
													<a href="{{route('vehicle.edit', $vehicle->id)}}" target="_blank">
														
														<span class="font-weight-bolder text-warning py-1 font-size-lg">Edit</span>
													</a>
													<!--end::Lable-->
												</div>
												@endforeach
												@elseif(isset($vendor))
													<div class="d-flex align-items-center mb-9 bg-danger rounded p-5">
														<!--begin::Icon-->
														<span class="svg-icon svg-icon-warning mr-5">
															<span class="svg-icon svg-icon-lg">
																<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24" />
																		<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
																		<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
																	</g>
																</svg>
																<!--end::Svg Icon-->
															</span>
														</span>
														<!--end::Icon-->
														<!--begin::Title-->
														<div class="d-flex flex-column flex-grow-1 mr-2">
															<a href="#" class="font-weight-bold text-white text-hover-primary font-size-lg mb-1" target="_blank">You will be allowed to add vehicles after Vendor has been created.</a>
															You have no vehicles
														</div>
														<!--end::Title-->
													</div>
												@else
												<div class="d-flex align-items-center mb-9 bg-danger rounded p-5">
													<!--begin::Icon-->
													<span class="svg-icon svg-icon-warning mr-5">
														<span class="svg-icon svg-icon-lg">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect x="0" y="0" width="24" height="24" />
																	<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
																	<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</span>
													<!--end::Icon-->
													<!--begin::Title-->
													<div class="d-flex flex-column flex-grow-1 mr-2">
														<a href="#" class="font-weight-bold text-white text-hover-primary font-size-lg mb-1" target="_blank">You will be allowed to add vehicles after Vendor has been created.</a>
														
													</div>
													<!--end::Title-->
												</div>
												@endif
												<!--begin::Item-->

												
											</div>
											<!--end::Body-->
										</div>
										<!--end::List Widget 11-->