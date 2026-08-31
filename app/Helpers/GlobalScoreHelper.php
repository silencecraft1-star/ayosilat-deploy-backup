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
        //dd($arena, $partaiFinal, $sesi, $setting);
        $statusPertandingan = jadwal_group::where('arena', $arena)->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
            $query->where('id_sesi', $sesi);
        }, function ($query) {
            $query->whereNull('id_sesi');
        })->first()->status ?? "pending";
        $keteranganPertandingan = jadwal_group::where('arena', $arena)->where('partai', $partaiFinal)->when($sesi ?? null, function ($query, $sesi) {
            $query->where('id_sesi', $sesi);
        }, function ($query) {
            $query->whereNull('id_sesi');
        })->first()->keterangan ?? "Tanding";
        $infoKelas = kelas::where('id', $pesertaMerah->kelas)->first()->name;
        $infoKategori = category::where('id', $pesertaMerah->category)->first()->name;


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
        $kontigenBiru = KontigenModel::where('id', $biruData->id_kontigen)->first();

        $merahData = PersertaModel::where('id', $settingData->merah)->first();
        $kontigenMerah = KontigenModel::where('id', $merahData->id_kontigen)->first();

        $data = [
            'arena' => $arena,
            'sesi' => $sesi,
            'data' => $pendingSend,
            'babak' => $settingData->babak,
            'partai' => $settingData->partai,
            'juri' => [
                'juri_1' => $settingData->juri_1,
                'juri_2' => $settingData->juri_2,
                'juri_3' => $settingData->juri_3,
            ],
            'biru' => [
                'id' => $biruData->id,
                'nama' => $biruData->name,
                'kontigen' => $kontigenBiru->kontigen
            ],
            'merah' => [
                'id' => $merahData->id,
                'nama' => $merahData->name,
                'kontigen' => $kontigenMerah->kontigen
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

    public function sendTunggalData($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();

        $data = score::where('arena', $arena)->where('partai', $setting->partai)->where('id_perserta', $setting->biru)->where('status', 'point_tunggal')->get();

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'data',
            'data' => $data
        ];

        event(new TunggalEvent($datas));
    }

    public function sendTunggalScore($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $peserta = PersertaModel::where('id', $setting->biru)->first();
        $kontigen = KontigenModel::where('id', $peserta->id_kontigen)->first();

        $infos = [
            'id' => $peserta->id,
            'name' => $peserta->name,
            'kontigen' => $kontigen->kontigen,
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
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();
        $peserta = PersertaModel::where('id', $setting->biru)->first();
        $kontigen = KontigenModel::where('id', $peserta->id_kontigen)->first();

        $infos = [
            'id' => $peserta->id,
            'name' => $peserta->name,
            'kontigen' => $kontigen->kontigen,
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
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();

        $data = score::where('arena', $arena)->where('partai', $setting->partai)->where('id_perserta', $setting->biru)->where('status', 'seni_minus')->get();

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'data',
            'data' => $data
        ];

        event(new TunggalEvent($datas));
    }

    public function sendSoloData($arena)
    {
        $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();

        $data = score::where('arena', $arena)->where('partai', $setting->partai)->where('id_perserta', $setting->biru)->where('status', 'point_solo')->get();

        $datas = [
            'arena' => $arena,
            'partai' => $setting->partai,
            'sesi' => $setting->sesi ?? null,
            'tipe' => 'data',
            'data' => $data
        ];

        event(new TunggalEvent($datas));
    }
}