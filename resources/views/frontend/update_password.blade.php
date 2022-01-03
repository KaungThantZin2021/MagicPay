@extends('frontend.layouts.app')
@section('title','Update Password')
{{-- @section('icon-color','color:#5842e3  !important;') --}}
@section('content')
    <div class="update-password mt-5">
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('update-password')}}" method="POST">
                    @csrf

                    <div class="text-center">
                        <img src="{{asset('img/update-password.png')}}" alt="">
                    </div>

                    <div class="form-group">
                        <label for="">Old Password</label>
                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror">
                        @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-4">Update Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script>
        $(document).ready(function(){


        })
    </script> --}}
@endsection
