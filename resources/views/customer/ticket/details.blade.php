@extends('layouts.customer')

@section('title') {{trans('admin.ticket.ticket_details')}} @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');

        body {
            background: #EEEEEE;
            font-family: 'Roboto', sans-serif
        }

        .card {
            width: 100%;
            border: none;
            border-radius: 15px
        }

        .adiv {
            background: #04CB28;
            border-radius: 15px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            font-size: 12px;
            height: 46px
        }

        .chat {
            border: none;
            background: #E2FFE8;
            font-size: 15px;
            border-radius: 20px
        }

        .bg-white {
            border: 1px solid #E7E7E9;
            font-size: 15px;
            border-radius: 20px
        }

        .myvideo img {
            border-radius: 20px
        }

        .dot {
            font-weight: bold
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #F0F0F0;
            font-size: 12px
        }

        .form-control:focus {
            box-shadow: none
        }

        .form-control::placeholder {
            font-size: 14px;
            color: #C4C4C4
        }

        .admin_message {
            margin: 0 0 0 auto;
        }

        .custom_image {
            float: right;
            position: relative;
            bottom: -39px;
            right: 10px;
            cursor: pointer;
        }

        .document_image {
            width: 180px;
            height: 100px;
        }

        .document_image img {
            width: 100%;
            height: 100%;
        }
        .c-pointer{
            cursor: pointer;
        }
        .card_content{
            box-shadow: 0 0 1px rgb(0 0 0 / 0%), 0 0px 0px rgb(0 0 0 / 0%) !important;
            overflow-y: scroll;
            height: 370px;
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
                        <h2 class="card-title">{{trans('customer.ticket.ticket_details')}}</h2>
                        <div class="float-right">

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{route('customer.ticket.reply')}}" id="messageForm" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{isset($ticket)?$ticket->id:''}}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8 col-12">
                                    <div class="container d-flex justify-content-center">
                                        <div class="card">
                                            <div class="d-flex flex-row justify-content-between p-3 adiv text-white">
                                                <i class="fas fa-chevron-left"></i> <span
                                                    class="pb-3">Ticket Conversation</span> <i class="fas fa-times c-pointer"></i></div>
                                            <div class="card card_content">
                                            @foreach($conversations as $conversation)
                                                @if($conversation->sent_status=='customer')
                                                    @if($conversation->document)
                                                        <div class="d-block">
                                                            <div class="document_image float-right"
                                                                 style="margin-right: 45px">
                                                                <img
                                                                    src="{{asset('uploads/'.$conversation->document)}}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex flex-row p-3 admin_message">
                                                        <div class="bg-white mr-2 p-3"><span class="text-muted">
                                                                {{$conversation->description}}
                                                            </span>
                                                        </div>
                                                        <img
                                                            src="https://img.icons8.com/color/48/000000/circled-user-male-skin-type-7.png"
                                                            width="30" height="30">
                                                    </div>
                                                @elseif($conversation->sent_status=='admin')
                                                    @if($conversation->document)
                                                        <div class="d-block">
                                                            <div class="document_image" style="margin-left: 45px;">
                                                                <a target="_blank"
                                                                   href="{{route('customer.ticket.download',['id'=>$conversation->id,'file'=>$conversation->document])}}">
                                                                    <img
                                                                        src="{{asset('uploads/'.$conversation->document)}}"
                                                                        alt="">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex flex-row p-3">
                                                        <img
                                                            src="https://img.icons8.com/color/48/000000/circled-user-female-skin-type-7.png"
                                                            width="30" height="30">
                                                        <div class="chat ml-2 p-3">
                                                            {{$conversation->description}}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            </div>
                                            <div class="form-group px-3">
                                                <label class="custom_image" for="image"><i
                                                        class="fa fa-link"></i></label>
                                                <textarea class="form-control textBoxClass" name="description" rows="5"
                                                          placeholder="Type your message"></textarea>
                                                <input id="image" name="document" type="file" class="form-control"
                                                       style="display: none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
        $(".textBoxClass").keypress(function (e) {
            // if the key pressed is the enter key
            if (e.which == 13) {
                $('#messageForm').submit();
            }
        });

        $('.fa-times').on('click', function (e){
            location.href='{{route('customer.ticket.index')}}';
        });
    </script>
@endsection


