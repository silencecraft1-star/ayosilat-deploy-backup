<?php

namespace App\Helpers;


use App\category;
use App\Events\DewanEvent;
use App\Events\JuriEvent;
use App\Events\ScoreEvent;
use App\Events\SoloEvent;
use App\Events\TunggalEvent;
use App\Events\VerificationEvent;
use Illuminate\Http\Request;
use App\score;
use App\arena;
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

class GlobalScoreHelper
{
    /**
     * Resolusi konfigurasi WMP (status aktif & threshold perbedaan poin) berdasarkan kategori pesilat.
     *
     * @param mixed $setting
     * @param mixed $pesertaBiru
     * @param mixed $pesertaMerah
     * @return array ['is_active' => bool, 'threshold' => int]
     */
    public static function getWmpConfigForMatch($setting, $pesertaBiru = null, $pesertaMerah = null)
    {
        $kategoriId = null;
        if ($pesertaBiru && !empty($pesertaBiru->category)) {
            $kategoriId = $pesertaBiru->category;
        } elseif ($pesertaMerah && !empty($pesertaMerah->category)) {
            $kategoriId = $pesertaMerah->category;
        }

        if ($kategoriId) {
            $category = category::where('id', $kategoriId)->first();
            if ($category) {
                // Jika kategori ini secara eksplisit menonaktifkan WMP (is_wmp = false / 0)
                if (isset($category->is_wmp) && !$category->is_wmp) {
                    return [
                        'is_active' => false,
                        'threshold' => (int) ($category->perbedaan_poin ?? 30),
                    ];
                }

                $threshold = 30;
                if ($category->perbedaan_poin !== null && is_numeric($category->perbedaan_poin) && (int) $category->perbedaan_poin > 0) {
                    $threshold = (int) $category->perbedaan_poin;
                } else {
                    $categoryName = (string) $category->name;
                    if (preg_match('/pra[\s\-_]?remaja/i', $categoryName)) {
                        $threshold = 20;
                    } elseif (preg_match('/remaja/i', $categoryName)) {
                        $threshold = 30;
                    }
                }

                return [
                    'is_active' => true,
                    'threshold' => $threshold,
                ];
            }
        }

        return [
            'is_active' => true,
            'threshold' => 30,
        ];
    }

    /**
     * Resolusi threshold perbedaan poin WMP (Wasit Menghentikan Pertandingan) berdasarkan kategori pesilat.
     * Default: 30 poin, Pra Remaja: 20 poin, Remaja: 30 poin.
     *
     * @param mixed $setting
     * @param mixed $pesertaBiru
     * @param mixed $pesertaMerah
     * @return int
     */
    public static function getWmpThresholdForMatch($setting, $pesertaBiru = null, $pesertaMerah = null)
    {
        $config = self::getWmpConfigForMatch($setting, $pesertaBiru, $pesertaMerah);
        return $config['threshold'];
    }

    public function sendTandingScore($arena, $sesi, $partai, $tipe = null, $keterangan = "score")
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $arenaData = arena::where('id', $arena)->first();

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

        $babakData = [
            'biru' => [
                'binaan1' => [0, 0, 0, 0],
                'binaan2' => [0, 0, 0, 0],
                'teguran1' => [0, 0, 0, 0],
                'teguran2' => [0, 0, 0, 0]
            ],
            'merah' => [
                'binaan1' => [0, 0, 0, 0],
                'binaan2' => [0, 0, 0, 0],
                'teguran1' => [0, 0, 0, 0],
                'teguran2' => [0, 0, 0, 0]
            ]
        ];

        $binaanSum = ['biru' => [0, 0], 'merah' => [0, 0]];
        $teguranSum = ['biru' => [0, 0], 'merah' => [0, 0]];

        $participants = [
            'biru' => [$setting->biru, 0, 0, 0, 0],
            'merah' => [$setting->merah, 0, 0, 0, 0]
        ];

        foreach ($participants as $key => $pInfo) {
            $id = $pInfo[0];
            for ($babakLoop = 1; $babakLoop <= 3; $babakLoop++) {
                $binaan = score::where('keterangan', 'binaan')->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('arena', $arena)->where('id_perserta', $id)->where('babak', $babakLoop)->count();

                $valB1 = ($binaan >= 1) ? 1 : 0;
                $valB2 = ($binaan > 1) ? 1 : 0;
                $binaanSum[$key][0] += $valB1;
                $binaanSum[$key][1] += $valB2;
                $babakData[$key]['binaan1'][$babakLoop] = $valB1;
                $babakData[$key]['binaan2'][$babakLoop] = $valB2;

                $teguran = score::where('keterangan', 'teguran')->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('arena', $arena)->where('id_perserta', $id)->where('babak', $babakLoop)->count();

                $valT1 = ($teguran >= 1) ? 1 : 0;
                $valT2 = ($teguran > 1) ? 1 : 0;
                $teguranSum[$key][0] += $valT1;
                $teguranSum[$key][1] += $valT2;
                $babakData[$key]['teguran1'][$babakLoop] = $valT1;
                $babakData[$key]['teguran2'][$babakLoop] = $valT2;
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
        $jadwalGroupObj = null;
        if (!empty($setting->jadwal)) {
            $jadwalGroupObj = jadwal_group::where('id', $setting->jadwal)->first();
        }
        if (!$jadwalGroupObj) {
            $jadwalGroupObj = jadwal_group::where('arena', $arena)->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->first();
        }

        $statusPertandingan = $jadwalGroupObj->status ?? "pending";
        $keteranganPertandingan = $jadwalGroupObj->keterangan ?? "PENYISIHAN";
        if (empty($keteranganPertandingan) || strtolower($keteranganPertandingan) === 'pemasalan') {
            $keteranganPertandingan = "PENYISIHAN";
        }

        $pesertaRef = $pesertaMerah ?: $pesertaBiru;
        $kelasRow = ($pesertaRef && $pesertaRef->kelas) ? kelas::where('id', $pesertaRef->kelas)->first() : null;
        $kategoriRow = ($pesertaRef && $pesertaRef->category) ? category::where('id', $pesertaRef->category)->first() : null;
        $infoKelas = $kelasRow->name ?? '-';
        $infoKategori = $kategoriRow->name ?? '-';
        $infoGender = $pesertaRef->gender ?? '-';


        if (!empty($setting)) {


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
                    'babak' => $setting->babak,
                    'partai' => $partaiFinal,
                    'idBiru' => $pesertaBiru->id,
                    'idMerah' => $pesertaMerah->id,
                    'namaBiru' => $namaBiru,
                    'namaMerah' => $namaMerah,
                    'kontigenBiru' => $kontigenBiru,
                    'kontigenMerah' => $kontigenMerah,
                    'statusPertandingan' => $statusPertandingan,
                    'keteranganPertandingan' => $keteranganPertandingan,
                    'pukulanb' => $pukulanb,
                    'pukulanm' => $pukulanm,
                    'tendanganb' => $tendanganb,
                    'tendanganm' => $tendanganm,
                    'infoKelas' => "$infoKelas | $infoKategori",
                    'namaKelas' => $infoKelas,
                    'namaKategori' => $infoKategori,
                    'infoGender' => $infoGender,
                    'jatuh1' => 0,
                    'binaan1' => 0,
                    'teguran1' => 0,
                    'totalBinaan1Biru' => $binaanSum['biru'][0],
                    'totalBinaan2Biru' => $binaanSum['biru'][1],
                    'totalTeguran1Biru' => $teguranSum['biru'][0],
                    'totalTeguran2Biru' => $teguranSum['biru'][1],
                    'totalBinaan1Merah' => $binaanSum['merah'][0],
                    'totalBinaan2Merah' => $binaanSum['merah'][1],
                    'totalTeguran1Merah' => $teguranSum['merah'][0],
                    'totalTeguran2Merah' => $teguranSum['merah'][1],

                    // Per-Babak Data
                    'b1b_1' => $babakData['biru']['binaan1'][1],
                    'b1b_2' => $babakData['biru']['binaan1'][2],
                    'b1b_3' => $babakData['biru']['binaan1'][3],
                    'b2b_1' => $babakData['biru']['binaan2'][1],
                    'b2b_2' => $babakData['biru']['binaan2'][2],
                    'b2b_3' => $babakData['biru']['binaan2'][3],
                    't1b_1' => $babakData['biru']['teguran1'][1],
                    't1b_2' => $babakData['biru']['teguran1'][2],
                    't1b_3' => $babakData['biru']['teguran1'][3],
                    't2b_1' => $babakData['biru']['teguran2'][1],
                    't2b_2' => $babakData['biru']['teguran2'][2],
                    't2b_3' => $babakData['biru']['teguran2'][3],

                    'b1m_1' => $babakData['merah']['binaan1'][1],
                    'b1m_2' => $babakData['merah']['binaan1'][2],
                    'b1m_3' => $babakData['merah']['binaan1'][3],
                    'b2m_1' => $babakData['merah']['binaan2'][1],
                    'b2m_2' => $babakData['merah']['binaan2'][2],
                    'b2m_3' => $babakData['merah']['binaan2'][3],
                    't1m_1' => $babakData['merah']['teguran1'][1],
                    't1m_2' => $babakData['merah']['teguran1'][2],
                    't1m_3' => $babakData['merah']['teguran1'][3],
                    't2m_1' => $babakData['merah']['teguran2'][1],
                    't2m_2' => $babakData['merah']['teguran2'][2],
                    't2m_3' => $babakData['merah']['teguran2'][3],
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
                    'socket_status' => $keterangan,
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

                $wmpConfig = self::getWmpConfigForMatch($setting, $pesertaBiru, $pesertaMerah);
                $wmpThreshold = $wmpConfig['threshold'];
                $isWmpActive = $wmpConfig['is_active'];
                $isWmp = $isWmpActive && (abs($response['score1'] - $response['score2']) >= $wmpThreshold);

                $response['selisih_20'] = $isWmp;
                $response['is_wmp'] = $isWmp;
                $response['wmp_active'] = $isWmpActive;
                $response['wmp_threshold'] = $wmpThreshold;

                if ($tipe == null) {
                    event(new ScoreEvent($response));
                }
                return $response;
            }

        }
    }

    public function sendPendingData($arena, $sesi, $partai, $settingData)
    {

        $settingData = Setting::where('id', $settingData->id)->first();
        $sesi = $settingData->sesi;

        $pendingSend = pending_tanding::where('arena', $arena)
            ->where('partai', $settingData->partai)
            ->when($sesi ?? null, function ($query, $sesi) {
                $query->where('id_sesi', $sesi);
            }, function ($query) {
                $query->whereNull('id_sesi');
            })->get();

        $biruData = PersertaModel::where('id', $settingData->biru)->first();
        $kontigenBiru = $biruData ? KontigenModel::where('id', $biruData->id_kontigen)->first() : null;

        $merahData = PersertaModel::where('id', $settingData->merah)->first();
        $kontigenMerah = $merahData ? KontigenModel::where('id', $merahData->id_kontigen)->first() : null;

        $jadwalData = null;
        if (!empty($settingData->jadwal)) {
            $jadwalData = jadwal_group::where('id', $settingData->jadwal)->first();
        }
        if (!$jadwalData) {
            $jadwalData = jadwal_group::where('arena', $arena)
                ->where('partai', $settingData->partai)
                ->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->first();
        }

        $keteranganPertandingan = $jadwalData->keterangan ?? 'PENYISIHAN';
        if (empty($keteranganPertandingan) || strtolower($keteranganPertandingan) === 'pemasalan') {
            $keteranganPertandingan = 'PENYISIHAN';
        }

        $refPeserta = $merahData ?: $biruData;
        $kelasInfo = ($refPeserta && $refPeserta->kelas) ? kelas::where('id', $refPeserta->kelas)->first() : null;
        $kategoriInfo = ($refPeserta && $refPeserta->category) ? category::where('id', $refPeserta->category)->first() : null;
        $namaKelas = $kelasInfo->name ?? '-';
        $namaKategori = $kategoriInfo->name ?? '-';
        $gender = $refPeserta->gender ?? '-';

        $data = [
            'arena' => $arena,
            'sesi' => $sesi,
            'data' => $pendingSend,
            'babak' => $settingData->babak,
            'partai' => $settingData->partai,
            'keteranganPertandingan' => $keteranganPertandingan,
            'namaKelas' => $namaKelas,
            'namaKategori' => $namaKategori,
            'infoKelas' => "$namaKelas | $namaKategori",
            'infoGender' => $gender,
            'juri' => [
                'juri_1' => $settingData->juri_1,
                'juri_2' => $settingData->juri_2,
                'juri_3' => $settingData->juri_3,
            ],
            'biru' => [
                'id' => $biruData->id ?? '',
                'nama' => $biruData->name ?? '',
                'kontigen' => $kontigenBiru->kontigen ?? ''
            ],
            'merah' => [
                'id' => $merahData->id ?? '',
                'nama' => $merahData->name ?? '',
                'kontigen' => $kontigenMerah->kontigen ?? ''
            ]
        ];

        event(new JuriEvent($data));
        return $data;
    }

    public function sendDewanData($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $partai = $setting->partai;

        $data = [
            (object) [
                'id' => $setting->biru,
                'partai' => $partai,
                // 'babak' => $setting->babak,
                'sesi' => $setting->sesi,
                'tim' => 'biru',
                'keterangan' => [
                    'jatuh',
                    'binaan',
                    'teguran',
                    'peringatan',
                ]
            ],
            (object) [
                'id' => $setting->merah,
                'partai' => $partai,
                // 'babak' => $setting->babak,
                'sesi' => $setting->sesi ?? null,
                'tim' => 'merah',
                'keterangan' => [
                    'jatuh',
                    'binaan',
                    'teguran',
                    'peringatan',
                ]
            ]
        ];

        $response = [
            'partai' => $partai,
            'babak' => $setting->babak,
            'sesi' => $setting->sesi,
            'arena' => $setting->arena,
            'peringatan' => 0,
            'data' => [
                '1' => [
                    'biru' => [
                        'jatuh' => 0,
                        'binaan' => 0,
                        'teguran' => 0,
                        'peringatan' => 0,
                    ],
                    'merah' => [
                        'jatuh' => 0,
                        'binaan' => 0,
                        'teguran' => 0,
                        'peringatan' => 0,
                    ],
                ],
                '2' => [
                    'biru' => [
                        'jatuh' => 0,
                        'binaan' => 0,
                        'teguran' => 0,
                        'peringatan' => 0,
                    ],
                    'merah' => [
                        'jatuh' => 0,
                        'binaan' => 0,
                        'teguran' => 0,
                        'peringatan' => 0,
                    ],
                ],
                '3' => [
                    'biru' => [
                        'jatuh' => 0,
                        'binaan' => 0,
                        'teguran' => 0,
                        'peringatan' => 0,
                    ],
                    'merah' => [
                        'jatuh' => 0,
                        'binaan' => 0,
                        'teguran' => 0,
                        'peringatan' => 0,
                    ],
                ]
            ]
        ];
        foreach ($data as $item) {
            $id_sesi = $item->sesi;
            foreach ($response['data'] as $babak => $nilaiPerBabak) {
                foreach ($item->keterangan as $keterangan) {
                    $total = score::where('keterangan', $keterangan)
                        ->where('babak', $babak)
                        ->where('partai', $item->partai)
                        ->when($id_sesi ?? null, function ($query, $id_sesi) {
                            $query->where('id_sesi', $id_sesi);
                        }, function ($query) {
                            $query->whereNull('id_sesi');
                        })
                        ->where('id_perserta', $item->id)
                        ->count();

                    if ($keterangan == 'peringatan') {
                        $peringatan = score::where('keterangan', $keterangan)
                            ->where('partai', $item->partai)
                            ->when($id_sesi ?? null, function ($query, $id_sesi) {
                                $query->where('id_sesi', $id_sesi);
                            }, function ($query) {
                                $query->whereNull('id_sesi');
                            })
                            ->where('id_perserta', $item->id)
                            ->count();

                        $response['data'][$babak][$item->tim][$keterangan] = $peringatan;
                    } else {
                        $response['data'][$babak][$item->tim][$keterangan] = $total;
                    }
                }
            }
        }

        event(new DewanEvent($response));
        return $response;
    }

    public function cekJuriTanding($arena, $sesi, $partai)
    {
        // $pending = pending_tanding::where('id_perserta',"$id")->first();
        $threshold = Carbon::now()->subSeconds(3);

        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first();

        $this->sendPendingData($arena, $settingData->sesi ?? null, $settingData->partai, $settingData);

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
                        // event(new JuriEvent($pendingSend));

                        $this->sendPendingData($data->arena, $settingData->sesi ?? null, $settingData->partai, $settingData);
                        $this->sendTandingScore($data->arena, $settingData->sesi ?? null, $settingData->partai);

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
                }
            }
        }

    }

    public function sendSeniIndicator($arena, $sesi, $partai, $id_juri, $status, $tipe)
    {
        if ($tipe == "tunggal") {
            $data = [
                'arena' => $arena,
                'partai' => $partai,
                'id_juri' => $id_juri,
                'sesi' => $sesi ?? null,
                'status' => $status,
                'tipe' => 'notif'
            ];

            event(new TunggalEvent($data));
        } else {
            $data = [
                'arena' => $arena,
                'partai' => $partai,
                'id_juri' => $id_juri,
                'sesi' => $sesi ?? null,
                'status' => $status,
                'tipe' => 'notif'
            ];

            event(new SoloEvent($data));
        }
    }

    public function getSeniData($arena, $kt = 'ganda')
    {
        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$settingData) {
            return null;
        }

        $currentPlaying = null;
        $cekBiru = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('biru', $settingData->biru)->where('partai', $settingData->partai)->first();
        $cekMerah = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('merah', $settingData->biru)->where('partai', $settingData->partai)->first();

        if ($cekBiru) {
            $currentPlaying = "biru";
        }
        if ($cekMerah) {
            $currentPlaying = "merah";
        }

        $partai = $settingData->partai;
        $id = $settingData->biru;

        $data = score::where('id_perserta', $id)->where('partai', $partai)->where('arena', $arena)->get();
        $penaltiesSummary = get_dewan_penalties_summary($id, $arena, $partai);
        $dewan = $penaltiesSummary['total_score'];
        $jadwalGanda = jadwal_group::where('id', $settingData->jadwal)->first();

        $pesertaBiru = PersertaModel::where('id', $settingData->biru)->first();
        if (!$pesertaBiru) {
            return null;
        }

        $kelas = kelas::where('id', $pesertaBiru->kelas)->first();
        $kontigenBiru = KontigenModel::where('id', $pesertaBiru->id_kontigen)->first();
        $namaBiru = $pesertaBiru->name;
        $keteranganJadwal = ($jadwalGanda && $jadwalGanda->keterangan) ? "- $jadwalGanda->keterangan" : "";

        $response = [
            'current' => $currentPlaying,
            'detailPartai' => "{$settingData->partai} {$keteranganJadwal}",
            'nama' => $namaBiru,
            'id_peserta' => $pesertaBiru->id,
            'gender' => $pesertaBiru->gender,
            'kelas' => $kelas ? $kelas->name : '',
            'kontigen' => $kontigenBiru ? $kontigenBiru->kontigen : '',
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
            'dewan' => (float)$dewan,
            'penalties' => $penaltiesSummary['items'],
            'time' => $settingData->time,
            'status' => $settingData->status,
            'keterangan_jadwal' => ($jadwalGanda && $jadwalGanda->keterangan) ? $jadwalGanda->keterangan : 'pemasalan',
        ];

        foreach ($data as $item) {
            for ($i = 1; $i <= 8; $i++) {
                $juriField = 'juri_' . $i;
                if ($item->id_juri === $settingData->$juriField) {
                    if ($item->keterangan === "attack") {
                        $response['attack' . $i] = (float)$item->score;
                    } elseif ($item->keterangan === "firmness") {
                        $response['firmness' . $i] = (float)$item->score;
                    } elseif ($item->keterangan === "soulfullness") {
                        $response['soulfullness' . $i] = (float)$item->score;
                    }
                }
            }
        }

        return $response;
    }

    public function getTunggalData($arena)
    {
        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$settingData) {
            return null;
        }

        $currentPlaying = null;
        $cekBiru = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('biru', $settingData->biru)->where('partai', $settingData->partai)->first();
        $cekMerah = jadwal_group::where('arena', $arena)->where('keterangan', 'prestasi')->where('merah', $settingData->biru)->where('partai', $settingData->partai)->first();

        if ($cekBiru) {
            $currentPlaying = "biru";
        }
        if ($cekMerah) {
            $currentPlaying = "merah";
        }

        $partai = $settingData->partai;
        $pesertaBiru = PersertaModel::where('id', $settingData->biru)->first();
        if (!$pesertaBiru) {
            return null;
        }

        $data = score::where('id_perserta', $pesertaBiru->id)->where('partai', $partai)->where('arena', $arena)->get();
        $penaltiesSummary = get_dewan_penalties_summary($pesertaBiru->id, $arena, $partai);
        $dewan = $penaltiesSummary['total_score'];
        $jadwalTunggal = jadwal_group::where('id', $settingData->jadwal)->first();

        $partaiFinal = $settingData->partai;
        $kelas = kelas::where('id', $pesertaBiru->kelas)->first();
        $kontigenBiru = KontigenModel::where('id', $pesertaBiru->id_kontigen)->first();
        $namaBiru = $pesertaBiru->name;

        if ($kelas && $kelas->name == "REGU") {
            $dataRegu = PersertaModel::where('id_kontigen', $pesertaBiru->id_kontigen)->get();
            $pesertaRegu = '';
            foreach ($dataRegu as $item) {
                $pesertaRegu .= "$item->name,";
            }
            $namaBiru = $pesertaRegu;
        }

        $keteranganJadwalTunggal = ($jadwalTunggal && $jadwalTunggal->keterangan) ? "- $jadwalTunggal->keterangan" : "";

        $response = [
            'current' => $currentPlaying,
            'detailArena' => "$partaiFinal $keteranganJadwalTunggal",
            'id_peserta' => $pesertaBiru->id,
            'nama' => $namaBiru,
            'gender' => $pesertaBiru->gender,
            'kelas' => $kelas ? $kelas->name : '',
            'kontigen' => $kontigenBiru ? $kontigenBiru->kontigen : '',
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
            'dewan' => (float)$dewan,
            'penalties' => $penaltiesSummary['items'],
            'time' => $settingData->time,
            'status' => $settingData->status,
            'keterangan_jadwal' => ($jadwalTunggal && $jadwalTunggal->keterangan) ? $jadwalTunggal->keterangan : 'pemasalan',
        ];

        foreach ($data as $item) {
            for ($i = 1; $i <= 8; $i++) {
                $juriField = 'juri_' . $i;
                if ($item->id_juri === $settingData->$juriField) {
                    if ($item->keterangan === "next") {
                        $response['actual' . $i] = 9.90 - ($item->score / 100);
                    } elseif ($item->keterangan === "flwo") {
                        $response['flwo' . $i] = (float)$item->score;
                    }
                }
            }
        }

        return $response;
    }

    public function sendTunggalData($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$setting) {
            return;
        }

        $response = $this->getTunggalData($arena);

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'data',
            'response' => $response
        ];

        event(new TunggalEvent($datas));
    }

    public function sendTunggalScore($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$setting) {
            return;
        }

        $peserta = PersertaModel::where('id', $setting->biru)->first();
        $kontigen = $peserta ? KontigenModel::where('id', $peserta->id_kontigen)->first() : null;

        $infos = [
            'id' => $peserta ? $peserta->id : null,
            'name' => $peserta ? $peserta->name : '',
            'kontigen' => $kontigen ? $kontigen->kontigen : '',
        ];

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'update',
            'data' => $infos
        ];

        event(new TunggalEvent($datas));
    }

    public function sendSoloScore($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$setting) {
            return;
        }

        $peserta = PersertaModel::where('id', $setting->biru)->first();
        $kontigen = $peserta ? KontigenModel::where('id', $peserta->id_kontigen)->first() : null;

        $infos = [
            'id' => $peserta ? $peserta->id : null,
            'name' => $peserta ? $peserta->name : '',
            'kontigen' => $kontigen ? $kontigen->kontigen : '',
        ];

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'update',
            'data' => $infos
        ];

        event(new SoloEvent($datas));
    }

    public function sendDewanTunggal($arena)
    {
        $this->sendTunggalData($arena);
    }

    public function sendSoloData($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$setting) {
            return;
        }

        $response = $this->getSeniData($arena, 'ganda');

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'data',
            'response' => $response
        ];

        event(new SoloEvent($datas));
    }

    /**
     * Hitung resmi skor seni (Solo / Tunggal) dan simpan langsung ke jadwal_group serta Setting
     * Memastikan data pertandingan seni 100% tersimpan ke jadwal saat Dewan menyelesaikan pertandingan,
     * tanpa bergantung pada tab monitor/scoreboard eksternal.
     *
     * @param int|string $arena
     * @param string $kategori 'solo'|'tunggal'|'ganda'
     * @param int|string|null $id_user
     * @return array|null
     */
    public static function calculateAndSaveSeniMatchResult($arena, $kategori = 'solo', $id_user = null)
    {
        $settingData = Setting::where('arena', $arena)->whereNotNull('judul')->first()
            ?? Setting::where('arena', $arena)->first();

        if (!$settingData) {
            return null;
        }

        $jadwal = jadwal_group::where('id', $settingData->jadwal)->first();
        if (!$jadwal) {
            return null;
        }

        $targetUser = $id_user ?? $settingData->biru;
        $isMerah = ($jadwal->merah == $targetUser);

        // Ambil jumlah juri dari admin-setting (default 4)
        $settingGlobal = Setting::where('keterangan', 'admin-setting')->first();
        $juriCount = (int)($settingGlobal->jadwal ?? 4);
        if ($juriCount < 1) {
            $juriCount = 4;
        }

        $isTunggal = (strtolower((string)$kategori) === 'tunggal');
        $partai = $settingData->partai;
        $scoresPerJuri = [];

        for ($i = 1; $i <= $juriCount; $i++) {
            $juriField = 'juri_' . $i;
            $juriId = $settingData->$juriField;

            if ($isTunggal) {
                // Tunggal: actual + flwo
                $actual = score::where('id_perserta', $targetUser)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('id_juri', $juriId)
                    ->where('keterangan', 'actual')
                    ->value('score');
                $actual = $actual !== null ? (float)$actual : 9.90;

                $flwo = score::where('id_perserta', $targetUser)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('id_juri', $juriId)
                    ->where('keterangan', 'flwo')
                    ->value('score');
                $flwo = $flwo !== null ? (float)$flwo : 0.00;

                $jScore = round($actual + $flwo, 3);
            } else {
                // Solo / Ganda: attack + firmness + soulfullness + 9.10
                $att = score::where('id_perserta', $targetUser)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('id_juri', $juriId)
                    ->where('keterangan', 'attack')
                    ->value('score') ?? 0;
                $firm = score::where('id_perserta', $targetUser)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('id_juri', $juriId)
                    ->where('keterangan', 'firmness')
                    ->value('score') ?? 0;
                $soul = score::where('id_perserta', $targetUser)
                    ->where('arena', $arena)
                    ->where('partai', $partai)
                    ->where('id_juri', $juriId)
                    ->where('keterangan', 'soulfullness')
                    ->value('score') ?? 0;

                $jScore = round((float)$att + (float)$firm + (float)$soul + 9.10, 3);
            }

            $scoresPerJuri[] = $jScore;
        }

        // Kalkulasi Median
        $sortedScores = $scoresPerJuri;
        sort($sortedScores);
        $count = count($sortedScores);
        if ($count > 0) {
            $mid = (int)floor($count / 2);
            if ($count % 2 === 0) {
                $median = ($sortedScores[$mid - 1] + $sortedScores[$mid]) / 2;
            } else {
                $median = $sortedScores[$mid];
            }
        } else {
            $median = 0.0;
        }
        $median = round($median, 3);

        // Pengurangan Dewan
        $penalties = get_dewan_penalties_summary($targetUser, $arena, $partai);
        $totalDewan = (float)($penalties['total_score'] ?? 0);

        // Skor Akhir Resmi (Presisi minimal 2 digit, maksimal 3 digit desimal)
        $finalScore = max(0, round($median - $totalDewan, 3));
        $finalScoreFormatted = format_final_score_precision($finalScore);

        // Deviasi Standar (Presisi penuh tanpa pembulatan pemotongan)
        $deviation = 0.0;
        if ($count > 0) {
            $avg = array_sum($scoresPerJuri) / $count;
            $variance = 0.0;
            foreach ($scoresPerJuri as $sc) {
                $variance += pow($sc - $avg, 2);
            }
            $deviation = sqrt($variance / $count);
        }

        // Waktu Pertandingan
        $savedTime = $settingData->time 
            ?? ($isMerah ? $jadwal->timer_merah : $jadwal->timer_biru) 
            ?? $jadwal->kondisi 
            ?? '00:00;00';
        $timerParsed = parse_timer_display($savedTime);
        $formattedTimer = $timerParsed['formatted'];

        // Persist langsung ke jadwal_group
        if ($isMerah) {
            $jadwal->update([
                'score_merah' => $finalScoreFormatted,
                'deviasi_merah' => $deviation,
                'timer_merah' => $formattedTimer,
                'status' => 'finish',
            ]);
        } else {
            $jadwal->update([
                'score_biru' => $finalScoreFormatted,
                'deviasi_biru' => $deviation,
                'timer_biru' => $formattedTimer,
                'status' => 'finish',
            ]);
        }

        // Update Setting arena status menjadi finish
        $settingData->update([
            'status' => 'finish',
            'time' => $formattedTimer,
        ]);

        // Cek medali pemasalan jika relevan
        if ($jadwal->keterangan === 'pemasalan' && !empty($jadwal->id_poll)) {
            \App\Http\Controllers\RekapController::checkAndAssignMedaliSeniPemasalan($jadwal->id_poll);
        }

        // Broadcast websocket agar monitor & score screen terupdate
        $helper = new self();
        if ($isTunggal) {
            $helper->sendTunggalData($arena);
        } else {
            $helper->sendSoloData($arena);
        }

        return [
            'final_score' => $finalScoreFormatted,
            'median' => $median,
            'deviation' => $deviation,
            'dewan' => $totalDewan,
            'timer' => $formattedTimer,
            'selected' => $isMerah ? 'merah' : 'biru',
            'scores_per_juri' => $scoresPerJuri,
        ];
    }
}