@extends('frontend.layouts.app')
@section('title','Scan & Pay Form')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="transfer">
    <div class="card">
        <div class="card-body">
            <form action="{{route('scan-and-pay.confirm')}}" method="GET" id="transfer-form">
                {{-- @csrf --}}

                {{-- hash_value --}}
                <input type="hidden" name="hash_value" class="hash_value" value="">

                <div class="form-group">
                    <label for="">From</label>
                    <p class="mb-1 text-muted">{{$from_account->name}}</p>
                    <p class="mb-1 text-muted">{{$from_account->phone}}</p>
                </div>

                <div class="form-group">
                    <label for="">To</label>
                    <p class="mb-1 text-muted">{{$to_account->name}}</p>
                    <p class="mb-1 text-muted">{{$to_account->phone}}</p>
                </div>

                {{-- Phone --}}
                <input type="hidden" name="to_phone" class="to_phone @error('to_phone') is-invalid @enderror" value="{{$to_account->phone}}">
                @error('to_phone')
                        <span class="invalid-feedback">{{$message}}</span>
                @enderror


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
