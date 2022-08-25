@extends('layouts.customer')

@section('title','Inbox | SmsBox')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('customer.inbound')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('customer.smsbox.inbox')}}">{{trans('customer.smsbox')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('customer.inbound')}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{route('customer.smsbox.compose')}}" class="btn btn-primary btn-block mb-3">{{trans('customer.compose')}}</a>

                @include('customer.smsbox.common')
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{trans('customer.inbound')}}</h3>

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
                            <button id="checkbox-toggle" data-checked="false" type="button" class="btn btn-default btn-sm checkbox-toggle"><i
                                    class="far fa-square"></i>
                            </button>
                            <div class="btn-group">
                                <button data-message="{{trans('customer.messages.move_trash')}}"
                                        data-action="{{route('customer.smsbox.inbox.trash')}}"
                                        data-input=''
                                        id="move-trash"
                                        data-toggle="modal" data-target="#modal-confirm"
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
                                <th>{{trans('customer.to')}}</th>
                                <th>{{trans('customer.message')}}</th>
                                <th>{{trans('customer.received_at')}}</th>
                                <th>{{trans('customer.address')}}</th>
                                </td>
                                </thead>
                                <tbody>
                                @foreach($messages as $message)
                                    <tr data-message-id="{{$message->id}}"
                                        title="{{$message->read=='yes'?'Already Read':'Haven\'t Read'}}"
                                        class="{{$message->read=='yes'?'smsbox-read':'smsbox-unread'}}">
                                        <td>
                                            <div class="icheck-primary">
                                                <input class="check-single" data-id="{{$message->id}}" type="checkbox"
                                                       value="" id="check-{{$message->id}}">
                                                <label for="check-{{$message->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="mailbox-name">{{$message->formatted_number_from}}</td>
                                        <td class="mailbox-name">{{isset($device_name[$message->formatted_number_to])?$device_name[$message->formatted_number_to]:''}}({{isset($device_model[$message->formatted_number_to])?$device_model[$message->formatted_number_to]:''}})</td>
                                        <td class="mailbox-subject">
                                            <div class="show-more">
                                                {{$message->body}}
                                            </div>
                                        </td>
                                        <td class="mailbox-date">{{$message->time}}</td>
                                        <td>{{isset($contact_address[$message->formatted_number_from])?$contact_address[$message->formatted_number_from]:''}}</td>

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
    <script !src="">
        "use strict";
        $(document).on('click', '.smsbox-unread', function (e) {
            e.preventDefault();
            $(this).removeClass('smsbox-unread').addClass('smsbox-read').attr('title', 'Already Read');
            const id = $(this).attr('data-message-id');
            $.ajax({
                method: 'post',
                url: '{{route('customer.smsbox.inbox.change-status')}}',
                data: {_token: '{{csrf_token()}}', id: id, status: 'read'},
                success: function (res) {
                    if (res.status == 'success') {
                        notify('success', res.message);
                    } else {
                        notify('danger', res.message);
                    }
                }
            })
        })

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
    </script>
@endsection
