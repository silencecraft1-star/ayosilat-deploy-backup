@extends('layout.master2')

@section('content')
@php
    // --- Data Initialization (from controller) ---
    // The $datakp variable holds the entire response array from sendTandingScore
    use App\arena;
    $data = $datakp; 
    $arena = arena::where('id', $data["arena"])->first();
    // Extracting key variables for PHP rendering
    $currentRound = $data['babak'];
@endphp

<style>
    /* Custom styles for visibility and theme */
    .poppins-regular {
        font-family: "Poppins", serif;
        font-weight: 400;
        font-style: normal;
    }
    .center-info {
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .detail-card {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-weight: 500;
        height: 48px; 
        transition: all 0.2s ease-in-out;
    }
    
    /* --- Score Detail Colors (Retained custom background CSS) --- */
    .bg-blue-score { background-color: #e0f2fe; } /* Equivalent to blue-50 */
    .bg-red-score { background-color: #fee2e2; }  /* Equivalent to red-50 */
    .text-blue-score { color: #0369a1; } /* Equivalent to blue-700/800 */
    .text-red-score { color: #dc2626; }  /* Equivalent to red-700/800 */

    /* Highlight class for visual feedback on update (for penalties/fouls) */
    .highlight-flash {
        box-shadow: 0 0 15px 3px rgba(255, 193, 7, 0.7); /* Yellow glow */
        transform: scale(1.02);
    }

    /* Custom sizing for assets (No direct BS equivalent, kept in CSS) */
    .size-8 { width: 2rem; height: 2rem; }
    .size-24 { width: 6rem; height: 6rem; }
</style>

<div class="poppins-regular container-fluid py-3">

    {{-- Info Section: Participants and Match Status (Centered) --}}
    <section class="mb-5 center-info">
        <div class="row align-items-center">
            
            {{-- Blue Team Info --}}
            <div class="col-5 d-flex flex-column align-items-start">
                <div id="kontigenb" class="text-blue-score fs-3 text-uppercase fw-bold" id="kontigenBiru">{{ $data['kontigenBiru'] }}</div>
                <div id="namab" class="fs-1 fw-bold text-uppercase mb-3" id="namaBiru">{{ $data['namaBiru'] }}</div>
            </div>

            {{-- Center Info (Babak, Time, Match Status) --}}
            <div class="col-2 text-center">
                <div class="text-primary fs-3">
                    {{ $arena->name }}
                </div>
                {{-- <div class="fs-6 text-muted mb-2" id="infoKelas">{{ $data['infoKelas'] }}</div> --}}
                <div class="fs-3 fw-bold text-dark" id="partai">
                    Partai {{ $data['partai'] }}
                </div>
                <div class="fs-3 fw-semibold text-uppercase text-danger mb-3">
                    ROUND <span id="babak">{{ $currentRound }}</span>
                </div>
            </div>

            {{-- Red Team Info --}}
            <div class="col-5 d-flex flex-column align-items-end">
                <div id="kontigenm"class="text-red-score fs-3 fw-bold text-end text-uppercase" id="kontigenMerah">{{ $data['kontigenMerah'] }}</div>
                <div id="namam"class="fs-1 fw-bold text-end text-uppercase mb-3" id="namaMerah">{{ $data['namaMerah'] }}</div>
            </div>
        </div>
    </section>

    <hr class="my-4">

    {{-- Detail Recap Section (Focus on Penalties/Actions) --}}
    <section class="mb-5">
        <h3 class="text-center fs-3 fw-bold mb-4">View KP Tanding</h3>
        <div class="row">
            
            {{-- Left Side: Blue Team Details --}}
            <div class="col-6">
                <div class="text-primary text-center fs-2 mb-3 fw-semibold">Tim Biru</div>
                @php
                    $details = [
                        // ['id' => 'pukulanb', 'label' => 'Pukulan (x1)', 'value' => $data['pukulanb'], 'bg_class' => 'bg-blue-score', 'img' => '../assets/Assets/fist (2).png', 'img_class' => ''],
                        // ['id' => 'tendanganb', 'label' => 'Tendangan (x2)', 'value' => $data['tendanganb'], 'bg_class' => 'bg-blue-score', 'img' => '../assets/Assets/kick.png', 'img_class' => 'scale-x-[-1]'],
                        ['id' => 'totalJatuhan1', 'label' => 'Jatuhan', 'value' => $data['jatuh1'], 'bg_class' => 'bg-info bg-opacity-25 highlightable', 'img' => '../assets/Assets/judo white.png', 'img_class' => ''],
                        ['id' => 'totalBinaan1Biru', 'label' => 'Binaan I', 'value' => $data['totalBinaan1Biru'], 'bg_class' => 'bg-blue-score highlightable', 'img' => '../assets/Assets/pointing_hand.png', 'img_class' => ''],
                        ['id' => 'totalBinaan2Biru', 'label' => 'Binaan II', 'value' => $data['totalBinaan2Biru'], 'bg_class' => 'bg-blue-score highlightable', 'img' => '../assets/Assets/peace_hand.png', 'img_class' => 'rotate-90'],
                        ['id' => 'totalTeguran1Biru', 'label' => 'Teguran I', 'value' => $data['totalTeguran1Biru'], 'bg_class' => 'bg-blue-score highlightable', 'img' => '../assets/Assets/pointing_hand.png', 'img_class' => '-rotate-90'],
                        ['id' => 'totalTeguran2Biru', 'label' => 'Teguran II', 'value' => $data['totalTeguran2Biru'], 'bg_class' => 'bg-blue-score highlightable', 'img' => '../assets/Assets/peace_hand.png', 'img_class' => ''],
                        ['id' => 'totalPeringatan1', 'label' => 'Peringatan (x5)', 'value' => $data['totalPeringatan1'], 'bg_class' => 'bg-info bg-opacity-25 highlightable', 'img' => '../assets/Assets/raising_hand.png', 'img_class' => '']
                    ];
                @endphp
                @foreach ($details as $detail)
                    <div class="detail-card {{ $detail['bg_class'] }}" id="card-{{ $detail['id'] }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle p-1 me-3">
                                <img src="{{ asset($detail['img']) }}" class="size-8 {{ $detail['img_class'] }}" alt="{{ $detail['label'] }}">
                            </div>
                            <span class="fs-5">{{ $detail['label'] }}</span>
                        </div>
                        <div class="fs-5 fw-bold" id="{{ $detail['id'] }}">{{ $detail['value'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Right Side: Red Team Details --}}
            <div class="col-6">
                <div class="text-danger text-center fs-2 mb-3 fw-semibold">Tim Merah</div>
                @php
                    $details = [
                        // ['id' => 'pukulanm', 'label' => 'Pukulan (x1)', 'value' => $data['pukulanm'], 'bg_class' => 'bg-red-score', 'img' => '../assets/Assets/fist (2).png', 'img_class' => 'scale-x-[-1]'],
                        // ['id' => 'tendanganm', 'label' => 'Tendangan (x2)', 'value' => $data['tendanganm'], 'bg_class' => 'bg-red-score', 'img' => '../assets/Assets/kick.png', 'img_class' => ''],
                        ['id' => 'totalJatuhan2', 'label' => 'Jatuhan', 'value' => $data['jatuh2'], 'bg_class' => 'bg-danger bg-opacity-25 highlightable', 'img' => '../assets/Assets/judo white.png', 'img_class' => 'scale-x-[-1]'],
                        ['id' => 'totalBinaan1Merah', 'label' => 'Binaan I', 'value' => $data['totalBinaan1Merah'], 'bg_class' => 'bg-red-score highlightable', 'img' => '../assets/Assets/pointing_hand.png', 'img_class' => 'scale-x-[-1]'],
                        ['id' => 'totalBinaan2Merah', 'label' => 'Binaan II', 'value' => $data['totalBinaan2Merah'], 'bg_class' => 'bg-red-score highlightable', 'img' => '../assets/Assets/peace_hand.png', 'img_class' => '-rotate-90 scale-x-[-1]'],
                        ['id' => 'totalTeguran1Merah', 'label' => 'Teguran I', 'value' => $data['totalTeguran1Merah'], 'bg_class' => 'bg-red-score highlightable', 'img' => '../assets/Assets/pointing_hand.png', 'img_class' => 'rotate-90 scale-x-[-1]'],
                        ['id' => 'totalTeguran2Merah', 'label' => 'Teguran II', 'value' => $data['totalTeguran2Merah'], 'bg_class' => 'bg-red-score highlightable', 'img' => '../assets/Assets/peace_hand.png', 'img_class' => 'scale-x-[-1]'],
                        ['id' => 'totalPeringatan2', 'label' => 'Peringatan (x5)', 'value' => $data['totalPeringatan2'], 'bg_class' => 'bg-danger bg-opacity-25 highlightable', 'img' => '../assets/Assets/raising_hand.png', 'img_class' => 'scale-x-[-1]']
                    ];
                @endphp
                 @foreach ($details as $detail)
                    <div class="detail-card {{ $detail['bg_class'] }}" id="card-{{ $detail['id'] }}">
                        <div class="fs-5 fw-bold" id="{{ $detail['id'] }}">{{ $detail['value'] }}</div>
                        <div class="d-flex align-items-center">
                            <span class="me-3 fs-5">{{ $detail['label'] }}</span>
                            <div class="bg-danger rounded-circle p-1">
                                <img src="{{ asset($detail['img']) }}" class="size-8 {{ $detail['img_class'] }}" alt="{{ $detail['label'] }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



</div>
{{-- Hidden elements for JavaScript access --}}
<div class="d-none" name="{{ $data['arena'] ?? '' }}" id="arenaid"></div>
<div class="d-none" name="{{ $data['partai'] ?? '' }}" id="idpartai"></div>
<div class="d-none" name="{{ $data['sesi'] ?? '' }}" id="sesiid"></div>


{{-- Scripts --}}
<script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
{{-- Include custom core file which should contain modal and time logic (like getselisih) --}}
{{-- @include('addon.tanding.core') --}}

<script>
    // --- JavaScript for Real-time Update ---

    const INITIAL_DATA = @json($datakp);
    
    // UI elements that need updating (matches keys from sendTandingScore response)
    const UI_ELEMENTS = [
        'jaruh1', 'jatuh2', 'totalBinaan1Biru', 'totalBinaan2Biru', 'totalTeguran1Biru', 'totalTeguran2Biru',
        'totalBinaan1Merah', 'totalBinaan2Merah', 'totalTeguran1Merah', 'totalTeguran2Merah',
        'totalJatuhan1', 'totalJatuhan2', 'totalPeringatan1', 'totalPeringatan2',
    ];

    /**
     * Highlights an element briefly when its value changes.
     * @param {string} elementId - The ID of the element to highlight.
     */
    function flashHighlight(elementId) {
        // Only flash elements marked as highlightable (penalties/fouls)
        if (elementId.includes('Binaan') || elementId.includes('Teguran') || elementId.includes('Peringatan') || elementId.includes('Jatuhan')) {
            const $card = $(`#card-${elementId}`);
            $card.addClass('highlight-flash');
            setTimeout(() => {
                $card.removeClass('highlight-flash');
            }, 500);
        }
    }

    /**
     * Updates the UI elements based on the incoming WebSocket data payload.
     * @param {Object} data - The score data object from sendTandingScore.
     */
    function updateScores(data) {

        // 1. Update Detail Recap Counters and check for highlights
        // UI_ELEMENTS.forEach(key => {
        //     const $element = $(`#${key}`);
        //     const newValue = data[key] || 0;
        //     const currentValue = parseInt($element.text());

        //     if (data.hasOwnProperty(key)) {
        //         if (newValue > currentValue) {
        //             flashHighlight(key);
        //         }
        //         $element.text(newValue);
        //     }
        // });

        // 2. Update Match Status and Round
        $('#partai').text(`Partai ${data.partai}`);
        $('#statusPertandingan').text(data.statusPertandingan);
        $('#babak').text(data.babak);

        console.log(data.namaBiru);
        $('#kontigenb').text(data.kontigenBiru);
        $('#namab').text(data.namaBiru);

        $('#kontigenm').text(data.kontigenMerah);
        $('#namam').text(data.namaMerah);
        
        // 3. Update Timer
        if (data.status === "pause") {
            $('#timer1').text("PAUSE");
        } else if (data.time) {
             // Assuming getselisih function is available from addon.tanding.core
            if (typeof getselisih === 'function') {
                const timer = getselisih(data.time);
                $('#timer1').text(timer);
            } else {
                 $('#timer1').text(data.time); 
            }
        }
        
        // 4. Handle Modal Notifications
        if (data.notif === "not") {
             $('.modal').modal('hide');
        }
    }


    // --- WebSocket Implementation ---
    function websocket() {
        var arena_id = $('#arenaid').attr('name');
        if (window.Echo) {
            window.Echo.connector.pusher.connection.bind('connected', function () {
                console.log("Terhubung ke Soketi!");
            });

            // Listen for the ScoreEvent which carries the final processed score data
            Echo.channel('score-channel') 
                .listen('ScoreEvent', (datas) => {
                    // Use datas.message because the event payload wraps the response data in 'message'
                    if(arena_id == datas.message.arena) {
                        console.log(datas.messsage);
                        updateScores(datas.message);
                    }
                });
            
        } else {
            console.error('Laravel Echo is not initialized. Real-time updates disabled.');
        }
    }

    // --- Initial Load ---
    $(document).ready(function() {
        // We ensure data is correctly parsed before starting the websocket listener
        updateScores(INITIAL_DATA);
        websocket();
    });
</script>
@endsection
