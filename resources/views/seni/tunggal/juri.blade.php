<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../assets/seni/juri_seni.css"> -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">

    <title>Juri Seni</title>
</head>

<body>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\category;
        use App\kelas;
        use App\arena;
        use App\juri;
        use App\jadwal_group;
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $jadwal = jadwal_group::where('id', $setting->jadwal)->first() ?? null;
        $partai = $setting->partai;
        $perserta = PersertaModel::where('id', $setting->biru)->first();
        $id_perserta = $perserta->id;
        $kelas = kelas::where('id', $jadwal->kelas)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
        $category = category::where('id', $perserta->category)->value('name');
        $number = 1;
        $dataJuri = juri::where('id', $id_juri)->first();
        $dataArena = arena::where('id', $arena)->first();
        $scores = score::where('id_perserta', $id_perserta)
            ->where('partai', $partai)
            ->where('arena', $setting->arena)
            ->where('id_juri', $id_juri)
            ->where('status', 'point_tunggal')
            ->get();
        $scs = score::where('id_perserta', $id_perserta)
            ->where('arena', $setting->arena)
            ->where('partai', $partai)
            ->where('id_juri', $id_juri)
            ->where('keterangan', 'next')
            ->where('status', 'point_tunggal')
            ->first() ?? (object) [
                'babak' => 0
            ];
        $score = 100;
        $total_score = 0;
        $max = 100;
        $dewan = 0;
        $babak = 0;
        $selectedJurus = null;

        $jurus = [
            'Jurus 1 (7 Gerakan)',
            'Jurus 2 (6 Gerakan)',
            'Jurus 3 (5 Gerakan)',
            'Jurus 4 (7 Gerakan)',
            'Jurus 5 (6 Gerakan)',
            'Jurus 6 (8 Gerakan)',
            'Jurus 7 (11 Gerakan)',
            'Jurus 8 (7 Gerakan)',
            'Jurus 9 (6 Gerakan)',
            'Jurus 10 (12 Gerakan)',
            'Jurus 11 (6 Gerakan)',
            'Jurus 12 (5 Gerakan)',
            'Jurus 13 (5 Gerakan)',
            'Jurus 14 (9 Gerakan)',
        ];

        $jurusTanganKosong = [
            'Jurus 1 (7 Gerakan)',
            'Jurus 2 (6 Gerakan)',
            'Jurus 3 (5 Gerakan)',
            'Jurus 4 (7 Gerakan)',
            'Jurus 5 (6 Gerakan)',
            'Jurus 6 (8 Gerakan)',
            'Jurus 7 (11 Gerakan)',
            'Jurus 8 (7 Gerakan)',
            'Jurus 9 (6 Gerakan)',
            'Jurus 10 (12 Gerakan)',
        ];

        $jurusSenjata = [
            'Jurus 8 (7 Gerakan)',
            'Jurus 9 (6 Gerakan)',
            'Jurus 10 (12 Gerakan)',
            'Jurus 11 (6 Gerakan)',
            'Jurus 12 (5 Gerakan)',
            'Jurus 13 (5 Gerakan)',
            'Jurus 14 (9 Gerakan)',
        ];

        $jurusSenjataTanganKosong = [
            'Jurus 1 (9 Gerakan)',
            'Jurus 2 (9 Gerakan)',
            'Jurus 3 (10 Gerakan)',
            'Jurus 4 (9 Gerakan)',
            'Jurus 5 (7 Gerakan)',
            'Jurus 6 (8 Gerakan)',
            'Jurus 7 (9 Gerakan)',
            'Jurus 8 (11 Gerakan)',
            'Jurus 9 (9 Gerakan)',
            'Jurus 10 (4 Gerakan)',
        ];

        $jurusRegu = [
            'Jurus 1 (9 Gerakan)',
            'Jurus 2 (9 Gerakan)',
            'Jurus 3 (10 Gerakan)',
            'Jurus 4 (9 Gerakan)',
            'Jurus 5 (7 Gerakan)',
            'Jurus 6 (8 Gerakan)',
            'Jurus 7 (9 Gerakan)',
            'Jurus 8 (11 Gerakan)',
            'Jurus 9 (9 Gerakan)',
            'Jurus 10 (4 Gerakan)',
            'Jurus 11 (8 Gerakan)',
            'Jurus 12 (7 Gerakan)',
        ];

        $jurusRegu16 = [
            'Jurus 1 (9 Gerakan)',
            'Jurus 2 (9 Gerakan)',
            'Jurus 3 (10 Gerakan)',
            'Jurus 4 (9 Gerakan)',
            'Jurus 5 (7 Gerakan)',
            'Jurus 6 (8 Gerakan)',
        ];

        $jurusRegu712 = [
            'Jurus 7 (9 Gerakan)',
            'Jurus 8 (11 Gerakan)',
            'Jurus 9 (9 Gerakan)',
            'Jurus 10 (4 Gerakan)',
            'Jurus 11 (8 Gerakan)',
            'Jurus 12 (7 Gerakan)',
        ];

        // $name_jurus = null;
        // if ($scs != null || $scs != '') {
        if (
            $kelas->name == 'SENI TUNGGAL' ||
            $kelas->name == 'TUNGGAL' ||
            $kelas->name == 'Tunggal' ||
            $kelas->name == 'Tunggal Full' ||
            $kelas->name == 'T.FULL' ||
            $kelas->name == 'T. FULL'
        ) {
            $scs = $scs->babak;

            $countTunggal = count($jurus) - 1;
            $selectedJurus = $jurus;
            $finalName = $scs > $countTunggal ? "$jurus[$countTunggal]" : "$jurus[$scs]";

            $name_jurus = $finalName;
        } elseif ($kelas->name == 'SENI REGU' || $kelas->name == 'BEREGU' || $kelas->name == 'REGU') {
            $scs = $scs->babak;

            $countRegu = count($jurusRegu) - 1;
            $selectedJurus = $jurusRegu;
            $finalName = $scs > $countRegu ? "$jurusRegu[$countRegu]" : $jurusRegu[$scs];

            $name_jurus = $finalName;
        } elseif ($kelas->name == 'BEREGU 1-6') {
            $scs = $scs->babak;

            $countRegu = count($jurusRegu16) - 1;
            $selectedJurus = $jurusRegu16;
            $finalName = $scs > $countRegu ? "$jurusRegu16[$countRegu]" : $jurusRegu16[$scs];

            $name_jurus = $finalName;
        } elseif ($kelas->name == 'BEREGU 7-12' || $kelas->name == 'REGU 7-12') {
            $scs = $scs->babak;

            $countRegu = count($jurusRegu712) - 1;
            $selectedJurus = $jurusRegu712;
            $finalName = $scs > $countRegu ? "$jurusRegu712[$countRegu]" : $jurusRegu712[$scs];

            $name_jurus = $finalName;
        } elseif ($kelas->name === 'T.GOLOK' || $kelas->name == 'TUNGGAL SENJATA') {
            $max = 75;
            $scs = $scs->babak;

            $countTunggal = count($jurusSenjata) - 1;
            $finalName = $scs > $countTunggal ? "$jurusSenjata[$countTunggal]" : "$jurusSenjata[$scs]";
            $selectedJurus = $jurusSenjata;
            $name_jurus = $finalName;
        } elseif (
            $kelas->name === 'T.KOSONG & GOLOK' ||
            $kelas->name == 'T. TANGKOS + GOLOK' ||
            $kelas->name == 'T. TANGKOS-GOLOK' ||
            $kelas->name == 'T.TANGKOS-GOLOK' ||
            $kelas->name == 'TK DAN SENJATA' ||
            $kelas->name == 'GANDA TK DAN SENJATA'
        ) {
            $max = 75;
            $scs = $scs->babak;
            $selectedJurus = $jurusSenjataTanganKosong;

            $countTunggal = count($jurusSenjataTanganKosong) - 1;
            $finalName =
                $scs > $countTunggal
                ? "$jurusSenjataTanganKosong[$countTunggal]"
                : "$jurusSenjataTanganKosong[$scs]";

            $name_jurus = $finalName;
        } elseif (
            $kelas->name == 'Tunggal T.Kosong' ||
            $kelas->name == 'TANGAN KOSONG' ||
            $kelas->name == 'T.TANGKOS' ||
            $kelas->name == 'T. TANGKOS' ||
            $kelas->name == 'Tungal T.Kosong' ||
            $kelas->name == 'TUNGGAL-TANGAN-KOSONG'
        ) {
            $max = 50;
            $scs = $scs->babak;
            echo $scs;
            $countTunggal = count($jurusTanganKosong) - 1;
            $finalName = $scs > $countTunggal ? "$jurusTanganKosong[$countTunggal]" : "$jurusTanganKosong[$scs]";
            $selectedJurus = $jurusTanganKosong;

            $name_jurus = $finalName;
        }
        // }

        if (!empty($scores)) {
            // dd($scores);
            foreach ($scores as $item) {
                if ($item->keterangan === 'next') {
                    $score -= $item->score;
                    $babak = $item->babak;
                } elseif ($item->keterangan === 'flwo') {
                    $dewan = $item->score;
                    $total_score += $item->score;
                }
            }
        } else {
            $score = 0;
        }
        $numbers = 9.0; // Ini adalah angka 9 dengan dua angka di belakang koma

        if ($score === 0) {
            $sc = number_format($score / 100, 2);
            $score_actual = $numbers + $sc; // Ini seharusnya 9,00 + 0,00 = 9,00
        } else {
            $sc = number_format(($score - 10) / 100, 2); // Ini akan menjadi 0,19 karena ($score - 10) adalah 19
            $score_actual = $numbers + $sc; // Ini seharusnya 9,00 + 0,19 = 9,19
        }
        $total_score = number_format($total_score);
        $total_score = $total_score + $score_actual + $dewan;
        $dewan = number_format($dewan, 2);

        $namaJuri = explode(' ', $dataJuri->name);
        $namaJuri = "$namaJuri[0] $namaJuri[2]";
        $arenaNama = explode('||', $setting->judul);

        if (!$selectedJurus) {
            $selectedJurus = $jurus;
            $scs = $scs->babak;
        }
    @endphp
    <!-- Match Info Section -->
    <div class="d-flex justify-content-center ">
        <div class="mid-header-text text-center">
            {{$arenaNama[0]}} <br />
            {{$arenaNama[1]}}
            <hr>
        </div>
    </div>
    <!-- Player Info Section -->
    <div class="container mt-3">
        <div class="row">
            <div class="col">
                Nama : <br>
                <span class="text-primary fw-bold fs-5 text-uppercase">{{ $perserta->name }}</span>
            </div>
            <div class="col">
                <div class="text-center fw-bold mt-2">
                    {{ $dataJuri->name }}
                </div>
            </div>
            <div class="col text-end">
                : Kontingen <br>
                <span class="text-primary fw-bold fs-5 text-uppercase">{{ $kontigen }}</span>
            </div>
        </div>
        <!-- Score Section -->
        <div class="text-center border border-black rounded py-1 fs-4" id="nameJurus">{{ $name_jurus ?? 'Start' }}</div>
        <div class="my-2">
            <select name="" style="width: 100%;" class="plain-select2 form-control" id="jurusSelect">
                @foreach ($selectedJurus as $index => $item)
                    <option value="{{ $index }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="row text-center mt-4" style="height: 200px;">
            <div class="col-md-5">
                <button @if ($babak == $max) disabled @endif
                    name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:next p:{{ $number }} keterangan:seni_tunggal"
                    class="btn btn-danger btn-lg custom-button shadow w-100 h-100 btn-data">
                    Wrong Move
                    <i class="fa fa-xmark"></i>
                </button>
            </div>
            <div class="col-md-2">
                <div class="container-fluid h-100">
                    <div class=" h-100 d-flex justify-content-between align-items-center">
                        <table class="table table-bordered border-black">
                            <thead>
                                <tr>
                                    <th colspan="2" class="fw-bold" style="color: rgb(190, 190, 0);">SCORE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-primary fw-bold" id="totalScore">{{ $score }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <button @if ($babak == $max) disabled @endif
                    class="btn btn-primary btn-lg custom-button shadow w-100 h-100 btn-data"
                    name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:next p:0 keterangan:seni_tunggal">Next
                    Move <i class="fa-regular fa-circle"></i></button>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center direc mt-4">
        <div class=" text-center container ">
            <div class="row">
                <table class="table table-bordered border-black rounded">
                    <thead>
                        <tr>
                            <th class="bg-dark-subtle">ACCURACY TOTAL SCORE</th>
                            <th class="text-primary fw-bold" id="actualScoreBefore">{{ $score_actual }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">
                                Flow of Movement/Stamina (Range Score : 0,01 - 0,10)
                                <div></div>
                                <!-- Increment 0,01 jadi 10 button 0,01 sampai 0,10 -->
                                @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        $number = number_format($i * 0.01, 2);

                                    @endphp
                                    <button class="btn btn-outline-primary btn-lg mx-1 btn-data"
                                        name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:flwo p:{{ $number }} keterangan:seni_tunggal">{{ $number }}</button>
                                @endfor
                            </td>
                            <td class="text-center align-middle text-primary fw-bold" id="flwo">
                                {{ $dewan }}
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-dark-subtle fw-bold">TOTAL SCORE :</td>
                            <td class="fw-bold text-primary" id="actualScore">{{ $total_score }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <!-- Laravel Echo -->
    <script src="{{ asset('js/app.js') }}"></script>
    <input type="hidden" name="{{ $scs }}" id="selected">
    <input type="hidden" name="{{ $arena }}" id="arenaId">
    <input type="hidden" name="{{ $id_juri }}" id="juriId">
    <input type="hidden" name="{{ $id_perserta }}" id="pesertaId">
    <script>
        let id = $('#selected').attr('name');
        let arena = $('#arenaId').attr('name');
        let juri = $('#juriId').attr('name');
        let peserta = $('#pesertaId').attr('name');
        let isProgrammaticChange = false; // Flag to prevent infinite loop
        let isInitialLoad = true; // Flag for initial page load

        $(document).ready(function () {
            $('.plain-select2').select2();

            // Set initial value and update nameJurus
            $('#jurusSelect').val(id);
            $('#nameJurus').text($('#jurusSelect').find(':selected').text());

            // Update Select2 display without triggering change handler
            $('#jurusSelect').trigger('change.select2');

            // Reset flag after a short delay
            setTimeout(function () {
                isInitialLoad = false;
            }, 100);

            $('#jurusSelect').on('change', function () {
                // Update nameJurus for all changes
                $('#nameJurus').text($(this).find(':selected').text());

                // Skip if this is a programmatic change from websocket or initial load
                if (isProgrammaticChange || isInitialLoad) {
                    isProgrammaticChange = false;
                    return;
                }

                let newId = $(this).val();

                fetch('{{ route('juri.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        arena: arena,
                        id: peserta,
                        juri: juri,
                        keterangan: "seni_tunggal",
                        info: "change",
                        status: "next",
                        p: newId,
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data);
                        // window.location.reload();
                    });
            })
        });

        // function updateData(item) {
        //     if(item.keterangan == 'next') {

        //     }
        // }

        function websocket() {
            // var arena_id = document.getElementById('arenaId').getAttribute('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Soketi!");
                });
                Echo.channel('tunggal-channel')
                    .listen('TunggalEvent', (datas) => {
                        const data = datas.message;
                        var totalScore = 100;
                        var actualScore = 9.9;
                        var actualScoreBefore = 9.9;
                        var flwo = 0;
                        var jurus = 0;
                        // console.log(data);
                        if (arena == data.arena && data.tipe == 'data') {
                            data.data.forEach((item, index) => {

                                if (item.id_juri == juri) {
                                    console.log(item);

                                    if (item.keterangan == 'next') {
                                        var scoreDecimal = parseFloat(item.score) || 0;
                                        totalScore -= scoreDecimal;
                                        actualScore -= scoreDecimal / 100;
                                        actualScoreBefore -= scoreDecimal / 100;
                                        jurus = parseFloat(item.babak);
                                    }

                                    if (item.keterangan == 'flwo') {
                                        var scoreDecimal = parseFloat(item.score) || 0;
                                        actualScore += scoreDecimal;
                                        flwo = scoreDecimal;
                                    }
                                }
                            });

                            // Format numbers to remove trailing zeros and limit decimal places
                            $('#actualScoreBefore').text(parseFloat(actualScoreBefore.toFixed(2)));
                            $('#actualScore').text(parseFloat(actualScore.toFixed(2)));
                            $('#flwo').text(parseFloat(flwo.toFixed(2)));
                            $('#totalScore').text(parseFloat(totalScore.toFixed(2)));

                            // Only update select if value actually changed
                            var currentJurus = $('#jurusSelect').val();
                            if (String(currentJurus) != String(jurus)) {
                                isProgrammaticChange = true;
                                $('#jurusSelect').val(jurus).trigger('change');
                                // nameJurus will be updated in the change handler
                            }
                        } else if (arena == data.arena && data.tipe == 'update') {
                            window.location.reload();
                        }
                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
        }
        // end function untuk websoket

        var tombolDenganKelas = document.querySelectorAll('.btn-data');
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
                // console.log(data);

                // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
                fetch('{{ route('juri.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data);
                        reload();
                        // $('button').prop('disabled', false);
                    })
                    .catch(error => {
                        // Tangani kesalahan jika ada
                    });

                function reload() {
                    // window.location.reload();
                }
                // setInterval(reload, 800);
            });
        });

        websocket();
    </script>
</body>

</html>