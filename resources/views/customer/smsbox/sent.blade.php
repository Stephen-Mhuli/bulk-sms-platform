@extends('layouts.customer')

@section('title','Sent | SmsBox')

@section('extra-css')

    <style>
        .mailbox-fail {
            color: red;
        }
    </style>
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('customer.outbound')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{route('customer.smsbox.inbox')}}">{{trans('customer.smsbox')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('customer.outbound')}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{route('customer.smsbox.compose')}}"
                   class="btn btn-primary btn-block mb-3">{{trans('customer.compose')}}</a>

                @include('customer.smsbox.common')
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{trans('customer.outbound')}}</h3>

                        <div class="card-tools d-none">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="{{trans('customer.search_mail')}}">
                                <div class="input-group-append">
                                    <div class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <button data-checked="false" id="checkbox-toggle" type="button"
                                    class="btn btn-default btn-sm checkbox-toggle"><i
                                    class="far fa-square"></i>
                            </button>
                            <div class="btn-group">
                                <button data-message="{{trans('customer.messages.move_trash')}}"
                                        data-action="{{route('customer.smsbox.sent.trash')}}"
                                        data-input=''
                                        data-toggle="modal" data-target="#modal-confirm"
                                        id="move-trash"
                                        type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                            <!-- /.btn-group -->
                            <button type="button" class="d-none btn btn-default btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                            <div class="float-right d-none">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fas fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fas fa-chevron-right"></i></button>
                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.float-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <thead>
                                <td>
                                <th>{{trans('customer.from')}}</th>
                                <th>{{trans('customer.number')}}</th>
                                <th>{{trans('customer.message')}}</th>
                                <th>{{trans('customer.schedule_at')}}</th>
                                </td>
                                </thead>
                                <tbody>
                                @foreach($messages as $message)
                                    <tr>
                                        <td>
                                            <div class="icheck-primary">
                                                <input class="check-single" data-id="{{$message->id}}" type="checkbox"
                                                       id="check-{{$message->id}}">
                                                <label for="check-{{$message->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="mailbox-name">
                                            <div class='show-more'>
                                                {{isset($device_name[$message->formatted_number_from])?$device_name[$message->formatted_number_from]:''}}({{isset($device_model[$message->formatted_number_from])?$device_model[$message->formatted_number_from]:''}})
                                            </div>
                                        </td>
                                        <td class="mailbox-name">
                                            <div class='show-more'>
                                                {{$message->formatted_number_to}}
                                            </div>
                                        </td>
                                        <td class="mailbox-subject">
                                            <div class="show-more-message" style="min-width: 200px">
                                                {{$message->body}}
                                            </div>
                                        </td>
                                        <td class="mailbox-subject">{{$message->schedule_datetime}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="float-right"> {{$messages->links("pagination::bootstrap-4")}} </div>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
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
    <script src="{{asset('js/readmore.min.js')}}"></script>
    <script !src="">
        "use strict";
        $('#checkbox-toggle').on('click', function (e) {
            e.preventDefault();
            if ($(this).attr('data-checked') == 'false') {
                $(this).attr('data-checked', 'true');
                $(this).find('i').removeClass('fa-square').addClass('fa-check-square');
            } else {
                $(this).attr('data-checked', 'false');
                $(this).find('i').addClass('fa-square').removeClass('fa-check-square');
            }

            $('.check-single').click();
        })

        $('#move-trash').on('click', function (e) {
            const classes = document.getElementsByClassName("check-single");
            let totalIds = [];
            let ids = [];
            for (var i = 0; i < classes.length; i++) {
                if ($(classes.item(i)).is(':checked'))
                    ids[i] = $(classes.item(i)).attr('data-id');
            }
            totalIds['ids'] = ids;
            $(this).attr('data-input', JSON.stringify(Object.assign({}, totalIds))).modal('show');

        });

        $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20});
        $(".show-more-message").css('overflow', 'hidden').readmore({collapsedHeight: 20});
    </script>

@endsection

