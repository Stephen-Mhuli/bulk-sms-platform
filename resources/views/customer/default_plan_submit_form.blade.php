@extends('layouts.customer')

@section('title') Billings @endsection

@section('content')
    <form id="form" action="{{route('paymentgateway::process')}}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{$plan->id}}">
    </form>
@endsection
@section('extra-scripts')
    <script>
        window.onload = function(){
            $('#form').submit();
        }
    </script>
@endsection

