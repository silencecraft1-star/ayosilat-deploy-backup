@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <style>
        .form-floating {
            position: relative;
        }

        .icon {
            position: absolute;
            top: calc(36%);
            left: .8rem;
        }   

        .forma {
            width: 400px;
        }

        .tr-semi td {
            background-color: rgb(255, 228, 179) !important;
        }

        .tr-final td {
            background-color: rgb(255, 200, 200) !important;
        }

        @media only screen and (max-width: 600px) {
            .forma {
                width: 200px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        use App\jadwal_group;
        use App\PersertaModel;
        use App\Setting;
        use App\jadwal;
        use App\kelas;
        use App\category;
        use App\arena;
        use App\PollingModel;
        use App\KontigenModel;
        use App\score;
        use App\SesiModel;
        $tipe_arena = arena::where('id', $arena)->first();
        $data_setting = Setting::first();
        $listArena = arena::get();
        $pollingModel = PollingModel::where('id_arena', $arena)->get();
        $currentSesi = $sesi ? SesiModel::where('id', $sesi)->first() : null;
        $kelasSeni = kelas::get();
        $data_perserta = $sesi
            ? jadwal_group::where('tipe', 'tanding')
                ->where('id_sesi', $sesi)
                ->where('arena', $arena)
                ->orderByRaw('CAST(partai as unsigned) ASC')
                ->get()
            : jadwal_group::where('tipe', 'tanding')
                ->whereNull('id_sesi')
                ->where('arena', $arena)
                ->orderByRaw('CAST(partai as unsigned) ASC')
                ->get();

        // $total_pertandingan = jadwal_group::where('arena', $arena)->count();
        // $finish_pertandingan = jadwal_group::where('arena', $arena)->where('status', 'finish')->count();
        $data_jadwal = $sesi
            ? jadwal_group::where('arena', $arena)
                ->where('id_sesi', $sesi)
                ->orderByRaw('CAST(partai as unsigned) ASC')
                ->get()
            : jadwal_group::where('arena', $arena)->orderByRaw('CAST(partai as unsigned) ASC')->get();

        //$kelasall = kelas::get();
        $PesertaAll = PersertaModel::take(30)->get();
        //$PesertaAll = PersertaModel::where('name' ,'giginyala')->get();
        $dataSesi = SesiModel::where('id_arena', $arena)->get();
        $dataPoll = PollingModel::where('id_arena', $arena)->get();

        if ($tipe_arena->status == 'Tanding') {
            //$PesertaAll = PersertaModel::leftJoin('kelas', 'kelas.id', '=', 'persertas.kelas')
            //                ->select('persertas.*', 'kelas.name AS kelas_name')
            //                ->where('kelas.name', 'LIKE', '%Kelas%')
            //                ->get();
            //$PesertaAll = PersertaModel::get();
            $PesertaAll = PersertaModel::take(30)->get();
        } elseif ($tipe_arena->status == 'Tunggal' || $tipe_arena->status == 'Group') {
            $PesertaAll = PersertaModel::leftJoin('kelas', 'kelas.id', '=', 'persertas.kelas')
                ->select('persertas.*', 'kelas.name AS kelas_name')
                ->where(function ($query) {
                    $query->where('kelas.name', 'NOT LIKE', '%Kelas%');
                })->take(30)
                ->get();
        } elseif ($tipe_arena->status == 'Ganda_Kreatif' || $tipe_arena->status == 'Solo_Kreatif') {
            $PesertaAll = PersertaModel::leftJoin('kelas', 'kelas.id', '=', 'persertas.kelas')
                ->select('persertas.*', 'kelas.name AS kelas_name')
                ->where(function ($query) {
                    $query
                        ->where('kelas.name', 'LIKE', '%Ganda%')
                        ->orWhere('kelas.name', 'LIKE', '%G%')
                        ->orWhere('kelas.name', 'LIKE', '%G.%')
                        ->orWhere('kelas.name', 'LIKE', '%Solo%');
                })->take(30)
                ->get();
        } else {
            $PesertaAll = PersertaModel::take(30)->get();
        }

        if ($tipe_arena->status != 'Tanding') {
            $persertaseni = jadwal_group::where('tipe', 'seni')
                ->where('arena', $arena)
                ->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })
                ->orderByRaw('CAST(partai as unsigned) ASC')
                ->get();
        }

        $id_juri = $id_juri ?? 0;

        if (!function_exists('olahTunggu')) {
            function olahTunggu($string)
            {
                return "PEMENANG PARTAI " . trim($string, '[]');
            }
        }
    @endphp

    <body>
        <input id="arenaType" type="hidden" name="" value="{{ $tipe_arena->status }}">
        <input id="arenaName" type="hidden" name="" value="{{ $tipe_arena->name }}">
        <input id="sessionId" type="hidden" name="" value="{{ $sesi }}">
        @if ($sesi)
            <input id="current_session_name" type="hidden" name="" value="{{ $currentSesi->name }}">
        @endif
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
                                <div class="bg-success p-2 text-center text-light rounded shadow" style="width: 75px;">
                                    Selesai</div>
                            </div>
                        </td>
                        <td class="">
                            <div class="d-flex justify-content-center p-0">
                                <div class="bg-warning p-2 text-center text-light rounded shadow" style="width: 75px;">
                                    Proses</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Status Info -->
        <div class="container-fluid">
            <div class="table-responsive-sm">
                <table class="table table-bordered border-dark shadow">
                    {{-- <thead class="text-center">
                        <tr>
                            <th class="px-1">Total : {{ $total_pertandingan }} Pertandingan</th>
                            <th class="px-2">Selesai : {{ $finish_pertandingan }} Pertandingan</th>
                            <th class="px-2">Sisa : {{ $total_pertandingan - $finish_pertandingan }} Pertandingan</th>
                        </tr>
                    </thead> --}}
                </table>
            </div>
        </div>

        @if ($tipe_arena->status === 'Tanding')
            <div class="cotainer-fluid">
                <div class="row">
                    <div class="col">
                        <div class="mb-2 mt-3 ps-3 d-flex justify-content-start align-items-center">
                            <button type="button" class="btn me-3 btn-primary shadow" data-bs-toggle="modal"
                                data-bs-target="#addpeserta">Tambah Peserta</button>
                            <button type="button" class="btn me-3 btn-secondary shadow" data-bs-toggle="modal"
                                data-bs-target="#addSesi">Tambah Sesi</button>
                            <button type="button" class="btn btn-success shadow me-3" data-bs-toggle="modal"
                                data-bs-target="#importJadwal">Import Jadwal</button>
                            <button type="button" class="btn btn-danger shadow" data-bs-toggle="modal"
                                data-bs-target="#clearJadwal">Clear Jadwal</button>
                        </div>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive-lg">
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <a href="/redirect?arena={{ $arena }}&role=arena-jadwal" name="test"
                                        class="sesi-btn mt-2 px-5 rounded-top btn {{ !$sesi ? 'btn-primary' : 'btn-secondary' }} text-lg fs-4 py-1">Tanpa
                                        Sesi</a>
                                    @if ($dataSesi->count() != 0)
                                        @foreach ($dataSesi as $items)
                                            <a href="/redirect?arena={{ $arena }}&role=arena-jadwal&sesi={{ $items->id }}"
                                                class="sesi-btn {{ $sesi == $items->id ? 'btn-primary' : 'btn-secondary' }} mt-2 px-5 rounded-top btn text-light text-lg fs-4 py-1">{{ $items->name }}</a>
                                        @endforeach
                                    @endif
                                </div>
                                @if (session('success'))
                                    <div class="mb-3">
                                        <div class="w-full bg-success-subtle text-success px-3 py-2 rounded">
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="mb-3">
                                        <div class="w-full bg-danger-subtle text-danger px-3 py-2 rounded">
                                            @foreach (session('error') as $message)
                                                {{ $message }}
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered shadow" style="width: 100%;">
                                        <thead class="text-center">
                                            <tr>
                                                <th class="bg-light text-center">Partai</th>
                                                <th class="bg-light text-center">Kelas</th>
                                                <th class="bg-light text-center">Sudut Biru</th>
                                                <th class="bg-light text-center">Sudut Merah</th>
                                                <th class="bg-light text-center">Skor</th>
                                                <th class="bg-light text-center">Skor</th>
                                                <th class="bg-light text-center">Kondisi Menang</th>
                                                <th class="bg-light text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                            @php
                                                $pemenang = 'N/a';
                                            @endphp
                                            @foreach ($data_perserta as $item)
                                                @if ($item->skor_merah > $item->skor_biru)
                                                    @php
                                                        $pemenang = $item->merah;
                                                    @endphp
                                                @elseif ($item->skor_merah === $item->skor_biru)
                                                    @php
                                                        $pemenang = 'N/a';
                                                    @endphp
                                                @else
                                                    @php
                                                        $pemenang = $item->biru;
                                                    @endphp
                                                @endif
                                                @php
                                                    $kelas = kelas::where('id', $item->kelas)->value('name');
                                                    // $category = category::where('id',$item->category)->value('name');
                                                    $pesertabiru = PersertaModel::where('id', $item->biru)->first();
                                                    $pesertamerah = PersertaModel::where('id', $item->merah)->first();
                                                    $kategori = '';

                                                    if ($pesertabiru) {
                                                        $kontigenBiru =
                                                            KontigenModel::where('id', $pesertabiru->id_kontigen)->first()
                                                                ->kontigen ?? '';

                                                        $kategori = category::where('id', $pesertabiru->category)->value(
                                                            'name',
                                                        );
                                                    } else {
                                                        $kontigenBiru = '';
                                                    }

                                                    if ($pesertamerah) {
                                                        $kontigenMerah =
                                                            KontigenModel::where('id', $pesertamerah->id_kontigen)->first()
                                                                ->kontigen ?? '';
                                                        $kategori = category::where('id', $pesertamerah->category)->value(
                                                            'name',
                                                        );
                                                    } else {
                                                        $kontigenMerah = '';
                                                    }

                                                @endphp
                                                @php
                                                    $rowClass = '';
                                                    if ($item->keterangan === 'semi-final') {
                                                        $rowClass = 'tr-semi';
                                                    } elseif ($item->keterangan === 'final') {
                                                        $rowClass = 'tr-final';
                                                    }
                                                @endphp
                                                <tr class="{{ $rowClass }}">
                                                    <td class="text-center">{{ $item->partai }}</td>
                                                    <td>{{ $kelas }} <br /> {{ $kategori }} </td>
                                                    <td class="fw-bold text-primary">
                                                        {{ $pesertabiru->name ?? (olahTunggu($item->biru) ?? '') }}
                                                        <br>
                                                        <span class="text-dark">
                                                            {{ $kontigenBiru }}
                                                        </span>
                                                    </td>
                                                    <td class="fw-bold text-danger">
                                                        {{ $pesertamerah->name ?? (olahTunggu($item->merah) ?? '') }}
                                                        <br>
                                                        <span class="text-dark">
                                                            {{ $kontigenMerah }}
                                                        </span>
                                                    </td>
                                                    <td class="fw-bold text-primary text-center">{{ $item->score_biru }}</td>
                                                    <td class="fw-bold text-danger text-center">{{ $item->score_merah }}</td>
                                                    @php
                                                        $pemenang = PersertaModel::where('id', $item->pemenang)->value(
                                                            'name',
                                                        );
                                                    @endphp
                                                    <td class="h-100 px-0 py-0 w-25">
                                                        <div class="container form-group p-0 ">
                                                            kondisi : {{ $item->kondisi }}
                                                            <br>
                                                            pemenang : <span class="text-success">{{ $pemenang ?? 'N/a' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2 p-0">
                                                            <button data-bs-target="#editPeserta" data-bs-toggle="modal"
                                                                onclick="asignEdit({{ $pesertabiru->id ?? null }}, {{ $pesertamerah->id ?? null }}, {{ $item->partai ?? null }}, {{ $item->id ?? null }}, '{{ $pesertabiru->name ?? null }}', '{{ $pesertamerah->name ?? null }}')"
                                                                class="btn btn-primary px-3 shadow text-light" @if (!$pesertabiru || !$pesertamerah) disabled @endif>Edit</button>
                                                            @if ($item->status === 'pending')
                                                                <button
                                                                    name="keterangan:jadwal id:{{ $item->id }} sesi:{{ $sesi ?? null }} p:{{ $item->biru }} p1:{{ $item->merah }} partai:{{ $item->partai }} status:proses arena:{{ $arena }}"
                                                                    class="btn btn-data btn-primary px-3 shadow text-light" @if (!$pesertabiru || !$pesertamerah) disabled @endif>Pending</button>
                                                            @endif
                                                            @if ($item->status === 'proses')
                                                                <button
                                                                    name=" keterangan:jadwal id:{{ $item->id }} sesi:{{ $sesi ?? null }} p:{{ $item->biru }} p1:{{ $item->merah }} partai:{{ $item->partai }} status:proses arena:{{ $arena }}"
                                                                    class="btn btn-data btn-warning px-3 shadow text-light">Proses</button>
                                                            @endif
                                                            @if ($item->status === 'finish')
                                                                <button
                                                                    name="keterangan:jadwal id:{{ $item->id }} sesi:{{ $sesi ?? null }} p:{{ $item->biru }} p1:{{ $item->merah }} partai:{{ $item->partai }} status:finish arena:{{ $arena }}"
                                                                    class="btn btn-data btn-success px-3 shadow text-light">Selesai</button>
                                                            @endif
                                                            <button
                                                                name="keterangan:hapusjadwal sesi:{{ $sesi ?? null }} p:{{ $item->id }}"
                                                                class="btn btn-delete btn-danger px-3 shadow text-light">
                                                                Hapus </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <td colspan="2"></td>
                                                    <td>{{ $kontigenBiru }}</td>
                                                    <td>{{ $kontigenMerah }}</td>
                                                    <td colspan="2"></td>
                                                    @php
                                                    $pemenang = PersertaModel::where('id', $item->pemenang)->value(
                                                    'name',
                                                    );
                                                    @endphp
                                                    <td class="bg-white text-success fw-bold">{{ $pemenang }}</td>
                                                    <td colspan="1"></td>
                                                </tr> --}}
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="cotainer-fluid">
                <div class="row">
                    <div class="col">
                        <div class="mb-3 mt-3 ps-3 d-flex justify-content-start align-items-center">
                            <button type="button" class="btn btn-primary shadow me-3" data-bs-toggle="modal"
                                data-bs-target="#addPesertaSeni">Tambah Peserta</button>
                            {{-- <button type="button" class="btn me-3 btn-secondary shadow" data-bs-toggle="modal"
                                data-bs-target="#addSesi">Tambah Sesi</button> --}}
                            <button type="button" class="btn me-3 btn-secondary shadow" data-bs-toggle="modal"
                                data-bs-target="#addPoll">Tambah Poll</button>
                            <button type="button" class="btn me-3 btn-danger shadow" data-bs-toggle="modal"
                                data-bs-target="#clearJadwal">Clear Jadwal</button>
                            {{-- <button type="button" class="btn btn-success shadow btn-ranking" value="{{ $arena }}">Rekap
                                Ranking</button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <div class="card-body table-responsive">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <a href="/redirect?arena={{ $arena }}&role=arena-jadwal" name="test"
                                        class="sesi-btn mt-2 px-5 rounded-top btn {{ !$sesi ? 'btn-primary' : 'btn-secondary' }} text-lg fs-4 py-1">Tanpa
                                        Sesi</a>
                                    @if ($dataSesi->count() != 0)
                                        @foreach ($dataSesi as $items)
                                            <a href="/redirect?arena={{ $arena }}&role=arena-jadwal&sesi={{ $items->id }}"
                                                class="sesi-btn {{ $sesi == $items->id ? 'btn-primary' : 'btn-secondary' }} mt-2 px-5 rounded-top btn text-light text-lg fs-4 py-1">{{ $items->name }}</a>
                                        @endforeach
                                    @endif
                                </div>
                                @foreach ($dataPoll as $item)
                                    @php
                                        $id = $item->id;
                                        $pollId = $id;
                                        $pollJadwal = $persertaseni->filter(function ($data) use ($id) {
                                            return $data->id_poll == $id;
                                        });

                                        $pemasalanData = $pollJadwal->filter(function ($data) {
                                            return $data->keterangan == "pemasalan";
                                        });

                                        $prestasiData = $pollJadwal->filter(function ($data) {
                                            return $data->keterangan == "prestasi";
                                        });
                                    @endphp
                                    <div class="card">
                                        <div class="card-body table-responsive">
                                            <h3>{{ $item->name }}</h3>
                                        </div>
                                        @if($pemasalanData)
                                            <div class="px-5 mb-3">
                                                <h5>Pemasalan</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered datatable shadow" id="datatable-2">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th class="bg-light text-center">No</th>
                                                                <th class="bg-light text-center">Partai</th>
                                                                <th class="bg-light text-center">Kelas</th>
                                                                <th class="bg-light text-center">Nama</th>
                                                                <th class="bg-light text-center">Skor</th>
                                                                <th class="bg-light text-center">Waktu</th>
                                                                <th class="bg-light text-center">Deviation</th>
                                                                <th class="bg-light text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center align-middle">
                                                            @foreach ($pemasalanData as $subitem)
                                                                <tr>
                                                                    @php
                                                                        $item = PersertaModel::where('id', $subitem->merah)->first() ?? PersertaModel::where('id', $subitem->biru)->first();
                                                                        $kelas = kelas::where('id', $item->kelas)->value('name');
                                                                        $category = category::where('id', $item->category)->value('name');
                                                                        $datar = score::where('id_perserta', $item->id)->get();
                                                                        $actual = 9.9;
                                                                        $sc = [];

                                                                    @endphp
                                                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                                                    <td class="text-center">{{ $subitem->partai }}</td>
                                                                    <td>{{ $kelas }}</td>
                                                                    <td class="">{{ $item->name }}</td>
                                                                    {{-- <td class="">{{$category}}</td> --}}
                                                                    <td class="text-danger text-center">{{ $subitem->score_biru }}</td>
                                                                    <td class="text-primary text-center">{{ $subitem->timer_biru }}</td>
                                                                    <td class="text-center">{{ $subitem->deviasi_biru }}</td>

                                                                    <td>
                                                                        <div class="d-flex justify-content-center p-0">
                                                                            @if(!empty($subitem->tipe) && $subitem->tipe == "prestasi")
                                                                                <button
                                                                                    name="keterangan:jadwal_seni id:{{ $subitem->id }} sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->biru }} partai:{{ $subitem->partai }} status:proses arena:{{ $arena }}"
                                                                                    class="btn btn-data btn-primary px-3 shadow text-light mx-1">Biru</button>
                                                                                <button
                                                                                    name="keterangan:jadwal_seni id:{{ $subitem->id }} sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->merah }} partai:{{ $subitem->partai }} status:proses arena:{{ $arena }}"
                                                                                    class="btn btn-data btn-danger px-3 shadow text-light mx-1">Merah</button>
                                                                            @else
                                                                                <button
                                                                                    name="keterangan:jadwal_seni id:{{ $subitem->id }} sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $item->id }} partai:{{ $subitem->partai }} status:proses arena:{{ $arena }}"
                                                                                    class="btn btn-data btn-primary px-3 shadow text-light mx-1">Ganti</button>
                                                                            @endif
                                                                            <button
                                                                                name="keterangan:jadwal_seni sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->id }} status:reset arena:{{ $arena }}"
                                                                                class="btn btn-data btn-secondary px-3 shadow text-light">Reset</button>
                                                                            <button
                                                                                name="keterangan:jadwal_seni sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->id }} status:delete arena:{{ $arena }}"
                                                                                class="btn btn-delete btn-danger px-3 ms-1 shadow text-light">Delete</button>
                                                                            {{-- @if ($item->status === 'pending')
                                                                            @endif
                                                                            @if ($item->status === 'proses')
                                                                            <button
                                                                                name="keterangan:jadwal_seni p:{{$item->id}} status:finish arena:{{$arena}}"
                                                                                class="btn btn-data btn-warning px-3 shadow text-light">Proses</button>
                                                                            @endif
                                                                            @if ($item->status === 'finish')
                                                                            <button
                                                                                name="keterangan:jadwal_seni p:{{$item->id}} status:finish arena:{{$arena}}"
                                                                                class="btn btn-success px-3 shadow text-light">Selesai</button>
                                                                            @endif --}}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        @if($prestasiData)
                                            <div class="px-5 mb-3">
                                                <h5>Prestasi</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered datatable overflow-auto shadow" id="datatable-3">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th class="bg-light text-center">No</th>
                                                                <th class="bg-light text-center">Partai</th>
                                                                <th class="bg-light text-center">Kelas</th>
                                                                <th class="bg-light text-center">Biru</th>
                                                                <th class="bg-light text-center">Merah</th>
                                                                <th class="bg-light text-center">Skor Biru</th>
                                                                <th class="bg-light text-center">Skor Merah</th>
                                                                <th class="bg-light text-center">Waktu Biru</th>
                                                                <th class="bg-light text-center">Waktu Merah</th>
                                                                <th class="bg-light text-center">Pemenang</th>
                                                                <th class="bg-light text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center align-middle">
                                                            @foreach ($prestasiData as $subitem)
                                                                <tr>
                                                                    @php
                                                                        $merah = PersertaModel::where('id', $subitem->merah)->first();
                                                                        $biru = PersertaModel::where('id', $subitem->biru)->first();
                                                                        $kelas = kelas::where('id', $item->kelas)->value('name');
                                                                        $category = category::where('id', $item->category)->value('name');
                                                                        $datar = score::where('id_perserta', $item->id)->get();
                                                                        $actual = 9.9;
                                                                        $sc = [];
                                                                    @endphp
                                                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                                                    <td class="text-center">{{ $subitem->partai }}</td>
                                                                    <td>{{ $kelas }}</td>
                                                                    <td class="">{{ $biru->name }}</td>
                                                                    <td class="">{{ $merah->name }}</td>
                                                                    {{-- <td class="">{{$category}}</td> --}}
                                                                    <td class="text-primary text-center">
                                                                        {{ $subitem->score_biru }} </br>
                                                                        {{ $subitem->deviasi_biru }}
                                                                    </td>
                                                                    <td class="text-danger text-center">
                                                                        {{ $subitem->score_merah }} </br>
                                                                        {{ $subitem->deviasi_merah }}
                                                                    </td>
                                                                    <td class="text-primary text-center">{{ $subitem->timer_biru }}</td>
                                                                    <td class="text-primary text-center">{{ $subitem->timer_merah }}</td>
                                                                    <td class="text-primary text-center">{{ $subitem->pemenang }}</td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-center p-0">
                                                                            @if(!empty($subitem->keterangan) && $subitem->keterangan == "prestasi")
                                                                                <button
                                                                                    name="keterangan:jadwal_seni id:{{ $subitem->id }} sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->biru }} partai:{{ $subitem->partai }} status:proses arena:{{ $arena }}"
                                                                                    class="btn btn-data btn-primary px-3 shadow text-light mx-1">Biru</button>
                                                                                <button
                                                                                    name="keterangan:jadwal_seni id:{{ $subitem->id }} sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->merah }} partai:{{ $subitem->partai }} status:proses arena:{{ $arena }}"
                                                                                    class="btn btn-data btn-danger px-3 shadow text-light mx-1">Merah</button>
                                                                            @else
                                                                                <button
                                                                                    name="keterangan:jadwal_seni id:{{ $subitem->id }} sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $item->id }} partai:{{ $subitem->partai }} status:proses arena:{{ $arena }}"
                                                                                    class="btn btn-data btn-primary px-3 shadow text-light mx-1">Ganti</button>
                                                                            @endif
                                                                            <button
                                                                                name="keterangan:jadwal_seni sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->id }} status:reset arena:{{ $arena }}"
                                                                                class="btn btn-data btn-secondary px-3 shadow text-light">Reset</button>
                                                                            <button
                                                                                name="keterangan:jadwal_seni sesi:{{ $sesi }} poll:{{ $pollId }} p:{{ $subitem->id }} status:delete arena:{{ $arena }}"
                                                                                class="btn btn-delete btn-danger px-3 ms-1 shadow text-light">Delete</button>
                                                                            {{-- @if ($item->status === 'pending')
                                                                            @endif
                                                                            @if ($item->status === 'proses')
                                                                            <button
                                                                                name="keterangan:jadwal_seni p:{{$item->id}} status:finish arena:{{$arena}}"
                                                                                class="btn btn-data btn-warning px-3 shadow text-light">Proses</button>
                                                                            @endif
                                                                            @if ($item->status === 'finish')
                                                                            <button
                                                                                name="keterangan:jadwal_seni p:{{$item->id}} status:finish arena:{{$arena}}"
                                                                                class="btn btn-success px-3 shadow text-light">Selesai</button>
                                                                            @endif --}}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="px-5 mb">
                                            <a href="redirect?arena={{ $arena }}&sesi={{ $sesi }}&poll={{ $pollId }}&role=ranking&kategori=seni"
                                                type="button" class="btn btn-success shadow" value="{{ $arena }}">Rekap
                                                Ranking
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Information Table -->

        {{-- Modal Section --}}
        <div class="modal fade" id="addpeserta" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="overflow:hidden;">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.addpesertas', ['arena' => $arena]) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Peserta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="partaiId">Partai</label>
                                        <input type="number" name="partai" id="partaiId" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type="hidden" value="{{ $sesi }}" name="sesi" />
                                        <div class="fs-5 text-primary">Tim Biru</div>
                                        <select class="js-example-basic-single" style="width:170px;" name="biru" id="biru">
                                            @foreach ($PesertaAll as $item)
                                                @php
                                                    $kelas = kelas::where('id', $item->kelas)->value('name');
                                                    $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                @endphp
                                                <option>{{ $item->name }} || {{ $kontigen->kontigen ?? ' ' }} ||
                                                    {{ $kelas ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- <input type="text" name="biru" id="biru" class="form-control" list="peserta-list">
                                                                                                                                                                                                                                                                        <datalist id="peserta-list">
                                                                                                                                                                                                                                                                        </datalist> -->
                                    </div>
                                    <div class="col">
                                        <label for="biru" class="fs-5 text-danger">Tim Merah</label>
                                        <select class="js-example-basic-single" style="width:170px;" name="merah"
                                            id="merah">
                                            @foreach ($PesertaAll as $item)
                                                @php
                                                    $kelas = kelas::where('id', $item->kelas)->value('name');
                                                    $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                @endphp
                                                <option>{{ $item->name }} || {{ $kontigen->kontigen ?? ' ' }} ||
                                                    {{ $kelas ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- <input type="text" name="merah" id="merah" class="form-control" list="peserta-list"> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="editPeserta" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="overflow:hidden;">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.editjadwal') }}">
                    @csrf
                    <input type="hidden" id="id_new_jadwal" name="newJadwalId" value="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Jadwal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="new_partai">Partai</label>
                                        <input type="number" name="partai" id="new_partai" class="form-control">
                                    </div>
                                </div>
                                <div class="fs-5 mb-2">
                                    Ganti Sesi
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div>
                                            <label for="id_new_session" class="fs-5 form-label">Sesi</label>
                                        </div>
                                        <select class="js-example-basic-single" style="width:100%;" name="newSession"
                                            id="id_new_session">
                                            @if (!$sesi)
                                                <option value="0" selected="selected">Tidak Ada Sesi</option>
                                            @endif
                                            @foreach ($dataSesi as $item)
                                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="fs-5 mb-2">
                                    Pindah Arena
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div>
                                            <label for="id_new_arena" class="fs-5">Arena</label>
                                        </div>
                                        <select class="js-example-basic-single" style="width:100%;" name="new_arena"
                                            id="id_new_arena">
                                            @foreach ($listArena as $item)
                                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <section>
                                    <div class="fs-5 mb-2">
                                        Tipe Pertandingan
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <div>
                                                <label for="id_tipe_tanding" class="fs-5">Arena</label>
                                            </div>
                                            <select class="js-example-basic-single" style="width:100%;" name="tipe_tanding"
                                                id="id_tipe_tanding">
                                                <option value="">Standard</option>
                                                <option value="semi-final">Semi Final</option>
                                                <option value="final">Final</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="fs-5 text-primary">Tim Biru</div>
                                        <select class="js-example-basic-single-peserta" style="width:170px;" name="biruEdit"
                                            id="biruEdit">
                                            @foreach ($PesertaAll as $item)
                                                @php
                                                    $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                @endphp
                                                <option value="{{ $item->id }}">{{ $item->name }} ||
                                                    {{ $kontigen->kontigen ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="merahEdit" class="fs-5 text-danger">Tim Merah</label>
                                        <select class="js-example-basic-single-peserta" style="width:170px;"
                                            name="merahEdit" id="merahEdit">
                                            @foreach ($PesertaAll as $item)
                                                @php
                                                    $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                @endphp
                                                <option value="{{ $item->id }}">{{ $item->name }} ||
                                                    {{ $kontigen->kontigen ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="addSesi" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="overflow:hidden;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Sesi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="">List Sesi</div>
                            <div class="" style="height: 10rem;">
                                <div class="mh-100 overflow-auto">
                                    @foreach ($dataSesi as $items)
                                        <div
                                            class="border border-primary rounded px-4 mb-2 shadow align-items-center d-flex justify-content-between py-2">
                                            <div>{{ $items->name }}</div>
                                            <button class="btn-delete-sesi btn btn-danger" name="id" value="{{ $items->id }}">
                                                Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                    <form method="POST" action="{{ route('admin.addSesi', ['arena' => $arena]) }}">
                                        @csrf
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="sesi">Nama Sesi</label>
                                    <input type="text" name="nama" class="form-control" placeholder="final....">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="tipe">Tipe Sesi <span class="text-danger">*untuk Tanding</span> </label>
                                    <select name="tipe" id="tipe" class="form-control">
                                        <option value="biasa">Biasa</option>
                                        <option value="semifinal">Semifinal</option>
                                        <option value="final">Final</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="addPoll" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="overflow:hidden;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Polling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="">List Polling</div>
                            <div class="" style="height: 10rem;">
                                <div class="mh-100 overflow-auto">
                                    @foreach ($dataPoll as $items)
                                        <div
                                            class="border border-primary rounded px-4 mb-2 shadow align-items-center d-flex justify-content-between py-2">
                                            <div>{{ $items->name }}</div>
                                            <button class="btn-delete-poll btn btn-danger" name="id" value="{{ $items->id }}">
                                                Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                    <form method="POST" action="{{ route('admin.addPoll', ['arena' => $arena]) }}">
                                        @csrf
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="sesi">Nama Poll</label>
                                    <input type="text" name="nama" class="form-control" placeholder="final....">
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col">
                                    <label for="tipe">Tipe Poll <span class="text-danger">*untuk Tanding</span> </label>
                                    <select name="tipe" id="tipe" class="form-control">
                                        <option value="biasa">Biasa</option>
                                        <option value="semifinal">Semifinal</option>
                                        <option value="final">Final</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="addPesertaSeni" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="overflow:hidden;">
            <div class="modal-dialog ">
                <form method="POST" action="{{ route('admin.addpesertas', ['arena' => $arena]) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Peserta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="partaiId">Partai</label>
                                        <input type="number" name="partai" id="partaiId" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        {{-- Seni Add --}}
                                        <div class="fs-5">Nama Peserta Biru</div>
                                        <input type="hidden" value="{{ $sesi }}" name="sesi" />
                                        <input type="hidden" value="seni" name="tipe" />
                                        <select class="js-select2 " style="width: 100%;" name="pesertaSenib"
                                            id="pesertaSenib">
                                            <!-- @foreach ($PesertaAll as $item)
                                                                        @php
                                                                            $kelas = kelas::where('id', $item->kelas)->first()->name;
                                                                            $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                                        @endphp
                                                                        @endforeach -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <div class="fs-5">Nama Peserta Merah <span class="text-danger">*Untuk Battle</span>
                                        </div>
                                        <select class="js-select2 " style="width: 100%;" name="pesertaSenim"
                                            id="pesertaSenim">
                                            <!-- @foreach ($PesertaAll as $item)
                                                                        @php
                                                                            $kelas = kelas::where('id', $item->kelas)->first()->name;
                                                                            $kontigen = KontigenModel::where('id', $item->id_kontigen)->first();
                                                                        @endphp
                                                                        @endforeach -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <div class="fs-5">Jurus <span class="text-danger">* Seni Tunggal</span></div>
                                        <select name="kelas" class="plain-select2" style="width:100%;" id="">
                                            @foreach ($kelasSeni as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <div class="fs-5">Tipe Pertandingan</div>
                                        <select name="tipe-seni" class="form-control" id="">
                                            <option value="pemasalan">Pemasalan</option>
                                            <option value="prestasi">Prestasi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <div class="fs-5">Poll</div>
                                        <select name="poll" class="form-control" id="">
                                            {{-- <option value={{ null }}>Tidak ada Poll</option> --}}
                                            @if ($dataPoll->count() != 0)
                                                @foreach ($dataPoll as $items)
                                                    <option value="{{ $items->id }}">{{ $items->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="importJadwal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ url('/admin/excel-jadwal ') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="arena" value="{{ $arena }}">
                            <input type="hidden" name="sesi" value="{{ $sesi }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                            </div>
                            <div class="modal-body">
                                <input type="file" class="form-control" name="file">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- Modal Clear --}}
        <div class="modal fade" id="clearJadwal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ url('/admin/clearJadwal ') }}" method="POST">
                            @csrf
                            <input type="hidden" name="arena" value="{{ $arena }}">
                            <input type="hidden" name="sesi" value="{{ $sesi }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadModalLabel">Hapus Jadwal</h5>
                            </div>
                            <div class="modal-body">
                                Bersihkan jadwal sesi ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteJadwal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ url('/admin/delete-jadwal ') }}" method="POST">
                            @csrf
                            <input type="hidden" name="arena" value="{{ $arena }}">
                            <input type="hidden" name="sesi" value="{{ $sesi }}">
                            <input type="hidden" name="id_peserta_delete" value="0">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadModalLabel">Hapus Data</h5>
                            </div>
                            <div class="modal-body">
                                Hapus Jadwal Ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
        <script>
            let identifier = [
                'biru',
                'biruEdit',
                'merah',
                'merahEdit',
            ]

            $(document).ready(async function () {
                identifier.forEach((data, index) => {
                    $(document).on('input', `[aria-controls="select2-${data}-results"]`, function () {
                        //searchSelect(data, $(this).val());
                    });
                });
            })

            var timeoutQueue = null;

            function searchSelect(identifier, value) {
                var duration = 1000;
                clearTimeout(timeoutQueue);

                timeoutQueue = setTimeout(() => {
                    $.ajax({
                        url: '/search-peserta',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({
                            identifier: identifier,
                            input: value
                        }),
                        success: function (response) {
                            //console.log('Search response:', response);

                            $(`#${identifier}`).html('');

                            // response.result is already an object, no need to parse
                            let contentObject = response.result;
                            console.log(contentObject);
                            contentObject.forEach((data, index) => {
                                $(`#${identifier}`).append(
                                    `<option value="${data.id}">${data.content}</option>`)
                            })
                        },
                        error: function (xhr, status, error) {
                            console.error('Search error:', error);
                        }
                    })
                }, duration);
            }

            function addAndSelect($select, value, text) {
                if ($select.find(`option[value="${value}"]`).length === 0) {
                    let newOption = new Option(text, value, true, true);
                    $select.append(newOption);
                }
                $select.val(value).trigger('change');
            }

            function asignEdit(biru, merah, partai, jadwalId, namaBiru, namaMerah) {
                //alert(`${biru}, ${merah}, ${namaBiru}, ${namaMerah}`);

                let arena = $('#arenaName').val();
                let arenaId = {{ $arena }};
                let sessionId = $('#sessionId').val();
                let sesi = $('#sessionId').val() === "" ? "Tidak ada Sesi" : $('#current_session_name').val();

                $('#id_new_jadwal').val(jadwalId);
                $('#new_partai').val(partai);
                $('#id_current_arena').val(arena);
                $('#id_new_arena').val(arenaId).trigger('change');
                $('#id_current_session').val(sesi);
                // alert(`${biru} + ${merah}`);

                addAndSelect($('#biruEdit'), biru, namaBiru);
                addAndSelect($('#merahEdit'), merah, namaMerah);

                if (sessionId) {
                    $('#id_new_session').val(sessionId).trigger('change');
                } else {
                    $('#id_new_session').val(0).trigger('change');
                }

            }

            $(document).ready(function () {
                let table = new DataTable('#datatable');
                let table3 = new DataTable('.datatable');
                // let table2 = new DataTable('#datatable-2');
                if ($('#arenaType').val() === 'Tanding') {
                    $('.js-example-basic-single').select2({
                        ajax: {
                            url: '/search-peserta',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            data: function (params) {
                                return JSON.stringify({
                                    input: params.term,
                                })
                            },
                            delay: 1000,
                            processResults: function (response, params) {
                                return {
                                    results: response.items
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Search error:', error);
                            }
                        },
                        placeholder: 'Search Peserta',
                        dropdownParent: $('#addpeserta')
                    });

                    $('#editPeserta .js-example-basic-single-peserta').select2({
                        ajax: {
                            url: '/search-peserta',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            data: function (params) {
                                return JSON.stringify({
                                    input: params.term,
                                })
                            },
                            delay: 1000,
                            processResults: function (response, params) {
                                return {
                                    results: response.items
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Search error:', error);
                            }
                        },
                        placeholder: 'Search Peserta',
                        dropdownParent: $('#editPeserta')
                    });

                    $('#editPeserta .js-example-basic-single').select2({
                        dropdownParent: $('#editPeserta')
                    });

                    $('#id_new_arena .js-example-basic-single').select2({
                        ajax: {
                            url: '/search-peserta',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            data: function (params) {
                                return JSON.stringify({
                                    input: params.term,
                                })
                            },
                            delay: 1000,
                            processResults: function (response, params) {
                                return {
                                    results: response.items
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Search error:', error);
                            }
                        },
                        placeholder: 'Search Peserta',
                        dropdownParent: $('#editPeserta')
                    });
                } else {
                    $('.js-select2').select2({
                        ajax: {
                            url: '/search-peserta',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            data: function (params) {
                                return JSON.stringify({
                                    input: params.term,
                                })
                            },
                            delay: 1000,
                            processResults: function (response, params) {
                                return {
                                    results: response.items
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Search error:', error);
                            }
                        },
                        placeholder: 'Search Peserta',
                        dropdownParent: $('#addPesertaSeni')
                    });

                    $('.plain-select2').select2({
                        dropdownParent: $('#addPesertaSeni')
                    });
                }
            });

            $('.btn-ranking').on('click', function () {
                let dataArena = $(this).attr('value');

                window.location.href = `redirect?arena=${dataArena}&role=ranking&kategori=seni`;
            });

            $('.btn-delete').on('click', function () {
                let data = [];
                let name = $(this).attr('name');

                name.split(' ').forEach(function (item) {
                    var parts = item.split(':');
                    data[parts[0]] = parts[1];
                });

                $("[name='id_peserta_delete']").val(data.p);

                $('#deleteJadwal').modal('show');
            })

            $('.btn-delete-sesi').on('click', function () {
                //alert($(this).attr('value'));
                let data = $(this).attr('value');
                fetch('{{ route('admin.deleteSesi') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: data
                    })
                })
                window.location.reload();
            });

            $('.btn-delete-poll').on('click', function () {
                //alert($(this).attr('value'));
                let data = $(this).attr('value');
                fetch('{{ route('admin.deletePoll') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: data
                    })
                })
                window.location.reload();
            });
            // new DataTable('#example')
        </script>
    </body>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('scoring-plugin')
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        var tombolDenganKelas = document.querySelectorAll('.btn-data');
        document.addEventListener('DOMContentLoaded', e => {
            $('#biru')
        }, false);
        document.addEventListener('DOMContentLoaded', e => {
            $('#merah')
        }, false);
        var id_arena = {{ $arena }};
        var id_juri = {{ $id_juri }};
        var path_url = `redirect?name=${id_juri}&arena=${id_arena}&role=dewan-tanding`;

        function jadwal() {
            window.location.href = path_url;
        }

        function reload() {
            window.location.reload();
        }
        // Loop melalui semua tombol dan tambahkan event listener
        tombolDenganKelas.forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"

                // Membagi nilai atribut "name" menjadi objek JavaScript
                var data = {};
                nameAttribute.split(' ').forEach(function (item) {
                    var parts = item.split(':');
                    data[parts[0]] = parts[1];
                });

                // Sekarang, Anda memiliki data dalam bentuk objek
                console.log(data);

                // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
                function SendData() {
                    fetch('{{ route('dewan.store') }}', {
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
                            if (id_juri > 0) {
                                // setInterval(jadwal, 800);
                                jadwal();
                            } else {
                                // setInterval(reload, 800);
                                reload();
                            }
                        })
                        .catch(error => {
                            // Tangani kesalahan jika ada
                        });
                }
                SendData();


            });
        });
    </script>
@endpush