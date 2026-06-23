<?php

namespace App\Http\Controllers\Api\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock data for Pimpinan Dashboard
        $stats = [
            'total_karyawan' => 25,
            'hadir_hari_ini' => 23,
            'izin_sakit' => 2,
            'karyawan_inaktif' => 1,
        ];

        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', \App\Http\Controllers\Api\Admin\KaryawanController::getInitialData());
        }
        $karyawans = \App\Http\Controllers\Api\Admin\KaryawanController::withDefaultBagian(session('karyawan_data'));
        
        $statusKaryawanMap = [];
        foreach ($karyawans as $karyawan) {
            $statusKaryawanMap[$karyawan['nama']] = $karyawan['status'] ?? 'Aktif';
        }

        $presensi = \App\Http\Controllers\Api\Admin\PresensiController::getPresensiData();
        $jamLemburMap = [];
        foreach ($presensi as $row) {
            $isInactive = strtolower($statusKaryawanMap[$row['nama']] ?? 'Aktif') === 'inaktif';
            if ($isInactive) {
                continue;
            }
            $jamLemburMap[$row['nama']] = ($jamLemburMap[$row['nama']] ?? 0) + (float) $row['jam_lembur'];
        }

        arsort($jamLemburMap);
        $topLembur = array_slice(array_map(
            fn ($nama, $jam) => ['nama' => $nama, 'jam_lembur' => $jam],
            array_keys($jamLemburMap),
            $jamLemburMap
        ), 0, 5);

        return view('pimpinan.dashboard', compact('stats', 'topLembur'));
    }
}
