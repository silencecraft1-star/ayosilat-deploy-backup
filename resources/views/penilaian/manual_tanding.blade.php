<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Tanding Input</title>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\arena;
        use App\jadwal_group;

        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $id_arena = $arena;
        $id_juri = $id_juri ?? 'Manual';

        $tim_merahs = PersertaModel::where('id', $setting->merah)->first();
        $tim_birus = PersertaModel::where('id', $setting->biru)->first();
        $tim_biru = $tim_birus->id;
        $tim_merah = $tim_merahs->id;

        $kontigenBiru = KontigenModel::where('id', $tim_birus->id_kontigen)->first();
        $kontigenMerah = KontigenModel::where('id', $tim_merahs->id_kontigen)->first();

        $arenaData = arena::where('id', $arena)->first();
        $partai = $setting->partai;
        $jadwalData = jadwal_group::where('id', $setting->jadwal)->first();

        $settingData = Setting::where('keterangan', 'admin-setting')->first();
        if ($settingData) {
            $imgData1 = $settingData->babak ?? '';
            $imgData2 = $settingData->partai ?? '';
        }
    @endphp
</head>

<body class="bg-gray-100">
    <!-- Header Section -->
    <nav class="w-full bg-blue-800 shadow-lg py-3 px-6 mb-6">
        <div class="flex justify-between items-center text-white">
            <div class="flex items-center gap-4">
                <a href="{{ url('/login-manual') }}"
                    class="bg-white text-blue-800 px-4 py-2 rounded font-bold hover:bg-gray-200 transition-colors">Logout</a>
                <h1 class="text-3xl font-bold uppercase">{{ $arenaData->name }} - MANUAL INPUT</h1>
            </div>
            <div class="flex items-center gap-3">
                @if(isset($imgData1))
                    <img src="{{ asset("/assets/Assets/uploads/$imgData1") }}"
                        class="h-10 w-10 object-contain bg-white rounded-full p-1" alt="">
                @endif
                @if(isset($imgData2))
                    <img src="{{ asset("/assets/Assets/uploads/$imgData2") }}"
                        class="h-10 w-10 object-contain bg-white rounded-full p-1" alt="">
                @endif
            </div>
        </div>
    </nav>

    <main class="container-fluid px-6 pb-12">
        <!-- Match Info -->
        <div class="grid grid-cols-3 gap-8 mb-8">
            <!-- Blue Participant -->
            <div
                class="bg-gradient-to-br from-blue-400 to-blue-500 text-white p-6 rounded-2xl shadow-2xl border-b-4 border-blue-900">
                <div class="text-xl font-medium opacity-90 uppercase tracking-wider mb-1">{{ $kontigenBiru->kontigen }}
                </div>
                <div
                    class="text-3xl font-black uppercase tracking-tight leading-tight mb-4 border-b border-blue-400 pb-2">
                    {{ $tim_birus->name }}
                </div>
                <div class="bg-blue-900/40 rounded-xl py-4 backdrop-blur-sm">
                    <div class="text-xs text-center uppercase font-bold opacity-70 mb-1">Current Total Score</div>
                    <div id="score1" class="text-7xl font-black tracking-tighter text-center">0</div>
                </div>
            </div>

            <!-- Match Center -->
            <div class="flex flex-col items-center justify-center space-y-6">
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full blur opacity-25 group-hover:opacity-60 transition duration-500">
                    </div>
                    <div
                        class="relative bg-white px-10 py-5 rounded-full shadow-xl border-2 border-yellow-500 flex flex-col items-center hover:scale-105 transition-transform duration-300">
                        <span
                            class="text-sm font-bold text-yellow-600 uppercase tracking-widest mb-1">Pertandingan</span>
                        <span class="text-5xl font-black text-yellow-600 uppercase italic leading-none">Partai
                            {{ $partai }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 w-full max-w-sm">
                    @for($i = 1; $i <= 3; $i++)
                        <div id="babak{{ $i }}"
                            class="py-3 text-center font-black rounded-xl border-2 transition-all duration-300 shadow-md @if($setting->babak == $i) bg-yellow-500 border-yellow-600 text-white scale-110 z-10 shadow-lg @else bg-white border-yellow-200 text-yellow-500 opacity-60 @endif">
                            BABAK {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Red Participant -->
            <div class="bg-red-500 text-white p-6 rounded-2xl shadow-2xl text-right border-b-4 border-red-900">
                <div class="text-xl font-medium opacity-90 uppercase tracking-wider text-end mb-1">
                    {{ $kontigenMerah->kontigen }}
                </div>
                <div
                    class="text-3xl font-black uppercase tracking-tight text-end leading-tight mb-4 border-b border-red-400 pb-2">
                    {{ $tim_merahs->name }}
                </div>
                <div class="bg-red-900/40 rounded-xl py-4 backdrop-blur-sm">
                    <div class="text-xs uppercase font-bold opacity-70 mb-1 text-center">Current Total Score</div>
                    <div id="score2" class="text-7xl font-black tracking-tighter text-center">0</div>
                </div>
            </div>
        </div>

        <div class="space-y-12">
            <!-- Unified Controls Container -->
            <div class="bg-white p-10 rounded-3xl shadow-2xl border-t-8 border-yellow-500">
                <div class="flex justify-between items-center mb-8 border-b pb-4">
                    <h3 class="text-2xl font-black text-blue-600 uppercase flex items-center">
                        <span class="w-4 h-10 bg-blue-600 rounded-full mr-3 shadow-md"></span>
                        Sudut Biru
                    </h3>
                    <div
                        class="text-xl font-black text-yellow-600 italic tracking-widest px-6 py-2 bg-yellow-50 rounded-full border border-yellow-200 shadow-inner">
                        MANUAL CONTROL PANEL
                    </div>
                    <h3 class="text-2xl font-black text-red-600 uppercase flex items-center">
                        Sudut Merah
                        <span class="w-4 h-10 bg-red-600 rounded-full ml-3 shadow-md"></span>
                    </h3>
                </div>

                <div class="grid grid-cols-12 gap-2">
                    <!-- ROW 1: PUKULAN -->
                    <button onclick="sendData('id:{{ $tim_biru }} status:pukulan p:1 keterangan:plus')"
                        class="col-span-3 h-24 bg-blue-500 text-white font-black rounded-2xl shadow-xl hover:bg-blue-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-xl border-b-8 border-blue-800">
                        Pukulan (+1)
                    </button>
                    <button onclick="sendData('id:{{ $tim_biru }} status:pukulan p:1 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Pukulan Biru
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:pukulan p:1 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Pukulan Merah
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:pukulan p:1 keterangan:plus')"
                        class="col-span-3 h-24 bg-red-500 text-white font-black rounded-2xl shadow-xl hover:bg-red-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-xl border-b-8 border-red-800">
                        Pukulan (+1)
                    </button>

                    <!-- ROW 2: TENDANGAN -->
                    <button onclick="sendData('id:{{ $tim_biru }} status:tendangan p:2 keterangan:plus')"
                        class="col-span-3 h-24 bg-blue-500 text-white font-black rounded-2xl shadow-xl hover:bg-blue-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-xl border-b-8 border-blue-800">
                        Tendangan (+2)
                    </button>
                    <button onclick="sendData('id:{{ $tim_biru }} status:tendangan p:2 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Tendangan Biru
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:tendangan p:2 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Tendangan Merah
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:tendangan p:2 keterangan:plus')"
                        class="col-span-3 h-24 bg-red-500 text-white font-black rounded-2xl shadow-xl hover:bg-red-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-xl border-b-8 border-red-800">
                        Tendangan (+2)
                    </button>

                    <!-- ROW 3: JATUHAN -->
                    <button onclick="sendData('id:{{ $tim_biru }} status:jatuh p:3 keterangan:plus')"
                        class="col-span-3 h-24 bg-blue-500 text-white font-black rounded-2xl shadow-xl hover:bg-blue-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-xl border-b-8 border-blue-800">
                        Jatuhan (+3)
                    </button>
                    <button onclick="sendData('id:{{ $tim_biru }} status:jatuh p:3 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Jatuhan Biru
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:jatuh p:3 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Jatuhan Merah
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:jatuh p:3 keterangan:plus')"
                        class="col-span-3 h-24 bg-red-500 text-white font-black rounded-2xl shadow-xl hover:bg-red-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-xl border-b-8 border-red-800">
                        Jatuhan (+3)
                    </button>

                    <!-- DIVIDER -->
                    <div class="col-span-12 my-6 relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t-2 border-gray-200 shadow-sm"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span
                                class="px-8 bg-white text-lg my-8 font-black text-blue-600 uppercase tracking-[0.2em] border-2 border-blue-100 rounded-full shadow-lg">Penalty
                                Section</span>
                        </div>
                    </div>

                    <!-- ROW 4: BINAAN -->
                    <button onclick="sendData('id:{{ $tim_biru }} status:binaan p:0 keterangan:plus')"
                        class="col-span-3 h-24 bg-blue-500 text-white font-black rounded-2xl shadow-lg hover:bg-blue-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-lg border-b-8 border-blue-700">
                        Binaan
                    </button>
                    <button onclick="sendData('id:{{ $tim_biru }} status:binaan p:0 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Binaan Biru
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:binaan p:0 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Binaan Merah
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:binaan p:0 keterangan:plus')"
                        class="col-span-3 h-24 bg-red-500 text-white font-black rounded-2xl shadow-lg hover:bg-red-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-lg border-b-8 border-red-700">
                        Binaan
                    </button>

                    <!-- ROW 5: TEGURAN -->
                    <button onclick="sendData('id:{{ $tim_biru }} status:teguran p:1 keterangan:plus')"
                        class="col-span-3 h-24 bg-blue-500 text-white font-black rounded-2xl shadow-lg hover:bg-blue-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-lg border-b-8 border-blue-700">
                        Teguran
                    </button>
                    <button onclick="sendData('id:{{ $tim_biru }} status:teguran p:1 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Teguran Biru
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:teguran p:1 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Teguran Merah
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:teguran p:1 keterangan:plus')"
                        class="col-span-3 h-24 bg-red-500 text-white font-black rounded-2xl shadow-lg hover:bg-red-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-lg border-b-8 border-red-700">
                        Teguran
                    </button>

                    <!-- ROW 6: PERINGATAN -->
                    <button onclick="sendData('id:{{ $tim_biru }} status:peringatan p:5 keterangan:plus')"
                        class="col-span-3 h-24 bg-blue-500 text-white font-black rounded-2xl shadow-lg hover:bg-blue-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-lg border-b-8 border-blue-700">
                        Peringatan
                    </button>
                    <button onclick="sendData('id:{{ $tim_biru }} status:peringatan p:5 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Peringatan Biru
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:peringatan p:5 keterangan:minus')"
                        class="col-span-3 h-24 bg-gray-500 text-white font-bold rounded-2xl shadow-lg hover:bg-gray-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-sm border-b-8 border-gray-700">
                        Hapus Peringatan Merah
                    </button>
                    <button onclick="sendData('id:{{ $tim_merah }} status:peringatan p:5 keterangan:plus')"
                        class="col-span-3 h-24 bg-red-500 text-white font-black rounded-2xl shadow-lg hover:bg-red-400 hover:scale-[1.03] active:scale-95 transition-all duration-200 uppercase text-lg border-b-8 border-red-700">
                        Peringatan
                    </button>
                </div>
            </div>
    </main>

    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const idArena = {{ $id_arena }};
        const babakCurrent = {{ $setting->babak }};
        const sesi = "{{ $jadwalData->id_sesi ?? '' }}";

        function sendData(rawContent) {
            const dataArr = rawContent.split(' ');
            const payload = {
                arena: idArena,
                juri: '{{ $id_juri }}',
                babak: babakCurrent,
                sesi: sesi
            };

            dataArr.forEach(item => {
                const [key, value] = item.split(':');
                payload[key] = value;
            });

            $.ajax({
                url: "{{ route('dewan.store') }}",
                method: "POST",
                data: JSON.stringify(payload),
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log("Success:", response);
                },
                error: function (err) {
                    console.error("Error:", err);
                }
            });
        }

        function initEcho() {
            if (window.Echo) {
                window.Echo.channel('score-channel')
                    .listen('ScoreEvent', (e) => {
                        const data = e.message;
                        if (data.arena == idArena) {
                            $('#score1').text(data.score1);
                            $('#score2').text(data.score2);

                            // Update babak indicators
                            for (let i = 1; i <= 3; i++) {
                                const el = $(`#babak${i}`);
                                if (data.babak == i) {
                                    el.removeClass('bg-white text-yellow-500 opacity-60').addClass('bg-yellow-500 border-yellow-600 text-white scale-110 z-10 shadow-lg');
                                } else {
                                    el.removeClass('bg-yellow-500 border-yellow-600 text-white scale-110 z-10 shadow-lg').addClass('bg-white border-yellow-200 text-yellow-500 opacity-60');
                                }
                            }
                        }
                    });
            }
        }

        $(document).ready(function () {
            initEcho();

            // Initial score fetch
            $.ajax({
                url: "/call-data",
                data: { arena: idArena },
                success: function (resp) {
                    if (resp.data) {
                        $('#score1').text(resp.data.score1);
                        $('#score2').text(resp.data.score2);
                    }
                }
            });
        });
    </script>
</body>

</html>