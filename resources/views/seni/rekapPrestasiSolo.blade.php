<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Prestasi Solo</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
</head>

<body class="bg-gray-100">
    <!-- Splash Screen -->
    <!-- <div id="splash" class="fixed inset-0 z-50 bg-slate-100 flex justify-center items-center transition-all duration-500">
        <div class="text-center">
            <div class="inline bg-gradient-to-br from-purple-700 to-purple-300 text-3xl bg-clip-text text-transparent font-bold animate-pulse">
                Sedang Mengambil Data Pertandingan Solo...
            </div>
            <p class="text-gray-500 mt-2">Mohon tunggu sebentar</p>
        </div>
    </div> -->
    @php
        use App\score;
        use App\Setting;
        use App\KontigenModel;
        use App\PersertaModel;
        use App\jadwal_group;
        
        $setting = Setting::where('arena', $arena)->first();
        $jadwal = jadwal_group::where('id', $setting->jadwal)->first();
        
        $pesertabiru = PersertaModel::where('id', $jadwal->biru)->first();
        $pesertamerah = PersertaModel::where('id', $jadwal->merah)->first();
        
        $kontigenbiru = KontigenModel::where('id', $pesertabiru->id_kontigen)->first()->kontigen ?? '';
        $kontigenmerah = KontigenModel::where('id', $pesertamerah->id_kontigen)->first()->kontigen ?? '';
        
        $arenaNama = explode('||', $setting->judul);

        $juri_ids = [
            $setting->juri_1,
            $setting->juri_2,
            $setting->juri_3,
            $setting->juri_4,
        ];
        // Filter out null juris if any
        $juri_ids = array_filter($juri_ids);

        if (!function_exists('getSoloScore')) {
            function getSoloScore($id_peserta, $arena, $partai, $juri_id, $keterangan) {
                return score::where('id_perserta', $id_peserta)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('id_juri', $juri_id)
                    ->where('keterangan', $keterangan)
                    ->value('score') ?? 0;
            }
        }

        if (!function_exists('getDewanMinusSolo')) {
            function getDewanMinusSolo($id_peserta, $arena, $partai) {
                 return score::where('id_perserta', $id_peserta)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('status', 'seni_minus')
                    ->get();
            }
        }
    @endphp

    <div class="m-5">
        <!-- Header -->
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold uppercase text-purple-900">{{ $arenaNama[0] ?? 'REKAP SENI SOLO' }}</h1>
            <h2 class="text-2xl text-purple-700">{{ $arenaNama[1] ?? 'PRESTASI' }}</h2>
            <div class="mt-3 flex justify-center gap-4 text-xl font-bold text-gray-700">
                <span class="bg-gray-200 px-4 py-1 rounded shadow">Partai: {{ $setting->partai }}</span>
                <!-- <span class="bg-gray-200 px-4 py-1 rounded shadow">Kelas: {{ $jadwal->kelas }}</span> -->
            </div>
            <div class="flex justify-center gap-4 mt-2">
                <img src="{{ asset('assets/Assets/IPSI.png') }}" class="w-16" alt="IPSI">
            </div>
        </header>

        @if($jadwal->pemenang && $jadwal->pemenang != 'N/a')
            @php
                $winner = $jadwal->pemenang == $pesertabiru->id ? $pesertabiru : $pesertamerah;
                $winnerColorText = $jadwal->pemenang == $pesertabiru->id ? 'Biru' : 'Merah';
                $winnerBg = $jadwal->pemenang == $pesertabiru->id ? 'from-blue-700 to-blue-500 shadow-blue-500/50' : 'from-red-700 to-red-500 shadow-red-500/50';
            @endphp
            <div id="winnerBanner" class="flex justify-center mb-10">
                <div class="bg-gradient-to-r {{ $winnerBg }} text-white font-bold py-5 px-16 rounded-full shadow-2xl text-3xl flex items-center gap-4 border-4 border-white animate-pulse">
                    <span>🏆 PEMENANG:</span>
                    <span class="uppercase tracking-wide text-yellow-300">{{ $winner->name }}</span>
                    <span>(SUDUT {{ strtoupper($winnerColorText) }})</span>
                </div>
            </div>
        @endif

        <!-- Participant Info Section -->
        <section class="mb-10">
            <div class="grid grid-cols-12 gap-4">
                <!-- Blue Participant -->
                <div class="col-span-1">
                    <div class="bg-gradient-to-b from-blue-700 to-blue-500 rounded shadow-xl h-24 flex justify-center items-center">
                        <div class="text-white text-4xl font-bold" id="score_biru_top">
                            {{ round($jadwal->score_biru, 3) }}
                        </div>
                    </div>
                </div>
                <div class="col-span-5 px-5">
                    <div class="bg-blue-500 px-5 py-2 mb-2 shadow-lg">
                        <div class="text-white uppercase text-2xl font-bold">{{ $pesertabiru->name }}</div>
                    </div>
                    <div class="bg-blue-700 px-5 py-1 inline-block shadow-lg rounded-r-lg">
                        <div class="text-white font-semibold">{{ $kontigenbiru }}</div>
                    </div>
                </div>

                <!-- Red Participant -->
                <div class="col-span-5 px-5 text-right">
                    <div class="bg-red-500 px-5 py-2 mb-2 shadow-lg">
                        <div class="text-white text-end uppercase text-2xl font-bold">{{ $pesertamerah->name }}</div>
                    </div>
                    <div class="w-full flex justify-end">   
                        <div class="bg-red-700 px-5 py-1 inline-block shadow-lg rounded-l-lg">
                            <div class="text-white font-semibold">{{ $kontigenmerah }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="bg-gradient-to-b from-red-700 to-red-500 rounded shadow-xl h-24 flex justify-center items-center">
                        <div class="text-white text-4xl font-bold" id="score_merah_top">
                            {{ round($jadwal->score_merah, 3) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Center Button Tentukan Pemenang (Dewan Only) -->
        <div id="btnTentukanPemenangWrap" class="flex justify-center mb-8 hidden">
             <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-full shadow-lg text-xl transition-all" data-bs-toggle="modal" data-bs-target="#winnerModal">🏆 Tentukan Pemenang</button>
        </div>

        <!-- Detailed Scores Section -->
        <div class="grid grid-cols-12 gap-8">
            <!-- Left Side (Blue) -->
            <div class="col-span-6 space-y-6">
                <div class="bg-white p-4 rounded-xl shadow-md border-l-8 border-blue-600">
                    <h3 class="text-xl font-bold mb-3 text-blue-800 border-b pb-2">Nilai Juri (BIRU)</h3>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-blue-100">
                            <tr class="text-center">
                                <th>Keterangan</th>
                                @foreach($juri_ids as $index => $jid)
                                    <th>Juri {{ $index + 1 }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-semibold text-sm">Attack</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSoloScore($pesertabiru->id, $arena, $setting->partai, $jid, 'attack'); @endphp
                                    <td class="text-center">{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="font-semibold text-sm">Firmness</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSoloScore($pesertabiru->id, $arena, $setting->partai, $jid, 'firmness'); @endphp
                                    <td class="text-center">{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="font-semibold text-sm">Soulfullness</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSoloScore($pesertabiru->id, $arena, $setting->partai, $jid, 'soulfullness'); @endphp
                                    <td class="text-center">{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="bg-blue-50 font-bold">
                                <td>TOTAL</td>
                                @foreach($juri_ids as $jid)
                                    @php 
                                        $a = getSoloScore($pesertabiru->id, $arena, $setting->partai, $jid, 'attack');
                                        $f = getSoloScore($pesertabiru->id, $arena, $setting->partai, $jid, 'firmness');
                                        $s = getSoloScore($pesertabiru->id, $arena, $setting->partai, $jid, 'soulfullness');
                                        // Based on rekapSolo.blade.php line 351, base score is 9.1
                                        $total = $a + $f + $s + 9.1;
                                    @endphp
                                    <td class="text-center text-blue-700">{{ number_format($total, 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-blue-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Score Monitor</div>
                        <div class="text-2xl font-bold" id="score_biru_bot">{{ round($jadwal->score_biru, 3) }}</div>
                    </div>
                    <div class="bg-blue-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Deviasi</div>
                        <div class="text-lg font-bold overflow-hidden" id="deviasi_biru">{{ $jadwal->deviasi_biru }}</div>
                    </div>
                    <div class="bg-blue-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Timer</div>
                        <div class="text-2xl font-bold" id="timer_biru">{{ $jadwal->timer_biru ?? '00:00' }}</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-md">
                    <h3 class="text-lg font-bold mb-2 text-red-600">Pengurangan Dewan (BIRU)</h3>
                    @php $minusB = getDewanMinusSolo($pesertabiru->id, $arena, $setting->partai); @endphp
                    @if($minusB->count() > 0)
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($minusB as $m)
                                <li class="text-gray-700">
                                    <span class="font-semibold">{{ $m->keterangan }}:</span> 
                                    <span class="text-red-500 font-bold">-{{ number_format($m->score, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-400 italic text-sm">Tidak ada pengurangan</p>
                    @endif
                </div>
            </div>

            <!-- Right Side (Red) -->
            <div class="col-span-6 space-y-6 text-right">
                <div class="bg-white p-4 rounded-xl shadow-md border-r-8 border-red-600 text-left">
                    <h3 class="text-xl font-bold mb-3 text-red-800 border-b pb-2 text-right">Nilai Juri (MERAH)</h3>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-red-100 text-center">
                            <tr>
                                <th>Keterangan</th>
                                @foreach($juri_ids as $index => $jid)
                                    <th>Juri {{ $index + 1 }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td class="font-semibold text-sm text-left">Attack</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSoloScore($pesertamerah->id, $arena, $setting->partai, $jid, 'attack'); @endphp
                                    <td>{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="text-center">
                                <td class="font-semibold text-sm text-left">Firmness</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSoloScore($pesertamerah->id, $arena, $setting->partai, $jid, 'firmness'); @endphp
                                    <td>{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="text-center">
                                <td class="font-semibold text-sm text-left">Soulfullness</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSoloScore($pesertamerah->id, $arena, $setting->partai, $jid, 'soulfullness'); @endphp
                                    <td>{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="bg-red-50 font-bold text-center">
                                <td class="text-left">TOTAL</td>
                                @foreach($juri_ids as $jid)
                                    @php 
                                        $a = getSoloScore($pesertamerah->id, $arena, $setting->partai, $jid, 'attack');
                                        $f = getSoloScore($pesertamerah->id, $arena, $setting->partai, $jid, 'firmness');
                                        $s = getSoloScore($pesertamerah->id, $arena, $setting->partai, $jid, 'soulfullness');
                                        $total = $a + $f + $s + 9.1;
                                    @endphp
                                    <td class="text-red-700">{{ number_format($total, 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-red-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Timer</div>
                        <div class="text-2xl font-bold" id="timer_merah">{{ $jadwal->timer_merah ?? '00:00' }}</div>
                    </div>
                    <div class="bg-red-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Deviasi</div>
                        <div class="text-lg font-bold overflow-hidden" id="deviasi_merah">{{ $jadwal->deviasi_merah }}</div>
                    </div>
                    <div class="bg-red-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Score Monitor</div>
                        <div class="text-2xl font-bold" id="score_merah_bot">{{ round($jadwal->score_merah, 3) }}</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-md text-left">
                    <h3 class="text-lg font-bold mb-2 text-red-600 text-right">Pengurangan Dewan (MERAH)</h3>
                    @php $minusM = getDewanMinusSolo($pesertamerah->id, $arena, $setting->partai); @endphp
                    @if($minusM->count() > 0)
                        <ul class="list-none text-right space-y-1">
                            @foreach($minusM as $m)
                                <li class="text-gray-700">
                                    <span class="font-semibold">{{ $m->keterangan }}:</span> 
                                    <span class="text-red-500 font-bold">-{{ number_format($m->score, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-400 italic text-sm text-right">Tidak ada pengurangan</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <!-- <footer class="mt-20 flex justify-center gap-6">
            <button onclick="window.print()" class="bg-gray-800 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-gray-700 transition-colors">
                Print Rekap
            </button>
            <a href="{{ url('login-juri') }}" class="bg-purple-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-purple-700 transition-colors no-underline">
                 Kembali ke Login
            </a>
        </footer> -->

        <!-- Winner Modal (Bootstrap) — Premium Redesign -->
        <style>
            .winner-modal-header {
                background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            }
            .card-blue {
                background: linear-gradient(145deg, #1d4ed8, #2563eb);
                border: 2px solid rgba(147, 197, 253, 0.3);
                box-shadow: 0 8px 32px rgba(37, 99, 235, 0.35);
                transition: all 0.25s ease;
            }
            .card-blue:hover {
                transform: translateY(-4px) scale(1.02);
                box-shadow: 0 16px 48px rgba(37, 99, 235, 0.5);
            }
            .card-red {
                background: linear-gradient(145deg, #b91c1c, #dc2626);
                border: 2px solid rgba(252, 165, 165, 0.3);
                box-shadow: 0 8px 32px rgba(220, 38, 38, 0.35);
                transition: all 0.25s ease;
            }
            .card-red:hover {
                transform: translateY(-4px) scale(1.02);
                box-shadow: 0 16px 48px rgba(220, 38, 38, 0.5);
            }
            .score-badge {
                background: rgba(255,255,255,0.2);
                border: 1px solid rgba(255,255,255,0.35);
                backdrop-filter: blur(4px);
            }
            #winnerModal .modal-content,
            #confirmModal .modal-content {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
            }
            .confirm-modal-icon {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                margin: 0 auto 1rem;
            }
        </style>

        <div class="modal fade" id="winnerModal" aria-hidden="true" aria-labelledby="winnerModalLabel" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 bg-transparent">
                    <div class="rounded-3xl overflow-hidden shadow-2xl">
                        <!-- Header Gradient -->
                        <div class="winner-modal-header px-10 pt-8 pb-6 text-white text-center relative">
                            <button type="button" class="btn-close btn-close-white absolute top-5 right-5 opacity-80" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-4xl mb-2">🏆</div>
                            <h2 class="text-3xl font-bold tracking-tight" id="winnerModalLabel">Tentukan Pemenang</h2>
                            <p class="text-blue-200 text-sm mt-1">Pilih peserta yang memenangkan pertandingan ini</p>
                        </div>

                        <!-- Body -->
                        <div class="bg-gray-50 px-8 py-8">
                            <div class="flex gap-5">
                                <!-- Blue Card -->
                                <button class="card-blue w-1/2 text-white rounded-2xl p-7 text-center cursor-pointer border-0"
                                    data-bs-target="#confirmModal" data-bs-toggle="modal"
                                    onclick="prepareConfirm('{{$pesertabiru->id}}', '{{ addslashes($pesertabiru->name) }}', '{{$pesertamerah->id}}', 'BIRU', '#1d4ed8')">
                                    <div class="text-xs font-bold uppercase tracking-widest opacity-70 mb-3">SUDUT BIRU</div>
                                    <div class="text-2xl font-extrabold mb-1 leading-tight">{{ $pesertabiru->name }}</div>
                                    <div class="text-sm opacity-75 mb-5">{{ $kontigenbiru }}</div>
                                    <div class="score-badge rounded-xl px-4 py-3">
                                        <div class="text-xs uppercase opacity-70 mb-1 tracking-widest">Score Akhir</div>
                                        <div class="text-4xl font-black tabular-nums" id="score_biru_modal">{{ round($jadwal->score_biru, 3) }}</div>
                                    </div>
                                    <div class="mt-5 text-sm font-semibold opacity-80 flex items-center justify-center gap-2">
                                        <span>Pilih sebagai Pemenang</span>
                                        <span>→</span>
                                    </div>
                                </button>

                                <!-- VS Divider -->
                                <div class="flex flex-col items-center justify-center gap-2 px-2 flex-shrink-0">
                                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest">VS</div>
                                    <div class="w-px flex-1 bg-gray-300"></div>
                                    <div class="text-gray-400 text-xs font-bold uppercase tracking-widest">VS</div>
                                </div>

                                <!-- Red Card -->
                                <button class="card-red w-1/2 text-white rounded-2xl p-7 text-center cursor-pointer border-0"
                                    data-bs-target="#confirmModal" data-bs-toggle="modal"
                                    onclick="prepareConfirm('{{$pesertamerah->id}}', '{{ addslashes($pesertamerah->name) }}', '{{$pesertabiru->id}}', 'MERAH', '#b91c1c')">
                                    <div class="text-xs font-bold uppercase tracking-widest opacity-70 mb-3">SUDUT MERAH</div>
                                    <div class="text-2xl font-extrabold mb-1 leading-tight">{{ $pesertamerah->name }}</div>
                                    <div class="text-sm opacity-75 mb-5">{{ $kontigenmerah }}</div>
                                    <div class="score-badge rounded-xl px-4 py-3">
                                        <div class="text-xs uppercase opacity-70 mb-1 tracking-widest">Score Akhir</div>
                                        <div class="text-4xl font-black tabular-nums" id="score_merah_modal">{{ round($jadwal->score_merah, 3) }}</div>
                                    </div>
                                    <div class="mt-5 text-sm font-semibold opacity-80 flex items-center justify-center gap-2">
                                        <span>Pilih sebagai Pemenang</span>
                                        <span>→</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-100 px-8 py-4 text-center border-t border-gray-200">
                            <button type="button" data-bs-dismiss="modal" class="text-gray-500 hover:text-gray-800 text-sm font-medium transition-colors">
                                ✕ Batal, tutup tanpa memilih pemenang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal — Premium -->
        <div class="modal fade" id="confirmModal" aria-hidden="true" aria-labelledby="confirmModalLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 bg-transparent">
                    <div class="bg-white rounded-3xl overflow-hidden shadow-2xl">
                        <!-- Top accent bar -->
                        <div id="confirmAccentBar" class="h-2 w-full"></div>

                        <div class="px-8 py-8 text-center">
                            <!-- Icon -->
                            <div class="confirm-modal-icon mb-4" id="confirmIconWrap">
                                <span id="confirmIcon">🏆</span>
                            </div>

                            <h3 class="text-2xl font-bold mb-2 text-gray-800" id="confirmModalLabel">Konfirmasi Pemenang</h3>
                            <p class="text-base text-gray-500 mb-2">Anda akan menetapkan pemenang:</p>
                            <div id="confirmMessage" class="text-xl font-bold mb-8 text-gray-800">...</div>

                            <form id="confirmForm" action="{{route('rekap.seni.data')}}" method="post">
                                @csrf
                                <input type="hidden" value="{{$arena}}" name="arena">
                                <input type="hidden" id="confirm_id_user" name="id_user">
                                <input type="hidden" value="solo" name="kategori">
                                <input type="hidden" id="confirm_menang" name="menang">
                                <input type="hidden" id="confirm_kalah" name="kalah">
                                <input type="hidden" value="{{ request()->get('name') }}" name="name">
                                <div class="flex justify-center gap-3">
                                    <button type="button"
                                        class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition-all border border-gray-200"
                                        data-bs-target="#winnerModal" data-bs-toggle="modal">
                                        ← Kembali
                                    </button>
                                    <button type="submit" id="confirmSubmitBtn"
                                        class="flex items-center gap-2 text-white font-bold px-8 py-2.5 rounded-xl transition-all shadow-lg hover:opacity-90">
                                        ✓ Yakin, Tetapkan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script>
        function prepareConfirm(winnerId, winnerName, loserId, winnerColor, accentColor) {
            document.getElementById('confirm_id_user').value = winnerId;
            document.getElementById('confirm_menang').value = winnerId;
            document.getElementById('confirm_kalah').value = loserId;
            
            const isBlue = winnerColor === 'BIRU';
            const color = accentColor || (isBlue ? '#1d4ed8' : '#b91c1c');
            const colorText = isBlue ? 'text-blue-700' : 'text-red-700';
            const icon = isBlue ? '🔵' : '🔴';

            // Update accent bar
            document.getElementById('confirmAccentBar').style.background = isBlue
                ? 'linear-gradient(90deg, #1e40af, #2563eb)'
                : 'linear-gradient(90deg, #991b1b, #dc2626)';

            // Update icon
            document.getElementById('confirmIconWrap').style.background = isBlue ? '#dbeafe' : '#fee2e2';
            document.getElementById('confirmIcon').textContent = icon;

            // Update message
            document.getElementById('confirmMessage').innerHTML =
                `<span class="font-extrabold ${colorText}">${winnerName}</span><br><span class="text-gray-500 text-base font-normal">(Sudut ${winnerColor})</span>`;

            // Update submit button color
            const btn = document.getElementById('confirmSubmitBtn');
            btn.style.background = isBlue
                ? 'linear-gradient(135deg, #1e40af, #2563eb)'
                : 'linear-gradient(135deg, #991b1b, #dc2626)';
        }
        let reloadCount = 0;
        const currentJadwalId = "{{ $jadwal->id }}";
        const currentPartai = "{{ $setting->partai }}";
        const currentActiveId = "{{ $setting->biru }}";

        $(document).ready(function () {
            checkStatus();
        });

        function checkStatus() {
            $('#splash').addClass('hidden');

            // Show Tentukan Pemenang button only for dewan
            const params = new URLSearchParams(window.location.search);
            const isDewan = params.get('isDewan');
            console.log(isDewan);
            if (isDewan) {
                $('#btnTentukanPemenangWrap').removeClass('hidden');
            }

            $.ajax({
                url: `/take-timer-data/?arena={{ $arena }}`,
                method: 'GET',
                success: function (response) {
                    const params = new URLSearchParams(window.location.search);
                    const isDewan = params.get('isDewan');
                    const nameParam = params.has('name') ? `&name=${params.get('name')}` : '';

                    // Detect if match has changed (New Jadwal, New Partai, or New Active Participant)
                    if (response.jadwal_id && (response.jadwal_id != currentJadwalId || response.partai != currentPartai || response.active_id != currentActiveId)) {
                         if (isDewan) {
                            window.location.href = `redirect?arena={{ $arena }}&role=dewan-solo${nameParam}`;
                        } else {
                            window.location.href = `redirect?arena={{ $arena }}&role=score`;
                        }
                        return;
                    }

                    if (response.isDone === false) {
                        $('#timer_biru').text(response.timer_biru);
                        $('#timer_merah').text(response.timer_merah);
                        
                        $('#score_biru_top').text(parseFloat(parseFloat(response.score_biru).toFixed(3)));
                        $('#score_biru_bot').text(parseFloat(parseFloat(response.score_biru).toFixed(3)));
                        $('#score_biru_modal').text(parseFloat(parseFloat(response.score_biru).toFixed(3)));
                        $('#deviasi_biru').text(response.deviasi_biru);

                        $('#score_merah_top').text(parseFloat(parseFloat(response.score_merah).toFixed(3)));
                        $('#score_merah_bot').text(parseFloat(parseFloat(response.score_merah).toFixed(3)));
                        $('#score_merah_modal').text(parseFloat(parseFloat(response.score_merah).toFixed(3)));
                        $('#deviasi_merah').text(response.deviasi_merah);

                        if (response.pemenang && response.pemenang !== 'N/a' && !document.getElementById('winnerBanner')) {
                            window.location.reload();
                            return;
                        }

                        if (response.status === 'pending' || response.status === 'proses') {
                            if (isDewan) {
                                window.location.href = `redirect?arena={{ $arena }}&role=dewan-solo${nameParam}`;
                            } else {
                                window.location.href = `redirect?arena={{ $arena }}&role=score`;
                            }
                        } else {
                            setTimeout(checkStatus, 1000);
                        }
                    } else {
                        $('#timer_biru').text(response.timer_biru);
                        $('#timer_merah').text(response.timer_merah);
                        
                        $('#score_biru_top').text(parseFloat(parseFloat(response.score_biru).toFixed(3)));
                        $('#score_biru_bot').text(parseFloat(parseFloat(response.score_biru).toFixed(3)));
                        $('#score_biru_modal').text(parseFloat(parseFloat(response.score_biru).toFixed(3)));
                        $('#deviasi_biru').text(response.deviasi_biru);

                        $('#score_merah_top').text(parseFloat(parseFloat(response.score_merah).toFixed(3)));
                        $('#score_merah_bot').text(parseFloat(parseFloat(response.score_merah).toFixed(3)));
                        $('#score_merah_modal').text(parseFloat(parseFloat(response.score_merah).toFixed(3)));
                        $('#deviasi_merah').text(response.deviasi_merah);

                        if (response.pemenang && response.pemenang !== 'N/a' && !document.getElementById('winnerBanner')) {
                            window.location.reload();
                            return;
                        }

                        setTimeout(checkStatus, 1000);
                    }
                },
                error: function() {
                    setTimeout(checkStatus, 2000);
                }
            });
        }
    </script>
</body>

</html>
