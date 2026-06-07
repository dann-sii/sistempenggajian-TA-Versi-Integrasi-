<?php

namespace App\Http\Controllers\Api\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    private function getInitialData() 
    {
        return [
            ['id' => 1, 'nik' => '1234567890', 'nama' => 'Budi Santoso', 'npwp' => '12.345.678.9-000.000', 'alamat' => 'Jl. Merdeka No. 10, Yogyakarta', 'no_telp' => '08123456789', 'status' => 'Aktif'],
            ['id' => 2, 'nik' => '0987654321', 'nama' => 'Siti Aminah', 'npwp' => '98.765.432.1-000.000', 'alamat' => 'Jl. Mawar No. 5, Yogyakarta', 'no_telp' => '08129876543', 'status' => 'Inaktif'],
            ['id' => 3, 'nik' => '1122334455', 'nama' => 'Andi Wijaya', 'npwp' => '11.223.344.5-000.000', 'alamat' => 'Jl. Melati No. 2, Yogyakarta', 'no_telp' => '08131122334', 'status' => 'Inaktif'],
            ['id' => 4, 'nik' => '2233445566', 'nama' => 'Dewi Lestari', 'npwp' => '22.334.455.6-000.000', 'alamat' => 'Jl. Anggrek No. 15, Yogyakarta', 'no_telp' => '08122233445', 'status' => 'Aktif'],
            ['id' => 5, 'nik' => '3344556677', 'nama' => 'Rizky Pratama', 'npwp' => '33.445.566.7-000.000', 'alamat' => 'Jl. Kenanga No. 8, Magelang', 'no_telp' => '08133344556', 'status' => 'Aktif'],
            ['id' => 6, 'nik' => '4455667788', 'nama' => 'Anita Sari', 'npwp' => '44.556.677.8-000.000', 'alamat' => 'Jl. Cempaka No. 12, Yogyakarta', 'no_telp' => '08144455667', 'status' => 'Inaktif'],
            ['id' => 7, 'nik' => '5566778899', 'nama' => 'Bambang Subianto', 'npwp' => '55.667.788.9-000.000', 'alamat' => 'Jl. Teratai No. 20, Magelang', 'no_telp' => '08155566778', 'status' => 'Aktif'],
            ['id' => 8, 'nik' => '6677889900', 'nama' => 'Eka Putri', 'npwp' => '66.778.899.0-000.000', 'alamat' => 'Jl. Kamboja No. 3, Yogyakarta', 'no_telp' => '08166677889', 'status' => 'Aktif'],
            ['id' => 9, 'nik' => '7788990011', 'nama' => 'Faisal Rahman', 'npwp' => '77.889.900.1-000.000', 'alamat' => 'Jl. Melur No. 7, Yogyakarta', 'no_telp' => '08177788990', 'status' => 'Inaktif'],
            ['id' => 10, 'nik' => '8899001122', 'nama' => 'Gita Permata', 'npwp' => '88.990.011.2-000.000', 'alamat' => 'Jl. Dahlia No. 11, Yogyakarta', 'no_telp' => '08188899001', 'status' => 'Aktif'],
            ['id' => 11, 'nik' => '1100223344', 'nama' => 'Hendra Wijaya', 'npwp' => '11.002.233.4-000.000', 'alamat' => 'Jl. Garuda No. 1, Magelang', 'no_telp' => '08111002233', 'status' => 'Aktif'],
            ['id' => 12, 'nik' => '2211334455', 'nama' => 'Indah Kusuma', 'npwp' => '22.113.344.5-000.000', 'alamat' => 'Jl. Merak No. 4, Yogyakarta', 'no_telp' => '08122113344', 'status' => 'Inaktif'],
            ['id' => 13, 'nik' => '3322445566', 'nama' => 'Joko Susilo', 'npwp' => '33.224.455.6-000.000', 'alamat' => 'Jl. Elang No. 6, Yogyakarta', 'no_telp' => '08133224455', 'status' => 'Aktif'],
            ['id' => 14, 'nik' => '4433556677', 'nama' => 'Kartini Putri', 'npwp' => '44.335.566.7-000.000', 'alamat' => 'Jl. Rajawali No. 9, Yogyakarta', 'no_telp' => '08144335566', 'status' => 'Aktif'],
            ['id' => 15, 'nik' => '5544667788', 'nama' => 'Lukman Hakim', 'npwp' => '55.446.677.8-000.000', 'alamat' => 'Jl. Kancil No. 2, Yogyakarta', 'no_telp' => '08155446677', 'status' => 'Inaktif'],
            ['id' => 16, 'nik' => '6655778899', 'nama' => 'Maya Sari', 'npwp' => '66.557.788.9-000.000', 'alamat' => 'Jl. Rusa No. 5, Yogyakarta', 'no_telp' => '08166557788', 'status' => 'Aktif'],
            ['id' => 17, 'nik' => '7766889900', 'nama' => 'Nugroho Saputra', 'npwp' => '77.668.899.0-000.000', 'alamat' => 'Jl. Gajah No. 8, Yogyakarta', 'no_telp' => '08177668899', 'status' => 'Aktif'],
            ['id' => 18, 'nik' => '8877990011', 'nama' => 'Oki Setiana', 'npwp' => '88.779.900.1-000.000', 'alamat' => 'Jl. Harimau No. 10, Yogyakarta', 'no_telp' => '08188779900', 'status' => 'Inaktif'],
            ['id' => 19, 'nik' => '9988001122', 'nama' => 'Puji Astuti', 'npwp' => '99.880.011.2-000.000', 'alamat' => 'Jl. Singa No. 3, Magelang', 'no_telp' => '08199880011', 'status' => 'Aktif'],
            ['id' => 20, 'nik' => '0099112233', 'nama' => 'Qomarudin', 'npwp' => '00.991.122.3-000.000', 'alamat' => 'Jl. Beruang No. 7, Yogyakarta', 'no_telp' => '08100991122', 'status' => 'Aktif'],
        ];
    }

    public function index(Request $request)
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', $this->getInitialData());
        }

        $allKaryawan = session('karyawan_data');

        $status = $request->query('status');
        if ($status) {
            $allKaryawan = array_values(array_filter($allKaryawan, function($k) use ($status) {
                return strtolower($k['status']) === strtolower($status);
            }));
        }

        $search = $request->query('search');
        if ($search) {
            $allKaryawan = array_values(array_filter($allKaryawan, function($k) use ($search) {
                return stripos((string) $k['id'], $search) !== false
                    || stripos((string) $k['nik'], $search) !== false
                    || stripos((string) $k['nama'], $search) !== false
                    || stripos((string) $k['npwp'], $search) !== false
                    || stripos((string) $k['alamat'], $search) !== false
                    || stripos((string) $k['no_telp'], $search) !== false
                    || stripos((string) $k['status'], $search) !== false;
            }));
        }

        $page = (int) $request->get('page', 1);
        $perPage = 10;
        $total = count($allKaryawan);
        $totalPages = max(1, ceil($total / $perPage));
        
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $karyawan = array_slice($allKaryawan, ($page - 1) * $perPage, $perPage);

        return view('pimpinan.karyawan.data_karyawan', compact('karyawan', 'page', 'totalPages', 'status', 'search'));
    }
}
