@extends('backend.layouts.app')
@section('title','Admin User Create')
@section('admin-user-active','mm-active')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Edit Admin User</div>
        </div>
    </div>
</div>

@include('backend.layouts.flash')

<div class="content pt-3">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.admin-user.update',$admin_user->id)}}" method="POST" id="update">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" class="form-control" value="{{$admin_user->name}}">
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control" value="{{$admin_user->email}}">
                </div>
                <div class="form-group">
                    <label for="">Phone</label>
                    <input type="number" name="phone" class="form-control" value="{{$admin_user->phone}}">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="d-flex justify-content-center">
                    <a href="" class="btn btn-secondary mr-2 back-btn">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\UpdateAdminUser','#update') !!}

<script>
    $(document).ready(function(){
        // $('.back-btn').on('click',function(){
        //     window.history.go(-1);
        //     return false;
        // })
    });
</script>
@endsection
