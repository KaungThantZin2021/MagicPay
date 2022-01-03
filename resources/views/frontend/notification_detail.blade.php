@extends('frontend.layouts.app')
@section('title','Notification Detail')
{{-- @section('icon-color','color:#5842e3  !important;') --}}
@section('content')
<div class="notification-detail">
    <div class="card">
        <div class="card-body text-center">

            <img src="{{asset('img/Notifications.png')}}" width="220px" alt="">

            <h6>{{$notification->data['title']}}</h6>
            <p class="text-muted mb-2">{{$notification->data['message']}}</p>
            <p class="mb-2">{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s A')}}</p>
            <a href="{{$notification->data['web_link']}}" class="btn-theme btn-sm">Continue</a>
        </div>
    </div>
</div>
@endsection

