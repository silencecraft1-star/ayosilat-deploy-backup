<?php

namespace App\Imports;

use App\KontigenModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\jadwal_group;
use App\PersertaModel;
use App\kelas;

class jadwalImport implements ToCollection
{
    private $sesi;
    private $arena;
    /**
     * @param Collection $collection
     */

    public function __construct($sesi, $arena)
    {
        $this->sesi = $sesi;
        $this->arena = $arena;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $row) {
            // if (!$row[0]) {
            //     break;
            // }



            if ($index >= 0) {
                $partai = $row[1];

                //row 3 = merah row 5 = biru
                $rowBiru = $row[3];
                $rowMerah = $row[4];

                $pemenangBiru = null;
                $pemenangMerah = null;
                $forArena = null;

                //Cek Apakah Kontigen

                if (str_contains($rowMerah, "PEMENANG PARTAI")) {
                    $explodeMerah = explode(" ", $rowMerah)[2];
                    $forArena = str_contains($rowMerah, ",") ? "," . explode(",", $rowMerah)[1] : "";

                    $pemenangMerah = "[$explodeMerah$forArena]";
                }

                if (str_contains($rowBiru, "PEMENANG PARTAI")) {
                    $explodeBiru = explode(" ", $rowBiru)[2];
                    $forArena = str_contains($rowBiru, ",") ? "," . explode(",", $rowBiru)[1] : "";

                    $pemenangBiru = "[$explodeBiru$forArena]";
                }

                $biru = PersertaModel::where('name', $rowBiru)->first()->id ?? $pemenangBiru;
                $merah = PersertaModel::where('name', $rowMerah)->first()->id ?? $pemenangMerah;
                $kelasCek = PersertaModel::where('name', $rowMerah)->first();

                if ($kelasCek) {
                    $kelas = kelas::where('id', $kelasCek->kelas)->first()->id ?? 1;
                }

                if (!$merah && !$pemenangMerah && !$biru && !$pemenangBiru) {
                    continue;
                } else {

                    jadwal_group::create([
                        'kelas' => $kelas ?? null,
                        'merah' => $merah,
                        'biru' => $biru,
                        'partai' => $partai ?? null,
                        'score_merah' => 0,
                        'score_biru' => 0,
                        'kondisi' => 'N/a',
                        'id_sesi' => $this->sesi ?? null,
                        'arena' => $this->arena,
                        'status' => 'pending',
                        'tipe' => 'tanding'
                    ]);
                }
            }
        }
    }


}
