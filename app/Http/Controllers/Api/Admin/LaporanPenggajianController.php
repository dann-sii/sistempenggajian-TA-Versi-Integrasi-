<?php

namespace App\Http\Controllers\Api\Admin;

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
            ],
        ];

        $search = $request->input('search');
        if ($search) {
            $allLaporan = array_filter($allLaporan, function ($l) use ($search) {
                return stripos($l['nama'], $search) !== false ||
                    stripos($l['id'], $search) !== false;
            });
        }

        return view('admin_penggajian.laporan_penggajian.laporan_penggajian', compact('allLaporan'));
    }
}
