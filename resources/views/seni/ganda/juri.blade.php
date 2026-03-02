<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">


    <title>Solo & Ganda</title>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\juri;
        use App\kelas;

        $setting = Setting::where('arena', $arena)->first();
        $perserta = PersertaModel::where('id', $setting->biru)->first();
        $id_perserta = $perserta->id;
        $dataKelas = kelas::where('id', $perserta->kelas)->first();
        $dataJuri = juri::where('id', $id_juri)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
        $scores = score::where('id_perserta', $id_perserta)->get();

        $namaJuri = explode(' ', $dataJuri->name);
        $namaJuri = "$namaJuri[0] $namaJuri[2]";
        $arenaNama = explode('||', $setting->judul);
    @endphp
</head>

<body>
    <!-- Match Info Section -->
    <div class="d-flex justify-content-center f-cent fs-5 mt-3">
        <div class="mid-header-text text-center">
            {{$arenaNama[0]}} <br />
            {{$arenaNama[1]}}
        </div>
    </div>
    <!-- Mid Section -->
    <div class="container-fluid px-4">
        <div class="row">
            <!-- Player Info Section -->
            <div class="col fs-5">
                <span class="fs-5">NAMA PESERTA :</span> <br>
                <span class="text-primary text-uppercase">{{ $perserta->name }}</span>
            </div>
            <div class="col">
                <div class="text-center fw-bold mt-2">
                    {{ $dataJuri->name }}
                </div>
            </div>
            <div class="col text-end fs-5">
                <span class="fs-5">: KONTINGEN</span> <br>
                <span class="text-primary text-uppercase">{{ $kontigen }}</span>
            </div>
        </div>
        <table class="table table-bordered border-black">
            <thead>
                <tr>
                    <th colspan="1" class="w-10 bg-dark-subtle text-center">SCORING ELEMENT</th>
                    <th colspan="3" class="text-center bg-dark-subtle w-75">SCORE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-2  align-middle">
                        Teknik Serangan dan Pertahanan
                        (0,01-0,30)
                    </td>
                    <td>
                        <!-- LOOP SAMPAI 0,30 -->
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                            @endphp
                            <button class="btn btn-light border-black px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:attack p:{{ $number }} keterangan:pointseni partai:1">{{ $number }}</button>
                        @endfor
                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        @php
                            $check = [
                                'id_perserta' => $id_perserta,
                                'keterangan' => 'attack',
                                'id_juri' => $id_juri,
                            ];
                            $data = score::where($check)->first();
                        @endphp
                        @if ($data)
                            <span class="text-primary">{{ $data->score }}</span>
                        @else
                            <span class="text-primary">0</span>
                        @endif
                    </td>
                    <td rowspan="3" class="w-10 text-center align-middle">
                        <span class="fs-4">Total Score</span> <br>
                        -Teknik <br>
                        -Ketegasan <br>
                        -Penjiwaan <br>
                        @php
                            $score = $scores->where('status', 'point_solo')->where('id_juri', $id_juri)->sum('score');
                            $score = number_format($score, 2);
                            $score = 9.1 + $score;
                        @endphp
                        <span class="text-primary fs-4 fw-bold">{{ $score }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-2 align-middle">
                        Ketegasan dan Keharmonisan
                        (0,01-0,30)
                    </td>
                    <td>
                        <!-- LOOP SAMPAI 0,03 -->
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);
                            @endphp
                            <button class="btn btn-light border-black  px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:firmness p:{{ $number }} keterangan:pointseni">{{ $number }}</button>
                        @endfor

                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        @php
                            $check = [
                                'id_perserta' => $id_perserta,
                                'keterangan' => 'firmness',
                                'id_juri' => $id_juri,
                            ];
                            $data = score::where($check)->first();
                        @endphp
                        @if ($data)
                            <span class="text-primary">{{ $data->score }}</span>
                        @else
                            <span class="text-primary">0</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="px-2 align-middle">
                        Penjiwaan
                        (0,01-0,30)
                    </td>
                    <td>
                        <!-- LOOP SAMPAI 0,03 -->
                        @for ($i = 1; $i <= 30; $i++)
                            @php
                                $number = number_format($i * 0.01, 2);

                            @endphp
                            <button class="btn btn-light border-black  px-2 py-1 mx-1 btn-data"
                                name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:soulfullness p:{{ $number }} keterangan:pointseni">{{ $number }}</button>
                        @endfor

                    </td>
                    <td class="w-5 text-center align-middle fw-bold fs-5">
                        SCORE <br>
                        @php
                            $check = [
                                'id_perserta' => $id_perserta,
                                'keterangan' => 'soulfullness',
                                'id_juri' => $id_juri,
                            ];
                            $data = score::where($check)->first();
                        @endphp
                        @if ($data)
                            <span class="text-primary">{{ $data->score }}</span>
                        @else
                            <span class="text-primary">0</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <input type="text" hidden value="{{$setting->arena}}" name="arena" id="arena_id">
    <script>
        // Temukan semua tombol dengan kelas "button-blue" atau "button-blue-delete"
        var tombolDenganKelas = document.querySelectorAll('.btn-data');
        var arena = $('#arena_id').val();

        function websocket() {
            // var arena_id = document.getElementById('arenaId').getAttribute('name');
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('connected', function () {
                    console.log("Terhubung ke Soketi!");
                });
                Echo.channel('solo-channel')
                    .listen('SoloEvent', (datas) => {
                        const data = datas.message;
                        if (arena == data.arena && data.tipe == 'update') {
                            window.location.reload();
                        }
                    });
            } else {
                console.error('Laravel Echo is not initialized.');
            }
        }

        // Loop melalui semua tombol dan tambahkan event listener
        tombolDenganKelas.forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"

                // Membagi nilai atribut "name" menjadi objek JavaScript
                var data = {};
                nameAttribute.split(' ').forEach(function (item) {
                    var parts = item.split(':');
                    data[parts[0]] = parts[1];
                });

                // Sekarang, Anda memiliki data dalam bentuk objek
                console.log(data);

                // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
                fetch('{{ route('juri.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        reload();
                    })
                    .catch(error => {
                        // Tangani kesalahan jika ada
                    });

                function reload() {
                    window.location.reload();
                }
                // setInterval(reload, 800);
            });
        });

        websocket();
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>


</body>

</html>