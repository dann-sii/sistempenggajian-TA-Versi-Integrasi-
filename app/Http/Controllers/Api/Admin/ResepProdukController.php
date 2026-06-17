<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class ResepProdukController extends Controller
{
    public function index()
    {
        $produkResep = AktivitasKaryawanController::getProdukResepData();

        return view('admin_penggajian.resep_produk', compact('produkResep'));
    }
}
