@extends('layout.master2')

@section('content')
    @php
        use App\arena;
        use App\PersertaModel;
        use App\kelas;
        // --- Data Initialization (from controller) ---
        $pending_scores = $datakp['data']; // Collection of all pending_tanding records
        $currentRound = $datakp['babak'];
        $teamBlue = $datakp['biru'];
        $teamRed = $datakp['merah'];
        $arena = $datakp['arena'];
        $juriMap = $datakp['juri']; // Access the new Juri IDs: ['juri_1' => ID_A, 'juri_2' => ID_B, ...]
        $arenaData = arena::where('id', $arena)->first();

        // Fetch Category/Class Info
        $peserta = PersertaModel::where('id', $teamBlue['id'])->first();
        $infoKategori = '';
        if ($peserta) {
            $kelasData = kelas::where('id', $peserta->kelas)->first();
            $infoKategori = strtoupper(($peserta->gender ?? '') . ($kelasData ? "| Kelas " . $kelasData->name : ""));
        }

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
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
        }

        .match-number {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        /* --- Grid Layout for Scoring --- */
        .scoring-grid {
            display: grid;
            grid-template-columns: 1fr 4fr 2fr 4fr 1fr;
            /* Proportions: 1-4-2-4-1 */
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid #333;
        }

        .grid-header {
            background: #333;
            color: white;
            padding: 15px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1.1rem;
            text-align: center;
            border-bottom: 1px solid #444;
            border-right: 1px solid #444;
        }

        .grid-header:last-child {
            border-right: none;
        }

        .grid-item {
            padding: 12px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            min-height: 60px;
        }

        /* Remove right border for the last items in each virtual row (which is the 5th column usually, but grid-span complicates it) */
        /* To keep it simple, we'll use border-right on all and override the far right ones if they are consistently in col 5 */

        .juri-label-cell {
            background-color: #f8f9fa;
            font-weight: 700;
            color: #555;
            justify-content: center;
            font-size: 0.9rem;
        }

        .score-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 6px;
            overflow: hidden;
            min-height: 40px;
            align-items: center;
            overflow-x: auto;
            width: 100%;
            scrollbar-width: thin;
        }

        .score-container::-webkit-scrollbar {
            height: 4px;
        }

        .score-container::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .score-container div {
            background: #eee;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1.1rem;
            white-space: nowrap;
        }

        .babak-cell {
            font-size: 3rem;
            font-weight: 900;
            background: #fff;
            justify-content: center;
            border-left: 2.5px solid #333;
            border-right: 2.5px solid #333;
            transition: all 0.3s;
            grid-column: 3;
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
                <div class="arena-name text-uppercase">{{ $arenaData->name }}</div>
                <div class="match-number">Partai {{ $datakp["partai"] }}</div>
                <div class="team-kontigen" style="color: #333;">{{ $infoKategori }}</div>
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

        <!-- Main Scoring Grid (1-4-2-4-1) -->
        <div class="scoring-grid">
            <!-- Grid Headers -->
            <div class="grid-header" style="grid-column: span 2;">RIWAYAT JURI (BIRU)</div>
            <div class="grid-header">BABAK</div>
            <div class="grid-header" style="grid-column: span 2;">RIWAYAT JURI (MERAH)</div>

            @for ($babak = 1; $babak <= 3; $babak++)
                @for ($jIdx = 1; $jIdx <= 3; $jIdx++)
                    <!-- Juri Label Label L -->
                    <div class="grid-item juri-label-cell" style="grid-column: 1;">JURI {{ $jIdx }}</div>

                    <!-- Blue Scores -->
                    <div class="grid-item" style="grid-column: 2;">
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
                    <div class="grid-item" style="grid-column: 4; justify-content: flex-end;">
                        <div id="data{{$babak}}m_{{$jIdx}}" class="score-container" style="justify-content: flex-end;">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Juri Label Label R -->
                    <div class="grid-item juri-label-cell" style="grid-column: 5; border-right: none;">JURI {{ $jIdx }}</div>
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
            const placeholder = '<div>-</div>';

            $(`#namab`).text(info.biru.nama);
            $(`#namam`).text(info.merah.nama);

            $(`#kontigenb`).text(info.biru.kontigen);
            $(`#kontigenm`).text(info.merah.kontigen);

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
                    console.log("Terhubung ke Soketi!");
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