<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DetailPenggajianController extends Controller
{
    public function index(Request $request)
    {
        // Mock data
        $allDetail = [
            [
                'id' => '1',
                'nama' => 'Budi Santoso',
                'komponen_gaji' => 'Gaji Pokok',
                'jumlah' => 3000000,
                'keterangan' => 'Gaji Pokok Bulan Januari 2026',
                'status' => 'Dibayar'
            ],
            [
                'id' => '2',
                'nama' => 'Budi Santoso',
                'komponen_gaji' => 'Lembur',
                'jumlah' => 200000,
                'keterangan' => 'Lembur 5 Jam',
                'status' => 'Dibayar'
            ],
            [
                'id' => '3',
                'nama' => 'Budi Santoso',
                'komponen_gaji' => 'Bonus Tahunan',
                'jumlah' => 500000,
                'keterangan' => 'Bonus Prestasi',
                'status' => 'Dibayar'
            ],
            [
                'id' => '4',
                'nama' => 'Budi Santoso',
                'komponen_gaji' => 'THR',
                'jumlah' => 1500000,
                'keterangan' => 'THR Idul Fitri',
                'status' => 'Menunggu'
            ],
            [
                'id' => '5',
                'nama' => 'Budi Santoso',
                'komponen_gaji' => 'Potongan BPJS',
                'jumlah' => -150000,
                'keterangan' => 'Potongan Iuran BPJS Kesehatan',
                'status' => 'Diproses'
            ],
        ];

        $search = $request->input('search');
        if ($search) {
            $allDetail = array_filter($allDetail, function ($d) use ($search) {
                return stripos($d['nama'], $search) !== false ||
                    stripos($d['id'], $search) !== false ||
                    stripos($d['komponen_gaji'], $search) !== false;
            });
        }

        return view('admin_penggajian.detail_penggajian.detail_penggajian', compact('allDetail'));
    }
}
