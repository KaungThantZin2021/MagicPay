@extends('backend.layouts.app')
@section('title','Add amount')
@section('wallet-active','mm-active')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-plus icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Add Amount</div>
        </div>
    </div>
</div>

<div class="content pt-3">
    <div class="card">
        <div class="card-body">

                @include('backend.layouts.flash')

            <form action="{{url('admin/wallet/add/amount/store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <select name="user_id" value="" class="form-control user_id @error('user_id') is-invalid @enderror">
                        <option value="">-- Please Choose --</option>
                        @foreach ($users as $user )

                        <option value="{{$user->id}}">{{$user->name}} ({{$user->phone}})</option>

                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="invalid-feedback">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="">Amount</label>
                    <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{old('amount')}}">
                    @error('amount')
                        <span class="invalid-feedback">{{$message}}</span>
                     @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control" value="{{old('description')}}"></textarea>
                </div>

                <div class="d-flex justify-content-center">
                    <a href="" class="btn btn-secondary mr-2 back-btn">Cancel</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Amount</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.user_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Please choose a user--",
            allowClear: true
        });
    } );
</script>
@endsection
