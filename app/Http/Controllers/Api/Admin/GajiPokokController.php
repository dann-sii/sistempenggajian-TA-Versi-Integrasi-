<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GajiPokokController extends Controller
{
    public static function getGajiPokokData(): array
    {
        $defaultData = [
            ['id' => 1, 'nama' => 'Gaji Pokok Produksi per Jam', 'tarif' => 15000.00, 'status' => 'Aktif'],
            ['id' => 2, 'nama' => 'Gaji Pokok Peternakan per Hari', 'tarif' => 75000.00, 'status' => 'Aktif'],
        ];

        return array_merge($defaultData, session('gaji_pokok_extra', []));
    }

    public static function getGajiLemburData(): array
    {
        $defaultData = [
            ['id' => 1, 'nama' => 'Gaji Lembur per Jam', 'tarif' => 10000.00, 'keterangan' => 'Tarif standar lembur per jam', 'status' => 'Aktif'],
        ];

        return array_merge($defaultData, session('gaji_lembur_extra', []));
    }

    public function index(Request $request)
    {
        // Mock data for Gaji Pokok rules
        $allGajiPokok = self::getGajiPokokData();
        $gajiLembur = self::getGajiLemburData();

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $gajiPokok = array_slice($allGajiPokok, ($page - 1) * $perPage, $perPage);

        return view('admin_penggajian.gaji_pokok', compact('gajiPokok', 'gajiLembur', 'page'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_tarif' => 'required|in:gaji_pokok,gaji_lembur',
            'nama' => 'required|string|max:100',
            'tarif' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:150',
            'status' => 'required|in:Aktif,Inaktif',
        ]);

        if ($validated['jenis_tarif'] === 'gaji_pokok') {
            $gajiPokok = self::getGajiPokokData();
            $extraData = session('gaji_pokok_extra', []);

            $extraData[] = [
                'id' => count($gajiPokok) + 1,
                'nama' => $validated['nama'],
                'tarif' => (float) $validated['tarif'],
                'status' => $validated['status'],
            ];

            session(['gaji_pokok_extra' => $extraData]);
        } else {
            $gajiLembur = self::getGajiLemburData();
            $extraData = session('gaji_lembur_extra', []);

            $extraData[] = [
                'id' => count($gajiLembur) + 1,
                'nama' => $validated['nama'],
                'tarif' => (float) $validated['tarif'],
                'keterangan' => $validated['keterangan'] ?: '-',
                'status' => $validated['status'],
            ];

            session(['gaji_lembur_extra' => $extraData]);
        }

        return redirect()
            ->route('gaji_pokok.index')
            ->with('success', 'Tarif gaji berhasil ditambahkan.');
    }

}
