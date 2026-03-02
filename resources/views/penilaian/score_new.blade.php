<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score</title>
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <style>
        @font-face {
            font-family: 'Poppins Regular';
            src: url("{{ asset('assets/fonts/poppins/Poppins-Regular.ttf') }}") format('truetype');
        }

        .poppins-regular {
            font-family: 'Poppins Regular', sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .by {
            background-color: yellow;
        }

        .bn {
            background-color: white;
        }

        .by2 {
            background-image: linear-gradient(to left, #eab308, #fde047)
        }

        .bb2 {
            background-color: #3F83F8;
        }

        .br2 {
            background-color: #F05252;
        }

        .bb {
            background-color: blue;
        }

        .br {
            background-color: red;
        }

        .border-black {
            border: 1px solid black;
        }

        .border-black-2 {
            border: 2px solid black;
        }

        .border-blue {
            border: 2px solid blue;
        }

        .border-red {
            border: 2px solid red;
        }

        .icon-size {
            width: auto;
            height: 5vh;
        }


        .icon-size-2 {
            width: auto;
            height: 15vh;
        }
    </style>
    @php
        // use Alert;
        use App\score;
        use App\Setting;
        use App\KontigenModel;
        use App\PersertaModel;
        use App\kelas;
        use App\arena;
        use App\jadwal_group;
        $data = Setting::where('arena', $arena)->first();
        $babak = $data->babak;
    @endphp
</head>

<!-- <body style="background: linear-gradient(to right, #7575ffff, rgba(255,255,255,0.5), #ff7575ff); min-height: 100vh;"> -->

<body>
    @php
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

        // function cekImage($filename): Boolean
        // {
        //     if ($filename == '') {
        //         return false;
        //     } elseif (File::exists(public_path("symbolic/Assets/uploads/$filename"))) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
    @endphp
    @php
        $jatuh = score::where('keterangan', 'jatuh')->where('id_perserta', '2')->count();
        $bina = score::where('keterangan', 'binaan')->where('id_perserta', '2')->count();
        $teguran = score::where('keterangan', 'teguran')->where('id_perserta', '2')->count();
        $peringatan = score::where('keterangan', 'peringatan')->where('id_perserta', '2')->count();
    @endphp
    <!-- Header Content -->
    <header>
        <div class="d-none" name="{{ $babak }}" id="babakid"></div>
        <div class="d-none" name="{{ $arena }}" id="arenaid"></div>
        <div class="hidden" id="idpartai" name="{{ $partai }}"></div>
    </header>
    <section>
        <section>
            <nav class=" w-full z-20 top-0 px-0 start-0 border-b border-gray-200 dark:border-gray-600"
                style="background: linear-gradient(to right, #000000, #727272ff, #000000);">
                <div id="idpartai" name="{{ $partai }}" class="grid grid-cols-3 " style="padding: 0px 8vh;">
                    <!-- Judul Turnamen -->
                    <div class="text-neutral-200 font-semibold text-start w-full h-full flex justify-start items-center"
                        style="font-size: 1.5vh;">
                        {{ $settingData->judul ?? '' }}
                    </div>
                    <div class="h-full my-0 relative hidden w-full lg:flex items-start justify-center">
                        <div class="justify-center items-start flex ">
                            <img src="../assets/Assets/header_ayosilat.png" style="height: 8vh; width: 32vh;" alt="">
                        </div>
                        <!-- Text Header -->
                        <div class="absolute top-1 text-center w-full text-white font-bold uppercase"
                            style="font-size: 3vh;">
                            {{ $dataArena->name }}
                        </div>
                    </div>
                    <!-- Loop Logo -->
                    <div class="hidden lg:grid">
                        <div class="col-span-2 lg:flex flex-row items-center justify-end" style="gap: 1.5vh;">
                            <img src="{{ asset("/assets/Assets/uploads/$imgData1") }}" style="width: 5vh; height: 5vh;"
                                alt="">
                            <img src="{{ asset("/assets/Assets/uploads/$imgData2") }}" style="width: 5vh; height: 5vh;"
                                alt="">
                        </div>
                    </div>
                </div>
            </nav>
        </section>
        <section>
            <div class="grid grid-cols-3" style="padding: 0.2vh 8vh;">
                <!-- Detail Player Biru -->
                <div class="flex flex-row items-center">
                    <div class="border-blue-600 rounded-full flex items-center justify-center"
                        style="padding: 1.5vh; width: 6vh; height: 6vh; border-width: 0.5vh;">
                        <img src="../assets/Assets/karate.png" style="width: 4.5vh; height: 3vh;" alt="">
                    </div>
                    <div class="flex items-center flex-col" style="padding: 1.5vh;">
                        <!-- Kontigen -->
                        <div class="text-blue-600 uppercase font-bold w-full" id="kontigenBiru"
                            style="font-size: 1.5vh;">
                            {{ $kontigen1->kontigen }}
                        </div>
                        <!-- Nama Peserta -->
                        <div class="w-full font-bold uppercase" id="namaBiru" style="font-size: 2.5vh;">
                            {{ $pesertabiru->name }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="h-100 content-center lg:my-0 text-center">
                        <div class="h-[12vh] w-full rounded-md bg-gradient-to-r from-blue-400 to-blue-600 ">
                            <div class="bg-white w-full h-full rounded flex items-center justify-center">
                                <span
                                    class="relative transition-all ease-in duration-75 bg-gradient-to-tr from-neutral-900 to-neutral-900 inline-block text-transparent bg-clip-text w-full h-full font-bold rounded-md group-hover:bg-opacity-0">
                                    <div class="text-center" id="timer1" style="font-size: 5vh;">
                                        03:00
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-end items-center">
                    <div class="flex items-center flex-col" style="padding: 1.5vh;">
                        <!-- Kontigen Merah -->
                        <div class="text-red-600 font-bold text-end w-full uppercase" id="kontigenMerah"
                            style="font-size: 1.5vh;">
                            {{ $kontigen2->kontigen }}
                        </div>
                        <!-- Nama Peserta Merah -->
                        <div class="w-full text-end font-bold uppercase" id="namaMerah" style="font-size: 2.5vh;">
                            {{ $pesertamerah->name }}
                        </div>
                    </div>
                    <div class="border-red-600 rounded-full flex items-center justify-center "
                        style="padding: 1.5vh; width: 6vh; height: 6vh; border-width: 0.5vh;">
                        <img src="../assets/Assets/karate (1).png" style="width: 4.5vh; height: 3vh;" alt="">
                    </div>
                </div>
            </div>
        </section>
        <hr class="w-full" style="background-color: black; height: 0.5vh; margin-bottom: 1vh;">
        <!-- Mid Section -->
        <div class="md:grid md:grid-cols-12 mb-[2vw]" style="padding: 0vh 8vh;">
            <div class="col-span-1 flex flex-wrap justify-center w-full" style="gap: 0px 1vh;">
                <img id="binaan1" src="../assets/Assets/pointing_hand.png" class="brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="binaan2" src="../assets/Assets/peace_hand.png" class="rotate-90 brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="teguran1" src="../assets/Assets/pointing_hand.png" class="-rotate-90 brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="teguran2" src="../assets/Assets/peace_hand.png" class="brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="peringatan1" src="../assets/Assets/raising_hand.png" class="brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="peringatan2" src="../assets/Assets/raising_hand.png" class="brightness-75 "
                    style="width: auto; height: 3vw;" alt="">
                <img id="peringatan3" src="../assets/Assets/raising_hand.png" class="brightness-75"
                    style="width: auto; height: 3vw;" alt="">
            </div>
            <div class="col-span-4">
                <div id="scorebiru"
                    class="border-blue poppins-regular text-blue-500 rounded flex items-center justify-center bg-blue-50 transition-all h-full"
                    style="background: linear-gradient(to top, #0853D2, #04245c);">
                    <div id="score2" name="{{ $id_perserta_1 }}" style="font-size: 30vh;">
                        40
                    </div>
                </div>
            </div>
            <div class="col-span-2 px-3 flex flex-col justify-center items-center h-full">
                <div class="flex flex-col justify-center items-center">
                    <div style="font-size: 2vh"
                        class=" font-semibold uppercase text-center  bg-gradient-to-r from-amber-500 to-amber-700 text-transparent bg-clip-text"
                        id="partai">

                    </div>
                    <div style="font-size: 1.8vh" class=" font-bold uppercase text-center">
                        Kelas C | Putra
                    </div>
                    <!-- <img src="{{ asset('/assets/Assets/IPSI.png') }}" class="icon-size-2" alt=""> -->
                </div>
                <div class="mt-5">
                    <div class="text-center text-red-800 fw-bold" style="font-size: 2.5vh;">
                        ROUND
                    </div>
                    <div class="flex flex-col justify-center mb-5 items-center gap-1 w-full">
                        <div id="babaksatu" class="border-black text-center border border-red-700 rounded"
                            style="font-size: 3.5vh; width: 14vh;">
                            <div class="text-red-700">
                                1
                            </div>
                        </div>
                        <div id="babakdua" class="border-black text-center border border-red-700 rounded"
                            style="font-size: 3.5vh; width: 14vh;">
                            <div class="text-red-700">
                                2
                            </div>
                        </div>
                        <div id="babaktiga" class="border-black text-center border border-red-700 rounded"
                            style="font-size: 3.5vh; width: 14vh;">
                            <div class="text-red-700">
                                3
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="{{ asset('assets/Assets/Ayo Silat.png') }}" class="icon-size-2" alt="">
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <div id="scoremerah"
                    class="border-red poppins-regular text-red-500  rounded flex items-center justify-center h-full bg-red-50 transition-all"
                    style="background: linear-gradient(to top, #ff2727, #520a0a);">
                    <div id="score1" name="{{ $id_perserta_2 }}" style="font-size: 30vh;">
                        40
                    </div>
                </div>
            </div>
            <div class="col-span-1 flex flex-wrap justify-center w-full " style="gap: 0px 1vh;">
                <img id="mbinaan2" src="../assets/Assets/peace_hand.png" class="-rotate-90 scale-x-[-1] brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="mbinaan1" src="../assets/Assets/pointing_hand.png" class="scale-x-[-1] brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="mteguran2" src="../assets/Assets/peace_hand.png" class=" brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="mteguran1" src="../assets/Assets/pointing_hand.png" class="-rotate-90 brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="mperingatan2" src="../assets/Assets/raising_hand.png" class="brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="mperingatan1" src="../assets/Assets/raising_hand.png" class=" brightness-75"
                    style="width: auto; height: 3vw;" alt="">
                <img id="mperingatan3" src="../assets/Assets/raising_hand.png" class="brightness-75"
                    style="width: auto; height: 3vw;" alt="">
            </div>
        </div>

        <div class="md:grid md:grid-cols-12" style="margin: 2vh 8vh; gap: 1vh;">
            <div class="col-span-1 md:mb-0 h-full border-blue rounded-xl overflow-hidden">
                <div class="flex flex-col h-full">
                    <div class="text-center flex-none flex bg-gradient-to-r from-blue-700 to-blue-500 text-neutral-100 justify-center py-1"
                        style="font-size: 2vh;">
                        Jatuhan
                    </div>
                    <div class="flex-grow flex justify-center items-center text-blue-500">
                        <div id="jatuh2" name="{{ $id_perserta_1 }}" style="font-size: 4vh;">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 md:mb-0 h-full">
                <div class="grid h-full grid-cols-1">
                    <div class="grid grid-cols-3 mb-3 " style="gap: 1vh;">
                        <div id="juri3bp"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-blue-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J3
                        </div>
                        <div id="juri2bp"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-blue-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J2
                        </div>
                        <div id="juri1bp"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-blue-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J1
                        </div>
                    </div>
                    <div class="grid grid-cols-3" style="gap: 1vh;">
                        <div id="juri3bt"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-blue-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J3
                        </div>
                        <div id="juri2bt"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-blue-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J2
                        </div>
                        <div id="juri1bt"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-blue-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J1
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-span-2 h-full md:mb-0">
                <div class="grid grid-cols-1 h-full" style="gap: 1vh;">
                    <div
                        class="bg-gradient-to-b from-neutral-900 to-neutral-400 flex-1 rounded shadow-xl py-1 flex justify-center items-center w-full">
                        <img src="../assets/Assets/fist (2).png" style="height: 4.5vh; width: 4.5vh;" alt="">
                    </div>
                    <div
                        class="bg-gradient-to-t from-neutral-900 to-neutral-400 flex-1 rounded shadow-xl py-1 flex justify-center items-center w-full">
                        <img src="../assets/Assets/kick.png" style="height: 4.5vh; width: 4.5vh;" alt="">
                    </div>
                </div>
            </div>
            <div class="col-span-4 h-full md:mb-0">
                <div class="grid h-full grid-cols-1">
                    <div class="grid grid-cols-3 mb-3" style="gap: 1vh;">
                        <div id="juri1mp"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-red-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J1
                        </div>
                        <div id="juri2mp"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-red-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J2
                        </div>
                        <div id="juri3mp"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-red-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J3
                        </div>
                    </div>
                    <div class="grid grid-cols-3" style="gap: 1vh;">
                        <div id="juri1mt"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-red-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J1
                        </div>
                        <div id="juri2mt"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-red-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J2
                        </div>
                        <div id="juri3mt"
                            class="border-black border-3 flex justify-center items-center rounded-xl text-center text-red-500 font-semibold"
                            style="font-size: 2.5vh;">
                            J3
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1 md:mb-0 border-red rounded-xl overflow-hidden h-full">
                <div class="flex flex-col h-full">
                    <div class="text-center flex-none flex bg-gradient-to-l from-red-700 to-red-500 text-neutral-100 justify-center py-1"
                        style="font-size: 2vh;">
                        Jatuhan
                    </div>
                    <div class="flex flex-grow justify-center items-center text-red-500">
                        <div id="jatuh1" name="{{ $id_perserta_2 }}" style="font-size: 4vh;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Running Text -->
    <footer>
        <div class="fixed w-full h-[6vh] bg-black bottom-0 flex items-center">
            <marquee class="text-neutral-100" style="font-size: 2.5vh;">
                {{ $settingData->arena }}
            </marquee>
        </div>
    </footer>

    {{-- Modal Section --}}
    {{-- Jatuhan --}}
    <div class="modal px-5" tabindex="-1" role="dialog" id="modaljatuhan">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 100%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keputusan <span class="text-danger">Dewan Juri</span></h5>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 text-center" style="font-size: 5vh;">
                                    Verifikasi <span class="text-primary">Jatuhan</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <table class="table table-bordered border-black">
                                        <thead>
                                            <tr class="text-center">
                                                <th>
                                                    <div class="container border fw-bold uppercase border-black py-3 "
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Juri 1
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container border fw-bold uppercase border-black py-3"
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Juri 2
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container border fw-bold uppercase border-black py-3"
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Juri 3
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div id="juri1-jatuhan" class="container py-5 bn">

                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri2-jatuhan" class="container py-5 bn">

                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri3-jatuhan" class="container py-5 bn">

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="container border fw-bold uppercase text-center border-black py-3 "
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Hasil Keputusan Juri
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="container py-5 bn text-center font-bold"
                                                        style="color: red; font-size: 2.5vh;">
                                                        Sudut Merah
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hukuman --}}
    <div class="modal" tabindex="-1" role="dialog" id="modalhukuman">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 100%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keputusan <span class="text-danger">Dewan Juri</span></h5>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 text-center" style="font-size: 5vh;">
                                    Verifikasi <span class="text-danger">Hukuman</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <table class="table table-bordered border-black">
                                        <thead>
                                            <tr class="text-center">
                                                <th>
                                                    <div class="container fw-bold uppercase border-black py-3"
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Juri 1
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container fw-bold uppercase border-black py-3"
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Juri 2
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container fw-bold uppercase border-black py-3"
                                                        style="background-color:rgb(41, 41, 41); color: #f5f5f5; font-size: 2.5vh;">
                                                        Juri 3
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div id="juri1-hukuman" class="container py-5 bn">

                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri2-hukuman" class="container py-5 bn">

                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri3-hukuman" class="container py-5 bn">

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
    {{--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script> --}}
    @include('addon.tanding.core')
</body>

</html>