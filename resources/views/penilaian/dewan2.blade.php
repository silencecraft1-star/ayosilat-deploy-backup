<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dewan</title>
    @php
        use App\score;
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\arena;
        use App\jadwal_group;
        $id_arena = $arena;
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $id_juri = $id_juri;
        $tim_merahs = PersertaModel::where('id', $setting->merah)->first();
        $tim_birus = PersertaModel::where('id', $setting->biru)->first();
        $tim_biru = $tim_birus->id;
        $tim_merah = $tim_merahs->id;
        $kontigenBiru = KontigenModel::where('id', $tim_birus->id_kontigen)->first();
        $kontigenMerah = KontigenModel::where('id', $tim_merahs->id_kontigen)->first();
        $babak = Setting::where('arena', $arena)->first();
        $babak = $babak->babak;
        $arena = arena::where('id', $setting->arena)->first();
        $partai = $setting->partai;
        $jadwalData = jadwal_group::where('id', $setting->jadwal)->first();
        //dd($id_arena, $setting->partai, $setting->biru, $setting->merah, $jadwalData);
        // dd($jadwalData);
        $settingData = Setting::where('keterangan', 'admin-setting')->first();

        if ($settingData) {
            $imgData1 = $settingData->babak ?? '';
            $imgData2 = $settingData->partai ?? '';
        }

    @endphp
</head>
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

    .bb2 {
        background-color: #3F83F8;
    }

    .br2 {
        background-color: #F05252;
    }

    .bb {
        background-color: blue;
    }

    .br {
        background-color: red;
    }

    .border-black {
        border: 1px solid black;
    }

    .border-black-2 {
        border: 2px solid black;
    }

    .border-blue {
        border: 2px solid blue;
    }

    .border-red {
        border: 2px solid red;
    }
</style>

<body>
    <!-- Header Section -->
    <section>
        <div class="w-full bg-blue-600 mb-3 shadow-lg shadow-gray-400 py-2">
            <div class="lg:grid lg:grid-cols-3 h-full py-1">
                <div class="flex items-center justify-center lg:justify-start lg:ms-5 lg:mb-0">
                    <button class="
                    bg-slate-300 shadow-lg shadow-gray-600 px-10 py-2 rounded 
                    hover:px-11 hover:py-3 hover:bg-slate-400 hover:shadow-transparent 
                    transition-all active:bg-slate-600 w-full lg:w-40 mx-10 lg:mx-0">
                        <a href="{{ url('login-juri') }}">Log Out</a>
                    </button>
                </div>
                <div class="flex items-center justify-center h-100 text-5xl text-white mb-3 lg:mb-0">
                    {{ $arena->name }}
                </div>
                <div class="lg:flex lg:justify-end h-full lg:me-5 hidden">
                    <div class="h-full flex items-center flex-wrap gap-2">
                        <img src="{{ asset("/assets/Assets/uploads/$imgData1") }}" class="size-12 " alt="">
                        <img src="{{ asset("/assets/Assets/uploads/$imgData2") }}" class="size-12" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Arena Info Section -->

    <!-- Score Detail Section -->
    <section>
        <!-- Kontigen and Player Info Section -->
        <div class="lg:grid lg:grid-cols-5 px-6 mb-4">
            <div class="col-span-2">
                <div class="text-start">
                    <div id="kontigen-biru" class="text-blue-600 text-2xl font-semibold uppercase">
                        {{$kontigenBiru->kontigen}}
                    </div>
                    <div id="nama-biru" class="text-2xl uppercase">{{ $tim_birus->name }}</div>
                </div>
            </div>
            <div class="col-span-1 items-center justify-center">
                <div class="flex items-center justify-center  flex-col flex-wrap mb-2 gap-1">
                    <div
                        class="text-4xl font-semibold uppercase text-center mb-3 bg-gradient-to-r from-amber-500 to-amber-700 text-transparent bg-clip-text">
                        Partai <span id="partai-label">{{ $setting->partai }}</span>
                    </div>
                </div>
            </div>
            <div class="col-span-2">
                <div class="text-end">
                    <div id="kontigen-merah" class="text-red-600 text-2xl font-semibold uppercase">
                        {{$kontigenMerah->kontigen}}
                    </div>
                    <div id="nama-merah" class="text-2xl uppercase">{{ $tim_merahs->name }}</div>
                </div>
            </div>
        </div>
        <!-- Score Table Section -->
        <div class="lg:grid lg:grid-cols-5 px-6 mb-5">
            <!-- Blue Score Table -->
            <div class="col-span-2 relative overflow-x-auto rounded shadow-lg shadow-slate-400">
                <table class="w-full">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="py-3 border border-black text-center border border-slate-700">Jatuhan</th>
                            <th class="py-3 border border-black text-center border border-slate-700">Binaan</th>
                            <th class="py-3 border border-black text-center border border-slate-700">Teguran</th>
                            <th class="py-3 border border-black text-center border border-slate-700">Peringatan</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                            $jatuh = score::where('keterangan', 'jatuh')
                                ->where('babak', '1')
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->count();
                            $bina = score::where('keterangan', 'binaan')
                                ->where('babak', '1')
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->count();
                            $teguran = score::where('keterangan', 'teguran')
                                ->where('babak', '1')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $peringatan = score::where('keterangan', 'peringatan')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                        @endphp
                        <tr>
                            <td id="b1jatuh" class="py-4 border border-black fw-bold text-3xl">{{ $jatuh }}x</td>
                            <td id="b1binaan" class="py-4 border border-black fw-bold text-3xl">{{ $bina }}x</td>
                            <td id="b1teguran" class="py-4 border border-black fw-bold text-3xl">{{ $teguran }}x</td>
                            <td id="bperingatan" class="py-4 border border-black fw-bold text-3xl text-4xl" rowspan="3">
                                {{ $peringatan }}x
                            </td>
                        </tr>
                        @php
                            $jatuh = score::where('keterangan', 'jatuh')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $bina = score::where('keterangan', 'binaan')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $teguran = score::where('keterangan', 'teguran')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $peringatan = score::where('keterangan', 'peringatan')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                        @endphp
                        <tr>
                            <td id="b2jatuh" class="py-4 border border-black fw-bold text-3xl">{{ $jatuh }}x</td>
                            <td id="b2binaan" class="py-4 border border-black fw-bold text-3xl">{{ $bina }}x</td>
                            <td id="b2teguran" class="py-4 border border-black fw-bold text-3xl">{{ $teguran }}x</td>
                            {{-- <td class="py-4 border border-black">{{$peringatan}}x</td> --}}
                        </tr>
                        @php
                            $jatuh = score::where('keterangan', 'jatuh')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $bina = score::where('keterangan', 'binaan')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $teguran = score::where('keterangan', 'teguran')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                            $peringatan = score::where('keterangan', 'peringatan')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_biru)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->count();
                        @endphp
                        <tr>
                            <td id="b3jatuh" class="py-4 border border-black fw-bold text-3xl">{{ $jatuh }}x</td>
                            <td id="b3binaan" class="py-4 border border-black fw-bold text-3xl">{{ $bina }}x</td>
                            <td id="b3teguran" class="py-4 border border-black fw-bold text-3xl">{{ $teguran }}x</td>
                            {{-- <td class="py-4 border border-black">{{$peringatan}}x</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Babak Section -->
            <div class="col-span-1 py-1 ">
                <div class="flex justify-center mb-5">
                    <div class="bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 p-1 w-1/2 h-11 rounded">
                        <div class="bg-white rounded">
                            <div
                                class="bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 bg-clip-text text-transparent">
                                <div class="text-center text-3xl font-semibold">
                                    BABAK
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center px-3">
                    <table class="w-full shadow-lg shadow-gray-400">
                        <tbody class="text-center">
                            <tr>
                                <td id="babak1"
                                    class="border-1 border-yellow-500 py-3 @if ($babak == '1') bg-gradient-to-r from-orange-400 to-yellow-500 @endif">
                                    I</td>
                            </tr>
                            <tr>
                                <td id="babak2"
                                    class="border-1 border-yellow-500  py-3 @if ($babak == '2') bg-gradient-to-r from-orange-400 to-yellow-500 @endif">
                                    II</td>
                            </tr>
                            <tr>
                                <td id="babak3"
                                    class="border-1 border-yellow-500 py-3 @if ($babak == '3') bg-gradient-to-r from-orange-400 to-yellow-500 @endif">
                                    III</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Red Score TABLE -->
            <div class="col-span-2 relative overflow-x-auto rounded shadow-lg shadow-slate-400">
                <table class="w-full">
                    <thead class="bg-red-500 text-white">
                        <tr>
                            <th class="py-3 text-center border border-black">Peringatan</th>
                            <th class="py-3 text-center border border-black">Teguran</th>
                            <th class="py-3 text-center border border-black">Binaan</th>
                            <th class="py-3 text-center border border-black">Jatuhan</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                            $jatuh = score::where('keterangan', 'jatuh')
                                ->where('babak', '1')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $bina = score::where('keterangan', 'binaan')
                                ->where('babak', '1')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $teguran = score::where('keterangan', 'teguran')
                                ->where('babak', '1')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $peringatan = score::where('keterangan', 'peringatan')
                                ->where('id_perserta', $tim_merah)
                                ->where('partai', $partai)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                        @endphp
                        <tr>
                            <td id="mperingatan" class="py-4 border border-black fw-bold text-4xl" rowspan="3">
                                {{ $peringatan }}x
                            </td>
                            <td id="m1teguran" class="py-4 border border-black fw-bold text-3xl">{{ $teguran }}x</td>
                            <td id="m1binaan" class="py-4 border border-black fw-bold text-3xl">{{ $bina }}x</td>
                            <td id="m1jatuh" class="py-4 border border-black fw-bold text-3xl">{{ $jatuh }}x</td>
                        </tr>
                        @php
                            $jatuh = score::where('keterangan', 'jatuh')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $bina = score::where('keterangan', 'binaan')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $teguran = score::where('keterangan', 'teguran')
                                ->where('babak', '2')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $peringatan = score::where('keterangan', 'peringatan')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                        @endphp
                        <tr>
                            {{-- <td class="py-4 border border-black">{{$peringatan}}x</td> --}}
                            <td id="m2teguran" class="py-4 border border-black fw-bold text-3xl">{{ $teguran }}x</td>
                            <td id="m2binaan" class="py-4 border border-black fw-bold text-3xl">{{ $bina }}x</td>
                            <td id="m2jatuh" class="py-4 border border-black fw-bold text-3xl">{{ $jatuh }}x</td>
                        </tr>
                        @php
                            $jatuh = score::where('keterangan', 'jatuh')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $bina = score::where('keterangan', 'binaan')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $teguran = score::where('keterangan', 'teguran')
                                ->where('babak', '3')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                            $peringatan = score::where('keterangan', 'peringatan')
                                ->where('partai', $partai)
                                ->where('id_perserta', $tim_merah)
                                ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                    $query->where('id_sesi', $id_sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })->where('arena', $id_arena)
                                ->count();
                        @endphp
                        <tr>
                            {{-- <td class="py-4 border border-black">{{$peringatan}}x</td> --}}
                            <td id="m3teguran" class="py-4 border border-black fw-bold text-3xl">{{ $teguran }}x</td>
                            <td id="m3binaan" class="py-4 border border-black fw-bold text-3xl">{{ $bina }}x</td>
                            <td id="m3jatuh" class="py-4 border border-black fw-bold text-3xl">{{ $jatuh }}x</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- Button Section -->
    <section>
        <div class="lg:grid lg:grid-cols-5 px-6">
            <!-- Button Biru Section -->
            @php
                $jatuh_babak = score::where('keterangan', 'jatuh')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('partai', $partai)
                    ->count();
                $bina_babak = score::where('keterangan', 'binaan')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('partai', $partai)
                    ->count();
                $teguran_babak = score::where('keterangan', 'teguran')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('partai', $partai)
                    ->count();
                $peringatan_babak = score::where('keterangan', 'peringatan')
                    ->where('babak', $setting->babak)
                    ->where('id_perserta', $tim_biru)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('partai', $partai)
                    ->count();
            @endphp
            <div class="col-span-2 flex flex-wrap gap-2 justify-center lg:justify-start mb-5 lg:mb-0">
                <div class="flex flex-wrap flex-col gap-2">
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:jatuh p:3 keterangan:plus"
                        id="kirimData"
                        class="bg-blue-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-blue-400 active:bg-blue-700 button-blue disabled:bg-gray-900">JATUHAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:binaan p:0 keterangan:plus"
                        id="kirimData"
                        class="bg-blue-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-blue-400 active:bg-blue-700 button-blue"
                        @if ($bina_babak === 2) disabled @endif>BINAAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:teguran p:1 keterangan:plus"
                        id="kirimData"
                        class="bg-blue-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-blue-400 active:bg-blue-700 button-blue"
                        @if ($teguran_babak === 2) disabled @endif>TEGURAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:peringatan p:5 keterangan:plus"
                        id="kirimData"
                        class="bg-blue-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-blue-400 active:bg-blue-700 button-blue"
                        @if ($peringatan === 3) disabled @endif>PERINGATAN</button>
                </div>
                <div class="flex flex-wrap flex-col gap-2">
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:jatuh p:3 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-blue-delete">HAPUS
                        JATUHAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:binaan p:0 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-blue-delete">HAPUS
                        BINAAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:teguran p:1 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-blue-delete">HAPUS
                        TEGURAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_biru }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:peringatan p:5 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-blue-delete">HAPUS
                        PERINGATAN</button>
                </div>
            </div>
            <!-- Mid Section -->
            <div class="col-span-1 mb-5 lg:mb-0">
                <div class="flex justify-center gap-3 mb-3">
                    <button type="button" id="btn-veryfication-jatuhan"
                        class="button-jatuhan bg-orange-700 text-white px-16 py-1 text-2xl rounded shadow shadow-gray-600 hover:bg-orange-400 active:bg-orange-800"
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:jatuhan p:on keterangan:notif">
                        Verifikasi <br> Jatuhan
                    </button>
                    <button type="button" id="btn-veryfication-hukuman"
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:hukuman p:on keterangan:notif"
                        class="button-jatuhan bg-orange-700 text-white px-16 py-1 text-2xl rounded shadow shadow-gray-600 hover:bg-orange-400 active:bg-orange-800">
                        Verifikasi <br> Hukuman
                    </button>
                </div>
                <div class="flex justify-center">
                    @php
                        $plus1 = score::where('status', 'plus')
                            ->where('partai', $partai)
                            ->where('arena', $id_arena)
                            ->where('id_perserta', $tim_biru)
                            ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                $query->where('id_sesi', $id_sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->sum('score');
                        $minus1 = score::where('status', 'minus')
                            ->where('partai', $partai)
                            ->where('arena', $id_arena)
                            ->where('id_perserta', $tim_biru)
                            ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                $query->where('id_sesi', $id_sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->sum('score');
                        $score1 = $plus1 - $minus1;
                        $plus2 = score::where('status', 'plus')
                            ->where('partai', $partai)
                            ->where('arena', $id_arena)
                            ->where('id_perserta', $tim_merah)
                            ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                $query->where('id_sesi', $id_sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->sum('score');
                        $minus2 = score::where('status', 'minus')
                            ->where('partai', $partai)
                            ->where('arena', $id_arena)
                            ->where('id_perserta', $tim_merah)
                            ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                                $query->where('id_sesi', $id_sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->sum('score');
                        $score2 = $plus2 - $minus2;
                    @endphp
                    <table>
                        <tbody>
                            <tr>
                                <td id="score1" class="border-1 border-gray-500 px-12 py-10 text-4xl text-blue-500">
                                    {{ $score1 }}
                                </td>
                                <td id="score2" class="border-1 border-gray-500 px-12 py-10 text-4xl text-red-500">
                                    {{ $score2 }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center mt-3">
                    <button id="showPemenang"
                        class="bg-green-600 px-3 py-2 text-white rounded hover:bg-green-400 active:bg-green-800 transition-all duration-100">Tentukan
                        Pemenang</button>
                </div>
            </div>
            @php
                $jatuh_babak = score::where('keterangan', 'jatuh')
                    ->where('babak', $setting->babak)
                    ->where('partai', $partai)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('id_perserta', $tim_merah)
                    ->count();
                $bina_babak = score::where('keterangan', 'binaan')
                    ->where('babak', $setting->babak)
                    ->where('partai', $partai)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('id_perserta', $tim_merah)->where('id_perserta', $tim_merah)
                    ->count();
                $teguran_babak = score::where('keterangan', 'teguran')
                    ->where('babak', $setting->babak)
                    ->where('partai', $partai)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('id_perserta', $tim_merah)->where('id_perserta', $tim_merah)
                    ->count();
                $peringatan_babak = score::where('keterangan', 'peringatan')
                    ->where('babak', $setting->babak)
                    ->where('partai', $partai)
                    ->when($jadwalData->id_sesi ?? null, function ($query, $id_sesi) {
                        $query->where('id_sesi', $id_sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('id_perserta', $tim_merah)->where('id_perserta', $tim_merah)
                    ->count();
            @endphp
            <!-- Button Merah Section -->
            <div class="col-span-2 flex flex-wrap gap-2 justify-center lg:justify-end mb-5 lg:mb-0">
                <div class="flex flex-wrap flex-col gap-2">
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:jatuh p:3 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 lg:px-14 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-red-delete">HAPUS
                        JATUHAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:binaan p:0 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 lg:px-14 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-red-delete">HAPUS
                        BINAAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:teguran p:1 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 lg:px-14 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-red-delete">HAPUS
                        TEGURAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:peringatan p:5 keterangan:minus"
                        id="kirimData"
                        class="bg-gray-500 px-2 lg:px-14 py-4 rounded text-white shadow shadow-gray-500 hover:bg-gray-400 active:bg-gray-700 button-red-delete">HAPUS
                        PERINGATAN</button>
                </div>
                <div class="flex flex-wrap flex-col gap-2">
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:jatuh p:3 keterangan:plus"
                        id="kirimData"
                        class="bg-red-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-red-400 active:bg-red-700 button-red">JATUHAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:binaan p:0 keterangan:plus"
                        id="kirimData"
                        class="bg-red-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-red-400 active:bg-red-700 button-red"
                        @if ($bina_babak === 2) disabled @endif>BINAAN</button>
                    <button
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:teguran p:1 keterangan:plus"
                        id="kirimData"
                        class="bg-red-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-red-400 active:bg-red-700 button-red"
                        @if ($teguran_babak === 2) disabled @endif>TEGURAN</button>
                    <button @if ($peringatan === 3) disabled @endif
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} sesi:{{ $jadwalData->id_sesi ?? null }} status:peringatan p:5 keterangan:plus"
                        id="kirimData"
                        class="bg-red-600 px-12 py-4 rounded text-white shadow shadow-gray-500 hover:bg-red-400 active:bg-red-700 button-red">PERINGATAN</button>
                </div>
                <div class="modal fade" id="vjatuhan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropJatuhan" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 100%">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Jatuhan</h5>
                                <button type="button" class="btn-close" aria-label="Close" id="btn-veryfication-close"
                                    data-bs-dismiss="modal"
                                    name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:jatuhan p:off keterangan:notif">

                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">Keputusan</div>
                                <div class="mb-4">
                                    <div class="text-center text-3xl" id="keputusanjatuhan"></div>
                                </div>
                                <table class="table text-center table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="background-color:rgb(41, 41, 41); color: #f5f5f5;"
                                                class="uppercase text-2xl">Juri 1</th>
                                            <th style="background-color:rgb(41, 41, 41); color: #f5f5f5;"
                                                class="uppercase text-2xl">Juri 2</th>
                                            <th style="background-color:rgb(41, 41, 41); color: #f5f5f5;"
                                                class="uppercase text-2xl">Juri 3</th>
                                            {{-- <th>Keputusan</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="vjatuhan-tbody">
                                        <tr>
                                            <td class="p-2">
                                                <div id="juri1-jatuhan" class="" style="height: 8rem;"></div>
                                            </td>
                                            <td class="p-2">
                                                <div id="juri2-jatuhan" class="" style="height: 8rem;"></div>
                                            </td>
                                            <td class="p-2">
                                                <div id="juri3-jatuhan" class="" style="height: 8rem;"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Tentukan Pemenang Section -->
    <section class="hidden" id="listPemenang">
        <div class="text-center my-3">
            Keterangan Kemenangan
        </div>
        <div class="w-3/4 lg:w-full">
            <div class="flex flex-wrap justify-center gap-1">
                <button
                    class="bg-slate-200 px-3 py-2 w-1/6 rounded border border-gray-400 hover:bg-slate-100 btnmenang active:bg-slate-300"
                    name="W.M.P">W.M.P</button>
                <button
                    class="btnmenang bg-slate-200 px-3 py-2 w-1/6 rounded border border-gray-400 hover:bg-slate-100 active:bg-slate-300"
                    name="Angka">Angka</button>
                <button
                    class="btnmenang bg-slate-200 px-3 py-2 w-1/6 rounded border border-gray-400 hover:bg-slate-100 active:bg-slate-300"
                    name="Teknik">Teknik</button>
                <button
                    class="btnmenang bg-slate-200 px-3 py-2 w-1/6 rounded border border-gray-400 hover:bg-slate-100 active:bg-slate-300"
                    name="Diskualifikasi">Diskualifikasi</button>
                <button
                    class="btnmenang bg-slate-200 px-3 py-2 w-1/6 rounded border border-gray-400 hover:bg-slate-100 active:bg-slate-300"
                    name="Undur Diri">Undur Diri</button>
            </div>
            <div class="flex justify-center">
                <div class="text-center border-1 border-gray-500 my-3 shadow text-2xl px-10 rounded">
                    <div id="status-winner"></div>
                </div>
            </div>
            <div class="flex justify-center mt-3">
                <table class="w-3/4 shadow-lg shadow-gray-500">
                    <thead>
                        <tr>
                            <th class="border text-white text-center bg-blue-500 border-gray-500 text-2xl py-3 w-1/2">
                                Tim Biru</th>
                            <th class="border text-white text-center bg-red-500 border-gray-500 text-2xl py-3 w-1/2">
                                Tim Merah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="table-nama-biru" class="text-center text-xl py-5 border border-gray-400">
                                {{ $tim_birus->name }}
                            </td>
                            <td id="table-nama-merah" class="text-center text-xl py-5 border border-gray-400">
                                {{ $tim_merahs->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3 border border-gray-400" id="container">
                                <button
                                    class="bg-blue-500 btn-winner rounded py-2 hover:bg-blue-400 active:bg-blue-700 text-white w-full"
                                    name="keterangan:jadwal p:{{ $tim_biru }} p1:{{ $tim_merah }} sesi:{{$jadwalData->id_sesi ?? null}} partai:{{ $setting->partai }} status:finish arena:{{ $id_arena }} kalah:{{$tim_merahs->id}} menang:{{ $tim_birus->id }}">Tim
                                    Biru</button>
                            </td>
                            <td class="p-3 border border-gray-400">
                                <button
                                    class="bg-red-500 btn-winner rounded py-2 hover:bg-red-400 active:bg-red-700 text-white w-full"
                                    name="keterangan:jadwal p:{{ $tim_biru }} p1:{{ $tim_merah }} sesi:{{$jadwalData->id_sesi ?? null}} partai:{{ $setting->partai }} status:finish arena:{{ $id_arena }} kalah:{{$tim_birus->id}} menang:{{ $tim_merahs->id }}">Tim
                                    Merah</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" id="vhukuman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabelHukuman" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered w-full" style="max-width: 100%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class=" text-center">Verifikasi Hukuman</h5>
                    <button type="button" class="btn-close" aria-label="Close" id="btn-hukuman-veryfication-close"
                        data-bs-dismiss="modal"
                        name="arena:{{ $id_arena }} juri:{{ $id_juri }} id:{{ $tim_merah }} babak:{{ $setting->babak }} status:hukuman p:off keterangan:notif">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">Keputusan</div>
                    <div class="mb-4">
                        <div class="text-center text-3xl" id="keputusanhukuman"></div>
                    </div>
                    <table class="table text-center table-bordered">
                        <thead>
                            <tr>
                                <th style="background-color:rgb(41, 41, 41); color: #f5f5f5;"
                                    class="uppercase text-2xl">Juri 1</th>
                                <th style="background-color:rgb(41, 41, 41); color: #f5f5f5;"
                                    class="uppercase text-2xl">Juri 2</th>
                                <th style="background-color:rgb(41, 41, 41); color: #f5f5f5;"
                                    class="uppercase text-2xl">Juri 3</th>
                                {{-- <th>Keputusan</th> --}}
                            </tr>
                        </thead>
                        <tbody id="vhukuman-tbody">
                            <tr>
                                <td class="p-2">
                                    <div id="juri1-hukuman" class="" style="height: 8rem;"></div>
                                </td>
                                <td class="p-2">
                                    <div id="juri2-hukuman" class="" style="height: 8rem;"></div>
                                </td>
                                <td class="p-2">
                                    <div id="juri3-hukuman" class="" style="height: 8rem;"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pemenangModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pemenang</h5>
                    </div>
                    <div class="modal-body">
                        test
                    </div>
                    <div class="modal-footer">
                        test2
                    </div>
                </div>
            </div>
        </div>
        <div id="IDarena" name="{{$id_arena}}" class="d-none"></div>
        <input type="hidden" name="{{ $tim_biru }}" id="id_biru">
        <input type="hidden" name="{{ $tim_merah }}" id="id_merah">
        @include('addon.tanding.reload');
        <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
        <script>
            // Temukan semua tombol dengan kelas "btn btn-primary button-blue" atau "btn btn-primary btn btn-secondary button-blue-delete"
            var tombolDenganKelas = document.querySelectorAll('.button-blue, .button-blue-delete , .button-red, .button-red-delete');
            var btnShowmenang = $('#showPemenang');
            var btnwinner = document.querySelectorAll('.btn-winner');
            $(".btnmenang").on('click', function () {
                $("#status-winner").text($(this).attr('name'));
            });
            var arena = $("#IDarena").attr('name');

            function WebSocket() {
                if (window.Echo) {
                    window.Echo.connector.pusher.connection.bind('connected', function () {
                        console.log("Terhubung ke Soketi!");
                    });
                    Echo.channel('dewan-channel')
                        .listen('DewanEvent', (datas) => {
                            var data = datas.message;

                            if (arena == data.arena) {
                                console.log(data);
                                assignDewan(data.data);
                            }
                            // alert(JSON.stringify(datas.message));
                        });
                    Echo.channel('score-channel')
                        .listen('ScoreEvent', (datas) => {
                            var data = datas.message;

                            if (data.arena == arena) {
                                // console.log(data);
                                var idbabak = data.babak;
                                if (idbabak == 1) {
                                    $(`#babak${idbabak}`).css('background-image', 'linear-gradient(to right, #fb923c, #facc15)');
                                    $(`#babak2`).css('background-image', 'linear-gradient(to right, transparent, transparent)');
                                    $(`#babak3`).css('background-image', 'linear-gradient(to right, transparent, transparent)');
                                } else if (idbabak == 2) {
                                    $(`#babak${idbabak}`).css('background-image', 'linear-gradient(to right, #fb923c, #facc15)');
                                    $(`#babak1`).css('background-image', 'linear-gradient(to right, transparent, transparent)');
                                    $(`#babak3`).css('background-image', 'linear-gradient(to right, transparent, transparent)');
                                } else if (idbabak == 3) {
                                    $(`#babak${idbabak}`).css('background-image', 'linear-gradient(to right, #fb923c, #facc15)');
                                    $(`#babak1`).css('background-image', 'linear-gradient(to right, transparent, transparent)');
                                    $(`#babak2`).css('background-image', 'linear-gradient(to right, transparent, transparent)');
                                }

                                assignScore(data);
                            }

                        })
                }
            }

            function assignScore(data) {
                console.log("Updating dewan data:", data);

                // Update kontingen dan nama peserta
                $('#kontigen-biru').text(data.kontigenBiru);
                $('#kontigen-merah').text(data.kontigenMerah);
                $('#nama-biru').text(data.namaBiru);
                $('#nama-merah').text(data.namaMerah);

                // Update untuk tabel pemenang
                $('#table-nama-biru').text(data.namaBiru);
                $('#table-nama-merah').text(data.namaMerah);

                // Update ID tim
                $('#id_biru').val(data.idBiru);
                $('#id_merah').val(data.idMerah);

                $('#score1').text(data.score1);
                $('#score2').text(data.score2);

                // Update semua button biru
                $(".button-blue").each(function () {
                    var arr = $(this).attr('name').split(' ');

                    // Update id tim biru (biasanya di index 2 atau 3, sesuaikan dengan struktur)
                    for (let i = 0; i < arr.length; i++) {
                        if (arr[i].startsWith('id:')) {
                            arr[i] = `id:${data.idBiru}`;
                        }
                        if (arr[i].startsWith('babak:')) {
                            arr[i] = `babak:${data.babak}`;
                        }
                        if (arr[i].startsWith('sesi:')) {
                            // Jika ada data sesi baru, update juga
                            if (data.sesi) {
                                arr[i] = `sesi:${data.sesi}`;
                            }
                        }
                    }

                    $(this).attr('name', arr.join(' '));
                });

                // Update semua button biru delete
                $(".button-blue-delete").each(function () {
                    var arr = $(this).attr('name').split(' ');

                    for (let i = 0; i < arr.length; i++) {
                        if (arr[i].startsWith('id:')) {
                            arr[i] = `id:${data.idBiru}`;
                        }
                        if (arr[i].startsWith('babak:')) {
                            arr[i] = `babak:${data.babak}`;
                        }
                        if (arr[i].startsWith('sesi:')) {
                            if (data.sesi) {
                                arr[i] = `sesi:${data.sesi}`;
                            }
                        }
                    }

                    $(this).attr('name', arr.join(' '));
                });

                // Update semua button merah
                $(".button-red").each(function () {
                    var arr = $(this).attr('name').split(' ');

                    for (let i = 0; i < arr.length; i++) {
                        if (arr[i].startsWith('id:')) {
                            arr[i] = `id:${data.idMerah}`;
                        }
                        if (arr[i].startsWith('babak:')) {
                            arr[i] = `babak:${data.babak}`;
                        }
                        if (arr[i].startsWith('sesi:')) {
                            if (data.sesi) {
                                arr[i] = `sesi:${data.sesi}`;
                            }
                        }
                    }

                    $(this).attr('name', arr.join(' '));
                });

                $(".button-red-delete").each(function () {
                    var arr = $(this).attr('name').split(' ');

                    for (let i = 0; i < arr.length; i++) {
                        if (arr[i].startsWith('id:')) {
                            arr[i] = `id:${data.idMerah}`;
                        }
                        if (arr[i].startsWith('babak:')) {
                            arr[i] = `babak:${data.babak}`;
                        }
                        if (arr[i].startsWith('sesi:')) {
                            if (data.sesi) {
                                arr[i] = `sesi:${data.sesi}`;
                            }
                        }
                    }

                    $(this).attr('name', arr.join(' '));
                });

                // Update button verifikasi
                $(".button-jatuhan").each(function () {
                    var arr = $(this).attr('name').split(' ');

                    for (let i = 0; i < arr.length; i++) {
                        if (arr[i].startsWith('id:')) {
                            arr[i] = `id:${data.idMerah}`;
                        }
                        if (arr[i].startsWith('babak:')) {
                            arr[i] = `babak:${data.babak}`;
                        }
                    }

                    $(this).attr('name', arr.join(' '));
                });

                // Update button pemenang
                $(".btn-winner").each(function () {
                    var arr = $(this).attr('name').split(' ');

                    for (let i = 0; i < arr.length; i++) {
                        if (arr[i].startsWith('p:')) {
                            arr[i] = `p:${data.idBiru}`;
                        }
                        if (arr[i].startsWith('p1:')) {
                            arr[i] = `p1:${data.idMerah}`;
                        }
                        if (arr[i].startsWith('kalah:')) {
                            // Button biru: kalah=merah, menang=biru
                            if ($(this).closest('td').prev().length === 0) { // Button biru
                                arr[i] = `kalah:${data.idMerah}`;
                            } else { // Button merah  
                                arr[i] = `kalah:${data.idBiru}`;
                            }
                        }
                        if (arr[i].startsWith('menang:')) {
                            // Button biru: kalah=merah, menang=biru
                            if ($(this).closest('td').prev().length === 0) { // Button biru
                                arr[i] = `menang:${data.idBiru}`;
                            } else { // Button merah
                                arr[i] = `menang:${data.idMerah}`;
                            }
                        }
                    }

                    $(this).attr('name', arr.join(' '));
                });

                // Update partai jika ada perubahan
                if (data.partai) {
                    $('#partai-label').text(data.partai);
                }

                console.log("Dewan data updated successfully");
            }

            function assignDewan(datas) {
                var list = ['jatuh', 'binaan', 'teguran', 'peringatan']

                list.forEach((data, index) => {
                    // Untuk data biasa (jatuh, binaan, teguran)
                    if (data !== 'peringatan') {
                        for (let i = 1; i <= 3; i++) {
                            $(`#b${i}${data}`).text(`${datas[i]["biru"][data]}x`)
                        }

                        for (let i = 1; i <= 3; i++) {
                            $(`#m${i}${data}`).text(`${datas[i]["merah"][data]}x`)
                        }
                    }
                    // Khusus untuk peringatan
                    else {
                        // Peringatan biru - ambil dari babak 1 (atau babak mana saja karena rowspan)
                        $(`#bperingatan`).text(`${datas[1]["biru"][data]}x`)

                        // Peringatan merah - ambil dari babak 1 (atau babak mana saja karena rowspan)
                        $(`#mperingatan`).text(`${datas[1]["merah"][data]}x`)
                    }

                })
            }

            const btnveryfication = document.querySelectorAll('#btn-veryfication-jatuhan , #btn-veryfication-hukuman');
            const btnclouseveryvication = document.querySelectorAll('#btn-veryfication-close,#btn-hukuman-veryfication-close');
            var id_arenas = {{ $id_arena }};
            var id_juris = {{ $id_juri }};
            var id_partai = {{ $setting->partai }};

            //const pathurl = `/redirect?arena=${id_arenas}&role=arena-jadwal&name=${id_juris}`;
            const pathurl = `/redirect?arena=${id_arenas}&partai=${id_partai}&role=rekapTanding&isDewan=true`;

            function reload() {
                window.location.reload();
            }

            function jadwal() {
                window.location.href = pathurl;
            }

            btnShowmenang.on('click', () => {

                let list = $('#listPemenang');

                if (list.hasClass('hidden')) {
                    list.removeClass('hidden');
                    list.addClass('block');
                } else {
                    list.removeClass('block');
                    list.addClass('hidden');
                }

            });

            btnveryfication.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"
                    // Membagi nilai atribut "name" menjadi objek JavaScript
                    var data = {};
                    nameAttribute.split(' ').forEach(function (item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });

                    let juri1 = "invalid";
                    let juri2 = "invalid";
                    let juri3 = "invalid";

                    let status = "v" + data.status;
                    var myModal = new bootstrap.Modal(document.getElementById(status));
                    fetch('{{ route('dewan.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => {
                            if (response.status == 200) {
                                // alert('aa');
                                setInterval(() => callverifikasi(data), 500);
                                // callverifikasi(data);
                                myModal.show();
                            }

                        })
                        .then(data => {

                        })
                        .catch(error => {
                            // Tangani kesalahan jika ada
                        });

                    function callverifikasi(data) {
                        let idtbody = "v" + data.status + "-tbody"

                        let juri1 = document.getElementById('juri1-' + data.status);
                        let juri2 = document.getElementById('juri2-' + data.status);
                        let juri3 = document.getElementById('juri3-' + data.status);

                        $.ajax({
                            url: '/call-data/?tipe=notif&arena=' + data.arena + '&status=' + data.status,
                            method: 'GET',
                            success: function (response) {
                                // console.log(response,data.arena,data);
                                let master = response.data
                                master.forEach(data => {
                                    if (juri1) {
                                        if (data.id_juri == "Juri_1") {
                                            if (data.score == "biru") {
                                                juri1.classList.remove('br', 'by',
                                                    'bn');
                                                juri1.classList.add('bb');
                                            } else if (data.score == "merah") {
                                                juri1.classList.remove('bb', 'by',
                                                    'bn');
                                                juri1.classList.add('br');
                                            } else if (data.score == "invalid") {
                                                juri1.classList.remove('bb', 'br',
                                                    'bn');
                                                juri1.classList.add('by');
                                            } else {
                                                juri3.classList.remove('bb', 'br',
                                                    'by');
                                                juri3.classList.add('bn');
                                            }
                                        }

                                    }
                                    if (juri2) {
                                        if (data.id_juri == "Juri_2") {
                                            if (data.score == "biru") {
                                                juri2.classList.remove('br', 'by',
                                                    'bn');
                                                juri2.classList.add('bb');
                                            } else if (data.score == "merah") {
                                                juri2.classList.remove('bb', 'by',
                                                    'bn');
                                                juri2.classList.add('br');
                                            } else if (data.score == "invalid") {
                                                juri2.classList.remove('bb', 'br',
                                                    'bn');
                                                juri2.classList.add('by');
                                            } else {
                                                juri3.classList.remove('bb', 'br',
                                                    'by');
                                                juri3.classList.add('bn');
                                            }
                                        }
                                    }
                                    if (juri3) {
                                        if (data.id_juri == "Juri_3") {
                                            if (data.score == "biru") {
                                                juri3.classList.remove('br', 'by',
                                                    'bn');
                                                juri3.classList.add('bb');
                                            } else if (data.score == "merah") {
                                                juri3.classList.remove('bb', 'by',
                                                    'bn');
                                                juri3.classList.add('br');
                                            } else if (data.score == "invalid") {
                                                juri3.classList.remove('bb', 'br',
                                                    'bn');
                                                juri3.classList.add('by');
                                            } else {
                                                juri3.classList.remove('bb', 'br',
                                                    'by');
                                                juri3.classList.add('bn');
                                            }
                                        }

                                    }
                                    //console.log(response);
                                });

                            }
                        });
                    }
                });
            });
            // function clear notif
            btnclouseveryvication.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var nameAttribute = this.getAttribute('name'); // Mendapatkan nilai atribut "name"
                    // Membagi nilai atribut "name" menjadi objek JavaScript
                    var data = {};
                    nameAttribute.split(' ').forEach(function (item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });
                    let status = "v" + data.status;
                    const myModal = document.getElementById(status)
                    myModal.addEventListener('hidden.bs.modal', event => {
                        fetch('{{ route('dewan.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                            .then(response => {
                                if (response.status == 200) {
                                    reload();
                                    // setInterval(reload, 800);
                                }

                            })
                            .then(data => {

                            })
                            .catch(error => {
                                // Tangani kesalahan jika ada
                            });
                    });

                });
            });
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
                    $('button').prop('disabled', true);

                    // Sekarang, Anda memiliki data dalam bentuk objek
                    //console.log(data);
                    // console.log(data);
                    // Lanjutkan dengan kode pengiriman permintaan POST jika diperlukan
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
                            // Lakukan sesuatu dengan respons dari server (opsional)
                            console.log(data);
                            // console.log('aa');
                            $('button').prop('disabled', false);
                        })
                        .catch(error => {
                            console.log(error);
                            $('button').prop('disabled', false);
                            // Tangani kesalahan jika ada
                        });
                    // reload();
                    //setInterval(reload, 800);
                });
            });
            btnwinner.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var nameAttribute = this.getAttribute('name');
                    var data = {};
                    nameAttribute.split(' ').forEach(function (item) {
                        var parts = item.split(':');
                        data[parts[0]] = parts[1];
                    });
                    data['statusmenang'] = $("#status-winner").text();
                    //console.log(data);
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
                            //console.log(data)
                            // Lakukan sesuatu dengan respons dari server (opsional)
                            jadwal();
                        })
                        .catch(error => {
                            // Tangani kesalahan jika ada
                        });
                    // setInterval(jadwal, 800);
                });
            });

            WebSocket();
        </script>

</body>

</html>