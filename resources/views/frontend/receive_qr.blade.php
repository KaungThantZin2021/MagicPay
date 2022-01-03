@extends('frontend.layouts.app')
@section('title','Receive QR')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="receive-qr">
    <div class="card">
        <div class="card-body">
            <p class="text-center font-weight-bold">Scan QR to pay me</p>
            <div class="text-center">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(240)->color(88, 66, 227)->generate($authUser->phone)) !!} ">
            </div>
            <p class="text-center font-weight-bold">{{$authUser->name}}</p>
            <p class="text-center">{{$authUser->phone}}</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function(){

        });
    </script>

@endsection
