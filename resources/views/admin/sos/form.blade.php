@csrf
<div class="row" data-sticky-container>
    <div class="col-lg-6 col-xl-8">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <div class="form-group">
                    <label>Booking <span class="text-danger">*</span></label>
                    <select class="form-control" name="booking_id" id="booking_id">
                        @foreach($bookings as $booking)
                        <option value="{{$booking->id}}">{{$booking->id}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group">
                    <label>SOS By <span class="text-danger">*</span></label>
                    <select class="form-control" name="sos_by" id="sos_by">
                        @foreach($bookings as $booking)
                        <option value="{{$booking->user_id}}">{{$booking->user_id}}</option>
                        @endforeach
                    </select>

                </div>


                <div class="form-group">
                    <label>Longitude</label>
                    <input class="form-control" name="sos_longitude" />
                    <span class="form-text text-muted">From which location the sos was created</span>
                </div>
                <div class="form-group">
                    <label>Latitude</label>
                    <input class="form-control" name="sos_latitude" />
                    <span class="form-text text-muted">From which location the sos was created</span>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input class="form-control" name="sos_location" />
                    <span class="form-text text-muted">From which location the sos was created</span>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <input class="form-control" name="sos_reason" />
                    <span class="form-text text-muted">Reason for SOS</span>
                </div>
                <div class="form-group">
                    <label for="">Sos Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="sos_status" id="">
                        <option value="1">Ongoing</option>
                        <option value="2">Resolved</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>SOS Date</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" id="sos-date" name="sos_date" readonly="readonly"
                            placeholder="Select date" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-3">
        <div class="card card-custom sticky" data-sticky="true" data-margin-top="140" data-sticky-for="1023"
            data-sticky-class="stickyjs">
            <div class="card-body">
                {{-- <div class="form-group row">
                    <label class="col-6 col-form-label">Status</label>
                    <div class="col-6">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="status" {{ old('status', isset($getdata->status) ?
                                $getdata->status : '')=='active' ? 'checked':'' }} {{ (old('status') == 'on') ?
                                'checked':'' }}/>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div> --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="card-footer">
    <button type="submit" class="btn btn-primary mr-2">Submit</button>
    <button type="reset" class="btn btn-secondary">Cancel</button>
</div> --}}
<!-- /.card-body -->