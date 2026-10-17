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

        $scores = score::where('id_perserta', $id_perserta)
            ->where('partai', $setting->partai)
            ->where('arena', $setting->arena)
            ->where('id_juri', $id_juri)
            ->where('status', 'point_solo')
            ->get();

        $attackScore = $scores->where('keterangan', 'attack')->value('score') ?? 0;
        $firmnessScore = $scores->where('keterangan', 'firmness')->value('score') ?? 0;
        $soulfullnessScore = $scores->where('keterangan', 'soulfullness')->value('score') ?? 0;
        $totalScore = number_format(9.10 + (float)$attackScore + (float)$firmnessScore + (float)$soulfullnessScore, 2);

        $jadwalData = \App\jadwal_group::where('id', $setting->jadwal)->first();
        $categoryData = \App\category::where('id', $perserta->category)->first();

        $namaJuriParts = explode(' ', $dataJuri->name ?? 'Juri');
        $namaJuri = count($namaJuriParts) >= 3 ? "{$namaJuriParts[0]} {$namaJuriParts[2]}" : ($dataJuri->name ?? 'Juri');
        $arenaNama = explode('||', $setting->judul ?? 'ARENA');
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

        <div class="d-flex flex-row flex-nowrap align-items-center justify-content-center gap-2 text-center text-uppercase text-nowrap mb-2">
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">Partai {{ $setting->partai ?? '-' }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ ucfirst($jadwalData->keterangan ?? 'Pemasalan') }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ $dataKelas->name ?? '-' }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ $categoryData->name ?? '-' }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ ucfirst($perserta->gender ?? '-') }}</span>
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
                    <td class="px-2 align-middle">
                        Teknik Serang-Bela
                        (0,01-0,30)
                    </td>
                    <td>
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                                $isActive = ((float)$attackScore == (float)$number);
                            @endphp
                            <button class="btn {{ $isActive ? 'btn-primary text-white' : 'btn-light' }} border-black px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:attack p:{{ $number }} keterangan:pointseni partai:{{ $setting->partai ?? 1 }}">{{ $number }}</button>
                        @endfor
                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        <span class="text-primary" id="score_attack">{{ number_format((float)$attackScore, 2) }}</span>
                    </td>
                    <td rowspan="3" class="w-10 text-center align-middle">
                        <span class="fs-4">Total Score</span> <br>
                        -Teknik <br>
                        -Ketegasan <br>
                        -Penjiwaan <br>
                        <span class="text-primary fs-4 fw-bold" id="total_score">{{ $totalScore }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-2 align-middle">
                        Kemantapan
                        (0,01-0,30)
                    </td>
                    <td>
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                                $isActive = ((float)$firmnessScore == (float)$number);
                            @endphp
                            <button class="btn {{ $isActive ? 'btn-primary text-white' : 'btn-light' }} border-black px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:firmness p:{{ $number }} keterangan:pointseni partai:{{ $setting->partai ?? 1 }}">{{ $number }}</button>
                        @endfor
                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        <span class="text-primary" id="score_firmness">{{ number_format((float)$firmnessScore, 2) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-2 align-middle">
                        Penjiwaan
                        (0,01-0,30)
                    </td>
                    <td>
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                                $isActive = ((float)$soulfullnessScore == (float)$number);
                            @endphp
                            <button class="btn {{ $isActive ? 'btn-primary text-white' : 'btn-light' }} border-black px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:soulfullness p:{{ $number }} keterangan:pointseni partai:{{ $setting->partai ?? 1 }}">{{ $number }}</button>
                        @endfor
                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        <span class="text-primary" id="score_soulfullness">{{ number_format((float)$soulfullnessScore, 2) }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <input type="text" hidden value="{{$setting->arena}}" name="arena" id="arena_id">
    <script>
        var arena = $('#arena_id').val();
        let currentAttack = parseFloat("{{ $attackScore }}") || 0;
        let currentFirmness = parseFloat("{{ $firmnessScore }}") || 0;
        let currentSoulfullness = parseFloat("{{ $soulfullnessScore }}") || 0;

        function updateTotalScore() {
            let total = 9.10 + currentAttack + currentFirmness + currentSoulfullness;
            $('#total_score').text(total.toFixed(2));
        }

        function websocket() {
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Layanan Notif!");
                });
                Echo.channel('solo-channel')
                    .listen('SoloEvent', (datas) => {
                        const data = datas.message;
                        if (arena == data.arena && data.tipe == 'update') {
                            if (data.data && data.data.id && data.data.id != "{{ $id_perserta }}") {
                                window.location.reload();
                            }
                        }
                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
        }

        $('.btn-data').on('click', function () {
            var nameAttribute = $(this).attr('name');
            if (!nameAttribute) return;

            var data = {};
            nameAttribute.split(' ').forEach(function (item) {
                var parts = item.split(':');
                if (parts.length === 2) {
                    data[parts[0]] = parts[1];
                }
            });

            var val = parseFloat(data.p) || 0;
            var status = data.status;

            // Highlight selected button in this row only
            $(this).closest('td').find('.btn-data').removeClass('btn-primary text-white').addClass('btn-light');
            $(this).removeClass('btn-light').addClass('btn-primary text-white');

            if (status === 'attack') {
                currentAttack = val;
                $('#score_attack').text(val.toFixed(2));
            } else if (status === 'firmness') {
                currentFirmness = val;
                $('#score_firmness').text(val.toFixed(2));
            } else if (status === 'soulfullness') {
                currentSoulfullness = val;
                $('#score_soulfullness').text(val.toFixed(2));
            }
            updateTotalScore();

            // Submit asynchronously without page reload to maintain fullscreen
            AyoSilatJudge.submitScore(data, function (res) {
                console.log('Score saved:', res);
            }, function (err, isQueued) {
                // Tersimpan dalam antrean offline jika koneksi putus
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

    <script>
        function toggleFullScreen() {
            if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }
    </script>
</body>

</html>