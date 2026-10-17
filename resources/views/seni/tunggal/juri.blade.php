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
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();
        $jadwal = $setting ? (jadwal_group::where('id', $setting->jadwal)->first() ?? null) : null;
        $partai = $setting ? $setting->partai : null;
        $perserta = ($setting && $setting->biru) ? PersertaModel::where('id', $setting->biru)->first() : null;
        $id_perserta = $perserta ? $perserta->id : 0;
        $kelas = ($jadwal && $jadwal->kelas) ? kelas::where('id', $jadwal->kelas)->first() : null;
        $kontigen = ($perserta && $perserta->id_kontigen) ? KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen') : '-';
        $category = ($perserta && $perserta->category) ? category::where('id', $perserta->category)->value('name') : '-';
        $number = 1;
        $dataJuri = juri::where('id', $id_juri)->first();
        $dataArena = arena::where('id', $arena)->first();

        $scores = ($perserta && $setting) ? score::where('id_perserta', $id_perserta)
            ->where('partai', $partai)
            ->where('arena', $setting->arena)
            ->where('id_juri', $id_juri)
            ->where('status', 'point_tunggal')
            ->get() : collect();

        $scs = ($perserta && $setting) ? score::where('id_perserta', $id_perserta)
            ->where('arena', $setting->arena)
            ->where('partai', $partai)
            ->where('id_juri', $id_juri)
            ->where('keterangan', 'next')
            ->where('status', 'point_tunggal')
            ->first() : null;

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

        $kelasName = strtoupper(trim($kelas->name ?? ''));
        $selectedJurus = $jurus;

        if (str_contains($kelasName, 'REGU 1-6') || str_contains($kelasName, 'BEREGU 1-6')) {
            $selectedJurus = $jurusRegu16;
        } elseif (str_contains($kelasName, 'REGU 7-12') || str_contains($kelasName, 'BEREGU 7-12')) {
            $selectedJurus = $jurusRegu712;
        } elseif (str_contains($kelasName, 'REGU') || str_contains($kelasName, 'BEREGU')) {
            $selectedJurus = $jurusRegu;
        } elseif (
            (str_contains($kelasName, 'KOSONG') && (str_contains($kelasName, 'GOLOK') || str_contains($kelasName, 'SENJATA') || str_contains($kelasName, '+'))) ||
            str_contains($kelasName, 'TK DAN SENJATA') ||
            str_contains($kelasName, 'T.KOSONG & GOLOK')
        ) {
            $selectedJurus = $jurusSenjataTanganKosong;
        } elseif (str_contains($kelasName, 'GOLOK') || str_contains($kelasName, 'SENJATA') || str_contains($kelasName, 'TOYA')) {
            $selectedJurus = $jurusSenjata;
        } elseif (str_contains($kelasName, 'TANGKOS') || str_contains($kelasName, 'T.KOSONG') || str_contains($kelasName, 'TANGAN KOSONG')) {
            $selectedJurus = $jurusTanganKosong;
        } else {
            $selectedJurus = $jurus;
        }

        $max = count($selectedJurus) - 1;
        $wrongScore = 0;
        $flowScore = 0;
        $rawBabak = 0;

        if ($scs && isset($scs->babak)) {
            $rawBabak = (int)$scs->babak;
        }

        if (!empty($scores) && count($scores) > 0) {
            foreach ($scores as $item) {
                if ($item->keterangan === 'next') {
                    $wrongScore = (float)$item->score;
                    $rawBabak = (int)$item->babak;
                } elseif ($item->keterangan === 'flwo') {
                    $flowScore = (float)$item->score;
                }
            }
        }

        $currentBabak = max(0, min($max, $rawBabak));
        $name_jurus = $selectedJurus[$currentBabak] ?? $selectedJurus[0];

        $score = max(0, 100 - $wrongScore);
        $score_actual = number_format(max(0, 9.90 - ($wrongScore / 100)), 2);
        $dewan = number_format($flowScore, 2);
        $total_score = number_format((float)$score_actual + $flowScore, 2);

        $juriNameParts = explode(' ', $dataJuri->name ?? 'Juri');
        $namaJuri = count($juriNameParts) >= 3 ? "{$juriNameParts[0]} {$juriNameParts[2]}" : ($dataJuri->name ?? 'Juri');
        $arenaNama = explode('||', $setting->judul ?? 'ARENA');
    @endphp
    <!-- Match Info Section -->
    <div class="d-flex flex-column align-items-center mt-3">
        <div class="mid-header-text text-center fs-5 fw-bold mb-2">
            {{$arenaNama[0]}}
        </div>

        <div class="d-flex flex-row flex-nowrap align-items-center justify-content-center gap-2 text-center text-uppercase text-nowrap mb-3">
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">Partai {{ $partai }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ ucfirst($jadwal->keterangan ?? 'Pemasalan') }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ $kelas->name ?? '-' }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ $category ?? '-' }}</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold">{{ ucfirst($perserta->gender ?? '-') }}</span>
        </div>
    </div>
    <!-- Player Info Section -->
    <div class="container mt-2">
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
                <button id="btnWrongMove"
                    name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:next p:1 keterangan:seni_tunggal"
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
                <button id="btnNextMove" @if ($currentBabak >= $max) disabled @endif
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
                                @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        $flowVal = number_format($i * 0.01, 2);
                                        $isActiveFlow = ((float)$flowVal == (float)$flowScore);
                                    @endphp
                                    <button class="btn {{ $isActiveFlow ? 'btn-primary' : 'btn-outline-primary' }} btn-lg mx-1 btn-data btn-flow"
                                        data-flow="{{ $flowVal }}"
                                        name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:flwo p:{{ $flowVal }} keterangan:seni_tunggal">{{ $flowVal }}</button>
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
    <input type="hidden" value="{{ $currentBabak }}" id="selected">
    <input type="hidden" value="{{ $arena }}" id="arenaId">
    <input type="hidden" value="{{ $id_juri }}" id="juriId">
    <input type="hidden" value="{{ $id_perserta }}" id="pesertaId">
    <script>
        let currentBabak = {{ $currentBabak }};
        const maxBabak = {{ $max }};
        let wrongMoves = {{ $wrongScore }};
        let currentFlow = {{ $flowScore }};
        let arena = "{{ $arena }}";
        let juri = "{{ $id_juri }}";
        let peserta = "{{ $id_perserta }}";
        let isProgrammaticChange = false;
        let isInitialLoad = true;

        function updateScoreDisplay() {
            let accuracy = 9.90 - (wrongMoves / 100);
            if (accuracy < 0) accuracy = 0;
            let total = accuracy + currentFlow;

            $('#totalScore').text(Math.max(0, 100 - wrongMoves));
            $('#actualScoreBefore').text(accuracy.toFixed(2));
            $('#flwo').text(currentFlow.toFixed(2));
            $('#actualScore').text(total.toFixed(2));

            if (currentBabak >= maxBabak) {
                $('#btnNextMove').prop('disabled', true);
            } else {
                $('#btnNextMove').prop('disabled', false);
            }
        }

        function updateJurusDisplay(newBabak) {
            currentBabak = Math.max(0, Math.min(maxBabak, parseInt(newBabak) || 0));
            isProgrammaticChange = true;
            $('#jurusSelect').val(currentBabak).trigger('change.select2');
            let selectedText = $('#jurusSelect').find('option[value="' + currentBabak + '"]').text() || $('#jurusSelect').find(':selected').text();
            if (selectedText) {
                $('#nameJurus').text(selectedText);
            }
            setTimeout(function () {
                isProgrammaticChange = false;
            }, 50);

            if (currentBabak >= maxBabak) {
                $('#btnNextMove').prop('disabled', true);
            } else {
                $('#btnNextMove').prop('disabled', false);
            }
        }

        $(document).ready(function () {
            $('.plain-select2').select2();

            // Set initial jurus display
            updateJurusDisplay(currentBabak);

            setTimeout(function () {
                isInitialLoad = false;
            }, 150);

            $('#jurusSelect').on('change', function () {
                let selectedText = $(this).find(':selected').text();
                if (selectedText) {
                    $('#nameJurus').text(selectedText);
                }

                if (isProgrammaticChange || isInitialLoad) {
                    return;
                }

                let newId = parseInt($(this).val()) || 0;
                currentBabak = newId;

                if (currentBabak >= maxBabak) {
                    $('#btnNextMove').prop('disabled', true);
                } else {
                    $('#btnNextMove').prop('disabled', false);
                }

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
                });
            });

            // Score button clicks: Instant local DOM update without page reload
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

                // Optimistic instant UI update
                if (data.status === 'next') {
                    var pVal = parseFloat(data.p) || 0;
                    if (pVal === 0) {
                        // Next Move: advance jurus
                        if (currentBabak < maxBabak) {
                            updateJurusDisplay(currentBabak + 1);
                        }
                    } else {
                        // Wrong Move: increment deductions
                        wrongMoves += pVal;
                        updateScoreDisplay();
                    }
                } else if (data.status === 'flwo') {
                    currentFlow = parseFloat(data.p) || 0;
                    $('.btn-flow').removeClass('btn-primary').addClass('btn-outline-primary');
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                    updateScoreDisplay();
                }

                // Submit asynchronously via AyoSilatJudge queue
                AyoSilatJudge.submitScore(data, function (res) {
                    if (res && res.score !== undefined) {
                        if (data.status === 'next' || res.status === 'next') {
                            wrongMoves = parseFloat(res.score) || 0;
                        } else if (data.status === 'flwo' || res.status === 'flwo') {
                            currentFlow = parseFloat(res.score) || 0;
                        }
                    }
                    if (res && res.babak !== undefined && data.status === 'next' && parseFloat(data.p) === 0) {
                        updateJurusDisplay(res.babak);
                    }
                    updateScoreDisplay();
                }, function (err, isQueued) {
                    // Handled gracefully in offline queue
                });
            });

            // WebSocket live synchronization
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Layanan Notif!");
                });

                Echo.channel('tunggal-channel')
                    .listen('TunggalEvent', function (datas) {
                        const data = datas.message || datas;
                        if (!data || arena != data.arena) return;

                        if (data.tipe == 'data') {
                            const rawScores = data.data || data.scores;
                            if (Array.isArray(rawScores)) {
                                rawScores.forEach(function (item) {
                                    if (item.id_juri == juri) {
                                        if (item.keterangan == 'next') {
                                            wrongMoves = parseFloat(item.score) || 0;
                                            var newBabak = parseFloat(item.babak);
                                            if (!isNaN(newBabak) && newBabak !== currentBabak) {
                                                updateJurusDisplay(newBabak);
                                            }
                                        }
                                        if (item.keterangan == 'flwo') {
                                            currentFlow = parseFloat(item.score) || 0;
                                            $('.btn-flow').each(function () {
                                                if (parseFloat($(this).data('flow')) === currentFlow) {
                                                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                                                } else {
                                                    $(this).removeClass('btn-primary').addClass('btn-outline-primary');
                                                }
                                            });
                                        }
                                    }
                                });
                                updateScoreDisplay();
                            }
                        } else if (data.tipe == 'update') {
                            // Only reload if match participant changed
                            if (data.data && data.data.id && data.data.id != peserta) {
                                window.location.reload();
                            }
                        }
                    });
            }
        });
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
    <script>function toggleFullScreen() { if (!document.fullscreenElement) { document.documentElement.requestFullscreen(); } else { if (document.exitFullscreen) { document.exitFullscreen(); } } }</script>
</body>

</html>