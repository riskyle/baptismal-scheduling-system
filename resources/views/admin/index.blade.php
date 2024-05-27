@extends('admin.layouts.main')
@section('content')
    <h1>Welcome to Baptismal Scheduling - Management Site
    </h1>
    <hr>
    <style>
        #site-cover {
            width: 100%;
            height: 40em;
            object-fit: cover;
            object-position: center center;
        }
    </style>
    <center>
        <img src="{{ asset('simbahan.jpg') }}" alt="Image here" id="site-cover" class="img-fluid w-100">
    </center>
@endsection
