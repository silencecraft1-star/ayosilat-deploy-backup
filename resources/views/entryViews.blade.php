<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <title>Entry</title>
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
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

        .gold-text {
            color: #ffd700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .gold-gradient-bg {
            background: linear-gradient(to bottom, #fff5e6, #ffd700, #b8860b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(2px 2px 2px rgba(0,0,0,0.8));
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
            background-color: #f0f4f8;
            color: #051d52;
            font-weight: 900;
            font-style: italic;
        }

        .score-blue {
            background: linear-gradient(to bottom, #1e3a8a, #1e40af);
            color: white;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
        }

        .score-red {
            background: linear-gradient(to bottom, #991b1b, #dc2626);
            color: white;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
        }
        
        .marquee-container {
            border-top: 2px solid #ffd700;
            border-bottom: 2px solid #ffd700;
            background-color: #03153e;
        }
    </style>
</head>

<body class="poppins-regular">

    <!-- <body> -->
    @php
        use App\Setting;
        use App\arena;
        use App\entry;

        $entryData = entry::get();
        $arenaData = arena::get();
        $settingData = Setting::where('keterangan', 'admin-setting')->first();
    @endphp
    <!-- Header Section -->
    <div class="w-full header-bg py-4 px-6 flex flex-col items-center justify-center relative shadow-2xl">
        <h1 class="text-5xl md:text-6xl font-black uppercase tracking-wider text-center gold-gradient-bg mb-2" style="font-family: 'Arial Black', sans-serif;">
            TRUNODJOYO NATIONAL CHAMPIONSHIP 2026
        </h1>
        <h2 class="text-xl md:text-2xl font-bold uppercase tracking-widest text-center gold-text bg-blue-900 bg-opacity-50 px-6 py-1 rounded-full border border-yellow-500">
            GEDUNG PERTEMUAN M. NOER UTM MADURA
        </h2>
    </div>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col p-4 md:p-8">
        <div class="w-full max-w-7xl mx-auto gold-border bg-white rounded-lg overflow-hidden shadow-2xl flex flex-col">
            <!-- Table Headers -->
            <div class="grid grid-cols-12 table-header text-2xl md:text-4xl py-4 border-b-[3px] border-[#ffd700]">
                <div class="col-span-4 flex items-center justify-center border-r-[3px] border-[#ffd700]">
                    <span class="gold-text">ARENA</span>
                </div>
                <div class="col-span-4 flex items-center justify-center border-r-[3px] border-[#ffd700]">
                    <span class="gold-text">PARTAI</span>
                </div>
                <div class="col-span-4 flex items-center justify-center">
                    <span class="gold-text">SCORE</span>
                </div>
            </div>

            <!-- Table Body (Dynamic) -->
            <div id="loopContainer" class="flex flex-col w-full">
                <!-- Data will be injected here via callData() -->
            </div>
        </div>
    </div>

    <!-- Footer Marquee -->
    <footer class="mt-auto pb-4">
        <div class="w-full marquee-container py-2 text-xl font-bold gold-text">
            <marquee class="tracking-widest">
                ❖ SELAMAT BERTANDING ❖ JUNJUNG TINGGI SPORTIVITAS ❖ TRUNODJOYO NATIONAL CHAMPIONSHIP 2026 ❖ {{ $settingData->arena }} ❖
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Entri Scor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-full px-5 py-2 bg-neutral-300 rounded h-24 overflow-auto mb-3">
                        @foreach($entryData as $item)
                            @php
                                $nama = arena::where('id', $item->arena)->first()->name ?? '';
                            @endphp
                            <div
                                class="flex justify-between mb-2 px-2 py-2 w-full bg-neutral-100 shadow-xl rounded border border-primary">
                                <div class="my-1">
                                    {{$nama}}
                                </div>
                                <button name="{{$item->id}}" class="btn-delete-entry bg-red-500 w-10 rounded shadow-xl">
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
                                <select name="arena" id="arenas" classs="form-control border border-primary">
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
    })

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
        })
        window.location.reload();
    })

    function parseArena(string) {
        if (!string) return '';
        let arr = string.split(' ');
        if (arr.length > 1) {
            return 'G.' + arr[1];
        }
        return string;
    }

    setInterval(updateData, 5000);

    function updateData() {
        $.ajax({
            url: "/call-data-entry",
            method: "GET",
            success: function (response) {
                listUpdate = response;
                listUpdate.forEach((data) => {
                    $(`#arena${data.id_arena}`).text(data.arena);
                    $(`#partai${data.id_arena}`).text(`Partai ${data.partai}`);
                    $(`#biru${data.id_arena}`).text(data.biru);
                    $(`#merah${data.id_arena}`).text(data.merah);
                })
            }
        })
    }

    function callData() {
        // Clear the content before inserting new data
        cont.innerHTML = "";

        $.ajax({
            url: "/call-data-entry",
            method: 'GET',
            success: function (response) {
                listContoh = response;
                console.log(response);

                // Iterate through the data and add each item to the container
                listContoh.forEach((data) => {
                    let arenaName = parseArena(data.arena);
                    // Handle arena name formatting if it's like "Gelanggang 1" -> "G.1"
                    if(arenaName && arenaName.length > 0) {
                        // Keep it as is or modify it based on the exact data. Let's just use what's returned.
                        // We can format it to G.X if needed, but let's stick to parsing it.
                    }

                    let isTanding = data.tipe == "tanding";

                    cont.insertAdjacentHTML('beforeend', `
                    <div class="grid grid-cols-12 border-b-[3px] border-[#ffd700] last:border-b-0 row-bg h-40">
                        <!-- ARENA -->
                        <div class="col-span-4 flex items-center justify-center border-r-[3px] border-[#ffd700]">
                            <div class="text-6xl md:text-8xl tracking-tighter" id="arena${data.arena}">
                                ${arenaName}
                            </div>
                        </div>
                        
                        <!-- PARTAI -->
                        <div class="col-span-4 flex items-center justify-center border-r-[3px] border-[#ffd700]">
                            <div class="text-4xl md:text-6xl tracking-tighter uppercase" id="partai${data.id_arena}">
                                PARTAI ${data.partai}
                            </div>
                        </div>
                        
                        <!-- SCORE -->
                        <div class="col-span-4 grid grid-cols-2 p-3 gap-2 bg-white">
                            ${isTanding ? `
                                <div class="score-blue rounded flex items-center justify-center shadow-lg border-2 border-blue-900">
                                    <div class="text-6xl md:text-8xl font-black italic text-white" style="text-shadow: 2px 2px 4px #000;" id="biru${data.id_arena}">
                                        ${data.biru ?? 0}
                                    </div>
                                </div>
                                <div class="score-red rounded flex items-center justify-center shadow-lg border-2 border-red-900">
                                    <div class="text-6xl md:text-8xl font-black italic text-white" style="text-shadow: 2px 2px 4px #000;" id="merah${data.id_arena}">
                                        ${data.merah ?? 0}
                                    </div>
                                </div>
                            ` : `
                                <div class="col-span-2 score-blue rounded flex items-center justify-center shadow-lg border-2 border-blue-900">
                                    <div class="text-6xl md:text-8xl font-black italic text-white" style="text-shadow: 2px 2px 4px #000;" id="merah${data.id_arena}">
                                        ${data.merah ?? 0}
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