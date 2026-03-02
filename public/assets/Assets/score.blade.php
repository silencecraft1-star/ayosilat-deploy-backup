<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            height: {
              '128': '32rem',
            }
          }
        }
      }
    </script>
    <style>
        .by {
        background-color: yellow;
        }

        .bn {
          background-color: white;
        }

        .by2 {
          background-image: linear-gradient(to left, #eab308, #fde047)
        }

        .bb {
            background-color: blue;
        }

        .br {
            background-color: red;
        }
    </style>
    @php
        // use Alert;
        Use App\score;
        Use App\Setting;
        Use App\KontigenModel;
        Use App\PersertaModel;
        use App\kelas;
        use App\arena;
        use App\jadwal_group;
        $data = Setting::where('arena',$arena)->first();
        $babak = $data->babak;
    @endphp
</head>
<body>
    @php
        $plus1 = score::where('status','plus')->where('id_perserta','4')->sum('score');
        $minus1 = score::where('status','minus')->where('id_perserta','4')->sum('score'); 
        $score1 = $plus1 - $minus1;
        $plus2 = score::where('status','plus')->where('id_perserta','6')->sum('score');
        $minus2 = score::where('status','minus')->where('id_perserta','6')->sum('score'); 
        $score2 = $plus2 - $minus2;
        $dataArena = arena::where('id',$arena)->first();
        $id_perserta_1 = $data->biru ;
        $id_perserta_2 = $data->merah;
        $pesertabiru = PersertaModel::where('id', $data->biru)->first();
        $pesertamerah = PersertaModel::where('id', $data->merah)->first();
        $kelas = kelas::where('id', $pesertabiru->kelas)->first();
        $kontigen1 = KontigenModel::where('id', $pesertabiru->id_kontigen)->first();
        $kontigen2 = KontigenModel::where('id', $pesertamerah->id_kontigen)->first();
        $cekpartai = jadwal_group::where('arena', $arena)->where('biru', $pesertabiru->id)->where('merah', $pesertamerah->id)->first();
        
        $partai = $data->partai ?? '';
        $settingData = Setting::where('keterangan', 'admin-setting')->first();
        
        if($settingData) {
          $imgData1 = $settingData->babak ?? '';
          $imgData2 = $settingData->partai ?? '';
        }
    
        function cekImage($filename) : Boolean {
            if($filename == "") {
                return false;
            }
            elseif(File::exists(public_path("symbolic/Assets/uploads/$filename"))) {
                return  true;
            }
             else {
                return false;
            }
        }

      @endphp
      @php
      $jatuh = score::where('keterangan','jatuh')->where('id_perserta','2')->count();
      $bina =  score::where('keterangan','binaan')->where('id_perserta','2')->count();
      $teguran = score::where('keterangan','teguran')->where('id_perserta','2')->count();
      $peringatan = score::where('keterangan','peringatan')->where('id_perserta','2')->count();
      @endphp
    <!-- Header Content -->
    <header> 
    <div class="d-none" name="{{$babak}}" id="babakid"></div>
    <div class="d-none" name="{{$arena}}" id="arenaid"></div>
    <section>
        <div class="grid md:grid-cols-2 mb-5">
            <div class="mb-3 md:mb-0">
                <div id="namaBiru" class="bg-blue-500 text-neutral-100 px-5 py-2 w-full md:w-[45svw] mb-2">Peserta 1</div>
                <div id="kontigenBiru" class="bg-blue-600 text-neutral-100 px-5 py-1 w-full md:w-[40svw]">Kontigen 1</div>
            </div>
            <div class="flex flex-col items-end">
                <div id="namaMerah" class="bg-red-500 text-end text-neutral-100 px-5 py-2 w-full md:w-[45svw] mb-2">Peserta 1</div>
                <div id="kontigenMerah" class="bg-red-600 text-end text-neutral-100 px-5 py-1 w-full md:w-[40svw]">Kontigen 1</div>
            </div>
        </div>
        <!-- Mid Section -->
        <div class="md:grid md:grid-cols-12 mb-4">
            <div class="col-span-1 flex flex-wrap justify-center gap-2 w-full my-5 md:my-0">
                <img src="../assets/Assets/pointing_hand.png" class="size-16" alt="">
                <img src="../assets/Assets/peace_hand.png" class="size-16 rotate-90" alt="">
                <img src="../assets/Assets/pointing_hand.png" class="size-16 -rotate-90" alt="">
                <img src="../assets/Assets/peace_hand.png" class="size-16 " alt="">
                <img src="../assets/Assets/raising_hand.png" class="size-16" alt="">
                <img src="../assets/Assets/raising_hand.png" class="size-16 " alt="">
                <img src="../assets/Assets/raising_hand.png" class="size-16" alt="">
            </div>
            <div class="col-span-4">
                <div class="border border-blue-500 rounded flex items-center justify-center h-full bg-blue-50">
                    <div id="score2" name="{{$id_perserta_1}}" class="text-blue-500 text-[20em] xl:text-[20em] md:text-[15em]">
                        40
                    </div>
                </div>
            </div>
            <div class="col-span-2 py-5 px-3">
                <div class="border border-neutral-700 rounded mb-5">
                    <div class="text-center">
                        Keterangan : keterangan 1
                    </div>
                    <div class="text-center">
                        Keterangan : keterangan 1
                    </div>
                    <div class="text-center">
                        Keterangan : keterangan 1
                    </div>
                    <div class="text-center">
                        Keterangan : keterangan 1
                    </div>
                </div>
                <div>
                    <div class="text-center text-red-800 fw-bold">
                        ROUND
                    </div>
                    <div class="flex flex-col justify-center items-center gap-3 mb-5">
                        <div class="text-center border border-red-700 px-5 text-3xl py-1 rounded">
                            <div id="babaksatu" class="text-red-700">
                                1
                            </div>
                        </div>
                        <div class="text-center border border-red-700 px-5 text-3xl py-1 rounded">
                            <div id="babakdua" class="text-red-700">
                                2
                            </div>
                        </div>
                        <div class="text-center border border-red-700 px-5 text-3xl py-1 rounded">
                            <div id="babaktiga" class="text-red-700">
                                3
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="border border-red-700 pb-5 px-5 lg:px-5 xl:px-8 rounded">
                            <div class="text-red-700 text-center">
                                TIMER
                            </div>
                            <div id="timer1" class="text-4xl lg:text-3xl xl:text-5xl font-semibold text-red-700">
                                07:00
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <div class="border border-red-500 rounded flex items-center justify-center h-full bg-red-50">
                    <div id="score1" name="{{$id_perserta_2}}" class="text-red-500 text-[20em] xl:text-[20em] md:text-[15em]">
                        40
                    </div>
                </div>
            </div>
            <div class="col-span-1 flex flex-wrap gap-2 justify-center w-full my-5 md:my-0">
                <img src="../assets/Assets/peace_hand.png" class="size-16 -rotate-90 scale-x-[-1]" alt="">
                <img src="../assets/Assets/pointing_hand.png" class="size-16 scale-x-[-1]" alt="">
                <img src="../assets/Assets/peace_hand.png" class="size-16 " alt="">
                <img src="../assets/Assets/pointing_hand.png" class="size-16 -rotate-90" alt="">
                <img src="../assets/Assets/raising_hand.png" class="size-16" alt="">
                <img src="../assets/Assets/raising_hand.png" class="size-16 " alt="">
                <img src="../assets/Assets/raising_hand.png" class="size-16" alt="">
            </div>
        </div>
        <div class="md:grid md:grid-cols-12 mb-10">
            <div class="col-span-1 mb-5 md:mb-0">
                <div class="border border-blue-500 h-full">
                    <div class="text-center text-neutral-100 bg-blue-500 py-1">
                        Jatuhan
                    </div>
                    <div class="flex justify-center items-center pt-1">
                        <div class="text-5xl">
                            20
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 mb-5 md:mb-0">
                <div class="grid grid-cols-3 mb-3">
                    <div class="border border-blue-500 rounded text-center bg-blue-50 text-blue-500 mx-2 py-2">
                        J3
                    </div>
                    <div class="border border-blue-500 rounded text-center bg-blue-50 text-blue-500 mx-2 py-2">
                        J2
                    </div>
                    <div class="border border-blue-500 rounded text-center bg-blue-50 text-blue-500 mx-2 py-2">
                        J1
                    </div>
                </div>
                <div class="grid grid-cols-3">
                    <div class="border border-blue-500 rounded text-center bg-blue-50 text-blue-500 mx-2 py-2">
                        J3
                    </div>
                    <div class="border border-blue-500 rounded text-center bg-blue-50 text-blue-500 mx-2 py-2">
                        J2
                    </div>
                    <div class="border border-blue-500 rounded text-center bg-blue-50 text-blue-500 mx-2 py-2">
                        J1
                    </div>
                </div>
            </div>
            <div class="col-span-2 mb-5 md:mb-0">
                <div class="flex items-center flex-col">
                    <img src="fist_bump.png" class="h-12 w-20" alt="">
                    <img src="kicks.png" class="h-12 w-20" alt="">
                </div>
            </div>
            <div class="col-span-4 mb-5 md:mb-0">
                <div class="grid grid-cols-3 mb-3">
                    <div class="border border-red-500 rounded text-center bg-red-50 text-red-500 mx-2 py-2">
                        J1
                    </div>
                    <div class="border border-red-500 rounded text-center bg-red-50 text-red-500 mx-2 py-2">
                        J2
                    </div>
                    <div class="border border-red-500 rounded text-center bg-red-50 text-red-500 mx-2 py-2">
                        J3
                    </div>
                </div>
                <div class="grid grid-cols-3">
                    <div class="border border-red-500 rounded text-center bg-red-50 text-red-500 mx-2 py-2">
                        J1
                    </div>
                    <div class="border border-red-500 rounded text-center bg-red-50 text-red-500 mx-2 py-2">
                        J2
                    </div>
                    <div class="border border-red-500 rounded text-center bg-red-50 text-red-500 mx-2 py-2">
                        J3
                    </div>
                </div>
            </div>
            <div class="col-span-1 mb-5 md:mb-0">
                <div class="border border-red-500 h-full">
                    <div class="text-center text-neutral-100 bg-red-500 py-1">
                        Jatuhan
                    </div>
                    <div class="flex justify-center items-center pt-1">
                        <div class="text-5xl">
                            20
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="fixed w-full h-8 bg-black bottom-0">
        <marquee class="text-neutral-100">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Similique culpa tenetur laudantium, voluptates numquam ipsum nisi fugiat ad impedit iure corrupti architecto placeat corporis dolorem expedita aspernatur consequuntur commodi hic.
        </marquee>
    </div>

    <div class="my-2 text-white">aa</div>
    <!-- Footer Running Text -->
    <footer>
      <div class="fixed bottom-0 w-full">
        <div class="w-full bg-black absolute bottom-0 h-8">
          <marquee behavior="" direction="left" class="text-white">{{$settingData->arena}}</marquee>
        </div>
        <div class="absolute bg-gray-500 bottom-0 h-8 w-16">
          <div class="flex items-center text-white">
            AyoSilat
          </div>
        </div>
      </div>
    </footer>
    {{-- Modal Section --}}
    {{-- Jatuhan --}}
        <div class="modal px-5"  tabindex="-1" role="dialog" id="modaljatuhan">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 100%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Keputusan <span class="text-danger">Dewan Juri</span></h5>
                <button type="button" 
                    class="btn-close" 
                    aria-label="Close"
                    data-bs-dismiss="modal"
               >
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 text-center fs-1 ">
                                    Verifikasi <span class="text-primary">Jatuhan</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>
                                                    <div class="container border py-3 ">
                                                        Juri 1 
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container border py-3">
                                                        Juri 2
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container border py-3">
                                                        Juri 3
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div id="juri1-jatuhan" class="container py-5 bn">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri2-jatuhan" class="container py-5 bn">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri3-jatuhan" class="container py-5 bn">
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    {{-- Hukuman --}}
        <div class="modal" tabindex="-1" role="dialog" id="modalhukuman">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 100%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Keputusan <span class="text-danger">Dewan Juri</span></h5>
                <button type="button" 
                    class="btn-close" 
                    aria-label="Close"
                    data-bs-dismiss="modal"
               >
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3 text-center fs-1 ">
                                    Verifikasi <span class="text-danger">Hukuman</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>
                                                    <div class="container py-3">
                                                        Juri 1 
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container py-3">
                                                        Juri 2
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="container py-3">
                                                        Juri 3
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div id="juri1-hukuman" class="container py-5 bn">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri2-hukuman" class="container py-5 bn">
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="juri3-hukuman" class="container py-5 bn">
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    @include('addon.tanding.core')
</body>
</html>