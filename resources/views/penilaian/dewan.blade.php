<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('assets/dewanJuri/dewan.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Dewan</title>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\arena;
        use App\jadwal_group;
        $id_arena = $arena;
        $setting = Setting::where('arena', $arena)->first();
        $id_juri = $id_juri;
        $tim_merahs = PersertaModel::where('id', $setting->merah)->first();
        $tim_birus = PersertaModel::where('id', $setting->biru)->first();
        $tim_biru = $tim_birus->id;
        $tim_merah = $tim_merahs->id;
        $jadwalData = jadwal_group::where('arena', $arena)->where('partai', $setting->partai)->where('biru', $setting->biru)->where('merah', $setting->merah)->first();

        $kontigenBiru = KontigenModel::where('id', $tim_birus->id_kontigen)->first();
        $kontigenMerah = KontigenModel::where('id', $tim_merahs->id_kontigen)->first();
        $babak = Setting::where('arena', $arena)->first();
        $babak = $babak->babak;
        $arena = arena::where('id', $setting->arena)->first();
    @endphp
</head>
<style>
    .by {
        background-color: yellow;
    }
</style>

<body>
    <div class="header-body">
        <div class="container px-0 mx-0 ">
            <button class="header-button-kembali">
                <a href="{{ url('/login-juri') }}" style="text-decoration: none;" class="text-dark">Log Out</a>
            </button>
        </div>
        <div class="header-title">
            <div class="header-title-2">
                Blitar Cup
            </div>
        </div>
        <div class="header-pict">
            <img src="../assets/Assets/Ayo Silat.png" alt=""
                style="width: 150%; height: 50px; margin-left: auto; background-color: rgb(154, 154, 154); border-radius: 5px; border: 1px solid black;">
        </div>
    </div>
    <div class="header-text">
        
    </div>
    <div class="player-detail my-2">
        <div>
            <div>
                {{ $tim_birus->name }}
            </div>
            <div>
                {{ $kontigenBiru->name ?? '' }}
            </div>
        </div>
        d
        <div class="player-detail-blue my-2">
            <div>
                <div>
                    {{ $kontigenMerahs->name ?? '' }}
                </div>
                <div>
                    {{ $tim_merahs->name }}
                </div>
            </div>
        </div>
    </div>
    <div class="table-container">
        <table class="table-blue">
            <thead>
                <tr>
                    <th>Jatuhan</th>
                    <th>Binaan</th>
                    <th>Teguran</th>
                    <th>Peringatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $jatuh = score::where('keterangan', 'jatuh')
                        ->where('babak', '1')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $bina = score::where('keterangan', 'binaan')
                        ->where('babak', '1')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $teguran = score::where('keterangan', 'teguran')
                        ->where('babak', '1')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $peringatan = score::where('keterangan', 'peringatan')
                        ->where('babak', '1')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                @endphp
                <tr>
                    <td>{{ $jatuh }}x</td>
                    <td>{{ $bina }}x</td>
                    <td>{{ $teguran }}x</td>
                    <td>{{ $peringatan }}x</td>
                </tr>
                @php
                    $jatuh = score::where('keterangan', 'jatuh')
                        ->where('babak', '2')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $bina = score::where('keterangan', 'binaan')
                        ->where('babak', '2')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $teguran = score::where('keterangan', 'teguran')
                        ->where('babak', '2')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $peringatan = score::where('keterangan', 'peringatan')
                        ->where('babak', '2')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                @endphp
                <tr>
                    <td>{{ $jatuh }}x</td>
                    <td>{{ $bina }}x</td>
                    <td>{{ $teguran }}x</td>
                    <td>{{ $peringatan }}x</td>
                </tr>
                @php
                    $jatuh = score::where('keterangan', 'jatuh')
                        ->where('babak', '3')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $bina = score::where('keterangan', 'binaan')
                        ->where('babak', '3')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $teguran = score::where('keterangan', 'teguran')
                        ->where('babak', '3')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                    $peringatan = score::where('keterangan', 'peringatan')
                        ->where('babak', '3')
                        ->where('id_perserta', $tim_biru)
                        ->count();
                @endphp
                <tr>
                    <td>{{ $jatuh }}x</td>
                    <td>{{ $bina }}x</td>
                    <td>{{ $teguran }}x</td>
                    <td>{{ $peringatan }}x</td>
                </tr>
            </tbody>
        </table>
        <div class="mid-section">
            <div class="babak-box">
                <div class="babak-text">BABAK</div>
            </div>
            <table class="mid-table">
                <tbody>
                    <tr class="
                    @if ($babak == '1') by @endif">
                        <td>I</td>
                    </tr>
                    <tr class="
                    @if ($babak == '2') by @endif">
                        <td>II</td>
                    </tr>
                    <tr class="
                    @if ($babak == '3') by @endif">
                        <td>III</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="table-red">
            <thead>
                <tr>
                    <th>Peringatan</th>
                    <th>Teguran</th>
                    <th>Binaan</th>
                    <th>Jatuhan</th>
                </tr>
            </thead>
            @php
                $jatuh = score::where('keterangan', 'jatuh')
                    ->where('babak', '1')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $bina = score::where('keterangan', 'binaan')
                    ->where('babak', '1')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $teguran = score::where('keterangan', 'teguran')
                    ->where('babak', '1')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $peringatan = score::where('keterangan', 'peringatan')
                    ->where('babak', '1')
                    ->where('id_perserta', $tim_merah)
                    ->count();
            @endphp
            <tr>
                <td>{{ $peringatan }}x</td>
                <td>{{ $teguran }}x</td>
                <td>{{ $bina }}x</td>
                <td>{{ $jatuh }}x</td>
            </tr>
            @php
                $jatuh = score::where('keterangan', 'jatuh')
                    ->where('babak', '2')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $bina = score::where('keterangan', 'binaan')
                    ->where('babak', '2')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $teguran = score::where('keterangan', 'teguran')
                    ->where('babak', '2')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $peringatan = score::where('keterangan', 'peringatan')
                    ->where('babak', '2')
                    ->where('id_perserta', $tim_merah)
                    ->count();
            @endphp
            <tr>
                <td>{{ $peringatan }}x</td>
                <td>{{ $teguran }}x</td>
                <td>{{ $bina }}x</td>
                <td>{{ $jatuh }}x</td>
            </tr>
            @php
                $jatuh = score::where('keterangan', 'jatuh')
                    ->where('babak', '3')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $bina = score::where('keterangan', 'binaan')
                    ->where('babak', '3')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $teguran = score::where('keterangan', 'teguran')
                    ->where('babak', '3')
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $peringatan = score::where('keterangan', 'peringatan')
                    ->where('babak', '3')
                    ->where('id_perserta', $tim_merah)
                    ->count();
            @endphp
            <tr>
                <td>{{ $peringatan }}x</td>
                <td>{{ $teguran }}x</td>
                <td>{{ $bina }}x</td>
                <td>{{ $jatuh }}x</td>
            </tr>
            @php
                $jatuh_babak = score::where('keterangan', 'jatuh')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->count();
                $bina_babak = score::where('keterangan', 'binaan')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->count();
                $teguran_babak = score::where('keterangan', 'teguran')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->count();
                $peringatan_babak = score::where('keterangan', 'peringatan')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->count();
            @endphp
            </tbody>
        </table>
    </div>
    <div class="button-section">
        <div class="button-blue-container">
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:jatuh p:3 keterangan:plus"
                id="kirimData" class="btn btn-primary button-blue">JATUHAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:binaan p:0 keterangan:plus"
                id="kirimData"
                class="btn btn-primary button-blue"@if ($bina_babak === 2) disabled @endif>BINAAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:teguran p:1 keterangan:plus"
                id="kirimData"
                class="btn btn-primary button-blue"@if ($teguran_babak === 2) disabled @endif>TEGURAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:peringatan p:5 keterangan:plus"
                id="kirimData"
                class="btn btn-primary button-blue"@if ($peringatan_babak === 3) disabled @endif>PERINGATAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:jatuh p:3 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS JATUHAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:binaan p:0 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS BINAAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:teguran p:1 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS TEGURAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} status:peringatan p:5 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS PERINGATAN</button>
        </div>
        <div class="mid-container">
            <button type="button" id="btn-veryfication-jatuhan"
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:jatuhan p:on keterangan:notif"
                class="button-jatuhan">Verifikasi <br>Jatuhan</button>
            <button type="button" id="btn-veryfication-hukuman" class="button-jatuhan"
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:hukuman p:on keterangan:notif">Verifikasi
                <br>Hukuman</button>
            <table class="score-table">
                <tbody>
                    @php
                        $plus1 = score::where('status', 'plus')->where('id_perserta', $tim_biru)->sum('score');
                        $minus1 = score::where('status', 'minus')->where('id_perserta', $tim_biru)->sum('score');
                        $score1 = $plus1 - $minus1;
                        $plus2 = score::where('status', 'plus')->where('id_perserta', $tim_merah)->sum('score');
                        $minus2 = score::where('status', 'minus')->where('id_perserta', $tim_merah)->sum('score');
                        $score2 = $plus2 - $minus2;
                    @endphp
                    <tr>
                        <td><span style="color: rgba(0, 102, 255, 1) ;">{{ $score1 }}</span></td>
                        <td><span style="color: rgba(241, 0, 0, 1);">{{ $score2 }}</span></td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-success mt-2" id="showPemenang">
                Tentukan Pemenang
            </button>
            @php
                $jatuh_babak = score::where('keterangan', 'jatuh')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $bina_babak = score::where('keterangan', 'binaan')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $teguran_babak = score::where('keterangan', 'teguran')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_merah)
                    ->count();
                $peringatan_babak = score::where('keterangan', 'peringatan')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_merah)
                    ->count();
            @endphp
        </div>
        <div class="button-blue-container">
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:jatuh p:3 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS JATUHAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:binaan p:0 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS BINAAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:teguran p:1 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS TEGURAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:peringatan p:5 keterangan:minus"
                id="kirimData" class=" btn btn-secondary button-blue-delete">HAPUS PERINGATAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:jatuh p:3 keterangan:plus"
                id="kirimData" class="btn btn-danger button-red">JATUHAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:binaan p:0 keterangan:plus"
                id="kirimData"
                class="btn btn-danger button-red"@if ($bina_babak === 2) disabled @endif>BINAAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:teguran p:1 keterangan:plus"
                id="kirimData"
                class="btn btn-danger button-red"@if ($teguran_babak === 2) disabled @endif>TEGURAN</button>
            <button
                name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:peringatan p:5 keterangan:plus"
                id="kirimData"
                class="btn btn-danger button-red"@if ($peringatan_babak === 3) disabled @endif>PERINGATAN</button>
        </div>
        <div class="modal fade" id="vjatuhan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropJatuhan" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Jatuhan</h5>
                        <button type="button" class="btn-close" aria-label="Close" id="btn-veryfication-close"
                            data-bs-dismiss="modal"
                            name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:jatuhan p:off keterangan:notif">

                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Juri</th>
                                    <th>Keputusan</th>
                                </tr>
                            </thead>
                            <tbody id="vjatuhan-tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5 d-none overflow" id="listPemenang">
        <div class="container ">
            <div class="container d-flex justify-content-center flex-column my-3">
                <div class="text-center mb-3 fw-bold">Keterangan Kemenangan</div>
                <select name="" id="" class="form-control">
                    <option value="angka">Angka</option>
                    <option value="teknik">Teknik</option>
                    <option value="wmp">Wasit Menghentikan Pertandingan</option>
                    <option value="dis">Diskualifikasi</option>
                    <option value="undur">Undur Diri</option>
                </select>
            </div>
            <table class="table table-bordered shadow">
                <thead>
                    <tr>
                        <th class="text-center bg-primary text-white">Tim Biru</th>
                        <th class="text-center bg-danger text-white">Tim Merah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center py-3">Nama peserta Biru</td>
                        <td class="text-center py-3">Nama peserta Merah</td>
                    </tr>
                    <tr>
                        <td>

                            <button
                                name="keterangan:jadwal p:{{ $tim_biru }} p1:{{ $tim_merah }} sesi:{{$jadwalData->sesi ?? null}} partai:{{ $setting->partai }} status:finish arena:{{ $id_arena }}"
                                class="btn btn-primary w-100 py-3 btn-winner">Biru Menang</button>
                        </td>
                        <td>
                            <button
                                name="keterangan:jadwal p:{{ $tim_biru }} p1:{{ $tim_merah }} sesi:{{$jadwalData->sesi ?? null}} partai:{{ $setting->partai }} status:finish arena:{{ $id_arena }}"
                                class="btn btn-danger w-100 py-3 btn-winner">Merah Menang</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="vhukuman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabelHukuman" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Jatuhan</h5>
                    <button type="button" class="btn-close" aria-label="Close" id="btn-hukuman-veryfication-close"
                        data-bs-dismiss="modal"
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:hukuman p:off keterangan:notif">

                    </button>
                </div>
                <div class="modal-body">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Juri</th>
                                <th>Keputusan</th>
                            </tr>
                        </thead>
                        <tbody id="vhukuman-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="pemenangModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pemenang</h5>
                    </div>
                    <div class="modal-body">
                        test
                    </div>
                    <div class="modal-footer">
                        test2
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script>
            // Temukan semua tombol dengan kelas "btn btn-primary button-blue" atau "btn btn-primary btn btn-secondary button-blue-delete"
            var tombolDenganKelas = document.querySelectorAll('.button-blue, .button-blue-delete , .button-red');
            var btnShowmenang = $('#showPemenang');
            var btnwinner = document.querySelectorAll('.btn-winner');

            const btnveryfication = document.querySelectorAll('#btn-veryfication-jatuhan , #btn-veryfication-hukuman');
            const btnclouseveryvication = document.querySelectorAll('#btn-veryfication-close,#btn-hukuman-veryfication-close');
            var id_arenas = {{ $id_arena }};
            var id_juris = {{ $id_juri }};
            var id_partai = {{ $setting->partai }};

            //const pathurl = `/redirect?arena=${id_arenas}&role=arena-jadwal&name=${id_juris}`;
            const pathurl = `/redirect?arena=${id_arenas}&partai=${id_partai}&role=rekapTanding`;

            function reload() {
                window.location.reload();
            }

            function jadwal() {
                window.location.href = pathurl;
            }

            btnShowmenang.on('click', () => {

                let list = $('#listPemenang');

                if (list.hasClass('d-none')) {
                    list.removeClass('d-none');
                    list.addClass('d-block');
                } else {
                    list.removeClass('d-block');
                    list.addClass('d-none');
                }
            });

            btnveryfication.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"
                    // Membagi nilai atribut "name" menjadi objek JavaScript
                    var data = {};
                    nameAttribute.split(' ').forEach(function(item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });
                    let status = "v" + data.status;
                    var myModal = new bootstrap.Modal(document.getElementById(status));
                    fetch('{{ route('dewan.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => {
                            if (response.status == 200) {
                                setInterval(() => callverifikasi(data), 500);

                                myModal.show();
                            }

                        })
                        .then(data => {

                        })
                        .catch(error => {
                            // Tangani kesalahan jika ada
                        });

                    function callverifikasi(data) {
                        let idtbody = "v" + data.status + "-tbody"
                        $.ajax({
                            url: '/call-data/?tipe=notif&arena=' + data.arena + '&status=' + data
                                .status,
                            method: 'GET',
                            success: function(response) {
                                console.log(response.data);
                                const tbody = document.getElementById(idtbody);
                                if (tbody) {
                                    tbody.innerHTML = ''; // Clear existing content
                                    response.data.forEach(
                                        item => { // Use response.data instead of data.forEach
                                            const tr = document.createElement('tr');

                                            const tdJuri = document.createElement('td');
                                            tdJuri.textContent = item.id_juri;
                                            tr.appendChild(tdJuri);

                                            const tdKeputusan = document.createElement('td');
                                            let color = '';
                                            let text = '';
                                            if (item.score === 'biru') {
                                                color = 'rgba(0, 102, 255, 1)';
                                                text = 'Tim Biru';
                                            } else {
                                                color = 'rgba(241, 0, 0, 1)';
                                                text = 'Tim Merah';
                                            }
                                            tdKeputusan.style.color = color;
                                            tdKeputusan.textContent = text;
                                            tr.appendChild(tdKeputusan);

                                            tbody.appendChild(tr);
                                        });
                                } else {
                                    console.error('Element with ID  not found.');
                                }
                            }
                        });
                    }
                });
            });
            // function clear notif
            btnclouseveryvication.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"
                    // Membagi nilai atribut "name" menjadi objek JavaScript
                    var data = {};
                    nameAttribute.split(' ').forEach(function(item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });
                    let status = "v" + data.status;
                    const myModal = document.getElementById(status)
                    myModal.addEventListener('hidden.bs.modal', event => {
                        fetch('{{ route('dewan.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(data)
                            })
                            .then(response => {
                                if (response.status == 200) {
                                    setInterval(reload, 800);
                                }

                            })
                            .then(data => {

                            })
                            .catch(error => {
                                // Tangani kesalahan jika ada
                            });
                    });

                });
            });
            // Loop melalui semua tombol dan tambahkan event listener
            tombolDenganKelas.forEach(function(tombol) {
                tombol.addEventListener('click', function() {
                    var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"

                    // Membagi nilai atribut "name" menjadi objek JavaScript
                    var data = {};
                    nameAttribute.split(' ').forEach(function(item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });

                    // Sekarang, Anda memiliki data dalam bentuk objek
                    //console.log(data);

                    // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
                    fetch('{{ route('dewan.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())

                        .then(data => {
                            console.log(data);
                            // Lakukan sesuatu dengan respons dari server (opsional)
                        })
                        .catch(error => {
                            // Tangani kesalahan jika ada
                        });
                    setInterval(reload, 800);
                });
            });
            btnwinner.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var nameAttribute = this.getAttribute('name');
                    var data = {};
                    nameAttribute.split(' ').forEach(function(item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });
                    console.log(data);
                    fetch('{{ route('dewan.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Lakukan sesuatu dengan respons dari server (opsional)
                        })
                        .catch(error => {
                            // Tangani kesalahan jika ada
                        });
                    setInterval(jadwal, 800);
                });
            });
        </script>

</body>

</html>
