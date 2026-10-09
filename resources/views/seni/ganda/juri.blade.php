<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">


    <title>Solo & Ganda</title>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\juri;
        use App\kelas;

        $setting = Setting::where('arena', $arena)->first();
        $perserta = PersertaModel::where('id', $setting->biru)->first();
        $id_perserta = $perserta->id;
        $dataKelas = kelas::where('id', $perserta->kelas)->first();
        $dataJuri = juri::where('id', $id_juri)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
        $scores = score::where('id_perserta', $id_perserta)->get();

        $jadwalData = \App\jadwal_group::where('id', $setting->jadwal)->first();
        $categoryData = \App\category::where('id', $perserta->category)->first();

        $namaJuri = explode(' ', $dataJuri->name);
        $namaJuri = "$namaJuri[0] $namaJuri[2]";
        $arenaNama = explode('||', $setting->judul);
    @endphp
</head>

<body>
    <!-- Tombol Fullscreen Kiri Atas & Kanan Atas -->
    <button onclick="toggleFullScreen()" class="btn btn-sm btn-outline-secondary shadow-sm position-fixed" style="top: 10px; left: 10px; z-index: 1050;" title="Full Screen">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
        </svg>
        Full Screen
    </button>
    <button onclick="toggleFullScreen()" class="btn btn-sm btn-outline-secondary shadow-sm position-fixed" style="top: 10px; right: 10px; z-index: 1050;" title="Full Screen">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
        </svg>
        Full Screen
    </button>
    <!-- Match Info Section -->
    <div class="d-flex flex-column align-items-center mt-3">
        <div class="mid-header-text text-center fw-bold fs-5 mb-2">
            {{$arenaNama[0]}}
        </div>

        <div class="d-flex justify-content-center flex-wrap gap-2 text-center mb-2">
            <span class="badge bg-dark fs-6 px-3 py-2">Partai {{ $setting->partai ?? '-' }}</span>
            <span class="badge bg-success fs-6 px-3 py-2">{{ ucfirst($jadwalData->keterangan ?? 'Pemasalan') }}</span>
            <span class="badge bg-primary fs-6 px-3 py-2">{{ $dataKelas->name ?? '-' }}</span>
            <span class="badge bg-info fs-6 px-3 py-2">{{ $categoryData->name ?? '-' }}</span>
            <span class="badge bg-secondary fs-6 px-3 py-2">{{ ucfirst($perserta->gender ?? '-') }}</span>
        </div>
    </div>
    <!-- Mid Section -->
    <div class="container-fluid px-4">
        <div class="row">
            <!-- Player Info Section -->
            <div class="col fs-5">
                <span class="fs-5">NAMA PESERTA :</span> <br>
                <span class="text-primary text-uppercase">{{ $perserta->name }}</span>
            </div>
            <div class="col">
                <div class="text-center fw-bold mt-2">
                    {{ $dataJuri->name }}
                </div>
            </div>
            <div class="col text-end fs-5">
                <span class="fs-5">: KONTINGEN</span> <br>
                <span class="text-primary text-uppercase">{{ $kontigen }}</span>
            </div>
        </div>
        <table class="table table-bordered border-black">
            <thead>
                <tr>
                    <th colspan="1" class="w-10 bg-dark-subtle text-center">SCORING ELEMENT</th>
                    <th colspan="3" class="text-center bg-dark-subtle w-75">SCORE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-2  align-middle">
                        Teknik Serang-Bela
                        (0,01-0,30)
                    </td>
                    <td>
                        <!-- LOOP SAMPAI 0,30 -->
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                            @endphp
                            <button class="btn btn-light border-black px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:attack p:{{ $number }} keterangan:pointseni partai:1">{{ $number }}</button>
                        @endfor
                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        @php
                            $check = [
                                'id_perserta' => $id_perserta,
                                'keterangan' => 'attack',
                                'id_juri' => $id_juri,
                            ];
                            $data = score::where($check)->first();
                        @endphp
                        @if ($data)
                            <span class="text-primary">{{ $data->score }}</span>
                        @else
                            <span class="text-primary">0</span>
                        @endif
                    </td>
                    <td rowspan="3" class="w-10 text-center align-middle">
                        <span class="fs-4">Total Score</span> <br>
                        -Teknik <br>
                        -Ketegasan <br>
                        -Penjiwaan <br>
                        @php
                            $score = $scores->where('status', 'point_solo')->where('id_juri', $id_juri)->sum('score');
                            $score = number_format($score, 2);
                            $score = 9.1 + $score;
                        @endphp
                        <span class="text-primary fs-4 fw-bold">{{ $score }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-2 align-middle">
                        Kemantapan
                        (0,01-0,30)
                    </td>
                    <td>
                        <!-- LOOP SAMPAI 0,03 -->
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                            @endphp
                            <button class="btn btn-light border-black  px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:firmness p:{{ $number }} keterangan:pointseni">{{ $number }}</button>
                        @endfor

                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        @php
                            $check = [
                                'id_perserta' => $id_perserta,
                                'keterangan' => 'firmness',
                                'id_juri' => $id_juri,
                            ];
                            $data = score::where($check)->first();
                        @endphp
                        @if ($data)
                            <span class="text-primary">{{ $data->score }}</span>
                        @else
                            <span class="text-primary">0</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="px-2 align-middle">
                        Penjiwaan
                        (0,01-0,30)
                    </td>
                    <td>
                        <!-- LOOP SAMPAI 0,03 -->
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);

                            @endphp
                            <button class="btn btn-light border-black  px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:soulfullness p:{{ $number }} keterangan:pointseni">{{ $number }}</button>
                        @endfor

                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        @php
                            $check = [
                                'id_perserta' => $id_perserta,
                                'keterangan' => 'soulfullness',
                                'id_juri' => $id_juri,
                            ];
                            $data = score::where($check)->first();
                        @endphp
                        @if ($data)
                            <span class="text-primary">{{ $data->score }}</span>
                        @else
                            <span class="text-primary">0</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <input type="text" hidden value="{{$setting->arena}}" name="arena" id="arena_id">
    <script>
        // Temukan semua tombol dengan kelas "button-blue" atau "button-blue-delete"
        var tombolDenganKelas = document.querySelectorAll('.btn-data');
        var arena = $('#arena_id').val();

        function websocket() {
            // var arena_id = document.getElementById('arenaId').getAttribute('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Layanan Notif!");
                });
                Echo.channel('solo-channel')
                    .listen('SoloEvent', (datas) => {
                        const data = datas.message;
                        if (arena == data.arena && data.tipe == 'update') {
                            window.location.reload();
                        }
                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
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

                // Sekarang, Anda memiliki data dalam bentuk objek
                AyoSilatJudge.submitScore(data, function (res) {
                    console.log(res);
                    reload();
                }, function (err, isQueued) {
                    // Tersimpan dalam antrean offline jika koneksi putus
                });

                function reload() {
                    window.location.reload();
                }
            });
        });

        websocket();
    </script>
    <script src="{{ asset('assets/js/score-queue.js') }}"></script>
    <script>
        AyoSilatJudge.init({
            arena: "{{ $arena }}",
            id_juri: "{{ $id_juri }}",
            csrfToken: "{{ csrf_token() }}",
            storeUrl: "{{ route('juri.store') }}"
        });
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>


    <script>function toggleFullScreen() { if (!document.fullscreenElement) { document.documentElement.requestFullscreen(); } else { if (document.exitFullscreen) { document.exitFullscreen(); } } }</script>
</body>

</html>