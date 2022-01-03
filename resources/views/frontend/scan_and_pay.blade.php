@extends('frontend.layouts.app')
@section('title','Scan & Pay')
{{-- @section('icon_color','color:#5842e3 !important;') --}}
@section('content')
<div class="scan-and-pay">

    @include('frontend.layouts.flash')

    <div class="card">
        <div class="card-body text-center">
            <div class="text-center">
                <img src="{{asset('img/scan-and-pay.png')}}" width="220px">
            </div>
            <p class="font-weight-bold">Click button,put QR code in the frame and pay</p>
            <button type="button" class="btn btn-theme btn-sm" data-toggle="modal" data-target="#myModal">Scan</button>

  <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Scan here</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <video id="scanner" width="100%" height="200px"></video>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('frontend/js/qr-scanner.umd.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            var videoElem = document.getElementById('scanner');
            const qrScanner = new QrScanner(videoElem, function(result){

                console.log(result);

                if (result) {
                    qrScanner.stop();
                    $('#myModal').modal('hide');

                    var to_phone = result;
                    window.location.replace(`scan-and-pay-form?to_phone=${to_phone}`);
                }
            });


            $('#myModal').on('show.bs.modal', function (event) {
                qrScanner.start();
            });

            $('#myModal').on('hidden.bs.modal', function (event) {
                qrScanner.stop();
            });


        });

    </script>

@endsection
