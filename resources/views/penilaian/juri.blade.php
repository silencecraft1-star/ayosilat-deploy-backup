<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pencak Silat</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    {{--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    --}}
    <link rel="stylesheet" href="../assets/juri/style3.css">
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\pending_tanding;
        $setting = Setting::where('arena', $arena)->first();
        $tim_merahs = PersertaModel::where('id', $setting->merah)->first();
        $tim_birus = PersertaModel::where('id', $setting->biru)->first();
        $tim_merah = $tim_merahs->id;
        $tim_biru = $tim_birus->id;
        $id_arena = $arena;
        $babak = Setting::where('arena', $id_arena)->first();
        $babak = $babak->babak;
        $satu = Setting::where('arena', $id_arena)->where('juri_1', $id_juri)->first();
        $dua = Setting::where('arena', $id_arena)->where('juri_2', $id_juri)->first();
        $tiga = Setting::where('arena', $id_arena)->where('juri_3', $id_juri)->first();
        if (!empty($satu)) {
            $idColomName = "Juri_1";
            $nomorjuri = "1";
        } elseif (!empty($dua)) {
            $idColomName = "Juri_2";
            $nomorjuri = '2';
        } elseif (!empty($tiga)) {
            $idColomName = "Juri_3";
            $nomorjuri = "3";
        }

    @endphp
</head>

<body>
    <div class="d-flex justify-start mx-5 mt-3 mb-3">
        <div class="btn1">
            <a href="{{url("/login-juri")}}" style="text-decoration: none;" class="text-dark">Kembali</a>
        </div>
    </div>

    <!-- JURI ARENA -->
    <section id="juri">
        <div class="mx-5 row ">
            <div class="col d-flex align-items-center justify-content-start">
                <div class="border border-primary rounded-circle p-2 me-2">
                    <img src="../assets/Assets/karate.png" style="width: 2em;" alt="">
                </div>
                <div class="text">
                    @php
                        $kontigen = KontigenModel::where('id', $tim_birus->id_kontigen)->first();
                    @endphp
                    <span id="kontigen_biru" class="team fw-bold text-uppercase">{{$kontigen->kontigen}}</span> <br>
                    <span id="name_biru" class="peserta text-primary text-uppercase">{{$tim_birus->name}}</span>
                </div>
            </div>
            <div class="col juri d-flex flex-column justify-content-center align-items-center">
                <h1>{{$idColomName}}</h1>
                <span>{{$setting->judul}}</span>
            </div>
            <div class="col d-flex align-items-center justify-content-end text-end">
                <div class="text">
                    @php
                        $kontigen = KontigenModel::where('id', $tim_merahs->id_kontigen)->first();
                     @endphp
                    <span id="kontigen_merah" class="team fw-bold text-uppercase">{{$kontigen->kontigen}}</span> <br>
                    <span id="name_merah" class="peserta text-danger text-uppercase">{{$tim_merahs->name}}</span>
                </div>
                <div class="border border-danger rounded-circle p-2 ms-2">
                    <img src="../assets/Assets/karate (1).png" style="width: 2em;" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- Scorering  -->
    <section id="scorering" class=" mx-5 d-flex mb-3">
        <!-- Kiri -->
        <div class="blueScore table-responsive">
            <table class="table table-bordered border border-black">
                <thead>
                    <tr>
                        <th scope="col" class=" text-center " colspan="3">Riwayat Point</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data1 = pending_tanding::where('id_perserta', $tim_biru)
                            ->where('status', 'plus')
                            ->where('juri1', $id_juri)
                            ->when($setting->sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where(function ($query) {
                                $query->where('keterangan', 'tendangan')
                                    ->orWhere('keterangan', 'pukulan');
                            })
                            ->where('babak', '1')
                            ->get();
                        $data2 = pending_tanding::where('id_perserta', $tim_biru)
                            ->where('status', 'plus')
                            ->where('juri1', $id_juri)
                            ->where(function ($query) {
                                $query->where('keterangan', 'tendangan')
                                    ->orWhere('keterangan', 'pukulan');
                            })
                            ->when($setting->sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('babak', '2')
                            ->get();
                        $data3 = pending_tanding::where('id_perserta', $tim_biru)
                            ->where('status', 'plus')
                            ->where('juri1', $id_juri)
                            ->where(function ($query) {
                                $query->where('keterangan', 'tendangan')
                                    ->orWhere('keterangan', 'pukulan');
                            })
                            ->when($setting->sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('babak', '3')
                            ->get();
                    @endphp
                    <tr>
                        <td colspan="3" class="d-flex flex-wrap text-truncate" style="height: 40px; max-height: 40px;"
                            id="data1b">
                            @forelse ($data1 as $index => $item)
                                @if($index > 30)
                                @elseif($item->isValid == "false")
                                    <div class="text-decoration-line-through">
                                        {{$item->score}},
                                    </div>
                                @else
                                    {{$item->score}},
                                @endif
                            @empty
                                .
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="d-flex flex-wrap text-truncate" style="height: 40px; max-height: 40px;"
                            id="data2b">
                            @forelse ($data2 as $index => $item)
                                @if($index > 30)
                                @elseif($item->isValid == "false")
                                    <div class="text-decoration-line-through">
                                        {{$item->score}},
                                    </div>
                                @else
                                    {{$item->score}},
                                @endif
                            @empty
                                .
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="d-flex flex-wrap text-truncate" style="height: 40px; max-height: 40px;"
                            id="data3b">
                            @forelse ($data3 as $index => $item)
                                @if($index > 30)
                                @elseif($item->isValid == "false")
                                    <div class="text-decoration-line-through">
                                        {{$item->score}},
                                    </div>
                                @else
                                    {{$item->score}},
                                @endif
                            @empty
                                .
                            @endforelse
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex ">
                <div class="btn-wrap d-flex flex-column gap-2">
                    {{-- btnSkill1 --}}
                    <button style="width: 10em;"
                        name="nj:{{$nomorjuri}} arena:{{$id_arena}} juri:{{$id_juri}} id:{{$tim_biru}} babak:{{$setting->babak}} status:pukulan p:1 keterangan:plus id_biru:{{$tim_biru}}"
                        class="btnSkill1 d-flex align-items-center justify-content-center btn blue-button fs-5 py-4 px-5 px-lg-5 px-md-5 me-1 shadow border-black">
                        <img src="../assets/juri/images/fist.png" style="width: 40px;">
                        <span class="ms-1">Pukulan</span>
                    </button>

                    <button style="width: 10em;"
                        name="nj:{{$nomorjuri}} arena:{{$id_arena}} juri:{{$id_juri}} id:{{$tim_biru}} babak:{{$setting->babak}} status:tendangan p:2 keterangan:plus id_biru:{{$tim_biru}}"
                        class="btnSkill1 d-flex align-items-center justify-content-center btn blue-button fs-5 py-4 px-5 px-lg-5 px-md-5 me-1 shadow border-black">
                        <img src="../assets/juri/images/kick.png" style="width: 40px;">
                        <span class="ms-1">Tendangan</span>
                    </button>
                </div>
                <div class="d-flex justify-content-start align-items-center">
                    <button style="height: 5em;"
                        name="nj:{{$nomorjuri}} arena:{{$id_arena}} juri:{{$id_juri}} id:{{$tim_biru}} babak:{{$setting->babak}} status:hapus p:2 keterangan:plus id_biru:{{$tim_biru}}"
                        class="btnSkill1 d-flex align-items-center justify-content-center btn btn-secondary fs-5 py-2 px-5 px-lg-5 px-md-5 me-1 shadow border-black">
                        {{-- <img src="../assets/juri/images/kick.png" style="width: 20px;"> --}}
                        <span class="ms-1">Hapus Nilai Terakhir</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Tengah -->
        <div class="babak d-flex flex-column align-items-center">
            <table class="table tabelBabak">
                <thead>
                    <tr>
                        <th scope="col" class="text-center border-top border-black"
                            style="background-color: green; color: white;">BABAK</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr>
                        <td class="text-center" @if ($babak=='1' ) style="background-color: lime;" @endif>I</td>
                    </tr> --}}
                    <tr>
                        <td class="text-center" id="babak-cell-1" @if ($babak == '1') style="background-color: lime;"
                        @endif>I</td>
                    </tr>
                    <tr>
                        <td class="text-center" id="babak-cell-2" @if ($babak == '2') style="background-color: lime;"
                        @endif>II</td>
                    </tr>
                    <tr>
                        <td class="text-center" id="babak-cell-3" @if ($babak == '3') style="background-color: lime;"
                        @endif>III</td>
                    </tr>
                </tbody>
            </table>

            @php
                $data = score::where('id_juri', $idColomName)
                    ->where('arena', $arena)
                    ->where('status', 'notif')
                    ->where('keterangan', 'jatuhan')
                    ->first();
                if (!empty($data)) {
                    $check = $data->id_juri;
                } else {
                    $check = "null";
                }
            @endphp

            {{-- <button @if($idColomName==$check) disabled @endif
                class="btn-jatuhan btn btn-secondary d-flex justify-content-center align-items-center px-5 py-2 fs-5 shadow border-light mx-1 mt-1"
                data-bs-toggle="modal" data-bs-target="#ModalJatuhan"> --}}
                {{-- <button
                    class="btn-jatuhan btn btn-secondary d-flex justify-content-center align-items-center px-5 py-2 fs-5 shadow border-light mx-1 mt-1"
                    data-bs-toggle="modal" data-bs-target="#ModalJatuhan">
                    <img src="../assets/Assets/judo white.png" alt="" class="me-1" style="width: 30px;">
                    Verifikasi Jatuhan
                </button> --}}
                @php
                    $data = score::where('id_juri', $idColomName)
                        ->where('arena', $arena)
                        ->where('status', 'notif')
                        ->where('keterangan', 'jatuhan')
                        ->first();
                    if (!empty($data)) {
                        $check = $data->id_juri;
                    } else {
                        $check = "null";
                    }
                @endphp
                {{-- <button @if($idColomName==$check) disable @endif
                    class="btn-jatuhan d-flex btn btn-secondary d-flex justify-content-center align-items-center px-5 py-2 fs-5 shadow border-light mt-2 mx-1"
                    data-bs-toggle="modal" data-bs-target="#ModalHukuman"> --}}
                    {{-- <button
                        class="btn-jatuhan d-flex btn btn-secondary d-flex justify-content-center align-items-center px-5 py-2 fs-5 shadow border-light mt-2 mx-1"
                        data-bs-toggle="modal" data-bs-target="#ModalHukuman">
                        <img src="../assets/Assets/warning.png" alt="" class="me-1" style="width: 30px;">
                        Verifikasi Hukuman
                    </button> --}}

                    <!-- Modal Verifikasi Jatuhan -->
                    <div class="modal fade" id="modaljatuhan" tabindex="-1" aria-labelledby="sexampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Verifikasi Jatuhan</h1>
                                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button> --}}
                                </div>
                                <div class="model-body">
                                    <div class="container-fluid mb-3">
                                        <div class="col d-flex justify-content-center align-items-center fs-5 fw-bold">
                                            Keputusan Juri
                                        </div>
                                        <div class="col h-100">
                                            <div class="row h100">
                                                <div class="col">
                                                    <button
                                                        name="arena:{{$id_arena}} tipe:verifikasi juri:{{$idColomName}} id:{{$tim_biru}} babak:{{$setting->babak}} status:jatuhan p:biru keterangan:notif"
                                                        class="bt-notif notif-biru btn btn-primary w-100 border-black shadow">
                                                        Tim Biru
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button
                                                        name="arena:{{$id_arena}} tipe:verifikasi juri:{{$idColomName}} id:{{$tim_biru}} babak:{{$setting->babak}} status:jatuhan p:invalid keterangan:notif"
                                                        class="bt-notif notif-invalid btn btn-warning w-100 border-black shadow">
                                                        Invalid
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button
                                                        name="arena:{{$id_arena}} tipe:verifikasi juri:{{$idColomName}} id:{{$tim_merah}} babak:{{$setting->babak}} status:jatuhan p:merah keterangan:notif"
                                                        class="bt-notif notif-merah btn btn-danger w-100 border-black shadow">
                                                        Tim Merah
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Verifikasi Hukuman -->
                    <div class="modal fade" id="modalhukuman" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Verifikasi Hukuman</h1>
                                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button> --}}
                                </div>
                                <div class="model-body">
                                    <div class="container-fluid mb-3">
                                        <div class="col d-flex justify-content-center align-items-center fs-5 fw-bold">
                                            Keputusan Juri
                                        </div>
                                        <div class="col h-100">
                                            <div class="row h100">
                                                <div class="col">
                                                    <button
                                                        name="arena:{{$id_arena}} tipe:verifikasi juri:{{$idColomName}} id:{{$tim_biru}} babak:{{$setting->babak}} status:hukuman p:biru keterangan:notif"
                                                        class="bt-notif notif-biru btn btn-primary w-100 border-black shadow">
                                                        Tim Biru
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button
                                                        name="arena:{{$id_arena}} tipe:verifikasi juri:{{$idColomName}} id:{{$tim_biru}} babak:{{$setting->babak}} status:hukuman p:invalid keterangan:notif"
                                                        class="bt-notif notif-invalid btn btn-warning w-100 border-black shadow">
                                                        Invalid
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <button
                                                        name="arena:{{$id_arena}} tipe:verifikasi juri:{{$idColomName}} id:{{$tim_merah}} babak:{{$setting->babak}} status:hukuman p:merah keterangan:notif"
                                                        class="bt-notif notif-merah btn btn-danger w-100 border-black shadow">
                                                        Tim Merah
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="popup" class="black-overlay">
                        <div id="popup2" class="white-content">
                            <div class="pop-header">
                                <div>Verifikasi Juri</div>
                                <a href="javascript:void(0)"
                                    onclick="document.getElementById('popup').style.display = 'none';document.getElementById('popup2').style.display = 'none'"
                                    style="text-decoration: none; color: red; cursor: pointer;">X</a>
                            </div>
                            <div class="pop-content">
                                <table class="table-juri table-bordered border border-black">
                                    <thead>
                                        <tr>
                                            <td scope="col" class="text-center">
                                                Verifikasi Jatuhan
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="col" class="text-center"
                                                style="background-color: rgb(199, 199, 199);">
                                                Keputusan Juri 2
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col" class="text-center"
                                                style="background-color: blue; color: #f5f5f5;">
                                                Biru Valid
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pop-button">
                                <button
                                    name="juri:{{$id_juri}} tipe:verifikasi id:{{$tim_biru}} babak:{{$setting->babak}} status:jatuhan p:0 keterangan:notif"
                                    type="button" class="bt-notif btn btn-primary btn-lg">Tim Biru</button>
                                <button
                                    name="juri:{{$id_juri}} tipe:verifikasi id:{{$tim_merah}} babak:{{$setting->babak}} status:jatuhan p:0 keterangan:notif"
                                    type="button" class="bt-notif btn btn-danger btn-lg">Tim Merah</button>
                                <button type="button" class="btn btn-warning btn-lg">Tim Invalid</button>
                            </div>
                        </div>
                    </div>

                    <div id="popup-hukuman" class="black-overlay">
                        <div id="popup2-hukuman" class="white-content-2">
                            <div class="pop-header">
                                <div>Verifikasi Juri</div>
                                <a href="javascript:void(0)"
                                    onclick="document.getElementById('popup-hukuman').style.display = 'none';document.getElementById('popup2-hukuman').style.display = 'none'"
                                    style="text-decoration: none; color: red; cursor: pointer;">X</a>
                            </div>
                            <div class="pop-content">
                                <table class="table-juri table-bordered border border-black">
                                    <thead>
                                        <tr>
                                            <td scope="col" class="text-center"">
                                    Verifikasi Hukuman
                                </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td scope=" col" class="text-center" style="background-color: rgb(199, 199, 199);">
                                                Keputusan Juri 2
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="col" class="text-center"
                                                style="background-color: blue; color: #f5f5f5;">
                                                Biru Valid
                                            </td>
                                        </tr>
                                        </tbody>
                                </table>
                            </div>
                            <div class="pop-button">
                                <button
                                    name="juri:{{$id_juri}} tipe:verifikasi id:{{$tim_biru}} babak:{{$setting->babak}} status:hukuman p:0 keterangan:notif"
                                    type="button" class="bt-notif btn btn-primary btn-lg">Tim Biru</button>
                                <button
                                    name="juri:{{$id_juri}} tipe:verifikasi id:{{$tim_merah}} babak:{{$setting->babak}} status:hukuman p:0 keterangan:notif"
                                    type="button" class="bt-notif btn btn-danger btn-lg">Tim Merah</button>
                                <button type="button" class="btn btn-warning btn-lg">Tim Invalid</button>
                            </div>
                        </div>
                    </div>

        </div>

        <!-- Kanan -->
        <div class="redScore table-responsive">
            <table class="table table-bordered border border-black">

                <thead>
                    <tr>
                        <th scope="col" class=" text-center" colspan="3">Riwayat Point</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data1 = pending_tanding::where('id_perserta', $tim_merah)
                            ->where('status', 'plus')
                            ->where('juri1', $id_juri)
                            ->where(function ($query) {
                                $query->where('keterangan', 'tendangan')
                                    ->orWhere('keterangan', 'pukulan');
                            })
                            ->when($setting->sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('babak', '1')
                            ->get();
                        $data2 = pending_tanding::where('id_perserta', $tim_merah)
                            ->where('status', 'plus')
                            ->where('juri1', $id_juri)
                            ->where(function ($query) {
                                $query->where('keterangan', 'tendangan')
                                    ->orWhere('keterangan', 'pukulan');
                            })
                            ->when($setting->sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('babak', '2')
                            ->get();
                        $data3 = pending_tanding::where('id_perserta', $tim_merah)
                            ->where('status', 'plus')
                            ->where('juri1', $id_juri)
                            ->where(function ($query) {
                                $query->where('keterangan', 'tendangan')
                                    ->orWhere('keterangan', 'pukulan');
                            })
                            ->when($setting->sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('babak', '3')
                            ->get();
                    @endphp
                    <tr>
                        <td colspan="3" class="d-flex flex-wrap text-truncate" style="height: 40px; max-height: 40px;"
                            id="data1m">
                            @forelse ($data1 as $index => $item)
                                @if($index > 30)
                                @elseif($item->isValid == "false")
                                    <div class="text-decoration-line-through">
                                        {{$item->score}},
                                    </div>
                                @else
                                    {{$item->score}},
                                @endif
                            @empty
                                .
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="d-flex flex-wrap text-truncate" style="height: 40px; max-height: 40px;"
                            id="data2m">
                            @forelse ($data2 as $index => $item)
                                @if($index > 30)
                                @elseif($item->isValid == "false")
                                    <div class="text-decoration-line-through">
                                        {{$item->score}},
                                    </div>
                                @else
                                    {{$item->score}},
                                @endif
                            @empty
                                .
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="d-flex flex-wrap text-truncate" style="height: 40px; max-height: 40px;"
                            id="data3m">
                            @forelse ($data3 as $index => $item)
                                @if($index > 30)
                                @elseif($item->isValid == "false")
                                    <div class="text-decoration-line-through">
                                        {{$item->score}},
                                    </div>
                                @else
                                    {{$item->score}},
                                @endif
                            @empty
                                .
                            @endforelse
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                <div class="d-flex gap-2">
                    <div class="d-flex justify-content-center align-items-center">
                        <button
                            name="nj:{{$nomorjuri}} arena:{{$id_arena}} juri:{{$id_juri}} id:{{$tim_merah}} babak:{{$setting->babak}} status:hapus p:2 keterangan:plus id_merah:{{$tim_merah}}"
                            style="width: 100%; height: 5em;"
                            class="btnSkill2 d-flex align-items-center justify-content-center btn btn-secondary fs-5 py-2 px-0 px-lg-5 px-md-5 shadow border-black">
                            {{-- <img src="../assets/juri/images/kick.png" style="width: 20px;"> --}}
                            <span class="ms-1">Hapus Nilai Terakhir</span>
                        </button>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <button style="width: 10em;"
                            name="nj:{{$nomorjuri}} arena:{{$id_arena}} juri:{{$id_juri}} id:{{$tim_merah}} babak:{{$setting->babak}} status:pukulan p:1 keterangan:plus id_merah:{{$tim_merah}}"
                            class="btnSkill2 d-flex align-items-center justify-content-center btn red-button fs-5 py-4 px-5 px-md-5 ms-1 shadow border-black">
                            <img src="../assets/juri/images/fist.png" style="width: 40px;">
                            <span class="ms-1">Pukulan</span>
                        </button>
                        <button style="width: 10em;"
                            name="nj:{{$nomorjuri}} arena:{{$id_arena}} juri:{{$id_juri}} id:{{$tim_merah}} babak:{{$setting->babak}} status:tendangan p:2 keterangan:plus id_merah:{{$tim_merah}}"
                            class="btnSkill2 d-flex align-items-center justify-content-center btn red-button fs-5 py-4 px-5 px-md-5 ms-1 shadow border-black">
                            <img src="../assets/juri/images/kick.png" style="width: 40px;">
                            <span class="ms-1">Tendangan</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div id="IDarena" name="{{$id_arena}}" class="d-none"></div>
    <input type="hidden" name="{{ $tim_biru }}" id="id_biru">
    <input type="hidden" name="{{ $tim_merah }}" id="id_merah">
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    @include('addon.tanding.reload');
    <script>
        let currentModal = "";
        //websocket
        function websocket() {
            let arena = $('#IDarena').attr('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Soketi!");
                });
                Echo.channel('verification-channel')
                    .listen('VerificationEvent', (datas) => {
                        var data = datas.message;

                        if (arena == data.arena) {

                            if (data.status == "modal") {
                                var name = "modal" + data.type;
                                //let myModal = new bootstrap.Modal(document.getElementById(name));
                                currentModal = name;

                                if (data.command == "open") {
                                    $(`#${name}`).modal('show');
                                }
                                else {
                                    $(`#${name}`).modal('hide');
                                }
                                // alert(`${name}, ${isModalLaunched}` );
                            }
                        }

                        // alert(JSON.stringify(datas.message));
                    });
                Echo.channel('score-channel')
                    .listen('ScoreEvent', (datas) => {
                        var data = datas.message;

                        if (data.arena == arena) {
                            // console.log(data);
                            var idbabak = data.babak;
                            if (idbabak == 1) {
                                $(`#babak-cell-${idbabak}`).css('background-color', 'lime');
                                $(`#babak-cell-2`).css('background-color', 'transparent');
                                $(`#babak-cell-3`).css('background-color', 'transparent');
                            } else if (idbabak == 2) {
                                $(`#babak-cell-${idbabak}`).css('background-color', 'lime');
                                $(`#babak-cell-1`).css('background-color', 'transparent');
                                $(`#babak-cell-3`).css('background-color', 'transparent');
                            } else if (idbabak == 3) {
                                $(`#babak-cell-${idbabak}`).css('background-color', 'lime');
                                $(`#babak-cell-2`).css('background-color', 'transparent');
                                $(`#babak-cell-1`).css('background-color', 'transparent');
                            }

                            assignData(data);
                        }

                    })
                Echo.channel('juri-channel')
                    .listen('JuriEvent', (datas) => {
                        updatePending(datas, arena);
                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
        }

        // Temukan semua tombol dengan kelas "button-blue" atau "button-blue-delete"
        var tombolDenganKelas = document.querySelectorAll('.btnHapus, .btnSkill1 , .btnSkill2 , .bt-notif');

        function updatePending(datas, arena) {
            var idMerah = $('#id_merah').attr('name');
            var idBiru = $('#id_biru').attr('name');

            console.log(datas.message);
            var data = datas.message.data;
            var data2 = datas.message;

            if (arena == data2.arena) {

                $('#data1b').html('');
                $('#data2b').html('');
                $('#data3b').html('');

                $('#data1m').html('');
                $('#data2m').html('');
                $('#data3m').html('');

                let data1b_found = false; // Round 1 Blue
                let data2b_found = false; // Round 2 Blue
                let data3b_found = false; // Round 3 Blue
                let data1m_found = false; // Round 1 Red
                let data2m_found = false; // Round 2 Red
                let data3m_found = false; // Round 3 Red

                data.forEach((data, index) => {

                    // 1. Filter by Judge ID
                    if (data.juri1 != "{{ $id_juri }}") {
                        return; // Use 'return;' to skip to the next item in a forEach loop
                    }

                    // 2. Determine the score value (1 for pukulan, 2 for tendangan)
                    let score = 0;
                    if (data.keterangan == "pukulan") {
                        score = 1;
                    } else if (data.keterangan == "tendangan") {
                        score = 2;
                    } else {
                        // Fallback for any other 'keterangan' value
                        score = 0;
                    }

                    // Determine the output HTML string
                    const output = data.isValid == "false"
                        ? `<div class="text-decoration-line-through">${score},</div>`
                        : `<div>${score},</div>`;

                    // 3. Logic for Blue Team (Tim Biru)
                    if (data.id_perserta == idBiru) {

                        // Round 1
                        if (data.babak == "1") {
                            $('#data1b').append(output);
                            data1b_found = true; // Mark as found
                        }

                        // Round 2
                        if (data.babak == "2") {
                            $('#data2b').append(output);
                            data2b_found = true; // Mark as found
                        }

                        // Round 3
                        if (data.babak == "3") {
                            $('#data3b').append(output);
                            data3b_found = true; // Mark as found
                        }
                    }

                    // 4. Logic for Red Team (Tim Merah)
                    if (data.id_perserta == idMerah) {

                        // Round 1
                        if (data.babak == "1") {
                            $('#data1m').append(output);
                            data1m_found = true; // Mark as found
                        }

                        // Round 2
                        if (data.babak == "2") {
                            $('#data2m').append(output);
                            data2m_found = true; // Mark as found
                        }

                        // Round 3
                        if (data.babak == "3") {
                            $('#data3m').append(output);
                            data3m_found = true; // Mark as found
                        }
                    }
                });

                // --- 5. Post-Loop Check (Insert Placeholder) ---
                const placeholder = '<div>-</div>';

                // Blue Team Checks
                if (!data1b_found) { $('#data1b').append(placeholder); }
                if (!data2b_found) { $('#data2b').append(placeholder); }
                if (!data3b_found) { $('#data3b').append(placeholder); }

                // Red Team Checks
                if (!data1m_found) { $('#data1m').append(placeholder); }
                if (!data2m_found) { $('#data2m').append(placeholder); }
                if (!data3m_found) { $('#data3m').append(placeholder); }


            }

        }

        function assignData(data) {
            //update current juri untuk pending_tanding
            console.log(data);
            $('#id_biru').attr('name', data.idBiru);
            $('#id_merah').attr('name', data.idMerah);

            //assign Button biru
            $(".btnSkill1").each(function () {
                var arr = $(this).attr('name').split(' ');

                arr[3] = `id:${data.idBiru}`; //3 = id_biru
                arr[4] = `babak:${data.babak}`;
                arr[8] = `id_biru:${data.idBiru}`;

                $(this).attr('name', arr.join(' '));
            })

            //assign button merah
            $(".btnSkill2").each(function () {
                var arr = $(this).attr('name').split(' ');

                arr[3] = `id:${data.idMerah}`; //3 = id_biru
                arr[4] = `babak:${data.babak}`;
                arr[8] = `id_merah:${data.idMerah}`;

                $(this).attr('name', arr.join(' '));
            })

            //assign button jatuhan
            $(".bt-notif .notif-biru").each(function () {
                var arr = $(this).attr('name').split(' ');

                arr[3] = `id:${data.idBiru}`;
                arr[4] = `babak:${data.babak}`;
            });

            $(".bt-notif .notif-merah").each(function () {
                var arr = $(this).attr('name').split(' ');

                arr[3] = `id:${data.idMerah}`;
                arr[4] = `babak:${data.babak}`;
            });

            $('#kontigen_biru').text(data.kontigenBiru);
            $('#kontigen_merah').text(data.kontigenMerah);

            $('#name_biru').text(data.namaBiru);
            $('#name_merah').text(data.namaMerah);
        }

        // Loop melalui semua tombol dan tambahkan event listener
        tombolDenganKelas.forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"

                // Membagi nilai atribut "name" menjadi objek JavaScript
                var data = {};
                nameAttribute.split(' ').forEach(function (item) {
                    var parts = item.split(':');
                    data[parts[0]] = parts[1];
                });

                // $('button').prop('disabled', true);

                // Sekarang, Anda memiliki data dalam bentuk objek
                console.log(data);
                // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
                fetch('{{ route('juri.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        // $('button').prop('disabled', false);

                        if (currentModal != "") {
                            $(`#${currentModal}`).modal('hide');
                            currentModal = "";
                        }
                        // reload();
                    })
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        // Tangani kesalahan jika ada
                        //reload();
                    });
                // reload();
                function reload() {
                    window.location.reload();
                }
                // setInterval(reload, 800);
            });
        });
        function reload() {
            // window.location.reload();
        }

        websocket();
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
    {{--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script> --}}
</body>

</html>