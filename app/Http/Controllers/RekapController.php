<?php

namespace App\Http\Controllers;

use App\arena;
use App\Medali;
use App\PersertaModel;
use App\SesiModel;
use Illuminate\Http\Request;
use App\jadwal_group;
use App\score;
use App\Setting;
use App\PollingModel;

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

            $formattedScore = format_final_score_precision($request->score);

            if ($selected == "merah") {
                $data->update([
                    'score_merah' => $formattedScore,
                    'deviasi_merah' => $request->deviation,
                    'timer_merah' => $request->time,
                    'status' => 'finish',
                ]);
            } else {
                $data->update([
                    'score_biru' => $formattedScore,
                    'deviasi_biru' => $request->deviation,
                    'timer_biru' => $request->time,
                    'status' => 'finish',
                ]);
            }

            // Evaluasi medali seni
            if ($data && $data->keterangan == "pemasalan" && !empty($data->id_poll)) {
                self::checkAndAssignMedaliSeniPemasalan($data->id_poll);
            } else if ($data && $data->keterangan != "pemasalan" && $peserta) {
                $medaliExists = Medali::where('id_peserta', $peserta->id)->exists();
                if (!$medaliExists) {
                    Medali::create([
                        'name' => ($namaSesi->nama ?? 'Sesi') . " - $ketSesi",
                        'id_peserta' => $peserta->id,
                        'kontigen' => $peserta->id_kontigen,
                        'kelas' => $peserta->kelas,
                        'kelamin' => $peserta->gender,
                        'kategori' => $peserta->category,
                        'point' => $formattedScore,
                        'keterangan' => "seni",
                    ]);
                }
            }

            // $setting->update([
            //     'status' => '',
            //     'time' => ''
            // ]);

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
                $ketSesi = arena::where('id', $settingData->arena)->first()->name ?? "Seni";
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

                $formattedScore = format_final_score_precision($request->score);

                $data->update([
                    'score_merah' => $formattedScore,
                    'deviasi_merah' => $request->deviation,
                    'timer_merah' => $request->time,
                    'status' => $isDiskualified ? "diskualifikasi" : "finish"
                ]);

                if ($data->keterangan == "pemasalan" && !empty($data->id_poll)) {
                    self::checkAndAssignMedaliSeniPemasalan($data->id_poll);
                } else if (!$isDiskualified) {
                    $peserta = PersertaModel::where('id', $selectedParticipant)->first();
                    if ($peserta) {
                        $existingMedali = Medali::where('id_peserta', $selectedParticipant)->first();
                        if ($existingMedali) {
                            $existingMedali->update([
                                'point' => $medalis,
                                'keterangan' => 'seni'
                            ]);
                        } else {
                            Medali::create([
                                'name' => ($namaSesi->nama ?? 'Sesi') . " - $ketSesi",
                                'id_peserta' => $selectedParticipant,
                                'kontigen' => $peserta->id_kontigen,
                                'kelas' => $peserta->kelas,
                                'kelamin' => $peserta->gender,
                                'kategori' => $peserta->category,
                                'point' => $medalis,
                                'keterangan' => "seni",
                            ]);
                        }
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

                $formattedScore = format_final_score_precision($request->score);

                $data->update([
                    'score_biru' => $formattedScore,
                    'deviasi_biru' => $request->deviation,
                    'timer_biru' => $request->time,
                    'status' => $isDiskualified ? "diskualifikasi" : "finish"
                ]);

                if ($data->keterangan == "pemasalan" && !empty($data->id_poll)) {
                    self::checkAndAssignMedaliSeniPemasalan($data->id_poll);
                } else if (!$isDiskualified) {
                    $peserta = PersertaModel::where('id', $selectedParticipant)->first();
                    if ($peserta) {
                        $existingMedali = Medali::where('id_peserta', $selectedParticipant)->first();
                        if ($existingMedali) {
                            $existingMedali->update([
                                'point' => $medalis,
                                'keterangan' => 'seni'
                            ]);
                        } else {
                            Medali::create([
                                'name' => ($namaSesi->nama ?? 'Sesi') . " - $ketSesi",
                                'id_peserta' => $selectedParticipant,
                                'kontigen' => $peserta->id_kontigen,
                                'kelas' => $peserta->kelas,
                                'kelamin' => $peserta->gender,
                                'kategori' => $peserta->category,
                                'point' => $medalis,
                                'keterangan' => "seni",
                            ]);
                        }
                    }
                }
            }

            Setting::where('arena', $arena)->update([
                'time' => $timer,
                'status' => 'finish'
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

        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();
        $jadwalData = $settingData ? \App\jadwal_group::where('id', $settingData->jadwal)->first() : null;

        // Ambil timer yang paling relevan dari Setting atau jadwal_group
        $targetUser = $request->id_user ?? ($settingData ? $settingData->biru : null);
        $timeRaw = $settingData ? $settingData->time : null;
        if (empty($timeRaw) || $timeRaw === '00:00;00' || $timeRaw === '00:00:00') {
            if ($jadwalData) {
                if ($targetUser && $jadwalData->merah == $targetUser) {
                    $timeRaw = $jadwalData->timer_merah;
                } else if ($targetUser && $jadwalData->biru == $targetUser) {
                    $timeRaw = $jadwalData->timer_biru;
                } else {
                    $timeRaw = $jadwalData->timer_biru ?: $jadwalData->timer_merah;
                }
            }
        }
        if (empty($timeRaw)) {
            $timeRaw = '00:00;00';
        }

        $parsed = parse_timer_display($timeRaw);
        $finalTime = [
            'menit' => $parsed['minute'],
            'detik' => $parsed['second'],
            'ms' => $parsed['ms'],
            'formatted' => $parsed['formatted'],
            'raw' => $parsed['raw']
        ];

        $isDone = ($settingData && $settingData->status == "finish");

        return response()->json([
            'isDone' => $isDone,
            'time' => $finalTime,
            'status' => $settingData->status ?? "pending",
            'jadwal_id' => $settingData->jadwal ?? null,
            'partai' => $settingData->partai ?? null,
            'active_id' => $settingData->biru ?? null,
            'timer_biru' => $jadwalData->timer_biru ?? '00:00;00',
            'timer_merah' => $jadwalData->timer_merah ?? '00:00;00',
            'score_biru' => $jadwalData->score_biru ?? 0,
            'score_merah' => $jadwalData->score_merah ?? 0,
            'deviasi_biru' => $jadwalData->deviasi_biru ?? 0,
            'deviasi_merah' => $jadwalData->deviasi_merah ?? 0,
            'pemenang' => $jadwalData->pemenang ?? 'N/a',
        ], 200);
    }

    public function senidata(Request $request)
    {
        $data = Setting::where('arena', $request->arena)->whereNotNull('judul')->first();

        if (!$data) {
            return back()->with('error', 'Setting arena tidak ditemukan.');
        }

        $jadwal = \App\jadwal_group::where('id', $data->jadwal)->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        // Jika ada menang (dari form Tentukan Pemenang), simpan pemenang ke jadwal_group
        if ($request->has('menang') && $request->menang) {
            $jadwal->update([
                'pemenang' => $request->menang,
                'status' => 'finish',
            ]);

            $nameParam = $request->has('name') && $request->name ? '&name=' . urlencode($request->name) : '';

            // Redirect kembali ke halaman rekap dengan isDewan dan name
            if ($request->kategori == "tunggal") {
                return redirect('/redirect?arena=' . $request->arena . '&role=rekapPrestasiTunggal&isDewan=true' . $nameParam);
            } else {
                return redirect('/redirect?arena=' . $request->arena . '&role=rekapPrestasiSolo&isDewan=true' . $nameParam);
            }
        }

        // Simpan hasil pertandingan ke jadwal_group secara persisten & mandiri
        if ($request->status_pertandingan == "diskualifikasi") {
            $data->update([
                'status' => 'diskualify',
            ]);
            $jadwal->update([
                'status' => 'diskualifikasi',
            ]);
        } else {
            // Kalkulasi dan simpan langsung ke jadwal_groups (score, deviasi, timer, status finish)
            \App\Helpers\GlobalScoreHelper::calculateAndSaveSeniMatchResult(
                $request->arena, 
                $request->kategori ?? 'solo', 
                $request->id_user
            );
        }

        if ($jadwal && $jadwal->keterangan == "pemasalan" && !empty($jadwal->id_poll)) {
            self::checkAndAssignMedaliSeniPemasalan($jadwal->id_poll);
        }

        if ($jadwal->keterangan == "prestasi") {
            $juriNameStr = $request->has('id_juri') ? '&name=' . $request->id_juri : '';
            if ($request->kategori == "tunggal") {
                return redirect('/redirect?arena=' . $request->arena . '&role=rekapPrestasiTunggal&isDewan=true' . $juriNameStr);
            } else {
                return redirect('/redirect?arena=' . $request->arena . '&role=rekapPrestasiSolo&isDewan=true' . $juriNameStr);
            }
        }

        $idJuriParam = $request->id_juri ?? request('name') ?? null;
        if ($request->kategori == "tunggal") {
            return view('seni.rekapTunggal', [
                'id_user' => $request->id_user,
                'arena' => $request->arena,
                'id_juri' => $idJuriParam
            ]);
        } else {
            return view('seni.rekapSolo', [
                'id_user' => $request->id_user,
                'arena' => $request->arena,
                'id_juri' => $idJuriParam
            ]);
        }
    }

    /**
     * Konfigurasi dan evaluasi pemberian medali seni pemasalan.
     * Aturan:
     * - Hanya diproses jika SEMUA pertandingan pada pool tersebut sudah dalam status selesai dan memiliki nilai.
     * - Yang mendapat medali adalah ranking 1-4 dalam pool pemasalan:
     *   Rank 1: Emas (point 5)
     *   Rank 2: Perak (point 3)
     *   Rank 3: Perunggu (point 2)
     *   Rank 4: Perunggu (point 2)
     *   Rank 5+: Tidak mendapat medali.
     *
     * @param int|string $id_poll
     * @return bool
     */
    public static function checkAndAssignMedaliSeniPemasalan($id_poll)
    {
        if (empty($id_poll)) {
            return false;
        }

        $poolMatches = jadwal_group::where('id_poll', $id_poll)
            ->where('keterangan', 'pemasalan')
            ->get();

        if ($poolMatches->isEmpty()) {
            return false;
        }

        // Kumpulkan semua ID peserta yang ada di pool ini
        $pesertaIdsInPool = $poolMatches->map(function ($m) {
            return ($m->biru != 'seni' && !empty($m->biru)) ? $m->biru : $m->merah;
        })->filter()->unique()->values();

        // 1. Verifikasi syarat: SEMUA pertandingan pada pool tersebut sudah dalam status selesai dan memiliki nilai
        $allCompleted = true;
        foreach ($poolMatches as $match) {
            $status = strtolower($match->status ?? '');
            $isStatusDone = in_array($status, ['selesai', 'finish', 'diskualifikasi']);
            $score = floatval($match->score_biru ?? $match->score_merah ?? 0);
            $hasScore = ($score > 0) || ($status === 'diskualifikasi');

            if (!$isStatusDone || !$hasScore) {
                $allCompleted = false;
                break;
            }
        }

        // Jika BELUM semua pertandingan berstatus selesai dan memiliki nilai:
        // Medali baru akan diberikan jika SEMUA pertandingan sudah selesai dan bernilai.
        // Hapus medali seni yang sempat terbuat secara prematur untuk peserta di pool ini.
        if (!$allCompleted) {
            Medali::whereIn('id_peserta', $pesertaIdsInPool)
                ->where('keterangan', 'seni')
                ->delete();
            return false;
        }

        // 2. Jika SEMUA pertandingan sudah selesai dan bernilai:
        // Urutkan peserta dari ranking tertinggi ke terendah
        // Ranking: Skor tertinggi (DESC), tie-break Deviasi (DESC)
        $validMatches = $poolMatches->filter(function ($m) {
            return strtolower($m->status ?? '') !== 'diskualifikasi';
        })->sort(function ($a, $b) {
            $scoreA = floatval($a->score_biru ?? $a->score_merah ?? 0);
            $scoreB = floatval($b->score_biru ?? $b->score_merah ?? 0);
            if ($scoreA == $scoreB) {
                $devA = floatval($a->deviasi_biru ?? $a->deviasi_merah ?? 0);
                $devB = floatval($b->deviasi_biru ?? $b->deviasi_merah ?? 0);
                return $devB <=> $devA;
            }
            return $scoreB <=> $scoreA;
        })->values();

        // Bersihkan data medali lama untuk peserta di pool ini agar sinkron & idempotent
        Medali::whereIn('id_peserta', $pesertaIdsInPool)
            ->where('keterangan', 'seni')
            ->delete();

        $pollData = PollingModel::where('id', $id_poll)->first();
        $namaPoll = $pollData->name ?? ("Pool " . $id_poll);

        // Alokasi medali:
        // Rank 1: Emas (point 5)
        // Rank 2: Perak (point 3)
        // Rank 3: Perunggu (point 2)
        // Rank 4: Perunggu (point 2)
        $medalAllocation = [
            0 => ['point' => '5', 'label' => 'Juara 1 (Emas)'],
            1 => ['point' => '3', 'label' => 'Juara 2 (Perak)'],
            2 => ['point' => '2', 'label' => 'Juara 3 (Perunggu)'],
            3 => ['point' => '2', 'label' => 'Juara 3 Bersama (Perunggu)'],
        ];

        foreach ($validMatches as $idx => $match) {
            if ($idx >= 4) {
                // Ranking 5 ke atas tidak mendapatkan medali
                break;
            }

            $idPeserta = ($match->biru != 'seni' && !empty($match->biru)) ? $match->biru : $match->merah;
            $peserta = PersertaModel::where('id', $idPeserta)->first();
            if (!$peserta) {
                continue;
            }

            $arenaData = arena::where('id', $match->arena)->first();
            $namaArena = $arenaData->name ?? ("Arena " . $match->arena);
            $medaliInfo = $medalAllocation[$idx];

            Medali::create([
                'name' => "$namaArena - $namaPoll ({$medaliInfo['label']})",
                'id_peserta' => $peserta->id,
                'kontigen' => $peserta->id_kontigen,
                'kelas' => $peserta->kelas,
                'kelamin' => $peserta->gender,
                'kategori' => $peserta->category,
                'point' => $medaliInfo['point'],
                'keterangan' => 'seni',
                'status' => 'selesai',
            ]);
        }

        return true;
    }

    public static function syncAllSeniPemasalanMedali()
    {
        $pollIds = jadwal_group::where('keterangan', 'pemasalan')
            ->whereNotNull('id_poll')
            ->distinct()
            ->pluck('id_poll');

        foreach ($pollIds as $pollId) {
            self::checkAndAssignMedaliSeniPemasalan($pollId);
        }
    }
}
