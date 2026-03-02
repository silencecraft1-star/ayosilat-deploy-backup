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
    <title>Rekap Tunggal</title>
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

        $perserta = PersertaModel::where('id', $id_user)->first();
        $kontigen = KontigenModel::where('id', $perserta->id_kontigen)->value('kontigen') ?? '';
        $group = jadwal_group::where('biru', $id_user)->where('arena', $arena)->first() ?? jadwal_group::where('merah', $id_user)->where('arena', $arena)->first();
        $times = explode(':', $group->kondisi);
        $detik = isset($times[1]) && $times[1] != 'N/a' ? $times[1] : '00';
        $menit = isset($times[0]) && $times[0] != 'N/a' ? $times[0] : '00';
        //$a = "PERFOMANCE EXCEDEED BY 10M BY 10M AREA";
        //$b = "DROPING OF WEAPON, TOUCHING THE FLOOR";
        //$c = "ATTIRE IS NOT ACCORDING TO PRESCRIPTION(TANJAK OR SAMPING FALLS OUT)";
        //$d = "ATHLETE STAYING AT ONE MOVE FOR MORE THAN 5 SECONDS";
        $a = 'PESERTA KELUAR DARI 10X10 meter ARENA';
        $b = 'MENJATUHKAN SENJATA, MENYENTUH LANTAI';
        $c = 'BUSANA TIDAK SESUAI PERSYARATAN(TANJAK ATAU SAMPING JATUH)';
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
                    Arena Tunggal
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
    @php
        $juriSeni = 6;
    @endphp
    <section class="px-4">
        <div class="rounded shadow-lg">
            {{-- Table Score --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <th class="bg-primary text-white text-center fs-3">Juri {{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center" id="actual{{ $i }}">
                                9.90
                            </td>
                        @endfor
                    </tr>
                    <tr>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center" id="flwo{{ $i }}">
                                9.90
                            </td>
                        @endfor
                    </tr>
                    <tr>
                        @for ($i = 1; $i <= $juriSeni; $i++)
                            <td class="py-4 fs-3 text-center text-primary" id="total{{ $i }}">
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
                                    <span id="menit-text" class="text-primary fs-3">
                                        {{ $menit }}
                                    </span>
                                    Menit
                                </td>
                                <td class="fs-3 text-center bg-white">
                                    <span id="detik-text" class="text-primary fs-3">
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
                                                        1
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
                                <td colspan="2" class="text-center text-primary fs-5" id="median">

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
                                <td class="text-primary font-bold text-center w-50" id="total">
                                    9.92
                                </td>
                            </tr>
                            <tr>
                                <td class="text-primary text-center w-50">
                                    Standard Deviation
                                </td>
                                <td class="text-primary font-bold text-center w-50" id="deviation">
                                    0.923716352
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-none" name="{{ $juriSeni }}" id="totalJuri"></div>

    </section>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script>
        var jumlahJuri = document.getElementById("totalJuri").getAttribute("name");
        // var jumlahJuri = 6;
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
                        $('#splash').addClass('hidden');

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
                url: '/call-data/?tipe=seni_tunggal&kt=tunggal&id=' + {{ $id_user }} + '&arena=' +
                    {{ $arena }},
                method: 'GET',
                success: function (response) {
                    console.log(response);
                    var juri1 = (parseFloat(response.actual1) + parseFloat(response.flwo1)).toFixed(2);
                    var juri2 = (parseFloat(response.actual2) + parseFloat(response.flwo2)).toFixed(2);
                    var juri3 = (parseFloat(response.actual3) + parseFloat(response.flwo3)).toFixed(2);
                    var juri4 = (parseFloat(response.actual4) + parseFloat(response.flwo4)).toFixed(2);
                    var juri5 = (parseFloat(response.actual5) + parseFloat(response.flwo5)).toFixed(2);
                    var juri6 = (parseFloat(response.actual6) + parseFloat(response.flwo6)).toFixed(2);
                    var juri7 = (parseFloat(response.actual7) + parseFloat(response.flwo7)).toFixed(2);
                    var juri8 = (parseFloat(response.actual8) + parseFloat(response.flwo8)).toFixed(2);
                    var all_juri = [];
                    var data_juri = [];

                    for (let i = 1; i <= jumlahJuri; i++) {
                        let score = (parseFloat(response[`actual${i}`]) + parseFloat(response[`flwo${i}`])).toFixed(2);
                        let jurisdata = {
                            nama: `Juri ${i}`,
                            point: score,
                        };

                        data_juri.push(jurisdata);
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
                    var total_score = (parseFloat(findMedian(all_juri)) - parseFloat(response.dewan))
                        .toFixed(2);
                    // console.log(response);

                    //var all_juri = [juri1, juri2, juri4, juri3, juri5, juri6, juri7, juri8];
                    // var juriDetail = [{
                    //         name: "1",
                    //         point: juri1
                    //     }, {
                    //         name: "2",
                    //         point: juri2
                    //     },
                    //     {
                    //         name: "3",
                    //         point: juri3
                    //     },
                    //     {
                    //         name: "4",
                    //         point: juri4
                    //     },
                    //     {
                    //         name: "5",
                    //         point: juri5
                    //     },
                    //     {
                    //         name: "6",
                    //         point: juri6
                    //     },
                    //     {
                    //         name: "7",
                    //         point: juri7
                    //     },
                    //     {
                    //         name: "8",
                    //         point: juri8
                    //     },
                    // ];

                    let {
                        juri: totaldif,
                        average: averageFinal,
                        deviation: deviation2,
                        total_score1: total2
                    } = calculateJuri(response);

                    // var average = (parseFloat(juri1) + parseFloat(juri2) + parseFloat(juri3) + parseFloat(
                    //         juri4) + parseFloat(juri5) + parseFloat(juri6) + parseFloat(juri7) +
                    //     parseFloat(juri8)) / 8;
                    // var deviations = Math.pow((parseFloat(juri1) - average), 2) + Math.pow((parseFloat(juri2) -
                    //         average), 2) + Math.pow((parseFloat(juri3) - average), 2) + Math.pow((parseFloat(
                    //         juri4) - average), 2) + Math.pow((parseFloat(juri5) - average), 2) + Math.pow((
                    //         parseFloat(juri6) - average), 2) + Math.pow((parseFloat(juri7) - average), 2) + Math
                    //     .pow((parseFloat(juri8) - average), 2);
                    // var deviation = Math.sqrt(deviations / 8);
                    // var total_score = (parseFloat(findMedian(all_juri)) - parseFloat(response.dewan)).toFixed(
                    //     2);
                    var sortedArray = data_juri.sort((a, b) => a.point - b.point);
                    console.log(JSON.stringify(sortedArray));
                    // alert(JSON.stringify(sortedArray[4]));

                    // Perbarui tampilan dengan data yang diperbarui
                    // console.log(all_juri);
                    $('#actual1').text(response.actual1);
                    $('#actual2').text(response.actual2);
                    $('#actual3').text(response.actual3);
                    $('#actual4').text(response.actual4);
                    $('#actual5').text(response.actual5);
                    $('#actual6').text(response.actual6);
                    $('#actual7').text(response.actual7);
                    $('#actual8').text(response.actual8);
                    $('#flwo1').text(response.flwo1);
                    $('#flwo2').text(response.flwo2);
                    $('#flwo3').text(response.flwo3);
                    $('#flwo4').text(response.flwo4);
                    $('#flwo5').text(response.flwo5);
                    $('#flwo6').text(response.flwo6);
                    $('#flwo7').text(response.flwo7);
                    $('#flwo8').text(response.flwo8);
                    $('#total1').text(juri1);
                    $('#total2').text(juri2);
                    $('#total3').text(juri3);
                    $('#total4').text(juri4);
                    $('#total5').text(juri5);
                    $('#total6').text(juri6);
                    $('#total7').text(juri7);
                    $('#total8').text(juri8);
                    $('#total').text(total_score);
                    $('#urut1').text(sortedArray[0].point);
                    $('#urut2').text(sortedArray[1].point);
                    $('#urut3').text(sortedArray[2].point);
                    $('#urut4').text(sortedArray[3].point);
                    $('#urut5').text(sortedArray[4].point);
                    $('#urut6').text(sortedArray[5].point);
                    // $('#urut7').text(sortedArray[6].point ?? null);
                    // $('#urut8').text(sortedArray[7].point ?? null);
                    $('#urutNama1').text(sortedArray[0].nama);
                    $('#urutNama2').text(sortedArray[1].nama);
                    $('#urutNama3').text(sortedArray[2].nama);
                    $('#urutNama4').text(sortedArray[3].nama);
                    $('#urutNama5').text(sortedArray[4].nama);
                    $('#urutNama6').text(sortedArray[5].nama);
                    // $('#urutNama7').text(sortedArray[6].nama ?? null);
                    // $('#urutNama8').text(sortedArray[7].nama ?? null);

                    $('#total').text(total_score);
                    $('#dewan').text('-' + response.dewan);
                    $('#median').text(findMedian(all_juri));
                    $('#deviation').text(deviation);

                    //$('#dewan').text('-' + response.dewan);
                    //$('#median').text(findMedian(all_juri));
                    //$('#deviation').text(deviation);
                }
            });
        }

        function calculateJuri(response) {
            let juri = [];
            let sum = 0;

            for (let i = 1; i <= jumlahJuri; i++) {
                let score = (parseFloat(response[`actual${i}`]) + parseFloat(response[`flwo${i}`])).toFixed(2);
                juri.push(parseFloat(score));
                sum += parseFloat(score);
            }

            let average = sum / juri.length;

            let deviations = juri.reduce((acc, value) => acc + Math.pow(value - average, 2), 0);
            let deviation = Math.sqrt(deviations / juri.length);

            let total_score = (findMedian(juri) - parseFloat(response.dewan)).toFixed(2);

            return {
                juri,
                average,
                deviation,
                total_score
            };
        }

        requestdata();
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>