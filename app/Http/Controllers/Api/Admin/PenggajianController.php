<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\GajiBonusController;
use App\Http\Controllers\Api\Admin\PotonganGajiController;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
        if (!session()->has('karyawan_data') || count(session('karyawan_data', [])) < count($this->getInitialData()) || strpos(session('karyawan_data')[0]['npwp'] ?? '', '.') !== false) {
            session()->put('karyawan_data', $this->getInitialData());
        }

        $karyawans = $this->withDefaultBagian(session('karyawan_data'));

        // Data presensi: hitung jumlah jam pokok dan jam lembur per karyawan per bulan
        $allPresensi = PresensiController::getCalculatedPresensiData();

        // Agregasi: hitung jumlah hari hadir, jam pokok, dan total jam lembur per nama karyawan
        $hariKerjaMap = [];
        $jamPokokMap = [];
        $jamLemburMap = [];
        foreach ($allPresensi as $p) {
            if ($p['kehadiran'] === 'Hadir') {
                $hariKerjaMap[$p['nama']] = ($hariKerjaMap[$p['nama']] ?? 0) + 1;
            }

            $jamPokokMap[$p['nama']] = ($jamPokokMap[$p['nama']] ?? 0) + (float) ($p['jam_kerja_pokok'] ?? 0);
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

        // Akumulasi potongan wastage kemasan rusak untuk bagian Packing
        $wastageDeductionsMap = [];
        $aktivitasList = session('aktivitas_karyawan_data', []);
        foreach ($aktivitasList as $row) {
            if (empty($row['tanggal']) || empty($row['karyawan_id'])) {
                continue;
            }
            if (($row['bagian'] ?? '') === 'Packing') {
                $period = date('Y-m', strtotime($row['tanggal']));
                $karyawanId = $row['karyawan_id'];

                $sachetRusak = (int) ($row['sachet_rusak_qty'] ?? 0);
                $plastik250Rusak = (int) ($row['plastik_250_rusak_qty'] ?? 0);
                $plastik500Rusak = (int) ($row['plastik_500_rusak_qty'] ?? 0);
                $plastik1000Rusak = (int) ($row['plastik_1000_rusak_qty'] ?? 0);

                // Tarif potongan wastage: Sachet: Rp250, P.250g: Rp500, P.500g: Rp1000, P.1kg: Rp1500
                $deduction = ($sachetRusak * 250) + ($plastik250Rusak * 500) + ($plastik500Rusak * 1000) + ($plastik1000Rusak * 1500);

                if ($deduction > 0) {
                    if (!isset($wastageDeductionsMap[$karyawanId][$period])) {
                        $wastageDeductionsMap[$karyawanId][$period] = [
                            'amount' => 0,
                            'details' => [],
                        ];
                    }
                    $wastageDeductionsMap[$karyawanId][$period]['amount'] += $deduction;

                    $breakdown = [];
                    if ($sachetRusak > 0) $breakdown[] = "Sachet: {$sachetRusak} x Rp250";
                    if ($plastik250Rusak > 0) $breakdown[] = "P.250g: {$plastik250Rusak} x Rp500";
                    if ($plastik500Rusak > 0) $breakdown[] = "P.500g: {$plastik500Rusak} x Rp1000";
                    if ($plastik1000Rusak > 0) $breakdown[] = "P.1kg: {$plastik1000Rusak} x Rp1500";

                    $wastageDeductionsMap[$karyawanId][$period]['details'][] = [
                        'date' => date('d-m-Y', strtotime($row['tanggal'])),
                        'amount' => $deduction,
                        'description' => implode(', ', $breakdown)
                    ];
                }
            }
        }

        // Buat lookup karyawan_id => data untuk dipakai JS
        $karyawanLookup = [];
        foreach ($karyawans as $k) {
            $hariKerja = $hariKerjaMap[$k['nama']] ?? 0;
            $jamPokok = $jamPokokMap[$k['nama']] ?? 0;
            $jamLembur = $jamLemburMap[$k['nama']] ?? 0;
            $bagian = $k['bagian'] ?? '-';
            $isPeternakan = strtolower($bagian) === 'peternakan';
            $gajiPokokRule = strtolower($bagian) === 'peternakan' ? $gajiPokokPeternakan : $gajiPokokProduksi;
            $satuanGajiPokok = stripos($gajiPokokRule['nama'], 'per Hari') !== false ? 'Hari' : 'Jam';
            $tarifGajiPokok = (float) $gajiPokokRule['tarif'];
            $totalGajiPokok = $satuanGajiPokok === 'Hari'
                ? $hariKerja * $tarifGajiPokok
                : $jamPokok * $tarifGajiPokok;
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
                'wastage_deductions_by_period' => $wastageDeductionsMap[$k['id']] ?? [],
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
        $result = [];

        foreach ($aktivitas as $row) {
            if (empty($row['tanggal']) || empty($row['karyawan_id'])) {
                continue;
            }

            if (($row['source'] ?? '') === 'piket_batch') {
                $period = date('Y-m', strtotime($row['tanggal']));
                $karyawanId = $row['karyawan_id'];
                $details = $row['bonus_details'] ?? [];
                $total = (float) ($row['bonus_total'] ?? array_sum(array_column($details, 'amount')));

                if ($total <= 0 && count($details) === 0) {
                    continue;
                }

                if (!isset($result[$karyawanId][$period])) {
                    $result[$karyawanId][$period] = [
                        'total' => 0,
                        'details' => [],
                    ];
                }

                $result[$karyawanId][$period]['total'] += $total;
                foreach ($details as $detail) {
                    $result[$karyawanId][$period]['details'][] = $detail;
                }

                continue;
            }

            $produk = null;
            if (!empty($row['id_produk'])) {
                $produk = $produkResep->get($row['id_produk']);
            }

            if (!$produk && !empty($row['nama_produk'])) {
                $produk = $produkResep->firstWhere('nama_produk', $row['nama_produk']);
            }

            if (!$produk || (float) $produk['target_produksi'] <= 0) {
                continue;
            }

            $jumlahProduksi = (float) ($row['jumlah_produksi'] ?? 0);
            if ($jumlahProduksi <= 0) {
                continue;
            }

            $factor = $jumlahProduksi / (float) $produk['target_produksi'];
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

        $legacyResult = $this->calculateBonusProduksi($dailyUsage, $bonusRules);
        foreach ($legacyResult as $karyawanId => $periods) {
            foreach ($periods as $period => $data) {
                if (!isset($result[$karyawanId][$period])) {
                    $result[$karyawanId][$period] = [
                        'total' => 0,
                        'details' => [],
                    ];
                }

                $result[$karyawanId][$period]['total'] += (float) ($data['total'] ?? 0);
                $result[$karyawanId][$period]['details'] = array_merge(
                    $result[$karyawanId][$period]['details'],
                    $data['details'] ?? []
                );
            }
        }

        return $result;
    }

    private function addDailyUsage(array &$dailyUsage, int|string $karyawanId, string $period, string $date, string $material, float $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $dailyUsage[$karyawanId][$period][$date][$material] = ($dailyUsage[$karyawanId][$period][$date][$material] ?? 0) + $amount;
    }

    private function calculateBonusProduksi(array $dailyUsage, Collection $bonusRules): array
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

    private function findBonusRuleForMaterial(Collection $bonusRules, string $material): ?array
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
