<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPenggajianController extends Controller
{
    public function index(Request $request)
    {
        $allLaporan = [
            [
                'id_laporan' => 'LPG-001',
                'id_penggajian' => 'PGJ-001',
                'id_detail' => '1, 2, 3, 4, 5',
                'nama' => 'Budi Santoso',
                'bagian' => 'Produksi',
                'jumlah_hari_kerja' => 26,
                'jumlah_hari_lembur' => 5,
                'gaji_pokok' => 3900000,
                'total_lembur' => 100000,
                'total_bonus' => 1500000,
                'total_potongan' => 80000,
                'rincian_lembur' => [
                    [
                        'keterangan' => 'Lembur kerja tambahan',
                        'jumlah_jam' => 5,
                        'tarif' => 20000,
                        'subtotal' => 100000,
                    ],
                ],
                'rincian_bonus' => [
                    [
                        'nama' => 'Bonus THR',
                        'jumlah' => 1,
                        'satuan' => 'Kali',
                        'tarif' => 1500000,
                        'subtotal' => 1500000,
                    ],
                ],
                'rincian_potongan' => [
                    [
                        'nama' => 'Potongan BPJS Kesehatan',
                        'jumlah' => 1,
                        'tarif' => 50000,
                        'subtotal' => 50000,
                    ],
                    [
                        'nama' => 'Potongan BPJS Ketenagakerjaan',
                        'jumlah' => 1,
                        'tarif' => 30000,
                        'subtotal' => 30000,
                    ],
                ],
                'tanggal_pembayaran' => '2026-06-05',
                'keterangan' => 'Gaji produksi bulan Juni 2026',
                'status_pembayaran' => 'Dibayar',
                'bulan' => '06',
                'tahun' => '2026',
            ],
            [
                'id_laporan' => 'LPG-002',
                'id_penggajian' => 'PGJ-002',
                'id_detail' => '6, 7, 8',
                'nama' => 'Eka Putri',
                'bagian' => 'Produksi',
                'jumlah_hari_kerja' => 24,
                'jumlah_hari_lembur' => 2,
                'gaji_pokok' => 3600000,
                'total_lembur' => 40000,
                'total_bonus' => 250000,
                'total_potongan' => 50000,
                'rincian_lembur' => [
                    [
                        'keterangan' => 'Lembur kerja tambahan',
                        'jumlah_jam' => 2,
                        'tarif' => 20000,
                        'subtotal' => 40000,
                    ],
                ],
                'rincian_bonus' => [
                    [
                        'nama' => 'Bonus Produksi Bahan Baku',
                        'jumlah' => 25,
                        'satuan' => 'Kg',
                        'tarif' => 10000,
                        'subtotal' => 250000,
                    ],
                ],
                'rincian_potongan' => [
                    [
                        'nama' => 'Potongan BPJS Kesehatan',
                        'jumlah' => 1,
                        'tarif' => 50000,
                        'subtotal' => 50000,
                    ],
                ],
                'tanggal_pembayaran' => null,
                'keterangan' => 'Menunggu konfirmasi pembayaran gaji',
                'status_pembayaran' => 'Diproses',
                'bulan' => '06',
                'tahun' => '2026',
            ],
            [
                'id_laporan' => 'LPG-003',
                'id_penggajian' => 'PGJ-003',
                'id_detail' => '9, 10, 11, 12',
                'nama' => 'Joko Susilo',
                'bagian' => 'Peternakan',
                'jumlah_hari_kerja' => 22,
                'jumlah_hari_lembur' => 0,
                'gaji_pokok' => 2200000,
                'total_lembur' => 0,
                'total_bonus' => 150000,
                'total_potongan' => 30000,
                'rincian_lembur' => [],
                'rincian_bonus' => [
                    [
                        'nama' => 'Bonus Produksi Susu Peternakan',
                        'jumlah' => 30,
                        'satuan' => 'Liter',
                        'tarif' => 5000,
                        'subtotal' => 150000,
                    ],
                ],
                'rincian_potongan' => [
                    [
                        'nama' => 'Potongan BPJS Ketenagakerjaan',
                        'jumlah' => 1,
                        'tarif' => 30000,
                        'subtotal' => 30000,
                    ],
                ],
                'tanggal_pembayaran' => null,
                'keterangan' => 'Gaji peternakan dan bonus produksi susu harian',
                'status_pembayaran' => 'Diproses',
                'bulan' => '06',
                'tahun' => '2026',
            ],
            [
                'id_laporan' => 'LPG-004',
                'id_penggajian' => 'PGJ-004',
                'id_detail' => '13, 14, 15',
                'nama' => 'Siti Aminah',
                'bagian' => 'Produksi',
                'jumlah_hari_kerja' => 25,
                'jumlah_hari_lembur' => 3,
                'gaji_pokok' => 3750000,
                'total_lembur' => 60000,
                'total_bonus' => 300000,
                'total_potongan' => 80000,
                'rincian_lembur' => [
                    [
                        'keterangan' => 'Lembur kerja tambahan',
                        'jumlah_jam' => 3,
                        'tarif' => 20000,
                        'subtotal' => 60000,
                    ],
                ],
                'rincian_bonus' => [
                    [
                        'nama' => 'Bonus Kehadiran',
                        'jumlah' => 1,
                        'satuan' => 'Kali',
                        'tarif' => 300000,
                        'subtotal' => 300000,
                    ],
                ],
                'rincian_potongan' => [
                    [
                        'nama' => 'Potongan BPJS Kesehatan',
                        'jumlah' => 1,
                        'tarif' => 50000,
                        'subtotal' => 50000,
                    ],
                    [
                        'nama' => 'Potongan BPJS Ketenagakerjaan',
                        'jumlah' => 1,
                        'tarif' => 30000,
                        'subtotal' => 30000,
                    ],
                ],
                'tanggal_pembayaran' => '2026-05-31',
                'keterangan' => 'Gaji produksi bulan Mei 2026',
                'status_pembayaran' => 'Dibayar',
                'bulan' => '05',
                'tahun' => '2026',
            ],
        ];

        $allLaporan = array_map(function ($laporan) {
            $laporan['total_gaji_bersih'] = $laporan['gaji_pokok']
                + $laporan['total_lembur']
                + $laporan['total_bonus']
                - $laporan['total_potongan'];

            return $laporan;
        }, $allLaporan);

        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        if ($search) {
            $allLaporan = array_filter($allLaporan, function ($l) use ($search) {
                return stripos($l['nama'], $search) !== false ||
                    stripos($l['id_laporan'], $search) !== false ||
                    stripos($l['id_penggajian'], $search) !== false;
            });
        }

        if ($bulan) {
            $allLaporan = array_filter($allLaporan, fn ($l) => $l['bulan'] === $bulan);
        }

        if ($tahun) {
            $allLaporan = array_filter($allLaporan, fn ($l) => $l['tahun'] === $tahun);
        }

        if ($status) {
            $allLaporan = array_filter($allLaporan, fn ($l) => $l['status_pembayaran'] === $status);
        }

        $summary = [
            'total_karyawan' => count($allLaporan),
            'total_gaji_pokok' => array_sum(array_column($allLaporan, 'gaji_pokok')),
            'total_lembur' => array_sum(array_column($allLaporan, 'total_lembur')),
            'total_bonus' => array_sum(array_column($allLaporan, 'total_bonus')),
            'total_potongan' => array_sum(array_column($allLaporan, 'total_potongan')),
            'total_gaji_bersih' => array_sum(array_column($allLaporan, 'total_gaji_bersih')),
        ];

        return view('admin_penggajian.laporan_penggajian', compact('allLaporan', 'summary', 'bulan', 'tahun', 'status'));
    }
}
