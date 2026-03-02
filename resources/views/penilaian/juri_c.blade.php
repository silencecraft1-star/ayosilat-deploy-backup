<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pencak Silat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/juri/style.css">
    @php
        $id_juri = 2;
        use App\score;
    @endphp
  </head>
  <body>
    <div class="container d-flex">
        <div class="btn1">
            <span>Kembali</span>
        </div>
    </div>

    <!-- Juri ARENA -->
    <section id="Juri">
        <div class="container d-flex justify-content-between">
            <div class="d-flex align-items-center justify-content-center">
                <div class="text">
                    <span class="team">SMKN 1 NGANJUK</span> <br>
                    <span class="peserta">Yudha</span>
                </div>
            </div>
            <div class="Juri d-flex flex-column align-items-center">
                <h1>Juri 1</h1>
                <span>ARENA A - 1</span>
            </div>
            <div class="d-flex align-items-center justify-content-center text-end">
                <div class="text">
                    <span class="team">SMA 1 JEMBER</span><br>
                    <span class="peserta">Alex</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Scorering  -->
    <section id="scorering" class="container d-flex">
        <!-- Kiri -->
        <div class="blueScore table-responsive">
            <table class="table table-bordered border border-black">
                <thead>
                    @php
                        $jatuh = score::where('keterangan','jatuh')->where('id_perserta','1')->count();
                        $tendangan =  score::where('keterangan','tendangan')->where('id_perserta','1')->count();
                        $pukulan = score::where('keterangan','pukulan')->where('id_perserta','1')->count();
                @endphp
                  <tr>
                    <th  class=" text-center">Pukulan</th>
                    <th scope="col" class=" text-center">Tendangan</th>
                    <th scope="col" class=" text-center">Jatuhan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center">{{$pukulan}}x</td>
                    <td class="text-center">{{$tendangan}}x</td>
                    <td class="text-center">{{$jatuh}}x</td>
                  </tr>
                </tbody>
                <thead>
                    <tr>
                        <th scope="col" class=" text-center " colspan="3">Riwayat Point</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = score::where('id_perserta','1')->where('status','plus')->get();
                    @endphp
                    <tr>
                        <td colspan="3">
                            @foreach ($data as $item)
                                {{$item->score}},
                            @endforeach
                        </td>
                    </tr>
                    {{-- <tr>
                        <td colspan="3"></td>
                    </tr> --}}
                </tbody>
              </table>

              <div class="skill d-flex justify-content-center">
                <button name="juri:{{$id_juri}} id:1 status:pukulan p:2 keterangan:plus" class="btnSkill1 d-flex align-items-center justify-content-center ">
                    <img src="../assets/juri/images/fist (2) 1.png">
                    <span>Pukulan</span>
                </button>
                <button name="juri:{{$id_juri}} id:1 status:tendangan p:3 keterangan:plus" class="btnSkill1 d-flex align-items-center justify-content-center">
                    <img src="../assets/juri/images/kick 1.png">
                    <span>Tendangan</span>
                </button>
              </div>

              <div class="hapus d-flex align-items-center justify-content-center">
                <button name="juri:{{$id_juri}} id:1 status:pukulan p:2 keterangan:minus" class="btnHapus text-center">
                    <span >Hapus <br> Pukulan</span>
                </button>
                <button name="juri:{{$id_juri}} id:1 status:tendangan p:3 keterangan:minus" class="btnHapus text-center">
                    <span>Hapus <br> Tendangan</span>
                </button>
              </div>
        </div>

        <!-- Tengah -->
        <div class="babak d-flex flex-column align-items-center">
            <table class="table tabelBabak">
                <thead>
                  <tr>
                    <td scope="col" class="text-center">Penyisihan</td>
                  </tr>
                  <tr>
                    <th scope="col" class="text-center">BABAK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center" style="background-color: #FFD600;">I</td>
                  </tr>
                  <tr>
                    <td class="text-center" >II</td>
                  </tr>
                  <tr>
                    <td class="text-center" >III</td>
                  </tr>
                </tbody>
              </table>

              <div class="refersh d-flex align-items-center justify-content-center">
                <img src="../assets/juri/images/Icon.png">
                <span>Refersh</span>
              </div>

              <table class="table point table-bordered border border-black">
                <thead>
                    @php
                    $plus1 = score::where('status','plus')->where('id_perserta','1')->sum('score');
                    $minus1 = score::where('status','minus')->where('id_perserta','1')->sum('score'); 
                    $score1 = $plus1 - $minus1;
                    $plus2 = score::where('status','plus')->where('id_perserta','2')->sum('score');
                    $minus2 = score::where('status','minus')->where('id_perserta','2')->sum('score'); 
                    $score2 = $plus2 - $minus2;
                @endphp
                  <tr>
                    <td scope="col" class="text-center " style="background-color: #FFD600;" colspan="2">Total Point</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center ">{{$score1}}</td>
                    <td class="text-center ">{{$score2}}</td>
                  </tr>
                </tbody>
              </table>
        </div>

        <!-- Kanan -->
        <div class="redScore table-responsive">
            <table class="table table-bordered border border-black">
                <thead class="border border-black">
                @php
                $jatuh = score::where('keterangan','jatuh')->where('id_perserta','2')->count();
                $tendangan =  score::where('keterangan','tendangan')->where('id_perserta','2')->count();
                $pukulan = score::where('keterangan','pukulan')->where('id_perserta','2')->count();
                @endphp
                <tr>
                    <th  class=" text-center">Pukulan</th>
                    <th scope="col" class=" text-center">Tendangan</th>
                    <th scope="col" class=" text-center">Jatuhan</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">{{$pukulan}}x</td>
                    <td class="text-center">{{$tendangan}}x</td>
                    <td class="text-center">{{$jatuh}}x</td>
                </tr>
        </tbody>
                <thead>
                    <tr>
                        <th scope="col" class=" text-center" colspan="3">Riwayat Point</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = score::where('id_perserta','2')->where('status','plus')->get();
                    @endphp
                    <tr>
                        <td colspan="3">
                            @foreach ($data as $item)
                                {{$item->score}},
                            @endforeach
                        </td>
                    </tr>
                    {{-- <tr>
                        <td colspan="3"></td>
                    </tr> --}}
                </tbody>
              </table>

              <div class="skill d-flex  justify-content-center">
                <button name="juri:{{$id_juri}} id:2 status:pukulan p:2 keterangan:plus" class="btnSkill2 d-flex align-items-center justify-content-center ">
                    <img src="../assets/juri/images/fist (2) 1.png">
                    <span>Pukulan</span>
                </button>
                <button name="juri:{{$id_juri}} id:2 status:tendangan p:3 keterangan:plus" class="btnSkill2 d-flex align-items-center justify-content-center">
                    <img src="../assets/juri/images/kick 1.png">
                    <span>Tendangan</span>
                </button>
                
              </div>

              <div class="hapus d-flex align-items-center justify-content-center">
                <button name="juri:{{$id_juri}} id:2 status:pukulan p:2 keterangan:minus" class="btnHapus text-center">
                    <span >Hapus <br> Pukulan</span>
                </button>
                <button name="juri:{{$id_juri}} id:2 status:tendangan p:3 keterangan:minus" class="btnHapus text-center">
                    <span>Hapus <br> Tendangan</span>
                </button>
              </div>
        </div>
    </section>
    <script>
        // Temukan semua tombol dengan kelas "button-blue" atau "button-blue-delete"
        var tombolDenganKelas = document.querySelectorAll('.btnHapus, .btnSkill1 , .btnSkill2');
    
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
                setInterval(reload, 4000);
                    });
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>