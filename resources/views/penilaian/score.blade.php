<!DOCTYPE html>
<html lang="en">        
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/score/score.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Score</title>
    @php
        Use App\score;
        Use App\Setting;
        Use App\KontigenModel;
        Use App\PersertaModel;
        use App\kelas;
        use App\jadwal_group;
        $data = Setting::where('arena',$arena)->first();
        $babak = $data->babak;
    @endphp
</head>
<style>
    .by {
        background-color: yellow;
    }

    .bb {
        background-color: blue;
    }

    .br {
        background-color: red;
    }
    
    .total-point-area {
    background-color: var(--biru);
    width: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 5px;
    }

    .total-point-area-red {
        background-color: var(--merah);
        width: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }
    
</style>
<body>
    @php
    
    $plus1 = score::where('status','plus')->where('id_perserta','4')->sum('score');
    $minus1 = score::where('status','minus')->where('id_perserta','4')->sum('score'); 
    $score1 = $plus1 - $minus1;
    $plus2 = score::where('status','plus')->where('id_perserta','6')->sum('score');
    $minus2 = score::where('status','minus')->where('id_perserta','6')->sum('score'); 
    $score2 = $plus2 - $minus2;
    $id_perserta_1 = $data->biru ;
    $id_perserta_2 = $data->merah;
    $pesertabiru = PersertaModel::where('id', $data->biru)->first();
    $pesertamerah = PersertaModel::where('id', $data->merah)->first();
    $kelas = kelas::where('id', $pesertabiru->kelas)->first();
    $kontigen1 = KontigenModel::where('id', $pesertabiru->id_kontigen)->first();
    $kontigen2 = KontigenModel::where('id', $pesertamerah->id_kontigen)->first();
    $cekpartai = jadwal_group::where('arena', $arena)->where('biru', $pesertabiru->id)->where('merah', $pesertamerah->id)->first();
    $partai = $data->partai ?? '';
@endphp
<div class="d-none" name="{{$babak}}" id="babakid"></div>
  <div class="d-none" name="{{$arena}}" id="arenaid"></div>
    <div id="idpartai" name="{{$partai}}" class="header-body">
        <div id=""name="" class="header-title">
        WALIKOTA CUP BLITAR
        </div>
        <div id=""name="" class="mid-header">
            <img src="../assets/Assets/header.png" alt="" style="position: relative; left: -50%; height: 40px; margin-bottom: 30px;">
            <div id=""name="" class="mid-text">
                <div id=""name="" class="mid-text-title " style="margin-left: 10px;">Partai {{ $partai  }}</div>
            </div>
        </div>
        <div id=""name="" class="header-pict">
            <img src="../assets/Assets/blitar.png" alt="" style="width: 100%; height: 60px; margin-left: auto;">
            <img src="../assets/Assets/IPSI.png" alt="" style="width: 100%; height: 60px; margin-left: auto;">
        </div>
    </div>
    <div id=""name="" class="container-fluid">
        <div class="row d-flex align-items-center">
            <div id=""name="" class="col ">
                <div class="row">
                    <div class="col-2 d-flex align-items-end justify-content-end">
                        <div id=""name="" class="blue-player-img">
                            <img src="../assets/Assets/Ellipse 2.png" alt="" style="height: 50px; z-index: 1;">
                            <img src="../assets/Assets/karate.png" alt="" style="height: 35px; position: absolute; right: 0; left: 10px;top: 5px;">
                        </div>
                    </div>
                    <div class="col px-0">
                        <div id=""name="" class=" text-start align-self-center mt-2 fw-bold w-100">
                            <span class="text-primary">
                                {{ $kontigen1->kontigen }}<br>
                            </span>
                            {{ $pesertabiru->name }}
                        </div>
                    </div>
                </div>
            </div>
            <div id=""name="" class="col-3">
                <div id=""name="" id="" class="row">
                    <div class="col d-flex justify-content-center text-center">
                        ARENA {{$arena}} <br>
                        {{ $kelas->name }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col d-flex justify-content-center">
                        <div id="timer1" name="" class="px-5 fs-1 bg-black border border-primary text-light rounded fw-bold">
                            03:00
                        </div>
                    </div>
                </div>
            </div>
            <div id=""name="" class="col" style="width: 200px;">
                <div class="row">
                    <div class="col d-flex justify-content-end justify-content-end">
                        <div id=""name="" class=" text-end align-self-center mt-2 fw-bold w-100">
                            <span class="text-danger">
                                {{ $kontigen2->kontigen }} <br>
                            </span>
                            {{ $pesertamerah->name }}
                        </div>
                    </div>
                    <div class="col-2 d-flex justify-content-start align-items-end">
                        <div id=""name="" class="red-player-img">
                            <img src="../assets/Assets/Ellipse 1.png" alt="" style="height: 50px; z-index: 1;">
                            <img src="../assets/Assets/karate (1).png" alt="" style="height: 35px; position: absolute; right: 0; left: 10px;top: 5px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-primary mt-3 p-0" style="height: 1px">-</div>
    <div id=""name="" class="score-section mt-3">
        <div id=""name="" class="score-area">
            <div id=""name="" class="score-blue">
                @php
                $jatuh = score::where('keterangan','jatuh')->where('id_perserta','2')->count();
                $bina =  score::where('keterangan','binaan')->where('id_perserta','2')->count();
                $teguran = score::where('keterangan','teguran')->where('id_perserta','2')->count();
                $peringatan = score::where('keterangan','peringatan')->where('id_perserta','2')->count();
            @endphp
                <div id=""name="" class="fs-3 ms-4">
                    BINAAN
                </div>
                <div id=""name="" class="" style="position: absolute; left: 12%;">
                    <div id="bina2"name="{{$id_perserta_1}}"  class="score-blue-point">
                        x {{$bina}}
                    </div>
                </div>
                <div id=""name="" class="score-blue-image">
                    <img src="../assets/Assets/point blue.png" alt="" class="point-image">
                </div>
            </div>
            <div id=""name="" class="score-blue">
                <div id=""name="" class="fs-3 ms-4">
                    TEGURAN
                </div>
                <div id=""name="" class="" style="position: absolute; left: 12%;">
                    <div id="teguran2"name="{{$id_perserta_1}}" class="score-blue-point">
                        x {{$teguran}}
                    </div>
                </div>
                <div id=""name="" class="score-blue-image">
                    <img src="../assets/Assets/man blue.png" alt="" class="point-image">
                </div>
            </div>
            <div id=""name="" class="score-blue">
                <div id=""name="" class="score-blue-text">
                    PERINGATAN
                </div>
                <div id=""name="" class="" style="position: absolute; left: 12%;">
                    <div id="peringatan2"name="{{$id_perserta_1}}" class="score-blue-point">
                        x {{$peringatan}}
                    </div>
                </div>
                <div id=""name="" class="score-blue-image">
                    <img src="../assets/Assets/signal blue.png" alt="" class="point-image">
                </div>
            </div>
            <div id=""name="" class="score-blue">
                <div id=""name="" class="score-blue-text">
                    JATUHAN
                </div>
                <div id=""name="" class="" style="position: absolute; left: 12%;">
                    <div id="jatuh2"name="{{$id_perserta_1}}" class="score-blue-point">
                        x {{$jatuh}}
                    </div>
                </div>
                <div id=""name="" class="score-blue-image">
                    <img src="../assets/Assets/judo blue.png" alt="" class="point-image">
                </div>
            </div>
        </div>
        <div id=""name="" class="total-point-area" style="width: 40em; height: 26em;">
            <div id="score2" name="{{$id_perserta_1}}" class="total-point-area-text" style="font-size: 15em"></div>
        </div>
        <div id=""name="" class="babak-area">
            <div id=""name="" class="babak-area-box">
                <div id=""name="" class="babak-area-box-text-babak">BABAK</div>
            </div>
            <div id="babaksatu"name="" class="babak-area-box-ronde ">
                <div id=""name="" class="babak-area-box-text">I</div>
            </div>
            <div id="babakdua"name="" class="babak-area-box-ronde ">
                <div id=""name="" class="babak-area-box-text">II</div>
            </div>
            <div id="babaktiga"name="" class="babak-area-box-ronde ">
                <div id=""name="" class="babak-area-box-text">III</div>
            </div>
        </div>
        <div id=""name="" class="total-point-area-red" style="width: 40em; height: 26em;">
            <div id="score1" name="{{$id_perserta_2}}" class="total-point-area-text" style="font-size: 15em"></div>
        </div>
        @php
        $jatuh = score::where('keterangan','jatuh')->where('id_perserta','1')->count();
        $bina =  score::where('keterangan','binaan')->where('id_perserta','1')->count();
        $teguran = score::where('keterangan','teguran')->where('id_perserta','1')->count();
        $peringatan = score::where('keterangan','peringatan')->where('id_perserta','1')->count();
    @endphp
        <div id=""name="" class="score-area">
            <div id=""name="" class="score-red">
                <div id=""name="" class="" style="position: absolute; right: 11%;">
                    <div id="bina1" name="{{$id_perserta_2}}" class="score-red-point">
                        x{{$bina}}
                    </div>
                </div>
                <div id=""name="" class="score-red-image">
                    <img src="../assets/Assets/point red.png" alt="" class="point-image-red">
                </div>
                <div id=""name="" class="score-red-text">
                    BINAAN
                </div>
            </div>
            <div id="" name="" class="score-red">
                <div id="" name="" class="" style="position: absolute; right: 11%;">
                    <div id="teguran1" name="{{$id_perserta_2}}" class="score-red-point">
                        x 
                    </div>
                </div>
                <div id=""name="" class="score-red-image">
                    <img src="../assets/Assets/man red.png" alt="" class="point-image-red">
                </div>
                <div id=""name="" class="score-red-text">
                    TEGURAN
                </div>
            </div>
            <div id=""name="" class="score-red">
                <div id=""name="" class="" style="position: absolute; right: 11%;">
                    <div id="peringatan1"name="{{$id_perserta_2}}" class="score-red-point">
                        x {{$peringatan}}
                    </div>
                </div>
                <div id=""name="" class="score-red-image">
                    <img src="../assets/Assets/signal red.png" alt="" class="point-image-red">
                </div>
                <div id=""name="" class="score-red-text">
                    PERINGATAN
                </div>
            </div>
            <div id=""name="" class="score-red">
                <div id=""name="" class="" style="position: absolute; right: 11%;">
                    <div id="jatuh1"name="{{$id_perserta_2}}" class="score-red-point">
                        
                    </div>
                </div>
                <div id=""name="" class="score-red-image">
                    <img src="../assets/Assets/olympic-judo-couple-silhouette.png" alt="" class="point-image-red">
                </div>
                <div id=""name="" class="score-red-text">
                    JATUHAN
                </div>
            </div>
        </div>
    </div>
    
    <div id=""name="" class="running-text">
        <img src="../assets/Assets/Ayo Silat.png" alt="" style="width: 70px; background-color: aliceblue; border-radius: 3px; border: 1px solid black;"> 
        <marquee behavior="" direction="Running">
            SH TERATE CUP 1, Ranting Kota, Cabang Kota Kediri Telah Di Mulai || Info Lebih Lanjut kunjungi IG @ayosilat atau menghubungi 0856-4909-2072 || kunjungi ayosilat.com untuk melihat update
        </marquee>
    </div>

    {{-- Modal Section --}}
    {{-- Jatuhan --}}
        <div class="modal px-5"  tabindex="-1" role="dialog" id="modaljatuhan">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 100%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Keputusan <span class="text-danger">Dewan Juri</span></h5>
                <button type="button" 
                    class="btn-close" 
                    aria-label="Close"
                    data-bs-dismiss="modal"
               >
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
                                                    <div id="juri1-jatuhan" class="container py-5 by">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri2-jatuhan" class="container py-5 by">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri3-jatuhan" class="container py-5 by">
                                                        
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
                <button type="button" 
                    class="btn-close" 
                    aria-label="Close"
                    data-bs-dismiss="modal"
               >
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
                                                    <div id="juri1-hukuman" class="container py-5 by">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri2-hukuman" class="container py-5 by">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri3-hukuman" class="container py-5 by">
                                                        
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>