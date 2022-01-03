@extends('frontend.layouts.app')
@section('title','Transfer Confirmation')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="transfer">
    @include('frontend.layouts.flash')
    <div class="card">
        <div class="card-body">
            <form action="{{route('transfer.complete')}}" method="POST" id="form">
                @csrf
                <input type="hidden" name="hash_value" value="{{$hash_value}}">

                <input type="hidden" name="to_phone" value="{{$to_account->phone}}">
                <input type="hidden" name="amount" value="{{$amount}}">
                <input type="hidden" name="description" value="{{$description}}">

                <div class="form-group">
                    <label for="" class="mb-0"><strong>From</strong></label>
                    <p class="mb-1 text-muted">{{$from_account->name}}</p>
                    <p class="mb-1 text-muted">{{$from_account->phone}}</p>
                </div>

                <div class="form-group">
                    <label for="" class="mb-0"><strong>To</strong></label>
                    <p class="mb-1 text-muted">{{$to_account->name}}</p>
                    <p class="mb-1 text-muted">{{$to_account->phone}}</p>
                </div>

                <div class="form-group">
                    <label for="" class="mb-0"><strong>Amount</strong></label>
                    <p class="mb-1 text-muted">{{number_format($amount)}} MMK</p>
                </div>

                <div class="form-group">
                    <label for="" class="mb-0"><strong>Description</strong></label>
                    <p class="mb-1 text-muted">{{$description}}</p>
                </div>

                <button class="btn btn-theme btn-block mt-5 confirm-btn">Confirm</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        $('.confirm-btn').on('click',function(e){
            e.preventDefault();

            Swal.fire({
                title: '<strong>Please fill the password.</strong>',
                icon: 'info',
                html: '<input type="password" class="form-control text-center password">',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonText:'Confirm',
                cancelButtonText:'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {

                    var password = $('.password').val();
                    $.ajax({
                        url : '/password-check?password='+password,
                        type : 'GET',
                        success : function(res){
                            // console.log(res);
                            if (res.status == 'success') {
                                $('#form').submit();

                            }else if (res.status == 'fail'){

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: res.message,
                                })

                            }
                        }
                    })
                }
            })
        })
    });
</script>

@endsection
