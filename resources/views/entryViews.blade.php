<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Entry</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <style>
        @font-face {
            font-family: 'Poppins Regular';
            src: url("{{ asset('assets/fonts/poppins/Poppins-Regular.ttf') }}") format('truetype');
        }

        .poppins-regular {
            font-family: 'Poppins Regular', sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        body {
            background-color: #03153e;
            background-image: radial-gradient(circle at 50% 50%, #062a78 0%, #03153e 100%);
            min-height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .gold-border {
            border: 3px solid #ffd700;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5), inset 0 0 10px rgba(255, 215, 0, 0.2);
        }

        .cell-border-r {
            border-right: 3px solid #ffd700 !important;
        }

        .cell-border-b {
            border-bottom: 3px solid #ffd700 !important;
        }

        .gold-text {
            color: #ffd700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .gold-gradient-bg {
            background: linear-gradient(to bottom, #fff5e6, #ffd700, #b8860b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(2px 2px 2px rgba(0, 0, 0, 0.8));
        }

        .header-bg {
            background: linear-gradient(to bottom, #072769, #03153e);
            border-bottom: 4px solid #ffd700;
        }

        .table-header {
            background: linear-gradient(to bottom, #0a358c, #051d52);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .row-bg {
            background-color: #ffffff;
            color: #051d52;
            font-weight: 900;
            font-style: italic;
        }

        .score-tanding-container {
            display: flex !important;
            flex-direction: row !important;
            gap: 12px !important;
            width: 100% !important;
            height: 100% !important;
            box-sizing: border-box !important;
        }

        .score-box-item {
            flex: 1 1 50% !important;
            width: 50% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .score-blue {
            background: linear-gradient(to bottom, #1e3a8a, #1e40af);
            color: white;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .score-red {
            background: linear-gradient(to bottom, #991b1b, #dc2626);
            color: white;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .marquee-container {
            border-top: 2px solid #ffd700;
            border-bottom: 2px solid #ffd700;
            background-color: #03153e;
        }
    </style>
</head>

<body class="poppins-regular">

    @php
        use App\Setting;
        use App\arena;
        use App\entry;

        $entryData = entry::get();
        $arenaData = arena::get();
        $settingData = Setting::where('keterangan', 'admin-setting')->first();
    @endphp

    <!-- Header Section -->
    <div class="w-full header-bg py-4 px-6 flex flex-col md:flex-row items-center justify-center relative shadow-2xl">
        <!-- Placeholder Logo (Kiri Atas) -->
        <!-- <div class="md:absolute md:left-8 flex items-center justify-center mb-3 md:mb-0"> -->
        <!-- </div> -->
        <div class="flex items-center justify-center gap-5 text-center">
            <img src="{{ asset('assets/Assets/logo_entry.png') }}" alt="Logo" style="width: 10em;"
                class=" rounded-full bg-white p-1 border-2 border-yellow-400 shadow-xl object-contain">
            <div>
                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-wider text-center gold-gradient-bg mb-2"
                    style="font-family: 'Arial Black', sans-serif;">
                    TRUNODJOYO NATIONAL CHAMPIONSHIP III 2026
                </h1>
                <h2
                    class="text-xl md:text-2xl font-bold uppercase tracking-widest text-center gold-text bg-blue-900 bg-opacity-50 px-6 py-1 rounded-full border border-yellow-500">
                    GEDUNG PERTEMUAN M. NOER UTM MADURA
                </h2>

            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col p-4 md:p-8">
        <div class="w-full max-w-7xl mx-auto gold-border bg-white rounded-lg overflow-hidden shadow-2xl flex flex-col">
            <!-- Table Headers with per-cell border -->
            <div class="grid grid-cols-12 table-header text-2xl md:text-4xl cell-border-b">
                <div class="col-span-4 flex items-center justify-center cell-border-r py-4">
                    <span class="gold-text">ARENA</span>
                </div>
                <div class="col-span-4 flex items-center justify-center cell-border-r py-4">
                    <span class="gold-text">PARTAI</span>
                </div>
                <div class="col-span-4 flex items-center justify-center py-4">
                    <span class="gold-text">SCORE</span>
                </div>
            </div>

            <!-- Table Body (Dynamic) -->
            <div id="loopContainer" class="flex flex-col w-full bg-white">
                <!-- Data will be injected here via callData() -->
            </div>
        </div>
    </div>

    <!-- Footer Marquee -->
    <footer class="mt-auto pb-4">
        <div class="w-full marquee-container py-2 text-xl font-bold gold-text">
            <marquee class="tracking-widest">
                ❖ SELAMAT BERTANDING ❖ JUNJUNG TINGGI SPORTIVITAS ❖ TRUNODJOYO NATIONAL CHAMPIONSHIP III 2026 ❖
                {{ $settingData->arena ?? '' }} ❖
            </marquee>
        </div>
    </footer>

    <div class="fixed bottom-0 w-full flex justify-start pe-5">
        <button data-bs-toggle="modal" data-bs-target="#addEntry"
            class="bg-gradient-to-r from-red-500 to-red-700 py-2 px-5 rounded shadow-xl animate-pulse">
            <div class="text-white text-xl">
                Live Skor !
            </div>
        </button>
    </div>

    <div class="modal fade" id="addEntry" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="overflow:hidden;">
        <div class="modal-dialog">
            <div class="modal-content text-slate-800">
                <div class="modal-header">
                    <h5 class="modal-title font-bold" id="exampleModalLabel">Tambah Entri Scor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-full px-5 py-2 bg-neutral-300 rounded h-24 overflow-auto mb-3">
                        @foreach($entryData as $item)
                            @php
                                $nama = arena::where('id', $item->arena)->first()->name ?? '';
                            @endphp
                            <div
                                class="flex justify-between items-center mb-2 px-2 py-2 w-full bg-neutral-100 shadow-xl rounded border border-primary">
                                <div class="my-1 font-medium text-slate-800">
                                    {{$nama}}
                                </div>
                                <button name="{{$item->id}}"
                                    class="btn-delete-entry bg-red-500 w-10 h-8 text-white rounded shadow-xl flex items-center justify-center">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <form method="POST" action="{{route('admin.createEntry')}}">
                        @csrf
                        <div class="row">
                            <div class="col my-2">
                                <div class="mb-3 fw-bold">
                                    Pilih arena Mana yang akan di tampilkan
                                </div>
                                <select name="arena" id="arenas" class="form-control border border-primary">
                                    @foreach($arenaData as $item)
                                        @if($item->name)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
<script>
    let listContoh = [];
    let listUpdate = [];
    const cont = document.getElementById('loopContainer');
    cont.innerText = "";

    document.addEventListener('DOMContentLoaded', function () {
        callData();
    });

    $('.btn-delete-entry').on('click', function () {
        let data = $(this).attr('name');
        fetch('{{ route('admin.deleteEntry') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: data
            })
        }).then(() => {
            window.location.reload();
        });
    });

    function parseArena(string) {
        if (!string) return '';
        let str = string.toString().trim();
        if (str.toUpperCase().startsWith('G.')) return str.toUpperCase();
        let arr = str.split(/\s+/);
        if (arr.length > 1) {
            return arr[arr.length - 1];
        }
        if (!isNaN(str)) {
            return str;
        }
        return str;
    }

    setInterval(updateData, 5000);

    function updateData() {
        $.ajax({
            url: "/call-data-entry",
            method: "GET",
            success: function (response) {
                listUpdate = response;
                if (listUpdate && listUpdate.length > 0) {
                    listUpdate.forEach((data) => {
                        $(`#arena${data.id_arena}`).text(parseArena(data.arena));
                        $(`#partai${data.id_arena}`).text(data.partai ?? 0);
                        $(`#biru${data.id_arena}`).text(data.biru ?? 0);
                        $(`#merah${data.id_arena}`).text(data.merah ?? 0);
                    });
                }
            }
        });
    }

    function callData() {
        cont.innerHTML = "";

        $.ajax({
            url: "/call-data-entry",
            method: 'GET',
            success: function (response) {
                listContoh = response;
                console.log(response);

                if (!listContoh || listContoh.length === 0) {
                    return;
                }

                listContoh.forEach((data) => {
                    let arenaName = parseArena(data.arena);
                    let isTanding = data.tipe == "tanding";

                    cont.insertAdjacentHTML('beforeend', `
                    <div class="grid grid-cols-12 cell-border-b row-bg h-40">
                        <!-- ARENA -->
                        <div class="col-span-4 flex items-center justify-center cell-border-r">
                            <div class="text-6xl md:text-8xl tracking-tighter" id="arena${data.id_arena}">
                                ${arenaName}
                            </div>
                        </div>
                        
                        <!-- PARTAI (Hanya menampilkan nomor) -->
                        <div class="col-span-4 flex items-center justify-center cell-border-r">
                            <div class="text-6xl md:text-8xl tracking-tighter" id="partai${data.id_arena}">
                                ${data.partai ?? 0}
                            </div>
                        </div>
                        
                        <!-- SCORE (Kanan - Kiri) -->
                        <div class="col-span-4 bg-white p-3 flex items-center justify-center">
                            ${isTanding ? `
                                <div class="score-tanding-container">
                                    <!-- Merah (Kiri) -->
                                    <div class="score-box-item score-red rounded shadow-lg border-2 border-red-900">
                                        <div class="text-6xl md:text-8xl font-black italic text-white" style="text-shadow: 2px 2px 4px #000;" id="merah${data.id_arena}">
                                            ${data.merah ?? 0}
                                        </div>
                                    </div>
                                    <!-- Biru (Kanan) -->
                                    <div class="score-box-item score-blue rounded shadow-lg border-2 border-blue-900">
                                        <div class="text-6xl md:text-8xl font-black italic text-white" style="text-shadow: 2px 2px 4px #000;" id="biru${data.id_arena}">
                                            ${data.biru ?? 0}
                                        </div>
                                    </div>
                                </div>
                            ` : `
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="w-full h-full score-blue rounded flex items-center justify-center shadow-lg border-2 border-blue-900">
                                        <div class="text-6xl md:text-8xl font-black italic text-white" style="text-shadow: 2px 2px 4px #000;" id="biru${data.id_arena}">
                                            ${data.biru ?? data.merah ?? 0}
                                        </div>
                                    </div>
                                </div>
                            `}
                        </div>
                    </div>
                    `);
                });
            }
        });
    }
</script>

</html>