<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap'); */

        * {
            margin: 0px;
            padding: 0px;
            font-family: 'Roboto', sans-serif;
        }

        .bg-green {
            background-color: rgb(0, 203, 0);
            color: red;
        }

        .bg-blue-gradient {
            background-image: linear-gradient(rgb(0, 255, 0), rgb(0, 153, 0));
        }

        /* @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap'); */

        * {
            margin: 0px;
            padding: 0px;
            font-family: 'Roboto', sans-serif;
        }

        .img-full {
            background-image: url('../../assets/Assets/header.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100%;
            height: 600px;
            width: 100%;
        }

        .red {
            background-color: red;
        }

        .green {
            background-color: greenyellow;
        }

        .bg-blue1 {
            background-color: #0066FF;
        }

        .h100 {
            height: 100%;
        }

        .w100 {
            width: 100%;
        }

        .blue-sec {
            background-color: #004DA8;
            color: #004DA8;
        }

        .center-pos {
            top: -1%;
            left: 44%;
        }

        .f-cent {
            text-align: center;
        }

        .border-blue {
            border: 2px solid #0066FF;
        }

        .text-green {
            color: rgb(3, 161, 0);
        }

        .bg-green-2 {
            background-color: rgb(3, 161, 0);
        }

        table thead th.bg-green-2 {
            background-color: rgb(3, 161, 0);
            color: #f5f5f5;
        }

        table tbody tr td.bg-green-2 {
            background-color: rgb(3, 161, 0);
            color: #f5f5f5;
        }

        .border-green {
            border: 2px solid #05FF00;
        }

        .btn-back-blue {
            background-image: linear-gradient(#5498FF, #B9D5FF)
        }

        .btn-back-green {
            background-image: linear-gradient(#05FF00, #C9FFC8)
        }

        .f-white {
            color: #f5f5f5;
        }

        .text-on-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

            .w-10 {
                width: 10%;
            }

            .w-5 {
                width: 5%;
            }
        }
    </style>
    {{--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.min.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
    <title>Player Recap</title>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\kelas;
        use App\arena;

        $setting = Setting::where('arena', $arena)->first();
        $perserta = PersertaModel::where('id', $setting->biru)->first();
        if (empty($perserta)) {
            echo '<script>
                                                                                                                                                                                        window.history.back();
                                                                                                                                                                                    </script>';
            exit();
        }

        $id_perserta = $perserta->id;
        $dataKelas = kelas::where('id', $perserta->kelas)->first();
        $dataArena = arena::where('id', $arena)->first();

        if ($dataKelas->name == "REGU") {
            $pesertaRegu = PersertaModel::where('id_kontigen', $perserta->id_kontigen)->get();
        }

        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
        $settingData = Setting::where('keterangan', 'admin-setting')->first();

        if ($settingData) {
            $imgData1 = $settingData->babak ?? '';
            $imgData2 = $settingData->partai ?? '';
        }

        function cekImage($filename): Boolean
        {
            if ($filename == '') {
                return false;
            } elseif (File::exists(public_path("symbolic/Assets/uploads/$filename"))) {
                return true;
            } else {
                return false;
            }
        }

        function nameFormat($name)
        {
            $arrName = explode(' ', $name);
            $newName = "$arrName[0] $arrName[1]";

            return $newName;
        }

    @endphp
</head>

<body>
    <!-- Header Section -->
    <div class="container-fluid bg-green-2 pb-2" style="color: #F5F5F5;">
        <div class="row " style="height: 100%;">
            <div class="col d-flex justify-content-end align-items-center fs-3">
                <span class="me-3 uppercase"> Partai {{ $setting->partai }}</span>
            </div>
            <div class="col h100">
                <div class="container position-relative h100 d-flex justify-content-center  fs-3" style="height: 70%;">
                    <img src="../../../assets/Assets/header_green.png" alt="" style="width: 60%;">
                    <div class="text-on-image h100 w100 d-flex justify-content-center ">
                        PENCAK SILAT
                    </div>

                    {{-- <div class="text-on-image h100 w100 d-flex justify-content-center ">
                        PENCAK SILAT
                    </div> --}}
                    <!-- PENCAK SILAT -->
                </div>
            </div>
            <div class="col">
                <div class="row" style="height: 100%;">
                    <div class="container-fluid h100">
                        <div class="row h100">
                            <div class="col-10 d-flex justify-content-start align-items-center fs-3">
                                <span class="ms-3">{{ $dataArena->name }}</span>
                            </div>
                            <div class="col-2 d-flex justify-content-end align-items-center gap-4">
                                <img src="{{ asset("/assets/Assets/uploads/$imgData1") }}" alt="" style="width: 60px;">
                                <img src="{{ asset("/assets/Assets/uploads/$imgData2") }}" alt="" style="width: 50px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Player Info Section -->
    <div class="container-fluid">
        <div class="d-flex mb-3 flex-wrap justify-content-between mx-3 mt-3">
            <div>
                <div class="text-dark">Nama Peserta : </div>
                @if($dataKelas->name == "REGU")
                    <div class="" id="parentRegu">
                        @foreach($pesertaRegu as $item)
                            <div class="fs-1 text-green" id="nama">
                                {{ nameFormat($item->name) }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="fs-1 text-green" id="nama">{{ $perserta->name }}</div>
                @endif
            </div>
            <div>
                <div class="text-dark text-end">: Kontingen</div>
                <div class="fs-1 text-end text-green" id="kontigen">{{ $kontigen }}</div>
            </div>
        </div>
    </div>
    <!-- Score Section -->
    @php
        $totaljuri = $settingData->jadwal ?? 6;
    @endphp
    <div class="container-fluid px-4">
        <table class="table table-bordered border-dark-subtle">
            <thead class="text-center align-middle">
                <tr>
                    @for ($i = 1; $i <= $totaljuri; $i++)
                        <th class="bg-light fs-6">Juri {{ $i }}</td>
                    @endfor
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                <tr>
                    @for ($i = 1; $i <= $totaljuri; $i++)
                        <td class="" style="font-size: 3em" id="actual{{ $i }}"></td>
                    @endfor
                </tr>
                <tr>c
                    @for ($i = 1; $i <= $totaljuri; $i++)
                        <td class="text-primary fw-bold" style="font-size: 3em" id="flwo{{ $i }}"></td>
                    @endfor
                </tr>
                <tr>
                    @for ($i = 1; $i <= $totaljuri; $i++)
                        <td class="bg-light text-success" style="font-size: 3em" id="total{{ $i }}"></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Final Score Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <table class="table table-responsive table-bordered border-black">
                    <thead class="text-center align-middle ">
                        <tr>
                            <th class="bg-green-2 text-light py-3 fs-2">Median</th>
                            <th class="bg-green-2 text-light py-3 fs-2">Penalty</th>
                            {{-- <th class="bg-green-2 text-light py-3 fs-2">Total</th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle ">
                        <tr>
                            <td class="py-3 fw-bold fs-2" id="median"></td>
                            <td class="py-3 fw-bold fs-2 text-danger" id="dewan"></td>
                            {{-- <td class="py-3 fw-bold fs-2 text-primary" id="total"></td> --}}
                        </tr>
                        <tr>
                            <td colspan="3" class="bg-green-2 text-light py-3 fs-2">Standard Deviation</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-3 fs-2" id="deviation"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <div class="container shadow-lg bg-green-2 text-light border-3 rounded text-center p-3 fs-1">
                            Time Performance
                        </div>
                        <div id="timer1" class="container text-green fw-bold text-center align-middle mt-2"
                            style="font-size: 8em;">
                            03:00
                        </div>
                    </div>
                    <div class="col">
                        <div class="container shadow-lg bg-green-2 text-light border-3 rounded text-center p-3 fs-1">
                            Score
                        </div>
                        <div id="total" class="container text-green fw-bold text-center align-middle mt-2"
                            style="font-size: 8em;">
                            9.2
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Running Text -->
    <div class="fixed-bottom container-fluid f-white px-0" style="height: 60px;">
        <div class="row d-flex flex-column h100">
            <div class="bg-dark-subtle border rounded border-black" style="width: 100px; height: 100%;">
                <img src="../../../assets/Assets/Ayo Silat.png" alt="" style="width: 100%;">
            </div>
            <div class="h100 d-flex align-items-center px-0">
                <div class="w100 bg-black d-flex align-items-center" style="height: 55px;">
                    <marquee behavior="" direction="Running">
                        {{ $settingData->arena }}
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    <div class="d-none" name="{{ $id_perserta }}" id="id_perserta"></div>
    <div class="d-none" name="{{ $arena }}" id="arena"></div>
    <div class="d-none" name="{{ $setting->partai }}" id="partai"></div>
    <div class="d-none" name="{{ $totaljuri }}" id="totalJuri"></div>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $('#timer1').text('00:00');
        var partai = document.getElementById("partai").getAttribute("name");
        let timerInterval;
        let timeSaveStatus = false;
        let lastSavedTime = '00:00';
        let currentTime = 0; // Simpan waktu dalam detik
        let isPaused = false;
        let StatusCondition = '';
        let intervalId; // Simpan referensi interval
        // function untuk websoket

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }
        function startTimer() {
            if (!isPaused) {
                currentTime = 0; // Reset waktu ke 00:00 saat start
            }

            currentTime++;
            //$('#timer1').removeClass('text-green');
            $('#timer1').text(formatTime(currentTime));

            isPaused = false;
            clearInterval(timerInterval); // Hentikan interval sebelumnya (jika ada)
            timerInterval = setInterval(() => {
                currentTime++;
                var Timers = formatTime(currentTime);
                $('#timer1').text(Timers);
            }, 1000);
        }

        function pauseTimer() {
            clearInterval(timerInterval);
            isPaused = true;
        }
        function resumeTimer() {
            if (isPaused) {
                isPaused = false;
                clearInterval(timerInterval); // Hentikan interval sebelumnya (jika ada)
                timerInterval = setInterval(() => {
                    currentTime++;
                    var Timers = formatTime(currentTime);
                    $('#timer1').text(Timers);
                }, 1000);
            }
        }
        function resetTimer() {
            //$('#timer1').addClass('text-green');
            clearInterval(timerInterval);
            currentTime = 0;
            isPaused = false;
            var Timers = formatTime(currentTime);
            $('#timer1').text(Timers);
        }
        function formatScore(val) {
            if (val === null || val === undefined || val === '' || isNaN(val)) return '0.00';
            let num = Number(val);
            // Bulatkan ke 3 digit desimal untuk membuang floating point noise (misal 3.80001 -> 3.8)
            let rounded = Math.round((num + Number.EPSILON) * 1000) / 1000;
            let str = rounded.toFixed(3);
            // Jika digit ke-3 adalah '0', format wajib 2 desimal (3.80)
            if (str.endsWith('0')) {
                return rounded.toFixed(2);
            }
            return str;
        }

        function findMedian(arr) {
            if (!arr || arr.length === 0) return 0;
            let sorted = arr.map(Number).sort((a, b) => a - b);
            const middleIndex = Math.floor(sorted.length / 2);
            if (sorted.length % 2 === 0) {
                return ((sorted[middleIndex - 1] * 100) + (sorted[middleIndex] * 100)) / 2 / 100;
            } else {
                return sorted[middleIndex];
            }
        }

        var elemenDiv = document.getElementById("id_perserta");
        var id = elemenDiv.getAttribute("name");
        var arenaDiv = document.getElementById("arena");
        var arena = arenaDiv.getAttribute("name");
        var jumlahJuri = document.getElementById("totalJuri").getAttribute("name");

        function pad(num, size) {
            let s = "000000000" + num;
            return s.substr(s.length - size);
        }

        function rekap(data) {
            $.ajax({
                url: '/rekapseni',
                method: 'GET',
                data: data,
                success: function (response) {
                    console.log(response);
                }
            });
        }

        function applyTunggalScore(response) {
            if (!response) return;

            var all_juri = [];
            for (let i = 1; i <= jumlahJuri; i++) {
                let actual = parseFloat(response[`actual${i}`]) || 0;
                let flwo = parseFloat(response[`flwo${i}`]) || 0;
                let score = actual + flwo;
                all_juri.push(score);
            }

            for (let i = 0; i < jumlahJuri; i++) {
                $(`#total${i + 1}`).text(formatScore(all_juri[i]));
            }

            var totalAll = 0;
            var average = 0;
            for (let i = 0; i < jumlahJuri; i++) {
                totalAll += parseFloat(all_juri[i]);
            }
            if (jumlahJuri > 0) {
                average = totalAll / jumlahJuri;
            }

            var deviations = 0;
            for (let i = 0; i < jumlahJuri; i++) {
                deviations += Math.pow((parseFloat(all_juri[i]) - average), 2);
            }
            var deviation = jumlahJuri > 0 ? Math.sqrt(deviations / jumlahJuri) : 0;
            var rawMedian = findMedian(all_juri);
            var dewanPenalty = parseFloat(response.dewan) || 0;
            var total_score = parseFloat(rawMedian) - dewanPenalty;

            if (response.nama) {
                let namaRegu = response.nama.split(',');
                if (namaRegu.length > 3) {
                    $('#parentRegu').html('');
                    namaRegu.forEach((data) => {
                        $('#parentRegu').append(`
                            <div class="fs-1 text-green" id="nama">
                                ${data}
                            </div>
                        `);
                    });
                } else {
                    $('#nama').text(response.nama);
                }
            }
            if (response.kontigen) $('#kontigen').text(response.kontigen);

            for (let i = 1; i <= 8; i++) {
                if (response[`actual${i}`] !== undefined) {
                    $(`#actual${i}`).text(formatScore(response[`actual${i}`]));
                }
                if (response[`flwo${i}`] !== undefined) {
                    $(`#flwo${i}`).text(formatScore(response[`flwo${i}`]));
                }
            }

            $('#total').text(formatScore(total_score));
            $('#dewan').text(dewanPenalty > 0 ? ('-' + formatScore(dewanPenalty)) : formatScore(0));
            $('#median').text(formatScore(rawMedian));
            $('#deviation').text(formatScore(deviation));

            if (response.status == "finish") {
                const send = {
                    id_user: id,
                    arena: arena,
                    time: formatTime(currentTime),
                    score: formatScore(total_score),
                    deviation: formatScore(deviation)
                };
                rekap(send);
            }

            if (response.status == 'taking-time' && timeSaveStatus == false) {
                let currentRunningTime = $('#timer1').text();
                timeSaveStatus = true;
                console.log('timer save initialized');
                $.ajax({
                    url: `/save-time?id_user=${id}&time=${currentRunningTime}&arena=${arena}&partai=${partai}&score=${formatScore(total_score)}&deviation=${formatScore(deviation)}`,
                    method: 'GET',
                    success: function (res) {
                        console.log(res);
                        timeSaveStatus = false;
                    }
                });
            }
        }

        function calldata() {
            $.ajax({
                url: '/call-data/?tipe=seni_tunggal&kt=tunggal&id=' + id + '&arena=' + arena,
                method: 'GET',
                success: function (response) {
                    applyTunggalScore(response);
                }
            });
        }

        function websocket() {
            var arena_id = document.getElementById('arena').getAttribute('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Layanan Notif!");
                });
                Echo.channel('indicator-channel')
                    .listen('.indicator.triggered', (e) => {
                        const data = e.message;
                        indicator(data);
                        try {
                            var parsedData = JSON.parse(data);
                            if (parsedData.arena === arena_id && parsedData.event === "reload") {
                                window.location.reload();
                                console.log("Reload dipicu.");
                            }
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                        }
                    })
                    .error((error) => {
                        console.error('Error:', error);
                    });

                Echo.channel('tunggal-channel')
                    .listen('TunggalEvent', (datas) => {
                        const data = datas.message;
                        if (arena_id == data.arena) {
                            if (data.tipe == 'data') {
                                applyTunggalScore(data.response);
                            } else if (data.tipe == 'update') {
                                calldata();
                            }
                        }
                    });

                Echo.channel('timer')
                    .listen('TimerUpdate', (datas) => {
                        const actions = datas.action;
                        var data = actions;
                        if (data && data.arena == arena_id) {
                            if (data.action === 'start') {
                                startTimer();
                            } else if (data.action === 'pause') {
                                pauseTimer();
                            } else if (data.action === 'resume') {
                                resumeTimer();
                            } else if (data.action === 'stop') {
                                resetTimer();
                            }
                        }
                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
        }

        function isTimeGreater(time1, time2) {
            const [min1, sec1] = time1.split(':').map(Number);
            const [min2, sec2] = time2.split(':').map(Number);

            const totalSeconds1 = min1 * 60 + sec1;
            const totalSeconds2 = min2 * 60 + sec2;

            return totalSeconds1 > totalSeconds2;
        }

        // Inisialisasi WebSocket dan bootstrap data 1x tanpa interval polling
        websocket();
        calldata();
    </script>
</body>

</html>