@extends('backend.layouts.app')
@section('title','Wallet')
@section('wallet-active','mm-active')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Wallets</div>
        </div>
    </div>
</div>

<div class="pt-3">
    <a href="{{url('admin/wallet/add/amount')}}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Amount</a>
    <a href="{{url('admin/wallet/reduce/amount')}}" class="btn btn-danger"><i class="fas fa-minus-circle"></i> Reduce Amount</a>
</div>

<div class="content pt-3">
    <div class="card">
        <div class="card-body">
            <table class="table table-border Datatable">
                <thead>
                    <tr class="bg-light">
                        <th>Account Number</th>
                        <th>Account Person</th>
                        <th>Amount</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($users as $user)

                    @endforeach
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('.Datatable').DataTable(
            {
                processing: true,
                serverSide: true,
                ajax: "/admin/wallet/datatable/ssd",
                columns:[
                    {
                        data:"account_number",
                        name:"account_number"
                    },
                    {
                        data:"account_person",
                        name:"account_person"
                    },
                    {
                        data:"amount",
                        name:"amount"
                    },
                    {
                        data:"created_at",
                        name:"created_at"
                    },
                    {
                        data:"updated_at",
                        name:"updated_at"
                    },
                ],
                order: [[ 4, "desc" ]]
            }
        );
    } );
</script>
@endsection
