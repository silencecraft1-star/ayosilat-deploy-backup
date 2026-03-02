@extends('layout.master2')

@section('content')
@php
    use App\arena;
    // --- Data Initialization (from controller) ---
    $pending_scores = $datakp['data']; // Collection of all pending_tanding records
    $currentRound = $datakp['babak'];
    $teamBlue = $datakp['biru'];
    $teamRed = $datakp['merah'];
    $arena = $datakp['arena'];
    $juriMap = $datakp['juri']; // Access the new Juri IDs: ['juri_1' => ID_A, 'juri_2' => ID_B, ...]
    // dd($datakp);
    $arenaData = arena::where('id', $arena)->first();
    // Safely extract the two participant IDs (assuming two unique IDs in the pending scores)
    $team_ids = $pending_scores->pluck('id_perserta')->unique()->toArray();
    $tim_biru_id = $team_ids[0] ?? ''; 
    $tim_merah_id = $team_ids[4] ?? '';
    // UI Loop setup
    $jumlahJuri = 3;
@endphp

<div class="py-5 container">
    {{-- Display overall match info --}}
    <div class="row mb-5">
        <div class="col text-start">
            <span id="kontigenb" class="team fw-bold text-uppercase">{{ $teamBlue['kontigen'] }}</span> <br>
            <span id="namab" class="peserta text-primary text-uppercase">{{ $teamBlue['nama'] }}</span>
        </div>
        <div class="col">
            <div class="mb-2">
                <div class="text-center text-primary fs-3">
                    {{ $arenaData->name }}
                </div>
            </div>
            <div class="mb-1">
                <div class="text-center fs-3">
                    Partai {{ $datakp["partai"] }}
                </div>
            </div>
        </div>
        <div class="col text-end">
            <span id="kontigenm" class="team fw-bold text-uppercase">{{ $teamRed['kontigen'] }}</span> <br>
            <span id="namam" class="peserta text-danger text-uppercase">{{ $teamRed['nama'] }}</span>
        </div>
    </div>
    
    {{-- Loop through each judge (Juri) --}}
    @for ($i = 1; $i <= $jumlahJuri; $i++)
        @php
            $juriKeyName = 'juri_' . $i; // Key name in the $juriMap: 'juri_1', 'juri_2', etc.
            $juriKey = $juriMap[$juriKeyName] ?? null; // The actual Juri ID from DB (e.g., 'JURI-A')
            $currentJuriName = "Juri {$i}";

            // Filter the initial data set for this specific judge using the actual Juri ID
            $scoresByJuri = $pending_scores->filter(function ($item) use ($juriKey) {
                // Ensure $item->juri1 matches the actual ID passed from the controller
                return $item->juri1 === $juriKey; 
            });

            // Filter by babak and team for initial render
            $data1b = $scoresByJuri->where('id_perserta', $tim_biru_id)->where('babak', '1');
            $data2b = $scoresByJuri->where('id_perserta', $tim_biru_id)->where('babak', '2');
            $data3b = $scoresByJuri->where('id_perserta', $tim_biru_id)->where('babak', '3');
            $data1m = $scoresByJuri->where('id_perserta', $tim_merah_id)->where('babak', '1');
            $data2m = $scoresByJuri->where('id_perserta', $tim_merah_id)->where('babak', '2');
            $data3m = $scoresByJuri->where('id_perserta', $tim_merah_id)->where('babak', '3');
        @endphp

        <div class="row my-4 border-bottom pb-4 judge-row">
            <div class="col-12 text-center fs-3 mb-3">
                {{-- Display the judge name and their actual ID for troubleshooting --}}
                <h2 class="fw-bold">{{ $currentJuriName }} (ID: {{ $juriKey }})</h2>
            </div>

            <!-- Scorering UI Structure -->
            <section id="scorering-{{ $i }}" class="d-flex justify-content-center w-100 score-section">
                
                {{-- Kiri (Blue Score Table) --}}
                <div class="blueScore table-responsive me-3">
                    <table class="table table-bordered border border-dark" style="min-width: 500px; max-width: 500px;">
                        <thead>
                            <tr>
                                <th scope="col" class=" text-center bg-primary text-white" colspan="3">Riwayat Point Tim Biru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($babak = 1; $babak <= 3; $babak++)
                                @php 
                                    // Use dynamic variable name for the collection based on loop
                                    // $data = ${"data{$babak}b"}; 
                                    $data = 'data'.$babak.'b'; 
                                @endphp
                                <tr>
                                    {{-- Dynamic ID for JavaScript updates: data[Round][Team]_Juri[Number] --}}
                                    <td colspan="3" class="d-flex flex-wrap" style="min-height: 40px;" id="data{{$babak}}b_{{$i}}">
                                        @forelse ($data as $item)
                                            @php
                                                // Dynamic score calculation: Pukulan=1, Tendangan=2
                                                $scoreValue = ($item->keterangan == 'pukulan') ? 1 : (($item->keterangan == 'tendangan') ? 2 : $item->score);
                                            @endphp
                                            @if($item->isValid == "false")
                                                <div class="text-decoration-line-through">
                                                    {{ $scoreValue }},
                                                </div>
                                            @else
                                                <div>{{ $scoreValue }},</div>
                                            @endif
                                        @empty
                                            <div>-</div> 
                                        @endforelse
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                {{-- Tengah (Babak Table) --}}
                <div class="babak d-flex flex-column align-items-center me-3" style="min-width: 100px;">
                    <table class="table tabelBabak border border-dark">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center border-top border-black">BABAK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="babak-1">
                                <td class="text-center" @if ($currentRound == '1') style="background-color: #FFD600;" @endif>I</td>
                            </tr>
                            <tr id="babak-2">
                                <td class="text-center" @if ($currentRound == '2') style="background-color: #FFD600;" @endif>II</td>
                            </tr>
                            <tr id="babak-3">
                                <td class="text-center" @if ($currentRound == '3') style="background-color: #FFD600;" @endif>III</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                

                {{-- Kanan (Red Score Table) --}}
                <div class="redScore table-responsive">
                    <table class="table table-bordered border border-dark" style="min-width: 500px; max-width: 500px;">
                        <thead>
                            <tr>
                                <th scope="col" class=" text-center bg-danger text-white" colspan="3">Riwayat Point Tim Merah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($babak = 1; $babak <= 3; $babak++)
                                @php 
                                    // Use dynamic variable name for the collection based on loop
                                    $data = ${"data{$babak}m"}; 
                                @endphp
                                <tr>
                                    {{-- Dynamic ID for JavaScript updates: data[Round][Team]_Juri[Number] --}}
                                    <td colspan="3" class="d-flex flex-wrap" style="min-height: 40px;" id="data{{$babak}}m_{{$i}}">
                                        @forelse ($data as $item)
                                            @php
                                                // Dynamic score calculation: Pukulan=1, Tendangan=2
                                                $scoreValue = ($item->keterangan == 'pukulan') ? 1 : (($item->keterangan == 'tendangan') ? 2 : $item->score);
                                            @endphp
                                            @if($item->isValid == "false")
                                                <div class="text-decoration-line-through">
                                                    {{ $scoreValue }},
                                                </div>
                                            @else
                                                <div>{{ $scoreValue }},</div>
                                            @endif
                                        @empty
                                            <div>-</div> 
                                        @endforelse
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    @endfor
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
        $(`.tabelBabak tr td`).css('background-color', '');
        $(`#babak-${currentBabak} td`).css('background-color', '#FFD600');


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
    $(document).ready(function() {
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

                    if(arena_id == data.arena) {
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
                            // Target the first/only <td> inside the <tr>
                            $(`#babak-${i} td`).css('background-color', 
                                i === currentBabak ? '#FFD600' : '' // Set 'transparent' or empty string for default
                            );
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
