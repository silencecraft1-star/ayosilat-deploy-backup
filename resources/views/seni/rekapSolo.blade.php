<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/seni/DewanSolo.css">
    <link rel="stylesheet" href="../assets/seni/ScoreSeni.css">
    <link rel="stylesheet" href="../assets/seni/JuriSeni.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dewan Solo</title>
    @php
        use App\PersertaModel;
        use App\KontigenModel;
        use App\jadwal_group;
        use App\score;
        if (empty($id_user && $arena)) {
            echo '<script>
                        window.history.back();
                    </script>';
            exit();
        }

        $juriSeni = 4;
        $perserta = PersertaModel::where('id', $id_user)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen');
        $group = jadwal_group::where('biru', $id_user)->where('arena', $arena)->first();
        $times = explode(':', $group->kondisi);
        $detik = isset($times[1]) && $times[1] != 'N/a' ? $times[1] : '00';
        $menit = isset($times[0]) && $times[0] != 'N/a' ? $times[0] : '00';
        //$a = "PERFOMANCE EXCEDEED BY 10M BY 10M AREA";
        //$b = "WEAPON DROP DOES NOT MEET SYNOPSIS";
        //$c = "WEAPON FALL OUT OF ARENA WHILE TEAM IS STILL REQUIRED TO USE IT";
        //$d = "ATHLETE STAYING AT ONE MOVE FOR MORE THAN 5 SECONDS";
        $a = 'PESERTA KELUAR DARI 10X10 meter ARENA';
        $b = 'SENJATA TIDAK SESUAI';
        $c = 'SENJATA JATUH KELUAR ARENA WALAUPUN TIM MASIH DI TUNTUT UNTUK MENGGUNAKANNYA';
        $d = 'PESERTA BERHENTI DALAM 1 GERAKAN LEBIH DARI 5 DETIK';
        $e = 'PESILAT MELEBIHI BATAS WAKTU TOLERANSI';
        $status = str_replace(' ', '', $a);
        $a = score::where('keterangan', $status)->where('id_perserta', $id_user)->sum('score');
        $a = number_format($a, 2);
        $status = str_replace(' ', '', $b);
        $b = score::where('keterangan', $status)->where('id_perserta', $id_user)->sum('score');
        $b = number_format($b, 2);
        $status = str_replace(' ', '', $c);
        $c = score::where('keterangan', $status)->where('id_perserta', $id_user)->sum('score');
        $c = number_format($c, 2);
        $status = str_replace(' ', '', $d);
        $d = score::where('keterangan', $status)->where('id_perserta', $id_user)->sum('score');
        $d = number_format($d, 2);
        $status = str_replace(' ', '', $e);
        $e = score::where('keterangan', $status)->where('id_perserta', $id_user)->sum('score');
        $e = number_format($e, 2);

    @endphp
</head>

<body>
    <section>
        <div id="splash" class="absolute w-screen h-screen bg-slate-100 flex justify-center items-center translate-all">
            <div class="inline bg-gradient-to-br from-blue-700 to-blue-300 text-3xl bg-clip-text text-transparent">
                Sedang Mengambil Data...
            </div>
        </div>
        <div class="w-full bg-blue-600 mb-3 shadow-lg shadow-gray-400 py-2">
            <div class="lg:grid lg:grid-cols-3 h-full py-1">
                <div class="flex items-center justify-center lg:justify-start lg:ms-5 lg:mb-0">
                    <button class="
                    bg-slate-300 shadow-lg shadow-gray-600 px-10 py-2 rounded 
                    hover:px-11 hover:py-3 hover:bg-slate-400 hover:shadow-transparent 
                    transition-all active:bg-slate-600 w-full lg:w-40 mx-10 lg:mx-0">
                        <a href="{{ url('login-juri') }}">Log Out</a>
                    </button>
                </div>
                <div class="flex items-center justify-center h-100 text-5xl text-white mb-3 lg:mb-0">
                    Arena Solo
                </div>
                <div class="lg:flex lg:justify-end h-full lg:me-5 hidden">
                    <div class="h-full flex items-center flex-wrap gap-2">
                        <!-- <img src="../assets/Assets/psht.png" class="w-12" alt=""> -->
                        <img src="../assets/Assets/IPSI.png" class="w-12" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="px-3">
        <div class="container-fluid my-4">
            <div class="d-flex justify-content-between fs-3">
                <div class="text-dark">
                    {{ $perserta->name }}
                </div>
                <div class="text-primary text-end fs-3">
                    {{ $kontigen }}
                </div>
            </div>
        </div>
    </section>
    <section class="px-4">
        <div class="rounded shadow-lg">
            {{-- Table Score --}}
            <table class="table table-bordered">
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
                        <td class="py-4 fs-3">
                            Total Nilai
                        </td>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center text-primary font-bold" id="total{{ $i }}">
                                9.90
                            </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container-fluid p-0 mb-3">
            {{-- Table Time dan Penalty --}}
            <div class="row w-100 h-100 m-0">
                <div class="col-lg shadow-lg h-100 pe-1 ps-0">
                    {{-- Time Performance --}}
                    <table class="w-100 table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-primary fs-4 p-3 text-white w-50">
                                    Time Performance
                                </td>
                                <td class="fs-3 text-center bg-white">
                                    <span class="text-primary fs-3">
                                        {{ $menit }}
                                    </span>
                                    Menit
                                </td>
                                <td class="fs-3 text-center bg-white">
                                    <span class="text-primary fs-3">
                                        {{ $detik }}
                                    </span>
                                    Detik
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 fs-4 bg-primary text-white">
                                    Juri Tersortir
                                </td>
                                <td colspan="2">
                                    <table class="table table-bordered w-100 h-100 p-0 m-0">
                                        <tbody>
                                            <tr class="text-center">
                                                @for ($i = 1; $i <= $juriSeni; $i++)
                                                    <td id="urutNama{{ $i }}">
                                                        {{ $i }}
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr class="text-center">
                                                @for ($i = 1; $i <= $juriSeni; $i++)
                                                    <td id="urut{{ $i }}" class="text-primary">
                                                        9.91
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
                                <td colspan="2" class="text-center text-primary fs-5" id="medianscore">
                                    9.92
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg shadow me-lg-2 p-0">
                    {{-- Pelanggaran Dari Dewan --}}
                    <table class="table table-bordered w-100 h-100">
                        <tbody>
                            <tr>
                                <td class="fs-5 w-75">
                                    Peserta Melewati garis arena
                                </td>
                                <td class="fs-5 text-center text-danger">
                                    {{ $a }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Senjata Tidak Sesuai Ketentuan
                                </td>
                                <td class="fs-5 text-center text-danger">
                                    {{ $b }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Senjata jatuh dari arena sementara peserta masih perlu menggunakannya
                                </td>
                                <td class="fs-5 text-center text-danger">
                                    {{ $c }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Peserta diam dalam 1 gerakan lebih dari 5 detik
                                </td>
                                <td class="fs-5 text-center text-danger">
                                    {{ $d }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-5 w-75">
                                    Pesilat Melebihi Batas Waktu Toleransi
                                </td>
                                <td class="fs-5 text-center text-danger">
                                    {{ $e }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <table class="table table-bordered border-primary">
                        <tbody>
                            <tr>
                                <td class="text-primary text-center w-50">
                                    Final Score
                                </td>
                                <td class="text-primary font-bold text-center w-50" id="total_score">
                                    9.92
                                </td>
                            </tr>
                            <tr>
                                <td class="text-primary text-center w-50">
                                    Standard Deviation
                                </td>
                                <td class="text-primary font-bold text-center w-50" id="deviationscore">
                                    0.923716352
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
    <script>
        let jumlahJuri = document.getElementById("jumlahJuri").getAttribute('name');
        function findMedian(arr) {
            arr.sort((a, b) => a - b);
            const middleIndex = Math.floor(arr.length / 2);
            const left = (arr[middleIndex - 1]) * 100;
            const right = (arr[middleIndex]) * 100;
            const middle = (left + right) / 2 / 100;
            if (arr.length % 2 === 0) {
                return middle;
            } else {
                return arr[middleIndex];
            }

        }

        let reloadCount = 0;
        $(document).ready(function () {
            taketimeData();
        });

        async function taketimeData() {
            $.ajax({
                url: `/take-timer-data/?arena=` +{{$arena}} +`&id_user=` + {{$id_user}},
                method: 'GET',
                success: function (response) {
                    console.log(response.isDone);
                    if (response.isDone || reloadCount > 5) {
                        $('#splash').addClass('opacity-0');
                        setTimeout(function () {
                            $('#splash').addClass('hidden');
                        }, 1000);

                        $('#menit-text').text(response.time.menit.toString());
                        $('#detik-text').text(response.time.detik.toString());
                    } else {
                        setTimeout(taketimeData, 1000);
                        reloadCount++;
                    }
                }
            });
        }

        function pad(num, size) {
            let s = "000000000" + num;
            return s.substr(s.length - size);
        }

        function requestdata() {
            $.ajax({
                url: '/call-data/?tipe=seni&kt=ganda&id=' + {{ $id_user }} + '&arena=' + {{ $arena }},
                method: 'GET',
                success: function (response) {
                    var all_juri = [];
                    var data_juri = [];
                    for (let i = 1; i <= jumlahJuri; i++) {
                        let score = (parseFloat(response[`firmness${i}`]) + parseFloat(response[`attack${i}`]) + parseFloat(response[`soulfullness${i}`]) + 9.1).toFixed(2);
                        let juri_obj = {
                            name: `${i}`,
                            point: score
                        }

                        data_juri.push(juri_obj);
                        all_juri.push(score);
                    }

                    for (let i = 0; i < jumlahJuri; i++) {
                        $(`#total${i + 1}`).text(all_juri[i]);
                    }

                    var totalAll = 0;
                    var average = 0;
                    for (let i = 0; i < jumlahJuri; i++) {
                        totalAll += parseFloat(all_juri[i]);

                        let currentIteration = i + 1;
                        if (currentIteration == jumlahJuri) {
                            average = totalAll / jumlahJuri;
                        }
                    }

                    var deviations = 0;
                    for (let i = 0; i < jumlahJuri; i++) {
                        deviations += Math.pow((parseFloat(all_juri[i]) - average), 2);
                    }

                    var deviation = Math.sqrt(deviations / jumlahJuri);
                    var total_score = findMedian(all_juri) - response.dewan;

                    var sortedJuri = data_juri.sort((a, b) => a.point - b.point);

                    for (let i = 1; i <= jumlahJuri; i++) {
                        $(`#soulfullness${i}`).text(response[`soulfullness${i}`]);
                        $(`#attack${i}`).text(response[`attack${i}`]);
                        $(`#firmness${i}`).text(response[`firmness${i}`]);
                        $(`#total${i}`).text(response[`total${i}`]);
                        $(`#urut${i}`).text(sortedJuri[i - 1].point);
                        $(`#urutNama${i}`).text(sortedJuri[i - 1].name);
                    }


                    $('#total_score').text(total_score);
                    $('#dewan_pinalti').text(response.dewan);
                    $('#medianscore').text(findMedian(all_juri));
                    $('#deviationscore').text(deviation);

                }
            });
        }
        requestdata();
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>