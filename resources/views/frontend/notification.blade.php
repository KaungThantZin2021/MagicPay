@extends('frontend.layouts.app')
@section('title','Notifications')
{{-- @section('icon-color','color:#5842e3  !important;') --}}
@section('content')
<div class="notification">
    <div class="infinite-scroll">
        @foreach ($notifications as $notification)
        <a href="{{url('notification/'.$notification->id)}}">
            <div class="card mb-2">
                <div class="card-body p-2">
                    <h6><i class="fas fa-bell @if (is_null($notification->read_at))
                        text-danger
                    @endif"></i> {{Illuminate\Support\Str::limit($notification->data['title'], 40)}}</h6>
                    <p class="mb-1">{{Illuminate\Support\Str::limit($notification->data['message'], 100)}}</p>
                    <small class="text-muted mb-1">{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s A')}}</small>
                </div>
            </div>
        </a>
        @endforeach
        {{$notifications->links()}}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
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
        });
    });
</script>
@endsection
