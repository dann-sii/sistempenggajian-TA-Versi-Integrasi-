<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\GajiBonusController;
use App\Http\Controllers\Api\Admin\PotonganGajiController;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    private function getInitialData() 
    {
        return [
            ['id' => 1, 'nik' => '1234567890', 'nama' => 'Budi Santoso', 'npwp' => '12.345.678.9-000.000', 'alamat' => 'Jl. Merdeka No. 10, Yogyakarta', 'no_telp' => '08123456789', 'status' => 'Aktif'],
            ['id' => 2, 'nik' => '0987654321', 'nama' => 'Siti Aminah', 'npwp' => '98.765.432.1-000.000', 'alamat' => 'Jl. Mawar No. 5, Yogyakarta', 'no_telp' => '08129876543', 'status' => 'Inaktif'],
            ['id' => 3, 'nik' => '1122334455', 'nama' => 'Andi Wijaya', 'npwp' => '11.223.344.5-000.000', 'alamat' => 'Jl. Melati No. 2, Yogyakarta', 'no_telp' => '08131122334', 'status' => 'Inaktif'],
            ['id' => 4, 'nik' => '2233445566', 'nama' => 'Dewi Lestari', 'npwp' => '22.334.455.6-000.000', 'alamat' => 'Jl. Anggrek No. 15, Yogyakarta', 'no_telp' => '08122233445', 'status' => 'Aktif'],
            ['id' => 5, 'nik' => '3344556677', 'nama' => 'Rizky Pratama', 'npwp' => '33.445.566.7-000.000', 'alamat' => 'Jl. Kenanga No. 8, Magelang', 'no_telp' => '08133344556', 'status' => 'Aktif'],
            ['id' => 6, 'nik' => '4455667788', 'nama' => 'Anita Sari', 'npwp' => '44.556.677.8-000.000', 'alamat' => 'Jl. Cempaka No. 12, Yogyakarta', 'no_telp' => '08144455667', 'status' => 'Inaktif'],
            ['id' => 7, 'nik' => '5566778899', 'nama' => 'Bambang Subianto', 'npwp' => '55.667.788.9-000.000', 'alamat' => 'Jl. Teratai No. 20, Magelang', 'no_telp' => '08155566778', 'status' => 'Aktif'],
            ['id' => 8, 'nik' => '6677889900', 'nama' => 'Eka Putri', 'npwp' => '66.778.899.0-000.000', 'alamat' => 'Jl. Kamboja No. 3, Yogyakarta', 'no_telp' => '08166677889', 'status' => 'Aktif'],
            ['id' => 9, 'nik' => '7788990011', 'nama' => 'Faisal Rahman', 'npwp' => '77.889.900.1-000.000', 'alamat' => 'Jl. Melur No. 7, Yogyakarta', 'no_telp' => '08177788990', 'status' => 'Inaktif'],
            ['id' => 10, 'nik' => '8899001122', 'nama' => 'Gita Permata', 'npwp' => '88.990.011.2-000.000', 'alamat' => 'Jl. Dahlia No. 11, Yogyakarta', 'no_telp' => '08188899001', 'status' => 'Aktif']
        ];
    }

    public function index()
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', $this->getInitialData());
        }
        
        $karyawans = session('karyawan_data');

        // Data presensi: hitung jumlah hari hadir dan jam lembur per karyawan per bulan
        $allPresensi = [
            ['nama' => 'Budi Santoso',     'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Budi Santoso',     'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Siti Aminah',      'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Andi Wijaya',      'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Dewi Lestari',     'tanggal' => '2026-03-01', 'kehadiran' => 'Izin', 'jam_lembur' => 0],
            ['nama' => 'Rizky Pratama',    'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Anita Sari',       'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0.5],
            ['nama' => 'Bambang Subianto', 'tanggal' => '2026-03-01', 'kehadiran' => 'Sakit', 'jam_lembur' => 0],
            ['nama' => 'Eka Putri',        'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Faisal Rahman',    'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 4],
            ['nama' => 'Gita Permata',     'tanggal' => '2026-03-01', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Hendra Wijaya',    'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Indah Kusuma',     'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Joko Susilo',      'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Kartini Putri',    'tanggal' => '2026-03-02', 'kehadiran' => 'Izin', 'jam_lembur' => 0],
            ['nama' => 'Lukman Hakim',     'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
            ['nama' => 'Maya Sari',        'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0.5],
            ['nama' => 'Nugroho Saputra',  'tanggal' => '2026-03-02', 'kehadiran' => 'Sakit', 'jam_lembur' => 0],
            ['nama' => 'Oki Setiana',      'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0.5],
            ['nama' => 'Puji Astuti',      'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 4],
            ['nama' => 'Qomarudin',        'tanggal' => '2026-03-02', 'kehadiran' => 'Hadir', 'jam_lembur' => 0],
        ];

        // Agregasi: hitung jumlah hari hadir dan total jam lembur per nama karyawan
        $hariKerjaMap = [];
        $jamLemburMap = [];
        foreach ($allPresensi as $p) {
            if ($p['kehadiran'] === 'Hadir') {
                $hariKerjaMap[$p['nama']] = ($hariKerjaMap[$p['nama']] ?? 0) + 1;
            }

            $jamLemburMap[$p['nama']] = ($jamLemburMap[$p['nama']] ?? 0) + (float) ($p['jam_lembur'] ?? 0);
        }

        // Gaji pokok (tarif per jam, Aktif)
        $gajiPokokAktif = 15000.00; // Rp 15.000 per jam
        $gajiLemburAktif = 20000.00; // Rp 20.000 per jam, sesuai menu Gaji Lembur aktif

        // Buat lookup karyawan_id => data untuk dipakai JS
        $karyawanLookup = [];
        foreach ($karyawans as $k) {
            $hariKerja = $hariKerjaMap[$k['nama']] ?? 0;
            $jamLembur = $jamLemburMap[$k['nama']] ?? 0;

            $karyawanLookup[$k['id']] = [
                'nama'        => $k['nama'],
                'hari_kerja'  => $hariKerja,
                'gaji_pokok'  => $gajiPokokAktif,
                'lembur'      => $jamLembur > 0 ? 'Ya' : 'Tidak',
                'jam_lembur'  => $jamLembur,
                'tarif_lembur' => $gajiLemburAktif,
                'total_lembur' => $jamLembur * $gajiLemburAktif,
            ];
        }

        $gajiBonus = array_values(array_filter(GajiBonusController::getBonusData(), function ($bonus) {
            return strtolower($bonus['status']) === 'aktif';
        }));

        $potonganGaji = array_values(array_filter(PotonganGajiController::getPotonganData(), function ($potongan) {
            return strtolower($potongan['status']) === 'aktif';
        }));

        return view('admin_penggajian.penggajian.penggajian', compact('karyawans', 'karyawanLookup', 'gajiBonus', 'potonganGaji'));
    }
}
