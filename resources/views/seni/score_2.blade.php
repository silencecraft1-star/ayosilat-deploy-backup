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

        .text-red {
            color: red;
        }

        .text-blue {
            color: blue;
        }

        .bg-green-2 {
            background-color: rgb(3, 161, 0);
        }

        .bg-red-2 {
            background-color: red;
        }

        .bg-blue-2 {
            background-color: blue;
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
        $settingGlobal = Setting::where('keterangan', 'admin-setting')->first();
        $perserta = PersertaModel::where('id', $setting->biru)->first();
        if (empty($perserta)) {
            echo '<script>
                                                                                                                                                                                        window.history.b        ack();
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
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();

        if ($settingData) {
            $imgData1 = $settingData->babak ?? '';
            $imgData2 = $settingData->partai ?? '';
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
    <div class="container-fluid pb-2" name="change"
        style="color: #F5F5F5; background: linear-gradient(to right, #a7a7a7ff, #000000, #a7a7a7ff);">
        <div class="row " style="height: 100%;">
            <div class="col d-flex justify-content-end align-items-center fs-3">
                <span class="me-3 uppercase"> Partai {{ $setting->partai }}</span>
            </div>
            <div class="col h100">
                <div class="container position-relative h100 d-flex justify-content-center  fs-3" style="height: 70%;">
                    <img src="../../../assets/Assets/header2.png" alt="" style="width: 20em; height: 2em;">
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
                <div class="text-dark fs-2">Nama Peserta : </div>
                @if($dataKelas->name == "REGU")
                    <div class="" id="parentRegu">
                        @foreach($pesertaRegu as $item)
                            <div class="fs-1 text-green" id="nama">
                                {{ nameFormat($item->name) }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class=" text-green fw-bold" name="changetext" style="font-size: 3em;" id="nama">
                        {{ $perserta->name }}
                    </div>
                @endif
            </div>
            <div>
                <div class="text-dark text-end fs-2">: Kontingen</div>
                <div class="fs-1 text-end text-green fw-bold" name="changetext" style="font-size: 3em;" id="kontigen">
                    {{ $kontigen }}
                </div>
            </div>
        </div>
    </div>
    <!-- Score Section -->
    @php
        $totaljuri = $settingGlobal->jadwal ?? 6;
    @endphp
    <div class="container-fluid" style="margin-bottom: 100px; margin-top:100px;">
        <table name="scoreTable" class="table table-bordered border-dark-subtle">
            <thead class="text-center align-middle">
                <tr>
                    @for ($i = 1; $i <= $totaljuri; $i++)
                        <th class="bg-dark text-white fs-2">Juri {{ $i }}</td>
                    @endfor
                </tr>
            </thead>
            <tbody class="text-center align-middle">
                {{-- <tr>
                    @for ($i = 1; $i <= $totaljuri; $i++) <td class="" style="font-size: 3em" id="actual{{ $i }}">
                        </td>
                        @endfor
                </tr> --}}
                {{-- <tr>
                    @for ($i = 1; $i <= $totaljuri; $i++) <td class="text-primary fw-bold" style="font-size: 3em"
                        id="flwo{{ $i }}">
                        </td>
                        @endfor
                </tr> --}}
                <tr>

                    @for ($i = 1; $i <= $totaljuri; $i++)
                        @php
                            $juri = $setting->{'juri_' . $i};
                        @endphp
                        <td class="text-white bg-success" name="{{ $juri }}" id="total{{ $i }}" style="font-size: 8em;">
                        </td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Final Score Section -->
    <div class="container-fluid">
        <div class="row flex-grow-1 h-full">
            <div class="col h-full ">
                <table class="table table-responsive table-bordered border-black">
                    <thead class="text-center align-middle ">
                        <tr>
                            <th class="bg-green-2 text-light py-2 fs-2">Median</th>
                            <th class="bg-green-2 text-light py-2 fs-2">Penalty</th>
                            {{-- <th class="bg-green-2 text-light py-5 fs-1">Total</th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle ">
                        <tr>
                            <td class="py-3 fw-bold" style="font-size: 4em; overflow:hidden; max-width: 7em;"
                                id="median"></td>
                            <td class="py-3 fw-bold text-danger" style="font-size: 4em;" id="dewan"></td>
                            {{-- <td class="py-5 fw-bold fs-2 text-primary" id="total"></td> --}}
                        </tr>
                        <tr>
                            <td colspan="3" class="bg-green-2 text-light py-2 fs-2">Standard Deviation</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="fs-2" style="padding: 1em 0px;" id="deviation"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-8 ">
                <div class="row justify-content-center items-center ">
                    <div class="col">
                        <div
                            class="border border-black shadow-lg bg-green-2 h-full text-light border-3 rounded text-center px-5 py-3 fs-2">
                            Time Performance
                        </div>
                        <div id="timer1" name="changetext" class="container text-green fw-bold text-center align-middle"
                            style="font-size: 14em;">
                            03:00
                        </div>
                    </div>
                    <div class="col">
                        <div
                            class="border border-black container shadow-lg bg-green-2 text-light border-3 rounded text-center px-5 py-3 fs-2">
                            Total Score
                        </div>
                        <div id="total" name="changetext" class="container text-green fw-bold text-center align-middle"
                            style="font-size: 14em;">
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
            <div class="bg-dark-subtle border d-flex justify-content-center align-items-center rounded border-black"
                style="width: 100px; height: 100%;">
                <img src="../../../assets/Assets/Ayo Silat.png" alt="" style="width: 80%;">
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
        function websocket() {
            var arena_id = document.getElementById('arena').getAttribute('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Soketi!");
                });
                Echo.channel('indicator-channel')
                    .listen('.indicator.triggered', (e) => {
                        const data = e.message;
                        indicator(data);
                        var reload = JSON.parse(data);

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
                        console.log(data);
                        if (arena_id == data.arena && data.tipe == 'notif') {
                            console.log(data);
                            $(`[name='${data.id_juri}']`).removeClass('bg-success text-white bg-warning bg-primary bg-danger text-black text-success');

                            if (data.status == "next") {
                                $(`[name='${data.id_juri}']`).addClass('bg-warning text-black');
                            }
                            else if (data.status == "flow") {
                                $(`[name='${data.id_juri}']`).addClass('bg-primary text-white');
                            }
                            else if (data.status == "wrong") {
                                $(`[name='${data.id_juri}']`).addClass('bg-danger text-white');
                            }

                            setTimeout(() => {
                                $(`[name='${data.id_juri}']`).removeClass('bg-success bg-primary bg-danger bg-warning text-black text-white');
                                $(`[name='${data.id_juri}']`).addClass('bg-success text-white');
                            }, 400);
                        }
                    });
                Echo.channel('timer')
                    .listen('TimerUpdate', (datas) => {
                        // console.log(datas);
                        const actions = datas.action;
                        var data = actions;
                        // console.log(actions, arena_id);
                        // error
                        if (data.arena == arena_id) {
                            if (data.action === 'start') {
                                startTimer(); // Mulai timer
                            }
                            else if (data.action === 'pause') {
                                pauseTimer(); // Jeda timer
                            }
                            else if (data.action === 'resume') {
                                resumeTimer(); // Lanjutkan timer
                            }
                            else if (data.action === 'stop') {
                                resetTimer(); // Reset timer
                            }
                        }

                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
        }
        // end function untuk websoket
        function calldata() {
            function findMedian(arr) {
                arr.sort((a, b) => a - b);
                const middleIndex = Math.floor(arr.length / 2);

                if (arr.length % 2 === 0) {
                    return (arr[middleIndex - 1] + arr[middleIndex]) / 2;
                } else {
                    return arr[middleIndex];
                }
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

            function findMedian(arr) {
                arr.sort((a, b) => a - b);
                const middleIndex = Math.floor(arr.length / 2);
                const left = (arr[middleIndex - 1]) * 100;
                const right = (arr[middleIndex]) * 100;
                const middle = (left + right) / 2 / 100;
                if (arr.length % 2 === 0) {
                    return middle;
                } else {
                    return arr[middleIndex];
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

            function calculateJuri(response) {
                let juri = [];
                let sum = 0;

                for (let i = 1; i <= jumlahJuri; i++) {
                    let score = (parseFloat(response[`actual${i}`]) + parseFloat(response[`flwo${i}`])).toFixed(2);
                    juri.push(parseFloat(score));
                    sum += parseFloat(score);
                }

                let average = sum / juri.length;

                let deviations = juri.reduce((acc, value) => acc + Math.pow(value - average, 2), 0);
                let deviation = Math.sqrt(deviations / juri.length);

                let total_score = (findMedian(juri) - parseFloat(response.dewan));

                return {
                    juri,
                    average,
                    deviation,
                    total_score
                };
            }

            //function getselisih(inputTime) {
            //    let now = new Date();
            //    let nowHours = pad(now.getHours(), 2);
            //    let nowMinutes = pad(now.getMinutes(), 2);
            //    let nowSeconds = pad(now.getSeconds(), 2);
            //
            //    if (inputTime) {
            //        let [inputHours, inputMinutes, inputSeconds] = inputTime.split(':').map(Number);
            //
            //        let inputDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), inputHours, inputMinutes,
            //            inputSeconds);
            //
            //        let differenceInSeconds = Math.abs((now - inputDate) / 1000);
            //
            //        let minutes = Math.floor(differenceInSeconds / 60);
            //        let seconds = Math.floor(differenceInSeconds % 60);
            //
            //        return `${pad(minutes, 2)}:${pad(seconds, 2)}`;
            //    } else {
            //        return '00:00';
            //    }
            //}

            // function getselisih(inputTime) {

            //     let now = new Date();
            //     let nowHours = pad(now.getHours(), 2);
            //     let nowMinutes = pad(now.getMinutes(), 2);
            //     let nowSeconds = pad(now.getSeconds(), 2);

            //     if (inputTime) {
            //         let [inputHours, inputMinutes, inputSeconds] = inputTime.split(':').map(Number);

            //         let inputDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), inputHours, inputMinutes,
            //             inputSeconds);

            //         let differenceInSeconds = Math.abs((now - inputDate) / 1000);

            //         let minutes = Math.floor(differenceInSeconds / 60);
            //         let seconds = Math.floor(differenceInSeconds % 60);

            //         return `${pad(minutes, 2)}:${pad(seconds, 2)}`;
            //     } else {
            //         return '00:00';
            //     }
            // }

            function requestdata() {
                $.ajax({
                    url: '/call-data/?tipe=seni_tunggal&kt=tunggal&id=' + id + '&arena=' + arena + '',
                    method: 'GET',
                    success: function (response) {
                        //var juri1 = (parseFloat(response.actual1) + parseFloat(response.flwo1)).toFixed(2);
                        //var juri2 = (parseFloat(response.actual2) + parseFloat(response.flwo2)).toFixed(2);
                        //var juri3 = (parseFloat(response.actual3) + parseFloat(response.flwo3)).toFixed(2);
                        //var juri4 = (parseFloat(response.actual4) + parseFloat(response.flwo4)).toFixed(2);
                        //var juri5 = (parseFloat(response.actual5) + parseFloat(response.flwo5)).toFixed(2);
                        //var juri6 = (parseFloat(response.actual6) + parseFloat(response.flwo6)).toFixed(2);
                        //var juri7 = (parseFloat(response.actual7) + parseFloat(response.flwo7)).toFixed(2);
                        //var juri8 = (parseFloat(response.actual8) + parseFloat(response.flwo8)).toFixed(2);
                        //var all_juri = [juri1, juri2, juri4, juri3, juri5, juri6, juri7, juri8];
                        var all_juri = [];



                        for (let i = 1; i <= jumlahJuri; i++) {
                            let score = (parseFloat(response[`actual${i}`]) + parseFloat(response[`flwo${i}`])).toFixed(2);
                            all_juri.push(score);
                        }

                        for (let i = 0; i < jumlahJuri; i++) {
                            $(`#total${i + 1}`).text(all_juri[i]);
                        }

                        var totalAll = 0;
                        var average = 0;
                        for (let i = 0; i < jumlahJuri; i++) {
                            totalAll += parseFloat(all_juri[i]);

                            let currentIteration = i + 1;
                            if (currentIteration == jumlahJuri) {
                                average = totalAll / jumlahJuri;
                            }
                        }

                        var deviations = 0;
                        for (let i = 0; i < jumlahJuri; i++) {
                            deviations += Math.pow((parseFloat(all_juri[i]) - average), 2);
                        }

                        //var average = (parseFloat(juri1) + parseFloat(juri2) + parseFloat(juri3) +
                        //    parseFloat(juri4) + parseFloat(juri5) + parseFloat(juri6) + parseFloat(juri7) +
                        //    parseFloat(juri8)) / jumlahJuri;
                        //var deviations = Math.pow((parseFloat(juri1) - average), 2) + Math.pow((parseFloat(
                        //    juri2) - average), 2) + Math.pow((parseFloat(juri3) - average), 2) + Math.pow((
                        //    parseFloat(juri4) - average), 2) + Math.pow((
                        //    parseFloat(juri5) - average), 2) + Math.pow((
                        //    parseFloat(juri6) - average), 2) + Math.pow((
                        //    parseFloat(juri7) - average), 2) + Math.pow((
                        //    parseFloat(juri8) - average), 2);
                        var deviation = Math.sqrt(deviations / jumlahJuri);
                        var total_score = (parseFloat(findMedian(all_juri)) - parseFloat(response.dewan))
                            .toFixed(2);
                        //console.log(response);
                        // if (response.status != "pause") {
                        //     if (response.time != 0) {
                        //         const timer = getselisih(response.time);
                        //         $('#timer1').text(timer);
                        //         localStorage.setItem('waktu', timer);
                        //     } else {
                        //         const timer = getselisih();
                        //         $('#timer1').text(timer);
                        //     }
                        // } else {
                        //     let saved = localStorage.getItem('waktu');
                        //     $('#timer1').text(saved ?? "pause`");
                        // }

                        let {
                            juri: totaldif,
                            average: averageFinal,
                            deviation: deviation2,
                            total_score1: total2
                        } = calculateJuri(response);

                        // Perbarui tampilan dengan data yang diperbarui
                        //console.log(all_juri);

                        let namaRegu = response.nama.split(',');
                        let finalArr = [];
                        let result;

                        if (response.current == "biru") {
                            $("[name='change']").removeClass('bg-green-2 bg-red-2 bg-blue-2');
                            $("[name='change']").addClass('bg-blue-2');

                            $("[name='changetext']").removeClass('text-green text-red text-blue');
                            $("[name='changetext']").addClass('text-blue');
                        } else if (response.current == "merah") {
                            $("[name='change']").removeClass('bg-green-2 bg-red-2 bg-blue-2 ');
                            $("[name='change']").addClass('bg-red-2');

                            $("[name='changetext']").removeClass('text-green text-red text-blue');
                            $("[name='changetext']").addClass('text-red');
                        } else {
                            $("[name='change']").removeClass('bg-green-2 bg-red-2 bg-blue-2 ');
                            $("[name='change']").addClass('bg-green-2');

                            $("[name='changetext']").removeClass('text-green text-red text-blue');
                            $("[name='changetext']").addClass('text-green');
                        }


                        if (namaRegu.length > 3) {

                            $('#parentRegu').html('');

                            namaRegu.forEach((data, index) => {

                                $('#parentRegu').append(`
                                    <div class="fs-1 text-green" id="nama">
                                        ${data}
                                    </div>
                                `);
                            })
                            //namaRegu.forEach((data,index) => {
                            //   if(data === "") {
                            //    return;
                            //   }
                            //   else {
                            //    let splitName = data.split(" ");

                            //    let formattedName = data.length > 2 ? `${splitName[0]} ${splitName[1]}` : `${splitName[0]}`; 

                            //    finalArr.push(formattedName);
                            //   }

                            //});

                            result = finalArr.join(' ,');
                        }
                        else {
                            result = namaRegu;
                            $('#nama').text(result);
                        }

                        //$('#nama').text(response.nama);
                        $('#id_perserta').attr('name', response.id_peserta);
                        $('#kontigen').text(response.kontigen);
                        $('#actual1').text(response.actual1);
                        $('#actual2').text(response.actual2);
                        $('#actual3').text(response.actual3);
                        $('#actual4').text(response.actual4);
                        $('#actual5').text(response.actual5);
                        $('#actual6').text(response.actual6);
                        $('#actual7').text(response.actual7);
                        $('#actual8').text(response.actual8);
                        $('#flwo1').text(response.flwo1);
                        $('#flwo2').text(response.flwo2);
                        $('#flwo3').text(response.flwo3);
                        $('#flwo4').text(response.flwo4);
                        $('#flwo5').text(response.flwo5);
                        $('#flwo6').text(response.flwo6);
                        $('#flwo7').text(response.flwo7);
                        $('#flwo8').text(response.flwo8);


                        //$('#total1').text(juri1);
                        //$('#total2').text(juri2);
                        //$('#total3').text(juri3);
                        //$('#total4').text(juri4);
                        //$('#total5').text(juri5);
                        //$('#total6').text(juri6);
                        //$('#total7').text(juri7);
                        //$('#total8').text(juri8);
                        $('#total').text(total_score);
                        $('#dewan').text('-' + response.dewan);
                        $('#median').text(findMedian(all_juri));
                        $('#deviation').text(deviation);

                        if (response.status == "finish") {
                            const send = {
                                id_user: id,
                                arena: arena,
                                time: formatTime(currentTime),
                                score: total_score,
                                deviation: deviation
                            }

                            rekap(send);
                        }

                        if ((response.status == 'taking-time' || response.status == 'diskualify') && timeSaveStatus == false) {
                            let currentRunningTime = $('#timer1').text();

                            timeSaveStatus = true;
                            console.log('timer save initialized');
                            $.ajax({
                                url: `/save-time?id_user=${id}&time=${currentRunningTime}&arena=${arena}&partai=${partai}&score=${total_score}&deviation=${deviation}`,
                                method: 'GET',
                                success: function (response) {
                                    console.log(response);
                                    timeSaveStatus = false;
                                }
                            });
                        }
                    }
                });
            }
            requestdata();
        }

        function isTimeGreater(time1, time2) {
            const [min1, sec1] = time1.split(':').map(Number);
            const [min2, sec2] = time2.split(':').map(Number);

            const totalSeconds1 = min1 * 60 + sec1;
            const totalSeconds2 = min2 * 60 + sec2;

            return totalSeconds1 > totalSeconds2;
        }

        websocket();
        calldata();
        setInterval(calldata, 500);
    </script>

</body>

</html>