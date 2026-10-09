<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public static function getInitialPresensiData(): array
    {
        return [
            ['id' => 1, 'nama' => 'Budi Santoso', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 2, 'nama' => 'Siti Aminah', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:15', 'jam_keluar' => '16:15', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 3, 'nama' => 'Andi Wijaya', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 4, 'nama' => 'Dewi Lestari', 'tanggal' => '2026-07-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Izin', 'jam_lembur' => '0', 'keterangan' => 'Mengurus perpanjangan STNK'],
            ['id' => 5, 'nama' => 'Rizky Pratama', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:05', 'jam_keluar' => '16:05', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 6, 'nama' => 'Anita Sari', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:30', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5', 'keterangan' => ''],
            ['id' => 7, 'nama' => 'Bambang Subianto', 'tanggal' => '2026-07-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Sakit', 'jam_lembur' => '0', 'keterangan' => 'Surat Dokter: Rawat Inap Tipes'],
            ['id' => 8, 'nama' => 'Eka Putri', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:10', 'jam_keluar' => '16:10', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 9, 'nama' => 'Faisal Rahman', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '20:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '4', 'keterangan' => ''],
            ['id' => 10, 'nama' => 'Gita Permata', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 11, 'nama' => 'Hendra Wijaya', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 12, 'nama' => 'Indah Kusuma', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:15', 'jam_keluar' => '16:15', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 13, 'nama' => 'Joko Susilo', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 14, 'nama' => 'Kartini Putri', 'tanggal' => '2026-07-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Izin', 'jam_lembur' => '0', 'keterangan' => 'Acara Keluarga di Luar Kota'],
            ['id' => 15, 'nama' => 'Lukman Hakim', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:05', 'jam_keluar' => '16:05', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 16, 'nama' => 'Maya Sari', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:30', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5', 'keterangan' => ''],
            ['id' => 17, 'nama' => 'Nugroho Saputra', 'tanggal' => '2026-07-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Sakit', 'jam_lembur' => '0', 'keterangan' => 'Surat Dokter: Demam Berdarah'],
            ['id' => 18, 'nama' => 'Oki Setiana', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:10', 'jam_keluar' => '16:40', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5', 'keterangan' => ''],
            ['id' => 19, 'nama' => 'Puji Astuti', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '20:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '4', 'keterangan' => ''],
            ['id' => 20, 'nama' => 'Qomarudin', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 21, 'nama' => 'Rian Hidayat', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 22, 'nama' => 'Santi Wijaya', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:15', 'jam_keluar' => '16:15', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 23, 'nama' => 'Teguh Prasetyo', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '17:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '1', 'keterangan' => ''],
            ['id' => 24, 'nama' => 'Umi Kulsum', 'tanggal' => '2026-07-01', 'jam_masuk' => '00:00', 'jam_keluar' => '00:00', 'kehadiran' => 'Izin', 'jam_lembur' => '0', 'keterangan' => 'Urusan keluarga'],
            ['id' => 25, 'nama' => 'Vina Panduwinata', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:05', 'jam_keluar' => '16:05', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 26, 'nama' => 'Wahyu Hidayat', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:30', 'kehadiran' => 'Hadir', 'jam_lembur' => '0.5', 'keterangan' => ''],
            ['id' => 27, 'nama' => 'Xena Putri', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
            ['id' => 28, 'nama' => 'Yayan Ruhian', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:10', 'jam_keluar' => '18:10', 'kehadiran' => 'Hadir', 'jam_lembur' => '2', 'keterangan' => ''],
            ['id' => 29, 'nama' => 'Zack Lee', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '20:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '4', 'keterangan' => ''],
            ['id' => 30, 'nama' => 'Ahmad Dahlan', 'tanggal' => '2026-07-01', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'kehadiran' => 'Hadir', 'jam_lembur' => '0', 'keterangan' => ''],
        ];
    }

    public static function getInitialAuditTrail(): array
    {
        return [
            [
                'id' => 1,
                'nama' => 'Dewi Lestari',
                'tanggal' => '2026-07-01',
                'timestamp' => '01 Jul 2026, 07:40',
                'user' => 'Admin Penggajian',
                'tipe' => 'Ketidakhadiran (Izin)',
                'perubahan' => 'Hadir (08:00 - 16:00) ➔ Izin',
                'alasan' => 'Mengurus perpanjangan STNK',
            ],
            [
                'id' => 2,
                'nama' => 'Bambang Subianto',
                'tanggal' => '2026-07-01',
                'timestamp' => '01 Jul 2026, 07:35',
                'user' => 'Admin Penggajian',
                'tipe' => 'Ketidakhadiran (Sakit)',
                'perubahan' => 'Hadir (08:00 - 16:00) ➔ Sakit',
                'alasan' => 'Surat Dokter: Rawat Inap Tipes',
            ],
            [
                'id' => 3,
                'nama' => 'Kartini Putri',
                'tanggal' => '2026-07-01',
                'timestamp' => '01 Jul 2026, 07:50',
                'user' => 'Admin Penggajian',
                'tipe' => 'Ketidakhadiran (Izin)',
                'perubahan' => 'Hadir (08:00 - 16:00) ➔ Izin',
                'alasan' => 'Acara Keluarga di Luar Kota',
            ],
            [
                'id' => 4,
                'nama' => 'Nugroho Saputra',
                'tanggal' => '2026-07-01',
                'timestamp' => '01 Jul 2026, 07:25',
                'user' => 'Admin Penggajian',
                'tipe' => 'Ketidakhadiran (Sakit)',
                'perubahan' => 'Hadir (08:00 - 16:00) ➔ Sakit',
                'alasan' => 'Surat Dokter: Demam Berdarah',
            ],
            [
                'id' => 5,
                'nama' => 'Umi Kulsum',
                'tanggal' => '2026-07-01',
                'timestamp' => '01 Jul 2026, 07:55',
                'user' => 'Admin Penggajian',
                'tipe' => 'Ketidakhadiran (Izin)',
                'perubahan' => 'Hadir (08:00 - 16:00) ➔ Izin',
                'alasan' => 'Urusan keluarga',
            ],
        ];
    }

    public static function getAuditTrailData(): array
    {
        if (function_exists('session') && session()->has('audit_trail_presensi')) {
            return session('audit_trail_presensi');
        }

        $initial = self::getInitialAuditTrail();
        if (function_exists('session')) {
            session()->put('audit_trail_presensi', $initial);
        }
        return $initial;
    }

    public static function getPresensiData(): array
    {
        if (function_exists('session') && session()->has('presensi_data')) {
            return session('presensi_data');
        }

        $initial = self::getInitialPresensiData();
        if (function_exists('session')) {
            session()->put('presensi_data', $initial);
        }
        return $initial;
    }

    public static function autoGenerateTodayIfNeeded(): void
    {
        $today = date('Y-m-d');
        // Melewati hari Minggu (libur operasional, 7 = Minggu)
        if (date('N', strtotime($today)) == 7) {
            return;
        }

        $allPresensi = self::getPresensiData();
        $existingTodayNames = [];
        foreach ($allPresensi as $p) {
            if ($p['tanggal'] === $today) {
                $existingTodayNames[$p['nama']] = true;
            }
        }

        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }
        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data'));
        $activeKaryawans = array_values(array_filter($karyawans, fn($k) => strtolower($k['status'] ?? 'aktif') === 'aktif'));

        $maxId = empty($allPresensi) ? 0 : max(array_column($allPresensi, 'id'));
        $added = false;

        $isSabtuToday = date('N', strtotime($today)) == 6;
        $defaultKeluar = $isSabtuToday ? '13:00' : '16:00';

        foreach ($activeKaryawans as $k) {
            if (!isset($existingTodayNames[$k['nama']])) {
                $maxId++;
                $allPresensi[] = [
                    'id' => $maxId,
                    'nama' => $k['nama'],
                    'tanggal' => $today,
                    'jam_masuk' => '08:00',
                    'jam_keluar' => $defaultKeluar,
                    'kehadiran' => 'Hadir',
                    'jam_lembur' => '0',
                    'keterangan' => '',
                ];
                $added = true;
            }
        }

        if ($added) {
            session()->put('presensi_data', $allPresensi);
        }
    }

    public static function getCalculatedPresensiData(): array
    {
        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data', KaryawanController::getInitialData()));
        $statusKaryawanMap = [];
        $bagianKaryawanMap = [];
        foreach ($karyawans as $karyawan) {
            $statusKaryawanMap[$karyawan['nama']] = $karyawan['status'] ?? 'Aktif';
            $bagianKaryawanMap[$karyawan['nama']] = $karyawan['bagian'] ?? 'Produksi';
        }

        $allPresensi = self::getPresensiData();
        $standarJamNormal = 7; // 7 jam kerja bersih (08:00 - 16:00 dikurangi 1 jam istirahat)
        $standarJamSabtu = 5;  // 5 jam kerja bersih (08:00 - 13:00)

        foreach ($allPresensi as &$row) {
            $row['status_karyawan'] = $statusKaryawanMap[$row['nama']] ?? 'Aktif';
            $row['is_inactive'] = strtolower($row['status_karyawan']) === 'inaktif';
            $bagian = $bagianKaryawanMap[$row['nama']] ?? 'Produksi';

            if ($row['is_inactive']) {
                $row['jam_masuk'] = '';
                $row['jam_keluar'] = '';
                $row['kehadiran'] = '';
                $row['jam_kerja_pokok'] = 0;
                $row['jam_lembur'] = 0;
                $row['menit_terlambat'] = 0;
                $row['is_terlambat'] = false;
            } else {
                if ($row['kehadiran'] === 'Hadir' && !empty($row['jam_masuk']) && !empty($row['jam_keluar']) && $row['jam_masuk'] !== '00:00') {
                    $masuk = strtotime($row['tanggal'] . ' ' . $row['jam_masuk']);
                    $keluar = strtotime($row['tanggal'] . ' ' . $row['jam_keluar']);

                    $durasiKotor = 0;
                    if ($keluar >= $masuk) {
                        $durasiKotor = ($keluar - $masuk) / 3600;
                    }

                    $isSabtu = date('N', strtotime($row['tanggal'])) == 6;

                    // Potongan 1 Jam Istirahat Siang (12:00 - 13:00)
                    $tgl = $row['tanggal'];
                    $jamIstirahatMulai = strtotime($tgl . ' 12:00');
                    $jamIstirahatSelesai = strtotime($tgl . ' 13:00');
                    $potonganIstirahat = 0;

                    if (!$isSabtu) {
                        // Hari Senin - Jumat: dipotong 1 jam jika jam kerja melintasi 12:00 s/d 13:00
                        if ($masuk <= $jamIstirahatMulai && $keluar >= $jamIstirahatSelesai) {
                            $potonganIstirahat = 1;
                        }
                    } else {
                        // Hari Sabtu (setengah hari 08:00 - 13:00): istirahat hanya dipotong jika lembur melewati 13:00
                        if ($masuk <= $jamIstirahatMulai && $keluar > $jamIstirahatSelesai) {
                            $potonganIstirahat = 1;
                        }
                    }

                    $durasiAktual = max(0, $durasiKotor - $potonganIstirahat);
                    $standarJam = $isSabtu ? $standarJamSabtu : $standarJamNormal;

                    $row['jam_kerja_pokok'] = round(min($durasiAktual, $standarJam), 2);
                    $row['jam_lembur'] = round(max(0, $durasiAktual - $standarJam), 2);

                    // Perhitungan Keterlambatan dengan Batas Toleransi 15 Menit dari 08:00
                    list($mH, $mM) = explode(':', $row['jam_masuk']);
                    $masukMenit = (int)$mH * 60 + (int)$mM;
                    $standarMenit = 8 * 60; // 08:00
                    $selisih = $masukMenit - $standarMenit;
                    if ($selisih > 15) {
                        $row['menit_terlambat'] = $selisih;
                        $row['is_terlambat'] = true;
                    } else {
                        $row['menit_terlambat'] = 0;
                        $row['is_terlambat'] = false;
                    }
                } else {
                    $row['jam_kerja_pokok'] = 0;
                    $row['jam_lembur'] = 0;
                    $row['menit_terlambat'] = 0;
                    $row['is_terlambat'] = false;
                }
            }
        }
        unset($row);

        return $allPresensi;
    }

    public function index(Request $request)
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }

        // Jalankan auto-generate otomatis untuk hari baru tanpa perlu klik manual
        self::autoGenerateTodayIfNeeded();

        $allPresensi = self::getCalculatedPresensiData();
        $standarJamNormal = 7;
        $standarJamSabtu = 5;

        $availableDates = array_values(array_unique(array_column($allPresensi, 'tanggal')));
        rsort($availableDates);

        $requestedDate = $request->get('date');
        if ($requestedDate) {
            $date = $requestedDate;
        } else {
            $today = date('Y-m-d');
            $date = in_array($today, $availableDates) ? $today : ($availableDates[0] ?? $today);
        }

        $fullDataset = $allPresensi;
        $search = $request->get('search');

        if ($search) {
            $allPresensi = array_filter($allPresensi, function ($p) use ($search) {
                return stripos($p['nama'], $search) !== false;
            });
        }

        if ($date) {
            $allPresensi = array_filter($allPresensi, function ($p) use ($date) {
                return $p['tanggal'] === $date;
            });
        }

        // Menghilangkan karyawan Inaktif dari tabel operasional harian
        $allPresensi = array_filter($allPresensi, function ($p) {
            return !$p['is_inactive'];
        });

        usort($allPresensi, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        // Audit Trail per karyawan
        $allAuditTrail = self::getAuditTrailData();
        $auditTrailByNama = [];
        foreach ($allAuditTrail as $at) {
            $auditTrailByNama[$at['nama']][] = $at;
        }

        $summaryByNama = [];
        foreach ($fullDataset as $ap) {
            $nama = $ap['nama'];

            if (!isset($summaryByNama[$nama])) {
                $summaryByNama[$nama] = [
                    'H' => 0,
                    'I' => 0,
                    'S' => 0,
                    'C' => 0,
                    'A' => 0,
                    'history' => [],
                ];
            }

            if ($ap['kehadiran'] !== '') {
                $summaryByNama[$nama]['history'][] = [
                    'date' => $ap['tanggal'],
                    'status' => $ap['kehadiran'],
                    'keterangan' => $ap['keterangan'] ?? '',
                ];
            }

            if ($ap['kehadiran'] === 'Hadir') {
                $summaryByNama[$nama]['H']++;
            } elseif ($ap['kehadiran'] === 'Izin') {
                $summaryByNama[$nama]['I']++;
            } elseif ($ap['kehadiran'] === 'Sakit') {
                $summaryByNama[$nama]['S']++;
            } elseif ($ap['kehadiran'] === 'Cuti') {
                $summaryByNama[$nama]['C']++;
            } elseif ($ap['kehadiran'] === 'Alpha' || $ap['kehadiran'] === 'Alfa') {
                $summaryByNama[$nama]['A']++;
            }
        }

        foreach ($allPresensi as &$p) {
            $nama = $p['nama'];
            $p['summary'] = [
                'H' => $summaryByNama[$nama]['H'] ?? 0,
                'I' => $summaryByNama[$nama]['I'] ?? 0,
                'S' => $summaryByNama[$nama]['S'] ?? 0,
                'C' => $summaryByNama[$nama]['C'] ?? 0,
                'A' => $summaryByNama[$nama]['A'] ?? 0,
            ];
            $p['history'] = $summaryByNama[$nama]['history'] ?? [];
            $p['audit_trail'] = $auditTrailByNama[$nama] ?? [];
        }
        unset($p);

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $totalCount = count($allPresensi);
        $totalPages = max(1, (int) ceil($totalCount / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $presensi = array_slice($allPresensi, ($page - 1) * $perPage, $perPage);

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data', KaryawanController::getInitialData()));
        $activeKaryawans = array_values(array_filter($karyawans, fn($k) => strtolower($k['status'] ?? 'aktif') === 'aktif'));
        usort($activeKaryawans, fn($a, $b) => strcasecmp($a['nama'], $b['nama']));

        $activeMonth = date('Y-m', strtotime($date));
        $currentMonthMin = $activeMonth . '-01';
        $currentMonthMax = ($activeMonth === date('Y-m')) ? date('Y-m-d') : date('Y-m-t', strtotime($date));

        return view('admin_penggajian.presensi', compact('presensi', 'page', 'totalPages', 'totalCount', 'standarJamNormal', 'standarJamSabtu', 'date', 'activeKaryawans', 'currentMonthMin', 'currentMonthMax', 'fullDataset'));
    }

    public function updateInline(Request $request)
    {
        $id = $request->input('id');
        $jamMasuk = $request->input('jam_masuk');
        $jamKeluar = $request->input('jam_keluar');
        $kehadiran = $request->input('kehadiran');
        $keterangan = $request->input('keterangan');

        $allPresensi = self::getPresensiData();
        $allAudit = self::getAuditTrailData();
        $found = false;

        foreach ($allPresensi as &$p) {
            if ((int)$p['id'] === (int)$id) {
                $oldKehadiran = $p['kehadiran'];
                $oldMasuk = $p['jam_masuk'];

                if ($request->has('jam_masuk')) {
                    $p['jam_masuk'] = $jamMasuk;
                }
                if ($request->has('jam_keluar')) {
                    $p['jam_keluar'] = $jamKeluar;
                }
                if ($request->has('kehadiran')) {
                    $p['kehadiran'] = $kehadiran;
                }
                if ($request->has('keterangan')) {
                    $p['keterangan'] = $keterangan;
                }

                $now = date('d M Y, H:i');

                // Audit Trail: Jika diubah ke Izin / Sakit / Cuti / Alpha
                if ($request->has('kehadiran') && $kehadiran !== $oldKehadiran && in_array($kehadiran, ['Izin', 'Sakit', 'Cuti', 'Alpha'])) {
                    $allAudit[] = [
                        'id' => count($allAudit) + 1,
                        'nama' => $p['nama'],
                        'tanggal' => $p['tanggal'],
                        'timestamp' => $now,
                        'user' => 'Admin Penggajian',
                        'tipe' => 'Ketidakhadiran (' . $kehadiran . ')',
                        'perubahan' => "{$oldKehadiran} ➔ {$kehadiran}",
                        'alasan' => !empty($keterangan) ? $keterangan : 'Pencatatan ketidakhadiran oleh Admin',
                    ];
                }

                // Audit Trail: Jika jam masuk diubah dan terlambat > 15 menit
                if ($request->has('jam_masuk') && $jamMasuk !== $oldMasuk && !empty($jamMasuk) && $jamMasuk !== '00:00') {
                    list($mH, $mM) = explode(':', $jamMasuk);
                    $selisih = ((int)$mH * 60 + (int)$mM) - 480;
                    if ($selisih > 15) {
                        $allAudit[] = [
                            'id' => count($allAudit) + 1,
                            'nama' => $p['nama'],
                            'tanggal' => $p['tanggal'],
                            'timestamp' => $now,
                            'user' => 'Admin Penggajian',
                            'tipe' => 'Keterlambatan',
                            'perubahan' => "Jam Masuk: {$oldMasuk} ➔ {$jamMasuk} (Terlambat {$selisih} mnt)",
                            'alasan' => 'Keterlambatan masuk kerja melebihi toleransi 15 menit',
                        ];
                    }
                }

                $found = true;
                break;
            }
        }
        unset($p);

        if ($found) {
            session()->put('presensi_data', $allPresensi);
            session()->put('audit_trail_presensi', $allAudit);

            // Hitung menit terlambat untuk respon frontend
            $menitTerlambat = 0;
            if (!empty($jamMasuk) && $jamMasuk !== '00:00' && $kehadiran === 'Hadir') {
                list($mH, $mM) = explode(':', $jamMasuk);
                $sel = ((int)$mH * 60 + (int)$mM) - 480;
                if ($sel > 15) {
                    $menitTerlambat = $sel;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data presensi berhasil disimpan',
                'menit_terlambat' => $menitTerlambat,
                'is_terlambat' => ($menitTerlambat > 0)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Data presensi tidak ditemukan'], 404);
    }

    public function storeKoreksi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Cuti,Alpha',
            'alasan' => 'required|string|min:3',
        ]);

        $nama = $request->input('nama');
        $tanggal = $request->input('tanggal');
        $status = $request->input('status');
        $alasan = $request->input('alasan');
        $jamMasuk = $status === 'Hadir' ? $request->input('jam_masuk', '08:00') : '00:00';
        $jamKeluar = $status === 'Hadir' ? $request->input('jam_keluar', '16:00') : '00:00';

        $allPresensi = self::getPresensiData();
        $allAudit = self::getAuditTrailData();
        $foundIndex = null;

        foreach ($allPresensi as $idx => $p) {
            if ($p['nama'] === $nama && $p['tanggal'] === $tanggal) {
                $foundIndex = $idx;
                break;
            }
        }

        $oldDesc = 'Belum Ada Presensi';
        if ($foundIndex !== null) {
            $oldP = $allPresensi[$foundIndex];
            $oldDesc = $oldP['kehadiran'] . ($oldP['jam_masuk'] && $oldP['jam_masuk'] !== '00:00' ? ' (' . $oldP['jam_masuk'] . ' - ' . $oldP['jam_keluar'] . ')' : '');
            $allPresensi[$foundIndex]['kehadiran'] = $status;
            $allPresensi[$foundIndex]['jam_masuk'] = $jamMasuk;
            $allPresensi[$foundIndex]['jam_keluar'] = $jamKeluar;
            $allPresensi[$foundIndex]['keterangan'] = $alasan;
        } else {
            $maxId = empty($allPresensi) ? 0 : max(array_column($allPresensi, 'id'));
            $allPresensi[] = [
                'id' => $maxId + 1,
                'nama' => $nama,
                'tanggal' => $tanggal,
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => $jamKeluar,
                'kehadiran' => $status,
                'jam_lembur' => 0,
                'keterangan' => $alasan,
            ];
        }

        $newDesc = $status . ($status === 'Hadir' ? ' (' . $jamMasuk . ' - ' . $jamKeluar . ')' : '');
        $newAuditId = empty($allAudit) ? 1 : max(array_column($allAudit, 'id')) + 1;
        $allAudit[] = [
            'id' => $newAuditId,
            'nama' => $nama,
            'tanggal' => $tanggal,
            'timestamp' => date('d M Y, H:i'),
            'user' => 'Admin Penggajian',
            'tipe' => 'Koreksi Presensi (' . $status . ')',
            'perubahan' => $oldDesc . ' ➔ ' . $newDesc,
            'alasan' => $alasan,
        ];

        session()->put('presensi_data', $allPresensi);
        session()->put('audit_trail_presensi', $allAudit);

        return redirect()->route('presensi.index', ['date' => $tanggal])
            ->with('success', 'Koreksi presensi untuk ' . $nama . ' pada ' . date('d M Y', strtotime($tanggal)) . ' berhasil disimpan ke Jejak Audit.');
    }
}
