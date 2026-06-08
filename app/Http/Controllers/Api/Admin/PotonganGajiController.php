<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PotonganGajiController extends Controller
{
    public static function getPotonganData(): array
    {
        return [
            ['id' => 1, 'nama' => 'Potongan BPJS Kesehatan', 'tarif' => 50000.00, 'tipe_potongan' => 'Tetap', 'status' => 'Aktif'],
            ['id' => 2, 'nama' => 'Potongan BPJS Ketenagakerjaan', 'tarif' => 30000.00, 'tipe_potongan' => 'Tetap', 'status' => 'Aktif'],
            ['id' => 3, 'nama' => 'Denda Keterlambatan', 'tarif' => 15000.00, 'tipe_potongan' => 'Kondisional', 'status' => 'Aktif'],
        ];
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
}
