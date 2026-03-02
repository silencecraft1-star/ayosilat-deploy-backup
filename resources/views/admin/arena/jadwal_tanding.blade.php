@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<body>
    <!-- Title -->
    <div class="containter-fluid fs-2 fw-bold d-flex justify-content-center align-items-center mt-2">
        Jadwal Pertandigan
    </div>
    <!-- Indicator -->
    <div class="container-fluid mt-2">
        <table class="table table-bordered border-dark shadow">
            <thead class="text-center">
                <tr>
                    <th class="text-primary" colspan="3">Indikator Pertandingan</th>
                </tr>
            </thead>
            <tbody class="">
                <tr>
                    <td class="">
                        <div class="d-flex justify-content-center p-0">
                            <div class="bg-success p-2 text-center text-light rounded shadow" style="width: 75px;">Selesai</div>
                        </div>
                    </td>
                    <td class="">
                        <div class="d-flex justify-content-center p-0">
                            <div class="bg-warning p-2 text-center text-light rounded shadow" style="width: 75px;">Proses</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Status Info -->
    <div class="container-fluid">
        <table class="table table-bordered border-dark shadow">
            <thead class="text-center">
                <tr>
                    <th class="px-1">Total : 10 Pertandingan</th>
                    <th class="px-2">Selesai : 10 Pertandingan</th>
                    <th class="px-2">Sisa : 10 Pertandingan</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- Match Info Section -->
    <div class="container-fluid table-responsive-lg">
        <table class="table table-warning table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>Senin, 15 Oktober 2024</th>
                    <th>08:00-selesai</th>
                    <th>Gelanggang 1</th>
                    <th>Penyisihan</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- Information Table -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive-lg">
                    <div class="card-body">
                        <table id="example"class="table table-bordered shadow" style="width: 100%;">
                            <thead class="text-center">
                                <tr>
                                    <th class="bg-light text-center">No</th>
                                    <th class="bg-light text-center">Partai</th>
                                    <th class="bg-light text-center">Kelas</th>
                                    <th class="bg-light text-center">Sudut Merah</th>
                                    <th class="bg-light text-center">Sudut Biru</th>
                                    <th class="bg-light text-center">Skor</th>
                                    <th class="bg-light text-center">Skor</th>
                                    <th class="bg-light text-center">Pemenang</th>
                                    <th class="bg-light text-center">Kondisi Menang</th>
                                    <th class="bg-light text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                               <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center">1</td>
                                    <td>KELAS D</td>
                                    <td class="fw-bold text-danger">Wira</td>
                                    <td class="fw-bold text-primary">Galang</td>
                                    <td class="fw-bold text-danger text-center">12</td>
                                    <td class="fw-bold text-primary text-center">23</td>
                                    <td class="fw-bold">Galang</td>
                                    <td class="h-100 px-0 py-0 w-25">
                                        <div class="container form-group p-0 " >
                                            <select class="custom-select w-100 p-0" id="input-continent" style="height: 60px;">
                                                <option value="menang-1">Menang Point</option>
                                                <option value="menang-2">Menang Teknik</option>
                                                <option value="menang-2">Diskualifikasi</option>
                                                <option value="menang-3">Lawan Mengundurkan diri</option>
                                                <option value="menang-4">Keputusan wasit</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center p-0">
                                            <button class="btn btn-success px-3 shadow">Selesai</button>
                                            <!-- <div class="bg-success p-2 text-center text-light rounded shadow" style="width: 85px;">Selesai</div> -->
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
    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>    
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example')
    </script>
</body>