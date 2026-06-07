<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GajiLemburController extends Controller
{
    public function index(Request $request)
    {
        // Mock data for Gaji Lembur rules
        $gajiLembur = [
            ['id' => 1, 'nama' => 'Gaji Lembur per Jam', 'tarif' => 10000.00, 'keterangan' => 'Tarif standar lembur per jam', 'status' => 'Aktif'],
        ];

        return view('admin_penggajian.gaji_lembur.gaji_lembur', compact('gajiLembur'));
    }
}
