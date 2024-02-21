@extends('layouts.admin.app')

@section('title','Returns and Refunds Policy')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header_ pb-4">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">Returns and Refunds Policy</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.business-settings.refunds-and-returns-policy')}}" method="post" id="tnc-form">
                    @csrf
                    <div class="form-group">
                        <label class="input-label">Returns Policy</label>
                        <textarea class="ckeditor form-control" name="returns_policy">{!! $returns_policy_data['value'] !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="input-label">Replacement Policy</label>
                        <textarea class="ckeditor form-control" name="replacement_policy">{!! $replacement_policy_data['value'] !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="input-label">Cancellation Policy</label>
                        <textarea class="ckeditor form-control" name="cancellation_policy">{!! $cancellation_policy_data['value'] !!}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
@endpush
