<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\GajiBonusController;
use App\Http\Controllers\Api\Admin\PotonganGajiController;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    private function getInitialData() 
    {
        return KaryawanController::getInitialData();
    }

    private function withDefaultBagian(array $karyawans): array
    {
        return KaryawanController::withDefaultBagian($karyawans);
    }

    public function index()
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', $this->getInitialData());
        }
        
        $karyawans = $this->withDefaultBagian(session('karyawan_data'));
        session()->put('karyawan_data', $karyawans);

        // Data presensi: hitung jumlah hari hadir dan jam lembur per karyawan per bulan
        $allPresensi = PresensiController::getPresensiData();

        // Agregasi: hitung jumlah hari hadir dan total jam lembur per nama karyawan
        $hariKerjaMap = [];
        $jamLemburMap = [];
        foreach ($allPresensi as $p) {
            if ($p['kehadiran'] === 'Hadir') {
                $hariKerjaMap[$p['nama']] = ($hariKerjaMap[$p['nama']] ?? 0) + 1;
            }

            $jamLemburMap[$p['nama']] = ($jamLemburMap[$p['nama']] ?? 0) + (float) ($p['jam_lembur'] ?? 0);
        }

        $gajiPokokRules = array_values(array_filter(GajiPokokController::getGajiPokokData(), function ($gaji) {
            return strtolower($gaji['status']) === 'aktif';
        }));
        $gajiLemburRules = array_values(array_filter(GajiPokokController::getGajiLemburData(), function ($gaji) {
            return strtolower($gaji['status']) === 'aktif';
        }));

        $gajiPokokProduksi = $gajiPokokRules[0] ?? ['tarif' => 0, 'nama' => 'Gaji Pokok per Jam'];
        $gajiPokokPeternakan = $gajiPokokRules[1] ?? $gajiPokokProduksi;
        $gajiLemburAktif = (float) ($gajiLemburRules[0]['tarif'] ?? 0);

        // Buat lookup karyawan_id => data untuk dipakai JS
        $karyawanLookup = [];
        foreach ($karyawans as $k) {
            $hariKerja = $hariKerjaMap[$k['nama']] ?? 0;
            $jamLembur = $jamLemburMap[$k['nama']] ?? 0;
            $bagian = $k['bagian'] ?? '-';
            $isPeternakan = strtolower($bagian) === 'peternakan';
            $gajiPokokRule = strtolower($bagian) === 'peternakan' ? $gajiPokokPeternakan : $gajiPokokProduksi;
            $satuanGajiPokok = stripos($gajiPokokRule['nama'], 'per Hari') !== false ? 'Hari' : 'Jam';
            $tarifGajiPokok = (float) $gajiPokokRule['tarif'];
            $totalGajiPokok = $satuanGajiPokok === 'Hari'
                ? $hariKerja * $tarifGajiPokok
                : $hariKerja * 8 * $tarifGajiPokok;
            $jamLemburDibayar = $isPeternakan ? 0 : $jamLembur;

            $karyawanLookup[$k['id']] = [
                'nama'        => $k['nama'],
                'bagian'      => $bagian,
                'hari_kerja'  => $hariKerja,
                'gaji_pokok'  => $tarifGajiPokok,
                'satuan_gaji_pokok' => $satuanGajiPokok,
                'total_gaji_pokok' => $totalGajiPokok,
                'lembur'      => $jamLemburDibayar > 0 ? 'Ya' : 'Tidak',
                'jam_lembur'  => $jamLemburDibayar,
                'tarif_lembur' => $gajiLemburAktif,
                'total_lembur' => $jamLemburDibayar * $gajiLemburAktif,
                'lembur_aktif' => !$isPeternakan,
            ];
        }

        $gajiBonus = array_values(array_filter(GajiBonusController::getBonusData(), function ($bonus) {
            return strtolower($bonus['status']) === 'aktif';
        }));

        $potonganGaji = array_values(array_filter(PotonganGajiController::getPotonganData(), function ($potongan) {
            return strtolower($potongan['status']) === 'aktif';
        }));

        return view('admin_penggajian.penggajian', compact('karyawans', 'karyawanLookup', 'gajiBonus', 'potonganGaji'));
    }
}
