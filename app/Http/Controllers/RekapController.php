<?php

namespace App\Http\Controllers;

use App\Medali;
use App\PersertaModel;
use App\SesiModel;
use Illuminate\Http\Request;
use App\jadwal_group;
use App\score;
use App\Setting;

class RekapController extends Controller
{
    public function senirekap(Request $request)
    {
        try {
            // Fix: Use 'arena' column instead of 'id' because $request->arena is usually the arena number
            $setting = Setting::where('arena', $request->arena)->whereNotNull('judul')->first();

            if (!$setting) {
                return response()->json(['error' => true, 'message' => 'Setting for arena ' . $request->arena . ' not found'], 404);
            }

            $selected = "biru";
            $data = null;

            $merahData = jadwal_group::where('merah', $request->id_user)
                ->where('id', $setting->jadwal)
                ->first();

            $biruData = jadwal_group::where('biru', $request->id_user)
                ->where('id', $setting->jadwal)
                ->first();

            if ($merahData) {
                $data = $merahData;
                $selected = "merah";
            } else if ($biruData) {
                $data = $biruData;
                $selected = "biru";
            }

            if (!$data) {
                return response()->json(['error' => true, 'message' => 'Jadwal data not found for user ' . $request->id_user], 404);
            }

            $namaSesi = SesiModel::where('id', $setting->sesi)->first();
            $ketSesi = $data->keterangan ?? "Seni";
            $id_peserta = ($selected == "merah") ? $data->merah : $data->biru;
            $peserta = PersertaModel::where('id', $id_peserta)->first();

            if ($selected == "merah") {
                $data->update([
                    'score_merah' => $request->score,
                    'deviasi_merah' => $request->deviation,
                    'timer_merah' => $request->time
                ]);
            } else {
                $data->update([
                    'score_biru' => $request->score,
                    'deviasi_biru' => $request->deviation,
                    'timer_biru' => $request->time
                ]);
            }

            // Create Medali with safety checks
            if ($peserta) {
                Medali::create([
                    'name' => ($namaSesi->nama ?? 'Sesi') . " - $ketSesi",
                    'id_peserta' => $peserta->id,
                    'kontigen' => $peserta->id_kontigen,
                    'kelas' => $peserta->kelas,
                    'kelamin' => $peserta->gender,
                    'kategori' => $peserta->category,
                    'point' => $request->score,
                    'keterangan' => "Selesai",
                ]);
            }

            $setting->update([
                'status' => '',
                'time' => ''
            ]);

            return response()->json([
                'data' => $request->deviation,
                'request' => $request->all(),
                'selected' => $selected,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveTime(Request $request)
    {
        try {
            $timer = $request->time;
            $arena = $request->arena;
            $partai = $request->partai;
            $sesi = $request->sesi;
            $poll = $request->poll;

            $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first();
            $setting = $settingData;
            $sesi = $settingData->sesi;
            $poll = $settingData->poll;
            $selected = "biru";
            $data = null;
            $medalis = '2';

            $isDiskualified = false;

            if ($setting->status == "diskualify") {
                $isDiskualified = true;
            }

            // $merahData = jadwal_group::where('merah', $request->id_user)
            //     ->where('arena', $request->arena)
            //     ->when($sesi ?? null, function ($query, $sesi) {
            //         $query->where('id_sesi', $sesi);
            //     }, function ($query) {
            //         $query->whereNull('id_sesi');
            //     })
            //     ->when($poll ?? null, function ($query, $poll) {
            //         $query->where('id_poll', $poll);
            //     }, function ($query) {
            //         $query->whereNull('id_poll');
            //     })->where('partai', $setting->partai)
            //     ->first();

            $merahData = jadwal_group::where('merah', $request->id_user)
                ->where('id', $settingData->jadwal)
                ->first();

            // $biruData = jadwal_group::where('biru', $request->id_user)
            //     ->when($sesi ?? null, function ($query, $sesi) {
            //         $query->where('id_sesi', $sesi);
            //     }, function ($query) {
            //         $query->whereNull('id_sesi');
            //     })
            //     ->when($poll ?? null, function ($query, $poll) {
            //         $query->where('id_poll', $poll);
            //     }, function ($query) {
            //         $query->whereNull('id_poll');
            //     })->where('arena', $request->arena)
            //     ->where('partai', $setting->partai)
            //     ->first();

            $biruData = jadwal_group::where('biru', $request->id_user)
                ->where('id', $settingData->jadwal)
                ->first();

            if ($merahData) {
                $data = $merahData;
                $selected = "merah";
            } else if ($biruData) {
                $data = $biruData;
                $selected = "biru";
            }

            if ($selected == "merah" && $data) {
                $namaSesi = SesiModel::where('id', $settingData->sesi)->first();
                $ketSesi = $data->keterangan ?? "Seni";
                $selectedParticipant = $data->merah;

                if ($selectedParticipant == $request->input('kalah')) {
                    $medalis = '3';
                } else {
                    $medalis = '5';
                    $selectedParticipant = $request->input('menang') ?? $selectedParticipant;
                }

                if ($isDiskualified) {
                    $medalis = '0';
                }

                $data->update([
                    'score_merah' => $request->score,
                    'deviasi_merah' => $request->deviation,
                    'timer_merah' => $request->time,
                    'status' => $isDiskualified ? "diskualifikasi" : "selesai"
                ]);

                if (!$isDiskualified) {
                    $peserta = PersertaModel::where('id', $selectedParticipant)->first();
                    if ($peserta) {
                        Medali::create([
                            'name' => ($namaSesi->nama ?? 'Sesi') . " - $ketSesi",
                            'id_peserta' => $selectedParticipant,
                            'kontigen' => $peserta->id_kontigen,
                            'kelas' => $peserta->kelas,
                            'kelamin' => $peserta->gender,
                            'kategori' => $peserta->category,
                            'point' => $medalis,
                            'keterangan' => "Selesai",
                        ]);
                    }
                }

            } else if ($selected == "biru" && $data) {
                $namaSesi = SesiModel::where('id', $settingData->sesi)->first();
                $ketSesi = $data->keterangan ?? "Seni";
                $selectedParticipant = $data->biru;

                if ($selectedParticipant == $request->input('kalah')) {
                    $medalis = '3';
                } else {
                    $medalis = '5';
                    $selectedParticipant = $request->input('menang') ?? $selectedParticipant;
                }

                if ($isDiskualified) {
                    $medalis = '0';
                }

                $data->update([
                    'score_biru' => $request->score,
                    'deviasi_biru' => $request->deviation,
                    'timer_biru' => $request->time,
                    'status' => $isDiskualified ? "diskualifikasi" : "selesai"
                ]);

                if (!$isDiskualified) {
                    $peserta = PersertaModel::where('id', $selectedParticipant)->first();
                    if ($peserta) {
                        Medali::create([
                            'name' => ($namaSesi->nama ?? 'Sesi') . " - $ketSesi",
                            'id_peserta' => $selectedParticipant,
                            'kontigen' => $peserta->id_kontigen,
                            'kelas' => $peserta->kelas,
                            'kelamin' => $peserta->gender,
                            'kategori' => $peserta->category,
                            'point' => $medalis,
                            'keterangan' => "Selesai",
                        ]);
                    }
                }
            }

            Setting::where('arena', $arena)->update([
                'time' => $timer,
                'status' => 'finished'
            ]);

            return response()->json([
                'request' => $request->all(),
                'selected' => $selected,
                'timer' => $timer,
                'arena' => $arena,
                'partai' => $partai
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function takeTimer(Request $request)
    {
        $arena = $request->arena;

        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first();

        if ($settingData->status != "finished") {
            return response()->json(['isDone' => false, 'time' => '00:00'], 200);
        } else {
            $time = $settingData->time ?? "00:00";
            $finalTime = [
                'menit' => "00",
                'detik' => "00",
            ];

            if ($time) {
                $arr = explode(':', $time);
                $finalTime['menit'] = $arr[0];
                $finalTime['detik'] = $arr[1];
            }

            $settingData->update([
                'status' => null,
                'time' => null,
            ]);

            return response()->json(['isDone' => true, 'time' => $finalTime], 200);
        }
    }

    public function senidata(Request $request)
    {
        $data = Setting::where('arena', $request->arena)->whereNotNull('judul')->first();

        if ($data->status != "finished") {

            if ($request->status_pertandingan == "diskualifikasi") {
                $data->update([
                    'status' => 'diskualify',
                ]);
            } else {
                $data->update(['status' => 'taking-time']);
            }
        }

        $jadwal = \App\jadwal_group::where('id', $data->jadwal)->first();

        if ($jadwal && $jadwal->keterangan == "prestasi") {
            if ($request->kategori == "tunggal") {
                return view('seni.rekapPrestasiTunggal', [
                    'id_user' => $request->id_user,
                    'arena' => $request->arena
                ]);
            } else {
                return view('seni.rekapPrestasiSolo', [
                    'id_user' => $request->id_user,
                    'arena' => $request->arena
                ]);
            }
        }

        if ($request->kategori == "tunggal") {
            return view('seni.rekapTunggal', [
                'id_user' => $request->id_user,
                'arena' => $request->arena
            ]);
        } else {

            return view('seni.rekapSolo', [
                'id_user' => $request->id_user,
                'arena' => $request->arena
            ]);
        }
    }
}
