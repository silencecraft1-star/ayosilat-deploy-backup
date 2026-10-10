<?php
  
function active_class($path, $active = 'active') {
  $patterns = array_map(function($p) {
    return ltrim($p, '/');
  }, (array)$path);
  return call_user_func_array('Request::is', $patterns) ? $active : '';
}

function is_active_route($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

if (!function_exists('parse_timer_display')) {
    /**
     * Parse and normalize timer string to minute:second;ms
     * Supports formats: "03:00;50", "03:00.50", "03:00:50", "03:00", etc.
     *
     * @param string|null $timeStr
     * @return array
     */
    function parse_timer_display($timeStr) {
        $default = [
            'minute' => '00',
            'second' => '00',
            'menit' => '00',
            'detik' => '00',
            'ms' => '00',
            'formatted' => '00:00;00',
            'raw' => $timeStr ?? '00:00;00'
        ];

        if (empty($timeStr) || $timeStr === 'N/a') {
            return $default;
        }

        $trimmed = trim((string)$timeStr);

        // Pattern minute:second;ms or minute:second.ms or minute:second:ms
        if (preg_match('/^(\d+):(\d+)[;.:](\d+)$/', $trimmed, $matches)) {
            $min = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $sec = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $ms = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return [
                'minute' => $min,
                'second' => $sec,
                'menit' => $min,
                'detik' => $sec,
                'ms' => $ms,
                'formatted' => "{$min}:{$sec};{$ms}",
                'raw' => $trimmed
            ];
        }

        // Pattern minute:second (misal: "03:00")
        if (preg_match('/^(\d+):(\d+)$/', $trimmed, $matches)) {
            $min = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $sec = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            return [
                'minute' => $min,
                'second' => $sec,
                'menit' => $min,
                'detik' => $sec,
                'ms' => '00',
                'formatted' => "{$min}:{$sec};00",
                'raw' => $trimmed
            ];
        }

        return $default;
    }
}

if (!function_exists('format_seni_penalty_label')) {
    /**
     * Normalize and map raw penalty key to human-readable IPSI standard description
     *
     * @param string|null $rawKey
     * @return string
     */
    function format_seni_penalty_label($rawKey) {
        $normalized = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$rawKey));

        $labels = [
            'PESERTAKELUARDARIARENA' => 'Peserta keluar dari arena (10x10 m)',
            'PESERTAKELUARDARI10X10METERARENA' => 'Peserta keluar dari arena (10x10 m)',
            'SENJATATIDAKSESUAIDENGANDESKRIPSI' => 'Senjata tidak sesuai deskripsi / ketentuan',
            'SENJATATIDAKSESUAI' => 'Senjata tidak sesuai deskripsi / ketentuan',
            'MENJATUHKANSENJATAMENYENTUHLANTAI' => 'Menjatuhkan senjata / menyentuh lantai',
            'BUSANATIDAKSESUAIPERSYARATAN' => 'Busana tidak sesuai ketentuan (tanjak/samping jatuh)',
            'BUSANATIDAKSESUAIPERSYARATANTANJAKATAUSAMPINGJATUH' => 'Busana tidak sesuai ketentuan (tanjak/samping jatuh)',
            'SENJATAJATUHKELUARARENAWALAUPUNTIMMASIHDITUNTUTUNTUKMENGGUNAKANNYA' => 'Senjata jatuh keluar arena saat masih harus digunakan',
            'PESERTABERHENTIDALAM1GERAKANLEBIHDARI5DETIK' => 'Peserta diam dalam 1 gerakan > 5 detik',
            'PESILATMELEBIHIBATASWAKTUTOLERANSI' => 'Pesilat melebihi batas waktu toleransi',
        ];

        return $labels[$normalized] ?? ucwords(strtolower(trim((string)$rawKey)));
    }
}

if (!function_exists('get_dewan_penalties_summary')) {
    /**
     * Retrieve and group all dewan penalties for a seni participant
     *
     * @param int|string $id_peserta
     * @param int|string|null $arena
     * @param int|string|null $partai
     * @return array
     */
    function get_dewan_penalties_summary($id_peserta, $arena = null, $partai = null) {
        $query = \App\score::where('id_perserta', $id_peserta)
            ->where('status', 'seni_minus');

        if (!empty($arena)) {
            $query->where('arena', $arena);
        }
        if (!empty($partai)) {
            $query->where('partai', $partai);
        }

        $records = $query->get();

        $grouped = [];
        foreach ($records as $item) {
            $rawKey = $item->keterangan ?? 'Lain-lain';
            $normKey = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$rawKey));
            if (!isset($grouped[$normKey])) {
                $grouped[$normKey] = [
                    'key' => $rawKey,
                    'normalized_key' => $normKey,
                    'label' => format_seni_penalty_label($rawKey),
                    'total_points' => 0.0,
                    'count' => 0
                ];
            }
            $grouped[$normKey]['total_points'] += (float)$item->score;
            $grouped[$normKey]['count'] += 1;
        }

        return [
            'total_score' => (float)$records->sum('score'),
            'items' => array_values($grouped)
        ];
    }
}

if (!function_exists('format_final_score_precision')) {
    /**
     * Format nilai skor akhir dengan aturan resmi:
     * - Minimal 2 angka di belakang koma (misal: 9.4 -> "9.40", 9 -> "9.00")
     * - Maksimal 3 angka di belakang koma (misal: 9.456 -> "9.456", 9.4567 -> "9.457")
     *
     * @param float|int|string|null $score
     * @return string
     */
    function format_final_score_precision($score) {
        if ($score === null || $score === '' || !is_numeric($score)) {
            return '0.00';
        }
        $val = (float)$score;
        // Format dengan 3 desimal
        $formatted = number_format($val, 3, '.', '');
        // Jika digit ke-3 adalah '0', potong menjadi 2 desimal
        if (substr($formatted, -1) === '0') {
            $formatted = substr($formatted, 0, -1);
        }
        return $formatted;
    }
}