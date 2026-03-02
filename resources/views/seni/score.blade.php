<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/seni/ScoreSeni.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <title>Seni Score</title>
    @php
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\kelas;
        use App\arena;

        $settingGlobal = Setting::where('keterangan', 'admin-setting')->first();
        $juriSeni = $settingGlobal->jadwal ?? 6;
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $perserta = PersertaModel::where('id', $setting->biru)->first();
        if (empty($perserta)) {
            echo '<script>
                                                                                                                window.history.back();
                                                                                                            </script>';
            exit();
        }

        $id_perserta = $perserta->id;
        $dataArena = arena::where('id', $arena)->first();

        $settingData = Setting::where('keterangan', 'admin-setting')->first();

        if ($settingData) {
            $imgData1 = $settingData->babak ?? '';
            $imgData2 = $settingData->partai ?? '';
        }

        $kelas = Kelas::where('id', $perserta->kelas)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
    @endphp
    <style>
        .text-green {
            color: rgb(3, 161, 0);
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

        .text-red {
            color: red;
        }

        .text-blue {
            color: blue;
        }

        table thead th.bg-green-2 {
            background-color: rgb(3, 161, 0);
            color: #f5f5f5;
        }

        table tbody tr td.bg-green-2 {
            background-color: rgb(3, 161, 0);
            color: #f5f5f5;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="container-fluid bg-green-2 " name="change" style="color: #F5F5F5;">
        <div class="row " style="height: 100%;">
            <div class="col d-flex justify-content-end align-items-center fs-3">
                <span class="me-3">{{ $kelas->name }}</span>
            </div>
            <div class="col h100">
                <div class="container position-relative h100 d-flex justify-content-center fs-3" style="height: 70%;">
                    <img src="../../../assets/Assets/header2.png" alt="" style="width: 20em; height: 2em;">
                    <div class="text-on-image h100 w100 d-flex justify-content-center ">
                        {{ $dataArena->name }}
                    </div>
                    <!-- PENCAK SILAT -->
                </div>
            </div>
            <div class="col">
                <div class="row" style="height: 100%;">
                    <div class="container-fluid h100">
                        <div class="row h100">
                            <div class="col-10 d-flex justify-content-start align-items-center fs-3">
                                <span class="ms-3 ">{{ Trim($dataArena->name) }}</span>
                            </div>
                            <div class="col-2 d-flex justify-content-end align-items-center py-2">
                                <img src="{{ asset("/assets/Assets/uploads/$imgData1") }}" alt="" style="width: 50px;">
                                <img src="{{ asset("/assets/Assets/uploads/$imgData2") }}" alt="" style="width: 50px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Match Title -->
    <div class="container-fluid d-flex justify-content-center f-cent fs-3">
        {{-- ARENA 1 <br>
        PENYISIHAN DEWASA (4PA) --}}
    </div>
    <!-- Container Body Content -->
    <div class="container-fluid ">
        <div class="container-fluid ">
            <!-- Player Info -->
            <div class="row mt-4">
                <div class="col">
                    <div class="fs-4">
                        Nama Peserta :
                    </div>
                    <div class="text-green fw-bold" name="changetext" style="font-size: 3em;" id="nama">
                        {{ $perserta->name }}
                    </div>
                </div>
                <div class="col">
                    <div class="text-end fs-4">
                        : Kontingen
                    </div>
                    <div class="text-end fw-bold text-green" name="changetext" style="font-size: 3em;" id="kontigen">
                        {{ $kontigen }}
                    </div>
                </div>
            </div>
            <!-- Point Table -->
            <table class="table table-bordered my-5 f-cent fs-3 border-dark-subtle">
                <thead>
                    <tr style="height: 70px;" class="fs-2">
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <th scope="col" class="bg-dark" style=" color: #f5f5f5;">JURI
                                {{ $i }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr style="height: 70px;">
                        @for ($i = 1; $i <= $juriSeni; $i++) <td class="fw-semibold" id="attack{{ $i }}">
                            </td>
                            @endfor
                    </tr> --}}
                    {{-- <tr style="height: 70px;">
                        @for ($i = 1; $i <= $juriSeni; $i++) <td class="fw-semibold" id="firmness{{ $i }}">
                            </td>
                            @endfor
                    </tr> --}}
                    {{-- <tr style="height: 70px;">
                        @for ($i = 1; $i <= $juriSeni; $i++) <td class="fw-semibold" id="soulfullness{{ $i }}">
                            </td>
                            @endfor
                    </tr> --}}
                    <tr style="height: 70px;">
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            @php
                                $juri = $setting->{'juri_' . $i};
                            @endphp
                            <td class="fw-semibold bg-success text-white" style="font-size: 5em;" name="{{ $juri }}"
                                id="total{{ $i }}"></td>
                        @endfor
                    </tr>
                </tbody>
            </table>
            <!-- Time and Score info -->
            <div class="container-fluid h100 mb-5 ">
                <div class="row">
                    <!-- Time Section  -->
                    <div class="col d-flex justify-content-center w-100 flex-wrap gap-0  px-0">
                        <div class="w-100 ">
                            <div class="w-100">
                                <table class="table table-bordered rounded border-dark w-100">
                                    <thead>
                                        <tr class="text-center">
                                            <th class=" text-light bg-green-2 fs-2 ">
                                                Median
                                            </th>
                                            <th class=" text-light bg-green-2 fs-2 ">
                                                Penalty
                                            </th>
                                            {{-- <th class="text-light bg-green-2 fs-2">
                                                Score
                                            </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td id="medianscore" name="changetext" class="fw-bold"
                                                style="font-size: 4em;" id="dewan_median">
                                                0
                                            </td>
                                            <td class="text-danger fw-bold" style="font-size: 4em;" id="dewan_pinalti">

                                            </td>
                                            {{-- <td class="fs-2 text-success fw-bold" id="total_score">

                                            </td> --}}
                                        </tr>
                                        <tr class="text-center">
                                            <td class="bg-green-2 text-light fs-2" colspan="3">Standard Deviation
                                            </td>
                                        </tr>
                                        <tr class="text-center">
                                            <td id="deviationscore" class="fs-2" colspan="3">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- mid section -->
                    <!-- Point Section -->
                    <div class="col-8 pe-0 ps-5">
                        <div class="row px-0">
                            <div class="col px-0">
                                <div
                                    class="container border-3 border-black shadow-lg bg-green-2 text-light border-3 rounded text-center p-5 fs-1">
                                    Time Performance
                                </div>
                                <div id="timer1" class="container text-green fw-bold text-center align-middle mt-2"
                                    style="font-size: 10em;">
                                    03:00
                                </div>
                            </div>
                            <div class="col">
                                <div
                                    class="container border-3 border-black shadow-lg bg-green-2 text-light border-3 rounded text-center p-5 fs-1">
                                    Score
                                </div>
                                <div id="total_score" name="changetext"
                                    class="container text-green fw-bold text-center align-middle mt-2"
                                    style="font-size: 10em;">
                                    9.2
                                </div>
                            </div>
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
                <img src="../assets/Assets/Ayo Silat.png" alt="" style="width: 100%;">
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
    <div class="d-none" name="{{ $juriSeni }}" id="jumlahJuri"></div>
    <div class="d-none" name="{{ $setting->partai }}" id="partai"></div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script>
        $('#timer1').text('00:00');
        var partai = document.getElementById("partai").getAttribute("name");
        let timerInterval;
        let currentTime = 0; // Simpan waktu dalam detik
        let timeSaveStatus = false;
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
                Echo.channel('solo-channel')
                    .listen('SoloEvent', (datas) => {
                        var data = datas.message;

                        if (arena_id == data.arena && data.tipe == 'notif') {
                            console.log(data);
                            $(`[name='${data.id_juri}']`).removeClass('bg-success bg-warning text-white text-success');
                            $(`[name='${data.id_juri}']`).addClass('bg-warning text-black');

                            setTimeout(() => {
                                $(`[name='${data.id_juri}']`).removeClass('bg-success bg-warning text-success text-white');
                                $(`[name='${data.id_juri}']`).addClass('bg-success text-white');
                            }, 300);
                        }

                    })
                Echo.channel('timer')
                    .listen('TimerUpdate', (datas) => {
                        // console.log(datas);
                        const actions = datas.action;
                        var data = actions;
                        // console.log(actions);
                        // Error
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
            let jumlahJuri = document.getElementById("jumlahJuri").getAttribute('name');

            function pad(num, size) {
                let s = "000000000" + num;
                return s.substr(s.length - size);
            }

            function getselisih(inputTime) {
                let now = new Date();
                let nowHours = pad(now.getHours(), 2);
                let nowMinutes = pad(now.getMinutes(), 2);
                let nowSeconds = pad(now.getSeconds(), 2);

                if (inputTime) {
                    let [inputHours, inputMinutes, inputSeconds] = inputTime.split(':').map(Number);

                    let inputDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), inputHours, inputMinutes,
                        inputSeconds);

                    let differenceInSeconds = Math.abs((now - inputDate) / 1000);

                    let minutes = Math.floor(differenceInSeconds / 60);
                    let seconds = Math.floor(differenceInSeconds % 60);

                    return `${pad(minutes, 2)}:${pad(seconds, 2)}`;
                } else {
                    return '00:00';
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

            function requestdata() {
                $.ajax({
                    url: '/call-data/?tipe=seni&kt=ganda&id=' + id + '&arena=' + arena,
                    method: 'GET',
                    success: function (response) {
                        //var juri1 = (parseFloat(response.firmness1) + parseFloat(response.attack1) + parseFloat(
                        //    response.soulfullness1) + 9.1).toFixed(2);
                        //var juri2 = (parseFloat(response.firmness2) + parseFloat(response.attack2) + parseFloat(
                        //    response.soulfullness2) + 9.1).toFixed(2);
                        //var juri3 = (parseFloat(response.firmness3) + parseFloat(response.attack3) + parseFloat(
                        //    response.soulfullness3) + 9.1).toFixed(2);
                        //var juri4 = (parseFloat(response.firmness4) + parseFloat(response.attack4) + parseFloat(
                        //    response.soulfullness4) + 9.1).toFixed(2);
                        //var juri5 = (parseFloat(response.firmness5) + parseFloat(response.attack5) + parseFloat(
                        //    response.soulfullness5) + 9.1).toFixed(2);
                        //var juri6 = (parseFloat(response.firmness6) + parseFloat(response.attack6) + parseFloat(
                        //    response.soulfullness6) + 9.1).toFixed(2);
                        //var juri7 = (parseFloat(response.firmness7) + parseFloat(response.attack7) + parseFloat(
                        //    response.soulfullness7) + 9.1).toFixed(2);
                        //var juri8 = (parseFloat(response.firmness8) + parseFloat(response.attack8) + parseFloat(
                        //    response.soulfullness8) + 9.1).toFixed(2);
                        //var all_juri = [juri1, juri2, juri3, juri4, juri5, juri6, juri7, juri8];

                        var all_juri = [];
                        for (let i = 1; i <= jumlahJuri; i++) {
                            let score = (parseFloat(response[`firmness${i}`]) + parseFloat(response[`attack${i}`]) + parseFloat(response[`soulfullness${i}`]) + 9.1).toFixed(2);
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

                        //var average = (parseFloat(juri1) + parseFloat(juri2) + parseFloat(juri3) + parseFloat(
                        //        juri4) + parseFloat(juri5) + parseFloat(juri6) + parseFloat(juri7) +
                        //    parseFloat(juri8)) / 8;
                        //var deviations = Math.pow((parseFloat(juri1) - average), 2) + Math.pow((parseFloat(
                        //        juri2) - average), 2) + Math.pow((parseFloat(juri3) - average), 2) + Math.pow((
                        //        parseFloat(juri4) - average), 2) + Math.pow((parseFloat(juri5) - average), 2) +
                        //    Math.pow((parseFloat(juri6) - average), 2) + Math.pow((parseFloat(juri7) - average),
                        //        2) + Math.pow((parseFloat(juri8) - average), 2);
                        var deviation = Math.sqrt(deviations / jumlahJuri);
                        var total_score = findMedian(all_juri) - response.dewan;

                        // if (response.status != "pause") {
                        //     if (response.time != 0) {
                        //         const timer = getselisih(response.time);
                        //         $('#timer1').text(timer);
                        //     } else {
                        //         const timer = getselisih();
                        //         $('#timer1').text(timer);
                        //     }
                        // } else {
                        //     $('#timer1').text("pause");
                        // }

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

                        $('#nama').text(response.nama);
                        $('#id_perserta').attr('name', response.id_peserta);
                        $('#kontigen').text(response.kontigen);
                        $('#soulfullness1').text(response.soulfullness1);
                        $('#soulfullness2').text(response.soulfullness2);
                        $('#soulfullness3').text(response.soulfullness3);
                        $('#soulfullness4').text(response.soulfullness4);
                        $('#soulfullness5').text(response.soulfullness5);
                        $('#soulfullness6').text(response.soulfullness6);
                        $('#soulfullness7').text(response.soulfullness7);
                        $('#soulfullness8').text(response.soulfullness8);
                        $('#attack1').text(response.attack1);
                        $('#attack2').text(response.attack2);
                        $('#attack3').text(response.attack3);
                        $('#attack4').text(response.attack4);
                        $('#attack5').text(response.attack5);
                        $('#attack6').text(response.attack6);
                        $('#attack7').text(response.attack7);
                        $('#attack8').text(response.attack8);
                        $('#firmness1').text(response.firmness1);
                        $('#firmness2').text(response.firmness2);
                        $('#firmness3').text(response.firmness3);
                        $('#firmness4').text(response.firmness4);
                        $('#firmness5').text(response.firmness5);
                        $('#firmness6').text(response.firmness6);
                        $('#firmness7').text(response.firmness7);
                        $('#firmness8').text(response.firmness8);


                        //$('#total1').text(juri1);
                        //$('#total2').text(juri2);
                        //$('#total3').text(juri3);
                        //$('#total4').text(juri4);
                        //$('#total5').text(juri5);
                        //$('#total6').text(juri6);
                        //$('#total7').text(juri7);
                        //$('#total8').text(juri8);
                        $('#total_score').text(total_score);
                        $('#dewan_pinalti').text(response.dewan);
                        $('#medianscore').text(findMedian(all_juri));
                        $('#deviationscore').text(deviation);
                        StatusCondition = response.status;
                        if (response.status == "finish") {
                            const send = {
                                id_user: id,
                                arena: arena,
                                time: formatTime(currentTime),
                                score: total_score,
                                deviation: deviation
                            }

                            rekap(send);
                            //clearInterval(intervalId);
                            //location.reload();
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

        // Jalankan calldata() setiap 500ms, simpan referensi ke intervalId
        calldata();
        websocket();
        setInterval(calldata, 500);
    </script>

</body>

</html>