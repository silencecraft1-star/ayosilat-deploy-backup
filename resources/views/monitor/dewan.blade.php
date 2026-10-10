@extends('layout.master2')

@section('content')
    @php
        use App\arena;
        use App\PersertaModel;
        use App\kelas;
        use App\category;
        use App\jadwal_group;
        use App\Setting;

        // --- Data Initialization (from controller) ---
        $pending_scores = $datakp['data'] ?? []; // Collection of all pending_tanding records
        $currentRound = $datakp['babak'] ?? 1;
        $teamBlue = $datakp['biru'] ?? [];
        $teamRed = $datakp['merah'] ?? [];
        $arena = $datakp['arena'] ?? 1;
        $juriMap = $datakp['juri'] ?? []; // Access the new Juri IDs: ['juri_1' => ID_A, 'juri_2' => ID_B, ...]
        $arenaData = arena::where('id', $arena)->first();
        $partaiNomor = $datakp['partai'] ?? '-';

        // Query Setting & Jadwal Group untuk sinkronisasi akurat dengan Halaman Dewan
        $settingObj = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $jadwalData = null;
        if ($settingObj && !empty($settingObj->jadwal)) {
            $jadwalData = jadwal_group::where('id', $settingObj->jadwal)->first();
        }
        if (!$jadwalData) {
            $jadwalData = jadwal_group::where('arena', $arena)
                ->where('partai', $partaiNomor)
                ->when($datakp['sesi'] ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->first();
        }

        // Keterangan Pertandingan (Babak Jadwal) - Tanding TIDAK PERNAH Pemasalan
        $keteranganPertandingan = $datakp['keteranganPertandingan'] ?? ($jadwalData->keterangan ?? 'PENYISIHAN');
        if (empty($keteranganPertandingan) || strtolower($keteranganPertandingan) === 'pemasalan') {
            $keteranganPertandingan = ($jadwalData && $jadwalData->keterangan && strtolower($jadwalData->keterangan) !== 'pemasalan') 
                ? $jadwalData->keterangan 
                : 'PENYISIHAN';
        }

        // Ambil Data Peserta (Merah / Biru) untuk Kelas, Kategori, Gender
        $pesertaBiru = PersertaModel::where('id', $teamBlue['id'] ?? null)->first();
        $pesertaMerah = PersertaModel::where('id', $teamRed['id'] ?? null)->first();
        $refPeserta = $pesertaMerah ?: $pesertaBiru;

        $kelasData = ($refPeserta && $refPeserta->kelas) ? kelas::where('id', $refPeserta->kelas)->first() : null;
        $kategoriData = ($refPeserta && $refPeserta->category) ? category::where('id', $refPeserta->category)->first() : null;

        $namaKelas = $datakp['namaKelas'] ?? ($kelasData->name ?? '-');
        $namaKategori = $datakp['namaKategori'] ?? ($kategoriData->name ?? '-');
        $gender = $datakp['infoGender'] ?? ($refPeserta->gender ?? '-');

        $tim_biru_id = $teamBlue['id'] ?? '';
        $tim_merah_id = $teamRed['id'] ?? '';
        $jumlahJuri = 3;
    @endphp

    <style>
        @font-face {
            font-family: 'Poppins Regular';
            src: url("{{ asset('assets/fonts/poppins/Poppins-Regular.ttf') }}") format('truetype');
        }

        body {
            font-family: 'Poppins Regular', sans-serif;
            background-color: #f4f7f6;
        }

        .header-monitor {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .team-box {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .team-box.red {
            justify-content: flex-end;
            text-align: right;
        }

        .team-icon-circle {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: 4px solid;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .team-icon-circle img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }

        .blue-circle {
            border-color: #0d6efd;
        }

        .red-circle {
            border-color: #dc3545;
        }

        .team-labels {
            display: flex;
            flex-direction: column;
        }

        .team-kontigen {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #666;
            margin-bottom: -2px;
        }

        .team-nama {
            font-size: 1.4rem;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .arena-info {
            text-align: center;
        }

        .arena-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 0.5px;
        }

        .match-partai {
            font-size: 1.85rem;
            font-weight: 800;
            color: #d97706;
            letter-spacing: 0.5px;
            line-height: 1.2;
            margin-top: 1px;
            margin-bottom: 4px;
        }

        .info-badge {
            background-color: #6c757d;
            color: #ffffff;
            padding: 5px 14px;
            border-radius: 50rem;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        /* --- Grid Layout for Scoring --- */
        .scoring-grid {
            display: grid;
            grid-template-columns: 80px minmax(0, 1fr) 80px minmax(0, 1fr) 80px;
            /* Proportions: Left 80px + 1fr, Center (Babak) 80px, Right 1fr + 80px (symmetric & compact center) */
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid #333;
        }

        .grid-header {
            padding: 14px 10px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1.05rem;
            text-align: center;
            border-bottom: 2px solid #333;
            letter-spacing: 0.5px;
        }

        .grid-header.header-blue {
            background: #0d6efd;
            color: white;
            border-right: 2px solid #333;
        }

        .grid-header.header-babak {
            background: #212529;
            color: white;
            border-right: 2px solid #333;
            font-size: 0.95rem;
            padding-left: 4px;
            padding-right: 4px;
        }

        .grid-header.header-red {
            background: #dc3545;
            color: white;
            border-right: none;
        }

        .grid-item {
            padding: 10px 12px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            min-height: 60px;
            box-sizing: border-box;
        }

        /* Remove right border for the last items in each virtual row (which is the 5th column usually, but grid-span complicates it) */
        /* To keep it simple, we'll use border-right on all and override the far right ones if they are consistently in col 5 */

        .juri-label-cell {
            background-color: #f8f9fa;
            font-weight: 700;
            color: #555;
            justify-content: center;
            font-size: 0.85rem;
            text-align: center;
            white-space: nowrap;
        }

        .score-container {
            display: flex;
            flex-wrap: wrap; /* Collapse kebawah jika point tidak mencukupi container */
            gap: 6px;
            min-height: 40px;
            align-items: center;
            align-content: center;
            width: 100%;
            padding: 2px 0;
        }

        .score-container div {
            background: #eee;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 1.1rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .babak-cell {
            font-size: 2.8rem;
            font-weight: 900;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-left: 2px solid #333;
            border-right: 2px solid #333;
            transition: all 0.3s;
            grid-column: 3;
            padding: 0;
            text-align: center;
        }

        .active-babak {
            background: #FFD600 !important;
            color: #000;
        }

        .text-decoration-line-through {
            text-decoration: line-through;
            opacity: 0.5;
        }
    </style>

    <div class="py-4 container-fluid px-5">
        <!-- Header Section -->
        <div class="header-monitor">
            <!-- Team Blue -->
            <div class="team-box">
                <div class="team-icon-circle blue-circle">
                    <img src="{{ asset('assets/Assets/karate.png') }}" alt="Blue Team Icon">
                </div>
                <div class="team-labels">
                    <span id="kontigenb" class="team-kontigen">{{ $teamBlue['kontigen'] }}</span>
                    <span id="namab" class="team-nama text-primary">{{ $teamBlue['nama'] }}</span>
                </div>
            </div>

            <!-- Arena/Match Info -->
            <div class="arena-info">
                <div class="arena-name text-uppercase">{{ $arenaData->name ?? ('ARENA ' . $arena) }}</div>
                <div class="match-partai text-uppercase">
                    Partai <span id="partai-label"><span id="partai">{{ $partaiNomor }}</span></span>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-1">
                    <span class="info-badge" id="info-keterangan">{{ strtoupper($keteranganPertandingan) }}</span>
                    <span class="info-badge" id="info-kelas">{{ strtoupper($namaKelas) }}</span>
                    <span class="info-badge" id="info-kategori">{{ strtoupper($namaKategori) }}</span>
                    <span class="info-badge" id="info-gender">{{ strtoupper($gender) }}</span>
                </div>
            </div>

            <!-- Team Red -->
            <div class="team-box red">
                <div class="team-labels">
                    <span id="kontigenm" class="team-kontigen">{{ $teamRed['kontigen'] }}</span>
                    <span id="namam" class="team-nama text-danger">{{ $teamRed['nama'] }}</span>
                </div>
                <div class="team-icon-circle red-circle">
                    <img src="{{ asset('assets/Assets/karate (1).png') }}" alt="Red Team Icon">
                </div>
            </div>
        </div>

        <!-- Main Scoring Grid (Compact Center & Symmetric Blue-Red) -->
        <div class="scoring-grid">
            <!-- Grid Headers with Team Colors -->
            <div class="grid-header header-blue" style="grid-column: span 2;">RIWAYAT JURI (BIRU)</div>
            <div class="grid-header header-babak">BABAK</div>
            <div class="grid-header header-red" style="grid-column: span 2;">RIWAYAT JURI (MERAH)</div>

            @for ($babak = 1; $babak <= 3; $babak++)
                @for ($jIdx = 1; $jIdx <= 3; $jIdx++)
                    @php $isLastJuri = ($jIdx === 3); @endphp
                    <!-- Juri Label Label L -->
                    <div class="grid-item juri-label-cell" style="grid-column: 1; @if($isLastJuri) border-bottom: 2px solid #333; @endif">JURI {{ $jIdx }}</div>

                    <!-- Blue Scores -->
                    <div class="grid-item" style="grid-column: 2; @if($isLastJuri) border-bottom: 2px solid #333; @endif">
                        <div id="data{{$babak}}b_{{$jIdx}}" class="score-container">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Babak Central (Spans 3 Rows) -->
                    @if ($jIdx === 1)
                        <div class="grid-item babak-cell @if($currentRound == $babak) active-babak @endif" id="babak-{{$babak}}"
                            style="grid-row: span 3; border-bottom: 2px solid #333;">
                            {{ $babak === 1 ? 'I' : ($babak === 2 ? 'II' : 'III') }}
                        </div>
                    @endif

                    <!-- Red Scores -->
                    <div class="grid-item" style="grid-column: 4; justify-content: flex-end; @if($isLastJuri) border-bottom: 2px solid #333; @endif">
                        <div id="data{{$babak}}m_{{$jIdx}}" class="score-container" style="justify-content: flex-end;">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Juri Label Label R -->
                    <div class="grid-item juri-label-cell" style="grid-column: 5; border-right: none; @if($isLastJuri) border-bottom: 2px solid #333; @endif">JURI {{ $jIdx }}</div>
                @endfor
            @endfor
        </div>

        <input type="hidden" name="{{ $arena }}" id="arenaid">
    </div>

    {{-- Includes jQuery and Echo setup scripts --}}
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    {{-- Assuming Echo/Pusher setup is available here or in master2 layout --}}
    @include('addon.tanding.reload')

    <script>
        // Define PHP variables in JavaScript scope
        const TIM_BIRU_ID = "{{ $tim_biru_id }}";
        const TIM_MERAH_ID = "{{ $tim_merah_id }}";
        const JUMLAH_JURI = {{ $jumlahJuri }};
        const INITIAL_DATA = @json($datakp);
        // NEW: Juri IDs are now correctly passed to match the incoming WebSocket data
        const JURI_IDS = @json($datakp['juri']);


        /**
         * Renders scores from the given data set onto the UI, filtered by judge.
         * @param {Object} data - The data payload containing 'data' (scores) and 'babak' (current round).
         */
        function updateScores(dataPayload) {
            const info = dataPayload;
            const scores = dataPayload.data;
            const currentBabak = dataPayload.babak;
            if (info.biru && info.biru.nama) $(`#namab`).text(info.biru.nama);
            if (info.merah && info.merah.nama) $(`#namam`).text(info.merah.nama);

            if (info.biru && info.biru.kontigen) $(`#kontigenb`).text(info.biru.kontigen);
            if (info.merah && info.merah.kontigen) $(`#kontigenm`).text(info.merah.kontigen);
            if (info.partai) {
                $('#partai-label').text(info.partai);
                $('#partai').text(info.partai);
            }

            if (info.keteranganPertandingan) {
                let ket = info.keteranganPertandingan;
                if (ket.toLowerCase() === 'pemasalan') ket = 'PENYISIHAN';
                $('#info-keterangan').text(ket.toUpperCase());
            }
            if (info.namaKelas) {
                $('#info-kelas').text(info.namaKelas.toUpperCase());
            } else if (info.infoKelas) {
                let parts = info.infoKelas.split(' | ');
                if (parts[0]) $('#info-kelas').text(parts[0].replace(/kelas/i, '').trim().toUpperCase());
                if (parts[1]) $('#info-kategori').text(parts[1].trim().toUpperCase());
            }
            if (info.namaKategori) {
                $('#info-kategori').text(info.namaKategori.toUpperCase());
            }
            if (info.infoGender) {
                $('#info-gender').text(info.infoGender.toUpperCase());
            }

            // 1. Clear all existing score containers
            for (let i = 1; i <= JUMLAH_JURI; i++) {
                for (let b = 1; b <= 3; b++) {
                    $(`#data${b}b_${i}`).empty();
                    $(`#data${b}m_${i}`).empty();
                }
            }

            // 2. Update active babak color
            $(`.babak-cell`).removeClass('active-babak');
            $(`#babak-${currentBabak}`).addClass('active-babak');


            // 3. Tracking flags for placeholders
            let foundFlags = {};
            for (let i = 1; i <= JUMLAH_JURI; i++) {
                for (let b = 1; b <= 3; b++) {
                    foundFlags[`${b}b_${i}`] = false;
                    foundFlags[`${b}m_${i}`] = false;
                }
            }

            // 4. Process all scores
            scores.forEach((data) => {
                // Determine the Juri index (1, 2, or 3) by matching data.juri1 (the actual ID) 
                // against the known JURI_IDS map.
                let juriIndex = null;
                if (data.juri1 === JURI_IDS.juri_1) {
                    juriIndex = 1;
                } else if (data.juri1 === JURI_IDS.juri_2) {
                    juriIndex = 2;
                } else if (data.juri1 === JURI_IDS.juri_3) {
                    juriIndex = 3;
                }

                if (juriIndex === null) {
                    return; // Skip if juri ID doesn't match a known judge slot
                }

                // Determine the score value (1 for pukulan, 2 for tendangan)
                let score = 0;
                if (data.keterangan === "pukulan") {
                    score = 1;
                } else if (data.keterangan === "tendangan") {
                    score = 2;
                } else {
                    score = data.score || 0; // Fallback to original score or 0
                }

                // Determine output team
                let team = '';
                if (data.id_perserta == info.biru.id) {
                    team = 'b';
                } else if (data.id_perserta == info.merah.id) {
                    team = 'm';
                } else {
                    return; // Not a recognized team
                }

                // Determine output HTML and update flag
                const outputId = `data${data.babak}${team}_${juriIndex}`;
                const outputHTML = data.isValid === "false"
                    ? `<div class="text-decoration-line-through">${score},</div>`
                    : `<div>${score},</div>`;


                // Append score
                $(`#${outputId}`).append(outputHTML);
                foundFlags[`${data.babak}${team}_${juriIndex}`] = true;
            });

            // 5. Insert Placeholders where no data was found
            for (let i = 1; i <= JUMLAH_JURI; i++) {
                for (let b = 1; b <= 3; b++) {
                    if (!foundFlags[`${b}b_${i}`]) { $(`#data${b}b_${i}`).append(placeholder); }
                    if (!foundFlags[`${b}m_${i}`]) { $(`#data${b}m_${i}`).append(placeholder); }
                }
            }
        }

        // --- Initial Load ---
        $(document).ready(function () {
            updateScores(INITIAL_DATA);
            websocket();
        });

        // --- WebSocket Listener ---
        function websocket() {
            var arena_id = $('#arenaid').attr('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Layanan Notif!");
                });
                // Listen for the JuriEvent containing the updated score data
                Echo.channel('juri-channel')
                    .listen('JuriEvent', (datas) => {
                        console.log('JuriEvent received:', datas.message);
                        let data = datas.message;

                        if (arena_id == data.arena) {
                            updateScores(datas.message);
                        }
                    });

                Echo.channel('score-channel')
                    .listen('ScoreEvent', ({ message: data }) => {
                        if (arena_id !== data.arena) return;

                        console.log(data);
                        // Ensure data.babak is treated as a number for strict comparison
                        const currentBabak = parseInt(data.babak);

                        for (let i = 1; i <= 3; i++) {
                            $(`#babak-${i}`).toggleClass('active-babak', i === currentBabak);
                        }
                        if (data.partai) {
                            $('#partai-label').text(data.partai);
                            $('#partai').text(data.partai);
                        }
                        if (data.namaBiru) $(`#namab`).text(data.namaBiru);
                        if (data.namaMerah) $(`#namam`).text(data.namaMerah);
                        if (data.kontigenBiru) $(`#kontigenb`).text(data.kontigenBiru);
                        if (data.kontigenMerah) $(`#kontigenm`).text(data.kontigenMerah);

                        if (data.keteranganPertandingan) {
                            let ket = data.keteranganPertandingan;
                            if (ket.toLowerCase() === 'pemasalan') ket = 'PENYISIHAN';
                            $('#info-keterangan').text(ket.toUpperCase());
                        }
                        if (data.namaKelas) {
                            $('#info-kelas').text(data.namaKelas.toUpperCase());
                        } else if (data.infoKelas) {
                            var parts = data.infoKelas.split(' | ');
                            if (parts[0]) $('#info-kelas').text(parts[0].replace(/kelas/i, '').trim().toUpperCase());
                            if (parts[1]) $('#info-kategori').text(parts[1].trim().toUpperCase());
                        }
                        if (data.namaKategori) {
                            $('#info-kategori').text(data.namaKategori.toUpperCase());
                        }
                        if (data.infoGender) {
                            $('#info-gender').text(data.infoGender.toUpperCase());
                        }
                    });

                // Listen for verification channel for modal updates (if needed)
                Echo.channel('verification-channel')
                    .listen('VerificationEvent', (datas) => {
                        console.log('VerificationEvent received:', datas.message);
                        // Add logic here to display verification status if required for KP monitoring
                    });

            } else {
                console.error('Laravel Echo is not initialized. Real-time updates disabled.');
            }
        }
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
@endsection