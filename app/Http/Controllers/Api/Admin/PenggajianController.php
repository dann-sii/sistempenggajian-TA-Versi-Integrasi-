<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\GajiBonusController;
use App\Http\Controllers\Api\Admin\PotonganGajiController;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    private function getInitialData() 
    {
        return KaryawanController::getInitialData();
    }

    private function withDefaultBagian(array $karyawans): array
    {
        return KaryawanController::withDefaultBagian($karyawans);
    }

    public function index()
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', $this->getInitialData());
        }
        
        $karyawans = $this->withDefaultBagian(session('karyawan_data'));
        session()->put('karyawan_data', $karyawans);

        // Data presensi: hitung jumlah hari hadir dan jam lembur per karyawan per bulan
        $allPresensi = PresensiController::getPresensiData();

        // Agregasi: hitung jumlah hari hadir dan total jam lembur per nama karyawan
        $hariKerjaMap = [];
        $jamLemburMap = [];
        foreach ($allPresensi as $p) {
            if ($p['kehadiran'] === 'Hadir') {
                $hariKerjaMap[$p['nama']] = ($hariKerjaMap[$p['nama']] ?? 0) + 1;
            }

            $jamLemburMap[$p['nama']] = ($jamLemburMap[$p['nama']] ?? 0) + (float) ($p['jam_lembur'] ?? 0);
        }

        $gajiPokokRules = array_values(array_filter(GajiPokokController::getGajiPokokData(), function ($gaji) {
            return strtolower($gaji['status']) === 'aktif';
        }));
        $gajiLemburRules = array_values(array_filter(GajiPokokController::getGajiLemburData(), function ($gaji) {
            return strtolower($gaji['status']) === 'aktif';
        }));

        $gajiPokokProduksi = $gajiPokokRules[0] ?? ['tarif' => 0, 'nama' => 'Gaji Pokok per Jam'];
        $gajiPokokPeternakan = $gajiPokokRules[1] ?? $gajiPokokProduksi;
        $gajiLemburAktif = (float) ($gajiLemburRules[0]['tarif'] ?? 0);
        $gajiBonus = array_values(array_filter(GajiBonusController::getBonusData(), function ($bonus) {
            return strtolower($bonus['status']) === 'aktif';
        }));
        $bonusProduksiMap = $this->buildBonusProduksiMap($gajiBonus);

        // Buat lookup karyawan_id => data untuk dipakai JS
        $karyawanLookup = [];
        foreach ($karyawans as $k) {
            $hariKerja = $hariKerjaMap[$k['nama']] ?? 0;
            $jamLembur = $jamLemburMap[$k['nama']] ?? 0;
            $bagian = $k['bagian'] ?? '-';
            $isPeternakan = strtolower($bagian) === 'peternakan';
            $gajiPokokRule = strtolower($bagian) === 'peternakan' ? $gajiPokokPeternakan : $gajiPokokProduksi;
            $satuanGajiPokok = stripos($gajiPokokRule['nama'], 'per Hari') !== false ? 'Hari' : 'Jam';
            $tarifGajiPokok = (float) $gajiPokokRule['tarif'];
            $totalGajiPokok = $satuanGajiPokok === 'Hari'
                ? $hariKerja * $tarifGajiPokok
                : $hariKerja * 8 * $tarifGajiPokok;
            $jamLemburDibayar = $isPeternakan ? 0 : $jamLembur;

            $karyawanLookup[$k['id']] = [
                'nama'        => $k['nama'],
                'bagian'      => $bagian,
                'hari_kerja'  => $hariKerja,
                'gaji_pokok'  => $tarifGajiPokok,
                'satuan_gaji_pokok' => $satuanGajiPokok,
                'total_gaji_pokok' => $totalGajiPokok,
                'lembur'      => $jamLemburDibayar > 0 ? 'Ya' : 'Tidak',
                'jam_lembur'  => $jamLemburDibayar,
                'tarif_lembur' => $gajiLemburAktif,
                'total_lembur' => $jamLemburDibayar * $gajiLemburAktif,
                'lembur_aktif' => !$isPeternakan,
                'bonus_produksi_by_period' => $bonusProduksiMap[$k['id']] ?? [],
            ];
        }

        $potonganGaji = array_values(array_filter(PotonganGajiController::getPotonganData(), function ($potongan) {
            return strtolower($potongan['status']) === 'aktif';
        }));

        return view('admin_penggajian.penggajian', compact('karyawans', 'karyawanLookup', 'gajiBonus', 'potonganGaji'));
    }

    private function buildBonusProduksiMap(array $gajiBonus): array
    {
        $aktivitas = session('aktivitas_karyawan_data', []);
        $produkResep = collect(AktivitasKaryawanController::getProdukResepData())->keyBy('id_produk');
        $bonusRules = collect($gajiBonus)->filter(function ($bonus) {
            return stripos($bonus['nama'], 'Bonus THR') === false;
        });
        $dailyUsage = [];

        foreach ($aktivitas as $row) {
            if (empty($row['tanggal']) || empty($row['karyawan_id'])) {
                continue;
            }

            $produk = null;
            if (!empty($row['id_produk'])) {
                $produk = $produkResep->get($row['id_produk']);
            }

            if (!$produk && !empty($row['nama_produk'])) {
                $produk = $produkResep->firstWhere('nama_produk', $row['nama_produk']);
            }

            if (!$produk || (float) $produk['jumlah_standar'] <= 0) {
                continue;
            }

            $jumlahProduksi = (float) ($row['jumlah_produksi'] ?? 0);
            if ($jumlahProduksi <= 0) {
                continue;
            }

            $factor = $jumlahProduksi / (float) $produk['jumlah_standar'];
            $period = date('Y-m', strtotime($row['tanggal']));
            $date = $row['tanggal'];
            $karyawanId = $row['karyawan_id'];

            $this->addDailyUsage($dailyUsage, $karyawanId, $period, $date, 'susu', (float) $produk['susu_liter'] * $factor);
            $this->addDailyUsage($dailyUsage, $karyawanId, $period, $date, 'gula', (float) $produk['gula_gram'] * $factor);

            if (!empty($produk['perasa_jenis'])) {
                $this->addDailyUsage(
                    $dailyUsage,
                    $karyawanId,
                    $period,
                    $date,
                    'perasa ' . strtolower($produk['perasa_jenis']),
                    (float) $produk['perasa_gram'] * $factor
                );
            }
        }

        return $this->calculateBonusProduksi($dailyUsage, $bonusRules);
    }

    private function addDailyUsage(array &$dailyUsage, $karyawanId, string $period, string $date, string $material, float $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $dailyUsage[$karyawanId][$period][$date][$material] = ($dailyUsage[$karyawanId][$period][$date][$material] ?? 0) + $amount;
    }

    private function calculateBonusProduksi(array $dailyUsage, $bonusRules): array
    {
        $result = [];

        foreach ($dailyUsage as $karyawanId => $periods) {
            foreach ($periods as $period => $dates) {
                $detailsByRule = [];

                foreach ($dates as $materials) {
                    foreach ($materials as $material => $usage) {
                        $rule = $this->findBonusRuleForMaterial($bonusRules, $material);
                        if (!$rule) {
                            continue;
                        }

                        $excess = max(0, $usage - (float) $rule['batas_normal']);
                        if ($excess <= 0) {
                            continue;
                        }

                        $ruleId = $rule['id'];
                        $detailsByRule[$ruleId]['title'] = $rule['nama'];
                        $detailsByRule[$ruleId]['satuan'] = $rule['satuan'];
                        $detailsByRule[$ruleId]['tarif'] = (float) $rule['tarif'];
                        $detailsByRule[$ruleId]['excess'] = ($detailsByRule[$ruleId]['excess'] ?? 0) + $excess;
                        $detailsByRule[$ruleId]['days'] = ($detailsByRule[$ruleId]['days'] ?? 0) + 1;
                        $detailsByRule[$ruleId]['amount'] = ($detailsByRule[$ruleId]['amount'] ?? 0) + ($excess * (float) $rule['tarif']);
                    }
                }

                $details = array_values(array_map(function ($detail) {
                    return [
                        'title' => $detail['title'],
                        'description' => sprintf(
                            'Selisih harian terakumulasi %s %s selama %s hari x Rp %s',
                            number_format($detail['excess'], 2, ',', '.'),
                            $detail['satuan'],
                            number_format($detail['days'], 0, ',', '.'),
                            number_format($detail['tarif'], 0, ',', '.')
                        ),
                        'amount' => $detail['amount'],
                    ];
                }, $detailsByRule));

                $result[$karyawanId][$period] = [
                    'total' => array_sum(array_column($details, 'amount')),
                    'details' => $details,
                ];
            }
        }

        return $result;
    }

    private function findBonusRuleForMaterial($bonusRules, string $material): ?array
    {
        return $bonusRules->first(function ($bonus) use ($material) {
            $name = strtolower($bonus['nama']);

            if ($material === 'susu') {
                return str_contains($name, 'susu') && !str_contains($name, 'peternakan');
            }

            if ($material === 'gula') {
                return str_contains($name, 'gula');
            }

            return str_contains($name, $material);
        });
    }
}
