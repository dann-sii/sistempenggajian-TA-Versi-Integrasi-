<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AktivitasKaryawanController extends Controller
{
    public static function getProdukResepData(): array
    {
        return [
            [
                'id_produk' => 'PR01',
                'nama_produk' => 'Susu Kambing Original',
                'jumlah_standar' => 40,
                'satuan_produk' => 'pcs / 1 kg',
                'susu_liter' => 5,
                'gula_gram' => 250,
                'perasa_jenis' => 'Vanila',
                'perasa_gram' => 15,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PR02',
                'nama_produk' => 'Susu Kambing Jahe',
                'jumlah_standar' => 40,
                'satuan_produk' => 'pcs / 1 kg',
                'susu_liter' => 5,
                'gula_gram' => 250,
                'perasa_jenis' => 'Jahe',
                'perasa_gram' => 20,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PR03',
                'nama_produk' => 'Susu Kambing Coklat',
                'jumlah_standar' => 40,
                'satuan_produk' => 'pcs / 1 kg',
                'susu_liter' => 5,
                'gula_gram' => 200,
                'perasa_jenis' => 'Coklat',
                'perasa_gram' => 25,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PR04',
                'nama_produk' => 'Susu Kambing Karamel',
                'jumlah_standar' => 40,
                'satuan_produk' => 'pcs / 1 kg',
                'susu_liter' => 5,
                'gula_gram' => 225,
                'perasa_jenis' => 'Karamel',
                'perasa_gram' => 20,
                'status' => 'aktif',
            ],
        ];
    }

    public function index()
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data'));
        session()->put('karyawan_data', $karyawans);

        $activeKaryawans = array_values(array_filter($karyawans, function ($karyawan) {
            return strtolower($karyawan['status']) === 'aktif';
        }));

        $aktivitas = session('aktivitas_karyawan_data', []);
        $produkResep = array_values(array_filter(self::getProdukResepData(), function ($produk) {
            return strtolower($produk['status']) === 'aktif';
        }));
        $lastAktivitas = $aktivitas[0] ?? null;
        $latestAktivitasByKaryawan = [];

        foreach ($aktivitas as $row) {
            if (!isset($latestAktivitasByKaryawan[$row['karyawan_id']])) {
                $latestAktivitasByKaryawan[$row['karyawan_id']] = $row;
            }
        }

        return view('admin_penggajian.aktivitas_karyawan', compact('activeKaryawans', 'produkResep', 'lastAktivitas', 'latestAktivitasByKaryawan'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal' => $this->normalizeTanggal($request->input('tanggal')),
        ]);

        $data = $request->validate([
            'karyawan_id' => 'required',
            'tanggal' => 'required|date',
            'id_produk' => 'required|string|max:4',
            'jumlah_produksi' => 'required|numeric|min:0',
        ]);

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data', KaryawanController::getInitialData()));
        $karyawan = collect($karyawans)->firstWhere('id', (int) $data['karyawan_id']);

        if (!$karyawan) {
            return redirect()->route('aktivitas_karyawan.index')->with('error', 'Karyawan tidak ditemukan.');
        }

        $produk = collect(self::getProdukResepData())->firstWhere('id_produk', $data['id_produk']);

        if (!$produk) {
            return redirect()->route('aktivitas_karyawan.index')->with('error', 'Produk tidak ditemukan.');
        }

        $aktivitas = session('aktivitas_karyawan_data', []);
        array_unshift($aktivitas, [
            'id' => count($aktivitas) + 1,
            'tanggal' => $data['tanggal'],
            'karyawan_id' => $karyawan['id'],
            'nama' => $karyawan['nama'],
            'nik' => $karyawan['nik'],
            'bagian' => $karyawan['bagian'] ?? '-',
            'id_produk' => $produk['id_produk'],
            'nama_produk' => $produk['nama_produk'],
            'jumlah_produksi' => (float) $data['jumlah_produksi'],
        ]);

        session()->put('aktivitas_karyawan_data', $aktivitas);

        return redirect()->route('aktivitas_karyawan.index')->with('success', 'Aktivitas karyawan berhasil dicatat.');
    }

    private function normalizeTanggal(?string $tanggal): ?string
    {
        $tanggal = trim((string) $tanggal);

        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $tanggal, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }

        return $tanggal ?: null;
    }
}
