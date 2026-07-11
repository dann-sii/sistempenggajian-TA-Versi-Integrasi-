<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GajiPokokController extends Controller
{
    public static function getGajiPokokData(): array
    {
        $defaultData = [
            ['id' => 1, 'nama' => 'Gaji Pokok per Jam', 'tarif' => 15000.00, 'status' => 'Aktif', 'is_default' => true, 'is_used' => true],
            ['id' => 2, 'nama' => 'Gaji Pokok per Hari', 'tarif' => 75000.00, 'status' => 'Aktif', 'is_default' => true, 'is_used' => true],
        ];

        $statusOverrides = session('gaji_pokok_status', []);
        $dataOverrides = session('gaji_pokok_overrides', []);
        $defaultData = array_map(function($item) use ($statusOverrides, $dataOverrides) {
            if (isset($statusOverrides[$item['id']])) {
                $item['status'] = $statusOverrides[$item['id']];
            }
            if (isset($dataOverrides[$item['id']])) {
                $item['nama'] = $dataOverrides[$item['id']]['nama'];
                $item['tarif'] = $dataOverrides[$item['id']]['tarif'];
            }
            return $item;
        }, $defaultData);

        $extraData = array_map(function($item) {
            $item['is_default'] = false;
            $item['is_used'] = false; // Mock data is_used
            return $item;
        }, session('gaji_pokok_extra', []));

        return array_merge($defaultData, $extraData);
    }

    public static function getGajiLemburData(): array
    {
        $defaultData = [
            ['id' => 1, 'nama' => 'Gaji Lembur per Jam', 'tarif' => 10000.00, 'keterangan' => 'Tarif standar lembur per jam', 'status' => 'Aktif', 'is_default' => true, 'is_used' => true],
        ];

        $statusOverrides = session('gaji_lembur_status', []);
        $dataOverrides = session('gaji_lembur_overrides', []);
        $defaultData = array_map(function($item) use ($statusOverrides, $dataOverrides) {
            if (isset($statusOverrides[$item['id']])) {
                $item['status'] = $statusOverrides[$item['id']];
            }
            if (isset($dataOverrides[$item['id']])) {
                $item['nama'] = $dataOverrides[$item['id']]['nama'];
                $item['tarif'] = $dataOverrides[$item['id']]['tarif'];
                $item['keterangan'] = $dataOverrides[$item['id']]['keterangan'];
            }
            return $item;
        }, $defaultData);

        $extraData = array_map(function($item) {
            $item['is_default'] = false;
            $item['is_used'] = false; // Mock data is_used
            return $item;
        }, session('gaji_lembur_extra', []));

        return array_merge($defaultData, $extraData);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $allGajiPokok = self::getGajiPokokData();
        $gajiLembur = self::getGajiLemburData();

        if ($search) {
            $allGajiPokok = array_filter($allGajiPokok, function ($g) use ($search) {
                return stripos($g['nama'], $search) !== false;
            });
            $gajiLembur = array_filter($gajiLembur, function ($g) use ($search) {
                return stripos($g['nama'], $search) !== false;
            });
        }

        $allGajiPokok = array_values($allGajiPokok);
        $gajiLembur = array_values($gajiLembur);

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $gajiPokok = array_slice($allGajiPokok, ($page - 1) * $perPage, $perPage);

        return view('admin_penggajian.gaji_pokok', compact('gajiPokok', 'gajiLembur', 'page', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_tarif' => 'required|in:gaji_pokok,gaji_lembur',
            'nama' => 'required|string|max:100',
            'tarif' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
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

    public function toggleStatusPokok($id)
    {
        $allData = self::getGajiPokokData();
        $target = collect($allData)->firstWhere('id', (int) $id);

        if (!$target) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data gaji pokok tidak ditemukan.');
        }

        $newStatus = strtolower($target['status']) === 'aktif' ? 'Inaktif' : 'Aktif';

        if (isset($target['is_default']) && $target['is_default']) {
            $overrides = session('gaji_pokok_status', []);
            $overrides[$target['id']] = $newStatus;
            session(['gaji_pokok_status' => $overrides]);
        } else {
            $extraData = session('gaji_pokok_extra', []);
            foreach ($extraData as &$item) {
                if ($item['id'] === (int) $id) {
                    $item['status'] = $newStatus;
                    break;
                }
            }
            session(['gaji_pokok_extra' => $extraData]);
        }

        return redirect()->route('gaji_pokok.index')->with('success', 'Status gaji pokok berhasil diubah menjadi ' . $newStatus . '.');
    }

    public function toggleStatusLembur($id)
    {
        $allData = self::getGajiLemburData();
        $target = collect($allData)->firstWhere('id', (int) $id);

        if (!$target) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data gaji lembur tidak ditemukan.');
        }

        $newStatus = strtolower($target['status']) === 'aktif' ? 'Inaktif' : 'Aktif';

        if (isset($target['is_default']) && $target['is_default']) {
            $overrides = session('gaji_lembur_status', []);
            $overrides[$target['id']] = $newStatus;
            session(['gaji_lembur_status' => $overrides]);
        } else {
            $extraData = session('gaji_lembur_extra', []);
            foreach ($extraData as &$item) {
                if ($item['id'] === (int) $id) {
                    $item['status'] = $newStatus;
                    break;
                }
            }
            session(['gaji_lembur_extra' => $extraData]);
        }

        return redirect()->route('gaji_pokok.index')->with('success', 'Status gaji lembur berhasil diubah menjadi ' . $newStatus . '.');
    }

    public function updatePokok(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tarif' => 'required|numeric|min:0',
        ]);

        $allData = self::getGajiPokokData();
        $target = collect($allData)->firstWhere('id', (int) $id);

        if (!$target) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data gaji pokok tidak ditemukan.');
        }

        if (isset($target['is_default']) && $target['is_default']) {
            // For default data, we only allow updating tarif (using another override session array)
            // But since this is a prototype, let's store it in a generic override
            $overrides = session('gaji_pokok_overrides', []);
            $overrides[$target['id']] = [
                'nama' => $validated['nama'],
                'tarif' => (float) $validated['tarif'],
            ];
            session(['gaji_pokok_overrides' => $overrides]);
        } else {
            // Extra data
            $extraData = session('gaji_pokok_extra', []);
            foreach ($extraData as &$item) {
                if ($item['id'] === (int) $id) {
                    $item['nama'] = $validated['nama'];
                    $item['tarif'] = (float) $validated['tarif'];
                    break;
                }
            }
            session(['gaji_pokok_extra' => $extraData]);
        }

        return redirect()->route('gaji_pokok.index')->with('success', 'Data gaji pokok berhasil diperbarui.');
    }

    public function updateLembur(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tarif' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $allData = self::getGajiLemburData();
        $target = collect($allData)->firstWhere('id', (int) $id);

        if (!$target) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data gaji lembur tidak ditemukan.');
        }

        if (isset($target['is_default']) && $target['is_default']) {
            $overrides = session('gaji_lembur_overrides', []);
            $overrides[$target['id']] = [
                'nama' => $validated['nama'],
                'tarif' => (float) $validated['tarif'],
                'keterangan' => $validated['keterangan'] ?: '-',
            ];
            session(['gaji_lembur_overrides' => $overrides]);
        } else {
            $extraData = session('gaji_lembur_extra', []);
            foreach ($extraData as &$item) {
                if ($item['id'] === (int) $id) {
                    $item['nama'] = $validated['nama'];
                    $item['tarif'] = (float) $validated['tarif'];
                    $item['keterangan'] = $validated['keterangan'] ?: '-';
                    break;
                }
            }
            session(['gaji_lembur_extra' => $extraData]);
        }

        return redirect()->route('gaji_pokok.index')->with('success', 'Data gaji lembur berhasil diperbarui.');
    }

    public function destroyPokok($id)
    {
        $allData = self::getGajiPokokData();
        $target = collect($allData)->firstWhere('id', (int) $id);

        if (!$target) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data gaji pokok tidak ditemukan.');
        }

        if (isset($target['is_default']) && $target['is_default']) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data bawaan sistem tidak dapat dihapus.');
        }

        $extraData = session('gaji_pokok_extra', []);
        $extraData = array_filter($extraData, function ($item) use ($id) {
            return $item['id'] !== (int) $id;
        });

        session(['gaji_pokok_extra' => array_values($extraData)]);
        return redirect()->route('gaji_pokok.index')->with('success', 'Data gaji pokok berhasil dihapus.');
    }

    public function destroyLembur($id)
    {
        $allData = self::getGajiLemburData();
        $target = collect($allData)->firstWhere('id', (int) $id);

        if (!$target) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data gaji lembur tidak ditemukan.');
        }

        if (isset($target['is_default']) && $target['is_default']) {
            return redirect()->route('gaji_pokok.index')->with('error', 'Data bawaan sistem tidak dapat dihapus.');
        }

        $extraData = session('gaji_lembur_extra', []);
        $extraData = array_filter($extraData, function ($item) use ($id) {
            return $item['id'] !== (int) $id;
        });

        session(['gaji_lembur_extra' => array_values($extraData)]);
        return redirect()->route('gaji_pokok.index')->with('success', 'Data gaji lembur berhasil dihapus.');
    }
}
