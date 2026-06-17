<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data'));
        session()->put('karyawan_data', $karyawans);

        $presensi = PresensiController::getPresensiData();
        $gajiPokokRules = GajiPokokController::getGajiPokokData();
        $gajiLemburRules = GajiPokokController::getGajiLemburData();
        $bonusRules = GajiBonusController::getBonusData();
        $potonganRules = PotonganGajiController::getPotonganData();

        $activeKaryawan = array_values(array_filter($karyawans, fn ($k) => strtolower($k['status']) === 'aktif'));
        $inactiveKaryawan = array_values(array_filter($karyawans, fn ($k) => strtolower($k['status']) !== 'aktif'));
        $statusKaryawanMap = [];
        foreach ($karyawans as $karyawan) {
            $statusKaryawanMap[$karyawan['nama']] = $karyawan['status'] ?? 'Aktif';
        }

        $attendanceSummary = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Cuti' => 0, 'Alpha' => 0];
        $hariKerjaMap = [];
        $jamLemburMap = [];
        $namaPresensi = [];
        $invalidTimeRows = [];

        foreach ($presensi as $row) {
            $isInactive = strtolower($statusKaryawanMap[$row['nama']] ?? 'Aktif') === 'inaktif';
            if ($isInactive) {
                continue;
            }

            $status = $row['kehadiran'] === 'Alfa' ? 'Alpha' : $row['kehadiran'];
            if (array_key_exists($status, $attendanceSummary)) {
                $attendanceSummary[$status]++;
            }

            $namaPresensi[$row['nama']] = true;
            if ($status === 'Hadir') {
                $hariKerjaMap[$row['nama']] = ($hariKerjaMap[$row['nama']] ?? 0) + 1;
            }

            $jamLemburMap[$row['nama']] = ($jamLemburMap[$row['nama']] ?? 0) + (float) $row['jam_lembur'];

            if ($row['jam_masuk'] === '00:00' && $row['jam_keluar'] === '00:00') {
                $invalidTimeRows[] = $row;
            }
        }

        $namaKaryawan = array_column($karyawans, 'nama');
        $activeNames = array_column($activeKaryawan, 'nama');
        $totalKaryawanAktif = count($activeKaryawan);
        $totalKaryawanSudahPresensi = count(array_filter($activeNames, fn ($nama) => isset($namaPresensi[$nama])));
        $karyawanTanpaPresensi = array_values(array_filter($activeNames, fn ($nama) => !isset($namaPresensi[$nama])));
        $presensiTanpaKaryawan = array_values(array_filter(array_keys($namaPresensi), fn ($nama) => !in_array($nama, $namaKaryawan, true)));

        $bagianCounts = ['Produksi' => 0, 'Peternakan' => 0];
        foreach ($activeKaryawan as $karyawan) {
            $bagian = $karyawan['bagian'] ?? 'Produksi';
            $bagianCounts[$bagian] = ($bagianCounts[$bagian] ?? 0) + 1;
        }

        $gajiPokokProduksi = $gajiPokokRules[0]['tarif'] ?? 0;
        $gajiPokokPeternakan = $gajiPokokRules[1]['tarif'] ?? $gajiPokokProduksi;
        $tarifLembur = $gajiLemburRules[0]['tarif'] ?? 0;
        $totalGajiPokok = 0;
        $totalLembur = 0;
        $peternakanDenganLembur = [];

        foreach ($activeKaryawan as $karyawan) {
            $nama = $karyawan['nama'];
            $hariKerja = $hariKerjaMap[$nama] ?? 0;
            $jamLembur = $jamLemburMap[$nama] ?? 0;

            if (($karyawan['bagian'] ?? 'Produksi') === 'Peternakan') {
                $totalGajiPokok += $hariKerja * $gajiPokokPeternakan;
                if ($jamLembur > 0) {
                    $peternakanDenganLembur[] = [
                        'nama' => $nama,
                        'jam_lembur' => $jamLembur,
                    ];
                }
                continue;
            }

            $totalGajiPokok += $hariKerja * 8 * $gajiPokokProduksi;
            $totalLembur += $jamLembur * $tarifLembur;
        }

        arsort($jamLemburMap);
        $topLembur = array_slice(array_map(
            fn ($nama, $jam) => ['nama' => $nama, 'jam_lembur' => $jam],
            array_keys($jamLemburMap),
            $jamLemburMap
        ), 0, 5);

        $totalBonusRules = count(array_filter($bonusRules, fn ($bonus) => strtolower($bonus['status']) === 'aktif'));
        $totalPotonganRules = count(array_filter($potonganRules, fn ($potongan) => strtolower($potongan['status']) === 'aktif'));
        $estimasiBruto = $totalGajiPokok + $totalLembur;

        $alerts = [
            [
                'title' => 'Karyawan aktif tanpa presensi',
                'value' => count($karyawanTanpaPresensi),
                'description' => count($karyawanTanpaPresensi) > 0 ? implode(', ', array_slice($karyawanTanpaPresensi, 0, 3)) : 'Semua karyawan aktif punya data presensi.',
            ],
            [
                'title' => 'Jam masuk/keluar kosong',
                'value' => count($invalidTimeRows),
                'description' => 'Baris presensi dengan jam 00:00 perlu ditinjau.',
            ],
            [
                'title' => 'Presensi tanpa master karyawan',
                'value' => count($presensiTanpaKaryawan),
                'description' => count($presensiTanpaKaryawan) > 0 ? implode(', ', array_slice($presensiTanpaKaryawan, 0, 3)) : 'Tidak ada nama asing pada presensi.',
            ],
            [
                'title' => 'Peternakan memiliki lembur',
                'value' => count($peternakanDenganLembur),
                'description' => 'Lembur bagian Peternakan tidak dihitung di Penggajian.',
            ],
        ];

        return view('admin_penggajian.dashboard', compact(
            'activeKaryawan',
            'inactiveKaryawan',
            'presensi',
            'totalKaryawanAktif',
            'totalKaryawanSudahPresensi',
            'attendanceSummary',
            'bagianCounts',
            'totalGajiPokok',
            'totalLembur',
            'estimasiBruto',
            'topLembur',
            'totalBonusRules',
            'totalPotonganRules',
            'alerts'
        ));
    }
}
