@extends('layouts.app')

@section('title', 'Aktivitas Karyawan')

@section('content')
    @php
        $statusClasses = [
            'Lebih' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Normal' => 'bg-blue-50 text-blue-700 border-blue-200',
            'Kurang' => 'bg-rose-50 text-rose-700 border-rose-200',
        ];
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1
                class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                REKAP AKTIVITAS KARYAWAN
            </h1>

            <form method="GET" action="{{ route('aktivitas_karyawan.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="date" name="tanggal" value="{{ $selectedTanggal }}"
                        class="rounded-lg border border-carbon-black-200 bg-white px-3 py-2 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                </div>

                <div class="relative">
                    <select name="bagian"
                        class="appearance-none rounded-lg border border-carbon-black-200 bg-white px-3 py-2 pr-8 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                        <option value="Produksi" {{ $selectedBagian === 'Produksi' ? 'selected' : '' }}>Produksi</option>
                        <option value="Peternakan" {{ $selectedBagian === 'Peternakan' ? 'selected' : '' }}>Peternakan
                        </option>
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex h-[38px] items-center justify-center gap-2 rounded-lg bg-black-forest-700 px-4 text-sm font-bold uppercase tracking-widest text-white shadow-sm transition-all hover:bg-black-forest-800 active:scale-95">
                    <x-tabler-filter class="h-4 w-4" />
                    Tampilkan
                </button>
            </form>
        </div>

        @if(session('success'))
            <div
                class="rounded-lg border-l-4 border-emerald-500 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg border-l-4 border-rose-500 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg border-l-4 border-rose-500 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div
            class="flex flex-wrap items-center justify-between gap-6 rounded-xl border border-carbon-black-200 bg-white px-6 py-4 shadow-sm">
            <div class="flex flex-1 items-center justify-between gap-6 md:justify-start md:gap-12">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-carbon-black-500">Baris Aktivitas</p>
                    <p class="mt-0.5 text-xl font-black text-carbon-black-900">
                        {{ number_format($summary['rows'], 0, ',', '.') }}</p>
                </div>
                <div class="hidden h-8 w-px bg-carbon-black-100 md:block"></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Status Lebih</p>
                    <p class="mt-0.5 text-xl font-black text-emerald-700">
                        {{ number_format($summary['lebih'], 0, ',', '.') }}</p>
                </div>
                <div class="hidden h-8 w-px bg-carbon-black-100 md:block"></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-600">Status Kurang</p>
                    <p class="mt-0.5 text-xl font-black text-rose-700">{{ number_format($summary['kurang'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-lg bg-carbon-black-50 px-4 py-2 border border-carbon-black-100">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-hunter-green-100 text-hunter-green-600">
                    <x-tabler-coin class="h-5 w-5" />
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-carbon-black-500">Bonus Tercatat</p>
                    <p class="text-lg font-black text-hunter-green-700">Rp
                        {{ number_format($summary['bonus_total'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('aktivitas_karyawan.batch_store') }}" id="piket-form">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
            <input type="hidden" name="bagian" value="{{ $selectedBagian }}">

            <section class="overflow-hidden rounded-none border border-carbon-black-200 bg-white shadow-xl">
                <div
                    class="flex items-center justify-between gap-4 border-b border-carbon-black-100 bg-carbon-black-50 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">
                            {{ $selectedBagian }} - {{ date('d M Y', strtotime($selectedTanggal)) }}
                        </h2>
                        <p class="mt-1 text-sm font-semibold text-carbon-black-500">
                            {{ $selectedBagian === 'Produksi' ? 'Target Total Produksi (TP): ' . number_format($targets['produksi_tp_gram'], 0, ',', '.') . ' gram' : 'Target Setoran: ' . number_format($targets['peternakan_liter'], 2, ',', '.') . ' liter' }}
                        </p>
                    </div>

                    <button type="submit"
                        class="inline-flex h-11 items-center gap-2 rounded-xl bg-hunter-green-600 px-6 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-hunter-green-700 active:scale-95">
                        <x-tabler-device-floppy class="h-5 w-5" />
                        Simpan Rekap
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-[1100px] w-full border-collapse">
                        <thead class="bg-carbon-black-50 text-carbon-black-600">
                            <tr class="border-b border-carbon-black-200">
                                <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest w-16">No</th>
                                <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-widest">Karyawan</th>
                                @if($selectedBagian === 'Produksi')
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Susu (Liter)
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Gula (Gram)
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Rasa</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Perasa (Gram)
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Hasil
                                        Produksi (Gram)</th>
                                @else
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Susu Disetor
                                        (Liter)</th>
                                @endif
                                <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Status
                                    Capaian</th>
                                <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Bonus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-carbon-black-100">
                            @forelse($batchKaryawans as $index => $karyawan)
                                @php
                                    $existing = $existingPiketByKaryawan[$karyawan['id']] ?? [];
                                    $status = $existing['status_piket'] ?? 'Kurang';
                                    $statusClass = $statusClasses[$status] ?? 'bg-carbon-black-50 text-carbon-black-600 border-carbon-black-200';
                                    $isActive = ($existing['liter_susu'] ?? 0) > 0 || ($existing['liter_setor'] ?? 0) > 0;
                                @endphp
                                <tr class="piket-row transition-all duration-200 hover:bg-hunter-green-50/50 {{ $isActive ? 'bg-hunter-green-50/30 shadow-sm border-y border-hunter-green-200' : '' }}"
                                    data-target="{{ $selectedBagian === 'Produksi' ? $targets['produksi_tp_gram'] : $targets['peternakan_liter'] }}">
                                    <td
                                        class="bg-carbon-black-50/50 px-4 py-4 text-center text-sm font-black text-hunter-green-700">
                                        {{ $index + 1 }}</td>
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-black text-carbon-black-900">{{ $karyawan['nama'] }}</p>
                                        <p class="mt-0.5 text-xs font-semibold text-carbon-black-400">NIK:
                                            {{ $karyawan['nik'] }}</p>
                                    </td>

                                    @if($selectedBagian === 'Produksi')
                                        <td class="px-3 py-4 text-center">
                                            <input type="number" min="0" step="0.01"
                                                name="entries[{{ $karyawan['id'] }}][liter_susu]"
                                                value="{{ $existing['liter_susu'] ?? '' }}" placeholder="Jml Susu"
                                                class="piket-input piket-susu w-28 rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-2 text-right text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-300 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            <input type="number" min="0" step="0.01"
                                                name="entries[{{ $karyawan['id'] }}][gula_gram]"
                                                value="{{ $existing['gula_gram'] ?? '' }}" placeholder="Jml Gula"
                                                class="piket-input piket-gula w-28 rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-2 text-right text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-300 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            <div class="relative inline-block text-left">
                                                <select name="entries[{{ $karyawan['id'] }}][perasa_jenis]"
                                                    class="piket-input piket-rasa w-36 appearance-none rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-2 pr-8 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                                                    <option value="">Rasa</option>
                                                    @foreach($perasaOptions as $perasa)
                                                        <option value="{{ $perasa }}" {{ ($existing['perasa_jenis'] ?? '') === $perasa ? 'selected' : '' }}>{{ $perasa }}</option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400">
                                                    <x-tabler-chevron-down class="h-4 w-4" />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            <input type="number" min="0" step="0.01"
                                                name="entries[{{ $karyawan['id'] }}][perasa_gram]"
                                                value="{{ $existing['perasa_gram'] ?? '' }}" placeholder="Jml Perasa"
                                                class="piket-input piket-perasa w-28 rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-2 text-right text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-300 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            <input type="number" min="0" step="0.01" name="entries[{{ $karyawan['id'] }}][tp_gram]"
                                                value="{{ $existing['tp_gram'] ?? '' }}" placeholder="Berat TP"
                                                class="piket-input piket-actual w-28 rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-2 text-right text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-300 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                                        </td>
                                    @else
                                        <td class="px-3 py-4 text-center">
                                            <input type="number" min="0" step="0.01"
                                                name="entries[{{ $karyawan['id'] }}][liter_setor]"
                                                value="{{ $existing['liter_setor'] ?? '' }}" placeholder="Jml Setor"
                                                class="piket-input piket-actual w-32 rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-2 text-right text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-300 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                                        </td>
                                    @endif

                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="piket-status inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                            <span class="status-text">{{ $status }}</span>
                                            <span
                                                class="piket-diff opacity-80">{{ isset($existing['selisih_tp_gram']) && $existing['selisih_tp_gram'] > 0 ? '(' . number_format($existing['selisih_tp_gram'], 0, ',', '.') . ')' : (isset($existing['selisih_liter_setor']) && $existing['selisih_liter_setor'] > 0 ? '(' . number_format($existing['selisih_liter_setor'], 2, ',', '.') . ')' : '') }}</span>
                                        </span>
                                    </td>
                                    <td
                                        class="px-4 py-4 text-center text-sm font-black piket-bonus-text {{ ($existing['bonus_total'] ?? 0) > 0 ? 'text-hunter-green-700' : 'text-carbon-black-300' }}">
                                        Rp <span
                                            class="piket-bonus-val">{{ number_format($existing['bonus_total'] ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $selectedBagian === 'Produksi' ? 9 : 5 }}"
                                        class="px-6 py-10 text-center text-sm font-bold text-carbon-black-500">
                                        Tidak ada karyawan aktif untuk bagian ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </form>

        <section class="rounded-xl border border-carbon-black-200 bg-white shadow-sm">
            <div class="border-b border-carbon-black-100 bg-carbon-black-50 px-5 py-4">
                <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Riwayat Aktivitas Terbaru
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full border-collapse">
                    <thead class="bg-white text-carbon-black-500">
                        <tr class="border-b border-carbon-black-100">
                            <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-widest">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-widest">Karyawan</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Bagian</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Aktual</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Bonus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-carbon-black-100">
                        @forelse($recentPiket as $row)
                            @php
                                $status = $row['status_piket'] ?? 'Kurang';
                                $statusClass = $statusClasses[$status] ?? 'bg-carbon-black-50 text-carbon-black-600 border-carbon-black-200';
                                $actual = ($row['bagian'] ?? '') === 'Produksi'
                                    ? number_format($row['tp_gram'] ?? 0, 0, ',', '.') . ' gram TP'
                                    : number_format($row['liter_setor'] ?? 0, 2, ',', '.') . ' liter';
                            @endphp
                            <tr class="hover:bg-hunter-green-50/50">
                                <td class="px-5 py-4 text-sm font-bold text-carbon-black-700">
                                    {{ date('d M Y', strtotime($row['tanggal'])) }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-carbon-black-900">{{ $row['nama'] }}</p>
                                    <p class="text-xs font-semibold text-carbon-black-400">NIK: {{ $row['nik'] }}</p>
                                </td>
                                <td class="px-5 py-4 text-center text-sm font-bold text-carbon-black-700">{{ $row['bagian'] }}
                                </td>
                                <td class="px-5 py-4 text-center text-sm font-bold text-carbon-black-700">{{ $actual }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">{{ $status }}</span>
                                </td>
                                <td class="px-5 py-4 text-center text-sm font-black text-hunter-green-700">Rp
                                    {{ number_format($row['bonus_total'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm font-bold text-carbon-black-500">Belum ada
                                    rekap aktivitas batch.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            const resepProduk = @json($produkResep ?? []);

            document.addEventListener('DOMContentLoaded', function () {
                const statusClasses = {
                    Lebih: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    Normal: 'bg-blue-50 text-blue-700 border-blue-200',
                    Kurang: 'bg-rose-50 text-rose-700 border-rose-200',
                };

                function formatNumber(value, maximumFractionDigits = 0) {
                    return Number(value || 0).toLocaleString('id-ID', {
                        maximumFractionDigits,
                        minimumFractionDigits: maximumFractionDigits > 0 && Number(value || 0) % 1 !== 0 ? 1 : 0,
                    });
                }

                function customRound(value) {
                    return Math.floor(value);
                }

                function updateRow(row) {
                    const target = Number(row.dataset.target || 0);
                    const actualInput = row.querySelector('.piket-actual');
                    const statusElement = row.querySelector('.piket-status');
                    const statusTextElement = statusElement?.querySelector('.status-text');
                    const diffElement = statusElement?.querySelector('.piket-diff');

                    const susuInput = row.querySelector('.piket-susu');
                    const rasaSelect = row.querySelector('.piket-rasa');
                    const gulaHidden = row.querySelector('.piket-gula');
                    const perasaHidden = row.querySelector('.piket-perasa');

                    const actual = Number(actualInput?.value || 0);
                    const rawDiff = actual - target;
                    const diff = customRound(Math.max(0, rawDiff));
                    let status = 'Kurang';

                    if (rawDiff > 0) {
                        status = 'Lebih';
                    } else if (rawDiff === 0 && actual > 0) {
                        status = 'Normal';
                    }

                    if (statusElement) {
                        statusElement.className = `piket-status inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest ${statusClasses[status]}`;
                        if (statusTextElement) statusTextElement.textContent = status;

                        if (diffElement) {
                            if (actual > 0 && diff > 0) {
                                const diffStr = formatNumber(diff, target < 100 ? 2 : 0);
                                diffElement.textContent = `(${status === 'Lebih' ? '+' : ''}${diffStr})`;
                            } else {
                                diffElement.textContent = '';
                            }
                        }
                    }

                    // Highlight row logic
                    if (actual > 0 || (susuInput && Number(susuInput.value) > 0)) {
                        row.classList.add('bg-hunter-green-50/30', 'shadow-sm', 'border-y', 'border-hunter-green-200');
                    } else {
                        row.classList.remove('bg-hunter-green-50/30', 'shadow-sm', 'border-y', 'border-hunter-green-200');
                    }

                }

                document.querySelectorAll('.piket-row').forEach(row => {
                    row.querySelectorAll('.piket-input').forEach(input => {
                        input.addEventListener('input', () => updateRow(row));
                        input.addEventListener('change', () => updateRow(row));
                    });
                    updateRow(row);
                });
            });
        </script>
    @endpush
@endsection