@extends('layouts.customer')

@section('title', 'Device Edit')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.device_edit')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.device.index')}}">@lang('customer.back')</a>
                    </div>
                    <form method="post" role="form"  action="{{route('customer.device.update',[$device])}}">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            @include('customer.device.form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('customer.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-scripts')

@endsection

