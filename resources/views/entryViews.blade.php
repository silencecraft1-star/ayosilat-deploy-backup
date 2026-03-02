<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <title>Entry</title>
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
</head>

<body>
    @php
        use App\Setting;
        use App\arena;
        use App\entry;

        $entryData = entry::get();
        $arenaData = arena::get();
        $settingData = Setting::where('keterangan', 'admin-setting')->first();
    @endphp
    <div class="grid-cols-12 grid py-3" style="background: linear-gradient(to right, #000000, #727272ff, #000000);">
        <div class="col-span-4 hidden md:block">
            <div class="uppercase text-2xl py-2 text-center text-neutral-100 font-bold">
                Arena
            </div>
        </div>
        <div class="col-span-4 hidden md:block">
            <div class="uppercase text-3xl py-2 text-center text-neutral-100 font-bold">
                Partai
            </div>
        </div>
        <div class="col-span-4 hidden md:block">
            <div class="uppercase text-3xl py-2 text-center text-neutral-100 font-bold">
                Score
            </div>
        </div>
        <div class="col-span-12 md:hidden">
            <div class="uppercase text-3xl py-5 text-center text-neutral-100">
                Tampilan live Score
            </div>
        </div>
    </div>
    <div class="grid md:grid-cols-12 border mb-32" id="loopContainer">
        <div class="col-span-6">
            <div class="bg-neutral-50">
                <div class="text-neutral-900 text-center text-3xl py-10">
                    32
                </div>
            </div>
        </div>
        <div class="col-span-6">
            <div class="p-5 ">
                <div class="grid grid-cols-12 shadow-xl">
                    <div class="col-span-6 bg-gradient-to-r from-blue-500 to-blue-700 flex justify-center items-center">
                        <div class="text-neutral-100 text-4xl my-5">
                            20
                        </div>
                    </div>
                    <div class="col-span-6 bg-gradient-to-l from-red-500 to-red-700 flex justify-center items-center">
                        <div class="text-neutral-100 text-4xl my-5">
                            20
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="fixed w-full h-8 bg-black bottom-0">
            <marquee class="text-neutral-100">
                {{ $settingData->arena }}
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
        let arr = string.split(' ');
        return arr[1]
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
                    cont.insertAdjacentHTML('beforeend', `
                <div class="col-span-4 border border-blue-300">
                    <div class="bg-neutral-50 h-full flex justify-center items-center">
                        <div class="text-neutral-900 text-center text-6xl py-10" id="arena${data.arena}">
                            ${parseArena(data.arena)}
                        </div>
                    </div>
                </div>
                <div class="col-span-4 border border-blue-300">
                    <div class="bg-neutral-50 h-full flex justify-center items-center">
                        <div class="text-neutral-900 text-center uppercase text-6xl py-10" id="partai${data.id_arena}">
                            Partai ${data.partai}
                        </div>
                    </div>
                </div>
                <div class="col-span-4 border border-blue-300">
                    <div class="px-5 py-1">
                        <div class="grid grid-cols-12 shadow-xl">
                            ${data.tipe == "tanding" ?
                            `
                                <div class="col-span-6 flex justify-center items-center"
                                style="background: linear-gradient(to top, #0853D2, #04245c);">
                                    <div class="text-neutral-100 text-8xl my-5" id="biru${data.id_arena}">
                                        ${data.biru ?? 0}
                                    </div>
                                </div>
                                <div class="col-span-6 flex justify-center items-center"
                                style="background: linear-gradient(to top, #ff2727, #520a0a);">
                                    <div class="text-neutral-100 text-8xl my-5" id="merah${data.id_arena}">
                                        ${data.merah ?? 0}
                                    </div>
                                </div>
                                ` :
                            `
                                <div class="col-span-12 flex justify-center items-center"
                                style="background: linear-gradient(to top, #0853D2, #04245c);">
                                    <div class="text-neutral-100 text-8xl my-5" id="merah${data.id_arena}">
                                        ${data.merah ?? 0}
                                    </div>
                                </div>
                                `
                        }
                        </div>
                    </div>
                </div>
            `);
                });
            }
        });

    }
</script>

</html>