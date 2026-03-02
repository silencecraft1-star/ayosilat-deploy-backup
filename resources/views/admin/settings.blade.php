@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php
    Use App\Setting;
    use Illuminate\Support\Facades\File;
    
    $settingData = Setting::where('keterangan', 'admin-setting')->first();
    $imgData = $settingData->babak ?? "";
    $imgData2 = $settingData->partai ?? "";
    function cekImage($filename) : String {
        if($filename == "") {
            return false;
        }
        elseif(File::exists(public_path("assets/Assets/uploads/$filename"))) {
            return  true;
        }
        else {
            return false;
        }
    }
@endphp
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Setting Konten Website</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <section>
                    <div class="mb-3">
                        <h3><span class="text-primary fw-bold">•</span> Setting Gambar</h3>
                        <small class="">Gunakan Bagian ini untuk mengganti gambar pada Score Seni, Tanding, dan UI dewan juri</small>
                    </div>
                    <div class="mb-5">
                        <div class="row">
                            <div class="col">
                                <label for="gambar1" class="form-label fw-bold">Gambar 1 di Score</label>
                                <input type="file" id="gambar1" class="form-control" name="gambar1">
                                @if(cekImage($imgData) == true)
                                    <div class="my-3 fw-bold">
                                        <span class="text-primary fw-bold">•</span> Gambar1 yang digunakan Saat Ini : 
                                    </div>
                                    <div class="text-center text-lg-start">
                                        
                                        <img src="{{asset("/assets/Assets/uploads/$imgData")}}" style="height: 20em;" alt="">
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <label for="gambar22" class="form-label fw-bold">Gambar 2 di Score</label>
                                <input type="file" id="gambar2" class="form-control" name="gambar2">
                                @if(cekImage($imgData2) == true)
                                    <div class="my-3 fw-bold">
                                        <span class="text-primary fw-bold">•</span> Gambar2 yang digunakan Saat Ini : 
                                    </div>
                                    <div class="text-center text-lg-start">
                                        <img src="{{asset("/assets/Assets/uploads/$imgData2")}}" style="height: 20em;" alt="">
                                    </div>    
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="mb-3">
                        <h3><span class="text-primary fw-bold">•</span> Setting Juri Seni</h3>
                        <small class="">Gunakan Bagian ini untuk mengganti Jumlah Juri Seni</small>
                    </div>
                    <div class="mb-5">
                        <div class="row">
                            <div class="col">
                                <label for="juri" class="form-label fw-bold">Juri Seni</label>
                                <input class="form-control" type="number" name="juri" id="juri" min="4" max="10" value="{{ $settingData->jadwal ?? "6" }}">
                            </div>
                        </div>
                    </div>
                </section>
                <div class="mb-3">
                    <h3><span class="text-primary fw-bold">•</span> Setting Text</h3>
                    <small class="">Gunakan Input ini untuk Mengganti Judul CUP</small>
                </div>
                <div class="mb-3">
                    <label for="cup-title" class="form-label fw-bold">Judul Cup</label>
                    <input type="text" id="cup-title" name="title" class="form-control" maxlength="255" value="{{old('title') ?? $settingData->judul ?? '' }}">
                </div>
                <div class="mb-3">
                    <label for="running-text" class="form-label fw-bold">Running Text Di Bawah</label>
                    <textarea maxlength="255" name="running" class="form-control" id="" rows="2">{{old('running') ?? $settingData->arena ?? ''}}</textarea>
                    @if(session('success'))
                        <div class="text-success mt-1">
                            {{session('success')}}
                        </div> 
                    @elseif(session('error'))   
                        <div class="text-danger mt-1">
                            {{session('error')}}
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="link-icon me-2" data-feather="save"></i>
                            Simpan
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
        
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
