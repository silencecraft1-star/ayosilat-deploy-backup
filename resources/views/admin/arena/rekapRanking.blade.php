@extends('layout.master')

@push('plugin.styles')

@endpush
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
@section('content')
@php
    use App\jadwal_group;
    use App\PersertaModel;
    use App\KontigenModel;
    use App\arena;
    use App\PollingModel;

    // Resolve Arena and Poll names
    $arenaData = arena::where('id', $arena)->first();
    $namaArena = $arenaData->name ?? ('Arena ' . $arena);

    $pollData = PollingModel::where('id', $poll)->first();
    $namaPoll = $pollData->name ?? ('Poll ' . $poll);

    // Get Pemasalan (only from Biru)
    $dataPemasalan = jadwal_group::where('arena', $arena)
        ->where('id_poll', $poll)
        ->where('keterangan', 'pemasalan')
        ->orderBy('score_biru', 'DESC')
        ->orderBy('deviasi_biru', 'DESC')
        ->get();

    // Get Prestasi (only Winners)
    $prestasiRaw = jadwal_group::where('arena', $arena)
        ->where('id_poll', $poll)
        ->where('keterangan', 'prestasi')
        ->whereNotNull('pemenang')
        ->get();

    $dataPrestasi = $prestasiRaw->map(function($item) {
        if ($item->pemenang == $item->merah) {
            $item->winner_score = $item->score_merah;
            $item->winner_deviasi = $item->deviasi_merah;
            $item->winner_timer = $item->timer_merah;
        } else {
            $item->winner_score = $item->score_biru;
            $item->winner_deviasi = $item->deviasi_biru;
            $item->winner_timer = $item->timer_biru;
        }
        return $item;
    })->sort(function($a, $b) {
        if ($a->winner_score == $b->winner_score) {
            return $b->winner_deviasi <=> $a->winner_deviasi;
        }
        return $b->winner_score <=> $a->winner_score;
    })->values();

@endphp
<div class="m-5">
    <!-- Header Info -->
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="/">
            <button class="px-5 py-2 bg-neutral-200 hover:bg-neutral-100 active:bg-neutral-300 shadow-xl transition-all rounded">Kembali</button>
        </a>
        <div class="text-right">
            <div class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Rekap Ranking Seni</div>
            <div class="text-2xl font-extrabold text-gray-800">{{ $namaArena }}</div>
            <div class="inline-block mt-1 bg-indigo-100 text-indigo-700 text-sm font-bold px-3 py-1 rounded-full">
                📋 {{ $namaPoll }}
            </div>
        </div>
    </div>

    <!-- TABEL PRESTASI -->
    <div class="mb-10">
        <div class="text-center mb-3 text-3xl font-bold text-blue-800">
            Ranking Seni - PRESTASI
        </div>
        <div class="overflow-auto shadow-xl rounded">
            <table class="table-primary w-full rounded shadow-xl">
                <thead>
                    <tr>
                        <th class="text-center bg-blue-600 py-2 text-xl text-neutral-100 px-3 border border-neutral-400">Ranking</th>
                        <th class="text-center bg-blue-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Nama</th>
                        <th class="text-center bg-blue-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Kontigen</th>
                        <th class="text-center bg-blue-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Skor</th>
                        <th class="text-center bg-blue-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Waktu</th>
                        <th class="text-center bg-blue-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Deviasi Final</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataPrestasi as $index => $items)
                    @php
                        $dataPeserta = PersertaModel::where('id', $items->pemenang)->first();
                        $nama = $dataPeserta ? $dataPeserta->name : "";
                        $kontigen = $dataPeserta ? KontigenModel::where('id', $dataPeserta->id_kontigen ?? 2)->first()->kontigen : "";
                    @endphp 
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition-colors">    
                        <td class="text-center border text-xl py-2 border-neutral-300 font-bold text-gray-700">{{$index + 1}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$nama}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$kontigen}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300 font-bold text-blue-700">{{$items->winner_score}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$items->winner_timer ?? '00:00'}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$items->winner_deviasi ?? '0'}}</td>
                    </tr>
                    @endforeach
                    @if(count($dataPrestasi) == 0)
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data prestasi / pemenang</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL PEMASALAN -->
    <div>
        <div class="text-center mb-3 text-3xl font-bold text-green-800">
            Ranking Seni - PEMASALAN
        </div>
        <div class="overflow-auto shadow-xl rounded">
            <table class="table-primary w-full rounded shadow-xl">
                <thead>
                    <tr>
                        <th class="text-center bg-green-600 py-2 text-xl text-neutral-100 px-3 border border-neutral-400">Ranking</th>
                        <th class="text-center bg-green-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Nama</th>
                        <th class="text-center bg-green-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Kontigen</th>
                        <th class="text-center bg-green-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Skor</th>
                        <th class="text-center bg-green-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Waktu</th>
                        <th class="text-center bg-green-600 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Deviasi Final</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataPemasalan as $index => $items)
                    @php
                        // Ambil data sudut Biru karena Merah dikosongkan
                        $dataPeserta = PersertaModel::where('id', $items->biru)->first();
                        $nama = $dataPeserta ? $dataPeserta->name : "";
                        $kontigen = $dataPeserta ? KontigenModel::where('id', $dataPeserta->id_kontigen ?? 2)->first()->kontigen : "";
                    @endphp 
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-green-50 transition-colors">    
                        <td class="text-center border text-xl py-2 border-neutral-300 font-bold text-gray-700">{{$index + 1}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$nama}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$kontigen}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300 font-bold text-green-700">{{$items->score_biru}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$items->timer_biru ?? '00:00'}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-300">{{$items->deviasi_biru ?? '0'}}</td>
                    </tr>
                    @endforeach
                    @if(count($dataPemasalan) == 0)
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data pemasalan</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection