<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DetailPenggajianController extends Controller
{
    public function index(Request $request)
    {
        $penggajian = [
            'PGJ-001' => ['id_penggajian' => 'PGJ-001', 'nama' => 'Budi Santoso', 'periode' => 'Juni 2026', 'status' => 'Diproses'],
            'PGJ-002' => ['id_penggajian' => 'PGJ-002', 'nama' => 'Eka Putri', 'periode' => 'Juni 2026', 'status' => 'Diproses'],
            'PGJ-003' => ['id_penggajian' => 'PGJ-003', 'nama' => 'Joko Susilo', 'periode' => 'Juni 2026', 'status' => 'Diproses'],
        ];

        // Mock data sesuai struktur tabel detail_penggajian.
        $allDetail = [
            ['id_detail' => 1, 'id_penggajian' => 'PGJ-001', 'tipe_komponen' => 'Gaji Pokok', 'keterangan' => 'Gaji pokok produksi 26 hari kerja', 'jumlah' => 3120000],
            ['id_detail' => 2, 'id_penggajian' => 'PGJ-001', 'tipe_komponen' => 'Gaji Lembur', 'keterangan' => 'Lembur 5 jam', 'jumlah' => 100000],
            ['id_detail' => 3, 'id_penggajian' => 'PGJ-001', 'tipe_komponen' => 'Bonus', 'keterangan' => 'Bonus THR', 'jumlah' => 1500000],
            ['id_detail' => 4, 'id_penggajian' => 'PGJ-001', 'tipe_komponen' => 'Potongan', 'keterangan' => 'Potongan BPJS Kesehatan', 'jumlah' => -50000],
            ['id_detail' => 5, 'id_penggajian' => 'PGJ-001', 'tipe_komponen' => 'Potongan', 'keterangan' => 'Potongan BPJS Ketenagakerjaan', 'jumlah' => -30000],

            ['id_detail' => 6, 'id_penggajian' => 'PGJ-002', 'tipe_komponen' => 'Gaji Pokok', 'keterangan' => 'Gaji pokok produksi 24 hari kerja', 'jumlah' => 2880000],
            ['id_detail' => 7, 'id_penggajian' => 'PGJ-002', 'tipe_komponen' => 'Gaji Lembur', 'keterangan' => 'Tidak ada lembur', 'jumlah' => 0],
            ['id_detail' => 8, 'id_penggajian' => 'PGJ-002', 'tipe_komponen' => 'Bonus', 'keterangan' => 'Bonus penggunaan susu', 'jumlah' => 75000],
            ['id_detail' => 9, 'id_penggajian' => 'PGJ-002', 'tipe_komponen' => 'Potongan', 'keterangan' => 'Potongan BPJS Kesehatan', 'jumlah' => -50000],

            ['id_detail' => 10, 'id_penggajian' => 'PGJ-003', 'tipe_komponen' => 'Gaji Pokok', 'keterangan' => 'Gaji pokok peternakan 22 hari kerja', 'jumlah' => 2200000],
            ['id_detail' => 11, 'id_penggajian' => 'PGJ-003', 'tipe_komponen' => 'Bonus', 'keterangan' => 'Bonus produksi susu peternakan', 'jumlah' => 150000],
            ['id_detail' => 12, 'id_penggajian' => 'PGJ-003', 'tipe_komponen' => 'Potongan', 'keterangan' => 'Potongan BPJS Ketenagakerjaan', 'jumlah' => -30000],
        ];

        $search = $request->input('search');

        $summaries = [];
        foreach ($allDetail as $detail) {
            $idPenggajian = $detail['id_penggajian'];

            if (!isset($summaries[$idPenggajian])) {
                $summaries[$idPenggajian] = array_merge($penggajian[$idPenggajian], [
                    'jumlah_komponen' => 0,
                    'total_gaji' => 0,
                    'details' => [],
                ]);
            }

            $summaries[$idPenggajian]['jumlah_komponen']++;
            $summaries[$idPenggajian]['total_gaji'] += $detail['jumlah'];
            $summaries[$idPenggajian]['details'][] = $detail;
        }

        $summaries = array_values($summaries);

        if ($search) {
            $summaries = array_values(array_filter($summaries, function ($summary) use ($search) {
                return stripos($summary['nama'], $search) !== false
                    || stripos($summary['id_penggajian'], $search) !== false
                    || stripos($summary['periode'], $search) !== false;
            }));
        }

        return view('admin_penggajian.detail_penggajian', compact('summaries'));
    }
}
