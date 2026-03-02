<?php

namespace App\Http\Controllers;

use App\Events\VerificationEvent;
use Illuminate\Http\Request;
use App\score;
use App\Medali;
use App\SesiModel;
use App\Setting;
use App\PersertaModel;
use App\jadwal_group;
use App\arena;
use Carbon\Carbon;
use App\Helpers\GlobalScoreHelper;
use Illuminate\Support\Facades\DB;
use App\Events\IndicatorEvent;

class DewanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penilaian.dewan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //  'score', 'keterangan', 'id_perserta','id_juri','status',
        // "juri:{{$id_juri}} id:2 status:tendangan p:3 keterangan:pluss"
        $keterangan = $request->keterangan;
        $p = $request->p;
        $p1 = $request->p1;
        $partai = $request->partai;
        $idJadwal = $request->id;
        $partaiInput = $request->partai;
        $status = $request->status;
        $id_perserta = $request->id;
        $id_juri = $request->juri;
        $babak = $request->babak;
        $arena = $request->arena;
        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $sesi = $request->sesi;

        if ($sesi) {
            $dataJadwal = jadwal_group::where('arena', $arena)->where('partai', $partai)->where('id_sesi', $sesi)->first();
        } else {
            $dataJadwal = jadwal_group::where('arena', $arena)->where('partai', $partai)->first();
        }

        $helper = new GlobalScoreHelper();

        if ($keterangan === "plus") {

            if ($status === "peringatan") {
                $datan = Score::where('keterangan', $status)
                    ->where('arena', $arena)
                    ->where('id_juri', $id_juri)
                    ->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })
                    ->where('id_perserta', $id_perserta)
                    ->first();

                $datas = Score::where('keterangan', $status)
                    ->where('id_juri', $id_juri)
                    ->where('arena', $arena)
                    ->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })
                    ->where('id_perserta', $id_perserta)
                    ->latest()
                    ->get();

                $score = $datas->isEmpty() ? null : $datas->first()->score;

                if (empty($datan)) {
                    $data = [
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                } elseif ($score == '5') {
                    $data = [
                        'score' => $p * 2,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                } elseif ($score == '10') {
                    $data = [
                        'score' => $p * 3,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                } else {
                    $data = [
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                }
            } elseif ($status == "teguran") {
                $datan = Score::where('keterangan', $status)
                    ->where('id_juri', $id_juri)
                    ->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })
                    ->where('babak', $babak)
                    ->where('arena', $arena)
                    ->where('id_perserta', $id_perserta)
                    ->first();
                if (empty($datan)) {
                    $data = [
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];

                } else {
                    $data = [
                        'score' => $p + 1,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                }



            } else {



                $data = [
                    'score' => $p,
                    'keterangan' => $status,
                    'id_perserta' => $id_perserta,
                    'id_juri' => $id_juri,
                    'id_sesi' => $sesi ?? null,
                    'status' => 'plus',
                    'babak' => $babak,
                    'arena' => $arena,
                    'partai' => $settingData->partai ?? null,
                ];
            }

            // Simpan data ke dalam tabel 'score'
            Score::create($data);

            $helper->sendDewanData($arena);
            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => 'Data berhasil disimpan']);
        } elseif ($keterangan === "notif") {
            if ($p === "off") {
                $data = Setting::where('arena', $arena)->first();
                $waiting = Setting::where('keterangan', 'waiting')->where('status', $data->id)->first();
                $time = $data->time;
                $time = Carbon::create($time);
                $time = $time->addSeconds($waiting->time);
                $time = $time->format('H:i:s');
                $data->update(
                    [
                        'status' => "start",
                        'time' => $time
                    ]
                );
                $waiting->delete();
                $data = Score::where('arena', $arena)
                    ->where('status', $status)
                    ->where('keterangan', 'notif')
                    ->delete();
                $datas = Score::where('arena', $arena)
                    ->where('keterangan', $status)
                    ->where('status', 'notif')
                    ->delete();

                event(new VerificationEvent([
                    'status' => 'modal',
                    'arena' => $arena,
                    'command' => 'close',
                    'type' => $status
                ]));

                $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
                return response()->json(['message' => 'Data berhasil disimpan']);

            } else {
                $datas = [
                    'score' => $p,
                    'keterangan' => "notif",
                    'id_perserta' => $id_perserta,
                    'id_juri' => $id_juri,
                    'id_sesi' => $sesi ?? null,
                    'status' => $status,
                    'babak' => $babak,
                    'arena' => $arena,
                    'partai' => $settingData->partai ?? null,
                ];
                $data = Setting::where('arena', $arena)->first();
                Setting::create([
                    'arena' => $arena,
                    'babak' => $data->babak,
                    'keterangan' => "waiting",
                    'status' => $data->id,
                    'time' => '0',
                    'partai' => $settingData->partai ?? null,
                ]);
                score::where('arena', $arena)
                    ->where('babak', $data->babak)
                    ->where('partai', $settingData->partai)
                    ->where('score', 'biru')->orWhere('score', 'merah')->where('status', 'notif')->delete();
                $data->update(
                    [
                        'status' => "pause"
                    ]
                );
                Score::create($datas);

                event(new VerificationEvent([
                    'status' => 'modal',
                    'arena' => $arena,
                    'command' => 'open',
                    'type' => $status
                ]));

                $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
                return response()->json(['message' => 'Data berhasil disimpan22']);
            }


            return response()->json(['message' => 'Data berhasil disimpan']);
        } elseif ($keterangan === "senidewans") {
            $data = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                'id_juri' => $id_juri,
                'id_sesi' => $sesi ?? null,
                'status' => 'seni_minus',
                'babak' => $babak,
                'arena' => $arena,
                'partai' => $settingData->partai ?? null,
            ];
            Score::create($data);
            return response()->json(['message' => 'Data berhasil dihapus']);
        } elseif ($keterangan === "senidewansc") {
            $data = score::where('keterangan', $status)
                ->where('id_perserta', $id_perserta)
                // ->when($sesi ?? null, function($query, $sesi) {
                //         $query->where('id_sesi', $sesi);
                //     }, function($query) {
                //         $query->whereNull('id_sesi');
                //     })
                ->where('partai', $settingData->partai ?? null)
                ->where('arena', $arena ?? null)
                ->first();
            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus']);
        } elseif ($keterangan === "minus") {

            $data = Score::where('keterangan', $status)
                ->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('arena', $arena)->where('babak', $settingData->babak)->where('partai', $settingData->partai)->where('id_perserta', $request->id);
            $id_perserta = $request->id;

            if ($status == "peringatan") {
                $countPeringatan = $data->orderBy('id', 'DESC')->get();
                $selected = $countPeringatan->first();
                $selected->delete();
            } else if ($status == "teguran") {
                $countTeguran = $data->orderBy('score', 'DESC')->get();
                $selected = $countTeguran->first();
                $selected->delete();
            } else {
                $data->first();
                $data->delete();
            }

            $helper->sendDewanData($arena);
            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => 'Data berhasil dihapus']);
            // return response()->json(['message' => "status : $status , id : $id_perserta, arena: $arena, partai: $settingData->partai, babak: $babak"]);

        } elseif ($keterangan === "jadwal") {
            $settingData = Setting::where('arena', $request->arena)->whereNotNull('judul')->first();
            $dataArena = arena::where('id', $settingData->arena)->first();

            // dd($settingData);
            $send = [
                "event" => "reload",
                "arena" => $request->arena
            ];
            $send = json_encode($send);
            // event(new IndicatorEvent($send));
            // $perserta = PersertaModel::where('id',$p)->update(['status'=>$status]);
            if ($status === "finish") {
                Setting::where('arena', $request->arena)->whereNotNull('judul')->update([
                    'babak' => '1',
                ]);
                $settingFinish = Setting::where('arena', $request->arena)->whereNotNull('judul')->first();

                // $initialData = jadwal_group::where('arena', $request->arena)->where('partai', $partai)
                //                 ->when($sesi ?? null, function($query, $sesi) {
                //                     $query->where('id_sesi', $sesi);
                //                 }, function($query) {
                //                     $query->whereNull('id_sesi');
                //                 })->first();

                $sesiData = SesiModel::where('id', $settingFinish->sesi)->first();
                $namaSesi = $sesiData->name ?? "Sesi Tidak Diketahui";
                $ketSesi = $dataArena->name;
                $sesi = $settingFinish->sesi;
                $jadwals = jadwal_group::where('id', $settingFinish->jadwal)->first();
                //Perunggu 2, Perak 3, Emas 5

                // dd($request->all());
                // Medali
                if ($jadwals && $jadwals->keterangan == "semi-final") {
                    $pesertaKalah = PersertaModel::where('id', $request->input('kalah'))->first();

                    Medali::create([
                        'name' => "$namaSesi - $ketSesi",
                        'id_peserta' => $pesertaKalah->id,
                        'kontigen' => $pesertaKalah->id_kontigen,
                        'kelas' => $pesertaKalah->kelas,
                        'kelamin' => $pesertaKalah->gender,
                        'kategori' => $pesertaKalah->category,
                        'point' => '2',
                        'keterangan' => 'tanding',
                    ]);
                } else if ($jadwals && $jadwals->keterangan == "final") {
                    $pesertaKalah = PersertaModel::where('id', $request->input('kalah'))->first();
                    $pesertaMenang = PersertaModel::where('id', $request->input('menang'))->first();

                    Medali::create([
                        'name' => "$namaSesi - $ketSesi",
                        'id_peserta' => $pesertaMenang->id,
                        'kontigen' => $pesertaMenang->id_kontigen,
                        'kelas' => $pesertaMenang->kelas,
                        'kelamin' => $pesertaMenang->gender,
                        'kategori' => $pesertaMenang->category,
                        'point' => '5',
                        'keterangan' => 'tanding',
                    ]);

                    Medali::create([
                        'name' => "$namaSesi - $ketSesi",
                        'id_peserta' => $pesertaKalah->id,
                        'kontigen' => $pesertaKalah->id_kontigen,
                        'kelas' => $pesertaKalah->kelas,
                        'kelamin' => $pesertaKalah->gender,
                        'kategori' => $pesertaKalah->category,
                        'point' => '3',
                        'keterangan' => 'tanding',
                    ]);
                }

                // $conditionPending = [
                //     'arena' => $request->arena,
                // ];

                // if($request->sesi) {
                //     $conditionPending['id_sesi'] = $request->sesi;    
                // }

                $pemenangPendingBiru = jadwal_group::where('arena', $request->arena)
                    ->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('biru', "[$partai]")
                    ->first();
                $pemenangPendingMerah = jadwal_group::where('arena', $request->arena)
                    ->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('merah', "[$partai]")
                    ->first();

                if ($pemenangPendingBiru) {
                    $pemenangPendingBiru->update([
                        'biru' => $request->input('menang')
                    ]);
                }

                if ($pemenangPendingMerah) {
                    $pemenangPendingMerah->update([
                        'merah' => $request->input('menang')
                    ]);
                }

                $conditions = [
                    'partai' => $partai,
                    'arena' => $request->arena,
                ];

                if ($request->sesi) {
                    $conditions['id_sesi'] = $request->sesi;
                }

                jadwal_group::where($conditions)->update([
                    'status' => $status,
                    'pemenang' => $request->input('menang'),
                    'kondisi' => $request->input('statusmenang')
                ]);

                $helper->sendTandingScore($request->arena, $request->sesi ?? null, $request->partai ?? null, null, "jadwal");
                $helper->sendPendingData($request->arena, $request->sesi ?? null, $request->partai ?? null, $settingData);
            } else {
                $condition2 = [
                    'partai' => $partai,
                    'arena' => $request->arena,
                    'id_sesi' => null,
                ];

                if ($request->sesi) {
                    $condition2['id_sesi'] = $request->sesi;
                }

                Setting::where('arena', $request->arena)->whereNotNull('judul')->update([
                    'biru' => $p,
                    'merah' => $p1,
                    'jadwal' => $idJadwal ?? null,
                    'poll' => $request->poll ?? null,
                    'partai' => $partai,
                    'sesi' => $request->sesi ?? null
                ]);
                jadwal_group::where($condition2)->update(['status' => $status]);

                $helper->sendTandingScore($request->arena, $sesi ?? null, $partai ?? null, null, "jadwal");
                $helper->sendDewanData($arena);
                $helper->sendPendingData($request->arena, $sesi ?? null, $partai ?? null, $settingData);
            }

            return response()->json(['message' => 'Data berhasil ']);
        } elseif ($keterangan === "hapusjadwal") {
            $data = jadwal_group::where('id', $p)->first();
            $settingData = Setting::where('arena', $data->arena)->first();
            $sesi = $settingData->sesi ?? null;

            $dataBiru = PersertaModel::where('id', $data->biru)->first();
            $dataMerah = PersertaModel::where('id', $data->merah)->first();

            if ($dataBiru) {
                score::where('id_perserta', $dataBiru->id)->where('partai', $data->partai)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('arena', $data->arena)->delete();
            }

            if ($dataMerah) {
                score::where('id_perserta', $dataMerah->id)->where('partai', $data->partai)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('arena', $data->arena)->delete();
            }

            $data->delete();
            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => "Data Berhasil Dihapus"]);
        } elseif ($keterangan === "jadwal_seni") {
            $data = Setting::first();
            // $sesi = $request->sesi;
            // $poll = $request->poll;
            if ($status == 'reset') {
                Score::where('id_perserta', $p)->where('partai', $partaiInput)->where('arena', $request->arena)->delete();
            } elseif ($status == 'delete') {
                jadwal_group::where('id', $p)->delete();
            } else {
                Setting::where('arena', $request->arena)->whereNotNull('judul')->update([
                    'biru' => $p,
                    'jadwal' => $request->id ?? null,
                    'sesi' => $request->sesi,
                    'poll' => $request->poll,
                    'status' => null,
                    'time' => null,
                    'partai' => $partaiInput
                ]);

                jadwal_group::where('id', $request->id)->update([
                    'status' => $status
                ]);

                PersertaModel::where('id', $p)->update(['status' => $status]);

                $helper->sendTunggalScore($arena);
                $helper->sendSoloScore($arena);
            }
            // jadwal_group::where('partai', $partai)->update(['status' => $status]);
            // $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            // return response()->json(['message' => 'Data berhasil ']);
            return response()->json(['message' => $request->all()]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
