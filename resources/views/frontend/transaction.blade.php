@extends('frontend.layouts.app')
@section('title','Transaction')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="transaction">
    <h6 class="font-weight-bold">Filter <i class="fas fa-filter"></i></h6>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-group m-0 p-0">
                        <div class="input-group-prepend">
                          <label class="input-group-text p-1">Date</label>
                        </div>
                        <input type="text" class="form-control date" value="{{request()->date}}" placeholder="All">
                    </div>
                </div>

                <div class="col-6">
                    <div class="input-group m-0 p-0">
                        <div class="input-group-prepend">
                          <label class="input-group-text p-1">Type</label>
                        </div>
                        <select class="custom-select type">
                          <option value="">All</option>
                          <option value="1" @if (request()->type == 1)
                            selected
                          @endif>Income</option>
                          <option value="2" @if (request()->type == 2)
                            selected
                          @endif>Expense</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h6 class="font-weight-bold">Transactions</h6>
    <div class="infinite-scroll">
        @foreach ($transactions as $transaction)
        <a href="{{url('transaction/'.$transaction->trx_id)}}">
            <div class="card mb-2">
                <div class="card-body px-2 pb-0">
                    <div class="d-flex justify-content-between">
                        <h6>Trx ID : {{$transaction->trx_id}}</h6>
                            @if ($transaction->type == 2)
                                <p class="text-danger">- {{number_format($transaction->amount)}} <small>MMK</small></p>
                            @elseif ($transaction->type == 1)
                                <p class="text-success">+ {{number_format($transaction->amount)}} <small>MMK</small></p>
                            @endif
                    </div>
                    <p class="text-muted p-0">
                        @if ($transaction->type == 2)
                            <strong>To</strong>
                        @elseif ($transaction->type == 1)
                            <strong>From</strong>
                        @endif
                        {{$transaction->source ? $transaction->source->name : ''}}
                    </p>
                    <p class="text-muted p-0">{{Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s A')}}</p>
                </div>
            </div>
        </a>
        @endforeach
        {{$transactions->links()}}
    </div>
</div>
@endsection

@section('scripts')

<script>
    $('ul.pagination').hide();
    $(function() {
        var loading = "{{ asset('img/loading.gif') }}"
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: `<div class="text-center mt-3"><img class="center-block" src="${loading}" width="30px" alt="Loading..." /></div>`,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });

        $('.date').daterangepicker({
            "singleDatePicker": true,
            "autoApply": false,
            "autoUpdateInput": false,
            "locale": {
                "format": "YYYY-MM-DD",
            },
        });
        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));

            var date = $('.date').val();
            var type = $('.type').val();
            history.pushState(null,'',`?date=${date}&type=${type}`);
            window.location.reload();
        });

        $('.date').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');

            var date = $('.date').val();
            var type = $('.type').val();
            history.pushState(null,'',`?date=${date}&type=${type}`);
            window.location.reload();
        });

        $('.type').change(function(){
            var date = $('.date').val();
            var type = $('.type').val();
            history.pushState(null,'',`?date=${date}&type=${type}`);
            window.location.reload();

        });
    });
</script>

@endsection

