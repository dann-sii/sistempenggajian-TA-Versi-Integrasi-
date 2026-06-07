<?php

namespace App\Http\Controllers\Api\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPenggajianController extends Controller
{
    public function index(Request $request)
    {
        // Mock data
        $allLaporan = [
            [
                'id' => '1',
                'nama' => 'Budi Santoso',
                'jumlah_hari_kerja' => 22,
                'jumlah_lembur' => 5,
                'gaji_pokok' => 3000000,
                'total_lembur' => 200000,
                'bulan' => '05',
                'tahun' => '2026',
            ],
            [
                'id' => '2',
                'nama' => 'Siti Aminah',
                'jumlah_hari_kerja' => 20,
                'jumlah_lembur' => 2,
                'gaji_pokok' => 2800000,
                'total_lembur' => 80000,
                'bulan' => '05',
                'tahun' => '2026',
            ],
            [
                'id' => '3',
                'nama' => 'Andi Wijaya',
                'jumlah_hari_kerja' => 24,
                'jumlah_lembur' => 10,
                'gaji_pokok' => 3500000,
                'total_lembur' => 400000,
                'bulan' => '04',
                'tahun' => '2026',
            ],
        ];

        $search = $request->input('search');
        $bulan = $request->input('bulan', '03'); // Default to Maret for dummy data
        $tahun = $request->input('tahun', '2026'); // Default to 2026 for dummy data

        if ($search) {
            $allLaporan = array_filter($allLaporan, function ($l) use ($search) {
                return stripos($l['nama'], $search) !== false ||
                    stripos($l['id'], $search) !== false;
            });
        }
        
        if ($bulan) {
            $allLaporan = array_filter($allLaporan, function ($l) use ($bulan) {
                return $l['bulan'] === $bulan;
            });
        }

        if ($tahun) {
            $allLaporan = array_filter($allLaporan, function ($l) use ($tahun) {
                return $l['tahun'] === $tahun;
            });
        }

        return view('pimpinan.laporan_penggajian.laporan_penggajian', compact('allLaporan', 'bulan', 'tahun'));
    }
}
