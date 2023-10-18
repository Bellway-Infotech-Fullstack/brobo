@extends('layouts.admin.app')
@section('title','Accoutn transaction information')
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('messages.dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{__('messages.account_transaction')}}</li>
        </ol>
    </nav>

    <!-- Page Heading -->
    <div class="d-sm-flex row align-items-center justify-content-between mb-2">
        <div class="col-md-6"> 
             <h4 class=" mb-0 text-black-50">{{__('messages.account_transaction')}} {{__('messages.information')}}</h4>
        </div>
    </div>
    <div class="row mt-3">
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="h3 mb-0  ">{{$account_transaction->vendor?__('messages.vendor'):__('messages.deliveryman')}} {{__('messages.info')}}</h3>
                </div>
                <div class="card-body">
                    <div class="col-md-8 mt-2">
                        <h4>{{__('messages.name')}}: {{$account_transaction->vendor ? $account_transaction->vendor->names() : ''}}</h4>
                        <h6>{{__('messages.phone')}}  : {{$account_transaction->vendor ? $account_transaction->vendor->phone : ''}}</h6>
                        <h6>{{__('messages.collected_cash')}} : {{\App\CentralLogics\Helpers::format_currency($account_transaction->vendor ? $account_transaction->vendor->wallet->collected_cash : 0)}}</h6>
                    </div>
                </div>
            </div>
        </div>
     
        <div class="col-md-6">
            {{-- {{ $wr }} --}}
           
            <div class="card">
                <div class="card-header">
                    <h3 class="h3 mb-0  ">{{__('messages.transaction')}} {{__('messages.information')}} </h3>
                </div>
                <div class="card-body">
                    <h6>{{__('messages.amount')}} : {{\App\CentralLogics\Helpers::format_currency($account_transaction->amount)}}</h6>
                    <h6 class="text-capitalize">{{__('messages.time')}} : {{$account_transaction->created_at->format('Y-m-d '.config('timeformat'))}}</h6>
                    <h6>{{__('messages.method')}} : {{$account_transaction->method}}</h6>
                    <h6>{{__('messages.reference')}} : {{$account_transaction->ref}}</h6>
                </div>
            </div>
          
       
       
        </div>
    
     

    </div>
 
</div>

@endsection

@push('script')

@endpush
