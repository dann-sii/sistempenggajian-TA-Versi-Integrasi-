<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('karyawan_data') || count(session('karyawan_data', [])) < count(KaryawanController::getInitialData()) || strpos(session('karyawan_data')[0]['npwp'] ?? '', '.') !== false) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }

        $sessionKaryawan = session('karyawan_data', []);
        $updatedSession = false;
        foreach ($sessionKaryawan as &$sk) {
            $expectedBagian = ((int) ($sk['id'] ?? 0) <= 16) ? 'Produksi' : 'Packing';
            if ((($sk['bagian'] ?? '') !== $expectedBagian || ($sk['bagian'] ?? '') === 'Peternakan') && ($sk['id'] ?? 0) <= 30) {
                $sk['bagian'] = $expectedBagian;
                $updatedSession = true;
            }
        }
        unset($sk);
        if ($updatedSession) {
            session()->put('karyawan_data', $sessionKaryawan);
        }

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data'));

        $presensi = PresensiController::getPresensiData();
        $gajiPokokRules = GajiPokokController::getGajiPokokData();
        $gajiLemburRules = GajiPokokController::getGajiLemburData();
        $bonusRules = GajiBonusController::getBonusData();
        $potonganRules = PotonganGajiController::getPotonganData();

        $activeKaryawan = array_values(array_filter($karyawans, fn($k) => strtolower($k['status']) === 'aktif'));
        $inactiveKaryawan = array_values(array_filter($karyawans, fn($k) => strtolower($k['status']) !== 'aktif'));
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
        $totalKaryawanSudahPresensi = count(array_filter($activeNames, fn($nama) => isset($namaPresensi[$nama])));
        $karyawanTanpaPresensi = array_values(array_filter($activeNames, fn($nama) => !isset($namaPresensi[$nama])));
        $presensiTanpaKaryawan = array_values(array_filter(array_keys($namaPresensi), fn($nama) => !in_array($nama, $namaKaryawan, true)));

        $bagianCounts = ['Produksi' => 0, 'Packing' => 0];
        foreach ($activeKaryawan as $karyawan) {
            $bagian = $karyawan['bagian'] ?? 'Produksi';
            if ($bagian === 'Peternakan') {
                $bagian = 'Packing';
            }
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
            fn($nama, $jam) => ['nama' => $nama, 'jam_lembur' => $jam],
            array_keys($jamLemburMap),
            $jamLemburMap
        ), 0, 5);

        $totalBonusRules = count(array_filter($bonusRules, fn($bonus) => strtolower($bonus['status']) === 'aktif'));
        $totalPotonganRules = count(array_filter($potonganRules, fn($potongan) => strtolower($potongan['status']) === 'aktif'));
        $estimasiBruto = $totalGajiPokok + $totalLembur;
        $totalJamLemburSemua = array_sum($jamLemburMap);
        $totalHariKerjaSemua = array_sum($hariKerjaMap);
        $totalJamKerjaSemua = $totalHariKerjaSemua * 8; // Estimasi standar 8 jam/hari kerja

        // Hitung karyawan yang sudah berhasil digaji (berstatus dibayar / selesai)
        $laporanList = session('laporan_penggajian_data', []);
        $currentMonth = date('m');
        $currentYear = date('Y');
        $sudahDigajiSet = [];
        foreach ($laporanList as $lap) {
            $lapMonth = $lap['bulan'] ?? (isset($lap['tanggal_pembayaran']) ? date('m', strtotime($lap['tanggal_pembayaran'])) : '');
            $lapYear = $lap['tahun'] ?? (isset($lap['tanggal_pembayaran']) ? date('Y', strtotime($lap['tanggal_pembayaran'])) : '');
            if (($lap['status_pembayaran'] ?? '') === 'Dibayar' && $lapMonth === $currentMonth && $lapYear === $currentYear) {
                $sudahDigajiSet[$lap['nama']] = true;
            }
        }
        $totalKaryawanSudahDigaji = count($sudahDigajiSet);

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

        // Hitung data tren 12 bulan untuk grafik ringkasan biaya (Company Payments Overview)
        $chartMonths = [];
        $indonesianMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $faktorMap = [
            11 => 0.82, 10 => 0.85, 9 => 0.83, 8 => 0.86,
            7 => 0.89, 6 => 0.87, 5 => 0.88, 4 => 0.94,
            3 => 0.91, 2 => 0.97, 1 => 0.95
        ];

        for ($i = 11; $i >= 0; $i--) {
            $t = strtotime("-{$i} months");
            $mNum = (int) date('n', $t);
            $yNum = date('Y', $t);
            $label = $indonesianMonths[$mNum] . ' ' . $yNum;
            $mKey = str_pad($mNum, 2, '0', STR_PAD_LEFT);

            if ($i === 0) {
                // Bulan berjalan (real-time data)
                $chartMonths[] = [
                    'label' => $label,
                    'is_current' => true,
                    'pokok' => $totalGajiPokok,
                    'lainnya' => $totalLembur,
                    'total' => $estimasiBruto,
                ];
            } else {
                // Data bulan-bulan sebelumnya (dari laporan atau estimasi berbobot realistis)
                $laporanBulanLalu = array_filter($laporanList, fn($l) => ($l['bulan'] ?? '') === $mKey);
                if (count($laporanBulanLalu) > 0) {
                    $pokok = array_sum(array_column($laporanBulanLalu, 'gaji_pokok'));
                    $lainnya = array_sum(array_column($laporanBulanLalu, 'total_lembur')) + array_sum(array_column($laporanBulanLalu, 'total_bonus'));
                } else {
                    // Bobot variasi historis proporsional
                    $faktor = $faktorMap[$i] ?? 0.90;
                    $pokok = round($totalGajiPokok * $faktor, -4);
                    $lainnya = round($totalLembur * ($faktor * 1.05), -4);
                }
                $chartMonths[] = [
                    'label' => $label,
                    'is_current' => false,
                    'pokok' => $pokok,
                    'lainnya' => $lainnya,
                    'total' => $pokok + $lainnya,
                ];
            }
        }

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
            'alerts',
            'totalJamLemburSemua',
            'totalJamKerjaSemua',
            'totalKaryawanSudahDigaji',
            'chartMonths'
        ));
    }
}
