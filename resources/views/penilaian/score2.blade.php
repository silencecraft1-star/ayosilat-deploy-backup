<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    height: {
                        '128': '32rem',
                    }
                }
            }
        }
    </script>
    <style>
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
    <!-- Header Content -->
    <header>
        <div class="d-none" name="{{ $babak }}" id="babakid"></div>
        <div class="d-none" name="{{ $arena }}" id="arenaid"></div>
        <!-- Navbar Section -->
        <section>
            <nav class="bg-blue-600  w-full z-20 top-0 px-0   start-0 border-b border-gray-200 dark:border-gray-600">
                <div id="idpartai" name="{{ $partai }}" class="grid grid-cols-3 lg:px-20">
                    <!-- Judul Turnamen -->
                    <div class="text-white text-start py-4 text-6xl lg:text-xl ">
                        {{ $settingData->judul ?? '' }}
                    </div>
                    <div class="h-full my-0 relative hidden lg:block">
                        <img src="../assets/Assets/header.png" class="h-12 w-full" alt="">
                        <!-- Text Header -->
                        <div class="absolute top-1 text-center w-full text-white text-3xl uppercase">
                            {{ $dataArena->name }}
                        </div>
                    </div>
                    <!-- Loop Logo -->
                    <div class="hidden lg:flex justify-end my-2 h-full gap-2">
                        <img src="{{ asset("/assets/Assets/uploads/$imgData1") }}" class="size-12 " alt="">
                        <img src="{{ asset("/assets/Assets/uploads/$imgData2") }}" class="size-12" alt="">
                    </div>
                </div>
            </nav>
        </section>
        <!-- Player Info Section -->
        <section>
            <div class="grid grid-cols-3 px-10 py-4 ">
                <!-- Detail Player Biru -->
                <div class="flex flex-wrap ">
                    <div class="border-blue-600 border-4 rounded-full p-2">
                        <img src="../assets/Assets/karate.png" class="size-10" alt="">
                    </div>
                    <div class="flex items-center px-2 pt-2 flex-col">
                        <!-- Kontigen -->
                        <div class="text-blue-600 text-2xl font-extrabold w-full" id="kontigenBiru">
                            {{ $kontigen1->kontigen }}
                        </div>
                        <!-- Nama Peserta -->
                        <div class="w-full text-4xl text-bold" id="namaBiru">
                            {{ $pesertabiru->name }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="h-100 content-center w-full lg:my-0 lg:w-64 text-center">
                        <div class="h-24 w-full rounded-md bg-gradient-to-r from-blue-400 to-blue-600 p-1">
                            <div class="bg-white w-full h-full rounded">
                                <span
                                    class="relative transition-all ease-in duration-75 bg-gradient-to-tr from-blue-400 to-blue-600 inline-block text-transparent bg-clip-text w-full h-full  text-3xl font-bold rounded-md group-hover:bg-opacity-0">
                                    <div class="text-center text-7xl" id="timer1">
                                        00:00
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap justify-end">
                    <div class="flex items-center px-2 pt-2 flex-col">
                        <!-- Kontigen Merah -->
                        <div class="text-red-600 text-xl font-bold text-end w-full" id="kontigenMerah">
                            {{ $kontigen2->kontigen }}
                        </div>
                        <!-- Nama Peserta Merah -->
                        <div class="w-full text-3xl"id="namaMerah">
                            {{ $pesertamerah->name }}
                        </div>
                    </div>
                    <div class="border-red-600 border-4 rounded-full p-2">
                        <img src="../assets/Assets/karate (1).png" class="size-10" alt="">
                    </div>
                </div>
            </div>
        </section>
    </header>
    <hr class="h-1 dark:bg-blue-500 bg-blue-500">
    <!-- Timer Section -->
    <section>
        <div class="w-full flex justify-center flex-col my-2">
            <div class="text-4xl uppercase" id="partai">

            </div>
            <img src="{{ asset('Assets/IPSI.png') }}" alt="">
        </div>
    </section>

    @php
        $jatuh = score::where('keterangan', 'jatuh')->where('id_perserta', '2')->count();
        $bina = score::where('keterangan', 'binaan')->where('id_perserta', '2')->count();
        $teguran = score::where('keterangan', 'teguran')->where('id_perserta', '2')->count();
        $peringatan = score::where('keterangan', 'peringatan')->where('id_perserta', '2')->count();
    @endphp

    <!-- Score Section -->
    <section>
        <div class="lg:grid lg:grid-cols-7 w-full mt-2 lg:h-[32rem]">
            <!-- Detail biru -->
            <div class="flex items-center w-full lg:h-full mb-5 lg:mb-0">
                <div class="w-full h-full">
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div class="flex flex-wrap">
                            <div>
                                <img src="../assets/Assets/point blue.png" class="size-8" alt="">
                            </div>
                            <div class="text-black text-3xl">
                                Binaan
                            </div>
                        </div>
                        <div id="bina2"name="{{ $id_perserta_1 }}" class="text-black text-3xl me-3">
                            x{{ $bina }}
                        </div>
                    </div>
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div class="flex flex-wrap">
                            <div>
                                <img src="../assets/Assets/man blue.png" class="size-8" alt="">
                            </div>
                            <div class="text-black text-3xl">
                                Teguran
                            </div>
                        </div>
                        <div id="teguran2"name="{{ $id_perserta_1 }}" class="text-black text-3xl me-3">
                            x{{ $teguran }}
                        </div>
                    </div>
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div class="flex flex-wrap">
                            <div>
                                <img src="../assets/Assets/signal blue.png" class="size-8" alt="">
                            </div>
                            <div class="text-black text-3xl">
                                Peringatan
                            </div>
                        </div>
                        <div id="peringatan2"name="{{ $id_perserta_1 }}" class="text-black text-3xl me-3">
                            x{{ $peringatan }}
                        </div>
                    </div>
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div class="flex flex-wrap">
                            <div>
                                <img src="../assets/Assets/judo blue.png" class="size-8" alt="">
                            </div>
                            <div class="text-black text-3xl">
                                Jatuhan
                            </div>
                        </div>
                        <div id="jatuh2"name="{{ $id_perserta_1 }}" class="text-black text-3xl me-3">
                            x {{ $jatuh }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Score Biru -->
            <div class="col-span-2">
                <div
                    class="bg-gradient-to-r from-blue-500 to-blue-700 w-full h-80 lg:h-full rounded-lg flex items-center justify-center">
                    <div id="score2" name="{{ $id_perserta_1 }}" class="text-white" style="font-size: 18rem;">

                    </div>
                </div>
            </div>
            <!-- Babak Section -->
            <div class="flex items-center my-10 lg:my-0">
                <div class="flex flex-col items-center w-full gap-2">
                    <div class="mx-auto max-w-screen-sm w-36 ">
                        <div
                            class="h-12 w-full rounded-md bg-gradient-to-r from-red-500 via-orange-400 to-yellow-600 p-1">
                            <div class="bg-white w-full h-full rounded">
                                <span
                                    class="relative transition-all ease-in duration-75 bg-gradient-to-tr from-red-500 via-orange-400 to-yellow-600 inline-block text-transparent bg-clip-text w-full h-full  text-3xl font-bold rounded-md group-hover:bg-opacity-0">
                                    <div class="text-center">
                                        Babak
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="border border-yellow-500 w-full rounded">
                        <div id="babaksatu" class="text-center text-2xl py-3 ">
                            Babak 1
                        </div>
                        <hr class="dark:bg-yellow-500 bg-yellow-500 h-0.5">
                        <div id="babakdua" class="text-center text-2xl py-3">
                            Babak 2
                        </div>
                        <hr class="dark:bg-yellow-500 bg-yellow-500 h-0.5">
                        <div id="babaktiga" class="text-center text-2xl py-3">
                            Babak 3
                        </div>
                    </div>
                </div>
            </div>
            <!-- Score Merah -->
            <div class="col-span-2">
                <div
                    class="bg-gradient-to-l from-red-500 to-red-700 w-full h-80 lg:h-full rounded-lg flex items-center justify-center">
                    <div id="score1" name="{{ $id_perserta_2 }}" class="text-white" style="font-size: 18rem;">

                    </div>
                </div>
            </div>
            @php
                $jatuh = score::where('keterangan', 'jatuh')->where('id_perserta', '1')->count();
                $bina = score::where('keterangan', 'binaan')->where('id_perserta', '1')->count();
                $teguran = score::where('keterangan', 'teguran')->where('id_perserta', '1')->count();
                $peringatan = score::where('keterangan', 'peringatan')->where('id_perserta', '1')->count();
            @endphp
            <div class="flex items-center w-full lg:h-full lg:mt-0">
                <div class="w-full h-full">
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div id="bina1" name="{{ $id_perserta_2 }}" class="text-black text-4xl ms-3">
                            x{{ $bina }}
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <div class="text-black text-4xl">
                                Binaan
                            </div>
                            <div>
                                <img src="../assets/Assets/point red.png" class="size-8" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div class="text-black text-3xl ms-3" id="teguran1" name="{{ $id_perserta_2 }}">
                            x3
                        </div>
                        <div class="flex flex-wrap">
                            <div class="text-black text-3xl">
                                Teguran
                            </div>
                            <div>
                                <img src="../assets/Assets/man red.png" class="size-8" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div class=" flex flex-wrap items-center gap-1">
                            <div id="peringatan1"name="{{ $id_perserta_2 }}" class="text-black text-3xl ms-3">
                                x3
                            </div>
                        </div>
                        <div class="flex flex-wrap">
                            <div class="text-black text-3xl">
                                Peringatan
                            </div>
                            <div>
                                <img src="../assets/Assets/signal red.png" class="size-8" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="border border-black w-full h-1/4 flex items-center px-2 flex-wrap justify-between">
                        <div id="jatuh1"name="{{ $id_perserta_2 }}" class="text-black text-3xl ms-3">
                            x3
                        </div>
                        <div class="flex flex-wrap">
                            <div class="text-black text-3xl">
                                Jatuhan
                            </div>
                            <div>
                                <img src="../assets/Assets/olympic-judo-couple-silhouette.png" class="size-8"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notification Session -->
    <section>
        <div class="flex flex-col items-center mt-3 gap-2">
            <div class="flex flex-wrap justify-center w-3/4">
                <div class="w-2/5 flex flex-wrap items-center">
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 text-center" id="juri3bp">
                                J3
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 text-center" id="juri2bp">
                                J2
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 text-center" id="juri1bp">
                                J1
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/5">
                    <div class="bg-gradient-to-b from-black to-slate-500 rounded shadow-lg shadow-slate-600">
                        <div class="text-5xl px-20 py-2 text-white flex justify-center">
                            <img src="../assets/Assets/fist (2).png" class="size-14" alt="">
                        </div>
                    </div>
                </div>
                <div class="w-2/5 flex flex-wrap items-center">
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri1mp">
                                J1
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri2mp">
                                J2
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri3mp">
                                J3
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap justify-center  w-3/4">
                <div class="w-2/5 flex flex-wrap items-center">
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri3bt">
                                J3
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20"id="juri2bt">
                                J2
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri1bt">
                                J1
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/5">
                    <div class="bg-gradient-to-t from-black to-slate-500 rounded shadow-lg shadow-gray-600">
                        <div class="text-5xl py-2 px-20 text-white flex justify-center">
                            <img src="../assets/Assets/kick.png" class="size-14" alt="">
                        </div>
                    </div>
                </div>
                <div class="w-2/5 flex flex-wrap items-center">
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri1mt">
                                J1
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri2mt">
                                J2
                            </div>
                        </div>
                    </div>
                    <div class="py-2 w-1/3">
                        <div class="border border-black rounded">
                            <div class=" text-3xl py-1 px-20" id="juri3mt">
                                J3
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="my-2 text-white">aa</div>
    <!-- Footer Running Text -->
    <footer>
        <div class="fixed bottom-0 w-full">
            <div class="w-full bg-black absolute bottom-0 h-8">
                <marquee behavior="" direction="left" class="text-white">{{ $settingData->arena }}</marquee>
            </div>
            <div class="absolute bg-gray-500 bottom-0 h-8 w-16">
                <div class="flex items-center text-white">
                    AyoSilat
                </div>
            </div>
        </div>
    </footer>
    {{-- Modal Section --}}
    {{-- Jatuhan --}}
    <div class="modal px-5" tabindex="-1" role="dialog" id="modaljatuhan">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 100%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keputusan <span class="text-danger">Dewan Juri</span></h5>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal">
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 text-center fs-1 ">
                                    Verifikasi <span class="text-primary">Jatuhan</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>
                                                    <div class="container border py-3 ">
                                                        Juri 1
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container border py-3">
                                                        Juri 2
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container border py-3">
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
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal">
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 text-center fs-1 ">
                                    Verifikasi <span class="text-danger">Hukuman</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>
                                                    <div class="container py-3">
                                                        Juri 1
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container py-3">
                                                        Juri 2
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container py-3">
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @include('addon.tanding.core')
</body>

</html>
