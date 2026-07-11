<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public static function getInitialData(): array
    {
        $data = [
            ['id' => 1, 'nik' => '3404011203900001', 'nama' => 'Budi Santoso', 'npwp' => '1612034567890001', 'alamat' => 'Jl. Merdeka No. 10, Yogyakarta', 'no_telp' => '08123456789', 'status' => 'Aktif'],
            ['id' => 2, 'nik' => '3404012204910002', 'nama' => 'Siti Aminah', 'npwp' => '1612034567890002', 'alamat' => 'Jl. Mawar No. 5, Yogyakarta', 'no_telp' => '08129876543', 'status' => 'Inaktif'],
            ['id' => 3, 'nik' => '3404011405920003', 'nama' => 'Andi Wijaya', 'npwp' => '1612034567890003', 'alamat' => 'Jl. Melati No. 2, Yogyakarta', 'no_telp' => '08131122334', 'status' => 'Inaktif'],
            ['id' => 4, 'nik' => '3404015006930004', 'nama' => 'Dewi Lestari', 'npwp' => '1612034567890004', 'alamat' => 'Jl. Anggrek No. 15, Yogyakarta', 'no_telp' => '08122233445', 'status' => 'Aktif'],
            ['id' => 5, 'nik' => '3404011107940005', 'nama' => 'Rizky Pratama', 'npwp' => '1612034567890005', 'alamat' => 'Jl. Kenanga No. 8, Magelang', 'no_telp' => '08133344556', 'status' => 'Aktif'],
            ['id' => 6, 'nik' => '3404014208950006', 'nama' => 'Anita Sari', 'npwp' => '1612034567890006', 'alamat' => 'Jl. Cempaka No. 12, Yogyakarta', 'no_telp' => '08144455667', 'status' => 'Inaktif'],
            ['id' => 7, 'nik' => '3404011509960007', 'nama' => 'Bambang Subianto', 'npwp' => '1612034567890007', 'alamat' => 'Jl. Teratai No. 20, Magelang', 'no_telp' => '08155566778', 'status' => 'Aktif'],
            ['id' => 8, 'nik' => '3404014510970008', 'nama' => 'Eka Putri', 'npwp' => '1612034567890008', 'alamat' => 'Jl. Kamboja No. 3, Yogyakarta', 'no_telp' => '08166677889', 'status' => 'Aktif'],
            ['id' => 9, 'nik' => '3404011811980009', 'nama' => 'Faisal Rahman', 'npwp' => '1612034567890009', 'alamat' => 'Jl. Melur No. 7, Yogyakarta', 'no_telp' => '08177788990', 'status' => 'Inaktif'],
            ['id' => 10, 'nik' => '3404015212990010', 'nama' => 'Gita Permata', 'npwp' => '1612034567890010', 'alamat' => 'Jl. Dahlia No. 11, Yogyakarta', 'no_telp' => '08188899001', 'status' => 'Aktif'],
            ['id' => 11, 'nik' => '3404021203900011', 'nama' => 'Hendra Wijaya', 'npwp' => '1612034567890011', 'alamat' => 'Jl. Garuda No. 1, Magelang', 'no_telp' => '08111002233', 'status' => 'Aktif'],
            ['id' => 12, 'nik' => '3404024204910012', 'nama' => 'Indah Kusuma', 'npwp' => '1612034567890012', 'alamat' => 'Jl. Merak No. 4, Yogyakarta', 'no_telp' => '08122113344', 'status' => 'Inaktif'],
            ['id' => 13, 'nik' => '3404021405920013', 'nama' => 'Joko Susilo', 'npwp' => '1612034567890013', 'alamat' => 'Jl. Elang No. 6, Yogyakarta', 'no_telp' => '08133224455', 'status' => 'Aktif'],
            ['id' => 14, 'nik' => '3404025006930014', 'nama' => 'Kartini Putri', 'npwp' => '1612034567890014', 'alamat' => 'Jl. Rajawali No. 9, Yogyakarta', 'no_telp' => '08144335566', 'status' => 'Aktif'],
            ['id' => 15, 'nik' => '3404021107940015', 'nama' => 'Lukman Hakim', 'npwp' => '1612034567890015', 'alamat' => 'Jl. Kancil No. 2, Yogyakarta', 'no_telp' => '08155446677', 'status' => 'Inaktif'],
            ['id' => 16, 'nik' => '3404024208950016', 'nama' => 'Maya Sari', 'npwp' => '1612034567890016', 'alamat' => 'Jl. Rusa No. 5, Yogyakarta', 'no_telp' => '08166557788', 'status' => 'Aktif'],
            ['id' => 17, 'nik' => '3404021509960017', 'nama' => 'Nugroho Saputra', 'npwp' => '1612034567890017', 'alamat' => 'Jl. Gajah No. 8, Yogyakarta', 'no_telp' => '08177668899', 'status' => 'Aktif'],
            ['id' => 18, 'nik' => '3404024510970018', 'nama' => 'Oki Setiana', 'npwp' => '1612034567890018', 'alamat' => 'Jl. Harimau No. 10, Yogyakarta', 'no_telp' => '08188779900', 'status' => 'Inaktif'],
            ['id' => 19, 'nik' => '3404021811980019', 'nama' => 'Puji Astuti', 'npwp' => '1612034567890019', 'alamat' => 'Jl. Singa No. 3, Magelang', 'no_telp' => '08199880011', 'status' => 'Aktif'],
            ['id' => 20, 'nik' => '3404025212990020', 'nama' => 'Qomarudin', 'npwp' => '1612034567890020', 'alamat' => 'Jl. Beruang No. 7, Yogyakarta', 'no_telp' => '08100991122', 'status' => 'Aktif'],
            ['id' => 21, 'nik' => '3404031203900021', 'nama' => 'Rian Hidayat', 'npwp' => '1612034567890021', 'alamat' => 'Jl. Solo Km 10, Yogyakarta', 'no_telp' => '08123344556', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 22, 'nik' => '3404034204910022', 'nama' => 'Santi Wijaya', 'npwp' => '1612034567890022', 'alamat' => 'Jl. Kaliurang Km 5, Yogyakarta', 'no_telp' => '08123344557', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 23, 'nik' => '3404031405920023', 'nama' => 'Teguh Prasetyo', 'npwp' => '1612034567890023', 'alamat' => 'Jl. Gejayan No. 8, Yogyakarta', 'no_telp' => '08123344558', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 24, 'nik' => '3404035006930024', 'nama' => 'Umi Kulsum', 'npwp' => '1612034567890024', 'alamat' => 'Jl. Palagan No. 12, Yogyakarta', 'no_telp' => '08123344559', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 25, 'nik' => '3404031107940025', 'nama' => 'Vina Panduwinata', 'npwp' => '1612034567890025', 'alamat' => 'Jl. Godean Km 4, Yogyakarta', 'no_telp' => '08123344560', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 26, 'nik' => '3404034208950026', 'nama' => 'Wahyu Hidayat', 'npwp' => '1612034567890026', 'alamat' => 'Jl. Imogiri No. 15, Yogyakarta', 'no_telp' => '08123344561', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 27, 'nik' => '3404031509960027', 'nama' => 'Xena Putri', 'npwp' => '1612034567890027', 'alamat' => 'Jl. Kusumanegara No. 20, Yogyakarta', 'no_telp' => '08123344562', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 28, 'nik' => '3404034510970028', 'nama' => 'Yayan Ruhian', 'npwp' => '1612034567890028', 'alamat' => 'Jl. Parangtritis Km 6, Yogyakarta', 'no_telp' => '08123344563', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 29, 'nik' => '3404031811980029', 'nama' => 'Zack Lee', 'npwp' => '1612034567890029', 'alamat' => 'Jl. Bantul Km 3, Yogyakarta', 'no_telp' => '08123344564', 'status' => 'Aktif', 'bagian' => 'Packing'],
            ['id' => 30, 'nik' => '3404035212990030', 'nama' => 'Ahmad Dahlan', 'npwp' => '1612034567890030', 'alamat' => 'Jl. KH. Ahmad Dahlan No. 5, Yogyakarta', 'no_telp' => '08123344565', 'status' => 'Aktif', 'bagian' => 'Packing'],
        ];

        return self::withDefaultBagian($data);
    }

    public static function withDefaultBagian(array $karyawans): array
    {
        return array_map(function ($k) {
            if (!isset($k['bagian'])) {
                $k['bagian'] = ((int) $k['id'] <= 12) ? 'Produksi' : 'Peternakan';
            }
            if (!isset($k['has_history'])) {
                // Mock: IDs 1-15 have history (cannot be hard-deleted), 16+ do not (can be hard-deleted).
                $k['has_history'] = ((int) $k['id'] <= 15);
            }

            return $k;
        }, $karyawans);
    }

    public function index(Request $request)
    {
        if (!session()->has('karyawan_data') || count(session('karyawan_data', [])) < count(self::getInitialData()) || strpos(session('karyawan_data')[0]['npwp'] ?? '', '.') !== false) {
            session()->put('karyawan_data', self::getInitialData());
        }

        $allKaryawan = self::withDefaultBagian(session('karyawan_data'));

        $status = $request->query('status', 'aktif');
        if ($status && strtolower($status) !== 'semua') {
            $allKaryawan = array_values(array_filter($allKaryawan, function ($k) use ($status) {
                return strtolower($k['status']) === strtolower($status);
            }));
        }

        $search = $request->query('search');
        if ($search) {
            $allKaryawan = array_values(array_filter($allKaryawan, function ($k) use ($search) {
                return stripos((string) $k['id'], $search) !== false
                    || stripos((string) $k['nik'], $search) !== false
                    || stripos((string) $k['nama'], $search) !== false
                    || stripos((string) $k['npwp'], $search) !== false
                    || stripos((string) ($k['bagian'] ?? ''), $search) !== false
                    || stripos((string) $k['alamat'], $search) !== false
                    || stripos((string) $k['no_telp'], $search) !== false
                    || stripos((string) $k['status'], $search) !== false;
            }));
        }

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $total = count($allKaryawan);
        $totalPages = max(1, ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $karyawan = array_slice($allKaryawan, ($page - 1) * $perPage, $perPage);

        return view('admin_penggajian.data_karyawan', compact('karyawan', 'page', 'totalPages', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'npwp' => 'required|string',
            'bagian' => 'required|in:Produksi,Peternakan,Packing',
            'no_telp' => ['nullable', 'string', 'regex:/^08[0-9]{8,11}$/'],
            'alamat' => 'required|string',
            'nama_bank' => 'required|string',
            'no_rekening' => 'nullable|string',
        ]);

        $allKaryawan = session('karyawan_data', []);
        $newId = count($allKaryawan) > 0 ? max(array_column($allKaryawan, 'id')) + 1 : 1;

        $newKaryawan = [
            'id' => $newId,
            'nik' => $data['nik'],
            'nama' => $data['nama'],
            'npwp' => $data['npwp'],
            'bagian' => $data['bagian'],
            'alamat' => $data['alamat'],
            'no_telp' => $data['no_telp'] ?? '-',
            'status' => 'Aktif',
            'has_history' => false,
            'nama_bank' => $data['nama_bank'],
            'no_rekening' => $data['no_rekening'] ?? '-',
        ];

        array_unshift($allKaryawan, $newKaryawan);
        session()->put('karyawan_data', $allKaryawan);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'npwp' => 'required|string',
            'bagian' => 'required|in:Produksi,Peternakan,Packing',
            'no_telp' => ['nullable', 'string', 'regex:/^08[0-9]{8,11}$/'],
            'alamat' => 'required|string',
            'status' => 'required|string',
            'nama_bank' => 'required|string',
            'no_rekening' => 'nullable|string',
        ]);

        $allKaryawan = session('karyawan_data', []);

        foreach ($allKaryawan as &$k) {
            if ($k['id'] == $id) {
                $k['nik'] = $data['nik'];
                $k['nama'] = $data['nama'];
                $k['npwp'] = $data['npwp'];
                $k['bagian'] = $data['bagian'];
                $k['alamat'] = $data['alamat'];
                $k['no_telp'] = $data['no_telp'] ?? '-';
                $k['status'] = $data['status'];
                $k['nama_bank'] = $data['nama_bank'];
                $k['no_rekening'] = $data['no_rekening'] ?? '-';
                break;
            }
        }

        session()->put('karyawan_data', $allKaryawan);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diubah.');
    }

    public function destroy(int $id)
    {
        $allKaryawan = session('karyawan_data', []);

        $allKaryawan = array_filter($allKaryawan, function ($k) use ($id) {
            return $k['id'] != $id;
        });

        session()->put('karyawan_data', array_values($allKaryawan));

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $allKaryawan = session('karyawan_data', []);

        foreach ($allKaryawan as &$k) {
            if ($k['id'] == $id) {
                // One-way deactivation
                $k['status'] = 'Inaktif';
                break;
            }
        }

        session()->put('karyawan_data', $allKaryawan);

        return redirect()->route('karyawan.index')->with('success', 'Status karyawan berhasil dinonaktifkan.');
    }
}
