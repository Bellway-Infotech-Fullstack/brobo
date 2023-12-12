@extends('layouts.admin.app')

@section('title','Update Coupon')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> Faq {{__('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.faq.faq-update',[$faq['id']])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Question <small style="color: red"> * </small></label>
                                <input type="text" name="question" class="form-control" placeholder="Question" value="{{$faq['question']}}" required>
                            </div>
                        </div>                             
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">Answer <small style="color: red"> * </small></label>
                                <textarea name="answer" class="form-control ckeditor" required>{{$faq['answer']}}</textarea>
                            </div>
                        </div>                             
                    </div>
                    
                    <button type="submit" class="btn btn-primary">{{__('messages.update')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">

        $(document).on('ready', function () {
            $('.ckeditor').ckeditor();
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
        });

       
    </script>
@endpush
