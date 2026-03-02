<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <title>Timer</title>
</head>

<body class="px-3 pt-5 bg-primary d-flex justify-content-center align-items-center" style="height: 650px;">
    <div class="container-fluid p-1 py-4 bg-light shadow ">
        <div class="container-fluid d-flex justify-content-center align-items-center fs-3">
            Timer Ayo<span class="text-primary">Silat</span>
        </div>

        @php
            use App\Setting;
            $btnStatus = Setting::where('arena', $arena)->first();
        @endphp
        <div class="contaner-fluid bg-danger-subtle text-danger text-center d-none" id="stopwatch">Tekan Stopwatch!
        </div>
        <div class="container-fluid d-flex justify-content-center my-4 px-1">
            @if ($btnStatus->status === "pause")
                <button id="resume" class="btn btn-warning mt-2 me-1 px-3 py-3 fs-1">
                    Resume
                </button>
            @else
                <button id="pause" class="btn btn-primary mt-2 me-1 px-3 py-3 fs-1">
                    Pause
                </button>
            @endif
            <button @if($btnStatus->status === "start" || $btnStatus->status === "pause") disabled @endif id="start"
                class="btn btn-success mt-2 px-3 py-3 fs-1">Start</button>
            <button id="stop" class="btn btn-danger mt-2 ms-1 px-3 py-3 fs-1">Stop</button>
        </div>

        <div class="container-fluid px-4 mb-4">
            <select class="custom-select w-100 p-0 border-3 border-primary fs-5" id="input-continent"
                style="height: 50px;">
                <option value="1">Babak 1</option>
                <option value="2">Babak 2</option>
                <option value="3">Babak 3</option>
            </select>
        </div>
        @if($btnStatus->status === "")

            <div class="continer-fluid ">
                <div class="row mx-3">
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text">Menit:</span>
                            <input type="number" value="" id="menitset" class="form-control form-control-lg">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <span class="input-group-text">Detik:</span>
                            <input type="number" value="" id="detikset" class="form-control form-control-lg">
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="container-fluid mt-5 ">
            <button data-bs-toggle="modal" data-bs-target="#resetModal"
                class="btn w-100 btn-primary mt-2 ms-1 px-3 py-3 fs-1">Reset Score</button>
        </div>

        <div class="modal fade" id="timerModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Setting Timer</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        Timer
                        <input type="time" class="form-control" placeholder="Timer">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary fw-bold">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="resetModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reset Modal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin Reset Score?
                        {{-- <input type="time" class="form-control" placeholder="Timer"> --}}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger fw-bold" id="reset">Reset</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your HTML code here -->

    <div class="d-none" id="arena" data-arena="{{$arena}}"></div>

    <!-- Include jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.min.js') }}"></script>

    <!-- Your script with the AJAX call -->
    <script>
        jQuery(document).ready(function ($) {
            function reload() {
                location.reload()
                // setInterval(() => {
                //     location.reload();
                // }, 800);
            }
            const arenaId = $('#arena').data('arena');
            // var menit = $('#menitset').prop('value');
            // var detik = $('#detikset').prop('value');
            function start() {
                var menit = $('#menitset').val();
                var detik = $('#detikset').val();
                console.log('trigger button start');
                $.ajax({
                    url: '/timeradmin/?tipe=start&arena=' + arenaId + '&menit=' + menit + '&detik=' + detik + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response.data);
                        // $('#stopwatch').removeClass('d-none');
                        setTimeout(function () {
                            reload();
                        }, 1000)
                    }
                });
            }
            function pause() {
                console.log('trigger button pause');
                $.ajax({
                    url: '/timeradmin/?tipe=pause&arena=' + arenaId + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response.data);
                        reload();
                    }
                });
            }
            function reset() {
                $.ajax({
                    url: '/timeradmin/?tipe=reset&arena=' + arenaId + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response.data);
                        reload();
                    }
                });
            }
            function resume() {
                console.log('resume');
                $.ajax({
                    url: '/timeradmin/?tipe=resume&arena=' + arenaId + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response.data);
                        reload();
                    }
                })
            }
            function stop() {
                console.log('trigger button stop');
                $.ajax({
                    url: '/timeradmin/?tipe=stop&arena=' + arenaId + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response.data);
                        reload();
                    }
                });
            }
            $('#input-continent').change(function () {
                const selectedValue = $(this).val();
                const arenaId = $('#arena').data('arena');
                $.ajax({
                    url: '/timeradmin/?tipe=babak&value=' + selectedValue + '&arena=' + arenaId + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response.data);
                    }
                });
            });
            $('#start').click(start);
            $('#stop').click(stop);
            $('#resume').click(resume);
            $('#pause').click(pause);
            $('#reset').click(reset);
        });
    </script>

    <!-- Include other scripts if needed -->

    <!-- Your other scripts here -->
  
</body>

</html>