<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $produkResep = AktivitasKaryawanController::getProdukResepData();

        // Merge with session data
        $sessionResep = session('resep_tambahan', []);
        $produkResep = array_merge($produkResep, $sessionResep);

        // Check if product is used in aktivitas
        $aktivitas = session('aktivitas_karyawan_data', []);
        $usedProductIds = array_unique(array_column($aktivitas, 'id_produk'));
        $overrides = session('resep_status_override', []);

        foreach ($produkResep as &$produk) {
            $produk['is_used'] = in_array($produk['id_produk'], $usedProductIds);
            
            // Apply status override if exists
            if (isset($overrides[$produk['id_produk']])) {
                $produk['status'] = $overrides[$produk['id_produk']];
            }
        }

        if ($search) {
            $produkResep = array_filter($produkResep, function ($produk) use ($search) {
                return stripos($produk['nama_produk'], $search) !== false;
            });
        }

        return view('admin_penggajian.data_produk', compact('produkResep', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'target_produksi' => 'required|numeric|min:0',
            'susu_liter' => 'required|numeric|min:0',
            'gula_gram' => 'required|numeric|min:0',
            'perasa_jenis' => 'nullable|string|max:255',
            'perasa_gram' => 'nullable|numeric|min:0',
        ]);

        $baseDataCount = count(AktivitasKaryawanController::getProdukResepData());
        $sessionDataCount = count(session('resep_tambahan', []));
        $newId = 'P' . str_pad($baseDataCount + $sessionDataCount + 1, 3, '0', STR_PAD_LEFT);

        $newData = [
            'id_produk' => $newId,
            'nama_produk' => $request->nama_produk,
            'target_produksi' => $request->target_produksi,
            'satuan_produk' => 'Gram',
            'susu_liter' => $request->susu_liter,
            'gula_gram' => $request->gula_gram,
            'perasa_jenis' => $request->perasa_jenis ?: '-',
            'perasa_gram' => $request->perasa_gram ?: 0,
            'status' => 'aktif',
        ];

        session()->push('resep_tambahan', $newData);

        return redirect()->route('data_produk.index')->with('success', 'Data produk berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $sessionResep = session('resep_tambahan', []);
        
        $sessionResep = array_filter($sessionResep, function ($produk) use ($id) {
            return $produk['id_produk'] !== $id;
        });

        session(['resep_tambahan' => array_values($sessionResep)]);

        return redirect()->route('data_produk.index')->with('success', 'Data produk berhasil dihapus secara permanen.');
    }

    public function toggleStatus($id)
    {
        $sessionResep = session('resep_tambahan', []);
        $foundInSession = false;

        // Cek di resep tambahan (baru)
        foreach ($sessionResep as &$produk) {
            if ($produk['id_produk'] === $id) {
                $produk['status'] = (strtolower($produk['status']) === 'aktif') ? 'inaktif' : 'aktif';
                $foundInSession = true;
                break;
            }
        }

        if ($foundInSession) {
            session(['resep_tambahan' => $sessionResep]);
        } else {
            // Cek di resep bawaan (mockup statis) menggunakan override session
            $overrides = session('resep_status_override', []);
            
            // Cek status saat ini, prioritas override
            $currentStatus = 'aktif';
            if (isset($overrides[$id])) {
                $currentStatus = strtolower($overrides[$id]);
            } else {
                $baseData = collect(AktivitasKaryawanController::getProdukResepData())->firstWhere('id_produk', $id);
                if ($baseData) {
                    $currentStatus = strtolower($baseData['status']);
                }
            }
            
            $newStatus = ($currentStatus === 'aktif') ? 'inaktif' : 'aktif';
            $overrides[$id] = $newStatus;
            
            session(['resep_status_override' => $overrides]);
        }

        return redirect()->route('data_produk.index')->with('success', 'Status data produk berhasil diperbarui.');
    }
}
