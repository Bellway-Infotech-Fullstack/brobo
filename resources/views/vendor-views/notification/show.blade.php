@extends('layouts.vendor.app')

@section('title','Notification')

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">Notification</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>{{$notification->title}}</span></h5>
                    </div>
                    <div class="card-body">
                        {{$notification->message}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
