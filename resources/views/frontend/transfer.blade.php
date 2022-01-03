@extends('frontend.layouts.app')
@section('title','Transfer')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="transfer">
    <div class="card">
        <div class="card-body">
            <form action="{{route('transfer.confirm')}}" method="GET" id="transfer-form">
                {{-- @csrf --}}

                <input type="hidden" name="hash_value" class="hash_value" value="">

                <div class="form-group">
                    <label for="">From</label>
                    <p class="mb-1 text-muted">{{$authUser->name}}</p>
                    <p class="mb-1 text-muted">{{$authUser->phone}}</p>
                </div>

                <div class="form-group">
                    <label for="">To <span class="to_account_info text-success font-weight-bold"></span>
                        <span class="to_account_info_fail text-danger"></span></label>

                    <div class="input-group mb-3">
                        <input type="number" name="to_phone" class="form-control to_phone @error('to_phone') is-invalid @enderror" value="{{old('to_phone')}}" placeholder="Phone Number" autocomplete="off">
                        <div class="input-group-append">
                          <span class="input-group-text btn verify-btn"><i class="fas fa-check"></i></span>
                        </div>
                        @error('to_phone')
                        <span class="invalid-feedback">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                <div class="form-group">
                    <label for="">Amount</label>
                    <input type="number" name="amount" class="form-control amount @error('amount') is-invalid @enderror" value="{{old('amount')}}" placeholder="MMK">
                    @error('amount')
                        <span class="invalid-feedback">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control description" value="{{old('description')}}"></textarea>
                </div>

                <button class="btn btn-theme btn-block mt-4 submit-btn">Continue</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function(){
            $('.verify-btn').on('click' , function(){

                var phone=$('.to_phone').val();

                $.ajax({
                    url : '/to-account-verify?phone='+phone,
                    type : 'GET',
                    success : function(res){
                        // console.log(res);
                        if (res.status == 'success') {

                            $('.to_account_info').text(res.data['name']);
                            $('.to_account_info_fail').text('');

                        }else if (res.status == 'fail'){

                            $('.to_account_info_fail').text('('+res.message+')');
                            $('.to_account_info').text('');

                        }else if (res.status == 'same_phone'){

                            $('.to_account_info_fail').text('('+res.message+')');
                            $('.to_account_info').text('');

                        }
                    }
                })
            });


            $('.submit-btn').on('click',function(e){
                e.preventDefault();

                var to_phone = $('.to_phone').val();
                var amount = $('.amount').val();
                var description = $('.description').val();

                $.ajax({
                    url : `/transfer-hash?to_phone=${to_phone}&amount=${amount}&description=${description}`,
                    type : 'GET',
                    success : function(res){
                        if (res.status == 'success') {
                            $('.hash_value').val(res.data);
                            $('#transfer-form').submit();
                        }
                    }
                });
            });
        });
    </script>

@endsection
