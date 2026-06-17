<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public static function getPresensiData(): array
    {
        return [
            ['id' => 1, 'nama' => 'Budi Santoso', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 2, 'nama' => 'Siti Aminah', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:15', 'jam_keluar' => '16:15', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 3, 'nama' => 'Andi Wijaya', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 4, 'nama' => 'Dewi Lestari', 'tanggal' => '2026-03-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Izin', 'jam_lembur' => '0'],
            ['id' => 5, 'nama' => 'Rizky Pratama', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:05', 'jam_keluar' => '16:05', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 6, 'nama' => 'Anita Sari', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:30', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5'],
            ['id' => 7, 'nama' => 'Bambang Subianto', 'tanggal' => '2026-03-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Sakit', 'jam_lembur' => '0'],
            ['id' => 8, 'nama' => 'Eka Putri', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:10', 'jam_keluar' => '16:10', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 9, 'nama' => 'Faisal Rahman', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:00', 'jam_keluar' => '20:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '4'],
            ['id' => 10, 'nama' => 'Gita Permata', 'tanggal' => '2026-03-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 11, 'nama' => 'Hendra Wijaya', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 12, 'nama' => 'Indah Kusuma', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:15', 'jam_keluar' => '16:15', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 13, 'nama' => 'Joko Susilo', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 14, 'nama' => 'Kartini Putri', 'tanggal' => '2026-03-02', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Izin', 'jam_lembur' => '0'],
            ['id' => 15, 'nama' => 'Lukman Hakim', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:05', 'jam_keluar' => '16:05', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
            ['id' => 16, 'nama' => 'Maya Sari', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:00', 'jam_keluar' => '16:30', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5'],
            ['id' => 17, 'nama' => 'Nugroho Saputra', 'tanggal' => '2026-03-02', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Sakit', 'jam_lembur' => '0'],
            ['id' => 18, 'nama' => 'Oki Setiana', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:10', 'jam_keluar' => '16:40', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5'],
            ['id' => 19, 'nama' => 'Puji Astuti', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:00', 'jam_keluar' => '20:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '4'],
            ['id' => 20, 'nama' => 'Qomarudin', 'tanggal' => '2026-03-02', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0'],
        ];
    }

    public function index(Request $request)
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data'));
        session()->put('karyawan_data', $karyawans);

        $statusKaryawanMap = [];
        foreach ($karyawans as $karyawan) {
            $statusKaryawanMap[$karyawan['nama']] = $karyawan['status'] ?? 'Aktif';
        }

        // Mock data for attendance based on wireframe columns
        $allPresensi = self::getPresensiData();

        foreach ($allPresensi as &$row) {
            $row['status_karyawan'] = $statusKaryawanMap[$row['nama']] ?? 'Aktif';
            $row['is_inactive'] = strtolower($row['status_karyawan']) === 'inaktif';

            if ($row['is_inactive']) {
                $row['jam_masuk'] = '';
                $row['jam_keluar'] = '';
                $row['kehadiran'] = '';
                $row['jam_lembur'] = '0';
            }
        }
        unset($row);

        $fullDataset = $allPresensi;
        $search = $request->get('search');
        $date = $request->get('date');

        if ($search) {
            $allPresensi = array_filter($allPresensi, function($p) use ($search) {
                return stripos($p['nama'], $search) !== false;
            });
        }

        if ($date) {
            $allPresensi = array_filter($allPresensi, function($p) use ($date) {
                return $p['tanggal'] === $date;
            });
        }

        usort($allPresensi, function ($a, $b) {
            if ($a['is_inactive'] === $b['is_inactive']) {
                return $a['id'] <=> $b['id'];
            }

            return $a['is_inactive'] ? 1 : -1;
        });

        // Calculate summary across the entire mock dataset for the current month
        foreach ($allPresensi as &$p) {
            $H = 0; $I = 0; $S = 0; $C = 0; $A = 0;
            $history = [];
            foreach ($fullDataset as $ap) {
                if ($ap['nama'] === $p['nama']) {
                    if ($ap['kehadiran'] !== '') {
                        $history[] = [
                            'date' => $ap['tanggal'],
                            'status' => $ap['kehadiran']
                        ];
                    }
                    
                    if ($ap['kehadiran'] === 'Hadir') $H++;
                    elseif ($ap['kehadiran'] === 'Izin') $I++;
                    elseif ($ap['kehadiran'] === 'Sakit') $S++;
                    elseif ($ap['kehadiran'] === 'Cuti') $C++;
                    elseif ($ap['kehadiran'] === 'Alpha') $A++;
                }
            }
            $p['summary'] = compact('H', 'I', 'S', 'C', 'A');
            $p['history'] = $history;
        }

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $presensi = array_slice($allPresensi, ($page - 1) * $perPage, $perPage);

        return view('admin_penggajian.presensi', compact('presensi', 'page'));
    }
}
