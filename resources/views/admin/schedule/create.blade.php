@extends('admin.layouts.main')
@section('content')
    <div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-primary">
        <h3><b>
             Create New Schedule
            </b></h3>
    </div>
    <style>
        img#cimg {
            max-height: 15em;
            width: 100%;
            object-fit: scale-down;
        }
    </style>
    <div class="row justify-content-center" style="margin-top:-2em;">
        <div class="col-lg-10 col-md-11 col-sm-11 col-xs-11">
            <div class="card rounded-0 shadow">
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="container-fluid">
                            <form action="{{ route('admin.schedule.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <laubel for="sched_date" class="control-label">Schedule Date</laubel>
                                    <input class="form-control form-control-sm rounded-0" type="datetime-local"
                                        id="sched_date" name="sched_date" required="required" value="" />
                                    @error('sched_date')
                                        <span role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <laubel for="sched_slot" class="control-label">Schedule Slot</laubel>
                                    <input class="form-control form-control-sm rounded-0" type="number" id="sched_slot"
                                        name="sched_slot" required="required" value="" />
                                    @error('sched_slot')
                                        <span role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-1 text-center">
                    <button type="submit" class="btn btn-primary btn-sm bg-gradient-primary rounded-0"><i
                            class="fa fa-save"></i>Save</button>
                    <a class="btn btn-light btn-sm bg-gradient-light border rounded-0"
                        href="{{ route('admin.schedules') }}"><i class="fa fa-angle-left"></i> Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
