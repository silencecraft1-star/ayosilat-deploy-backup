<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/seni/juri_seni.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Juri Seni</title>
</head>
<body>
    @php
            use App\score;
            use App\Setting;
            use App\PersertaModel;
            use App\KontigenModel;
            use App\category;
            use App\kelas;
            use App\arena;
            $setting = Setting::where('arena',$arena)->first();
            $perserta = PersertaModel::where('id',$setting->biru)->first(); 
            $id_perserta = $perserta->id;
            $kelas = kelas::where('id', $perserta->kelas)->first();
            $kontigen = KontigenModel::where('id',$perserta->id_kontigen)->value('kontigen');
            $category = category::where('id',$perserta->category)->value('name');
            $dataArena = arena::where('id', $arena)->first();

            $number = 1;
            $scores = score::where('id_perserta',$id_perserta)
                            ->where('id_juri',$id_juri)
                            ->where('status','point_tunggal')->get();
            $scs= score::where('id_perserta',$id_perserta)
                            ->where('id_juri',$id_juri)
                            ->where('status','point_tunggal')->first();
            $score = 100;
            $total_score = 0;
            $max = 100;
            $dewan = 0;
            $babak = 0;
            $jurus = [
                'Jurus 1 (7 Gerakan)',
                'Jurus 2 (6 Gerakan)',
                'Jurus 3 (5 Gerakan)',
                'Jurus 4 (7 Gerakan)',
                'Jurus 5 (6 Gerakan)',
                'Jurus 6 (8 Gerakan)',
                'Jurus 7 (11 Gerakan)',
                'Jurus 8 (7 Gerakan)',
                'Jurus 9 (6 Gerakan)',
                'Jurus 10 (12 Gerakan)',
                'Jurus 11 (6 Gerakan)',
                'Jurus 12 (5 Gerakan)',
                'Jurus 13 (5 Gerakan)',
                'Jurus 14 (9 Gerakan)'
            ];

             $name_jurus = $jurus[0];
                if(!empty($scs)){
                    if($category === "TUNGGAL"){
                        $max = 100;
                        if($scs->babak <= 1){
                            $name_jurus= "$jurus[0]";
                        }
                        elseif($scs->babak <= 2){
                            $scs= $scs->babak-7;
                            $name_jurus= "$jurus[1]";
                        }
                        elseif($scs->babak <= 3){
                            $scs= $scs->babak-13;
                            $name_jurus= "$jurus[2]";
                        }
                        elseif($scs->babak <= 4){
                            $scs= $scs->babak-18;
                            $name_jurus= "$jurus[3]";
                        }
                        elseif($scs->babak <= 5){
                            $scs= $scs->babak-25;
                            $name_jurus= "$jurus[4]";
                        }
                        elseif($scs->babak <= 6){
                            $scs= $scs->babak-31;
                            $name_jurus= "$jurus[5]";
                        }
                        elseif($scs->babak <= 7){
                            $scs= $scs->babak-39;
                            $name_jurus= "$jurus[6]";
                        }
                        elseif($scs->babak <= 8){
                            $scs= $scs->babak-50;
                            $name_jurus= "$jurus[7]";
                        }
                        elseif($scs->babak <= 9){
                            $scs= $scs->babak-57;
                            $name_jurus= "$jurus[8]";
                        }
                        elseif($scs->babak <= 10){
                            $scs= $scs->babak-63;
                            $name_jurus= "$jurus[9]";
                        }
                        elseif($scs->babak <= 11){
                            $scs= $scs->babak-75;
                            $name_jurus= "$jurus[10]";
                        }
                        elseif($scs->babak <= 12){
                            $scs= $scs->babak-81;
                            $name_jurus= "$jurus[11]";
                        }
                        elseif($scs->babak <= 13){
                            $scs= $scs->babak-86;
                            $name_jurus= "$jurus[12]";
                        }
                        elseif($scs->babak <= 14){
                            $scs= $scs->babak-91;
                            $name_jurus= "$jurus[13]";
                        }

                    }
                    elseif($category === "TUNGGAL T.KOSONG&GOLOK"){
                        $max = 75;
                        if($scs->babak <= 1){
                            $name_jurus= "$jurus[0]";
                        }
                        elseif($scs->babak <= 2){
                            $scs= $scs->babak-7;
                            $name_jurus= "$jurus[1]";
                        }
                        elseif($scs->babak <= 3){
                            $scs= $scs->babak-13;
                            $name_jurus= "$jurus[2]";
                        }
                        elseif($scs->babak <= 4){
                            $scs= $scs->babak-18;
                            $name_jurus= "$jurus[3]";
                        }
                        elseif($scs->babak <= 5){
                            $scs= $scs->babak-25;
                            $name_jurus= "$jurus[4]";
                        }
                        elseif($scs->babak <= 6){
                            $scs= $scs->babak-31;
                            $name_jurus= "$jurus[5]";
                        }
                        elseif($scs->babak <= 7){
                            $scs= $scs->babak-39;
                            $name_jurus= "$jurus[6]";
                        }
                        elseif($scs->babak <= 8){
                            $scs= $scs->babak-50;
                            $name_jurus= "$jurus[7]";
                        }
                        elseif($scs->babak <= 9){
                            $scs= $scs->babak-57;
                            $name_jurus= "$jurus[8]";
                        }
                        elseif($scs->babak <= 10){
                            $scs= $scs->babak-63;
                            $name_jurus= "$jurus[9]";
                        }
                    }
                    elseif($category === "T.KOSONG & GOLOK"){
                        $max = 75;
                        if($scs->babak <= 1){
                            $name_jurus= "$jurus[0]";
                        }
                        elseif($scs->babak <= 2){
                            $scs= $scs->babak-7;
                            $name_jurus= "$jurus[1]";
                        }
                        elseif($scs->babak <= 3){
                            $scs= $scs->babak-13;
                            $name_jurus= "$jurus[2]";
                        }
                        elseif($scs->babak <= 4){
                            $scs= $scs->babak-18;
                            $name_jurus= "$jurus[3]";
                        }
                        elseif($scs->babak <= 5){
                            $scs= $scs->babak-25;
                            $name_jurus= "$jurus[4]";
                        }
                        elseif($scs->babak <= 6){
                            $scs= $scs->babak-31;
                            $name_jurus= "$jurus[5]";
                        }
                        elseif($scs->babak <= 7){
                            $scs= $scs->babak-39;
                            $name_jurus= "$jurus[6]";
                        }
                        elseif($scs->babak <= 8){
                            $scs= $scs->babak-50;
                            $name_jurus= "$jurus[7]";
                        }
                        elseif($scs->babak <= 9){
                            $scs= $scs->babak-57;
                            $name_jurus= "$jurus[8]";
                        }
                        elseif($scs->babak <= 10){
                            $scs= $scs->babak-63;
                            $name_jurus= "$jurus[9]";
                        }
                    }
                    elseif($category === "TUNGGAL T.KOSONG"){
                        $max = 50;
                        if($scs->babak <= 1){
                            $name_jurus= "$jurus[0]";
                        }
                        elseif($scs->babak <= 2){
                            $scs= $scs->babak-7;
                            $name_jurus= "$jurus[1]";
                        }
                        elseif($scs->babak <= 3){
                            $scs= $scs->babak-13;
                            $name_jurus= "$jurus[2]";
                        }
                        elseif($scs->babak <= 4){
                            $scs= $scs->babak-18;
                            $name_jurus= "$jurus[3]";
                        }
                        elseif($scs->babak <= 5){
                            $scs= $scs->babak-25;
                            $name_jurus= "$jurus[4]";
                        }
                        elseif($scs->babak <= 6){
                            $scs= $scs->babak-31;
                            $name_jurus= "$jurus[5]";
                        }
                        elseif($scs->babak <= 7){
                            $scs= $scs->babak-39;
                            $name_jurus= "$jurus[6]";
                        }
                    }
                    
                }
            if(!empty($scores)){
                    foreach ($scores as $item) {
                        if($item->keterangan === "next"){
                            $score -= $item->score;
                            $babak = $item->babak; 
                        }
                        elseif($item->keterangan === 'flwo'){
                            $dewan = $item->score;
                            $total_score += $item->score;
                        }
                    }
            }
            else{
                $score = 0;
            }
          $numbers = 9.00; // Ini adalah angka 9 dengan dua angka di belakang koma

        if ($score === 0) {
            $sc = number_format($score / 100, 2); 
            $score_actual = $numbers + $sc; // Ini seharusnya 9,00 + 0,00 = 9,00
        } else {
            $sc = number_format(($score - 10) / 100, 2); // Ini akan menjadi 0,19 karena ($score - 10) adalah 19
            $score_actual = $numbers + $sc; // Ini seharusnya 9,00 + 0,19 = 9,19
        }
            $total_score = number_format($total_score);
            $total_score = $total_score + $score_actual + $dewan;
            $dewan = number_format($dewan,2);
    @endphp
        <!-- Match Info Section -->
        <div class="d-flex justify-content-center ">
            <div class="mid-header-text text-center" >
                ARENA {{$dataArena->name}} <br>
                PENYISIHAN - {{$kelas->name}} ({{$category}})
            </div>
        </div>
        <!-- Player Info Section -->
        <div class="container mt-3" >   
            <div class="row">
                <div class="col">
                    Nama : <br>
                    <span class="text-primary fw-bold fs-5">{{$perserta->name}}</span>
                </div>
                <div class="col text-end">
                    : Kontingen <br>    
                    <span class="text-primary fw-bold fs-5">{{$kontigen}}</span>
                </div>
            </div>
            <!-- Score Section -->
            <div class="text-center border border-black rounded py-1 fs-4">{{$name_jurus ?? "Start"}}</div>
            <div class="row text-center mt-4" style="height: 200px;">
                <div class="col-md-5">
                    <button
                    @if ($babak == $max)
                    disabled
                     @endif
                    name="arena:{{$arena}} juri:{{$id_juri}} id:{{$id_perserta}} status:next p:{{$number}} keterangan:seni_tunggal"
                    class="btn btn-danger btn-lg custom-button shadow w-100 h-100 btn-data">
                        Wrong Move
                        <i class="fa fa-xmark"></i>
                    </button>
                </div>
                <div class="col-md-2">
                    <div class="container-fluid h-100">
                        <div class=" h-100 d-flex justify-content-between align-items-center">
                            <table class="table table-bordered border-black">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="fw-bold" style="color: rgb(190, 190, 0);">SCORE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-primary fw-bold">{{$score}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-5" >
                    <button
                    @if ($babak == $max)
                        disabled
                    @endif
                     class="btn btn-primary btn-lg custom-button shadow w-100 h-100 btn-data"
                     name="arena:{{$arena}} juri:{{$id_juri}} id:{{$id_perserta}} status:next p:0 keterangan:seni_tunggal"
                     >Next Move <i class="fa-regular fa-circle"></i></button>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center direc mt-4">
            <div class=" text-center container " >
                <div class="row">
                    <table class="table table-bordered border-black rounded">
                        <thead>
                            <tr>
                                <th class="bg-dark-subtle">ACCURACY TOTAL SCORE</th>
                                <th class="text-primary fw-bold">{{$score_actual}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row"> 
                                    Flow of Movement/Stamina (Range Score : 0,01 - 0,10)
                                    <div></div>
                                    <!-- Increment 0,01 jadi 10 button 0,01 sampai 0,10 -->
                                    @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        $number = number_format($i * 0.01, 2);
            
                                    @endphp
                                        <button
                                        class="btn btn-outline-primary btn-lg mx-1 btn-data"
                                        name="arena:{{$arena}} juri:{{$id_juri}} id:{{$id_perserta}} status:flwo p:{{$number}} keterangan:seni_tunggal"
                                        >{{$number}}</button>
                                    @endfor
                                </td>
                                <td class="text-center align-middle text-primary fw-bold">
                                    {{$dewan}}
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-dark-subtle fw-bold">TOTAL SCORE :</td>
                                <td class="fw-bold text-primary">{{$total_score}}</td>    
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
        <script>
             var tombolDenganKelas = document.querySelectorAll('.btn-data');
             tombolDenganKelas.forEach(function(tombol) {
            tombol.addEventListener( 'click', function() {
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
                })
                .catch(error => {
                    // Tangani kesalahan jika ada
                });
                function reload(){
                window.location.reload();
                }
                setInterval(reload, 800);
                    });
        });

        </script>
</body>
</html>