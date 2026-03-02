@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
  @php
    use App\PersertaModel;
    use App\Setting;
    use App\Medali;
    use App\KontigenModel;

    // Fetch unique options for filters
    $filterKategori = Medali::distinct()->pluck('kategori')->filter();
    $filterKelas = Medali::distinct()->pluck('kelas')->filter();

    // Get active filters from request
    $selectedKategori = request('kategori');
    $selectedKelas = request('kelas');

    // Base query for Medali
    $query = Medali::query();
    if ($selectedKategori) {
      $query->where('kategori', $selectedKategori);
    }
    if ($selectedKelas) {
      $query->where('kelas', $selectedKelas);
    }

    $dataMedali = $query->get();
    $totalMedali = [];

    // Grouping logic
    foreach ($dataMedali as $item) {
      // Use the cached participant/kontigen if possible, or just use the data already in Medali table if available
      // In previous turn, we saw Medali table has 'kontigen', 'kelas', etc. 
      // But the original code was fetching from PersertaModel.
      $peserta = PersertaModel::where('id', $item->id_peserta)->first();
      if (!$peserta)
        continue;

      $id_kontigen = $peserta->id_kontigen;
      $kontigenName = KontigenModel::where('id', $id_kontigen)->value('kontigen') ?? 'Unknown';

      if (!isset($totalMedali[$id_kontigen])) {
        $totalMedali[$id_kontigen] = [
          'id_kontigen' => $id_kontigen,
          'kontigen' => $kontigenName,
          'emas' => 0,
          'perak' => 0,
          'perunggu' => 0,
        ];
      }

      if ($item->point == "5")
        $totalMedali[$id_kontigen]['emas']++;
      elseif ($item->point == "3")
        $totalMedali[$id_kontigen]['perak']++;
      elseif ($item->point == "2")
        $totalMedali[$id_kontigen]['perunggu']++;
    }
  @endphp

  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body bg-light rounded shadow-sm border-start border-4 border-primary">
          <div class="row align-items-center">
            <div class="col-md-4">
              <h5 class="mb-0 fw-bold"><i class="link-icon" data-feather="filter"></i> Filter Rekap Medali</h5>
            </div>
            <div class="col-md-8">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-uppercase opacity-75">Berdasarkan Kategori</label>
                  <select class="form-select border-0 shadow-sm" onchange="applyFilter('kategori', this.value)">
                    <option value="">Semua Kategori</option>
                    @foreach($filterKategori as $kat)
                      @php 
                        $catModel = App\category::where('id', $kat)->first();
                        $label = $catModel ? $catModel->name : $kat;
                      @endphp
                      <option value="{{ $kat }}" {{ $selectedKategori == $kat ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-uppercase opacity-75">Berdasarkan Kelas</label>
                  <select class="form-select border-0 shadow-sm" onchange="applyFilter('kelas', this.value)">
                    <option value="">Semua Kelas</option>
                    @foreach($filterKelas as $kls)
                      @php 
                        $kelasModel = App\kelas::where('id', $kls)->first();
                        $label = $kelasModel ? $kelasModel->name : $kls;
                      @endphp
                      <option value="{{ $kls }}" {{ $selectedKelas == $kls ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h2 class="card-title fw-bolder fs-3">
            Rekap Medali Tanding
          </h2>
          <table id="table-recap" class="table table-bordered shadow">
            <thead>
              <tr>
                <th class="bg-light">Kontigen</th>
                <th class="bg-light">Emas</th>
                <th class="bg-light">Perak</th>
                <th class="bg-light">Perunggu</th>
                <th class="bg-light">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($totalMedali as $item)
                <tr>
                  <td>{{ $item['kontigen'] }}</td>
                  <td>{{ $item['emas'] }}</td>
                  <td>{{ $item['perak'] }}</td>
                  <td>{{ $item['perunggu'] }}</td>
                  <td>{{ ($item['emas'] * 5) + ($item['perak'] * 3) + ($item['perunggu'] * 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h2 class="card-title fw-bolder fs-3">
            Juara Umum
          </h2>
          <table class="table table-bordered shadow">
            <thead>
              <tr>
                <th class="bg-light">Peringkat</th>
                <th class="bg-light">Kontigen</th>
                <th class="bg-light">Emas</th>
                <th class="bg-light">Perak</th>
                <th class="bg-light">Perunggu</th>
                <th class="bg-light">Total</th>
              </tr>
            </thead>
            <tbody>
              @php
                $juaraUmum = collect($totalMedali)->sort(function ($a, $b) {
                  $pointA = ($a['emas'] * 5) + ($a['perak'] * 3) + ($a['perunggu'] * 2);
                  $pointB = ($b['emas'] * 5) + ($b['perak'] * 3) + ($b['perunggu'] * 2);

                  // Prioritas 1: Total Poin
                  if ($pointA !== $pointB) return $pointB <=> $pointA;
                  
                  // Prioritas 2: Jumlah Emas (Tie Breaker)
                  if ($a['emas'] !== $b['emas']) return $b['emas'] <=> $a['emas'];
                  
                  // Prioritas 3: Jumlah Perak (Tie Breaker)
                  if ($a['perak'] !== $b['perak']) return $b['perak'] <=> $a['perak'];
                  
                  return $b['perunggu'] <=> $a['perunggu'];
                })->take(3)->values();
              @endphp
              @foreach ($juaraUmum as $index => $item)
                <tr>
                  <td>Juara {{ $index + 1 }}</td>
                  <td>{{ $item['kontigen'] }}</td>
                  <td>{{ $item['emas'] }}</td>
                  <td>{{ $item['perak'] }}</td>
                  <td>{{ $item['perunggu'] }}</td>
                  <td>{{ ($item['emas'] * 5) + ($item['perak'] * 3) + ($item['perunggu'] * 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

<script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script>
    let table = new DataTable('#table-recap');

    function applyFilter(type, value) {
      const url = new URL(window.location.href);
      if (value) {
        url.searchParams.set(type, value);
      } else {
        url.searchParams.delete(type);
      }
      window.location.href = url.toString();
    }
  </script>
@endpush