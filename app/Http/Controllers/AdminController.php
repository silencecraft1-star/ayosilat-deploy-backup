<?php

namespace App\Http\Controllers;

use App\PollingModel;
use Illuminate\Http\Request;
use App\Setting;
use App\juri;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Perserta;
use App\Imports\jadwalImport;
use App\arena;
use App\entry;
use App\score;
use Illuminate\Support\Str;
use App\jadwal_group;
use App\pending_tanding;
use App\PersertaModel;
use App\SesiModel;
use App\kelas;
use App\KontigenModel;
use App\category;
use App\Helpers\GlobalScoreHelper;
use Carbon\Carbon;
use App\Events\TimerUpdate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $timeAll;

    public function index()
    {
        $status = 'admin';
        return view('admin.panel', compact('status'));
    }

    public function arena()
    {
        $status = 'arena';
        return view('admin.PanelArena', compact('status'));
    }

    public function timer(Request $request)
    {
        $helper = new GlobalScoreHelper();
        $tipe = $request->tipe;
        $select = $request->value;
        $arena = $request->arena;
        $action = [
            'arena' => $arena,
            'action' => $tipe
        ];
        event(new TimerUpdate('00:00', $action));
        if ($tipe === "babak") {
            Setting::where('arena', $arena)->whereNotNull('judul')->first()->update(['babak' => $select]);
            $data = Setting::where('arena', $arena)->whereNotNull('judul')->first();

            $datas = $helper->sendTandingScore($arena, $data->sesi ?? null, $data->partai, null, "babak");
            return response()->json($datas);
        } elseif ($tipe === "stop") {
            Setting::where('arena', $arena)->where('keterangan', 'waiting')->delete();
            $data = Setting::where('arena', $arena)->first();
            $data->update(
                [
                    'time' => "",
                    'status' => ""
                ]
            );
            return response()->json('success', 200);
        } elseif ($tipe === "reset") {
            $data = Setting::where('arena', $arena)->whereNotNull('judul')->first();
            $data->update(
                [
                    'time' => "",
                    'status' => "",
                    'babak' => "1"
                ]
            );
            if ($data->keterangan != "Tanding") {
                pending_tanding::where('arena', $arena)->where('partai', $data->partai)->delete();
                score::where('arena', $arena)->where('partai', $data->partai)->delete();
            } else {
                pending_tanding::where('arena', $arena)->where('partai', $data->partai)->delete();
                score::where('arena', $arena)->where('partai', $data->partai)->delete();
            }

            $helper->sendTandingScore($arena, $data->sesi ?? null, $data->partai);

            return response()->json('success', 200);
        } elseif ($tipe === "start") {
            $menit = $request->menit;
            $detik = $request->detik;
            $current_time = Carbon::now();
            $current_time = $current_time->addMinutes($menit);
            $current_time = $current_time->addSeconds($detik);
            $time = $current_time->format('H:i:s');
            $data = Setting::where('arena', $arena)->first();
            $data->update(
                [
                    'time' => $time,
                    'status' => "start"
                ]
            );

            return response()->json('success', 200);
        } elseif ($tipe === "pause") {
            $data = Setting::where('arena', $arena)->first();
            Setting::create([
                'arena' => $arena,
                'babak' => $data->babak,
                'keterangan' => "waiting",
                'status' => $data->id,
                'time' => now() // Simpan timestamp awal pause
            ]);
            $data->update(['status' => "pause"]);
        } elseif ($tipe === "resume") {
            $data = Setting::where('arena', $arena)->first();
            $waiting = Setting::where('keterangan', 'waiting')->where('status', $data->id)->first();
            $pauseStart = Carbon::parse($waiting->time); // Ambil timestamp awal pause
            $pauseDuration = now()->diffInSeconds($pauseStart); // Hitung durasi pause
            $time = Carbon::create($data->time)->addSeconds($pauseDuration)->format('H:i:s');
            $data->update(['status' => "start", 'time' => $time]);
            $waiting->delete();
        } elseif ($tipe === "getCurrent") {
            $time = Setting::where('arena', $arena)->first();
            $start = $time->time;
            $status = $time->status;
            $duration = 180;
            if ($status === "start") {
                $elapsed = Carbon::now()->timestamp - Carbon::parse($start)->timestamp;
                $remaining = max(0, $duration - $elapsed);
                Session::put(`timer_remaining_$arena`, $remaining);
                $data = [
                    'remaining' => $remaining
                ];
                return response()->json($data);
            } elseif ($status === "paused") {
                $remaining = Session::get(`timer_remaining_$arena`);
                return response()->json([
                    'remaining' => $remaining
                ]);
            } elseif ($status === "resumed") {
                $remaining = Session::get(`timer_remaining_$arena`);
                return response()->json([
                    'remaining' => $remaining
                ]);
            }
        }
        return response()->json(['status' => 'success']);
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
        $data = [
            'judul' => $request->judul,
            'arena' => $request->arena,
            'babak' => $request->babak,
            'biru' => $request->biru,
            'merah' => $request->merah,
            'keterangan' => "setting",
        ];
        Setting::updateOrCreate(['keterangan' => 'setting'], $data);
        $status = 'arena';
        return view('admin.PanelArena', compact('status'));
    }

    public function rekapMedali(Request $request)
    {
        $status = 'admin';
        return view('admin.PanelArena', compact('status'));
    }

    public function excel(Request $request)
    {
        Excel::import(new Perserta, request()->file('file'));
        return redirect()->back()->with('success', 'Data Imported');

    }
    public function juri(Request $request)
    {
        $data = [
            'name' => $request->name,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
        ];
        juri::create($data);
        return redirect()->back()->with('success', 'Data Imported');
    }
    public function arenastore(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required',
        ]);

        // Process the form data
        $name = $request->input('name');
        $kelas = $request->input('kelas');
        $keterangan = $request->input('keterangan');

        // Create and save a new Data model instance
        $data = new arena([
            'name' => $name,
            'status' => $kelas, // Assuming 'kelas' is a comma-separated list
            'keterangan' => $keterangan,
            // Add other fields as needed
        ]);

        $data->save();
        $arena = arena::where('name', $name)->first();
        $datas = [
            'judul' => "$name || $kelas",
            'arena' => $arena->id,
            'babak' => '1',
            'biru' => '',
            'merah' => '',
            'keterangan' => "$kelas",
            'juri_1' => $request->input('juri_1'),
            'juri_2' => $request->input('juri_2'),
            'juri_3' => $request->input('juri_3'),
            'juri_4' => ($kelas == "Tanding") ? '' : $request->input('juri_4'),
            'juri_5' => ($kelas == "Tanding") ? '' : $request->input('juri_5'),
            'juri_6' => ($kelas == "Tanding") ? '' : $request->input('juri_6'),
            'juri_7' => ($kelas == "Tanding") ? '' : $request->input('juri_7'),
            'juri_8' => ($kelas == "Tanding") ? '' : $request->input('juri_8'),
        ];
        Setting::create($datas);

        // Redirect or respond as needed
        return redirect()->back()->with('success', 'Data saved successfully');
    }
    public function redirect(Request $request)
    {
        $id_juri = $request->name;
        $role = $request->role;
        $arena = $request->arena;
        $sesi = $request->sesi;
        $this->updateJadwal($arena, $sesi);
        $helper = new GlobalScoreHelper();

        $data = [
            'name' => $id_juri,
            'role' => $role,
            'arena' => $arena
        ];

        // check role
        if ($role === "juri-tanding") {
            return view('penilaian.juri', compact('id_juri', 'arena'));
        } elseif ($role === "dewan-tanding") {
            return view('penilaian.dewan2', compact('id_juri', 'arena'));
        } elseif ($role == "arena") {
            $status = 'arena';
            return view('admin.arena.panel', compact('arena', 'status'));
        } elseif ($role == "dewanTanding") {
            $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();

            // $dataScore = $helper->sendTandingScore($arena, $setting->sesi ?? null, $setting->partai);
            $datakp = $helper->sendPendingData($arena, $setting->sesi ?? null, $setting->partai ?? null, $setting);
            return view('monitor.dewan', compact('datakp'));
        } elseif ($role == 'kp') {
            $setting = Setting::where('arena', $arena)->whereNotNull('judul')->first();

            $datakp = $helper->sendTandingScore($arena, $setting->sesi ?? null, $setting->partai);
            return view('monitor.kp', compact('datakp'));
        } elseif ($role == "dewanSeniSolo") {
            return view("monitor.solo", compact('arena'));
        } elseif ($role == "dewanSeniTunggal") {
            return view("monitor.tunggal", compact('arena'));
        } elseif ($role == "arena-jadwal") {
            //update jadwal disini
            $this->updateJadwal($arena, $sesi);
            //$this->updateScore($arena, $sesi);

            $status = 'arena';
            return view('admin.arena.jadwal', compact('arena', 'status', 'id_juri', 'sesi'));
        } elseif ($role == "arena-jadwal" && $sesi) {
            //update jadwal disini
            $this->updateJadwal($arena, $sesi);

            $status = 'arena';
            return view('admin.arena.jadwal', compact('arena', 'status', 'id_juri', 'sesi'));
        } elseif ($role == "rekapTanding") {
            return view('rekapTanding', compact('arena'));
        } elseif ($role == "manual-tanding") {
            return view('penilaian.manual_tanding', compact('arena', 'id_juri'));
        } elseif ($role == "ranking") {
            $status = 'arena';
            $sesi = $request->sesi;
            $poll = $request->poll;
            return view('admin.arena.rekapRanking', compact('arena', 'status', 'sesi', 'poll'));
        } elseif ($role == "score") {
            $data = arena::where('id', $arena)->first();
            if ($data->status === "Tanding") {
                // return view('penilaian.score2',compact('arena'));
                return view('penilaian.score_new', compact('arena'));
            } elseif ($data->status === "Ganda_Kreatif" || $data->status === "Solo_Kreatif") {
                return view('seni.score', compact('arena'));
            } elseif ($data->status === "Tunggal" || $data->status === "Group") {
                return view('seni.score_2', compact('arena'));
            } else {
                return view('loginscore');
            }
        } elseif ($role == 'timer') {
            return view('timer', compact('arena'));
        } elseif ($role == 'juri-ganda') {
            return view('seni.ganda.juri', compact('id_juri', 'arena'));
        } elseif ($role == 'juri-regu') {
            return view('seni.tunggal.juri2', compact('id_juri', 'arena'));
        } elseif ($role == 'dewan-ganda') {
            // $setting = Setting::where('arena', $arena)->first();

            // $jadwal = jadwal_group::where('id', $setting->jadwal)->first();

            // if(!empty($jadwal->keterangan) && $jadwal->keterangan == "prestasi") {
            //     return view('seni.ganda.dewan-2', compact('id_juri', 'arena'));
            // }
            // else {
            return view('seni.ganda.dewan', compact('id_juri', 'arena'));
            // }

        } elseif ($role == 'juri-tunggal') {
            return view('seni.tunggal.juri', compact('id_juri', 'arena'));
        } elseif ($role == 'dewan-tunggal') {
            return view('seni.tunggal.dewan', compact('id_juri', 'arena'));
        } else {
            // dd($data);
        }
    }

    public function addpesertas(Request $request, $arena)
    {
        $biru = $request->input('biru');
        $merah = $request->input('merah');
        $seni = $request->input('pesertaSeni');
        $biruSeni = $request->input('pesertaSenib');
        $merahSeni = $request->input('pesertaSenim');
        $biru = Trim(explode('||', $biru)[0]);
        $merah = Trim(explode('||', $merah)[0]);
        $sesi = $request->input('sesi');
        $kelas = $request->input('kelas');
        $partaiInput = $request->input('partai');

        // dd($biru, $merah);
        $tipe = $request->input('tipe') ?? null;
        $tipeSeni = $request->input('tipe-seni');
        $poll = $request->input('poll');

        $pesertas = PersertaModel::where('id', $biru)->first();
        $idmerah = PersertaModel::where('id', $merah)->first();
        $data_seni = PersertaModel::where('id', $biruSeni)->first();
        $newpeserta = jadwal_group::where('arena', $arena)->get();


        // dd($pesertas, $idmerah);

        $count = count($newpeserta);
        $partai = 0;

        if ($partaiInput) {
            $partai = $partaiInput;
        }

        if (!empty($tipe) && $tipe == "seni") {

            if ($tipeSeni == "pemasalan") {
                $datas = [
                    'kelas' => $kelas ?? $data_seni->kelas,
                    'id_sesi' => $sesi ?? null,
                    'id_poll' => $poll ?? null,
                    'partai' => $partaiInput,
                    // 'merah' => $biruSeni,
                    'merah' => "seni",
                    'biru' => $biruSeni,
                    'score_merah' => "0",
                    'kondisi' => "N/a",
                    'arena' => $arena,
                    'status' => "pending",
                    'pemenang' => "N/a",
                    'tipe' => 'seni',
                    'keterangan' => $tipeSeni
                ];
            } else {
                $datas = [
                    'kelas' => $kelas ?? $data_seni->kelas,
                    'id_sesi' => $sesi ?? null,
                    'id_poll' => $poll ?? null,
                    'partai' => $partaiInput,
                    'merah' => $merahSeni,
                    'biru' => $biruSeni,
                    'score_merah' => "0",
                    'score_biru' => "0",
                    'kondisi' => "N/a",
                    'arena' => $arena,
                    'status' => "pending",
                    'pemenang' => "N/a",
                    'tipe' => 'seni',
                    'keterangan' => $tipeSeni
                ];
            }
        } else {
            $datas = [
                'id_sesi' => $sesi ?? null,
                'kelas' => $pesertas->kelas ?? "Tanding",
                'partai' => $partaiInput,
                'biru' => $pesertas->id,
                'merah' => $idmerah->id,
                'score_merah' => "0",
                'score_biru' => "0",
                'kondisi' => "N/a",
                'arena' => $arena,
                'status' => "pending",
                'pemenang' => "N/a",
                'tipe' => 'tanding'
            ];
        }
        // dd($datas);
        jadwal_group::create($datas);
        return redirect()->back()->with('success', 'Data saved successfully');
    }

    public function modifySettings(Request $request)
    {
        //    try {
        //Mapping to variable
        $title = $request->input('title');
        $runningText = $request->input('running');
        $juri = $request->input('juri');
        $gambar1 = $request->file('gambar1');
        $gambar2 = $request->file('gambar2');

        $randKey = rand(1000, 2000);
        //Validate Text dan runningText
        if (empty($title) || empty($runningText)) {
            return redirect()->back()->withInput(request()->all())->with('error', 'Judul atau Running Text Tidak Boleh Kosong');
        } else {
            //Cek Data
            $cekSetting = Setting::where('keterangan', 'admin-setting');
            $settingFirst = $cekSetting->first();

            //Upload Gambar
            $this->imageUpload($gambar1, $randKey . '-gambar1.png', $settingFirst->babak ?? "");
            $this->imageUpload($gambar2, $randKey . '-gambar2.png', $settingFirst->partai ?? "");

            //Fill Data
            $data = [
                'judul' => $title ?? "",
                'arena' => $runningText ?? "",
                'jadwal' => $juri ?? "6",
                'babak' => ($gambar1) ? $randKey . '-gambar1.png' : $settingFirst->babak,
                'partai' => ($gambar2) ? $randKey . '-gambar2.png' : $settingFirst->partai,
                'keterangan' => 'admin-setting'
            ];

            //Validate Update atau Insert
            if (!$settingFirst) {
                $setting = new Setting;
                $setting->insert($data);
            } else {
                $cekSetting->update($data);
            }

            return redirect()->back()->with('success', 'Sukses Menyimpan Data');
        }
        // } catch (\Exception $ex) {
        //     return redirect()->back()->with('error', "terjadi Kesalahan : $ex");
        // }
    }

    //Functon Upload image with replace
    public function imageUpload($image, $filename, $dataBefore)
    {
        //Cek Gambar Apakah sudah ada
        $cekImage = public_path("assets/Assets/uploads/$dataBefore");
        //Validate
        if (!$image) {
            return;
        }
        //Jika Gambar Sudah ada maka Replace
        elseif (File::exists($cekImage)) {
            //Delete File yang sudah ada
            File::delete($cekImage);

            //Replace dengan file yang baru
            $image->move(public_path('assets/Assets/uploads/'), "$filename");
        } else {
            //Jika Belum ada maka Buat Baru
            $image->move(public_path('assets/Assets/uploads/'), "$filename");
        }
    }

    public function modifyKelas(Request $request)
    {
        // $request->validate([
        //     'nama' => 'required|string|max:255',
        //     'umur' => 'required',
        //     'status' => 'required'
        // ]);

        $statusData = $request->input('status');

        $fixedClass = $request->input('fixedClass');
        if ($statusData == "add") {
            $nama = $fixedClass ?? $request->input('nama');
            $ket = $request->input('keterangan');

            $data = [
                'name' => $nama,
                'keterangan' => $ket
            ];

            kelas::create($data);
        } elseif ($statusData == "edit") {
            $nama = $fixedClass ?? $request->input('nama');
            $nama = $request->input('nama');
            $umur = $request->input('umur');

            $data = [
                'name' => $nama,
                'keterangan' => $umur
            ];

            $dataTarget = kelas::where('id', $request->input('idEdit'))->first();
            $dataTarget->update($data);
        } elseif ($statusData == "hapus") {
            $dataTarget = kelas::where('id', $request->input('idHapus'))->first();
            $dataTarget->delete();
        }

        $status = 'admin';
        return back();
    }

    public function modifyCategory(Request $request)
    {
        // $request->validate([
        //     'nama' => 'required|string|max:255',
        //     'umur' => 'required',
        //     'status' => 'required'
        // ]);

        $statusData = $request->input('status');

        if ($statusData == "add") {
            $nama = $request->input('nama');
            $umur = $request->input('umur');

            $data = [
                'name' => $nama,
                'keterangan' => $umur
            ];

            category::create($data);
        } elseif ($statusData == "edit") {
            $nama = $request->input('nama');
            $umur = $request->input('umur');

            $data = [
                'name' => $nama,
                'keterangan' => $umur
            ];

            $dataTarget = category::where('id', $request->input('idEdit'))->first();
            $dataTarget->update($data);
        } elseif ($statusData == "hapus") {
            $dataTarget = category::where('id', $request->input('idHapus'))->first();
            $dataTarget->delete();
        }

        $status = 'admin';
        return back();
    }

    public function modifyKontigen(Request $request)
    {
        $statusData = $request->input('status');

        if ($statusData == 'add') {
            $data = [
                'kontigen' => $request->input('kontigen'),
                'manager' => $request->input('manager'),
                'official' => $request->input('official'),
                'hp' => $request->input('nohp'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
                'kecamatan' => $request->input('kecamatan'),
                'desa' => $request->input('desa'),
                'alamat' => $request->input('alamat')
            ];

            KontigenModel::create($data);
        } elseif ($statusData == 'edit') {
            $data = [
                'kontigen' => $request->input('kontigen'),
                'manager' => $request->input('manager'),
                'official' => $request->input('official'),
                'hp' => $request->input('nohp'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
                'kecamatan' => $request->input('kecamatan'),
                'desa' => $request->input('desa'),
                'alamat' => $request->input('alamat')
            ];

            $dataTarget = KontigenModel::where('id', $request->input('idEdit'))->first();
            $dataTarget->update($data);
        } elseif ($statusData == 'hapus') {
            $dataTarget = KontigenModel::where('id', $request->input('idHapus'))->first();
            $dataTarget->delete();
        }

        return back();
    }
    public function modifyArena(Request $request)
    {
        $id = $request->id;
        $arena = arena::where('id', $id)->first();

        if ($request->status == "hapus") {
            $settingData = Setting::where('arena', $arena)->delete();

            $arena->delete();
            return back()->with('success', 'Success Hapus Data');
        } else if ($request->status == "edit") {
            $settingData = Setting::where('arena', $arena->id)->whereNotNull('judul')->first();

            $arenaName = $request->input('name') ?? $arena->name;
            $arena->update([
                'name' => $arenaName,
            ]);

            $settingData->update([
                'judul' => "$arenaName || $arena->status",
                'juri_1' => $request->input('juri_1'),
                'juri_2' => $request->input('juri_2'),
                'juri_3' => $request->input('juri_3'),
                'juri_4' => $arena->status == "Tanding" ? null : $request->input('juri_4'),
            ]);

            return back()->with('success', 'Success Edit Data');
        }

    }

    public function getArenaData(Request $request)
    {
        $id = $request->arena;
        $arena = arena::where('id', $id)->first();
        $settingData = Setting::where('arena', $arena->id)->whereNotNull('judul')->first();

        return response()->json([
            'judul' => $arena->name,
            'keterangan' => $arena->keterangan ?? "",
            'juri_1' => juri::where('id', $settingData->juri_1)->first()->id ?? 1,
            'juri_2' => juri::where('id', $settingData->juri_2)->first()->id ?? 1,
            'juri_3' => juri::where('id', $settingData->juri_3)->first()->id ?? 1,
            'juri_4' => juri::where('id', $settingData->juri_4)->first()->id ?? 1,
            'juri_5' => juri::where('id', $settingData->juri_5)->first()->id ?? 1,
            'juri_6' => juri::where('id', $settingData->juri_6)->first()->id ?? 1,
            'juri_7' => juri::where('id', $settingData->juri_7)->first()->id ?? 1,
            'juri_8' => juri::where('id', $settingData->juri_8)->first()->id ?? 1,
        ]);
    }


    public function newPeserta(Request $request)
    {
        // dd($request->input());
        $nama = $request->name;
        $kontigenPeserta = $request->kontigenPeserta;
        $kategoriPeserta = $request->kategoriPeserta;
        $kelasPeserta = $request->kelasPeserta;

        PersertaModel::create([
            'name' => $nama ?? "Kosong",
            'id_kontigen' => $kontigenPeserta ?? 1,
            'kelas' => $kelasPeserta ?? 1,
            'category' => $kategoriPeserta ?? 1
        ]);
        return redirect()->back()->with('Success', 'Success Tambah Data');
    }

    public function editPeserta(Request $request)
    {
        // dd($request->input());
        $id = $request->id;
        $nama = $request->name;
        $kontigenPeserta = $request->kontigenPeserta;
        $kategoriPeserta = $request->kategoriPeserta;
        $kelasPeserta = $request->kelasPeserta;

        $initialData = PersertaModel::where('id', $id)->first();

        PersertaModel::where('id', $id)->update([
            'name' => $nama ?? $initialData->name,
            'id_kontigen' => $kontigenPeserta ?? $initialData->id_kontigen,
            'category' => $kategoriPeserta ?? $initialData->category,
            'kelas' => $kelasPeserta ?? $initialData->kelas,
        ]);

        return redirect()->back()->with('Success', 'Success Tambah Data');
    }

    public function clearPeserta(Request $request)
    {
        $password = $request->input('passwordClear');
        if ($password == "092023") {
            // dd($request->input('passwordClear'));
            PersertaModel::truncate();
            KontigenModel::truncate();
            // Setting::where('keterangan', '!=', 'admin-setting')->delete();
            kelas::truncate();
            pending_tanding::truncate();
            score::truncate();
            category::truncate();
            jadwal_group::truncate();
            return response()->json(["status" => 'success', 'message' => 'success clear data']);
        } else {
            return response()->json(["status" => 'failed', 'message' => 'Password Salah']);
        }
    }

    public function newSesi(Request $request)
    {
        $name = $request->input('nama');
        $arena = $request->arena;
        $status = $request->input('tipe');

        SesiModel::create([
            'id_arena' => $arena,
            'name' => $name,
            'keterangan' => $status ?? null,
        ]);

        return redirect()->back()->with('success', 'Success Add Sesi');
    }

    public function newPoll(Request $request)
    {
        $name = $request->input('nama');
        $arena = $request->arena;
        $status = $request->input('tipe');

        PollingModel::create([
            'id_arena' => $arena,
            'name' => $name,
            'keterangan' => $status ?? null,
        ]);

        return redirect()->back()->with('success', 'Success Add Sesi');
    }

    public function deleteSesi(Request $request)
    {
        $id = $request->input('id');
        SesiModel::where('id', $id)->delete();

        if ($id) {
            jadwal_group::where('id_sesi', $id)->delete();
        }

        return redirect()->back()->with('success', 'Success Delete Sesi');
    }

    public function deletePoll(Request $request)
    {
        $id = $request->input('id');
        PollingModel::where('id', $id)->delete();

        if ($id) {
            jadwal_group::where('id_poll', $id)->delete();
        }

        return redirect()->back()->with('success', 'Success Delete Sesi');
    }

    public function importJadwal(Request $request)
    {
        $dryData = Excel::toCollection(new jadwalImport($request->input('sesi'), $request->input('arena')), $request->file('file'));
        $errors = [];

        $data = $dryData->first()->skip(2);

        foreach ($data as $index => $row) {

            $rowMerah = $row[4];
            $rowBiru = $row[3];
            if (empty($row[1])) {
                continue;
            }

            $merah = trim($rowMerah ?? '');
            $biru = trim($rowBiru ?? '');
            $partai = trim($row[1] ?? '');

            $isWinnerMerah = Str::startsWith(strtoupper($merah), 'PEMENANG PARTAI');
            $isWinnerBiru = Str::startsWith(strtoupper($biru), 'PEMENANG PARTAI');

            $isKontigenMerah = KontigenModel::where('kontigen', $merah)->first();
            $isKontigenBiru = KontigenModel::where('kontigen', $biru)->first();

            if (!$isWinnerBiru && !$isKontigenBiru) {
                if (empty($biru)) {
                    $errors[] = "Partai $partai: Nama Sudut Biru kosong.";
                } else {
                    $checkBiru = PersertaModel::where('name', $biru)->first();

                    if (!$checkBiru) {
                        $stat = is_numeric($biru) ? 'Angka' : 'Nama'; // Tetap gunakan format error lama
                        $errors[] = "Nama Sudut Biru: $biru (Partai $partai) , Tidak Ditemukan Silakan Cek Ulang. ($stat)";
                    }
                }
            }

            if (!$isWinnerMerah && !$isKontigenMerah) {
                if (empty($merah)) {
                    $errors[] = "Partai $partai: Nama Sudut Merah kosong.";
                } else {
                    $checkMerah = PersertaModel::where('name', $merah)->first();

                    if (!$checkMerah) {
                        $stat = is_numeric($merah) ? 'Angka' : 'Nama'; // Tetap gunakan format error lama
                        $errors[] = "Nama Sudut Merah: $merah (Partai $partai) , Tidak Ditemukan Silakan Cek Ulang. ($stat)";
                    }
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', $errors);
        }

        Excel::import(new jadwalImport($request->input('sesi'), $request->input('arena')), request()->file('file'));
        return redirect()->back()->with('success', 'Data Imported');
    }

    public function updateJadwal($arena, $sesi)
    {
        $dataSelesai = jadwal_group::where('status', 'finish')->where('arena', $arena)->get();
        // $dataMenunggu = jadwal_group::where('merah', 'Like', '[]')->orWhere('biru', 'LIKE', '[]')->get();
        // dd($dataSelesai);
        foreach ($dataSelesai as $item) {
            //cek all Merah

            $cekPemenangMerah = str_contains($item->merah, ',');
            //$pemenangArenaMerah = arena::where('keterangan', trim(explode(',', $cekPemenangMerah)[0], '[]'))->first();

            $merah = jadwal_group::where('merah', "[$item->partai]")->where('arena', $arena);

            $cekMerah = $merah->first();
            if (!$cekMerah) {
                //return;
            } else if ($item->status != "finish") {
                //dd($cekMerah);
                //return;
            } else {
                $merah->update([
                    'merah' => $item->pemenang
                ]);
            }

            //cek all biru
            $cekPemenangBiru = str_contains($item->biru, ',');
            $biru = jadwal_group::where('biru', "[$item->partai]")->where('arena', $arena);

            $cekbiru = $biru->first();
            if (!$cekbiru) {
                //return;
            } else if ($item->status != "finish") {
                //return;
            } else {
                $biru->update([
                    'biru' => $item->pemenang
                ]);
            }
        }
    }

    public function updateScore($arena, $sesi)
    {
        $globalSetting = Setting::where('arena', $arena)->first();

        $jadwal = jadwal_group::where('arena', $arena)->when($sesi ?? null, function ($query, $sesi) {
            $query->where('id_sesi', $sesi);
        }, function ($query) {
            $query->whereNull('id_sesi');
        })->where('status', 'finish')->get();
        $dataArena = arena::where('id', $arena)->first();

        if ($dataArena->status == "Tanding") {

            foreach ($jadwal as $item) {
                $sesi = $item->id_sesi;
                $partai = $item->partai;

                //Update Score Merah
                $merah = $item->merah;

                $plusm = score::where('status', 'plus')->where('arena', $arena)->where('partai', $partai)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('id_perserta', "$merah")->sum('score');
                $minusm = score::where('status', 'minus')->where('arena', $arena)->where('partai', $partai)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('id_perserta', "$merah")->sum('score');

                $scorem = $plusm - $minusm;

                //Update Score Biru
                $biru = $item->biru;

                $plusb = score::where('status', 'plus')->where('arena', $arena)->where('partai', $partai)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('id_perserta', "$biru")->sum('score');
                $minusb = score::where('status', 'minus')->where('arena', $arena)->where('partai', $partai)->when($sesi ?? null, function ($query, $sesi) {
                    $query->where('id_sesi', $sesi);
                }, function ($query) {
                    $query->whereNull('id_sesi');
                })->where('id_perserta', "$biru")->sum('score');

                $scoreb = $plusb - $minusb;

                $item->update([
                    'score_merah' => $scorem,
                    'score_biru' => $scoreb
                ]);
            }
        }
    }

    public function createEntry(Request $request)
    {
        $arena2 = $request->input('arena');

        $settingData = Setting::where('arena', $arena2)->first();
        $jadwalData = jadwal_group::where('arena', $arena2)->where('partai', $settingData->partai)->first();
        entry::create([
            'arena' => $arena2,
            'partai' => $settingData->partai ?? '',
            'merah' => $jadwalData->merah ?? '',
            'biru' => $jadwalData->biru ?? '',
        ]);

        return redirect()->back()->with('success', 'Data Ter Clear');
    }

    public function deleteEntry(Request $request)
    {
        $id = $request->input('id');

        entry::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Ter Clear');
    }

    public function entryData(Request $request)
    {
        $responseData = [];
        $compArena = [];

        $entryData = entry::get();
        //$dataArena = arena::get();

        foreach ($entryData as $items) {
            $resultSetting = Setting::where('arena', $items->arena)->first();
            $arenaData = arena::where('id', $items->arena)->first();

            $compArena[] = (object) [
                'namaArena' => $arenaData->name,
                'arenaId' => $arenaData->id,
                'biru' => $resultSetting->biru,
                'merah' => $resultSetting->merah
            ];
        }

        $jadwalData = jadwal_group::get();

        foreach ($compArena as $items) {
            $dataJadwal = null;
            $tipeArena = "tanding";

            if ($items->merah == "") {
                $dataJadwal = jadwal_group::where('arena', $items->arenaId)->where('merah', $items->biru)->first() ?? jadwal_group::where('arena', $items->arenaId)->where('biru', $items->biru)->first();
                $tipeArena = "seni";
            } else {
                $dataJadwal = jadwal_group::where('arena', $items->arenaId)->where('merah', $items->merah)->where('biru', $items->biru)->first();
            }

            if ($dataJadwal) {
                $responseData[] = (object) [
                    'id_arena' => $items->arenaId,
                    'arena' => $items->namaArena,
                    'tipe' => $tipeArena,
                    'partai' => $dataJadwal->partai,
                    'biru' => $dataJadwal->score_biru,
                    'merah' => $dataJadwal->score_merah,
                ];
            }
        }

        return $responseData;
    }

    public function editJadwal(Request $request)
    {
        $idJadwal = $request->input('newJadwalId');
        $kelasData = PersertaModel::where('id', $request->input('biruEdit'))->first();
        $newSession = $request->input('newSession');
        $tipeTanding = $request->input('tipe_tanding') == "" ? null : $request->input('tipe_tanding');

        $dataArenaJadwal = jadwal_group::where('id', $idJadwal)->first()->arena;
        $finalSesi = null;

        if ($newSession == "0") {
            $finalSesi = null;
        } else if ($dataArenaJadwal != $request->input('new_arena')) {
            $finalSesi = null;
        } else {
            $finalSesi = $newSession;
        }

        jadwal_group::where('id', $idJadwal)->update([
            "id_sesi" => $finalSesi ?? null,
            "kelas" => $kelasData->kelas,
            "partai" => $request->input('partai'),
            "merah" => $request->input('merahEdit'),
            "biru" => $request->input('biruEdit'),
            "arena" => $request->input('new_arena'),
            "keterangan" => $tipeTanding
        ]);

        return redirect()->back()->with('success', 'Berhasil Edit Jadwal');
    }

    public function deleteJadwal(Request $request)
    {
        $id = $request->id_peserta_delete;

        jadwal_group::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Terhapus');
    }

    public function ClearJadwal(Request $request)
    {
        $arena = $request->arena;
        $sesi = $request->sesi;

        if ($sesi) {
            jadwal_group::where('arena', $arena)
                ->where('id_sesi', $sesi)
                ->delete();
        } else {
            jadwal_group::where('arena', $arena)
                ->whereNull('id_sesi')
                ->delete();
        }

        return redirect()->back()->with('success', 'Data Ter Clear');
    }

    public function globalTime(Request $request)
    {

        return response()->json(Carbon::now()->toTimeString());
    }

    public function getPeserta(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['error' => 'ID is required'], 400);
        }

        $peserta = PersertaModel::where('id', $id)->first();

        if (!$peserta) {
            return response()->json(['error' => 'Participant not found'], 404);
        }

        $kontigen = KontigenModel::where('id', $peserta->id_kontigen)->first();
        $kelas = kelas::where('id', $peserta->kelas)->first();
        $category = category::where('id', $peserta->category)->first();

        return response()->json([
            'id' => $id,
            'name' => $peserta->name,
            'kelas' => $kelas->id ?? null,
            'kategori' => $category->id ?? null,
            'kontigen' => $kontigen->id ?? null,
        ]);
    }

    public function searchPeserta(Request $request)
    {
        $identifier = $request->input('identifier');
        $input = $request->input('input');
        $finalResult = [];

        $searchResult = PersertaModel::where('name', 'LIKE', '%' . $input . '%')->take(30)->get();

        if ($searchResult->isEmpty()) {
            $searchResult = PersertaModel::take(30)->get();

            foreach ($searchResult as $item) {
                $kelas = kelas::where('id', $item->kelas)->first()->name ?? "";
                $kontigen = KontigenModel::where('id', $item->id_kontigen)->first()->kontigen ?? "";

                $finalResult[] = [
                    'id' => $item->id,
                    'text' => "$item->name || $kontigen || $kelas",
                ];
            }
        } else {
            foreach ($searchResult as $item) {
                $kelas = kelas::where('id', $item->kelas)->first()->name ?? "";
                $kontigen = KontigenModel::where('id', $item->id_kontigen)->first()->kontigen ?? "";

                $finalResult[] = [
                    'id' => $item->id,
                    'text' => "$item->name || $kontigen || $kelas",
                ];
            }
        }

        return response()->json([
            'items' => $finalResult
        ]);
    }

    public function searchPesertaFull(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');

        $query = PersertaModel::query();

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                // Search in peserta name
                $q->where('name', 'LIKE', '%' . $searchValue . '%')
                    // Search in kontigen
                    ->orWhereHas('kontigen', function ($kontigenQuery) use ($searchValue) {
                        $kontigenQuery->where('kontigen', 'LIKE', '%' . $searchValue . '%');
                    })
                    // Search in kelas
                    ->orWhereHas('kelas', function ($kelasQuery) use ($searchValue) {
                        $kelasQuery->where('name', 'LIKE', '%' . $searchValue . '%');
                    })
                    // Search in category
                    ->orWhereHas('category', function ($categoryQuery) use ($searchValue) {
                        $categoryQuery->where('name', 'LIKE', '%' . $searchValue . '%');
                    });
            });
        }

        // Get total records count
        $totalRecords = PersertaModel::count();

        // Get filtered records count
        $filteredRecords = $query->count();

        // Get paginated results
        $searchResult = $query->skip($start)->take($length)->get();

        $finalResult = [];
        foreach ($searchResult as $index => $item) {
            $kelas = kelas::where('id', $item->kelas)->first()->name ?? "";
            $kontigen = KontigenModel::where('id', $item->id_kontigen)->first()->kontigen ?? "";
            $kategori = category::where('id', $item->category)->first()->name ?? "";

            $finalResult[] = [
                'index' => $start + $index + 1,
                'id' => $item->id,
                'name' => $item->name,
                'kontigen' => $kontigen,
                'kelas' => $kelas,
                'category' => $kategori,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $finalResult
        ]);
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