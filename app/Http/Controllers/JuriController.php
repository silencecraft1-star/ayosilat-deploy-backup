<?php

namespace App\Http\Controllers;

use App\Events\VerificationEvent;
use Illuminate\Http\Request;
use App\score;
use App\Perserta;
use App\pending_tanding;
use App\Setting;
use App\kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\jadwal_group;
use App\KontigenModel;
use App\PersertaModel;
use App\Events\IndicatorEvent;
use App\Helpers\GlobalScoreHelper;

class JuriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penilaian.juri');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $helper = new GlobalScoreHelper();
        $tipe = $request->tipe;
        $keterangan = $request->keterangan;
        $p = $request->p;
        $status = $request->status;
        $id_perserta = $request->id;
        $id_juri = $request->juri;
        $nomor_juri = $request->nj;
        $arena = $request->arena;
        $settingData = Setting::where('arena', $arena)->first();
        $globalSesi = $settingData->sesi ?? null;
        $partai = $settingData->partai;

        if ($keterangan === "plus") {
            $currentTimestamp = Carbon::now();
            $threeSecondsAgo = $currentTimestamp->subSeconds(3);
            $datas = pending_tanding::where('id_perserta', $id_perserta)
                ->where('keterangan', $status)
                ->first();
            $data = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                // "juri$nomor_juri" => $id_juri,
                "juri1" => $id_juri,
                'status' => 'plus',
                'isValid' => 'false',
                'babak' => $request->babak,
                'arena' => $arena,
                'partai' => $settingData->partai ?? null,
                'id_sesi' => $settingData->sesi ?? null,
            ];
            $datanotif = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                'id_biru' => $settingData->biru,
                'id_merah' => $settingData->merah,
                // "juri$nomor_juri" => $id_juri,
                "juri$nomor_juri" => $id_juri,
                'status' => 'plus',
                'babak' => $request->babak,
                'arena' => $arena,
                'partai' => $settingData->partai ?? null,
                'id_sesi' => $settingData->sesi ?? null,
            ];
            event(new IndicatorEvent($datanotif));

            if ($status == "hapus") {
                $hapus = pending_tanding::where('id_perserta', $id_perserta)
                    ->where('arena', $arena)
                    ->where('juri1', $id_juri)
                    ->when($globalSesi ?? null, function ($query, $globalSesi) {
                        $query->where('id_sesi', $globalSesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })
                    ->where('partai', $settingData->partai ?? null)
                    ->latest()
                    ->first();

                if ($hapus) {
                    $hapus->delete();
                }
                ;
            } else {

                if ($datas !== null) {
                    pending_tanding::create($data);
                    // $updateData = [
                    //     "juri$nomor_juri" => $id_juri,
                    // ];
                    // $datas->update($updateData);
                } else {
                    pending_tanding::create($data);
                    return response()->json("success", 200);
                }
            }


            $helper->cekJuriTanding($arena, $settingData->sesi ?? null, $partai);

            return response()->json(['message' => 'Data berhasil disimpan']);

        } elseif ($status === "terakhir") {
            $data = score::where('id_perserta', $request->id)
                ->where('babak', $request->babak)
                ->where('arena', $arena)
                ->when($globalSesi ?? null, function ($query, $globalSesi) {
                    $query->where('id_sesi', $globalSesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })
                ->where('partai', $partai)
                ->latest()
                ->first();
            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus']);
        } elseif ($keterangan === "minus") {
            $data = Score::where('keterangan', $status)
                ->where('babak', $request->babak)
                ->where('arena', $arena)
                ->when($globalSesi ?? null, function ($query, $globalSesi) {
                    $query->where('id_sesi', $globalSesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })
                ->where('partai', $partai)
                ->first();
            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus']);

        } elseif ($keterangan === "notif") {

            if ($status == "jatuhan" || $status == "hukuman") {
                $checkJuri = score::where('arena', $arena)->where('partai', $settingData->partai)->where('id_juri', $id_juri)->first();
                if ($checkJuri) {
                    $checkJuri->update([
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'status' => 'notif',
                        'babak' => $request->babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                        'id_sesi' => $settingData->sesi ?? null,
                    ]);
                } else {
                    $data = [
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'status' => 'notif',
                        'babak' => $request->babak,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                        'id_sesi' => $settingData->sesi ?? null,
                    ];
                    score::create($data);
                }

                $verifikasi = score::where('keterangan', $status)
                    ->where('arena', $arena)
                    ->where('status', 'notif')
                    ->get();

                event(new VerificationEvent([
                    'arena' => $arena,
                    'type' => 'verifikasi',
                    'command' => 'input',
                    'status' => $status,
                    'data' => $verifikasi
                ]));
                // $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai ?? null);
            } else {
                $data = [
                    'score' => $p,
                    'keterangan' => $status,
                    'id_perserta' => $id_perserta,
                    'id_juri' => $id_juri,
                    'status' => 'notif',
                    'babak' => $request->babak,
                    'arena' => $arena,
                    'partai' => $settingData->partai ?? null,
                    'id_sesi' => $settingData->sesi ?? null,
                ];
                score::create($data);

                $verifikasi = score::where('keterangan', $status)
                    ->where('arena', $arena)
                    ->where('status', 'notif')
                    ->get();

                event(new VerificationEvent([
                    'arena' => $arena,
                    'type' => 'verifikasi',
                    'command' => 'input',
                    'status' => $status,
                    'data' => $verifikasi
                ]));
                // $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai ?? null);
            }
        } elseif ($keterangan === "senidewan") {
            $data = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                'id_juri' => $id_juri,
                'status' => 'plus',
                'arena' => $arena,
                'partai' => $settingData->partai ?? null,
                'id_sesi' => $settingData->sesi ?? null,
            ];
        } elseif ($keterangan === "pointseni") {
            $helperSeni = new GlobalScoreHelper();
            $check = [
                'id_perserta' => $id_perserta,
                'keterangan' => $status,
                'id_juri' => $id_juri,
                'partai' => $settingData->partai ?? null,
                'arena' => $arena
            ];
            $data = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                'id_juri' => $id_juri,
                'status' => 'point_solo',
                'arena' => $arena,
                'partai' => $settingData->partai ?? null,
            ];
            $datas = score::where($check)->first();
            if ($datas) {
                $datas->update($data);
            } else {
                score::create($data);
            }
            $helperSeni->sendSeniIndicator($arena, $settingData->sesi, $settingData->partai, $id_juri, "point", "solo");
            $helperSeni->sendSoloData($arena);
            return response()->json(['message' => 'Data berhasil disimpan']);
        } elseif ($keterangan === "seni_tunggal") {
            $helper2 = new GlobalScoreHelper();
            $check = [
                'id_perserta' => $id_perserta,
                'keterangan' => $status,
                'id_juri' => $id_juri,
                'partai' => $settingData->partai ?? null,
                'arena' => $arena
            ];
            $data = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                'id_juri' => $id_juri,
                'babak' => 0,
                'status' => 'point_tunggal',
                'arena' => $arena,
                'partai' => $settingData->partai ?? null,
            ];
            $datas = score::where($check)->first();
            if ($datas) {
                if ($status == 'next' && !empty($request->info) && $request->info == "change") {
                    // dd($request->all());
                    $datas->update([
                        'babak' => $request->p,
                    ]);

                    $helper2->sendTunggalData($arena);
                } else if ($status == 'next' && $p != 0) {
                    $data = [
                        'score' => $datas->score + $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'status' => 'point_tunggal',
                        // 'babak' => $datas->babak + 1,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                    $helper2->sendSeniIndicator($arena, $settingData->sesi ?? null, $settingData->partai ?? null, $id_juri, "wrong", "tunggal");
                    $datas->update($data);
                    $helper2->sendTunggalData($arena);
                } elseif ($status == 'next' && $p == 0) {
                    $data = [
                        'score' => $datas->score + $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'status' => 'point_tunggal',
                        'babak' => $datas->babak + 1,
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                    $helper2->sendSeniIndicator($arena, $settingData->sesi ?? null, $settingData->partai ?? null, $id_juri, "next", "tunggal");
                    $datas->update($data);
                    $helper2->sendTunggalData($arena);
                } elseif ($status == 'flwo') {
                    $data = [
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'status' => 'point_tunggal',
                        'arena' => $arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                    $helper2->sendSeniIndicator($arena, $settingData->sesi ?? null, $settingData->partai ?? null, $id_juri, "flow", "tunggal");
                    $datas->update($data);
                    $helper2->sendTunggalData($arena);
                }

            } else {
                score::create($data);
            }
            $helper2->sendTunggalData($arena);
            return response()->json(['message' => 'Data berhasil disimpan']);
        }
        return response()->json(['message' => 'Invalid status'], 400);
    }

    public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        while (true) {
            $last = score::latest('created_at')->first();
            $last = $last->created_at;
            $last = $last->subSeconds(10);
            $newData = score::where('created_at', '>', $last)->exists(); // Fungsi untuk memeriksa data baru

            if ($newData) {
                echo "data: newData\n\n";
                ob_flush();
                flush();
            }

            sleep(10); // Polling setiap 5 detik (sesuaikan sesuai kebutuhan)
        }
    }

    public function data(Request $request)
    {
        $tipe = $request->input('tipe');
        $id = $request->input('id');
        $partai = $request->input('partai');
        $arena = $request->input('arena');
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $sesi = $setting->sesi ?? null;
        //$sesi = $request->sesi;
        // $partai = Setting::where('arena', $arena)->first()->partai;
        $timer1 = Session::get("timer_remaining_$arena");
        if ($tipe === "score") {

            $plus = score::where('status', 'plus')->where('arena', $arena)->where('partai', $partai)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->where('id_perserta', "$id")->sum('score');
            $minus = score::where('status', 'minus')->where('arena', $arena)->where('partai', $partai)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->where('id_perserta', "$id")->sum('score');
            $score = $plus - $minus;
            return response()->json(['data' => $score]);
        } elseif ($tipe === "checkbabak") {
            $data = Setting::where('arena', $id)->first();
            $data = $data->babak;
            return response()->json(['data' => $data]);
        }
        // primary function
        elseif ($tipe === "tanding") {
            $arena = $request->input('arena');
            $partaiFinal = $setting->partai;
            $babak = $setting->babak;
            $sesi = $setting->sesi ?? null;

            $pesertaBiru = PersertaModel::where('id', $setting->biru)->first();
            $kontigenBiru = KontigenModel::where('id', $pesertaBiru->id_kontigen)->first()->kontigen;
            $namaBiru = $pesertaBiru->name;

            $pesertaMerah = PersertaModel::where('id', $setting->merah)->first();
            $kontigenMerah = KontigenModel::where('id', (int) $pesertaMerah->id_kontigen)->first()->kontigen;
            $namaMerah = $pesertaMerah->name;

            $pukulanb = score::where('keterangan', 'pukulan')->where('partai', $partaiFinal)->where('arena', $arena)->where('id_perserta', $setting->biru)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $tendanganb = score::where('keterangan', 'tendangan')->where('partai', $partaiFinal)->where('arena', $arena)->where('id_perserta', $setting->biru)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $pukulanm = score::where('keterangan', 'pukulan')->where('partai', $partaiFinal)->where('arena', $arena)->where('id_perserta', $setting->merah)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $tendanganm = score::where('keterangan', 'tendangan')->where('partai', $partaiFinal)->where('arena', $arena)->where('id_perserta', $setting->merah)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();

            $totalBinaan = [0, 0, 0, 0]; // [Binaan11, Binaan12, Binaan21, Binaan22]
            $totalTeguran = [0, 0, 0, 0]; // [Teguran11, Teguran12, Teguran21, Teguran22]

            $participants = [
                'biru' => [$setting->biru, &$totalBinaan[0], &$totalBinaan[1], &$totalTeguran[2], &$totalTeguran[3]],
                'merah' => [$setting->merah, &$totalBinaan[2], &$totalBinaan[3], &$totalTeguran[0], &$totalTeguran[1]]
            ];

            foreach ($participants as $key => [$id, &$binaan1, &$binaan2, &$teguran1, &$teguran2]) {
                for ($babakLoop = 1; $babakLoop <= 3; $babakLoop++) {
                    $binaan = score::where('keterangan', 'binaan')->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('arena', $arena)->where('id_perserta', $id)->where('babak', $babakLoop)->count();
                    $binaan1 += ($binaan >= 1) ? 1 : 0;
                    $binaan2 += ($binaan > 1) ? 1 : 0;

                    $teguran = score::where('keterangan', 'teguran')->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })->where('arena', $arena)->where('id_perserta', $id)->where('babak', $babakLoop)->count();
                    $teguran1 += ($teguran >= 1) ? 1 : 0;
                    $teguran2 += ($teguran > 1) ? 1 : 0;
                }
            }
            //count Binaan 1
            $totalBinaan1 = score::where('keterangan', 'binaan')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->biru)->where('babak', $babak)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $totalBinaan2 = score::where('keterangan', 'binaan')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->merah)->where('babak', $babak)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();

            $totalTeguran1 = score::where('keterangan', 'teguran')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->biru)->where('babak', $babak)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $totalTeguran2 = score::where('keterangan', 'teguran')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->merah)->where('babak', $babak)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();

            $totalPeringatan1 = score::where('keterangan', 'peringatan')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->biru)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $totalPeringatan2 = score::where('keterangan', 'peringatan')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->merah)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();

            $totalJatuhan1 = score::where('keterangan', 'jatuh')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->biru)->where('babak', $babak)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            $totalJatuhan2 = score::where('keterangan', 'jatuh')->where('partai', $partai)->where('arena', $arena)->where('id_perserta', $setting->merah)->where('babak', $babak)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->count();
            //dd($arena, $partaiFinal, $sesi, $setting);
            $statusPertandingan = jadwal_group::where('arena', $arena)->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->first()->status ?? "pending";
            $infoKelas = kelas::where('id', $pesertaMerah->kelas)->first()->name;

            //  check pause
            // if($setting->status === "pause"){
            //     $timer = Setting::where('keterangan','waiting')->where('status',$setting->id)->first();
            //     $timer->update([
            //         'time'=> $timer->time + 1
            //     ]);

            // }
            if (!empty($setting)) {

                //$data = score::where('arena',$arena)
                //            ->where('partai', $partai)
                //            ->where('id_perserta',$setting->biru)
                //            ->orWhere('id_perserta',$setting->merah)
                //            ->get();
                $data = score::where('arena', $arena)
                    ->where('partai', $partaiFinal)
                    ->where(function ($query) use ($setting) {
                        $query->where('id_perserta', $setting->biru)
                            ->orWhere('id_perserta', $setting->merah);
                    })
                    ->when($sesi ?? null, function ($query, $sesi) {
                        $query->where('id_sesi', $sesi);
                    }, function ($query) {
                        $query->whereNull('id_sesi');
                    })
                    ->get();
                //dd($data, $arena, $partai, $babak, $setting);
                $notif = score::where('arena', $arena)->where('keterangan', 'notif')->where('partai', $partai)->value('status');
                if (!empty($notif)) {
                    $notifs = $notif;
                } else {
                    $notifs = "not";
                }
                if (!empty($data)) {
                    $response = [
                        'arena' => $arena,
                        'partai' => $partaiFinal,
                        'idBiru' => $pesertaBiru->id,
                        'idMerah' => $pesertaMerah->id,
                        'namaBiru' => $namaBiru,
                        'namaMerah' => $namaMerah,
                        'kontigenBiru' => $kontigenBiru,
                        'kontigenMerah' => $kontigenMerah,
                        'statusPertandingan' => $statusPertandingan,
                        'pukulanb' => $pukulanb,
                        'pukulanm' => $pukulanm,
                        'tendanganb' => $tendanganb,
                        'tendanganm' => $tendanganm,
                        'infoKelas' => $infoKelas,
                        'jatuh1' => 0,
                        'binaan1' => 0,
                        'teguran1' => 0,
                        'totalBinaan1Biru' => $participants["biru"][1],
                        'totalBinaan2Biru' => $participants["biru"][2],
                        'totalTeguran1Biru' => $participants["biru"][3],
                        'totalTeguran2Biru' => $participants["biru"][4],
                        'totalBinaan1Merah' => $participants["merah"][1],
                        'totalBinaan2Merah' => $participants["merah"][2],
                        'totalTeguran1Merah' => $participants["merah"][3],
                        'totalTeguran2Merah' => $participants["merah"][4],
                        'totalJatuhan1' => $totalJatuhan1,
                        'totalJatuhan2' => $totalJatuhan2,
                        'peringatan1' => 0,
                        'totalPeringatan1' => $totalPeringatan1,
                        'totalPeringatan2' => $totalPeringatan2,
                        'totalBinaan1' => $totalBinaan1,
                        'totalBinaan2' => $totalBinaan2,
                        'teguranTotal1' => $totalTeguran1,
                        'teguranTotal2' => $totalTeguran2,
                        'score1' => 0,
                        'jatuh2' => 0,
                        'binaan2' => 0,
                        'teguran2' => 0,
                        'peringatan2' => 0,
                        'score2' => 0,
                        'time' => $setting->time,
                        'status' => $setting->status,
                        'notif' => $notifs,
                        'sesi' => $sesi ?? null,
                        'jurip1biru' => pending_tanding::where('id_perserta', $setting->biru)->where('keterangan', 'pukulan')->value('juri1') ?? 0,
                        'jurip2biru' => pending_tanding::where('id_perserta', $setting->biru)->where('keterangan', 'pukulan')->value('juri2') ?? 0,
                        'jurip3biru' => pending_tanding::where('id_perserta', $setting->biru)->where('keterangan', 'pukulan')->value('juri3') ?? 0,
                        'jurip1merah' => pending_tanding::where('id_perserta', $setting->merah)->where('keterangan', 'pukulan')->value('juri1') ?? 0,
                        'jurip2merah' => pending_tanding::where('id_perserta', $setting->merah)->where('keterangan', 'pukulan')->value('juri2') ?? 0,
                        'jurip3merah' => pending_tanding::where('id_perserta', $setting->merah)->where('keterangan', 'pukulan')->value('juri3') ?? 0,
                        'jurit1biru' => pending_tanding::where('id_perserta', $setting->biru)->where('keterangan', 'tendangan')->value('juri1') ?? 0,
                        'jurit2biru' => pending_tanding::where('id_perserta', $setting->biru)->where('keterangan', 'tendangan')->value('juri2') ?? 0,
                        'jurit3biru' => pending_tanding::where('id_perserta', $setting->biru)->where('keterangan', 'tendangan')->value('juri3') ?? 0,
                        'jurit1merah' => pending_tanding::where('id_perserta', $setting->merah)->where('keterangan', 'tendangan')->value('juri1') ?? 0,
                        'jurit2merah' => pending_tanding::where('id_perserta', $setting->merah)->where('keterangan', 'tendangan')->value('juri2') ?? 0,
                        'jurit3merah' => pending_tanding::where('id_perserta', $setting->merah)->where('keterangan', 'tendangan')->value('juri3') ?? 0,

                    ];
                    foreach ($data as $item) {
                        if ($item->id_perserta === $setting->biru) {
                            $response['jatuh1'] += ($item->keterangan === "jatuh") ? $item->score / 3 : 0;
                            if ($item->babak == $setting->babak) {
                                $response['binaan1'] += ($item->keterangan === "binaan") ? $item->score + 1 : 0;
                                $response['teguran1'] += ($item->keterangan === "teguran") ? $item->score : 0;
                            }
                            $response['peringatan1'] += ($item->keterangan === "peringatan") ? $item->score / 5 : 0;
                            $plus = score::where('status', 'plus')
                                ->where('id_perserta', $item->id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->sum('score');
                            $minus = score::where('status', 'minus')
                                ->where('id_perserta', $item->id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->sum('score');
                            $score = $plus - $minus;
                            $response['score1'] = $score;
                            jadwal_group::where('arena', $arena)
                                ->where('partai', $partai)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->update(['score_biru' => $score]);
                        } elseif ($item->id_perserta === $setting->merah) {
                            $response['jatuh2'] += ($item->keterangan === "jatuh") ? $item->score / 3 : 0;

                            if ($item->babak == $setting->babak) {
                                $response['binaan2'] += ($item->keterangan === "binaan") ? $item->score + 1 : 0;
                                $response['teguran2'] += ($item->keterangan === "teguran") ? $item->score : 0;
                            }
                            $response['peringatan2'] += ($item->keterangan === "peringatan") ? $item->score / 5 : 0;
                            $plus = score::where('status', 'plus')
                                ->where('id_perserta', $item->id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->sum('score');
                            $minus = score::where('status', 'minus')
                                ->where('id_perserta', $item->id_perserta)
                                ->where('arena', $arena)
                                ->where('partai', $partai)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->sum('score');
                            $score = $plus - $minus;
                            $response['score2'] = $score;
                            jadwal_group::where('arena', $arena)
                                ->where('partai', $partai)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->update(['score_merah' => $score]);
                        }

                    }

                    return response()->json($response, 200);
                }

            }

        } elseif ($tipe === "detail") {
            $kt = $request->input('kt');
            $data = score::where('id_perserta', "$id")->get();
            return response()->json(['data' => $data]);
        } elseif ($tipe === "notif") {
            $status = $request->input('status');
            $arena = $request->input('arena');
            $data = score::where('keterangan', $status)
                ->where('arena', $arena)
                ->where('status', 'notif')
                ->get();

            return response()->json(['data' => $data]);
        } elseif ($tipe === "checkjatuhan") {
            $arena = $request->input('arena');
            $id_juri = $request->juri('juri');
            $data = score::where('id_juri', $id_juri)->where('arena', $arena)->where('status', 'notif')->where('keterangan', 'hukuman')->first();
            $data = $data->score;
            return response()->json(['data' => $data]);
        } elseif ($tipe === "checkhukuman") {
            $arena = $request->input('arena');
            $id_juri = $request->juri('juri');
            $data = score::where('id_juri', $id_juri)->where('arena', $arena)->where('status', 'notif')->where('keterangan', 'hukuman')->first();
            $data = $data->score;
            return response()->json(['data' => $data]);
        } elseif ($tipe === "checkhukuman") {
            $arena = $request->input('arena');
            $id_juri = $request->input('juri');
            $data = score::where('id_juri', $id_juri)->where('arena', $arena)->where('status', 'notif')->where('keterangan', 'jatuhan')->first();

            $data = $data->score;
            return response()->json(['data' => $data]);
        } elseif ($tipe === "check") {
            $pending = pending_tanding::where('id_perserta', "$id")->first();
            $final = null;
            if ($pending) {
                $arena = $request->input('arena');
                $variable1 = $pending->juri1;
                $variable2 = $pending->juri2;
                $variable3 = $pending->juri3;
                $threshold = Carbon::now()->subSeconds(60);
                $five = Carbon::now()->subSeconds(5);
                $hapus_dewan = score::where('status', 'notif')->where('arena', $arena)->get();

                $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first();
                // $queryPending = pending_tanding::where('arena', $arena)->where('babak', $settingData->babak)->
                //             when($sesi ?? null, function($query, $sesi) {
                //                 $query->where('id_sesi', $sesi);
                //             }, function($query) {
                //                 $query->whereNull('id_sesi');
                //             })->where('isValid', 'false')->where('created_at', '>=', $threshold);

                $pendingData = [
                    [
                        'name' => 'pukulan',
                        'data' => [
                            [
                                'identifier' => 'biru',
                            ],
                            [
                                'identifier' => 'merah',
                            ],
                        ],
                    ],
                    [
                        'name' => 'tendangan',
                        'data' => [
                            [
                                'identifier' => 'biru',
                            ],
                            [
                                'identifier' => 'merah',
                            ],
                        ],
                    ]
                ];

                foreach ($pendingData as $item) {
                    foreach ($item['data'] as $dataItem) {
                        $identifier = $dataItem['identifier'];

                        $idPerserta = $settingData->{$identifier};

                        $count = pending_tanding::where('arena', $arena)
                            ->where('keterangan', $item['name'])
                            ->where('partai', $settingData->partai)
                            ->where('babak', $settingData->babak)
                            ->where('id_perserta', $idPerserta)
                            ->when($sesi ?? null, function ($query, $sesi) {
                                $query->where('id_sesi', $sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('isValid', 'false')
                            ->where('created_at', '>=', $threshold)
                            ->distinct('juri1')
                            ->count();

                        if ($count >= 2) {
                            // Get first record
                            $data = pending_tanding::where('arena', $arena)
                                ->where('keterangan', $item['name'])
                                ->where('partai', $settingData->partai)
                                ->where('babak', $settingData->babak)
                                ->where('id_perserta', $idPerserta)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->where('isValid', 'false')
                                ->where('created_at', '>=', $threshold)->first();

                            // Update records
                            pending_tanding::where('arena', $arena)
                                ->where('keterangan', $item['name'])
                                ->where('partai', $settingData->partai)
                                ->where('babak', $settingData->babak)
                                ->where('id_perserta', $idPerserta)
                                ->when($sesi ?? null, function ($query, $sesi) {
                                    $query->where('id_sesi', $sesi);
                                }, function ($query) {
                                    $query->whereNull('id_sesi');
                                })
                                ->where('isValid', 'false')
                                ->where('created_at', '>=', $threshold)->update([
                                        'isValid' => 'true'
                                    ]);

                            // Check if data exists before creating score
                            if ($data) {
                                $datas = [
                                    'score' => $data->score,
                                    'keterangan' => $data->keterangan,
                                    'id_perserta' => $data->id_perserta,
                                    "id_juri" => $data->juri1,
                                    'status' => 'plus',
                                    'babak' => $data->babak,
                                    'arena' => $data->arena,
                                    'partai' => $settingData->partai ?? null,
                                    'id_sesi' => $settingData->sesi ?? null,
                                ];

                                score::create($datas);

                                return response()->json([
                                    '1' => $item['name'],
                                    '2' => $settingData->partai,
                                    '3' => $settingData->babak,
                                    '4' => $idPerserta,
                                    '5' => $sesi,
                                    '6' => $threshold,
                                    '7' => $arena,
                                    '8' => $count
                                ]);
                            }
                            $final = [
                                $count,
                                $data,
                                $datas,
                            ];
                        }
                    }
                }

                // $hapus_data = pending_tanding::where('created_at', '<', $threshold)->delete();
                // if (($variable1 !== null) + ($variable2 !== null) + ($variable3 !== null) >= 2) {
                // // if (($variable1 != null && $variable2 != null) || ($variable1 != null && $variable3 != null) || ($variable2 != null && $variable3 != null)) {
                //     // $data = pending_tanding::where('id',$pending->id)->first();
                //     $datas = [
                //         'score' => $data->score,
                //         'keterangan' => $data->keterangan,
                //         'id_perserta' => $data->id_perserta,
                //         "id_juri" => $data->juri1,
                //         'status' => 'plus',
                //         'babak' => $data->babak,
                //         'arena' => $data->arena,
                //         'partai' => $settingData->partai ?? null,
                //         'id_sesi' => $settingData->sesi ?? null,
                //     ];
                //     //masuk data score
                //     score::create($datas);
                //     $data->delete();
                // }

                // elseif($hapus_dewan->count() > 0) {
                //     $hapus_dewan->each->delete();
                // }
            }

            // return response()->json([
            //     // 'count' => $countPukulanBiru,
            //     'data' => $pukulanBiru->first(),
            //     'babak' => $settingData->babak,
            //     'arena' => $settingData->arena
            // ]);

            // return response()->json($final);
        } elseif ($tipe === "keterangan") {
            $data = score::where('id_perserta', "$id")->where('status', 'plus')->get();
            foreach ($data as $item) {
                $data[] = $item->score;
            }
            return response()->json(['data' => $data]);
        } elseif ($tipe === "seni") {
            $kt = $request->input('kt');
            $id = $request->input('id');
            $arena = $request->input('arena');
            $settingData = Setting::where('arena', $arena)->first();

            $currentPlaying = null;

            //temporary
            $cekBiru = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('biru', $settingData->biru)->where('partai', $settingData->partai)->first();
            $cekMerah = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('merah', $settingData->biru)->where('partai', $settingData->partai)->first();

            if ($cekBiru) {
                $currentPlaying = "biru";
            }

            if ($cekMerah) {
                $currentPlaying = "merah";
            }

            $partai = $settingData->partai;
            $id_juri = $request->input('juri');
            if ($kt == "ganda") {
                $data = score::where('id_perserta', $id)->where('partai', $partai)->where('arena', $arena)->get();
                $dewan = $data->where('status', 'seni_minus')->sum('score');
                $setting = Setting::where('arena', $request->input('arena'))->first();

                $partaiFinal = $setting->partai;

                $pesertaBiru = PersertaModel::where('id', $setting->biru)->first();
                $kontigenBiru = KontigenModel::where('id', $pesertaBiru->id_kontigen)->first()->kontigen;
                $namaBiru = $pesertaBiru->name;
                if (!empty($data)) {
                    $response = [
                        'current' => $currentPlaying,
                        'nama' => $namaBiru,
                        'id_peserta' => $pesertaBiru->id,
                        'kontigen' => $kontigenBiru,
                        'attack1' => 0,
                        'attack2' => 0,
                        'attack3' => 0,
                        'attack4' => 0,
                        'attack5' => 0,
                        'attack6' => 0,
                        'attack7' => 0,
                        'attack8' => 0,
                        'soulfullness1' => 0,
                        'soulfullness2' => 0,
                        'soulfullness3' => 0,
                        'soulfullness4' => 0,
                        'soulfullness5' => 0,
                        'soulfullness6' => 0,
                        'soulfullness7' => 0,
                        'soulfullness8' => 0,
                        'firmness1' => 0,
                        'firmness2' => 0,
                        'firmness3' => 0,
                        'firmness4' => 0,
                        'firmness5' => 0,
                        'firmness6' => 0,
                        'firmness7' => 0,
                        'firmness8' => 0,
                        'dewan' => 0,
                        'time' => $setting->time,
                        'status' => $setting->status,
                    ];
                    $response['dewan'] += $dewan;
                    foreach ($data as $item) {
                        if ($item->id_juri === $setting->juri_1) {
                            if ($item->keterangan === "attack") {
                                $response['attack1'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness1'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness1'] = $item->score;
                            }

                        } elseif ($item->id_juri === $setting->juri_2) {
                            if ($item->keterangan === "attack") {
                                $response['attack2'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness2'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness2'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_3) {
                            if ($item->keterangan === "attack") {
                                $response['attack3'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness3'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness3'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_4) {
                            if ($item->keterangan === "attack") {
                                $response['attack4'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness4'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness4'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_5) {
                            if ($item->keterangan === "attack") {
                                $response['attack5'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness5'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness5'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_6) {
                            if ($item->keterangan === "attack") {
                                $response['attack6'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness6'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness6'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_7) {
                            if ($item->keterangan === "attack") {
                                $response['attack7'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness7'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness7'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_8) {
                            if ($item->keterangan === "attack") {
                                $response['attack8'] = $item->score;
                            } elseif ($item->keterangan === "firmness") {
                                $response['firmness8'] = $item->score;
                            } elseif ($item->keterangan === "soulfullness") {
                                $response['soulfullness8'] = $item->score;
                            }
                        }
                    }
                    return response()->json($response, 200);
                } else {
                    return response()->json(['message' => 'No data available'], 404);
                }
            }
            // return response()->json($respone);
        } elseif ($tipe === "seni_tunggal") {
            $kt = $request->input('kt');
            $id = $request->input('id');
            $id_juri = $request->input('juri');
            $settingData = Setting::where('arena', $request->input('arena'))->first();
            $currentPlaying = null;

            //temporary
            $cekBiru = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('biru', $settingData->biru)->where('partai', $settingData->partai)->first();
            $cekMerah = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('merah', $settingData->biru)->where('partai', $settingData->partai)->first();

            if ($cekBiru) {
                $currentPlaying = "biru";
            }

            if ($cekMerah) {
                $currentPlaying = "merah";
            }

            $arena = $settingData->arena;
            $partai = $settingData->partai;
            if ($kt == "tunggal") {
                $setting = Setting::where('arena', $request->input('arena'))->first();
                $pesertaBiru = PersertaModel::where('id', $setting->biru)->first();
                $data = score::where('id_perserta', $pesertaBiru->id)->where('partai', $partai)->where('arena', $arena)->get();
                $dewan = $data->where('status', 'seni_minus')->sum('score');

                $partaiFinal = $setting->partai;

                $kelas = kelas::where('id', $pesertaBiru->kelas)->first();

                $id = $pesertaBiru->id;
                $kontigenBiru = KontigenModel::where('id', $pesertaBiru->id_kontigen)->first()->kontigen;
                $namaBiru = $pesertaBiru->name;

                $isRegu = false;
                if ($kelas->name == "REGU") {
                    $pesertaRegu = '';
                    $dataRegu = PersertaModel::where('id_kontigen', $pesertaBiru->id_kontigen)->get();

                    $isRegu = true;
                    foreach ($dataRegu as $item) {
                        $pesertaRegu .= "$item->name,";
                    }
                }

                if (!empty($data)) {
                    $response = [
                        'current' => $currentPlaying,
                        'id_peserta' => $pesertaBiru->id,
                        'nama' => $namaBiru,
                        'kontigen' => $kontigenBiru,
                        'actual1' => 9.9,
                        'actual2' => 9.9,
                        'actual3' => 9.9,
                        'actual4' => 9.9,
                        'actual5' => 9.9,
                        'actual6' => 9.9,
                        'actual7' => 9.9,
                        'actual8' => 9.9,
                        'flwo1' => 0,
                        'flwo2' => 0,
                        'flwo3' => 0,
                        'flwo4' => 0,
                        'flwo5' => 0,
                        'flwo6' => 0,
                        'flwo7' => 0,
                        'flwo8' => 0,
                        'dewan' => 0,
                        'time' => $setting->time,
                        'status' => $setting->status,
                    ];
                    $response['dewan'] = number_format($dewan, 2);
                    foreach ($data as $item) {
                        if ($item->id_juri === $setting->juri_1) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual1'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo1'] = $item->score;
                            }

                        } elseif ($item->id_juri === $setting->juri_2) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual2'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo2'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_3) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual3'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo3'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_4) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual4'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo4'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_5) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual5'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo5'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_6) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual6'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo6'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_7) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual7'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo7'] = $item->score;
                            }
                        } elseif ($item->id_juri === $setting->juri_8) {
                            if ($item->keterangan === "next") {
                                $numbers = 9.90 - 0.1;
                                if ($item->score === '0') {
                                    $sc = number_format($item->score / 100, 2);
                                } else {
                                    $sc = number_format(($item->score - 10) / 100, 2);
                                }
                                $response['actual8'] = floor(abs($numbers - $sc) * 100) / 100;
                            } elseif ($item->keterangan === "flwo") {
                                $response['flwo8'] = $item->score;
                            }
                        }
                    }

                    // jadwal_group::where('arena', $arena)->where('partai', $partai)->where('merah', $id)->update([

                    // ])
                    return response()->json($response, 200);
                } else {
                    return response()->json(['message' => 'No data available'], 404);
                }
            }
            // return response()->json($respone);
        }
    }

    public function scoreJadwal(Request $request)
    {
        //Get Tipe Seni
        $kategori = $request->input('kategori');

        //Gather data semua peserta dengan kategori tersebut
        $allPeserta = PersertaModel::where('kelas', '14')->get();

        if ($kategori == "seni_tunggal") {
            foreach ($allPeserta as $items) {
                $scoreData = score::where('id_perserta', $items->id)->get();
                if ($scoreData->isEmpty()) {
                    continue; // Skip if no scores found for this participant
                }
            }
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
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
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
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }
}
