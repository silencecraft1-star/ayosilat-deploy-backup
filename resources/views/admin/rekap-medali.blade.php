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
    $filterKontigen = KontigenModel::orderBy('kontigen')->get();

    // Get active filters from request
    $selectedKategori = request('kategori');
    $selectedKelas = request('kelas');
    $selectedKontigen = request('kontigen');

    // Base query for Medali: Only include actual medals (emas=5, perak=3, perunggu=2)
    $query = Medali::whereIn('point', [5, 3, 2, '5', '3', '2']);
    if ($selectedKategori) {
      $query->where('kategori', $selectedKategori);
    }
    if ($selectedKelas) {
      $query->where('kelas', $selectedKelas);
    }
    if ($selectedKontigen) {
      $query->where('kontigen', $selectedKontigen);
    }

    $dataMedali = $query->get();
    $totalMedali = [];

    // Grouping logic
    foreach ($dataMedali as $item) {
      // Use the cached participant/kontigen if possible, or just use the data already in Medali table if available
      // In previous turn, we saw Medali table has 'kontigen', 'kelas', etc. 
      // But the original code was fetching from PersertaModel.
      $peserta = PersertaModel::where('id', $item->id_peserta)->first();

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

      if ($item->point == "5" || $item->point == 5)
        $totalMedali[$id_kontigen]['emas']++;
      elseif ($item->point == "3" || $item->point == 3)
        $totalMedali[$id_kontigen]['perak']++;
      elseif ($item->point == "2" || $item->point == 2)
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
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                  <label class="form-label small fw-bold text-uppercase opacity-75">Berdasarkan Kontigen</label>
                  <select class="form-select border-0 shadow-sm" onchange="applyFilter('kontigen', this.value)">
                    <option value="">Semua Kontigen</option>
                    @foreach($filterKontigen as $ktg)
                      <option value="{{ $ktg->id }}" {{ $selectedKontigen == $ktg->id ? 'selected' : '' }}>
                        {{ $ktg->kontigen }}
                      </option>
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
          <div class="table-responsive">
            <table id="table-recap" class="table table-bordered shadow">
              <thead>
                <tr>
                  <th class="bg-light">Kontigen</th>
                  <th class="bg-light">Emas 🥇</th>
                  <th class="bg-light">Perak 🥈</th>
                  <th class="bg-light">Perunggu 🥉</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($totalMedali as $item)
                  <tr>
                    <td>{{ $item['kontigen'] }}</td>
                    <td>{{ $item['emas'] }}</td>
                    <td>{{ $item['perak'] }}</td>
                    <td>{{ $item['perunggu'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h2 class="card-title fw-bolder fs-3">
            Daftar Peserta Peraih Medali
          </h2>
          <div class="table-responsive">
            <table id="table-peserta-medali" class="table table-bordered shadow" style="width:100%">
              <thead>
                <tr>
                  <th class="bg-light">No</th>
                  <th class="bg-light">Nama Peserta</th>
                  <th class="bg-light">Kontigen</th>
                  <th class="bg-light">Kelas</th>
                  <th class="bg-light">Kategori</th>
                  <th class="bg-light">Keterangan</th>
                  <th class="bg-light">Medali</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($dataMedali as $idx => $med)
                  @php
                    $pesertaMedali = PersertaModel::where('id', $med->id_peserta)->first();
                    $kontigenId = $pesertaMedali ? $pesertaMedali->id_kontigen : $med->kontigen;
                    $kontigenMedali = KontigenModel::where('id', $kontigenId)->first();
                    $kelasMedali = App\kelas::where('id', $med->kelas)->first();
                    $kategoriMedali = App\category::where('id', $med->kategori)->first();
                    $medaliLabel = 'Tidak Ada';
                    $medaliClass = '';
                    if ($med->point == 5) {
                      $medaliLabel = 'Emas 🥇';
                      $medaliClass = 'bg-warning text-dark';
                    } elseif ($med->point == 3) {
                      $medaliLabel = 'Perak 🥈';
                      $medaliClass = 'bg-secondary text-white';
                    } elseif ($med->point == 2) {
                      $medaliLabel = 'Perunggu 🥉';
                      $medaliClass = 'bg-danger text-white';
                    }
                  @endphp
                  <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $pesertaMedali->name ?? '-' }}</td>
                    <td>{{ $kontigenMedali->kontigen ?? '-' }}</td>
                    <td>{{ $kelasMedali->name ?? '-' }}</td>
                    <td>{{ $kategoriMedali->name ?? '-' }}</td>
                    <td>{{ $med->name ?? '-' }}</td>
                    <td><span class="badge {{ $medaliClass }}">{{ $medaliLabel }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
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
          <div class="table-responsive">
            <table class="table table-bordered shadow">
              <thead>
                <tr>
                  <th class="bg-light">Peringkat</th>
                  <th class="bg-light">Kontigen</th>
                  <th class="bg-light">Emas 🥇</th>
                  <th class="bg-light">Perak 🥈</th>
                  <th class="bg-light">Perunggu 🥉</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $juaraUmum = collect($totalMedali)->sort(function ($a, $b) {
                    $pointA = ($a['emas'] * 5) + ($a['perak'] * 3) + ($a['perunggu'] * 2);
                    $pointB = ($b['emas'] * 5) + ($b['perak'] * 3) + ($b['perunggu'] * 2);
                    if ($pointA !== $pointB)
                      return $pointB <=> $pointA;
                    if ($a['emas'] !== $b['emas'])
                      return $b['emas'] <=> $a['emas'];
                    if ($a['perak'] !== $b['perak'])
                      return $b['perak'] <=> $a['perak'];
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
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
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
    $(document).ready(function () {
      let table = new DataTable('#table-recap');
      let tablePeserta = new DataTable('#table-peserta-medali');
    });

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