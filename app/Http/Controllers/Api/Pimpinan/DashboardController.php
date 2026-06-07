<?php

namespace App\Http\Controllers\Api\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock data for Pimpinan Dashboard
        $stats = [
            'total_karyawan' => 25,
            'hadir_hari_ini' => 23,
            'izin_sakit' => 2,
            'karyawan_inaktif' => 1,
        ];

        return view('pimpinan.dashboard.dashboard', compact('stats'));
    }
}
