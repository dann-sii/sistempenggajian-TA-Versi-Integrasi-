<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AktivitasKaryawanController extends Controller
{
    public static function getPiketTargets(): array
    {
        return [
            'produksi_tp_gram' => 3000,
            'peternakan_liter' => 5,
        ];
    }

    public static function getProdukResepData(): array
    {
        return [
            [
                'id_produk' => 'PR01',
                'nama_produk' => 'Susu Kambing Original',
                'jumlah_standar' => 120,
                'satuan_produk' => 'pcs / 3 kg',
                'susu_liter' => 10,
                'gula_gram' => 250,
                'perasa_jenis' => 'Vanila',
                'perasa_gram' => 15,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PR02',
                'nama_produk' => 'Susu Kambing Jahe',
                'jumlah_standar' => 120,
                'satuan_produk' => 'pcs / 3 kg',
                'susu_liter' => 10,
                'gula_gram' => 250,
                'perasa_jenis' => 'Jahe',
                'perasa_gram' => 20,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PR03',
                'nama_produk' => 'Susu Kambing Coklat',
                'jumlah_standar' => 120,
                'satuan_produk' => 'pcs / 3 kg',
                'susu_liter' => 10,
                'gula_gram' => 200,
                'perasa_jenis' => 'Coklat',
                'perasa_gram' => 25,
                'status' => 'aktif',
            ],
            [
                'id_produk' => 'PR04',
                'nama_produk' => 'Susu Kambing Karamel',
                'jumlah_standar' => 120,
                'satuan_produk' => 'pcs / 3 kg',
                'susu_liter' => 10,
                'gula_gram' => 225,
                'perasa_jenis' => 'Karamel',
                'perasa_gram' => 20,
                'status' => 'aktif',
            ],
        ];
    }

    public static function getPerasaOptions(): array
    {
        $options = [];

        foreach (self::getProdukResepData() as $produk) {
            if (strtolower($produk['status']) !== 'aktif' || empty($produk['perasa_jenis'])) {
                continue;
            }

            $options[$produk['perasa_jenis']] = $produk['perasa_jenis'];
        }

        return array_values($options);
    }

    public function index(Request $request)
    {
        if (!session()->has('karyawan_data')) {
            session()->put('karyawan_data', KaryawanController::getInitialData());
        }

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data'));

        $activeKaryawans = array_values(array_filter($karyawans, function ($karyawan) {
            return strtolower($karyawan['status']) === 'aktif';
        }));

        $selectedTanggal = $this->normalizeTanggal($request->query('tanggal')) ?: date('Y-m-d');
        $selectedBagian = in_array($request->query('bagian'), ['Produksi', 'Peternakan'], true)
            ? $request->query('bagian')
            : 'Produksi';

        $batchKaryawans = array_values(array_filter($activeKaryawans, function ($karyawan) use ($selectedBagian) {
            return ($karyawan['bagian'] ?? 'Produksi') === $selectedBagian;
        }));

        $aktivitas = session('aktivitas_karyawan_data', []);
        $produkResep = array_values(array_filter(self::getProdukResepData(), function ($produk) {
            return strtolower($produk['status']) === 'aktif';
        }));
        $perasaOptions = self::getPerasaOptions();
        $targets = self::getPiketTargets();
        $existingPiketByKaryawan = [];
        $recentPiket = [];
        $summary = [
            'rows' => 0,
            'bonus_total' => 0,
            'lebih' => 0,
            'normal' => 0,
            'kurang' => 0,
        ];

        foreach ($aktivitas as $row) {
            if (($row['source'] ?? '') !== 'piket_batch') {
                continue;
            }

            if (count($recentPiket) < 8) {
                $recentPiket[] = $row;
            }

            if (($row['tanggal'] ?? null) === $selectedTanggal && ($row['bagian'] ?? null) === $selectedBagian) {
                $existingPiketByKaryawan[$row['karyawan_id']] = $row;
            }

            $summary['rows']++;
            $summary['bonus_total'] += (float) ($row['bonus_total'] ?? 0);
            $status = strtolower($row['status_piket'] ?? '');
            if ($status === 'lebih') {
                $summary['lebih']++;
            } elseif ($status === 'normal') {
                $summary['normal']++;
            } elseif ($status === 'kurang') {
                $summary['kurang']++;
            }
        }

        $lastAktivitas = $aktivitas[0] ?? null;
        $latestAktivitasByKaryawan = [];

        foreach ($aktivitas as $row) {
            if (!isset($latestAktivitasByKaryawan[$row['karyawan_id']])) {
                $latestAktivitasByKaryawan[$row['karyawan_id']] = $row;
            }
        }

        return view('admin_penggajian.aktivitas_karyawan', compact(
            'activeKaryawans',
            'batchKaryawans',
            'produkResep',
            'perasaOptions',
            'targets',
            'selectedTanggal',
            'selectedBagian',
            'existingPiketByKaryawan',
            'recentPiket',
            'summary',
            'lastAktivitas',
            'latestAktivitasByKaryawan'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal' => $this->normalizeTanggal($request->input('tanggal')),
        ]);

        $data = $request->validate([
            'karyawan_id' => 'required',
            'tanggal' => 'required|date',
            'id_produk' => 'required|string|max:4',
            'jumlah_produksi' => 'required|numeric|min:0',
        ]);

        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data', KaryawanController::getInitialData()));
        $karyawan = collect($karyawans)->firstWhere('id', (int) $data['karyawan_id']);

        if (!$karyawan) {
            return redirect()->route('aktivitas_karyawan.index')->with('error', 'Karyawan tidak ditemukan.');
        }

        $produk = collect(self::getProdukResepData())->firstWhere('id_produk', $data['id_produk']);

        if (!$produk) {
            return redirect()->route('aktivitas_karyawan.index')->with('error', 'Produk tidak ditemukan.');
        }

        $aktivitas = session('aktivitas_karyawan_data', []);
        array_unshift($aktivitas, [
            'id' => count($aktivitas) + 1,
            'tanggal' => $data['tanggal'],
            'karyawan_id' => $karyawan['id'],
            'nama' => $karyawan['nama'],
            'nik' => $karyawan['nik'],
            'bagian' => $karyawan['bagian'] ?? '-',
            'id_produk' => $produk['id_produk'],
            'nama_produk' => $produk['nama_produk'],
            'jumlah_produksi' => (float) $data['jumlah_produksi'],
        ]);

        session()->put('aktivitas_karyawan_data', $aktivitas);

        return redirect()->route('aktivitas_karyawan.index')->with('success', 'Aktivitas karyawan berhasil dicatat.');
    }

    public function batchStore(Request $request)
    {
        $request->merge([
            'tanggal' => $this->normalizeTanggal($request->input('tanggal')),
        ]);

        $data = $request->validate([
            'tanggal' => 'required|date',
            'bagian' => 'required|in:Produksi,Peternakan',
            'entries' => 'nullable|array',
            'entries.*.liter_susu' => 'nullable|numeric|min:0',
            'entries.*.gula_gram' => 'nullable|numeric|min:0',
            'entries.*.perasa_jenis' => 'nullable|string|max:50',
            'entries.*.perasa_gram' => 'nullable|numeric|min:0',
            'entries.*.tp_gram' => 'nullable|numeric|min:0',
            'entries.*.liter_setor' => 'nullable|numeric|min:0',
        ]);

        $entries = $data['entries'] ?? [];
        $karyawans = KaryawanController::withDefaultBagian(session('karyawan_data', KaryawanController::getInitialData()));
        $karyawanById = collect($karyawans)->keyBy('id');
        $newRows = [];
        $replaceKeys = [];

        foreach ($entries as $karyawanId => $entry) {
            $karyawan = $karyawanById->get((int) $karyawanId);
            if (!$karyawan || strtolower($karyawan['status'] ?? '') !== 'aktif' || ($karyawan['bagian'] ?? 'Produksi') !== $data['bagian']) {
                continue;
            }

            if (!$this->isBatchEntryFilled($entry, $data['bagian'])) {
                continue;
            }

            $row = $this->buildPiketRow($data['tanggal'], $data['bagian'], $karyawan, $entry);
            $newRows[] = $row;
            $replaceKeys[$data['tanggal'] . '|' . $karyawan['id']] = true;
        }

        if (count($newRows) === 0) {
            return redirect()
                ->route('aktivitas_karyawan.index', ['tanggal' => $data['tanggal'], 'bagian' => $data['bagian']])
                ->with('error', 'Tidak ada baris piket yang terisi.');
        }

        $aktivitas = session('aktivitas_karyawan_data', []);
        $aktivitas = array_values(array_filter($aktivitas, function ($row) use ($replaceKeys) {
            if (($row['source'] ?? '') !== 'piket_batch') {
                return true;
            }

            $key = ($row['tanggal'] ?? '') . '|' . ($row['karyawan_id'] ?? '');
            return !isset($replaceKeys[$key]);
        }));

        $nextId = count($aktivitas) > 0 ? max(array_column($aktivitas, 'id')) + 1 : 1;
        foreach ($newRows as &$row) {
            $row['id'] = $nextId++;
        }
        unset($row);

        session()->put('aktivitas_karyawan_data', array_merge(array_reverse($newRows), $aktivitas));

        return redirect()
            ->route('aktivitas_karyawan.index', ['tanggal' => $data['tanggal'], 'bagian' => $data['bagian']])
            ->with('success', count($newRows) . ' baris piket harian berhasil disimpan.');
    }

    private function isBatchEntryFilled(array $entry, string $bagian): bool
    {
        $fields = $bagian === 'Produksi'
            ? ['liter_susu', 'gula_gram', 'perasa_jenis', 'perasa_gram', 'tp_gram']
            : ['liter_setor'];

        foreach ($fields as $field) {
            if (isset($entry[$field]) && trim((string) $entry[$field]) !== '') {
                return true;
            }
        }

        return false;
    }

    private function buildPiketRow(string $tanggal, string $bagian, array $karyawan, array $entry): array
    {
        $base = [
            'id' => 0,
            'source' => 'piket_batch',
            'tanggal' => $tanggal,
            'karyawan_id' => $karyawan['id'],
            'nama' => $karyawan['nama'],
            'nik' => $karyawan['nik'],
            'bagian' => $bagian,
        ];

        if ($bagian === 'Peternakan') {
            $literSetor = (float) ($entry['liter_setor'] ?? 0);
            $calculation = self::calculatePeternakanBonus($literSetor, $tanggal);

            return array_merge($base, [
                'liter_setor' => $literSetor,
                'target_liter_harian' => $calculation['target'],
                'selisih_liter_setor' => $calculation['selisih'],
                'status_piket' => $calculation['status'],
                'bonus_total' => $calculation['total'],
                'bonus_details' => $calculation['details'],
            ]);
        }

        $tpGram = (float) ($entry['tp_gram'] ?? 0);
        $literSusu = (float) ($entry['liter_susu'] ?? 0);
        $gulaGram = (float) ($entry['gula_gram'] ?? 0);
        $perasaGram = (float) ($entry['perasa_gram'] ?? 0);
        $perasaJenis = trim((string) ($entry['perasa_jenis'] ?? '')) ?: 'Vanila';

        $calculation = self::calculateProduksiBonus($tpGram, $literSusu, $gulaGram, $perasaGram, $perasaJenis, $tanggal);

        return array_merge($base, [
            'liter_susu' => $literSusu,
            'gula_gram' => $gulaGram,
            'perasa_jenis' => $perasaJenis,
            'perasa_gram' => $perasaGram,
            'tp_gram' => $tpGram,
            'target_tp_harian' => $calculation['target'],
            'selisih_tp_gram' => $calculation['selisih_tp'],
            'selisih_susu_liter' => $calculation['selisih_susu_liter'],
            'selisih_gula_gram' => $calculation['selisih_gula_gram'],
            'selisih_perasa_gram' => $calculation['selisih_perasa_gram'],
            'status_piket' => $calculation['status'],
            'bonus_total' => $calculation['total'],
            'bonus_details' => $calculation['details'],
        ]);
    }

    public static function calculateProduksiBonus(float $tpGram, float $literSusu, float $gulaGram, float $perasaGram, string $perasaJenis, string $tanggal = ''): array
    {
        $targetTp = (float) self::getPiketTargets()['produksi_tp_gram'];
        $selisihTp = self::customRound(max(0, $tpGram - $targetTp));

        $produk = self::findProdukByPerasa($perasaJenis) ?? self::getProdukResepData()[0];

        $targetSusu = (float) $produk['susu_liter'];
        $selisihSusu = self::customRound(max(0, $literSusu - $targetSusu));

        $targetGula = (float) $produk['gula_gram'];
        $selisihGula = self::customRound(max(0, $gulaGram - $targetGula));

        $targetPerasa = (float) $produk['perasa_gram'];
        $selisihPerasa = self::customRound(max(0, $perasaGram - $targetPerasa));

        $details = [];
        $total = 0;

        foreach (
            [
                ['material' => 'susu', 'amount' => $selisihSusu, 'unit' => 'Liter', 'target' => $targetSusu, 'actual' => $literSusu],
                ['material' => 'gula', 'amount' => $selisihGula, 'unit' => 'Gram', 'target' => $targetGula, 'actual' => $gulaGram],
                ['material' => 'perasa ' . strtolower($produk['perasa_jenis']), 'amount' => $selisihPerasa, 'unit' => 'Gram', 'target' => $targetPerasa, 'actual' => $perasaGram],
            ] as $item
        ) {
            $rule = self::findBonusRuleForMaterialName($item['material']);
            if (!$rule || $item['amount'] <= 0) {
                continue;
            }

            $amount = $item['amount'] * (float) $rule['tarif'];
            $total += $amount;
            $details[] = [
                'title' => $rule['nama'],
                'description' => sprintf(
                    '%s: %s %s %s, target %s %s, selisih %s %s x Rp %s',
                    $tanggal ?: 'Piket harian',
                    ucfirst(explode(' ', $item['material'])[0]),
                    number_format($item['actual'], 2, ',', '.'),
                    $item['unit'],
                    number_format($item['target'], 2, ',', '.'),
                    $item['unit'],
                    number_format($item['amount'], 0, ',', '.'),
                    $item['unit'],
                    number_format($rule['tarif'], 0, ',', '.')
                ),
                'amount' => $amount,
            ];
        }

        return [
            'target' => $targetTp,
            'selisih_tp' => $selisihTp,
            'selisih_susu_liter' => $selisihSusu,
            'selisih_gula_gram' => $selisihGula,
            'selisih_perasa_gram' => $selisihPerasa,
            'status' => self::statusFromActual($tpGram, $targetTp),
            'total' => $total,
            'details' => $details,
        ];
    }

    public static function calculatePeternakanBonus(float $literSetor, string $tanggal = ''): array
    {
        $target = (float) self::getPiketTargets()['peternakan_liter'];
        $selisih = self::customRound(max(0, $literSetor - $target));
        $rule = self::findBonusRuleForMaterialName('peternakan');
        $total = $rule ? $selisih * (float) $rule['tarif'] : 0;
        $details = [];

        if ($rule && $selisih > 0) {
            $details[] = [
                'title' => $rule['nama'],
                'description' => sprintf(
                    '%s: Setor %s liter, target %s liter, selisih %s liter x Rp %s',
                    $tanggal ?: 'Piket harian',
                    number_format($literSetor, 2, ',', '.'),
                    number_format($target, 2, ',', '.'),
                    number_format($selisih, 2, ',', '.'),
                    number_format($rule['tarif'], 0, ',', '.')
                ),
                'amount' => $total,
            ];
        }

        return [
            'target' => $target,
            'selisih' => $selisih,
            'status' => self::statusFromActual($literSetor, $target),
            'total' => $total,
            'details' => $details,
        ];
    }

    public static function findProdukByPerasa(string $perasaJenis): ?array
    {
        foreach (self::getProdukResepData() as $produk) {
            if (strtolower($produk['status']) === 'aktif' && strtolower($produk['perasa_jenis'] ?? '') === strtolower($perasaJenis)) {
                return $produk;
            }
        }

        return null;
    }

    private static function findBonusRuleForMaterialName(string $material): ?array
    {
        $material = strtolower($material);

        foreach (GajiBonusController::getBonusData() as $bonus) {
            if (strtolower($bonus['status']) !== 'aktif') {
                continue;
            }

            $name = strtolower($bonus['nama']);

            if ($material === 'susu' && str_contains($name, 'susu') && !str_contains($name, 'peternakan')) {
                return $bonus;
            }

            if ($material === 'gula' && str_contains($name, 'gula')) {
                return $bonus;
            }

            if ($material === 'peternakan' && str_contains($name, 'peternakan')) {
                return $bonus;
            }

            if (str_starts_with($material, 'perasa') && str_contains($name, $material)) {
                return $bonus;
            }
        }

        return null;
    }

    private static function statusFromActual(float $actual, float $target): string
    {
        $diff = $actual - $target;
        if ($diff > 0) {
            return 'Lebih';
        }

        if ($diff === 0.0) {
            return 'Normal';
        }

        return 'Kurang';
    }

    private static function customRound(float $value): float
    {
        return floor($value);
    }

    private function normalizeTanggal(?string $tanggal): ?string
    {
        $tanggal = trim((string) $tanggal);

        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $tanggal, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }

        return $tanggal ?: null;
    }
}
