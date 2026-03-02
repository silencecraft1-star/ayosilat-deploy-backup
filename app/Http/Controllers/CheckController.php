<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckController extends Controller
{
    //
    public function checkScore(Request $request) {
        $pending = pending_tanding::where('id_perserta',"$id")->first();
                    
                    if($pending) {
                        $arena = $request->input('arena');
                        $variable1 = $pending->juri1;
                        $variable2 = $pending->juri2;
                        $variable3 = $pending->juri3;
                        $threshold = Carbon::now()->subSeconds(3);
                        $five = Carbon::now()->subSeconds(5);
                        $hapus_dewan = score::where('status','notif')->where('arena',$arena)->get();
                        $hapus_data = pending_tanding::where('created_at', '<', $threshold)->delete();
                        if (($variable1 !== null) + ($variable2 !== null) + ($variable3 !== null) >= 2) {
                            $data = pending_tanding::where('id',$pending->id)->first();
                            $settingData = Setting::where('arena', $data->arena)->first();
                            $datas = [
                                'score' => $data->score,
                                'keterangan' => $data->keterangan,
                                'id_perserta' => $data->id_perserta,
                                "id_juri" => $data->juri1,
                                'status' => 'plus',
                                'babak' => $data->babak,
                                'arena' => $data->arena,
                                'partai' => $settingData->partai ?? null,
                            ];
                            //masuk data score
                            score::create($datas);
                            $data->delete();
                        }
                        elseif($hapus_dewan->count() > 0) {
                            $hapus_dewan->each->delete();
                        }
                    }
    }
}
