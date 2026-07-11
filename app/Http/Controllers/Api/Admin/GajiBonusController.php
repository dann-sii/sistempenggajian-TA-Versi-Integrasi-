<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GajiBonusController extends Controller
{
    public static function getBonusData(): array
    {
        return [
            ['id' => 1, 'batas_normal' => 10, 'satuan' => 'Liter', 'nama' => 'Bonus Penggunaan Susu', 'tarif' => 2500.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih produksi susu berhasil di atas target resep (10 liter) per hari', 'is_default' => true],
            ['id' => 2, 'batas_normal' => 250, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Gula', 'tarif' => 100.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih pemakaian gula di atas target resep produk per hari', 'is_default' => true],
            ['id' => 3, 'batas_normal' => 20, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Jahe', 'tarif' => 150.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa jahe di atas target resep produk per hari', 'is_default' => true],
            ['id' => 4, 'batas_normal' => 25, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Coklat', 'tarif' => 175.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa coklat di atas target resep produk per hari', 'is_default' => true],
            ['id' => 5, 'batas_normal' => 15, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Vanila', 'tarif' => 200.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa vanila di atas target resep produk per hari', 'is_default' => true],
            ['id' => 6, 'batas_normal' => 20, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Karamel', 'tarif' => 180.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa karamel di atas target resep produk per hari', 'is_default' => true],
            ['id' => 7, 'batas_normal' => 1, 'satuan' => 'Kali', 'nama' => 'Bonus THR', 'tarif' => 1500000.00, 'status' => 'Aktif', 'keterangan' => 'Bonus hari raya yang diberikan satu kali pada periode hari raya jika ditetapkan admin', 'is_default' => true],
            ['id' => 8, 'batas_normal' => 5, 'satuan' => 'Liter', 'nama' => 'Bonus Produksi Susu Peternakan', 'tarif' => 5000.00, 'status' => 'Aktif', 'keterangan' => 'Diberikan untuk karyawan peternakan dari selisih produksi susu harian di atas target piket (5 liter)', 'is_default' => true],
            ['id' => 9, 'batas_normal' => 3000, 'satuan' => 'Gram', 'nama' => 'Bonus Produktivitas Packing', 'tarif' => 10.00, 'status' => 'Aktif', 'keterangan' => 'Diberikan untuk karyawan packing dari selisih berat kemasan harian di atas target piket (3.000 gram)', 'is_default' => true],
        ];
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $baseGajiBonus = self::getBonusData();
        
        // Merge session overrides for default data
        $overrides = Session::get('gaji_bonus_overrides', []);
        foreach ($baseGajiBonus as &$gb) {
            if (isset($overrides[$gb['id']])) {
                $gb['tarif'] = $overrides[$gb['id']]['tarif'] ?? $gb['tarif'];
                $gb['status'] = $overrides[$gb['id']]['status'] ?? $gb['status'];
                $gb['keterangan'] = $overrides[$gb['id']]['keterangan'] ?? $gb['keterangan'];
                if (isset($overrides[$gb['id']]['batas_normal'])) $gb['batas_normal'] = $overrides[$gb['id']]['batas_normal'];
                if (isset($overrides[$gb['id']]['nama'])) $gb['nama'] = $overrides[$gb['id']]['nama'];
                if (isset($overrides[$gb['id']]['satuan'])) $gb['satuan'] = $overrides[$gb['id']]['satuan'];
            }
        }
        unset($gb);

        // Merge custom data
        $customGajiBonus = Session::get('gaji_bonus_custom', []);
        $gajiBonus = array_merge($baseGajiBonus, $customGajiBonus);

        if ($search) {
            $gajiBonus = array_filter($gajiBonus, function ($g) use ($search) {
                return stripos($g['nama'], $search) !== false;
            });
        }

        return view('admin_penggajian.gaji_bonus', compact('gajiBonus', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'batas_normal' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'tarif' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $customGajiBonus = Session::get('gaji_bonus_custom', []);
        
        // Generate new ID (starting from 101 to avoid clash with defaults)
        $newId = 101;
        if (count($customGajiBonus) > 0) {
            $ids = array_column($customGajiBonus, 'id');
            $newId = max($ids) + 1;
        }

        $customGajiBonus[] = [
            'id' => $newId,
            'nama' => $request->nama,
            'batas_normal' => $request->batas_normal,
            'satuan' => $request->satuan,
            'tarif' => $request->tarif,
            'keterangan' => $request->keterangan ?? '-',
            'status' => 'Aktif', // Default status for new custom bonus is Aktif
            'is_default' => false,
        ];

        Session::put('gaji_bonus_custom', $customGajiBonus);

        return redirect()->route('gaji_bonus.index')->with('success', 'Data gaji bonus berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tarif' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $customGajiBonus = Session::get('gaji_bonus_custom', []);
        $isCustom = false;
        
        foreach ($customGajiBonus as &$gb) {
            if ($gb['id'] == $id) {
                $gb['tarif'] = $request->tarif;
                $gb['keterangan'] = $request->keterangan ?? '-';
                if ($request->has('nama')) $gb['nama'] = $request->nama;
                if ($request->has('batas_normal')) $gb['batas_normal'] = $request->batas_normal;
                if ($request->has('satuan')) $gb['satuan'] = $request->satuan;
                $isCustom = true;
                break;
            }
        }

        if ($isCustom) {
            Session::put('gaji_bonus_custom', $customGajiBonus);
        } else {
            $overrides = Session::get('gaji_bonus_overrides', []);
            $overrides[$id] = $overrides[$id] ?? [];
            $overrides[$id]['tarif'] = $request->tarif;
            $overrides[$id]['keterangan'] = $request->keterangan ?? '-';
            if ($request->has('batas_normal')) $overrides[$id]['batas_normal'] = $request->batas_normal;
            Session::put('gaji_bonus_overrides', $overrides);
        }

        return redirect()->route('gaji_bonus.index')->with('success', 'Data gaji bonus berhasil diubah.');
    }

    public function toggleStatus($id)
    {
        $customGajiBonus = Session::get('gaji_bonus_custom', []);
        $isCustom = false;
        $newStatus = 'Aktif';

        foreach ($customGajiBonus as &$gb) {
            if ($gb['id'] == $id) {
                $gb['status'] = (strtolower($gb['status']) === 'aktif') ? 'Inaktif' : 'Aktif';
                $newStatus = $gb['status'];
                $isCustom = true;
                break;
            }
        }

        if ($isCustom) {
            Session::put('gaji_bonus_custom', $customGajiBonus);
        } else {
            $baseGajiBonus = self::getBonusData();
            $defaultStatus = 'Aktif';
            foreach ($baseGajiBonus as $bg) {
                if ($bg['id'] == $id) {
                    $defaultStatus = $bg['status'];
                    break;
                }
            }

            $overrides = Session::get('gaji_bonus_overrides', []);
            $currentStatus = $overrides[$id]['status'] ?? $defaultStatus;
            $newStatus = (strtolower($currentStatus) === 'aktif') ? 'Inaktif' : 'Aktif';
            
            $overrides[$id] = $overrides[$id] ?? [];
            $overrides[$id]['status'] = $newStatus;
            Session::put('gaji_bonus_overrides', $overrides);
        }

        return redirect()->route('gaji_bonus.index')->with('success', "Status gaji bonus berhasil diubah menjadi {$newStatus}.");
    }

    public function destroy($id)
    {
        $customGajiBonus = Session::get('gaji_bonus_custom', []);
        $initialCount = count($customGajiBonus);
        
        $customGajiBonus = array_filter($customGajiBonus, function ($gb) use ($id) {
            return $gb['id'] != $id;
        });

        if (count($customGajiBonus) < $initialCount) {
            Session::put('gaji_bonus_custom', array_values($customGajiBonus));
            return redirect()->route('gaji_bonus.index')->with('success', 'Data gaji bonus berhasil dihapus.');
        }

        return redirect()->route('gaji_bonus.index')->with('error', 'Data bawaan sistem tidak dapat dihapus.');
    }
}
