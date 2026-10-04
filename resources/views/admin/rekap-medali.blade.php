@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
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
    $totalEmas = 0;
    $totalPerak = 0;
    $totalPerunggu = 0;

    // Grouping logic
    foreach ($dataMedali as $item) {
      $peserta = PersertaModel::where('id', $item->id_peserta)->first();

      $id_kontigen = $peserta ? $peserta->id_kontigen : $item->kontigen;
      $kontigenName = KontigenModel::where('id', $id_kontigen)->value('kontigen') ?? 'Unknown';

      if (!isset($totalMedali[$id_kontigen])) {
        $totalMedali[$id_kontigen] = [
          'id_kontigen' => $id_kontigen,
          'kontigen' => $kontigenName,
          'emas' => 0,
          'perak' => 0,
          'perunggu' => 0,
          'total' => 0,
        ];
      }

      if ($item->point == "5" || $item->point == 5) {
        $totalMedali[$id_kontigen]['emas']++;
        $totalMedali[$id_kontigen]['total']++;
        $totalEmas++;
      } elseif ($item->point == "3" || $item->point == 3) {
        $totalMedali[$id_kontigen]['perak']++;
        $totalMedali[$id_kontigen]['total']++;
        $totalPerak++;
      } elseif ($item->point == "2" || $item->point == 2) {
        $totalMedali[$id_kontigen]['perunggu']++;
        $totalMedali[$id_kontigen]['total']++;
        $totalPerunggu++;
      }
    }

    // Urutkan perolehan medali berdasarkan: Emas, Perak, Perunggu, lalu Total
    $totalMedali = collect($totalMedali)->sort(function ($a, $b) {
      if ($a['emas'] !== $b['emas']) return $b['emas'] <=> $a['emas'];
      if ($a['perak'] !== $b['perak']) return $b['perak'] <=> $a['perak'];
      if ($a['perunggu'] !== $b['perunggu']) return $b['perunggu'] <=> $a['perunggu'];
      return $b['total'] <=> $a['total'];
    })->values()->all();

    $grandTotalMedali = $totalEmas + $totalPerak + $totalPerunggu;

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

  {{-- Tabel 1: Rekap Medali Tanding --}}
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="card-title fw-bolder fs-3 mb-0">
              Rekap Medali Tanding
            </h2>
            <span class="badge bg-primary fs-6 px-3 py-2 shadow-sm">
              Total: {{ $grandTotalMedali }} Medali
            </span>
          </div>
          <div class="table-responsive">
            <table id="table-recap" class="table table-bordered shadow">
              <thead>
                <tr>
                  <th class="bg-light">Kontigen</th>
                  <th class="bg-light text-center">Emas 🥇</th>
                  <th class="bg-light text-center">Perak 🥈</th>
                  <th class="bg-light text-center">Perunggu 🥉</th>
                  <th class="bg-light text-center fw-bold">Total Medali 🏆</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($totalMedali as $item)
                  <tr>
                    <td class="fw-semibold">{{ $item['kontigen'] }}</td>
                    <td class="text-center">{{ $item['emas'] }}</td>
                    <td class="text-center">{{ $item['perak'] }}</td>
                    <td class="text-center">{{ $item['perunggu'] }}</td>
                    <td class="text-center fw-bold bg-light text-primary fs-6">{{ $item['total'] }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light fw-bold">
                <tr>
                  <th>Total Seluruh Medali</th>
                  <th class="text-center text-warning-emphasis">{{ $totalEmas }}</th>
                  <th class="text-center text-secondary">{{ $totalPerak }}</th>
                  <th class="text-center text-danger">{{ $totalPerunggu }}</th>
                  <th class="text-center bg-primary-subtle text-primary fs-6">{{ $grandTotalMedali }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabel 2: Daftar Peserta Peraih Medali --}}
  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="card-title fw-bolder fs-3 mb-0">
              Daftar Peserta Peraih Medali
            </h2>
            <div class="d-flex align-items-center gap-1 flex-wrap">
              <span class="badge bg-warning text-dark px-2 py-1 shadow-sm">🥇 {{ $totalEmas }} Emas</span>
              <span class="badge bg-secondary text-white px-2 py-1 shadow-sm">🥈 {{ $totalPerak }} Perak</span>
              <span class="badge bg-danger text-white px-2 py-1 shadow-sm">🥉 {{ $totalPerunggu }} Perunggu</span>
              <span class="badge bg-primary fs-6 px-3 py-2 shadow-sm ms-1">
                Total: {{ $dataMedali->count() }} Medali
              </span>
            </div>
          </div>
          <div class="table-responsive">
            <table id="table-peserta-medali" class="table table-bordered shadow" style="width:100%">
              <thead>
                <tr>
                  <th class="bg-light text-center" style="width: 50px;">No</th>
                  <th class="bg-light">Nama Peserta</th>
                  <th class="bg-light">Kontigen</th>
                  <th class="bg-light">Kelas</th>
                  <th class="bg-light">Kategori</th>
                  <th class="bg-light">Keterangan</th>
                  <th class="bg-light text-center">Medali</th>
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
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="fw-semibold">{{ $pesertaMedali->name ?? '-' }}</td>
                    <td>{{ $kontigenMedali->kontigen ?? '-' }}</td>
                    <td>{{ $kelasMedali->name ?? '-' }}</td>
                    <td>{{ $kategoriMedali->name ?? '-' }}</td>
                    <td>{{ $med->name ?? '-' }}</td>
                    <td class="text-center"><span class="badge {{ $medaliClass }}">{{ $medaliLabel }}</span></td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light fw-bold">
                <tr>
                  <th colspan="6" class="text-end">Total Seluruh Medali:</th>
                  <th class="text-center">
                    <span class="badge bg-primary fs-6">{{ $dataMedali->count() }} Medali</span>
                  </th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabel 3: Juara Umum --}}
  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="card-title fw-bolder fs-3 mb-0">
              Juara Umum
            </h2>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-warning text-dark fs-6 px-3 py-2 shadow-sm">
                Top 3 Kontingen
              </span>
              @if($juaraUmum->count() > 0)
                <span class="badge bg-primary fs-6 px-3 py-2 shadow-sm">
                  Total: {{ $juaraUmum->sum('total') }} Medali
                </span>
              @endif
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered shadow">
              <thead>
                <tr>
                  <th class="bg-light text-center" style="width: 120px;">Peringkat</th>
                  <th class="bg-light">Kontigen</th>
                  <th class="bg-light text-center">Emas 🥇</th>
                  <th class="bg-light text-center">Perak 🥈</th>
                  <th class="bg-light text-center">Perunggu 🥉</th>
                  <th class="bg-light text-center fw-bold">Total Medali 🏆</th>
                  <th class="bg-light text-center">Total Poin</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($juaraUmum as $index => $item)
                  @php
                    $poin = ($item['emas'] * 5) + ($item['perak'] * 3) + ($item['perunggu'] * 2);
                  @endphp
                  <tr>
                    <td class="text-center fw-bold">Juara {{ $index + 1 }}</td>
                    <td class="fw-semibold">{{ $item['kontigen'] }}</td>
                    <td class="text-center">{{ $item['emas'] }}</td>
                    <td class="text-center">{{ $item['perak'] }}</td>
                    <td class="text-center">{{ $item['perunggu'] }}</td>
                    <td class="text-center fw-bold bg-light text-primary fs-6">{{ $item['total'] }}</td>
                    <td class="text-center fw-bold text-success">{{ $poin }}</td>
                  </tr>
                @endforeach
              </tbody>
              @if($juaraUmum->count() > 0)
                <tfoot class="table-light fw-bold">
                  <tr>
                    <th colspan="2" class="text-center">Total Juara Umum (Top 3)</th>
                    <th class="text-center text-warning-emphasis">{{ $juaraUmum->sum('emas') }}</th>
                    <th class="text-center text-secondary">{{ $juaraUmum->sum('perak') }}</th>
                    <th class="text-center text-danger">{{ $juaraUmum->sum('perunggu') }}</th>
                    <th class="text-center bg-primary-subtle text-primary fs-6">{{ $juaraUmum->sum('total') }}</th>
                    <th class="text-center text-success">{{ $juaraUmum->sum(function($i) { return ($i['emas'] * 5) + ($i['perak'] * 3) + ($i['perunggu'] * 2); }) }}</th>
                  </tr>
                </tfoot>
              @endif
            </table>
          </div>
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
  <script>
    $(document).ready(function () {
      let table = new DataTable('#table-recap', {
        order: []
      });
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