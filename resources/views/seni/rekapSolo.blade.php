<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Solo — Arena {{ $arena ?? '' }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/seni/DewanSolo.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/seni/ScoreSeni.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/seni/JuriSeni.css') }}">

    @php
        use App\PersertaModel;
        use App\KontigenModel;
        use App\jadwal_group;
        use App\score;
        use App\Setting;

        if (empty($id_user) || empty($arena)) {
            echo '<script>window.history.back();</script>';
            exit();
        }

        $id_juri = $id_juri ?? request('id_juri') ?? request('name') ?? '';
        $settingGlobal = Setting::where('keterangan', 'admin-setting')->first();
        $juriSeni = (int)($settingGlobal->jadwal ?? 4);
        if ($juriSeni < 1) {
            $juriSeni = 4;
        }

        $perserta = PersertaModel::where('id', $id_user)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen ?? null)->value('kontigen') ?? '-';
        $group = jadwal_group::where('biru', $id_user)->where('arena', $arena)->first() 
            ?? jadwal_group::where('merah', $id_user)->where('arena', $arena)->first();

        $settingArena = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        $savedTime = null;
        if ($group) {
            if ($group->biru == $id_user) {
                $savedTime = $group->timer_biru ?? $group->kondisi ?? null;
            } else {
                $savedTime = $group->timer_merah ?? $group->kondisi ?? null;
            }
        }
        if (empty($savedTime) || $savedTime === '00:00;00' || $savedTime === '00:00:00') {
            $savedTime = $settingArena->time ?? '00:00;00';
        }

        $timerParsed = parse_timer_display($savedTime);
        $menit = $timerParsed['minute'];
        $detik = $timerParsed['second'];
        $ms = $timerParsed['ms'];
        $formattedTimer = $timerParsed['formatted'];

        $initScore = '0.00';
        $initDeviation = '0';
        if ($group) {
            if ($group->biru == $id_user) {
                $initScore = $group->score_biru ? format_final_score_precision($group->score_biru) : '0.00';
                $initDeviation = $group->deviasi_biru ?? '0';
            } else {
                $initScore = $group->score_merah ? format_final_score_precision($group->score_merah) : '0.00';
                $initDeviation = $group->deviasi_merah ?? '0';
            }
        }

        // Ambil summary pengurangan dewan
        $penaltiesSummary = get_dewan_penalties_summary($id_user, $arena, $group->partai ?? null);
        $totalPenaltiDewan = $penaltiesSummary['total_score'];

        // 5 Pelanggaran Standar Dewan Kategori Solo
        $standardRules = [
            'RULE_1' => [
                'aliases' => ['PESERTAKELUARDARIARENA', 'PESERTAKELUARDARI10X10METERARENA'],
                'label' => 'Peserta keluar dari arena (10x10 m)',
            ],
            'RULE_2' => [
                'aliases' => ['SENJATATIDAKSESUAIDENGANDESKRIPSI', 'SENJATATIDAKSESUAI'],
                'label' => 'Senjata tidak sesuai deskripsi / ketentuan',
            ],
            'RULE_3' => [
                'aliases' => ['SENJATAJATUHKELUARARENAWALAUPUNTIMMASIHDITUNTUTUNTUKMENGGUNAKANNYA'],
                'label' => 'Senjata jatuh keluar arena saat masih harus digunakan',
            ],
            'RULE_4' => [
                'aliases' => ['PESERTABERHENTIDALAM1GERAKANLEBIHDARI5DETIK'],
                'label' => 'Peserta diam dalam 1 gerakan > 5 detik',
            ],
            'RULE_5' => [
                'aliases' => ['PESILATMELEBIHIBATASWAKTUTOLERANSI'],
                'label' => 'Pesilat melebihi batas waktu toleransi',
            ],
        ];

        $ruleScores = [];
        $matchedKeys = [];
        foreach ($standardRules as $rKey => $rule) {
            $sum = 0.0;
            foreach ($penaltiesSummary['items'] as $item) {
                if (in_array($item['normalized_key'], $rule['aliases'])) {
                    $sum += (float)$item['total_points'];
                    $matchedKeys[] = $item['normalized_key'];
                }
            }
            $ruleScores[$rKey] = $sum;
        }

        $extraPenalties = [];
        foreach ($penaltiesSummary['items'] as $item) {
            if (!in_array($item['normalized_key'], $matchedKeys)) {
                $extraPenalties[] = $item;
            }
        }
    @endphp
</head>

<body>
    <section>
        <!-- Splash Loader -->
        <div id="splash" class="absolute w-screen h-screen bg-slate-100 flex justify-center items-center transition-all duration-500 z-50">
            <div class="inline bg-gradient-to-br from-blue-700 to-blue-300 text-3xl bg-clip-text text-transparent font-bold">
                Sedang Mengambil Data...
            </div>
        </div>

        <!-- Header -->
        <div class="w-full bg-blue-600 mb-3 shadow-lg shadow-gray-400 py-2">
            <div class="lg:grid lg:grid-cols-3 h-full py-1">
                <div class="flex items-center justify-center lg:justify-start lg:ms-5 lg:mb-0 gap-2">
                    <a href="{{ url('/redirect?arena=' . $arena . '&role=dewan-solo' . (!empty($id_juri) ? '&name=' . $id_juri : '')) }}"
                        class="bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold px-4 py-2 rounded shadow transition-all text-sm no-underline inline-block">
                        ⬅ Kembali ke Dewan
                    </a>
                </div>
                <div class="flex items-center justify-center h-100 text-4xl lg:text-5xl text-white font-bold mb-2 lg:mb-0">
                    Arena Solo
                </div>
                <div class="lg:flex lg:justify-end h-full lg:me-5 hidden">
                    <div class="h-full flex items-center flex-wrap gap-2">
                        <img src="{{ asset('assets/Assets/IPSI.png') }}" class="w-12" alt="IPSI Logo">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Participant Banner -->
    <section class="px-3">
        <div class="container-fluid my-4">
            <div class="d-flex justify-content-between align-items-center fs-3">
                <div class="text-dark fw-bold">
                    {{ $perserta->name ?? '-' }}
                </div>
                <div class="text-primary text-end fs-3 fw-bold">
                    {{ $kontigen }}
                </div>
            </div>
        </div>
    </section>

    <!-- Score Tables Section -->
    <section class="px-4">
        <div class="rounded shadow-lg mb-4">
            {{-- Table Score Juri --}}
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="w-25 bg-primary text-white fs-3">Juri</th>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <th class="bg-primary text-white text-center fs-3">{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-4 fs-3">
                            Teknik Serangan dan Pertahanan
                        </td>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center" id="attack{{ $i }}">
                                0.00
                            </td>
                        @endfor
                    </tr>
                    <tr>
                        <td class="py-4 fs-3">
                            Ketegasan dan Keharmonisan
                        </td>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center" id="firmness{{ $i }}">
                                0.00
                            </td>
                        @endfor
                    </tr>
                    <tr>
                        <td class="py-4 fs-3">
                            Penjiwaan
                        </td>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center" id="soulfullness{{ $i }}">
                                0.00
                            </td>
                        @endfor
                    </tr>
                    <tr>
                        <td class="py-4 fs-3 fw-bold">
                            Total Nilai
                        </td>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center text-primary fw-bold" id="total{{ $i }}">
                                9.10
                            </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="container-fluid p-0 mb-3">
            {{-- Table Time dan Penalty --}}
            <div class="row w-100 h-100 m-0">
                <!-- Kolom Kiri: Time Performance, Juri Tersortir, Median -->
                <div class="col-lg shadow-lg h-100 pe-1 ps-0 mb-3 mb-lg-0">
                    <table class="w-100 table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-primary fs-4 p-3 text-white" style="width: 35%;">
                                    Time Performance
                                </td>
                                <td class="fs-4 text-center bg-white" colspan="2">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fs-4">
                                            <span id="menit-text" class="text-primary fw-bold">{{ $menit }}</span> Menit
                                            <span id="detik-text" class="text-primary fw-bold">{{ $detik }}</span> Detik
                                            <span id="ms-text" class="text-primary fw-bold">{{ $ms }}</span> Ms
                                        </span>
                                    </div>
                                    <input type="hidden" id="formatted-timer-val" value="{{ $formattedTimer }}">
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 fs-4 bg-primary text-white">
                                    Juri Tersortir
                                </td>
                                <td colspan="2">
                                    <table class="table table-bordered w-100 h-100 p-0 m-0">
                                        <tbody>
                                            <tr class="text-center font-bold">
                                                @for ($i = 1; $i <= $juriSeni; $i++)
                                                    <td id="urutNama{{ $i }}">
                                                        {{ $i }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr class="text-center font-bold">
                                                @for ($i = 1; $i <= $juriSeni; $i++)
                                                    <td id="urut{{ $i }}" class="text-primary">
                                                        9.10
                                                    </td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 fs-4 bg-primary text-white">
                                    Median
                                </td>
                                <td colspan="2" class="text-center text-primary fs-3 fw-bold py-3" id="medianscore">
                                    0.00
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Kolom Kanan: Pelanggaran Dari Dewan -->
                <div class="col-lg shadow me-lg-2 p-0">
                    <table class="table table-bordered w-100 h-100 mb-0">
                        <tbody>
                            <tr>
                                <td class="fs-5 w-75">
                                    Peserta keluar dari arena (10x10 m)
                                </td>
                                <td class="fs-5 text-center text-danger font-bold" id="penalti_rule_1">
                                    {{ $ruleScores['RULE_1'] > 0 ? '-' . number_format($ruleScores['RULE_1'], 2) : '0.00' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Senjata tidak sesuai deskripsi / ketentuan
                                </td>
                                <td class="fs-5 text-center text-danger font-bold" id="penalti_rule_2">
                                    {{ $ruleScores['RULE_2'] > 0 ? '-' . number_format($ruleScores['RULE_2'], 2) : '0.00' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Senjata jatuh keluar arena saat masih harus digunakan
                                </td>
                                <td class="fs-5 text-center text-danger font-bold" id="penalti_rule_3">
                                    {{ $ruleScores['RULE_3'] > 0 ? '-' . number_format($ruleScores['RULE_3'], 2) : '0.00' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Peserta diam dalam 1 gerakan > 5 detik
                                </td>
                                <td class="fs-5 text-center text-danger font-bold" id="penalti_rule_4">
                                    {{ $ruleScores['RULE_4'] > 0 ? '-' . number_format($ruleScores['RULE_4'], 2) : '0.00' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Pesilat melebihi batas waktu toleransi
                                </td>
                                <td class="fs-5 text-center text-danger font-bold" id="penalti_rule_5">
                                    {{ $ruleScores['RULE_5'] > 0 ? '-' . number_format($ruleScores['RULE_5'], 2) : '0.00' }}
                                </td>
                            </tr>
                            @foreach ($extraPenalties as $extra)
                                <tr>
                                    <td class="fs-5 w-75">
                                        {{ $extra['label'] }}
                                    </td>
                                    <td class="fs-5 text-center text-danger font-bold">
                                        -{{ number_format($extra['total_points'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-danger">
                                <td class="fs-5 w-75 fw-bold text-danger">
                                    Total Pengurangan Dewan
                                </td>
                                <td class="fs-5 text-center text-danger font-black" id="dewan_pinalti">
                                    {{ $totalPenaltiDewan > 0 ? '-' . number_format($totalPenaltiDewan, 2) : '0.00' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Final Score & Standard Deviation -->
        <div class="container-fluid p-0 mb-5">
            <div class="row">
                <div class="col">
                    <table class="table table-bordered border-primary mb-0">
                        <tbody>
                            <tr>
                                <td class="text-primary text-center w-50 fs-4 py-3 fw-semibold">
                                    Final Score
                                </td>
                                <td class="text-primary font-bold text-center w-50 fs-2 py-3" id="total_score">
                                    {{ $initScore }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-primary text-center w-50 fs-4 py-3 fw-semibold">
                                    Standard Deviation
                                </td>
                                <td class="text-primary font-bold text-center w-50 fs-4 py-3" id="deviationscore">
                                    {{ $initDeviation }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-none" name="{{ $juriSeni }}" id="jumlahJuri"></div>
    </section>

    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        const jumlahJuri = parseInt(document.getElementById("jumlahJuri").getAttribute('name')) || 4;
        const currentArena = "{{ $arena }}";
        const currentIdUser = "{{ $id_user }}";
        const currentIdJuri = "{{ $id_juri }}";
        const currentJadwalId = "{{ $group->id ?? '' }}";
        const currentPartai = "{{ $group->partai ?? '' }}";

        let isRedirecting = false;
        function redirectToDewan() {
            if (isRedirecting) return;
            isRedirecting = true;
            window.location.href = `/redirect?arena=${currentArena}&role=dewan-solo${currentIdJuri ? '&name=' + currentIdJuri : ''}`;
        }

        // Helper format angka presisi: minimal 2 angka dan maksimal 3 angka di belakang koma
        function formatScore(val) {
            if (val === null || val === undefined || isNaN(val)) return '0.00';
            let num = parseFloat(val);
            let formatted = num.toFixed(3);
            if (formatted.endsWith('0')) {
                formatted = formatted.slice(0, -1);
            }
            return formatted;
        }

        function findMedian(arr) {
            if (!arr || arr.length === 0) return 0;
            let sorted = [...arr].map(Number).sort((a, b) => a - b);
            const mid = Math.floor(sorted.length / 2);
            if (sorted.length % 2 === 0) {
                return (sorted[mid - 1] + sorted[mid]) / 2;
            } else {
                return sorted[mid];
            }
        }

        let reloadCount = 0;
        $(document).ready(function () {
            taketimeData();
            requestdata();
            setupWebSocketListeners();
        });

        // Polling timer dan status aktif pertandingan
        async function taketimeData() {
            $.ajax({
                url: `/take-timer-data/?arena=${currentArena}&id_user=${currentIdUser}`,
                method: 'GET',
                success: function (response) {
                    if (response) {
                        $('#splash').addClass('opacity-0');
                        setTimeout(function () {
                            $('#splash').addClass('hidden');
                        }, 500);

                        // Auto-redirect jika peserta atau jadwal aktif di arena berganti
                        if (response.active_id && String(response.active_id) !== String(currentIdUser)) {
                            redirectToDewan();
                            return;
                        }
                        if (response.jadwal_id && currentJadwalId && String(response.jadwal_id) !== String(currentJadwalId)) {
                            redirectToDewan();
                            return;
                        }
                        if (response.partai && currentPartai && String(response.partai) !== String(currentPartai)) {
                            redirectToDewan();
                            return;
                        }

                        if (response.time) {
                            let m = '00', s = '00', ms = '00', fmt = '00:00;00';
                            if (typeof response.time === 'object') {
                                m = response.time.menit || response.time.minute || '00';
                                s = response.time.detik || response.time.second || '00';
                                ms = response.time.ms || '00';
                                fmt = response.time.formatted || `${m}:${s};${ms}`;
                            } else if (typeof response.time === 'string') {
                                let parts = response.time.split(/[:;.]/);
                                m = parts[0] || '00';
                                s = parts[1] || '00';
                                ms = parts[2] || '00';
                                fmt = response.time;
                            }
                            $('#menit-text').text(m.toString().padStart(2, '0'));
                            $('#detik-text').text(s.toString().padStart(2, '0'));
                            $('#ms-text').text(ms.toString().padStart(2, '0'));
                            $('#formatted-timer-val').val(fmt);
                        }
                    }
                    // Tetap lanjutkan polling berkala untuk mendeteksi perubahan peserta/jadwal
                    setTimeout(taketimeData, 1500);
                },
                error: function() {
                    $('#splash').addClass('hidden');
                    setTimeout(taketimeData, 3000);
                }
            });
        }

        // Ambil data nilai seni Solo
        function requestdata() {
            $.ajax({
                url: `/call-data/?tipe=seni&kt=ganda&id=${currentIdUser}&arena=${currentArena}`,
                method: 'GET',
                success: function (response) {
                    if (!response) return;

                    // Auto-redirect jika endpoint mengindikasikan pergantian peserta
                    if (response.id_peserta && String(response.id_peserta) !== String(currentIdUser)) {
                        redirectToDewan();
                        return;
                    }

                    var all_juri = [];
                    var data_juri = [];

                    for (let i = 1; i <= jumlahJuri; i++) {
                        let att = parseFloat(response[`attack${i}`]) || 0;
                        let firm = parseFloat(response[`firmness${i}`]) || 0;
                        let soul = parseFloat(response[`soulfullness${i}`]) || 0;
                        let score = parseFloat((att + firm + soul + 9.1).toFixed(3));

                        data_juri.push({
                            name: `${i}`,
                            point: score
                        });
                        all_juri.push(score);

                        $(`#attack${i}`).text(formatScore(att, 2));
                        $(`#firmness${i}`).text(formatScore(firm, 2));
                        $(`#soulfullness${i}`).text(formatScore(soul, 2));
                        $(`#total${i}`).text(formatScore(score, 2));
                    }

                    // Urutkan juri
                    var sortedJuri = [...data_juri].sort((a, b) => a.point - b.point);
                    for (let i = 1; i <= jumlahJuri; i++) {
                        if (sortedJuri[i - 1]) {
                            $(`#urut${i}`).text(formatScore(sortedJuri[i - 1].point, 2));
                            $(`#urutNama${i}`).text(sortedJuri[i - 1].name);
                        }
                    }

                    // Median & Standar Deviasi
                    let median = findMedian(all_juri);
                    let dewan = parseFloat(response.dewan) || 0;
                    let total_score = Math.max(0, median - dewan);

                    let sum = all_juri.reduce((a, b) => a + b, 0);
                    let avg = all_juri.length > 0 ? (sum / all_juri.length) : 0;
                    let variance = all_juri.reduce((acc, val) => acc + Math.pow(val - avg, 2), 0) / (all_juri.length || 1);
                    let deviation = Math.sqrt(variance);

                    let formattedFinalScore = formatScore(total_score);
                    $('#medianscore').text(formatScore(median));
                    $('#dewan_pinalti').text(dewan > 0 ? ('-' + formatScore(dewan)) : '0.00');
                    $('#total_score').text(formattedFinalScore);
                    $('#deviationscore').text(deviation);

                    // Tampilkan rincian penalti bila ada di payload
                    if (response.penalties && Array.isArray(response.penalties)) {
                        updatePenaltiesDisplay(response.penalties);
                    }

                    // Sinkronisasi backup ke backend /rekapseni jika timer tersedia
                    let timerText = $('#formatted-timer-val').val() || `${$('#menit-text').text()}:${$('#detik-text').text()};${$('#ms-text').text()}`;
                    syncToJadwalBackup(formattedFinalScore, deviation, timerText);
                }
            });
        }

        function updatePenaltiesDisplay(penalties) {
            const aliasMap = {
                'RULE_1': ['PESERTAKELUARDARIARENA', 'PESERTAKELUARDARI10X10METERARENA'],
                'RULE_2': ['SENJATATIDAKSESUAIDENGANDESKRIPSI', 'SENJATATIDAKSESUAI'],
                'RULE_3': ['SENJATAJATUHKELUARARENAWALAUPUNTIMMASIHDITUNTUTUNTUKMENGGUNAKANNYA'],
                'RULE_4': ['PESERTABERHENTIDALAM1GERAKANLEBIHDARI5DETIK'],
                'RULE_5': ['PESILATMELEBIHIBATASWAKTUTOLERANSI']
            };

            for (const [rKey, aliases] of Object.entries(aliasMap)) {
                let sum = 0;
                penalties.forEach(p => {
                    let norm = (p.normalized_key || '').toUpperCase();
                    if (aliases.includes(norm)) {
                        sum += parseFloat(p.total_points) || 0;
                    }
                });
                let elId = '#penalti_' + rKey.toLowerCase();
                if (sum > 0) {
                    $(elId).text('-' + formatScore(sum, 2));
                }
            }
        }

        // Panggilan fallback persisten ke /rekapseni
        function syncToJadwalBackup(score, deviation, time) {
            $.ajax({
                url: '/rekapseni',
                method: 'GET',
                data: {
                    arena: currentArena,
                    id_user: currentIdUser,
                    score: score,
                    deviation: deviation,
                    time: time
                },
                success: function(res) {
                    console.log('Jadwal synced successfully via fallback:', res);
                }
            });
        }

        // WebSocket Listener untuk update real-time dan auto-redirect
        function setupWebSocketListeners() {
            if (typeof window.Echo === 'undefined') return;

            window.Echo.channel('solo-channel')
                .listen('SoloEvent', function (e) {
                    let payload = (e && e.message) ? e.message : e;
                    if (!payload || String(payload.arena) !== String(currentArena)) return;

                    // Jika jadwal/peserta berubah, auto-redirect kembali ke UI Dewan
                    if (payload.tipe === 'update' && payload.data && payload.data.id && String(payload.data.id) !== String(currentIdUser)) {
                        redirectToDewan();
                        return;
                    }

                    if (payload.tipe === 'data' && payload.response) {
                        if (payload.response.id_peserta && String(payload.response.id_peserta) !== String(currentIdUser)) {
                            redirectToDewan();
                            return;
                        }
                        requestdata();
                    }
                });

            window.Echo.channel('indicator-channel')
                .listen('.indicator.triggered', function (e) {
                    let payload = (e && e.message) ? e.message : e;
                    if (!payload || String(payload.arena) !== String(currentArena)) return;

                    let statusObj = payload.status || (payload.data && payload.data.status) || payload;
                    let targetBiru = statusObj.biru || payload.id_biru || payload.id_perserta;
                    if (targetBiru && String(targetBiru) !== String(currentIdUser)) {
                        redirectToDewan();
                    }
                });
        }
    </script>
</body>

</html>