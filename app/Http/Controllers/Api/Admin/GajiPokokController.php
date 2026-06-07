<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GajiPokokController extends Controller
{
    public function index(Request $request)
    {
        // Mock data for Gaji Pokok rules
        $allGajiPokok = [
            ['id' => 1, 'nama' => 'Gaji Pokok per Jam', 'tarif' => 15000.00, 'status' => 'Aktif'],
        ];

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $gajiPokok = array_slice($allGajiPokok, ($page - 1) * $perPage, $perPage);

        return view('admin_penggajian.gaji_pokok.gaji_pokok', compact('gajiPokok', 'page'));
    }
}
