<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GajiBonusController extends Controller
{
    public static function getBonusData(): array
    {
        return [
            ['id' => 1, 'batas_normal' => 5, 'satuan' => 'Liter', 'nama' => 'Bonus Penggunaan Susu', 'tarif' => 2500.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih produksi susu berhasil di atas 5 liter per hari'],
            ['id' => 2, 'batas_normal' => 250, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Gula', 'tarif' => 100.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih pemakaian gula di atas batas normal per hari'],
            ['id' => 3, 'batas_normal' => 20, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Jahe', 'tarif' => 150.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa jahe di atas batas normal per hari'],
            ['id' => 4, 'batas_normal' => 25, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Coklat', 'tarif' => 175.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa coklat di atas batas normal per hari'],
            ['id' => 5, 'batas_normal' => 15, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Vanila', 'tarif' => 200.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa vanila di atas batas normal per hari'],
            ['id' => 6, 'batas_normal' => 20, 'satuan' => 'Gram', 'nama' => 'Bonus Penggunaan Perasa Karamel', 'tarif' => 180.00, 'status' => 'Aktif', 'keterangan' => 'Dihitung dari selisih perasa karamel di atas batas normal per hari'],
            ['id' => 7, 'batas_normal' => 1, 'satuan' => 'Kali', 'nama' => 'Bonus THR', 'tarif' => 1500000.00, 'status' => 'Aktif', 'keterangan' => 'Bonus hari raya yang diberikan satu kali pada periode hari raya jika ditetapkan admin'],
            ['id' => 8, 'batas_normal' => 5, 'satuan' => 'Liter', 'nama' => 'Bonus Produksi Susu Peternakan', 'tarif' => 5000.00, 'status' => 'Aktif', 'keterangan' => 'Diberikan untuk karyawan peternakan dari selisih produksi susu harian di atas 5 liter'],
        ];
    }

    public function index(Request $request)
    {
        $gajiBonus = self::getBonusData();

        return view('admin_penggajian.gaji_bonus', compact('gajiBonus'));
    }
}
