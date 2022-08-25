@extends('layouts.admin')

@section('title') Addon @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content mt-4">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.addon.import')</h2>
                        <div class="float-right">

                                <a class="btn btn-info"
                                   href="{{route('admin.addon.index')}}">@lang('admin.form.button.back')</a>

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <form action="{{route('admin.addon.import')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="choose_file">@lang('Choose addon zip file')</label>
                                        <input type="file" name="addon" accept=".zip">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary"
                                                type="submit">@lang('admin.form.button.submit')</button>
                                    </div>
                                </div>
                            </div>
                        </form>

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

@endsection

