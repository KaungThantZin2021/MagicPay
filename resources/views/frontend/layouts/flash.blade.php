@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            {{$error}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        @endforeach
    </div>
@endif

{{-- without loop --}}

{{-- @if ($errors->has('fail'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{$errors->first('fail')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif --}}
