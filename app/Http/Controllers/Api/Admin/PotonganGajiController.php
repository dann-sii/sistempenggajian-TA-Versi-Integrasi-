<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PotonganGajiController extends Controller
{
    public static function getPotonganData(): array
    {
        // Use Session to persist additions/modifications/deletions during testing
        if (Session::has('potongan_gaji_data')) {
            return Session::get('potongan_gaji_data');
        }

        $defaultData = [
            ['id' => 1, 'nama' => 'Potongan BPJS Kesehatan', 'tarif' => 1.00, 'tipe_potongan' => 'Persentase', 'status' => 'Aktif', 'is_default' => true, 'is_used' => true],
            ['id' => 2, 'nama' => 'Potongan BPJS Ketenagakerjaan', 'tarif' => 3.00, 'tipe_potongan' => 'Persentase', 'status' => 'Aktif', 'is_default' => true, 'is_used' => true],
            ['id' => 3, 'nama' => 'Denda Keterlambatan', 'tarif' => 15000.00, 'tipe_potongan' => 'Kondisional', 'status' => 'Aktif', 'is_default' => true, 'is_used' => true],
        ];

        Session::put('potongan_gaji_data', $defaultData);
        return $defaultData;
    }

    public function index(Request $request)
    {
        $allPotongan = self::getPotonganData();

        $search = $request->query('search');
        if ($search) {
            $allPotongan = array_values(array_filter($allPotongan, function($p) use ($search) {
                return stripos((string) $p['nama'], $search) !== false;
            }));
        }

        return view('admin_penggajian.potongan_gaji', compact('allPotongan', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tarif' => 'required|numeric|min:0',
            'tipe_potongan' => 'required|string|in:Tetap,Kondisional,Persentase',
        ]);

        $data = self::getPotonganData();
        
        $newId = 101;
        if (!empty($data)) {
            $maxId = max(array_column($data, 'id'));
            if ($maxId >= 100) {
                $newId = $maxId + 1;
            }
        }

        $newItem = [
            'id' => $newId,
            'nama' => $request->nama,
            'tarif' => (float) $request->tarif,
            'tipe_potongan' => $request->tipe_potongan,
            'status' => 'Aktif',
            'is_default' => false,
            'is_used' => false,
        ];

        $data[] = $newItem;
        Session::put('potongan_gaji_data', $data);

        return redirect()->route('potongan_gaji.index')
            ->with('success', 'Data potongan gaji berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = self::getPotonganData();
        $index = array_search((int)$id, array_column($data, 'id'));

        if ($index === false) {
            return redirect()->route('potongan_gaji.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        // Only allow changing name and type if not default
        if (isset($data[$index]['is_default']) && $data[$index]['is_default']) {
            $request->validate([
                'tarif' => 'required|numeric|min:0',
            ]);
            $data[$index]['tarif'] = (float) $request->tarif;
        } else {
            $request->validate([
                'nama' => 'required|string|max:255',
                'tarif' => 'required|numeric|min:0',
                'tipe_potongan' => 'required|string|in:Tetap,Kondisional,Persentase',
            ]);
            $data[$index]['nama'] = $request->nama;
            $data[$index]['tarif'] = (float) $request->tarif;
            $data[$index]['tipe_potongan'] = $request->tipe_potongan;
        }

        Session::put('potongan_gaji_data', $data);

        return redirect()->route('potongan_gaji.index')
            ->with('success', 'Data potongan gaji berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $data = self::getPotonganData();
        $index = array_search((int)$id, array_column($data, 'id'));

        if ($index === false) {
            return redirect()->route('potongan_gaji.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        $currentStatus = strtolower($data[$index]['status']);
        $data[$index]['status'] = $currentStatus === 'aktif' ? 'Inaktif' : 'Aktif';

        Session::put('potongan_gaji_data', $data);

        return redirect()->route('potongan_gaji.index')
            ->with('success', 'Status potongan gaji berhasil diubah.');
    }

    public function destroy($id)
    {
        $data = self::getPotonganData();
        $index = array_search((int)$id, array_column($data, 'id'));

        if ($index === false) {
            return redirect()->route('potongan_gaji.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        if (isset($data[$index]['is_default']) && $data[$index]['is_default']) {
            return redirect()->route('potongan_gaji.index')
                ->with('error', 'Data bawaan sistem tidak dapat dihapus.');
        }

        array_splice($data, $index, 1);
        Session::put('potongan_gaji_data', $data);

        return redirect()->route('potongan_gaji.index')
            ->with('success', 'Data potongan gaji berhasil dihapus.');
    }
}
