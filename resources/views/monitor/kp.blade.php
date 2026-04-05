@extends('layout.master2')

@section('content')
    @php
        use App\arena;
        use App\PersertaModel;
        use App\kelas;

        $data = $datakp;
        $arenaData = arena::where('id', $data['arena'] ?? '')->first();
        $currentRound = $data['babak'];

        // Get Category info (consistent with dewan)
        $peserta = PersertaModel::where('id', $data['idBiru'] ?? '')->first();
        $infoKategori = '';
        if ($peserta) {
            $kelasData = kelas::where('id', $peserta->kelas)->first();
            $infoKategori = strtoupper(($peserta->gender ?? '') . ($kelasData ? "| Kelas  " . $kelasData->name : ""));
        }
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

        /* --- Grid Layout for KP Monitoring --- */
        .kp-grid {
            display: grid;
            grid-template-columns: 1fr 4fr 2fr 4fr 1fr;
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
            padding: 0;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            min-height: 70px;
        }

        .label-cell {
            background-color: #f8f9fa;
            font-weight: 700;
            color: #555;
            justify-content: center;
            font-size: 0.9rem;
            text-align: center;
            padding: 15px;
        }

        .value-cell {
            font-size: 2.2rem;
            font-weight: 900;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .value-blue {
            color: #0d6efd;
        }

        .value-red {
            color: #dc3545;
        }

        .sub-grid-babak {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            width: 100%;
            height: 100%;
        }

        .sub-grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #eee;
            padding: 10px 5px;
        }

        .sub-grid-item:last-child {
            border-right: none;
        }

        .babak-sub-header {
            font-size: 0.7rem;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .sub-val {
            font-size: 1.8rem;
            line-height: 1;
        }

        .category-name-cell {
            font-size: 1.1rem;
            font-weight: 800;
            background: #eee;
            justify-content: center;
            text-transform: uppercase;
            border-left: 2px solid #333;
            border-right: 2px solid #333;
            color: #333;
            padding: 15px;
        }

        .highlight-flash {
            background-color: #fff3cd !important;
            animation: flash 0.5s ease-in-out;
        }

        .round-indicator {
            background: #FFD600;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 900;
            font-size: 1rem;
            display: inline-block;
            margin-top: 5px;
        }

        @keyframes flash {
            0% {
                background-color: #fff3cd;
            }

            50% {
                background-color: #FFD600;
            }

            100% {
                background-color: #fff3cd;
            }
        }

        .icon-stroke {
            filter: drop-shadow(1px 1px 0px #fff) drop-shadow(-1px -1px 0px #fff) drop-shadow(1px -1px 0px #fff) drop-shadow(-1px 1px 0px #fff);
        }

        .grid-icon {
            width: 30px;
            height: 30px;
            filter: grayscale(50%) drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.3));
        }
    </style>

    <div class="py-4 container-fluid px-5">
        <!-- Header Section -->
        <div class="header-monitor">
            <!-- Team Blue -->
            <div class="team-box">
                <div class="team-icon-circle blue-circle">
                    <img src="{{ asset('assets/Assets/karate.png') }}" alt="Blue Team Icon" class="icon-stroke">
                </div>
                <div class="team-labels">
                    <span id="kontigenb" class="team-kontigen">{{ $data['kontigenBiru'] ?? '-' }}</span>
                    <span id="namab" class="team-nama text-primary">{{ $data['namaBiru'] ?? '-' }}</span>
                </div>
            </div>

            <!-- Arena/Match Info -->
            <div class="arena-info">
                <div class="arena-name text-uppercase">{{ $arenaData->name ?? 'ARENA' }}</div>
                <div id="partai" class="match-number">Partai {{ $data['partai'] ?? '-' }}</div>
                <div class="team-kontigen" style="color: #333; margin-bottom: 5px;">{{ $infoKategori }}</div>
                <div class="round-indicator">BABAK <span id="babak">{{ $currentRound }}</span></div>
            </div>

            <!-- Team Red -->
            <div class="team-box red">
                <div class="team-labels">
                    <span id="kontigenm" class="team-kontigen">{{ $data['kontigenMerah'] ?? '-' }}</span>
                    <span id="namam" class="team-nama text-danger">{{ $data['namaMerah'] ?? '-' }}</span>
                </div>
                <div class="team-icon-circle red-circle">
                    <img src="{{ asset('assets/Assets/karate (1).png') }}" alt="Red Team Icon" class="icon-stroke">
                </div>
            </div>
        </div>

        <!-- Main KP Grid -->
        <div class="kp-grid">
            <div class="grid-header">ICON</div>
            <div class="grid-header">TIM BIRU</div>
            <div class="grid-header">KATEGORI</div>
            <div class="grid-header">TIM MERAH</div>
            <div class="grid-header">ICON</div>

            @php
                $categories = [
                    ['id_b' => 'b1b', 'id_m' => 'b1m', 'label' => 'Binaan I', 'icon' => 'assets/Assets/pointing_hand.png', 'split' => true],
                    ['id_b' => 'b2b', 'id_m' => 'b2m', 'label' => 'Binaan II', 'icon' => 'assets/Assets/peace_hand.png', 'split' => true],
                    ['id_b' => 't1b', 'id_m' => 't1m', 'label' => 'Teguran I', 'icon' => 'assets/Assets/pointing_hand.png', 'split' => true],
                    ['id_b' => 't2b', 'id_m' => 't2m', 'label' => 'Teguran II', 'icon' => 'assets/Assets/peace_hand.png', 'split' => true],
                    ['id_b' => 'totalJatuhan1', 'id_m' => 'totalJatuhan2', 'label' => 'Jatuhan', 'icon' => 'assets/Assets/judo white.png', 'split' => false],
                    ['id_b' => 'totalPeringatan1', 'id_m' => 'totalPeringatan2', 'label' => 'Peringatan', 'icon' => 'assets/Assets/raising_hand.png', 'split' => false],
                ];
            @endphp

            @foreach ($categories as $cat)
                <!-- Icon L -->
                <div class="grid-item label-cell" style="grid-column: 1;">
                    <img src="{{ asset($cat['icon']) }}" class="grid-icon">
                </div>

                <!-- Blue Value -->
                <div class="grid-item" style="grid-column: 2;">
                    @if($cat['split'])
                        <div class="sub-grid-babak value-blue">
                            @for($i = 1; $i <= 3; $i++)
                                <div id="card-{{ $cat['id_b'] }}_{{ $i }}" class="sub-grid-item">
                                    <span class="babak-sub-header">B {{$i}}</span>
                                    <span id="{{ $cat['id_b'] }}_{{ $i }}"
                                        class="sub-val">{{ $data[$cat['id_b'] . '_' . $i] ?? 0 }}</span>
                                </div>
                            @endfor
                        </div>
                    @else
                        <div id="card-{{ $cat['id_b'] }}" class="value-cell value-blue d-flex align-items-center">
                            <span id="{{ $cat['id_b'] }}">{{ $data[$cat['id_b']] ?? 0 }}</span>
                        </div>
                    @endif
                </div>

                <!-- Category Name -->
                <div class="grid-item category-name-cell" style="grid-column: 3;">
                    {{ $cat['label'] }}
                </div>

                <!-- Red Value -->
                <div class="grid-item" style="grid-column: 4;">
                    @if($cat['split'])
                        <div class="sub-grid-babak value-red">
                            @for($i = 1; $i <= 3; $i++)
                                <div id="card-{{ $cat['id_m'] }}_{{ $i }}" class="sub-grid-item">
                                    <span class="babak-sub-header">B {{$i}}</span>
                                    <span id="{{ $cat['id_m'] }}_{{ $i }}"
                                        class="sub-val">{{ $data[$cat['id_m'] . '_' . $i] ?? 0 }}</span>
                                </div>
                            @endfor
                        </div>
                    @else
                        <div id="card-{{ $cat['id_m'] }}" class="value-cell value-red d-flex align-items-center">
                            <span id="{{ $cat['id_m'] }}">{{ $data[$cat['id_m']] ?? 0 }}</span>
                        </div>
                    @endif
                </div>

                <!-- Icon R -->
                <div class="grid-item label-cell" style="grid-column: 5; border-right: none;">
                    <img src="{{ asset($cat['icon']) }}" class="grid-icon">
                </div>
            @endforeach
        </div>

        <input type="hidden" name="{{ $data['arena'] ?? '' }}" id="arenaid">
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        const INITIAL_DATA = @json($datakp);

        function flashHighlight(elementId) {
            const $card = $(`#card-${elementId}`);
            $card.addClass('highlight-flash');
            setTimeout(() => {
                $card.removeClass('highlight-flash');
            }, 1000);
        }

        function updateScores(data) {
            console.log("WebSocket Score Update:", data);

            // Updated keys for split data
            const rounds = [1, 2, 3];
            const splitTypes = ['b1b', 'b2b', 't1b', 't2b', 'b1m', 'b2m', 't1m', 't2m'];

            splitTypes.forEach(type => {
                rounds.forEach(r => {
                    const key = `${type}_${r}`;
                    const $element = $(`#${key}`);
                    if ($element.length) {
                        const newValue = data[key] !== undefined ? data[key] : 0;
                        const currentValue = parseInt($element.text()) || 0;
                        if (newValue !== currentValue) {
                            $element.text(newValue);
                            flashHighlight(key);
                        }
                    }
                });
            });

            // Single keys
            const singleKeys = ['totalJatuhan1', 'totalJatuhan2', 'totalPeringatan1', 'totalPeringatan2',
                'totalBinaan1Biru', 'totalBinaan2Biru', 'totalTeguran1Biru', 'totalTeguran2Biru',
                'totalBinaan1Merah', 'totalBinaan2Merah', 'totalTeguran1Merah', 'totalTeguran2Merah'];
            singleKeys.forEach(key => {
                const $element = $(`#${key}`);
                if ($element.length) {
                    const newValue = data[key] !== undefined ? data[key] : 0;
                    const currentValue = parseInt($element.text()) || 0;
                    if (newValue !== currentValue) {
                        $element.text(newValue);
                        flashHighlight(key);
                    }
                }
            });

            $('#partai').text(`Partai ${data.partai}`);
            $('#babak').text(data.babak);
            $('#namab').text(data.namaBiru);
            $('#namam').text(data.namaMerah);
            $('#kontigenb').text(data.kontigenBiru);
            $('#kontigenm').text(data.kontigenMerah);
        }

        function websocket() {
            var arena_id = $('#arenaid').attr('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Soketi!");
                });

                Echo.channel('score-channel')
                    .listen('ScoreEvent', (datas) => {
                        if (arena_id == datas.message.arena) {
                            updateScores(datas.message);
                        }
                    });
            }
        }

        $(document).ready(function () {
            updateScores(INITIAL_DATA);
            websocket();
        });
    </script>
@endsection