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
        $partaiInput = $request->partai;
        $status = $request->status;
        $id_perserta = $request->id;
        $id_juri = $request->juri;
        $babak = $request->babak;
        $arena = $request->arena;
        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $sesi = $request->sesi;
        
        if($sesi) {
            $dataJadwal = jadwal_group::where('arena', $arena)->where('partai', $partai)->where('id_sesi', $sesi)->first();
        }
        else {
            $dataJadwal = jadwal_group::where('arena', $arena)->where('partai', $partai)->first();
        }

        $helper = new GlobalScoreHelper();
    
        if ($keterangan === "plus") {

            if ($status === "peringatan") {
                $datan = Score::where('keterangan', $status)
                    ->where('arena', $arena)
                    ->where('id_juri', $id_juri)
                    ->when($sesi ?? null, function($query, $sesi) {
                            $query->where('id_sesi', $sesi);
                        }, function($query) {
                            $query->whereNull('id_sesi');
                        })
                    ->where('id_perserta', $id_perserta)
                    ->first();

                $datas = Score::where('keterangan', $status)
                    ->where('id_juri', $id_juri)
                    ->where('arena', $arena)
                    ->when($sesi ?? null, function($query, $sesi) {
                            $query->where('id_sesi', $sesi);
                        }, function($query) {
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
                        'arena'=>$arena,
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
                        'arena'=>$arena,
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
                        'arena'=>$arena,
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
                        'arena'=>$arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                }
            } 
            elseif($status == "teguran"){
                $datan = Score::where('keterangan', $status)
                    ->where('id_juri', $id_juri)
                    ->when($sesi ?? null, function($query, $sesi) {
                            $query->where('id_sesi', $sesi);
                        }, function($query) {
                            $query->whereNull('id_sesi');
                        })
                    ->where('babak', $babak)
                    ->where('arena', $arena)
                    ->where('id_perserta', $id_perserta)
                    ->first();
                if(empty($datan)){
                    $data = [
                        'score' => $p,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena'=>$arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                    
                }
                else{
                    $data = [
                        'score' => $p+1,
                        'keterangan' => $status,
                        'id_perserta' => $id_perserta,
                        'id_juri' => $id_juri,
                        'id_sesi' => $sesi ?? null,
                        'status' => 'minus',
                        'babak' => $babak,
                        'arena'=>$arena,
                        'partai' => $settingData->partai ?? null,
                    ];
                }
                    
                
                
            }
            else {
                


                $data = [
                    'score' => $p,
                    'keterangan' => $status,
                    'id_perserta' => $id_perserta,
                    'id_juri' => $id_juri,
                    'id_sesi' => $sesi ?? null,
                    'status' => 'plus',
                    'babak' => $babak,
                    'arena'=>$arena,
                    'partai' => $settingData->partai ?? null,
                ];
            }

            // Simpan data ke dalam tabel 'score'
            Score::create($data);

            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => 'Data berhasil disimpan']);
        }
        elseif($keterangan === "notif"){
            if($p === "off"){
                $data = Setting::where('arena',$arena)->first();
                $waiting = Setting::where('keterangan','waiting')->where('status',$data->id)->first();
                $time = $data->time;
                $time = Carbon::create($time);
                $time = $time->addSeconds($waiting->time);
                $time = $time->format('H:i:s');
                    $data->update(
                        [
                            'status'=>"start",
                            'time'=>$time
                        ]
                    );
                 $waiting->delete();   
               $data = Score::where('arena',$arena)
                    ->where('babak',$babak)
                    ->where('status',$status)
                    ->when($sesi ?? null, function($query, $sesi) {
                            $query->where('id_sesi', $sesi);
                        }, function($query) {
                            $query->whereNull('id_sesi');
                        })
                    ->where('keterangan','notif')
                    ->delete();
               $datas = Score::where('arena',$arena)
                    ->where('babak',$babak)
                    ->when($sesi ?? null, function($query, $sesi) {
                            $query->where('id_sesi', $sesi);
                        }, function($query) {
                            $query->whereNull('id_sesi');
                        })
                    ->where('keterangan',$status)
                    ->where('status','notif')
                    ->delete();

                    event(new VerificationEvent([
                        'status' => 'modal',
                        'command' => 'close',
                        'type' => $status
                    ]));

            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => 'Data berhasil disimpan']);
                  
            }
            else{
                $datas = [
                    'score' => $p,
                    'keterangan' => "notif",
                    'id_perserta' => $id_perserta,
                    'id_juri' => $id_juri,
                    'id_sesi' => $sesi ?? null,
                    'status' => $status,
                    'babak' => $babak,
                    'arena'=>$arena,
                    'partai' => $settingData->partai ?? null,
                ];
                $data = Setting::where('arena',$arena)->first();
                Setting::create([
                    'arena'=>$arena,
                    'babak'=>$data->babak,
                    'keterangan'=>"waiting",
                    'status'=>$data->id,
                    'time'=>'0',
                    'partai' => $settingData->partai ?? null,
                ]);
                score::where('arena' ,$arena)
                ->where('babak', $data->babak)
                ->where('partai', $settingData->partai)
                ->where('score', 'biru')->orWhere('score', 'merah')->where('status', 'notif')->delete();
                $data->update(
                    [
                    'status'=>"pause"
                    ]
                );
                Score::create($datas);

                event(new VerificationEvent([
                    'status' => 'modal',
                    'command' => 'open',
                    'type' => $status
                ]));

                $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
                return response()->json(['message' => 'Data berhasil disimpan22']);

            }
            

            return response()->json(['message' => 'Data berhasil disimpan']);
        }
        elseif($keterangan === "senidewans"){
            $data = [
                'score' => $p,
                'keterangan' => $status,
                'id_perserta' => $id_perserta,
                'id_juri' => $id_juri,
                'id_sesi' => $sesi ?? null,
                'status' => 'seni_minus',
                'babak' => $babak,
                'arena'=>$arena,
                'partai' => $settingData->partai ?? null,
            ];
            Score::create($data);
                return response()->json(['message' => 'Data berhasil dihapus']);
        }
        elseif($keterangan === "senidewansc"){
            $data = score::where('keterangan',$status)
            ->where('id_perserta',$id_perserta)
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
        }
        elseif($keterangan === "minus"){
            
            $data = Score::where('keterangan', $status)
                ->when($sesi ?? null, function($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function($query) {
                    $query->whereNull('id_sesi');
                })->where('arena', $arena)->where('babak', $settingData->babak)->where('partai', $settingData->partai)->where('id_perserta',$request->id);
            $id_perserta = $request->id;

            if($status == "peringatan") {
                $countPeringatan = $data->orderBy('id', 'DESC')->get();
                $selected = $countPeringatan->first();
                $selected->delete();
            }
            else if($status == "teguran") {
                $countTeguran = $data->orderBy('score', 'DESC')->get();
                $selected = $countTeguran->first();
                $selected->delete();
            }
            else {
                $data->first();
                $data->delete();
            }
            

            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => 'Data berhasil dihapus']);
            // return response()->json(['message' => "status : $status , id : $id_perserta, arena: $arena, partai: $settingData->partai, babak: $babak"]);

        }
        elseif($keterangan === "jadwal"){
            $settingData = Setting::where('arena',$request->arena)->whereNotNull('judul')->first();

            Setting::where('arena',$request->arena)->whereNotNull('judul')->update(['biru'=>$p, 'merah'=>$p1, 'partai' => $partai, 'sesi' => $settingData->sesi ?? null]);
            
            $send = [
                "event"=>"reload",
                "arena"=>$request->arena
            ];
            $send =json_encode($send);
            // event(new IndicatorEvent($send));
            // $perserta = PersertaModel::where('id',$p)->update(['status'=>$status]);
            if($status === "finish"){
                // $initialData = jadwal_group::where('arena', $request->arena)->where('partai', $partai)
                //                 ->when($sesi ?? null, function($query, $sesi) {
                //                     $query->where('id_sesi', $sesi);
                //                 }, function($query) {
                //                     $query->whereNull('id_sesi');
                //                 })->first();

                // $sesiData = SesiModel::where('id', $sesi)->first();
                // $namaSesi = $sesiData->name ?? "Sesi Tidak Diketahui";
                // $ketSesi = $sesiData->keterangan ?? null;

                
                //Perunggu 2, Perak 3, Emas 5

                //Medali
                // if($ketSesi && $ketSesi == "semifinal") {
                //     Medali::create([
                //         'name' => "$namaSesi - $ketSesi",
                //         'id_perserta' => $request->input('kalah'),
                //         'point' => '2',
                //     ]);
                // }
                // else if($ketsesi && $ketSesi == "final") {
                //     Medali::create([
                //         'name' => "$namaSesi - $ketSesi",
                //         'id_perserta' => $request->input('menang'),
                //         'point' => '5',
                //     ]);

                //     Medali::create([
                //         'name' => "$namaSesi - $ketSesi",
                //         'id_perserta' => $request->input('kalah'),
                //         'point' => '3',
                //     ]);
                // }

                // $conditionPending = [
                //     'arena' => $request->arena,
                // ];

                // if($request->sesi) {
                //     $conditionPending['id_sesi'] = $request->sesi;    
                // }

                $pemenangPendingBiru = jadwal_group::where('arena', $request->arena)
                                        ->when($sesi ?? null, function($query, $sesi) {
                                            $query->where('id_sesi', $sesi);
                                        }, function($query) {
                                            $query->whereNull('id_sesi');
                                        })->where('biru', "[$partai]")
                                        ->first();
                $pemenangPendingMerah = jadwal_group::where('arena', $request->arena)
                                        ->when($sesi ?? null, function($query, $sesi) {
                                            $query->where('id_sesi', $sesi);
                                        }, function($query) {
                                            $query->whereNull('id_sesi');
                                        })->where('merah', "[$partai]")
                                        ->first();

                if($pemenangPendingBiru) {
                    $pemenangPendingBiru->update([
                        'biru' => $request->input('menang')
                    ]);             
                }

                if($pemenangPendingMerah) {
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

                $helper->sendTandingScore($request->arena, $request->sesi ?? null, $request->partai ?? null);
                $helper->sendPendingData($request->arena, $request->sesi ?? null, $request->partai ?? null, $settingData);
            }
           else{
                $condition2 = [
                    'partai' => $partai,
                    'arena' => $request->arena,
                    'id_sesi' => null,
                ];

                if ($request->sesi) {
                    $condition2['id_sesi'] = $request->sesi;
                }

                jadwal_group::where($condition2)->update(['status' => $status]);
                
                $helper->sendTandingScore($request->arena, $sesi ?? null, $partai ?? null);
                $helper->sendPendingData($request->arena, $sesi ?? null, $partai ?? null, $settingData);
           }
            
            return response()->json(['message' => 'Data berhasil ']);
        }
        elseif($keterangan === "hapusjadwal"){
            $data = jadwal_group::where('id',$p)->first();
            $settingData = Setting::where('arena', $data->arena)->first();
            $sesi = $settingData->sesi ?? null;

            $dataBiru = PersertaModel::where('id', $data->biru)->first();
            $dataMerah = PersertaModel::where('id', $data->merah)->first();

            if($dataBiru) {
                score::where('id_perserta', $dataBiru->id)->where('partai', $data->partai)->when($sesi ?? null, function($query, $sesi) {$query->where('id_sesi', $sesi);}, function($query) {$query->whereNull('id_sesi');})->where('arena', $data->arena)->delete();
            }

            if($dataMerah) {
                score::where('id_perserta', $dataMerah->id)->where('partai', $data->partai)->when($sesi ?? null, function($query, $sesi) {$query->where('id_sesi', $sesi);}, function($query) {$query->whereNull('id_sesi');})->where('arena', $data->arena)->delete();
            }

            $data->delete();
            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => "Data Berhasil Dihapus"]);
        }
        elseif($keterangan === "jadwal_seni"){
            $data = Setting::first();
            if($status == 'reset') {
                Score::where('id_perserta', $p)->where('arena', $request->arena)->delete();
            }
            elseif($status == 'delete') {
                jadwal_group::where('id', $p)->delete();
            }
            else {
                Setting::where('arena',$request->arena)->update(['biru'=>$p, 'status' => $partai, 'partai' => $partaiInput]);
                $perserta = PersertaModel::where('id',$p)->update(['status'=>$status]);
            }
            // jadwal_group::where('partai', $partai)->update(['status' => $status]);
            $helper->sendTandingScore($arena, $settingData->sesi ?? null, $settingData->partai);
            return response()->json(['message' => 'Data berhasil ']);
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
