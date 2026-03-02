<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Prestasi Tunggal</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
</head>

<body class="bg-gray-100">
    <!-- Splash Screen -->
    <div id="splash" class="fixed inset-0 z-50 bg-slate-100 flex justify-center items-center transition-all duration-500">
        <div class="text-center">
            <div class="inline bg-gradient-to-br from-blue-700 to-blue-300 text-3xl bg-clip-text text-transparent font-bold animate-pulse">
                Sedang Mengambil Data Pertandingan...
            </div>
            <p class="text-gray-500 mt-2">Mohon tunggu sebentar</p>
        </div>
    </div>
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
            $setting->juri_5,
            $setting->juri_6,
        ];

        function getSeniScore($id_peserta, $arena, $partai, $juri_id, $keterangan) {
            return score::where('id_perserta', $id_peserta)
                ->where('arena', $arena)
                ->where('partai', $partai)
                ->where('id_juri', $juri_id)
                ->where('keterangan', $keterangan)
                ->value('score') ?? 0;
        }

        function getDewanMinus($id_peserta, $arena, $partai) {
             return score::where('id_perserta', $id_peserta)
                ->where('arena', $arena)
                ->where('partai', $partai)
                ->where('status', 'seni_minus')
                ->get();
        }
    @endphp

    <div class="m-5">
        <!-- Header -->
        <header class="text-center mb-10">
            <h1 class="text-4xl font-bold uppercase text-blue-900">{{ $arenaNama[0] ?? 'REKAP SENI TUNGGAL' }}</h1>
            <h2 class="text-2xl text-blue-700">{{ $arenaNama[1] ?? 'PRESTASI' }}</h2>
            <div class="flex justify-center gap-4 mt-2">
                <img src="{{ asset('assets/Assets/IPSI.png') }}" class="w-16" alt="IPSI">
            </div>
        </header>

        <!-- Participant Info Section (Mirroring rekapTanding style) -->
        <section class="mb-10">
            <div class="grid grid-cols-12 gap-4">
                <!-- Blue Participant -->
                <div class="col-span-1">
                    <div class="bg-gradient-to-b from-blue-700 to-blue-500 rounded shadow-xl h-24 flex justify-center items-center">
                        <div class="text-white text-4xl font-bold">
                            {{ number_format($jadwal->score_biru, 2) }}
                        </div>
                    </div>
                </div>
                <div class="col-span-5 px-5">
                    <div class="bg-blue-500 px-5 py-2 mb-2 shadow-lg">
                        <div class="text-white text-end uppercase text-2xl font-bold">{{ $pesertabiru->name }}</div>
                    </div>
                    <div class="bg-blue-700 px-5 py-1 inline-block shadow-lg rounded-r-lg">
                        <div class="text-white font-semibold">{{ $kontigenbiru }}</div>
                    </div>
                </div>

                <!-- Red Participant -->
                <div class="col-span-5 px-5 text-right">
                    <div class="bg-red-500 px-5 py-2 mb-2 shadow-lg ">
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
                        <div class="text-white text-4xl font-bold">
                            {{ number_format($jadwal->score_merah, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                                <td class="font-semibold">Next</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSeniScore($pesertabiru->id, $arena, $setting->partai, $jid, 'next'); @endphp
                                    <td class="text-center">{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="font-semibold">Flwo</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSeniScore($pesertabiru->id, $arena, $setting->partai, $jid, 'flwo'); @endphp
                                    <td class="text-center">{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="bg-blue-50 font-bold">
                                <td>TOTAL</td>
                                @foreach($juri_ids as $jid)
                                    @php 
                                        $n = 9.9 - (getSeniScore($pesertabiru->id, $arena, $setting->partai, $jid, 'next') / 100);
                                        $f = getSeniScore($pesertabiru->id, $arena, $setting->partai, $jid, 'flwo');
                                    @endphp
                                    <td class="text-center text-blue-700">{{ number_format($n + $f, 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-blue-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Score Monitor</div>
                        <div class="text-2xl font-bold">{{ number_format($jadwal->score_biru, 2) }}</div>
                    </div>
                    <div class="bg-blue-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Deviasi</div>
                        <div class="text-2xl font-bold">{{ number_format($jadwal->deviasi_biru, 4) }}</div>
                    </div>
                    <div class="bg-blue-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Timer</div>
                        <div class="text-2xl font-bold">{{ $jadwal->timer_biru ?? '00:00' }}</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-md">
                    <h3 class="text-lg font-bold mb-2 text-red-600">Pengurangan Dewan (BIRU)</h3>
                    @php $minusB = getDewanMinus($pesertabiru->id, $arena, $setting->partai); @endphp
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
                                <td class="font-semibold text-left">Next</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSeniScore($pesertamerah->id, $arena, $setting->partai, $jid, 'next'); @endphp
                                    <td>{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="text-center">
                                <td class="font-semibold text-left">Flwo</td>
                                @foreach($juri_ids as $jid)
                                    @php $val = getSeniScore($pesertamerah->id, $arena, $setting->partai, $jid, 'flwo'); @endphp
                                    <td>{{ number_format($val, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr class="bg-red-50 font-bold text-center">
                                <td class="text-left">TOTAL</td>
                                @foreach($juri_ids as $jid)
                                    @php 
                                        $n = 9.9 - (getSeniScore($pesertamerah->id, $arena, $setting->partai, $jid, 'next') / 100);
                                        $f = getSeniScore($pesertamerah->id, $arena, $setting->partai, $jid, 'flwo');
                                    @endphp
                                    <td class="text-red-700">{{ number_format($n + $f, 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-red-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Timer</div>
                        <div class="text-2xl font-bold">{{ $jadwal->timer_merah ?? '00:00' }}</div>
                    </div>
                    <div class="bg-red-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Deviasi</div>
                        <div class="text-2xl font-bold">{{ number_format($jadwal->deviasi_merah, 4) }}</div>
                    </div>
                    <div class="bg-red-600 text-white p-3 rounded shadow text-center">
                        <div class="text-xs uppercase opacity-75">Score Monitor</div>
                        <div class="text-2xl font-bold">{{ number_format($jadwal->score_merah, 2) }}</div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-md text-left">
                    <h3 class="text-lg font-bold mb-2 text-red-600 text-right">Pengurangan Dewan (MERAH)</h3>
                    @php $minusM = getDewanMinus($pesertamerah->id, $arena, $setting->partai); @endphp
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
            <a href="{{ url('login-juri') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-blue-700 transition-colors no-underline">
                 Kembali ke Login
            </a>
        </footer> -->
    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        let reloadCount = 0;
        $(document).ready(function () {
            taketimeData();
        });

        function taketimeData() {
            $.ajax({
                url: `/take-timer-data/?arena={{ $arena }}`,
                method: 'GET',
                success: function (response) {
                    console.log('Take Timer Data:', response);
                    if (response.isDone || reloadCount > 10) {
                        $('#splash').addClass('opacity-0');
                        setTimeout(() => {
                            $('#splash').addClass('hidden');
                        }, 500);
                        
                        window.location.reload();
                        // We can reload if needed or just keep current data if already loaded via PHP
                        // But since this is a rekap page loaded with PHP, 
                        // this takeTimer is mostly to ensure we don't show the page 
                        // until the match is officially 'finished' in settings.
                    } else {
                        setTimeout(taketimeData, 1000);
                        reloadCount++;
                    }
                },
                error: function() {
                    // Fail gracefully after some attempts
                    if (reloadCount > 10) {
                         $('#splash').addClass('opacity-0');
                         setTimeout(() => $('#splash').addClass('hidden'), 500);
                    } else {
                         setTimeout(taketimeData, 2000);
                         reloadCount++;
                    }
                }
            });
        }
    </script>
</body>

</html>
