@extends('layouts.customer')

@section('title') {{trans('customer.developer')}} @endsection

@section('extra-css')
    <style>
        .c-pointer {
            cursor: pointer;
        }
    </style>

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('customer.developer')}}</h2>
                        <div class="float-right">

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 cpl-md-12">
                                <a target="_blank" href="https://documenter.getpostman.com/view/15947626/UzJLPGYF" class="btn btn-primary float-right">{{trans('API Documentation')}}</a>
                            </div>
                            <div class="col-md-6 mx-auto">
                                <form action="{{route('customer.authorization.token.store')}}" method="post" id="apiForm">
                                    @csrf
                                    <div class="form-group">
                                            <label for="">Access Key</label>
                                            <div class="input-group date" id="reservationdatetime"
                                                 data-target-input="nearest">
                                                <input class="form-control" type="text"
                                                       value="{{isset($authorization_token->access_token)?$authorization_token->access_token:''}}" id="accessKey">
                                                <div class="input-group-append" data-target="#reservationdatetime"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i onclick="myFunction()" onmouseout="outFunc()"
                                                           class="fa fa-copy c-pointer"></i>
                                                                <i  class="fas fa-sync-alt ml-3 c-pointer" id="refresh"></i>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
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
    <script>
        $(document).on('click', '#refresh', function (e){
            $('#apiForm').submit();
        })
        function myFunction() {
            var copyText = document.getElementById("accessKey");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            var tooltip = document.getElementById("keyToolTip");
            tooltip.innerHTML = "Copied: " + copyText.value;
        }

        function outFunc() {
            var tooltip = document.getElementById("keyToolTip");
            tooltip.innerHTML = "Copy to clipboard";
        }
    </script>
@endsection

