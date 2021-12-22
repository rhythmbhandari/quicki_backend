@extends('layouts.admin.app')

@section('title', 'SOS Detail')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
	<li class="breadcrumb-item text-muted">
		<a href="{{route('admin.dashboard') }}" class="text-muted">Dashboard</a>
	</li>
    <li class="breadcrumb-item text-muted">
		<a href="{{ route('admin.sos.index')}}" class="text-muted">SOS List</a>
	</li>
	<li class="breadcrumb-item text-active">
		<a href="{{ route('admin.sos.create')}}" class="text-active">SOS Add</a>
	</li>
</ul>
@endsection
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            SOS for booking ID&nbsp;<a href="{{ route('admin.booking.show', $sos->booking->id) }}">#{{$sos->booking->id}}</a>
        </h3>
    </div>
    <!--begin::Form-->
    <div class="card-body">
        <div class="card card-custom card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header align-items-center border-0 mt-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">SOS Activity</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">{{$sos->created_at->toDayDateTimeString()}}</span>
                </h3>
            </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::Timeline-->
                    <div class="timeline timeline-6 mt-3">
                        <!--begin::Item-->
                        <div class="timeline-item align-items-start">
                            <!--begin::Label-->
                            <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">{{$sos->created_at->format('g:i a')}}</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-warning icon-xl"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">{{ $sos->description }}</div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        @if($events != null)
                            @foreach($events as $event)
                                <div class="timeline-item align-items-start">
                                    <!--begin::Label-->
                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">{{$event->created_at->format('g:i a')}}</div>
                                    <!--end::Label-->
                                    <!--begin::Badge-->
                                    <div class="timeline-badge">
                                        <i class="fa fa-genderless text-success icon-xl"></i>
                                    </div>
                                    <!--end::Badge-->
                                    <!--begin::Content-->
                                    <div class="timeline-content d-flex">
                                        <span class="font-weight-bolder text-dark-75 pl-3 font-size-lg">{{$event->action_taken}}</span>
                                        <span class="font-weight-bolder text-dark-50 pl-3 font-size-lg"><i class="fas fa-user"></i>{{$event->handlerName}}</span>
                                    </div>
                                    <!--end::Content-->
                                </div>
                            @endforeach
                        @endif
                        
                    </div>
                </div>
        </div>
        
    <div class="card card-custom gutter-b">
        @if ($sos->status == 'active')
        <div class="card-header">
          <div class="card-title">
           <h3 class="card-label">
            Action
            <!-- <small>sub title</small> -->
           </h3>
          </div>
         </div>
         <div class="card-body">
             
        <form method="post"  action="{{route('admin.sos-detail.store', $sos->id, )}}">
            @csrf
            <div class="card-body">
                <div class="form-group" style="display: none;">
                    <label>Action Date <span class="text-danger">*</span></label>
                    <input type="input" name="action_date" value="{{date('Y-m-d h:i:s')}}" class="form-control" readonly>

                </div>

                    <div class="form-group">
                        <label>SOS Comment</label>
                        <input class="form-control @error('action_taken') is-invalid @enderror" name="action_taken" value="{{old('action_taken')}}"/>
                        <span class="form-text text-muted">Action Taken</span>
                            @error('action_taken')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                    </div>
                   {{-- <div class="form-group">
                        <label>Handler <span class="text-danger">*</span></label>
                        <select class="form-control @error('handler') is-invalid @enderror" name="handler" id="handler_id">
                            <option value="{{Auth::user()->employee->id}}" selected="selected">{{Auth::user()->name}}</option>
                        </select>
                        @error('handler')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                        
                    </div> --}}

                    <div class="form-group">
                        <label for="">Sos Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('sos_status') is-invalid @enderror" name="sos_status" id="">
                            <option value="ongoing" {{old('sos_status') == 'ongoing' ? 'selected' : ''}}>Ongoing</option>
                            <option value="resolved" {{old('sos_status') == 'resolved' ? 'selected' : ''}}>Resolved</option>
                            
                        </select>
                        @error('sos_status')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
                    </div>
                    
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="{{route('admin.sos.index')}}"><button type="button" class="btn btn-secondary">Cancel</button></a>
                </div>
        </form>
        </div>
        @else
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        SOS already resoved!!
                    </h3>
                </div>
            </div>
        @endif
    </div>
    <!--end::Form-->
</div>
@endsection
