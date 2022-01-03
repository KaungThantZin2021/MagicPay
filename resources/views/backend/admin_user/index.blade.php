@extends('backend.layouts.app')
@section('title','Admin User')
@section('admin-user-active','mm-active')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Admin Users</div>
        </div>
    </div>
</div>

<div class="pt-3">
    <a href="{{route('admin.admin-user.create')}}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create Admin User</a>
</div>

<div class="content pt-3">
    <div class="card">
        <div class="card-body">
            <table class="table table-border Datatable">
                <thead>
                    <tr class="bg-light">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>IP</th>
                        <th>User Agent</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
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
                ajax: "/admin/admin-user/datatable/ssd",
                columns:[
                    {
                        data:"name",
                        name:"name"
                    },
                    {
                        data:"email",
                        name:"email"
                    },
                    {
                        data:"phone",
                        name:"phone"
                    },
                    {
                        data:"ip",
                        name:"ip",
                        sortable : false,
                        searchable : false
                    },
                    {
                        data:"user_agent",
                        name:"user_agent",
                        sortable : false,
                        searchable : false
                    },
                    {
                        data:"created_at",
                        name:"created_at"
                    },
                    {
                        data:"updated_at",
                        name:"updated_at"
                    },
                    {
                        data:"action",
                        name:"action",
                        sortable : false,
                        searchable : false
                    },
                ],
                order: [[ 6, "desc" ]]
            }
        );

        $(document).on('click','.delete',function(e){
            e.preventDefault();

            var id = $(this).data('id');
            // alert(id)
            Swal.fire({
                title: 'Are you sure, Do you want to delete?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url :'/admin/admin-user/'+id,
                        type :'DELETE',
                        success:function(){
                            table.ajax.reload();
                        }
                    });
                }
            })
        })
} );
</script>
@endsection
