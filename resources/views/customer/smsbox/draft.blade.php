@extends('layouts.customer')

@section('title','Draft | SmsBox')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('customer.draft')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('customer.smsbox.inbox')}}">{{trans('customer.smsbox')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('customer.draft')}}</li>
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
            <!-- /.card -->

                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{trans('customer.draft')}}</h3>

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
                                        data-action="{{route('customer.smsbox.move.draft')}}"
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
                                        <th>{{trans('customer.to')}}</th>
                                        <th>{{trans('customer.message')}}</th>
                                        <th>{{trans('customer.schedule_at')}}</th>
                                        <th>{{trans('customer.action')}}</th>
                                    </td>
                                </thead>
                                <tbody>
                                @foreach($drafts as $message)
                                    <tr>
                                        <td>
                                            <div class="icheck-primary">
                                                <input class="check-single" data-id="{{$message->id}}" type="checkbox"
                                                       id="check-{{$message->id}}">
                                                <label for="check-{{$message->id}}"></label>
                                            </div>
                                        </td>
                                        <td class="mailbox-name">{{$message->formatted_number_from}}</td>
                                        <td class="mailbox-name">{{$message->formatted_number_to}}</td>
                                        <td class="mailbox-subject">{{$message->body}}</td>
                                        <td class="mailbox-subject">{{$message->schedule_datetime}}</td>
                                        <td class="mailbox-name d-flex">
                                            <a class="btn btn-primary btn-sm" href="{{route('customer.smsbox.compose',['draft'=>$message->id])}}" title="Resume"><i class="fas fa-file"></i></a>
                                            <button
                                                data-message="{{trans('customer.messages.delete_draft')}} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.delete_draft_nb')}}</span>"
                                                data-action="{{route('customer.smsbox.draft.delete')}}"
                                                data-input='{"id":"{{$message->id}}"}'
                                                data-toggle="modal" data-target="#modal-confirm"
                                                id="deleteDraft" class="btn btn-danger btn-sm ml-2"
                                                type="button" title="Delete"><i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
