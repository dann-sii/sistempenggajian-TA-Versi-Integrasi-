<?php

namespace App\Http\Controllers\Api\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('karyawan_data') || count(session('karyawan_data', [])) < count(\App\Http\Controllers\Api\Admin\KaryawanController::getInitialData()) || strpos(session('karyawan_data')[0]['npwp'] ?? '', '.') !== false) {
            session()->put('karyawan_data', \App\Http\Controllers\Api\Admin\KaryawanController::getInitialData());
        }
        $karyawans = \App\Http\Controllers\Api\Admin\KaryawanController::withDefaultBagian(session('karyawan_data'));

        $statusKaryawanMap = [];
        $karyawanInaktifCount = 0;
        foreach ($karyawans as $karyawan) {
            $status = $karyawan['status'] ?? 'Aktif';
            $statusKaryawanMap[$karyawan['nama']] = $status;
            if (strtolower($status) !== 'aktif') {
                $karyawanInaktifCount++;
            }
        }

        $presensi = \App\Http\Controllers\Api\Admin\PresensiController::getPresensiData();
        $jamLemburMap = [];
        $hadirCount = 0;
        $izinSakitCount = 0;
        
        foreach ($presensi as $row) {
            $isInactive = strtolower($statusKaryawanMap[$row['nama']] ?? 'Aktif') === 'inaktif';
            if ($isInactive) {
                continue;
            }
            $jamLemburMap[$row['nama']] = ($jamLemburMap[$row['nama']] ?? 0) + (float) $row['jam_lembur'];
            
            if ($row['kehadiran'] === 'Hadir') {
                $hadirCount++;
            } elseif (in_array($row['kehadiran'], ['Izin', 'Sakit'])) {
                $izinSakitCount++;
            }
        }

        $karyawanAktifCount = count($karyawans) - $karyawanInaktifCount;
        $tidakHadirCount = $karyawanAktifCount - $hadirCount;

        $stats = [
            'karyawan_aktif' => $karyawanAktifCount,
            'hadir_hari_ini' => $hadirCount,
            'tidak_hadir' => $tidakHadirCount,
            'karyawan_inaktif' => $karyawanInaktifCount,
        ];

        arsort($jamLemburMap);
        $topLembur = array_slice(array_map(
            fn($nama, $jam) => ['nama' => $nama, 'jam_lembur' => $jam, 'nominal' => $jam * 20000],
            array_keys($jamLemburMap),
            $jamLemburMap
        ), 0, 5);

        // Mock data for Summary Cards
        $ringkasanFinansial = [
            'estimasi_payroll' => 51750000, // gaji_pokok + lembur + bonus
            'total_bonus' => 5500000,
            'total_potongan' => 1250000,
            'persentase_terbayar' => 85, // 85% terbayar
        ];

        return view('pimpinan.dashboard', compact('stats', 'topLembur', 'ringkasanFinansial'));
    }
}
