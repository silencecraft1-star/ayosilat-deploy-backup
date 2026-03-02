<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/seni/DewanSolo.css">
    <link rel="stylesheet" href="../assets/seni/ScoreSeni.css">
    <link rel="stylesheet" href="../assets/seni/JuriSeni.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dewan Solo</title>
</head>

<body>
    <div class="container-fluid f-cent fs-4 mt-3">
        <!-- Match Info Section -->
        @php
            use App\score;
            use App\Setting;
            use App\PersertaModel;
            use App\KontigenModel;
            use App\juri;
            use App\jadwal_group;
            // peraturan
            $a = 'PESERTA KELUAR DARI 10X10 meter ARENA';
            $b = 'GERAKAN TIDAK SESUAI DESKRIPSI DAN SENJATA TIDAK SESUAI';
            $c = 'SENJATA JATUH KELUAR ARENA WALAUPUN TIM MASIH DI TUNTUT UNTUK MENGGUNAKANNYA';
            $d = 'PESERTA BERHENTI DALAM 1 GERAKAN LEBIH DARI 5 DETIK';
            $e = 'PESILAT MELEBIHI BATAS WAKTU TOLERANSI';
            $minus = '0.50';
            $setting = Setting::where('arena', $arena)->first();
            $partai = $setting->partai;
            $jadwal = jadwal_group::where('id', $setting->jadwal)->first();
            $persertam = PersertaModel::where('id', $jadwal->merah)->first();
            $perserta = PersertaModel::where('id', $setting->biru)->first();
            $id_perserta = $perserta->id;
            $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
            $kontigem = KontigenModel::where('id', $persertam->id_kontigen)->value('kontigen');
            $arenaNama = explode( '||', $setting->judul);
            $dataJuri = juri::where('id', $id_juri)->first();
        @endphp
        <div>
            {{$arenaNama[0]}} <br />
            {{$arenaNama[1]}} 
        </div>
        <!-- Player Info Section -->
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col fs-2 text-start">
                    <div>
                        <span class="fs-5">NAMA PESERTA :</span> <br>
                        <span class="text-primary">{{ $perserta->name }}</span>
                    </div>
                    <div>
                        <span class="fs-5">KONTINGEN : </span> <br>
                        <span class="text-primary">{{ $kontigen }}</span>
                    </div>
                </div>
                <div class="col fs-2 text-center d-flex justify-content-center align-items-center">
                    <div>
                        {{$dataJuri->name}}
                    </div>
                </div>
                <div class="col fs-2 text-end">
                    <div>
                        <span class="fs-5">NAMA PESERTA :</span> <br>
                        <span class="text-danger">{{ $persertam->name }}</span>
                    </div>
                    <div>
                        <span class="fs-5">: KONTINGEN</span> <br>
                        <span class="text-danger">{{ $kontigem }}</span>
                    </div>
                </div>
            </div>
            <table class="table table-bordered border-black">
                <thead>
                    <tr>
                        <th class="bg-dark-subtle" colspan="2">SCORE</th>
                        <th class="w-50 bg-dark-subtle">PENALTY</th>
                        <th class="bg-dark-subtle" colspan="2">SCORE</th>
                    </tr>
                </thead>
                <tbody class="text-start">
                    <tr>
                        
                        @php
                            $status = str_replace(' ', '', $a);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                        <td class="align-middle">
                            {{ $a }}
                        </td>
                        @php
                            $status = str_replace(' ', '', $b);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                    </tr>
                    <tr>
                        @php
                            $status = str_replace(' ', '', $b);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                        <td class="align-middle">
                            {{ $b }}
                        </td>
                        @php
                            $status = str_replace(' ', '', $b);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                    </tr>
                    <tr>
                        @php
                            $status = str_replace(' ', '', $c);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                        <td class="align-middle">
                            {{ $c }}
                        </td>
                        @php
                            $status = str_replace(' ', '', $b);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                    </tr>
                    <tr>
                      
                        @php
                            $status = str_replace(' ', '', $d);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                        <td class="align-middle">
                            {{ $d }}
                        </td>
                        @php
                            $status = str_replace(' ', '', $b);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                    </tr>
                    <tr>
                        @php
                            $status = str_replace(' ', '', $e);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                        <td class="align-middle">
                            {{ $e }}
                        </td>
                        @php
                            $status = str_replace(' ', '', $b);
                        @endphp
                        <td style="height: 6em;">
                            <div class="container-fluid h100">
                                <div class="row h100 ">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewansc"
                                            class="btn btn-primary btn-lg h100 w100 btn-data">CLEAR</button>
                                    </div>
                                    <div class="col">
                                        <button
                                            name="arena:{{ $arena }} juri:{{ $id_juri }} id:{{ $id_perserta }} status:{{ $status }} p:{{ $minus }} keterangan:senidewans"
                                            class="btn btn-danger btn-lg h100 w100 btn-data">
                                            - {{ $minus }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                            $score = score::where('keterangan', $status)
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                            $score_check = number_format(0, 2);
                        @endphp
                        @if ($score > $score_check)
                            <td class="w-10 fw-bold text-danger align-middle text-center">-{{ $score }}</td>
                        @else
                            <td class="w-10 fw-bold text-primary align-middle text-center">{{ $score }}</td>
                        @endif
                    </tr>
                    <tr>
                        {{-- <td colspan="2" class="text-end">Total Pengurangan :</td>
                        @php
                            $score = score::where('status', 'seni_minus')
                                ->where('id_perserta', $id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->sum('score');
                            $score = number_format($score, 2);
                        @endphp
                        <td class="align-middle text-center text-danger fw-bold">-{{ $score }}</td> --}}
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <form action="{{ route('rekap.seni.data') }}" method="post">
                        @csrf
                        <input type="text" hidden value="{{ $setting->arena }}" name="arena">
                        <input type="text" hidden value="{{ $setting->biru }}" name="id_user">
                        <button class="btn btn-lg btn-danger">Selesai Pertandingan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Temukan semua tombol dengan kelas "button-blue" atau "button-blue-delete"
        var tombolDenganKelas = document.querySelectorAll('.btn-data');

        // Loop melalui semua tombol dan tambahkan event listener
        tombolDenganKelas.forEach(function(tombol) {
            tombol.addEventListener('click', function() {
                var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"

                // Membagi nilai atribut "name" menjadi objek JavaScript
                var data = {};
                nameAttribute.split(' ').forEach(function(item) {
                    var parts = item.split(':');
                    data[parts[0]] = parts[1];
                });

                // Sekarang, Anda memiliki data dalam bentuk objek
                console.log(data);

                // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
                fetch('{{ route('dewan.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Lakukan sesuatu dengan respons dari server (opsional)
                    })
                    .catch(error => {
                        // Tangani kesalahan jika ada
                    });

                function reload() {
                    window.location.reload();
                }
                setInterval(reload, 800);
            });
        });
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
