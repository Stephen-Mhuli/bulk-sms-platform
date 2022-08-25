@extends('layouts.customer')

@section('title','Create Device')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('Device')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.device.index')}}">@lang('customer.back')</a>
                    </div>
                    <form method="post" role="form"  action="{{route('customer.device.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @include('customer.device.form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('customer.submit')</button>
                        </div>
                    </form>
                </div>


            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('extra-scripts')

@endsection

