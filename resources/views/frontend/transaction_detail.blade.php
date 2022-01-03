@extends('frontend.layouts.app')
@section('title','Transaction Detail')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="transaction-detail">
    <div class="card mb-5">
        <div class="card-body">
            <div class="text-center mb-3">
                <img src="{{asset('img/checked.png')}}" alt="">
            </div>

            @if ($transaction->type == 2)
                <h5 class="text-center text-danger mb-4">- {{number_format($transaction->amount)}} <small>MMK</small></h5>
            @elseif ($transaction->type == 1)
                <h5 class="text-center text-success mb-4">+ {{number_format($transaction->amount)}} <small>MMK</small></h5>
            @endif

            @if (session('transfer_success'))
                <div class="alert alert-success" role="alert">
                    <p class="text-center m-0">{{session('transfer_success')}}</p>
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <p class="mb-0 text-muted">Trx ID</p>
                <p class="mb-0">{{$transaction->trx_id}}</p>
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p class="mb-0 text-muted">Reference Number</p>
                <p class="mb-0">{{$transaction->ref_no}}</p>
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p class="mb-0 text-muted">Type</p>
                @if ($transaction->type == 1)
                    <p class="badge badge-pill badge-success mb-0">Income</p>
                @elseif ($transaction->type == 2)
                    <p class="badge badge-pill badge-danger mb-0">Expense</p>
                @endif
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p class="mb-0 text-muted">Amount</p>
                @if ($transaction->type == 2)
                    <p class="text-center text-danger mb-0">- {{number_format($transaction->amount)}} <small>MMK</small></p>
                @elseif ($transaction->type == 1)
                    <p class="text-center text-success mb-0">+ {{number_format($transaction->amount)}} <small>MMK</small></p>
                @endif
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p class="mb-0 text-muted">Date & Time</p>
                <p class="mb-0">{{Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s A')}}</p>
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p class="text-muted mb-0">
                    @if ($transaction->type == 2)
                        To
                    @elseif ($transaction->type == 1)
                        From
                    @endif
                </p>
                <p class="mb-0">{{$transaction->source ? $transaction->source->name : '-'}}</p>
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p class="mb-0 text-muted">Description</p>
                <p class="mb-0">{{$transaction->description}}</p>
            </div>

        </div>
    </div>
</div>
@endsection

