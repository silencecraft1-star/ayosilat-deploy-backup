<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
</head>

<body>
    @php
        use App\score;
        use App\Setting;
        use App\KontigenModel;
        use App\PersertaModel;
        use App\kelas;
        use App\arena;
        use App\jadwal_group;
        $data = Setting::where('arena', $arena)->first();
        $babak = $data->babak;

        $plus1 = score::where('status', 'plus')->where('id_perserta', '4')->sum('score');
        $minus1 = score::where('status', 'minus')->where('id_perserta', '4')->sum('score');
        $score1 = $plus1 - $minus1;
        $plus2 = score::where('status', 'plus')->where('id_perserta', '6')->sum('score');
        $minus2 = score::where('status', 'minus')->where('id_perserta', '6')->sum('score');
        $score2 = $plus2 - $minus2;
        $dataArena = arena::where('id', $arena)->first();
        $id_perserta_1 = $data->biru;
        $id_perserta_2 = $data->merah;
        $pesertabiru = PersertaModel::where('id', $data->biru)->first();
        $pesertamerah = PersertaModel::where('id', $data->merah)->first();
        $kelas = kelas::where('id', $pesertabiru->kelas)->first();
        $kontigen1 = KontigenModel::where('id', $pesertabiru->id_kontigen)->first();
        $kontigen2 = KontigenModel::where('id', $pesertamerah->id_kontigen)->first();
        $cekpartai = jadwal_group::where('arena', $arena)
            ->where('biru', $pesertabiru->id)
            ->where('merah', $pesertamerah->id)
            ->first();

        $partai = $data->partai ?? '';
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

    @endphp
    <div class="hidden" id="idpartai" name="{{ $partai }}"></div>
    <div class="d-none" name="{{ $arena }}" id="arenaid"></div>
    <div class="m-5">
        <!-- Info Section -->
        <section>
            <div class="md:grid md:grid-cols-12 mb-10">
                <div class="col-span-1" >
                    <div
                        class="bg-gradient-to-b shadow-neutral-400 from-blue-700 to-blue-500 rounded shadow-xl h-24 flex justify-center items-center mb-3 md:mb-0">
                        <div class="text-neutral-100 text-5xl" id="scoreBiru">
                            40
                        </div>
                    </div>
                </div>
                <div class="col-span-5 px-5 mb-3 md:mb-0" >
                    <div class="bg-blue-500 px-5 py-1 mb-2 shadow-xl shadow-neutral-400">
                        <div class="text-neutral-100 uppercase text-2xl" id="namaBiru">
                            Peserta 1
                        </div>
                    </div>
                    <div class="md:flex md:justify-start">
                        <div class="bg-blue-700 px-5 py-1 w-auto md:w-96 shadow-xl shadow-neutral-400">
                            <div class="text-neutral-100" id="kontigenBiru">
                                Kontigen 1
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-5 px-5 mb-3 md:mb-0">
                    <div class="flex justify-end">
                        <div class="bg-red-500 px-5 py-1 mb-2 shadow-xl w-full shadow-neutral-400">
                            <div class="text-neutral-100 text-end uppercase text-2xl" id="namaMerah">
                                Peserta 1
                            </div>
                        </div>
                    </div>
                    <div class="md:flex md:justify-end">
                        <div class="bg-red-700 px-5 py-1 w-auto md:w-96 shadow-xl shadow-neutral-400">
                            <div class="text-neutral-100 text-end" id="kontigenMerah">
                                Kontigen 1
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-span-1" >
                    <div
                        class="bg-gradient-to-b from-red-700 to-red-500 rounded shadow-xl h-24 flex justify-center items-center shadow-neutral-400">
                        <div class="text-neutral-100 text-5xl" id="scoreMerah">
                            40
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Recap Section -->
        <section>
            <div>
                {{-- <div class="text-orange-500 text-center text-3xl mb-10" data-aos="fade-up">
                    Rekap Skor Tanding
                </div> --}}
                <div class="md:grid md:grid-cols-12">
                    <div class="md:col-span-6">
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="pukulanb">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Pukulan
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/fist (2).png" class="size-24" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="tendanganb">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Tendangan
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/kick.png" class="size-24 scale-x-[-1]" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="jatuhanb">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Jatuhan
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-300 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/judo white.png" class="size-24 " alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="binaan1b">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Binaan 1
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/pointing_hand.png" class="size-24" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="binaan2b">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Binaan 2
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/peace_hand.png" class="size-24 rotate-90" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="teguran1b">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Teguran 1
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/pointing_hand.png" class="size-24 -rotate-90"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="teguran2b">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Teguran 2
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/peace_hand.png" class="size-24 " alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-blue-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="peringatanb">
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex justify-end items-center h-full px-5 text-2xl">
                                            Peringatan
                                        </div>
                                    </div>
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-blue-300 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/raising_hand.png" class="size-24 " alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-6" >
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/fist (2).png" class="size-24 scale-x-[-1]"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Pukulan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="pukulanm">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/kick.png" class="size-24" alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Tendangan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="tendanganm">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-300 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/judo white.png" class="size-24 scale-x-[-1]"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Jatuhan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="jatuhanm">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/pointing_hand.png" class="size-24 scale-x-[-1]"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Binaan 1
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="binaan1m">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/peace_hand.png"
                                                class="size-24 -rotate-90 scale-x-[-1]" alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Binaan 2
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="binaan2m">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/pointing_hand.png"
                                                class="size-24 rotate-90 scale-x-[-1]" alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Teguran 1
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="teguran1m">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-100 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/peace_hand.png" class="size-24 scale-x-[-1]"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Teguran 2
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="teguran2m">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 mb-3">
                            <div class="col-span-9 md:col-span-10">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-4 md:col-span-2 md:me-3">
                                        <div class="bg-red-300 rounded shadow-xl flex justify-center items-center">
                                            <img src="../assets/Assets/raising_hand.png" class="size-24 scale-x-[-1]"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="col-span-8 md:col-span-10">
                                        <div class="flex items-center h-full px-5 text-2xl">
                                            Peringatan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 md:col-span-2">
                                <div
                                    class="bg-red-100 flex justify-center items-center h-24 rounded shadow-xl shadow-neutral-300">
                                    <div class="text-neutral-800 text-2xl" id="peringatanm">
                                        1
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script>

        function calldata() {
            var elemenDiv = document.getElementById("arenaid");
            var arena = elemenDiv.getAttribute("name");
            var element2 = document.getElementById("idpartai");
            var partai = element2.getAttribute("name");

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

            function data() {
                $.ajax({
                    url: '/call-data/?tipe=tanding&arena=' + arena + '&partai=' + partai + '',
                    method: 'GET',
                    success: function (response) {
                        console.log(response);
                        if (response.status != "pause") {
                            if (response.time != 0) {
                                const timer = getselisih(response.time);
                                $('#timer1').text(timer);
                            } else {
                                const timer = getselisih();
                                $('#timer1').text(timer);
                            }
                        } else {

                            $('#timer1').text("pause");
                        }

                        $('#pukulanb').text(response.pukulanb);
                        $('#pukulanm').text(response.pukulanm);
                        $('#tendanganb').text(response.tendanganb);
                        $('#tendanganm').text(response.tendanganm);

                        $('#binaan1b').text(response.totalBinaan1Biru)
                        $('#binaan2b').text(response.totalBinaan2Biru)
                        $('#teguran1b').text(response.totalTeguran1Biru)
                        $('#teguran2b').text(response.totalTeguran2Biru)
                        $('#peringatanb').text(response.totalPeringatan1);
                        $('#jatuhanb').text(response.jatuh1);

                        $('#binaan1m').text(response.totalBinaan1Merah ?? 0)
                        $('#binaan2m').text(response.totalBinaan2Merah ?? 0)
                        $('#teguran1m').text(response.totalTeguran1Merah ?? 0)
                        $('#teguran2m').text(response.totalTeguran2Merah ?? 0)
                        $('#peringatanm').text(response.totalPeringatan2);
                        $('#jatuhanm').text(response.jatuh2);

                        //rekap Params
                        const params = new URLSearchParams(window.location.search);
                        const isDewan = params.get('isDewan');

                        if (response.statusPertandingan == "proses" || response.statusPertandingan == "pending") {
                            if (isDewan) {
                                window.location.href = `redirect?name=8&arena=${arena}&role=dewan-tanding`;
                            }
                            else {
                                window.location.href = `redirect?arena=${arena}&role=score`;
                            }
                        }

                        // console.log(timer);
                        $('#partai').text(`Partai ${response.partai}`);
                        $('#namaBiru').text(response.namaBiru);
                        $('#namaMerah').text(response.namaMerah);
                        $('#kontigenBiru').text(response.kontigenBiru);
                        $('#kontigenMerah').text(response.kontigenMerah);
                        $('#jatuh2').text('' + ' ' + response.jatuh1);
                        $('#jatuh1').text('' + ' ' + response.jatuh2);
                        $('#bina2').text('x' + ' ' + response.binaan1);
                        $('#bina1').text('x' + ' ' + response.binaan2);
                        $('#teguran2').text('x' + ' ' + response.teguran1);
                        $('#teguran1').text('x' + ' ' + response.teguran2);
                        $('#peringatan2').text('x' + ' ' + response.peringatan1);
                        $('#peringatan1').text('x' + ' ' + response.peringatan2);
                        $('#scoreBiru').text(response.score1);
                        $('#scoreMerah').text(response.score2);

                        if (response.notif != "not") {
                            var name = "modal" + response.notif
                            let myModal = new bootstrap.Modal(document.getElementById(name));
                            lastmodal = name;
                            let juri1 = document.getElementById('juri1-' + response.notif);
                            let juri2 = document.getElementById('juri2-' + response.notif);
                            let juri3 = document.getElementById('juri3-' + response.notif);
                            $.ajax({
                                url: '/call-data/?tipe=notif&arena=' + arena + '&status=' + response
                                    .notif,
                                method: 'GET',
                                success: function (response) {
                                    let master = response.data
                                    master.forEach(data => {
                                        if (juri1) {
                                            if (data.id_juri == "Juri_1") {
                                                if (data.score == "biru") {
                                                    juri1.classList.replace('bn', 'bb');
                                                } else if (data.score == "merah") {
                                                    juri1.classList.replace('bn', 'br');
                                                } else {
                                                    juri1.classList.replace('bn', 'by');
                                                }
                                            }

                                        }
                                        if (juri2) {
                                            if (data.id_juri == "Juri_2") {
                                                if (data.score == "biru") {
                                                    juri2.classList.replace('bn', 'bb');
                                                } else if (data.score == "merah") {
                                                    juri2.classList.replace('bn', 'br');
                                                } else {
                                                    juri2.classList.replace('bn', 'by');
                                                }
                                            }

                                        }
                                        if (juri3) {
                                            if (data.id_juri == "Juri_3") {
                                                if (data.score == "biru") {
                                                    juri3.classList.replace('bn', 'bb');
                                                } else if (data.score == "merah") {
                                                    juri3.classList.replace('bn', 'br');
                                                } else {
                                                    juri3.classList.replace('bn', 'by');
                                                }
                                            }

                                        }
                                        console.log(data);
                                    });

                                }
                            });
                            launchModal(myModal);
                        } else if (response.notif == "not") {
                            //CloseModal(lastmodal);
                        }

                    }
                });
            }

            data();
        }

        setInterval(calldata, 900);
    </script>
</body>

</html>