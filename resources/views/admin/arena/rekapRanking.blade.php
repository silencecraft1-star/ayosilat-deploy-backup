@extends('layout.master')

@push('plugin.styles')

@endpush
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
@section('content')
@php
    use App\jadwal_group;
    use App\PersertaModel;
    use App\KontigenModel;
    $dataRanking = jadwal_group::where('arena', $arena)->where('id_poll', $poll)->orderBy('score_merah', 'DESC')->orderBy('deviasi_merah', 'DESC')->get();
@endphp
<div class="m-5">
        <div>
        <a href="/">
            <button class="px-5 py-2 bg-neutral-200 hover:bg-neutral-100 active:bg-neutral-300 shadow-xl transition-all">Kembali</button>
        </a>
        </div>
        <div class="text-center mb-3 text-3xl">
            Ranking Seni
        </div>
        <div class="overflow-auto shadow-xl">
            <table class="table-primary w-full rounded shadow-xl">
                <thead>
                    <tr>
                        <th class="text-center bg-blue-500 py-2 text-xl text-neutral-100 px-3 border border-neutral-400">Ranking</th>
                        <th class="text-center bg-blue-500 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Nama</th>
                        <th class="text-center bg-blue-500 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Kontigen</th>
                        <th class="text-center bg-blue-500 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Skor</th>
                        <th class="text-center bg-blue-500 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Waktu</th>
                        <th class="text-center bg-blue-500 py-2 text-xl text-neutral-100 px-5 border border-neutral-400">Deviation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataRanking as $index => $items)
                    @php
                        $dataPeserta = PersertaModel::where('id', $items->merah)->first();
                        if(!$dataPeserta) {
                            $nama = "";
                        }
                        else {
                            $nama = $dataPeserta->name;
                        }
                        
                        $kontigen = KontigenModel::where('id', $dataPeserta->id_kontigen ?? 2)->first()->kontigen;
                    @endphp 
                    <tr>    
                        <td class="text-center border text-xl py-2 border-neutral-400">{{$index + 1}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-400">{{$nama}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-400">{{$kontigen}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-400">{{$items->score_merah}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-400">{{$items->timer_merah ?? '00:00'}}</td>
                        <td class="text-center border text-xl py-2 border-neutral-400">{{$items->deviasi_merah ?? '0'}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection