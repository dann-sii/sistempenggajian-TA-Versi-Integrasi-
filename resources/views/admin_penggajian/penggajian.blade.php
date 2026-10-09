@extends('layouts.app')

@section('title', 'Penggajian Karyawan')

@section('content')
    @php
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $currentMonthIndex = date('n') - 1;
        $currentYear = (int) date('Y');
        $activeKaryawans = array_values(array_filter($karyawans ?? [], function ($karyawan) {
            return strtolower($karyawan['status'] ?? '') === 'aktif';
        }));
        $bagianOptions = array_values(array_unique(array_filter(array_map(function ($karyawan) use ($karyawanLookup) {
            return $karyawanLookup[$karyawan['id']]['bagian'] ?? ($karyawan['bagian'] ?? null);
        }, $activeKaryawans))));
        sort($bagianOptions);
    @endphp

    <div class="space-y-6 pb-12">
        <!-- 1. Header & Periode Selector -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                    PENGGAJIAN KARYAWAN
                </h1>
                <p class="text-xs font-semibold text-carbon-black-500 pl-4 mt-1">
                    Kelola dan verifikasi gaji pokok, jam lembur, bonus produksi, dan potongan gaji karyawan.
                </p>
            </div>

            <!-- Periode Filter Dropdowns -->
            <div class="flex items-center gap-3">
                <!-- Dropdown Bulan -->
                <div class="relative flex items-center h-[42px] bg-white rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 shadow-sm transition-colors overflow-hidden">
                    <div class="pl-3.5 pr-1 text-hunter-green-600 pointer-events-none">
                        <x-tabler-calendar class="w-4 h-4" />
                    </div>
                    <select id="periode-bulan-select"
                        class="appearance-none bg-transparent pl-2 pr-9 py-2 text-sm font-bold text-carbon-black-900 focus:outline-none cursor-pointer min-w-[130px]">
                        @foreach($months as $index => $month)
                            <option value="{{ $month }}" data-month-number="{{ $index + 1 }}" {{ $index === $currentMonthIndex ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute right-0 inset-y-0 flex items-center px-2.5 text-carbon-black-400">
                        <x-tabler-chevron-down class="w-4 h-4" />
                    </div>
                </div>

                <!-- Dropdown Tahun -->
                <div class="relative flex items-center h-[42px] bg-white rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 shadow-sm transition-colors overflow-hidden">
                    <select id="periode-tahun-select"
                        class="appearance-none bg-transparent pl-3.5 pr-8 py-2 text-sm font-bold text-carbon-black-900 focus:outline-none cursor-pointer min-w-[90px]">
                        @for($year = $currentYear - 5; $year <= $currentYear + 5; $year++)
                            <option value="{{ $year }}" {{ $year === $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <div class="pointer-events-none absolute right-0 inset-y-0 flex items-center px-2.5 text-carbon-black-400">
                        <x-tabler-chevron-down class="w-4 h-4" />
                    </div>
                </div>

                <button type="button" id="btn-batch-process-all"
                    class="bg-black-forest-700 text-white px-5 h-[42px] text-xs font-black rounded-lg shadow-sm hover:bg-black-forest-800 transition-all flex items-center space-x-2 active:scale-95 group uppercase tracking-wider">
                    <x-tabler-checks class="w-4 h-4" />
                    <span>Bayar Semua</span>
                </button>
            </div>
        </div>

        <!-- 2. Executive Metric Cards (4 Cards - Inspired by Desktop Run Payroll) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metric 1: Total Penggajian Bersih Periode -->
            <div class="bg-white rounded-xl p-5 border border-carbon-black-200 shadow-2xs hover:shadow-xs transition-shadow relative overflow-hidden group">
                <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-hunter-green-50 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="flex items-center justify-between mb-2 relative z-10">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-500">Total Pengeluaran Gaji</span>
                    <span class="p-1.5 rounded-lg bg-hunter-green-100 text-hunter-green-700">
                        <x-tabler-wallet class="w-4 h-4" />
                    </span>
                </div>
                <div class="relative z-10">
                    <div id="stat-total-gaji-bersih" class="text-2xl font-black text-carbon-black-900 tracking-tight">
                        Rp 0
                    </div>
                    <p class="text-xs font-semibold text-hunter-green-700 mt-1 flex items-center gap-1">
                        <x-tabler-users class="w-3.5 h-3.5" />
                        <span id="stat-total-karyawan-count">{{ count($activeKaryawans) }} Karyawan Aktif</span>
                    </p>
                </div>
            </div>

            <!-- Metric 2: Progres Status Pembayaran -->
            <div class="bg-white rounded-xl p-5 border border-carbon-black-200 shadow-2xs hover:shadow-xs transition-shadow relative overflow-hidden group">
                <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="flex items-center justify-between mb-2 relative z-10">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-500">Progres Pembayaran</span>
                    <span class="p-1.5 rounded-lg bg-emerald-100 text-emerald-700">
                        <x-tabler-circle-check class="w-4 h-4" />
                    </span>
                </div>
                <div class="relative z-10">
                    <div id="stat-progres-rasio" class="text-2xl font-black text-emerald-800 tracking-tight">
                        0 / {{ count($activeKaryawans) }} <span class="text-xs font-bold text-carbon-black-400">Lunas</span>
                    </div>
                    <div class="w-full bg-carbon-black-100 h-2 rounded-full mt-2.5 overflow-hidden">
                        <div id="stat-progres-bar" class="bg-emerald-600 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Total Jam Lembur -->
            <div class="bg-white rounded-xl p-5 border border-carbon-black-200 shadow-2xs hover:shadow-xs transition-shadow relative overflow-hidden group">
                <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="flex items-center justify-between mb-2 relative z-10">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-500">Akumulasi Lembur</span>
                    <span class="p-1.5 rounded-lg bg-amber-100 text-amber-700">
                        <x-tabler-clock-bolt class="w-4 h-4" />
                    </span>
                </div>
                <div class="relative z-10">
                    <div id="stat-total-jam-lembur" class="text-2xl font-black text-amber-700 tracking-tight">
                        0 Jam
                    </div>
                    <p class="text-xs font-semibold text-amber-800 mt-1 flex items-center gap-1">
                        <x-tabler-coin class="w-3.5 h-3.5" />
                        <span id="stat-total-nominal-lembur">Rp 0 Upah Lembur</span>
                    </p>
                </div>
            </div>

            <!-- Metric 4: Tambahan vs Potongan -->
            <div class="bg-white rounded-xl p-5 border border-carbon-black-200 shadow-2xs hover:shadow-xs transition-shadow relative overflow-hidden group">
                <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="flex items-center justify-between mb-2 relative z-10">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-500">Bonus & Potongan</span>
                    <span class="p-1.5 rounded-lg bg-blue-100 text-blue-700">
                        <x-tabler-adjustments-horizontal class="w-4 h-4" />
                    </span>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <span id="stat-total-bonus" class="text-base font-black text-hunter-green-700">+ Rp 0</span>
                        <span class="text-xs text-carbon-black-300 font-bold">|</span>
                        <span id="stat-total-potongan" class="text-base font-black text-rose-600">- Rp 0</span>
                    </div>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1.5 flex items-center justify-between">
                        <span>Bonus Produksi</span>
                        <span>Potongan/Wastage</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. Visual Payout Distribution Breakdown -->
        <div class="bg-white rounded-xl border border-carbon-black-200 p-4.5 shadow-2xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2.5">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-hunter-green-600"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-carbon-black-900">
                        Komposisi Biaya Penggajian Bulan Ini
                    </h3>
                </div>
                <div class="flex flex-wrap items-center gap-3.5 text-xs font-bold">
                    <span class="flex items-center gap-1.5 text-hunter-green-800">
                        <span class="w-2 h-2 rounded-full bg-hunter-green-600"></span>
                        Gaji Pokok: <span id="comp-pokok-val" class="font-black">Rp 0</span>
                    </span>
                    <span class="flex items-center gap-1.5 text-amber-700">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Lembur: <span id="comp-lembur-val" class="font-black">Rp 0</span>
                    </span>
                    <span class="flex items-center gap-1.5 text-blue-700">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Bonus: <span id="comp-bonus-val" class="font-black">Rp 0</span>
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-700">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Potongan: <span id="comp-potongan-val" class="font-black">Rp 0</span>
                    </span>
                </div>
            </div>

            <!-- Distribution Progress Bar -->
            <div class="w-full h-3 rounded-lg overflow-hidden flex bg-carbon-black-100">
                <div id="bar-pokok" class="bg-hunter-green-600 h-full transition-all duration-500" style="width: 80%" title="Gaji Pokok"></div>
                <div id="bar-lembur" class="bg-amber-500 h-full transition-all duration-500" style="width: 10%" title="Upah Lembur"></div>
                <div id="bar-bonus" class="bg-blue-500 h-full transition-all duration-500" style="width: 10%" title="Bonus"></div>
            </div>
        </div>

        <!-- 4. Batch Floating Action Bar -->
        <div id="payroll-batch-bar" class="hidden transition-all duration-300">
            <div class="bg-carbon-black-900 text-white rounded-xl px-5 py-3 shadow-xl flex items-center justify-between border border-carbon-black-800">
                <div class="flex items-center space-x-3">
                    <span class="p-1 rounded-md bg-hunter-green-500 text-white">
                        <x-tabler-checks class="w-4 h-4" />
                    </span>
                    <span class="text-sm font-bold">
                        <strong id="batch-selected-count">0</strong> karyawan dipilih
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" id="btn-batch-deselect" class="px-3.5 py-1.5 text-xs font-bold text-carbon-black-300 hover:text-white transition-colors">
                        Batal Pilih
                    </button>
                    <button type="button" id="btn-batch-pay-selected" class="px-4 py-2 bg-hunter-green-600 hover:bg-hunter-green-500 text-white text-xs font-black uppercase tracking-wider rounded-lg transition-colors flex items-center gap-1.5">
                        <x-tabler-check class="w-3.5 h-3.5" />
                        <span>Tandai Lunas Terpilih</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 5. Main Table Section (Scaled to Match Data Karyawan Font Baseline) -->
        <section class="bg-white rounded-xl border border-carbon-black-200 shadow-2xs overflow-hidden">
            <!-- Search & Bagian Filter Toolbar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-carbon-black-100 bg-carbon-black-50/60 px-5 py-3.5">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <!-- Search Input -->
                    <div class="flex shadow-2xs rounded-lg overflow-hidden border border-carbon-black-300 focus-within:border-hunter-green-400 bg-white transition-all w-full sm:w-80">
                        <input type="text" id="payroll-search" placeholder="Cari nama karyawan..."
                            class="w-full px-3.5 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-400 text-sm font-semibold"
                            autocomplete="off">
                        <div class="grid place-items-center bg-carbon-black-50 px-3.5 border-l border-carbon-black-200 text-carbon-black-400">
                            <x-tabler-search class="w-4 h-4" />
                        </div>
                    </div>

                    <!-- Bagian Filter -->
                    <div class="relative w-48 shrink-0">
                        <select id="payroll-bagian-filter"
                            class="w-full appearance-none rounded-lg border border-carbon-black-300 bg-white px-3.5 py-2 pr-8 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-1 focus:ring-hunter-green-500">
                            <option value="">Semua Bagian</option>
                            @foreach($bagianOptions as $bagian)
                                <option value="{{ $bagian }}">{{ $bagian }}</option>
                            @endforeach
                        </select>
                        <x-tabler-chevron-down class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-carbon-black-400" />
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-auto text-xs text-carbon-black-500 font-semibold">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Klik baris untuk buka rincian slip karyawan</span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto scrollbar-invisible">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600 text-sm font-black uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-4 text-center w-12">
                                <input type="checkbox" id="check-select-all" class="w-4 h-4 rounded border-carbon-black-300 text-hunter-green-600 focus:ring-hunter-green-500 cursor-pointer">
                            </th>
                            <th class="px-5 py-4">Karyawan</th>
                            <th class="px-5 py-4 text-center">Bagian</th>
                            <th class="px-5 py-4 text-center">Hari & Lembur</th>
                            <th class="px-5 py-4 text-center">Penyesuaian</th>
                            <th class="px-5 py-4 text-right">Gaji Bersih</th>
                            <th class="px-4 py-4 text-center min-w-[140px] whitespace-nowrap">Status</th>
                            <th class="px-5 py-4 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="payroll-table-body" class="divide-y divide-carbon-black-100 text-base">
                        @forelse($activeKaryawans as $index => $karyawan)
                            @php
                                $lookup = $karyawanLookup[$karyawan['id']] ?? [];
                            @endphp
                            <tr class="payroll-row cursor-pointer transition-colors hover:bg-hunter-green-50/60 group"
                                data-karyawan-id="{{ $karyawan['id'] }}" data-karyawan-name="{{ $karyawan['nama'] }}"
                                data-karyawan-bagian="{{ $lookup['bagian'] ?? ($karyawan['bagian'] ?? '-') }}"
                                data-row-index="{{ $index + 1 }}">
                                <!-- Checkbox -->
                                <td class="px-4 py-4.5 text-center" onclick="event.stopPropagation()">
                                    <input type="checkbox" class="row-checkbox w-4 h-4 rounded border-carbon-black-300 text-hunter-green-600 focus:ring-hunter-green-500 cursor-pointer" data-id="{{ $karyawan['id'] }}">
                                </td>

                                <!-- Nama Karyawan (Baseline seukuran Data Karyawan) -->
                                <td class="px-5 py-4.5 font-semibold text-carbon-black-900 group-hover:text-black-forest-700" data-field="nama_karyawan">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-hunter-green-100 text-hunter-green-800 flex items-center justify-center font-black text-xs shrink-0">
                                            {{ strtoupper(substr($karyawan['nama'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-base text-carbon-black-900">{{ $karyawan['nama'] }}</div>
                                            <div class="mt-1">
                                                @php
                                                    $bNama = $lookup['nama_bank'] ?? ($karyawan['nama_bank'] ?? 'Tidak Memiliki');
                                                    $bRek = $lookup['no_rekening'] ?? ($karyawan['no_rekening'] ?? '-');
                                                    $isTf = !empty($bNama) && strtolower($bNama) !== 'tidak memiliki' && !empty($bRek) && $bRek !== '-';
                                                @endphp
                                                @if($isTf)
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-hunter-green-700 bg-hunter-green-50 px-2 py-0.5 rounded border border-hunter-green-200" title="Transfer Bank: {{ $bNama }} ({{ $bRek }})">
                                                        <x-tabler-building-bank class="w-3 h-3 text-hunter-green-600 shrink-0" />
                                                        <span>Transfer</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-carbon-black-600 bg-carbon-black-100 px-2 py-0.5 rounded border border-carbon-black-200" title="Pembayaran Tunai">
                                                        <x-tabler-cash class="w-3 h-3 text-carbon-black-500 shrink-0" />
                                                        <span>Tunai</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Bagian (Badge standard data karyawan) -->
                                <td class="px-5 py-4.5 text-center font-semibold text-carbon-black-700" data-field="bagian">
                                    <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-carbon-black-100 text-carbon-black-800 border border-carbon-black-200 shadow-2xs">
                                        {{ $lookup['bagian'] ?? ($karyawan['bagian'] ?? '-') }}
                                    </span>
                                </td>

                                <!-- Hari Kerja & Lembur (text-base font-semibold) -->
                                <td class="px-5 py-4.5 text-center" data-field="hari_lembur">
                                    <div class="inline-flex items-center gap-1.5 text-base font-semibold">
                                        <span class="text-carbon-black-800" data-field="jumlah_hari_kerja">{{ $lookup['hari_kerja'] ?? 0 }} hari</span>
                                        <span class="text-carbon-black-300">/</span>
                                        <span class="text-amber-700" data-field="jam_lembur_badge">{{ $lookup['jam_lembur'] ?? 0 }} jam lembur</span>
                                    </div>
                                </td>

                                <!-- Penyesuaian (Bonus & Potongan Pills) -->
                                <td class="px-5 py-4.5 text-center" data-field="penyesuaian_badges">
                                    <div class="inline-flex items-center gap-1.5 text-xs font-bold">
                                        <span class="px-2.5 py-1 rounded bg-hunter-green-50 text-hunter-green-700 border border-hunter-green-200" data-field="bonus_badge">
                                            + Bonus
                                        </span>
                                        <span class="px-2.5 py-1 rounded bg-rose-50 text-rose-700 border border-rose-200" data-field="potongan_badge">
                                            - Potongan
                                        </span>
                                    </div>
                                </td>

                                <!-- Total Gaji Bersih (text-base font-black) -->
                                <td class="px-5 py-4.5 text-right font-black text-carbon-black-900 text-base" data-field="total_gaji_bersih">
                                    Rp 0
                                </td>

                                <!-- Status Pembayaran -->
                                <td class="px-4 py-4.5 text-center whitespace-nowrap" data-field="status_pembayaran">
                                    <span class="inline-flex items-center justify-center whitespace-nowrap rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-amber-700 shadow-2xs">
                                        Belum Dibayar
                                    </span>
                                </td>

                                <!-- Aksi (Button standard data karyawan) -->
                                <td class="px-5 py-4.5 text-center" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-action="process" data-karyawan-id="{{ $karyawan['id'] }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-hunter-green-200 bg-hunter-green-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-hunter-green-700 transition-colors hover:bg-hunter-green-100 shadow-2xs" title="Tandai Bayar">
                                            <x-tabler-check class="h-4 w-4" />
                                            <span>Bayar</span>
                                        </button>
                                        <button type="button" data-action="open-drawer" data-karyawan-id="{{ $karyawan['id'] }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-carbon-black-200 bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-carbon-black-700 transition-colors hover:bg-carbon-black-50 hover:text-hunter-green-700 shadow-2xs" title="Rincian Detail">
                                            <x-tabler-layout-sidebar-right-collapse class="h-4 w-4 text-hunter-green-600" />
                                            <span>Rincian</span>
                                        </button>
                                        <button type="button" data-action="slip" data-karyawan-id="{{ $karyawan['id'] }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-amber-700 transition-colors hover:bg-amber-100 shadow-2xs" title="Cetak Slip">
                                            <x-tabler-printer class="h-4 w-4" />
                                            <span>Slip</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm font-bold text-carbon-black-400">
                                    Tidak ada karyawan aktif untuk diproses.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="payroll-empty-filter-row" class="hidden">
                            <td colspan="8" class="px-6 py-8 text-center text-sm font-bold text-carbon-black-400">
                                Tidak ada data penggajian yang sesuai dengan pencarian/filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <div class="bg-carbon-black-50 border-t border-carbon-black-100 px-6 py-4 flex items-center justify-between">
                <p id="payroll-pagination-info" class="text-sm font-medium text-carbon-black-600">Menampilkan 0 data</p>
                <div id="payroll-pagination" class="flex items-center justify-end gap-1"></div>
            </div>
        </section>
    </div>

    <!-- 6. SLIDE-OVER DRAWER FOR EMPLOYEE DETAIL (Scaled typography) -->
    <div id="payroll-drawer-backdrop" class="fixed inset-0 z-50 bg-carbon-black-900/40 backdrop-blur-xs transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closePayrollDrawer()"></div>
    
    <div id="payroll-drawer" class="fixed inset-y-0 right-0 z-50 w-full max-w-2xl bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <!-- Drawer Header -->
        <div class="px-6 py-4.5 border-b border-carbon-black-100 flex items-center justify-between bg-carbon-black-50">
            <div class="flex items-center space-x-3">
                <span class="p-2 bg-hunter-green-100 text-hunter-green-700 rounded-lg">
                    <x-tabler-receipt class="w-5 h-5" />
                </span>
                <div>
                    <h3 id="detail-employee-name" class="text-lg font-black text-carbon-black-900">Pilih Karyawan</h3>
                    <p id="detail-employee-meta" class="text-xs font-bold text-carbon-black-500 mt-0.5">-</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span id="detail-status-badge" class="inline-flex items-center justify-center whitespace-nowrap rounded-full border border-carbon-black-200 bg-carbon-black-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-carbon-black-500 shadow-2xs">
                    Belum Dibayar
                </span>
                <button type="button" onclick="closePayrollDrawer()" class="p-1.5 rounded-lg text-carbon-black-400 hover:text-carbon-black-800 hover:bg-carbon-black-100 transition-colors">
                    <x-tabler-x class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Drawer Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-5 scrollbar-invisible">
            <!-- Identitas & Parameter Penggajian Grid -->
            <div class="grid grid-cols-2 gap-3.5">
                <div class="p-3.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-400">ID Penggajian</span>
                    <div id="detail-id-penggajian" class="font-black text-base text-carbon-black-900 mt-0.5">-</div>
                </div>
                <div class="p-3.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-400">ID Karyawan</span>
                    <div id="detail-id-karyawan" class="font-black text-base text-carbon-black-900 mt-0.5">-</div>
                </div>
                <div class="p-3.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-400">Periode</span>
                    <div class="font-black text-base text-carbon-black-900 mt-0.5">
                        <span id="detail-periode-bulan">-</span> <span id="detail-periode-tahun">-</span>
                    </div>
                </div>
                <div class="p-3.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-400">Tanggal Bayar</span>
                    <div id="detail-tanggal-pembayaran" class="font-black text-base text-carbon-black-900 mt-0.5">-</div>
                </div>
                <div class="p-3.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-400">Metode Pembayaran</span>
                    <div id="detail-metode-pembayaran" class="font-semibold text-sm text-carbon-black-900 mt-0.5 break-words">-</div>
                </div>
                <div class="p-3.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-400">Basis Perhitungan</span>
                    <div id="detail-basis-perhitungan" class="font-semibold text-sm text-carbon-black-900 mt-0.5">-</div>
                </div>
            </div>

            <!-- Jam Kerja & Lembur -->
            <div class="grid grid-cols-2 gap-3.5">
                <div class="p-4 bg-hunter-green-50/50 rounded-xl border border-hunter-green-200">
                    <span class="text-xs font-black uppercase tracking-wider text-hunter-green-700">Hari Kerja Efektif</span>
                    <div id="detail-hari-kerja" class="text-xl font-black text-hunter-green-900 mt-1">-</div>
                    <div class="text-xs font-semibold text-hunter-green-600 mt-1">Tarif Pokok: <span id="detail-gaji-pokok" class="font-bold">Rp -</span> / <span id="detail-satuan-pokok">Jam</span></div>
                </div>
                <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-200">
                    <span class="text-xs font-black uppercase tracking-wider text-amber-700">Akumulasi Lembur</span>
                    <div id="detail-jumlah-lembur" class="text-xl font-black text-amber-900 mt-1">-</div>
                    <div class="text-xs font-semibold text-amber-600 mt-1">Upah Lembur: <span id="detail-total-lembur" class="font-bold">Rp -</span></div>
                </div>
            </div>

            <!-- Rincian Komponen Gaji (Cards) -->
            <div class="space-y-2">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-black uppercase tracking-wider text-carbon-black-700 whitespace-nowrap">Komponen Pendapatan & Potongan</span>
                    <span class="text-[12.5px] text-carbon-black-400 font-medium normal-case whitespace-nowrap">Klik tombol Detail pada masing-masing</span>
                </div>
                <div id="salary-detail-list" class="grid grid-cols-2 gap-2.5">
                    <!-- Populated by JS -->
                </div>
            </div>

            <!-- Subtotal Bonus & Potongan -->
            <div class="p-4.5 bg-carbon-black-50 rounded-xl border border-carbon-black-100 space-y-2.5 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-carbon-black-600 font-semibold">Total Bonus Tambahan:</span>
                    <span id="detail-total-bonus" class="font-bold text-hunter-green-700">Rp -</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-carbon-black-600 font-semibold">Total Potongan Gaji:</span>
                    <span id="detail-total-potongan" class="font-bold text-rose-600">Rp -</span>
                </div>
                <div class="border-t border-carbon-black-200 pt-2.5 flex justify-between items-center font-bold">
                    <span class="text-carbon-black-700">Total Penghasilan Kotor:</span>
                    <span id="detail-gross-total" class="text-carbon-black-900 font-black">Rp -</span>
                </div>
            </div>

            <!-- Total Gaji Bersih Callout -->
            <div class="bg-gradient-to-r from-black-forest-800 to-hunter-green-800 text-white rounded-xl p-5 shadow-md flex items-center justify-between">
                <div>
                    <span class="text-xs font-black uppercase tracking-wider text-hunter-green-200">Gaji Bersih (Take Home Pay)</span>
                    <div id="detail-net-total" class="text-2xl font-black text-white tracking-tight mt-1">
                        Rp -
                    </div>
                </div>
                <button type="button" onclick="printFromDrawer()" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-lg border border-white/20 transition-all flex items-center gap-1.5 active:scale-95">
                    <x-tabler-printer class="w-4 h-4" />
                    <span>Slip</span>
                </button>
            </div>
        </div>

        <!-- Drawer Footer Actions -->
        <div class="p-4.5 bg-carbon-black-50 border-t border-carbon-black-100 grid grid-cols-3 gap-3">
            <button type="button" id="detail-edit-bonus"
                class="px-3.5 py-3 rounded-lg border border-hunter-green-200 bg-hunter-green-50 text-hunter-green-700 text-xs font-black uppercase tracking-wider hover:bg-hunter-green-100 transition-colors flex items-center justify-center gap-1.5">
                <x-tabler-pencil class="h-4 w-4" />
                <span>Bonus</span>
            </button>
            <button type="button" id="detail-edit-potongan"
                class="px-3.5 py-3 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 text-xs font-black uppercase tracking-wider hover:bg-rose-100 transition-colors flex items-center justify-center gap-1.5">
                <x-tabler-pencil class="h-4 w-4" />
                <span>Potongan</span>
            </button>
            <button type="button" id="detail-save-payroll"
                class="px-3.5 py-3 rounded-lg bg-hunter-green-600 text-white text-xs font-black uppercase tracking-wider shadow-sm hover:bg-hunter-green-700 transition-colors flex items-center justify-center gap-1.5 active:scale-95">
                <x-tabler-check class="h-4 w-4" />
                <span>Bayar</span>
            </button>
        </div>
    </div>

    <!-- 7. Bonus Modal -->
    <div id="bonus-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Isian Bonus Karyawan</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Pilih jenis bonus, lalu isi jumlah atau selisih sesuai ketentuan bonus.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                    onclick="closeBonusModal(false)">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto scrollbar-invisible">
                <div id="bonus-rows" class="space-y-3"></div>
                <button type="button" id="add-bonus-row"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-hunter-green-200 bg-hunter-green-50 text-hunter-green-700 text-xs font-black uppercase tracking-widest hover:bg-hunter-green-100 transition-colors">
                    <x-tabler-plus class="w-4 h-4" />
                    Tambah Bonus
                </button>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Bonus</p>
                    <p class="text-xl font-black text-carbon-black-900">Rp <span id="bonus-modal-total">0</span></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors"
                        onclick="closeBonusModal(false)">Batal</button>
                    <button type="button" id="save-bonus-modal"
                        class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 hover:shadow-lg transition-all active:scale-95">Simpan Bonus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. Potongan Modal -->
    <div id="potongan-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Isian Potongan Gaji</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Pilih jenis potongan dari data master Potongan Gaji.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                    onclick="closePotonganModal(false)">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto scrollbar-invisible">
                <div id="potongan-rows" class="space-y-3"></div>
                <button type="button" id="add-potongan-row"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 text-xs font-black uppercase tracking-widest hover:bg-rose-100 transition-colors">
                    <x-tabler-plus class="w-4 h-4" />
                    Tambah Potongan
                </button>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Potongan</p>
                    <p class="text-xl font-black text-carbon-black-900">Rp <span id="potongan-modal-total">0</span></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors"
                        onclick="closePotonganModal(false)">Batal</button>
                    <button type="button" id="save-potongan-modal"
                        class="px-5 py-2.5 bg-rose-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-rose-700 hover:shadow-lg transition-all active:scale-95">Simpan Potongan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 9. Slip Gaji Modal -->
    <div id="slip-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Slip Gaji Karyawan</h3>
                    <p id="slip-subtitle" class="text-xs font-semibold text-carbon-black-500 mt-1">-</p>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" onclick="printSlip()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-carbon-black-200 text-carbon-black-600 text-xs font-black uppercase tracking-widest rounded-lg shadow-sm hover:border-carbon-black-300 hover:bg-carbon-black-50 hover:text-carbon-black-900 active:scale-95 transition-all">
                        <x-tabler-printer class="w-4 h-4" />
                        Cetak Slip
                    </button>
                    <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                        onclick="closeSlipModal()">
                        <x-tabler-x class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[65vh] overflow-y-auto scrollbar-invisible">
                <div id="slip-detail-list" class="space-y-3"></div>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Gaji Bersih</p>
                    <p class="text-2xl font-black text-carbon-black-900" id="slip-total-net">Rp 0</p>
                </div>
                <button type="button"
                    class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all"
                    onclick="closeSlipModal()">Kembali</button>
            </div>
        </div>
    </div>

    <!-- 10. Component Detail Modal -->
    <div id="component-detail-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 id="component-detail-title"
                        class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Detail Komponen</h3>
                    <p id="component-detail-subtitle" class="text-sm font-semibold text-carbon-black-500 mt-1">-</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                    onclick="closeComponentDetailModal()">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6">
                <div id="component-detail-content" class="space-y-3"></div>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-end rounded-b-xl">
                <button type="button"
                    class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all"
                    onclick="closeComponentDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- 11. Hidden Print Container -->
    <div id="print-container" class="hidden font-sans text-black bg-white" style="max-width: 800px; margin: 0 auto;">
        <table style="width: 100%; border-bottom: 2px solid black; margin-bottom: 10px; padding-bottom: 10px;">
            <tr>
                <td style="vertical-align: top;">
                    <h1 style="font-size: 24px; font-weight: 900; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        SAHABAT TERNAK</h1>
                </td>
                <td style="vertical-align: bottom; text-align: right;">
                    <p style="font-size: 12px; margin: 0;">Tanggal Cetak: <span id="print-tanggal-cetak"></span></p>
                </td>
            </tr>
        </table>

        <h2 style="text-align: center; font-size: 18px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px;">
            SLIP GAJI KARYAWAN</h2>

        <table style="width: 100%; margin-bottom: 20px; font-size: 12px; font-weight: 600;">
            <tr>
                <td style="width: 120px;">NAMA</td>
                <td style="width: 10px;">:</td>
                <td id="print-nama"></td>

                <td style="width: 100px;">PERIODE</td>
                <td style="width: 10px;">:</td>
                <td id="print-periode"></td>
            </tr>
            <tr>
                <td>BAGIAN</td>
                <td>:</td>
                <td id="print-bagian"></td>

                <td style="width: 100px;">METODE</td>
                <td style="width: 10px;">:</td>
                <td id="print-metode"></td>
            </tr>
        </table>

        <table style="width: 100%; border: 2px solid black; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th style="border: 2px solid black; padding: 8px; text-transform: uppercase; letter-spacing: 1px; width: 50%;">
                        PENERIMAAN</th>
                    <th style="border: 2px solid black; padding: 8px; text-transform: uppercase; letter-spacing: 1px; width: 50%;">
                        POTONGAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-right: 2px solid black; padding: 12px; vertical-align: top; height: 150px;">
                        <table style="width: 100%; border-collapse: collapse;" id="print-penerimaan-list"></table>
                    </td>
                    <td style="padding: 12px; vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;" id="print-potongan-list"></table>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid black; font-weight: bold;">
                    <td style="border-right: 2px solid black; padding: 8px;">
                        <table style="width: 100%;">
                            <tr>
                                <td>TOTAL PENERIMAAN</td>
                                <td style="text-align: right;" id="print-total-penerimaan"></td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 8px;">
                        <table style="width: 100%;">
                            <tr>
                                <td>TOTAL POTONGAN</td>
                                <td style="text-align: right;" id="print-total-potongan"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="width: 50%; margin-top: 15px; font-size: 16px; font-weight: bold; border-top: 2px solid black; border-bottom: 2px solid black;">
            <tr>
                <td style="padding: 8px 0; width: 160px; text-transform: uppercase; letter-spacing: 1px;">TAKE HOME PAY</td>
                <td style="padding: 8px 0;">: <span id="print-thp"></span></td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 50px; text-align: center; font-size: 12px; font-weight: 600;">
            <tr>
                <td style="width: 50%;">
                    <p style="margin-bottom: 70px;">Dibuat Oleh,</p>
                    <p style="border-bottom: 1px solid black; width: 180px; margin: 0 auto;"></p>
                    <p style="margin-top: 5px; text-transform: uppercase;">Payroll Admin</p>
                </td>
                <td style="width: 50%;">
                    <p style="margin-bottom: 70px;">Diterima Oleh,</p>
                    <p style="border-bottom: 1px solid black; width: 180px; margin: 0 auto;" id="print-sign-nama"></p>
                    <p style="margin-top: 5px; text-transform: uppercase;">Karyawan</p>
                </td>
            </tr>
        </table>
    </div>

    <style>
        @media print {
            body> :not(#print-container) {
                display: none !important;
            }

            #print-container {
                display: block !important;
                position: static;
                width: 100%;
                margin: 0 auto;
                padding: 10mm;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const karyawanLookup = @json($karyawanLookup ?? []);
                const karyawanList = @json($activeKaryawans ?? []);
                const bonusData = @json($gajiBonus ?? []);
                const potonganData = @json($potonganGaji ?? []);
                const months = @json($months);

                const monthSelect = document.getElementById('periode-bulan-select');
                const yearSelect = document.getElementById('periode-tahun-select');
                const payrollSearch = document.getElementById('payroll-search');
                const payrollBagianFilter = document.getElementById('payroll-bagian-filter');
                const rows = Array.from(document.querySelectorAll('.payroll-row'));
                const payrollTableBody = document.getElementById('payroll-table-body');
                const payrollEmptyFilterRow = document.getElementById('payroll-empty-filter-row');
                const payrollPagination = document.getElementById('payroll-pagination');
                const payrollPaginationInfo = document.getElementById('payroll-pagination-info');
                
                // Drawer Elements
                const drawer = document.getElementById('payroll-drawer');
                const drawerBackdrop = document.getElementById('payroll-drawer-backdrop');
                const detailName = document.getElementById('detail-employee-name');
                const detailMeta = document.getElementById('detail-employee-meta');
                const detailStatusBadge = document.getElementById('detail-status-badge');
                const detailGrossTotal = document.getElementById('detail-gross-total');
                const detailNetTotal = document.getElementById('detail-net-total');
                const salaryDetailList = document.getElementById('salary-detail-list');
                const detailEditBonus = document.getElementById('detail-edit-bonus');
                const detailEditPotongan = document.getElementById('detail-edit-potongan');
                const detailSavePayroll = document.getElementById('detail-save-payroll');
                const detailIdPenggajian = document.getElementById('detail-id-penggajian');
                const detailIdKaryawan = document.getElementById('detail-id-karyawan');
                const detailHariKerja = document.getElementById('detail-hari-kerja');
                const detailJumlahLembur = document.getElementById('detail-jumlah-lembur');
                const detailTanggalPembayaran = document.getElementById('detail-tanggal-pembayaran');
                const detailGajiPokok = document.getElementById('detail-gaji-pokok');
                const detailTotalLembur = document.getElementById('detail-total-lembur');
                const detailTotalBonus = document.getElementById('detail-total-bonus');
                const detailTotalPotongan = document.getElementById('detail-total-potongan');
                const detailPeriodeBulan = document.getElementById('detail-periode-bulan');
                const detailPeriodeTahun = document.getElementById('detail-periode-tahun');
                const detailMetodePembayaran = document.getElementById('detail-metode-pembayaran');
                const detailBasisPerhitungan = document.getElementById('detail-basis-perhitungan');
                const detailSatuanPokok = document.getElementById('detail-satuan-pokok');

                // Metric Cards Elements
                const statTotalGajiBersih = document.getElementById('stat-total-gaji-bersih');
                const statProgresRasio = document.getElementById('stat-progres-rasio');
                const statProgresBar = document.getElementById('stat-progres-bar');
                const statTotalJamLembur = document.getElementById('stat-total-jam-lembur');
                const statTotalNominalLembur = document.getElementById('stat-total-nominal-lembur');
                const statTotalBonus = document.getElementById('stat-total-bonus');
                const statTotalPotongan = document.getElementById('stat-total-potongan');
                const compPokokVal = document.getElementById('comp-pokok-val');
                const compLemburVal = document.getElementById('comp-lembur-val');
                const compBonusVal = document.getElementById('comp-bonus-val');
                const compPotonganVal = document.getElementById('comp-potongan-val');
                const barPokok = document.getElementById('bar-pokok');
                const barLembur = document.getElementById('bar-lembur');
                const barBonus = document.getElementById('bar-bonus');

                // Batch Selection Elements
                const checkSelectAll = document.getElementById('check-select-all');
                const batchBar = document.getElementById('payroll-batch-bar');
                const batchSelectedCount = document.getElementById('batch-selected-count');
                const btnBatchDeselect = document.getElementById('btn-batch-deselect');
                const btnBatchPaySelected = document.getElementById('btn-batch-pay-selected');
                const btnBatchProcessAll = document.getElementById('btn-batch-process-all');

                // Modals
                const bonusModal = document.getElementById('bonus-modal');
                const bonusModalInner = bonusModal.querySelector('div');
                const bonusRows = document.getElementById('bonus-rows');
                const addBonusRowButton = document.getElementById('add-bonus-row');
                const saveBonusModalButton = document.getElementById('save-bonus-modal');
                const bonusModalTotal = document.getElementById('bonus-modal-total');

                const potonganModal = document.getElementById('potongan-modal');
                const potonganModalInner = potonganModal.querySelector('div');
                const potonganRows = document.getElementById('potongan-rows');
                const addPotonganRowButton = document.getElementById('add-potongan-row');
                const savePotonganModalButton = document.getElementById('save-potongan-modal');
                const potonganModalTotal = document.getElementById('potongan-modal-total');

                const slipModal = document.getElementById('slip-modal');
                const slipModalInner = slipModal.querySelector('div');
                const slipSubtitle = document.getElementById('slip-subtitle');
                const slipDetailList = document.getElementById('slip-detail-list');
                const slipTotalNet = document.getElementById('slip-total-net');

                const componentDetailModal = document.getElementById('component-detail-modal');
                const componentDetailModalInner = componentDetailModal.querySelector('div');
                const componentDetailTitle = document.getElementById('component-detail-title');
                const componentDetailSubtitle = document.getElementById('component-detail-subtitle');
                const componentDetailContent = document.getElementById('component-detail-content');

                const payrollState = {};
                const payrollRowsPerPage = 10;
                let payrollCurrentPage = 1;
                let activeKaryawanId = rows[0]?.dataset.karyawanId || null;
                let editingType = null;

                function formatRupiah(value) {
                    return 'Rp ' + Math.round(Number(value || 0)).toLocaleString('id-ID');
                }

                function formatNumber(value) {
                    return Number(value || 0).toLocaleString('id-ID');
                }

                function formatDecimal(value) {
                    return Number(value || 0).toLocaleString('id-ID', {
                        minimumFractionDigits: Number(value || 0) % 1 === 0 ? 0 : 1,
                        maximumFractionDigits: 2,
                    });
                }

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function statusBadgeClasses(status) {
                    const normalized = String(status || '').toLowerCase();
                    if (normalized === 'dibayar' || normalized === 'selesai') {
                        return 'border-emerald-200 bg-emerald-50 text-emerald-700';
                    }
                    if (normalized === 'diproses') {
                        return 'border-blue-200 bg-blue-50 text-blue-700';
                    }
                    return 'border-amber-200 bg-amber-50 text-amber-700';
                }

                function statusBadge(status) {
                    const classes = statusBadgeClasses(status);
                    return `<span class="inline-flex items-center justify-center whitespace-nowrap rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest shadow-2xs ${classes}">${escapeHtml(status)}</span>`;
                }

                function statusPriority(status) {
                    const normalized = String(status || '').toLowerCase();
                    if (normalized === 'dibayar' || normalized === 'selesai') return 2;
                    if (normalized === 'diproses') return 1;
                    return 0;
                }

                function getEmployee(id) {
                    return karyawanList.find(item => String(item.id) === String(id)) || {};
                }

                function getSelectedPeriodKey() {
                    const month = String((monthSelect?.selectedIndex ?? new Date().getMonth()) + 1).padStart(2, '0');
                    const year = yearSelect?.value || new Date().getFullYear();
                    return `${year}-${month}`;
                }

                function getSelectedMonthName() {
                    return monthSelect?.value || months[new Date().getMonth()];
                }

                function getSelectedYear() {
                    return yearSelect?.value || new Date().getFullYear();
                }

                function buildInitialState(id, index) {
                    const data = karyawanLookup[id] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0, details: [] };
                    const totalPokok = Number(data.total_gaji_pokok || 0);
                    const totalLembur = Number(data.total_lembur || 0);

                    return {
                        id_penggajian: `PGJ-${String(index + 1).padStart(3, '0')}`,
                        id_karyawan: id,
                        periode_bulan: getSelectedMonthName(),
                        periode_tahun: getSelectedYear(),
                        jumlah_hari_kerja: Number(data.hari_kerja || 0),
                        jumlah_lembur: Number(data.jam_lembur || 0),
                        gaji_pokok: totalPokok,
                        total_lembur: totalLembur,
                        total_bonus: Number(periodBonus.total || 0),
                        total_potongan: 0,
                        total_gaji_kotor: totalPokok + totalLembur + Number(periodBonus.total || 0),
                        total_gaji_bersih: totalPokok + totalLembur + Number(periodBonus.total || 0),
                        tanggal_pembayaran: '-',
                        status_pembayaran: 'Belum Dibayar',
                        bonusDetails: periodBonus.details || [],
                        potonganDetails: [],
                        manualBonusRows: [],
                        manualPotonganRows: [],
                    };
                }

                function ensureState(id) {
                    if (!payrollState[id]) {
                        const row = rows.find(item => String(item.dataset.karyawanId) === String(id));
                        payrollState[id] = buildInitialState(id, Number(row?.dataset.rowIndex || 1) - 1);
                    }
                    return payrollState[id];
                }

                function recalculateState(id) {
                    const state = ensureState(id);
                    const data = karyawanLookup[id] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0, details: [] };

                    // Rebuild Bonus
                    let manualBonusTotal = 0;
                    const manualBonusDetails = [];
                    state.manualBonusRows.forEach(item => {
                        const bonus = bonusData.find(b => String(b.id) === String(item.id));
                        if (bonus) {
                            const amount = item.usage * Number(bonus.tarif || 0);
                            manualBonusTotal += amount;
                            const isFixed = String(bonus.satuan || '').toLowerCase() === 'kali';
                            manualBonusDetails.push({
                                title: bonus.nama,
                                description: isFixed ? `Dihitung 1 kali sebesar ${formatRupiah(bonus.tarif)}` : `Sejumlah ${formatUsageValue(item.usage, bonus.satuan)} x ${formatRupiah(bonus.tarif)}`,
                                amount
                            });
                        }
                    });
                    state.total_bonus = Number(periodBonus.total || 0) + manualBonusTotal;
                    state.bonusDetails = [...(periodBonus.details || []), ...manualBonusDetails];

                    // Rebuild Potongan
                    let automaticPotonganTotal = 0;
                    const automaticPotonganDetails = [];
                    potonganData.forEach(p => {
                        if (String(p.tipe_potongan || '').toLowerCase() === 'tetap') {
                            automaticPotonganTotal += Number(p.tarif || 0);
                            automaticPotonganDetails.push({
                                title: p.nama,
                                description: `Potongan tetap (Otomatis)`,
                                amount: Number(p.tarif || 0)
                            });
                        } else if (String(p.tipe_potongan || '').toLowerCase() === 'persentase') {
                            const percentage = Number(p.tarif || 0);
                            const amount = (percentage / 100) * Number(state.gaji_pokok || 0);
                            automaticPotonganTotal += amount;
                            automaticPotonganDetails.push({
                                title: p.nama,
                                description: `Potongan persentase ${percentage}% dari Gaji Pokok (Otomatis)`,
                                amount: amount
                            });
                        }
                    });

                    const periodWastage = data.wastage_deductions_by_period?.[getSelectedPeriodKey()] || { amount: 0, details: [] };
                    if (periodWastage.amount > 0) {
                        automaticPotonganTotal += periodWastage.amount;
                        periodWastage.details.forEach(detail => {
                            automaticPotonganDetails.push({
                                title: 'Potongan Wastage Kemasan',
                                description: `${detail.description} (Piket harian tgl ${detail.date})`,
                                amount: detail.amount
                            });
                        });
                    }

                    let manualPotonganTotal = 0;
                    const manualPotonganDetails = [];
                    state.manualPotonganRows.forEach(item => {
                        const potongan = potonganData.find(p => String(p.id) === String(item.id));
                        if (potongan) {
                            const amount = item.jumlah * Number(potongan.tarif || 0);
                            manualPotonganTotal += amount;
                            manualPotonganDetails.push({
                                title: potongan.nama,
                                description: `${formatNumber(item.jumlah)} kejadian x ${formatRupiah(potongan.tarif)}`,
                                amount
                            });
                        }
                    });
                    state.total_potongan = automaticPotonganTotal + manualPotonganTotal;
                    state.potonganDetails = [...automaticPotonganDetails, ...manualPotonganDetails];

                    state.periode_bulan = getSelectedMonthName();
                    state.periode_tahun = getSelectedYear();
                    if (String(state.status_pembayaran || '').toLowerCase() === 'selesai') {
                        state.status_pembayaran = 'Dibayar';
                    }
                    state.total_gaji_kotor = Number(state.gaji_pokok) + Number(state.total_lembur) + Number(state.total_bonus);
                    state.total_gaji_bersih = Math.max(0, Number(state.total_gaji_kotor) - Number(state.total_potongan));
                    return state;
                }

                function updatePeriodBonus(id) {
                    recalculateState(id);
                }

                function setCell(row, field, value) {
                    const cell = row.querySelector(`[data-field="${field}"]`);
                    if (cell) cell.innerHTML = value;
                }

                function renderTableRow(id) {
                    const state = recalculateState(id);
                    const row = rows.find(item => String(item.dataset.karyawanId) === String(id));
                    if (!row) return;

                    const data = karyawanLookup[id] || {};
                    setCell(row, 'bagian', `<span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-carbon-black-100 text-carbon-black-800 border border-carbon-black-200 shadow-2xs">${escapeHtml(data.bagian || getEmployee(id).bagian || '-')}</span>`);
                    setCell(row, 'total_gaji_bersih', formatRupiah(state.total_gaji_bersih));
                    setCell(row, 'status_pembayaran', statusBadge(state.status_pembayaran));

                    const hariKerjaEl = row.querySelector('[data-field="jumlah_hari_kerja"]');
                    if (hariKerjaEl) hariKerjaEl.textContent = `${formatNumber(state.jumlah_hari_kerja)} hari`;

                    const lemburBadge = row.querySelector('[data-field="jam_lembur_badge"]');
                    if (lemburBadge) lemburBadge.textContent = data.lembur_aktif === false ? '-' : `${formatDecimal(state.jumlah_lembur)} jam lembur`;

                    const bonusBadge = row.querySelector('[data-field="bonus_badge"]');
                    if (bonusBadge) {
                        bonusBadge.textContent = state.total_bonus > 0 ? `+${formatRupiah(state.total_bonus)}` : 'Bonus: Rp 0';
                        bonusBadge.className = state.total_bonus > 0
                            ? 'px-2.5 py-1 rounded bg-hunter-green-100 text-hunter-green-800 border border-hunter-green-300 font-bold'
                            : 'px-2.5 py-1 rounded bg-carbon-black-100 text-carbon-black-500 font-medium';
                    }

                    const potonganBadge = row.querySelector('[data-field="potongan_badge"]');
                    if (potonganBadge) {
                        potonganBadge.textContent = state.total_potongan > 0 ? `-${formatRupiah(state.total_potongan)}` : 'Potongan: Rp 0';
                        potonganBadge.className = state.total_potongan > 0
                            ? 'px-2.5 py-1 rounded bg-rose-100 text-rose-800 border border-rose-300 font-bold'
                            : 'px-2.5 py-1 rounded bg-carbon-black-100 text-carbon-black-500 font-medium';
                    }

                    const processButton = row.querySelector('[data-action="process"]');
                    if (processButton) {
                        const paid = String(state.status_pembayaran || '').toLowerCase() === 'dibayar';
                        processButton.disabled = paid;
                        processButton.className = paid
                            ? 'inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 opacity-80 cursor-default shadow-2xs'
                            : 'inline-flex items-center gap-1.5 rounded-lg border border-hunter-green-200 bg-hunter-green-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-hunter-green-700 transition-colors hover:bg-hunter-green-100 shadow-2xs';
                        processButton.querySelector('span').textContent = paid ? 'Lunas' : 'Bayar';
                    }
                }

                // Update Executive Summary Metrics Cards & Donut Breakdown
                function updatePayrollSummaryCards() {
                    let sumNet = 0;
                    let sumPokok = 0;
                    let sumLembur = 0;
                    let sumBonus = 0;
                    let sumPotongan = 0;
                    let sumLemburHours = 0;
                    let paidCount = 0;
                    const totalEmployees = rows.length;

                    rows.forEach(row => {
                        const id = row.dataset.karyawanId;
                        const state = recalculateState(id);
                        sumNet += Number(state.total_gaji_bersih || 0);
                        sumPokok += Number(state.gaji_pokok || 0);
                        sumLembur += Number(state.total_lembur || 0);
                        sumBonus += Number(state.total_bonus || 0);
                        sumPotongan += Number(state.total_potongan || 0);
                        sumLemburHours += Number(state.jumlah_lembur || 0);
                        if (String(state.status_pembayaran || '').toLowerCase() === 'dibayar') {
                            paidCount++;
                        }
                    });

                    // Update DOM cards
                    if (statTotalGajiBersih) statTotalGajiBersih.textContent = formatRupiah(sumNet);
                    if (statProgresRasio) statProgresRasio.innerHTML = `${paidCount} / ${totalEmployees} <span class="text-xs font-bold text-carbon-black-400">Lunas</span>`;
                    
                    const progressPercent = totalEmployees > 0 ? (paidCount / totalEmployees) * 100 : 0;
                    if (statProgresBar) statProgresBar.style.width = progressPercent + '%';

                    if (statTotalJamLembur) statTotalJamLembur.textContent = formatDecimal(sumLemburHours) + ' Jam';
                    if (statTotalNominalLembur) statTotalNominalLembur.textContent = formatRupiah(sumLembur) + ' Upah Lembur';

                    if (statTotalBonus) statTotalBonus.textContent = '+ ' + formatRupiah(sumBonus);
                    if (statTotalPotongan) statTotalPotongan.textContent = '- ' + formatRupiah(sumPotongan);

                    // Update Breakdown Values
                    if (compPokokVal) compPokokVal.textContent = formatRupiah(sumPokok);
                    if (compLemburVal) compLemburVal.textContent = formatRupiah(sumLembur);
                    if (compBonusVal) compBonusVal.textContent = formatRupiah(sumBonus);
                    if (compPotonganVal) compPotonganVal.textContent = formatRupiah(sumPotongan);

                    // Progress bar proportions
                    const totalGross = (sumPokok + sumLembur + sumBonus) || 1;
                    if (barPokok) barPokok.style.width = Math.round((sumPokok / totalGross) * 100) + '%';
                    if (barLembur) barLembur.style.width = Math.round((sumLembur / totalGross) * 100) + '%';
                    if (barBonus) barBonus.style.width = Math.round((sumBonus / totalGross) * 100) + '%';
                }

                function componentCard(title, tone = 'neutral', detailType = '') {
                    return `
                        <div class="flex items-center justify-between gap-2 rounded-xl border border-carbon-black-100 bg-white px-3.5 py-3 shadow-2xs h-full">
                            <p class="text-sm font-bold text-carbon-black-800">${escapeHtml(title)}</p>
                            <button type="button" data-component-detail="${detailType}" class="inline-flex shrink-0 items-center gap-1 rounded-md border border-carbon-black-200 bg-carbon-black-50 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-carbon-black-600 transition-colors hover:border-hunter-green-300 hover:bg-hunter-green-50 hover:text-hunter-green-700" title="Detail ${escapeHtml(title)}">
                                Detail
                            </button>
                        </div>
                    `;
                }

                function renderDetail(id) {
                    const state = recalculateState(id);
                    const data = karyawanLookup[id] || {};
                    const employee = getEmployee(id);
                    const satuanGajiPokok = data.satuan_gaji_pokok || 'Jam';
                    const hariKerja = Number(data.hari_kerja || 0);
                    const tarifPokok = Number(data.gaji_pokok || 0);

                    detailName.textContent = employee.nama || data.nama || 'Karyawan';
                    detailMeta.textContent = `${data.bagian || employee.bagian || '-'} • Periode ${state.periode_bulan} ${state.periode_tahun}`;
                    detailStatusBadge.textContent = state.status_pembayaran;
                    detailStatusBadge.className = `inline-flex items-center justify-center whitespace-nowrap rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest shadow-2xs ${statusBadgeClasses(state.status_pembayaran)}`;
                    detailGrossTotal.textContent = formatRupiah(state.total_gaji_kotor);
                    detailNetTotal.textContent = formatRupiah(state.total_gaji_bersih);
                    detailIdPenggajian.textContent = state.id_penggajian;
                    detailIdKaryawan.textContent = state.id_karyawan;
                    detailHariKerja.textContent = `${formatNumber(state.jumlah_hari_kerja)} hari`;
                    detailJumlahLembur.textContent = data.lembur_aktif === false ? '-' : `${formatDecimal(state.jumlah_lembur)} jam`;
                    detailTanggalPembayaran.textContent = state.tanggal_pembayaran;
                    detailGajiPokok.textContent = formatRupiah(state.gaji_pokok);
                    detailTotalLembur.textContent = formatRupiah(state.total_lembur);
                    detailTotalBonus.textContent = formatRupiah(state.total_bonus);
                    detailTotalPotongan.textContent = formatRupiah(state.total_potongan);
                    detailPeriodeBulan.textContent = state.periode_bulan;
                    detailPeriodeTahun.textContent = state.periode_tahun;

                    if (detailMetodePembayaran) {
                        const isTransfer = data.metode_pembayaran === 'Transfer Bank';
                        if (isTransfer) {
                            detailMetodePembayaran.innerHTML = `
                                <div class="font-bold text-hunter-green-700">Transfer Bank</div>
                                <div class="text-xs font-semibold text-carbon-black-600 mt-0.5">${escapeHtml(data.nama_bank || '-')} • ${escapeHtml(data.no_rekening || '-')}</div>
                            `;
                        } else {
                            detailMetodePembayaran.innerHTML = `
                                <div class="font-bold text-carbon-black-800">Tunai</div>
                            `;
                        }
                    }
                    if (detailBasisPerhitungan) {
                        detailBasisPerhitungan.textContent = data.satuan_gaji_pokok === 'Hari'
                            ? 'Harian (Per Hari Kerja)'
                            : 'Per Jam (Jam Reguler & Lembur)';
                    }
                    if (detailSatuanPokok) {
                        detailSatuanPokok.textContent = data.satuan_gaji_pokok === 'Hari' ? 'Hari' : 'Jam';
                    }

                    const isPaid = String(state.status_pembayaran || '').toLowerCase() === 'dibayar';
                    detailEditBonus.disabled = isPaid;
                    detailEditPotongan.disabled = isPaid;
                    if (detailSavePayroll) {
                        detailSavePayroll.disabled = isPaid;
                        detailSavePayroll.className = isPaid
                            ? 'px-4 py-3 rounded-lg bg-emerald-600 text-white text-xs font-black uppercase tracking-wider opacity-70 cursor-not-allowed flex items-center justify-center gap-1.5'
                            : 'px-4 py-3 rounded-lg bg-hunter-green-600 text-white text-xs font-black uppercase tracking-wider shadow-sm hover:bg-hunter-green-700 transition-colors flex items-center justify-center gap-1.5 active:scale-95';
                        detailSavePayroll.querySelector('span').textContent = isPaid ? 'Sudah Lunas' : 'Tandai Lunas';
                    }

                    const details = [
                        componentCard('Gaji Pokok', 'neutral', 'gaji_pokok'),
                        componentCard('Lembur', 'neutral', 'lembur'),
                        componentCard('Bonus', 'neutral', 'bonus'),
                        componentCard('Potongan', 'deduction', 'potongan')
                    ];

                    salaryDetailList.innerHTML = details.join('');
                }

                // Drawer Controls
                window.openPayrollDrawer = function(id) {
                    if (id) {
                        selectRow(id);
                    }
                    drawerBackdrop.classList.remove('pointer-events-none', 'opacity-0');
                    drawerBackdrop.classList.add('opacity-100');
                    drawer.classList.remove('translate-x-full');
                    drawer.classList.add('translate-x-0');
                }

                window.closePayrollDrawer = function() {
                    drawerBackdrop.classList.add('opacity-0', 'pointer-events-none');
                    drawerBackdrop.classList.remove('opacity-100');
                    drawer.classList.add('translate-x-full');
                    drawer.classList.remove('translate-x-0');
                }

                window.printFromDrawer = function() {
                    if (activeKaryawanId) {
                        openSlipModal(activeKaryawanId);
                    }
                }

                function componentDetailItem(title, description, amount, tone = 'neutral') {
                    const amountClass = tone === 'deduction' ? 'text-rose-600' : 'text-hunter-green-700';
                    const prefix = tone === 'deduction' && Number(amount) > 0 ? '- ' : '';

                    return `
                        <div class="flex items-start justify-between gap-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3.5">
                            <div>
                                <p class="text-sm font-bold text-carbon-black-800">${escapeHtml(title)}</p>
                                <p class="mt-1 text-xs font-semibold text-carbon-black-500">${escapeHtml(description)}</p>
                            </div>
                            <p class="shrink-0 text-right text-sm font-bold ${amountClass}">${prefix}${formatRupiah(amount)}</p>
                        </div>
                    `;
                }

                function openComponentDetailModal(type) {
                    if (!activeKaryawanId) return;

                    const state = recalculateState(activeKaryawanId);
                    const data = karyawanLookup[activeKaryawanId] || {};
                    const employee = getEmployee(activeKaryawanId);
                    const normalizedType = String(type || '').toLowerCase();
                    let title = 'Detail Komponen';
                    let items = [];

                    if (normalizedType === 'gaji_pokok') {
                        title = 'Detail Gaji Pokok';
                        const satuanGajiPokok = data.satuan_gaji_pokok || 'Jam';
                        const hariKerja = Number(data.hari_kerja || 0);
                        const tarifPokok = Number(data.gaji_pokok || 0);
                        const gajiPokokDetail = satuanGajiPokok === 'Hari'
                            ? `${formatNumber(hariKerja)} hari x ${formatRupiah(tarifPokok)}`
                            : `${formatNumber(hariKerja)} hari x 7 jam x ${formatRupiah(tarifPokok)}`;
                        items = [
                            componentDetailItem('Gaji Pokok', gajiPokokDetail, state.gaji_pokok)
                        ];
                    } else if (normalizedType === 'lembur') {
                        title = 'Detail Lembur';
                        items = [
                            componentDetailItem(
                                data.lembur_aktif === false ? 'Status Lembur' : 'Jam Lembur',
                                data.lembur_aktif === false ? 'Tidak berlaku untuk bagian Peternakan.' : `${formatDecimal(state.jumlah_lembur)} jam x ${formatRupiah(data.tarif_lembur || 0)}`,
                                state.total_lembur
                            ),
                        ];
                    } else if (normalizedType === 'bonus') {
                        title = 'Detail Bonus';
                        items = state.bonusDetails.length > 0
                            ? state.bonusDetails.map(item => componentDetailItem(item.title, item.description, item.amount))
                            : [componentDetailItem('Bonus', 'Tidak ada bonus yang dihitung.', 0)];
                    } else if (normalizedType === 'potongan') {
                        title = 'Detail Potongan';
                        items = state.potonganDetails.length > 0
                            ? state.potonganDetails.map(item => componentDetailItem(item.title, item.description, item.amount, 'deduction'))
                            : [componentDetailItem('Potongan', 'Tidak ada potongan yang dihitung.', 0, 'deduction')];
                    }

                    componentDetailTitle.textContent = title;
                    componentDetailSubtitle.textContent = `${employee.nama || data.nama || 'Karyawan'} - ${state.periode_bulan} ${state.periode_tahun}`;
                    componentDetailContent.innerHTML = items.join('');
                    componentDetailModal.classList.remove('hidden');
                    componentDetailModal.classList.add('flex');
                    setTimeout(() => {
                        componentDetailModal.classList.remove('opacity-0');
                        componentDetailModalInner.classList.remove('scale-95');
                        componentDetailModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.closeComponentDetailModal = function () {
                    componentDetailModal.classList.add('opacity-0');
                    componentDetailModalInner.classList.remove('scale-100');
                    componentDetailModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        componentDetailModal.classList.add('hidden');
                        componentDetailModal.classList.remove('flex');
                    }, 300);
                }

                function renderSlip(id) {
                    const state = recalculateState(id);
                    const data = karyawanLookup[id] || {};
                    const employee = getEmployee(id);

                    const bonusItemsHTML = state.bonusDetails.length > 0
                        ? state.bonusDetails.map(item => `
                            <div class="px-5 py-4 flex justify-between items-start">
                                <div>
                                    <p class="text-base font-semibold text-carbon-black-800">${escapeHtml(item.title)}</p>
                                    <p class="text-xs font-semibold text-carbon-black-500 mt-0.5">${escapeHtml(item.description)}</p>
                                </div>
                                <p class="text-base font-bold text-hunter-green-700">${formatRupiah(item.amount)}</p>
                            </div>
                        `).join('')
                        : `
                            <div class="px-5 py-4 flex justify-between items-center">
                                <p class="text-base font-semibold text-carbon-black-400">Bonus</p>
                                <p class="text-base font-semibold text-carbon-black-400">Rp 0</p>
                            </div>
                        `;

                    const potonganItemsHTML = state.potonganDetails.length > 0
                        ? state.potonganDetails.map(item => `
                            <div class="px-5 py-4 flex justify-between items-start">
                                <div>
                                    <p class="text-base font-semibold text-carbon-black-800">${escapeHtml(item.title)}</p>
                                    <p class="text-xs font-semibold text-carbon-black-500 mt-0.5">${escapeHtml(item.description)}</p>
                                </div>
                                <p class="text-base font-bold text-rose-600">- ${formatRupiah(item.amount)}</p>
                            </div>
                        `).join('')
                        : `
                            <div class="px-5 py-4 flex justify-between items-center">
                                <p class="text-base font-semibold text-carbon-black-400">Potongan</p>
                                <p class="text-base font-semibold text-carbon-black-400">Rp 0</p>
                            </div>
                        `;

                    const html = `
                        <div class="space-y-4">
                            <div class="bg-carbon-black-50 rounded-xl border border-carbon-black-100 p-4.5 grid grid-cols-2 gap-y-3.5 gap-x-6 text-sm">
                                <div>
                                    <p class="text-xs font-bold uppercase text-carbon-black-400 tracking-wider">ID Penggajian</p>
                                    <p class="text-base font-semibold text-carbon-black-900 mt-0.5">${escapeHtml(state.id_penggajian)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase text-carbon-black-400 tracking-wider">ID Karyawan</p>
                                    <p class="text-base font-semibold text-carbon-black-900 mt-0.5">${escapeHtml(state.id_karyawan)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase text-carbon-black-400 tracking-wider">Periode</p>
                                    <p class="text-base font-semibold text-carbon-black-900 mt-0.5">${escapeHtml(state.periode_bulan)} ${escapeHtml(state.periode_tahun)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase text-carbon-black-400 tracking-wider">Status Pembayaran</p>
                                    <div class="mt-0.5">${statusBadge(state.status_pembayaran)}</div>
                                </div>
                                <div class="col-span-2 pt-2.5 border-t border-carbon-black-200 flex justify-between items-center text-xs">
                                    <span class="font-bold text-carbon-black-500 uppercase tracking-wider">Metode Pembayaran:</span>
                                    <span class="font-semibold text-sm text-carbon-black-800">${escapeHtml(data.metode_pembayaran || 'Tunai')}${data.metode_pembayaran === 'Transfer Bank' ? ` (${escapeHtml(data.nama_bank)} - ${escapeHtml(data.no_rekening)})` : ''}</span>
                                </div>
                            </div>

                            <div class="border border-carbon-black-100 rounded-xl overflow-hidden shadow-2xs">
                                <div class="bg-carbon-black-50 px-5 py-3.5 border-b border-carbon-black-100 flex items-center justify-between">
                                    <h4 class="text-xs font-black uppercase tracking-wider text-hunter-green-700">Penerimaan</h4>
                                    <p class="text-sm font-black text-hunter-green-700">${formatRupiah(Number(state.gaji_pokok) + Number(state.total_lembur) + Number(state.total_bonus))}</p>
                                </div>
                                <div class="divide-y divide-carbon-black-50 bg-white text-sm">
                                    <div class="px-5 py-3.5 flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-carbon-black-800">Gaji Pokok</p>
                                            <p class="text-xs font-semibold text-carbon-black-500 mt-0.5">${formatNumber(state.jumlah_hari_kerja)} hari kerja</p>
                                        </div>
                                        <p class="font-bold text-hunter-green-700">${formatRupiah(state.gaji_pokok)}</p>
                                    </div>

                                    <div class="px-5 py-3.5 flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-carbon-black-800">Lembur</p>
                                            <p class="text-xs font-semibold text-carbon-black-500 mt-0.5">${data.lembur_aktif === false ? 'Tidak berlaku' : `${formatDecimal(state.jumlah_lembur)} jam lembur`}</p>
                                        </div>
                                        <p class="font-bold text-hunter-green-700">${formatRupiah(state.total_lembur)}</p>
                                    </div>

                                    ${bonusItemsHTML}
                                </div>

                                <div class="bg-carbon-black-50 px-5 py-3.5 border-y border-carbon-black-100 flex items-center justify-between">
                                    <h4 class="text-xs font-black uppercase tracking-wider text-rose-700">Potongan</h4>
                                    <p class="text-sm font-black text-rose-700">- ${formatRupiah(state.total_potongan)}</p>
                                </div>
                                <div class="divide-y divide-carbon-black-50 bg-white text-sm">
                                    ${potonganItemsHTML}
                                </div>
                            </div>
                        </div>
                    `;

                    slipSubtitle.textContent = `${state.id_penggajian} • ${employee.nama || data.nama || 'Karyawan'} • ${data.bagian || employee.bagian || '-'}`;
                    slipDetailList.innerHTML = html;
                    slipTotalNet.textContent = formatRupiah(state.total_gaji_bersih);

                    // Populate Print Template
                    const totalPenerimaan = Number(state.gaji_pokok) + Number(state.total_lembur) + Number(state.total_bonus);
                    document.getElementById('print-tanggal-cetak').textContent = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                    document.getElementById('print-nama').textContent = employee.nama || data.nama || 'Karyawan';
                    document.getElementById('print-bagian').textContent = data.bagian || employee.bagian || '-';
                    document.getElementById('print-periode').textContent = `${state.periode_bulan} ${state.periode_tahun}`;
                    document.getElementById('print-sign-nama').textContent = employee.nama || data.nama || 'Karyawan';
                    const printMetode = document.getElementById('print-metode');
                    if (printMetode) {
                        const metodePembayaran = data.metode_pembayaran || (data.nama_bank && data.nama_bank !== 'Tidak Memiliki' && data.no_rekening && data.no_rekening !== '-' ? 'Transfer Bank' : 'Tunai');
                        printMetode.textContent = metodePembayaran === 'Transfer Bank'
                            ? `${metodePembayaran} (${data.nama_bank || '-'} - ${data.no_rekening || '-'})`
                            : 'Tunai';
                    }

                    let printPenerimaanHTML = `
                        <tr><td style="padding: 4px 0;">Gaji Pokok</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(state.gaji_pokok)}</td></tr>
                        <tr><td style="padding: 4px 0;">Lembur</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(state.total_lembur)}</td></tr>
                    `;
                    if (state.bonusDetails.length > 0) {
                        state.bonusDetails.forEach(item => {
                            printPenerimaanHTML += `<tr><td style="padding: 4px 0;">${escapeHtml(item.title)}</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(item.amount)}</td></tr>`;
                        });
                    }
                    document.getElementById('print-penerimaan-list').innerHTML = printPenerimaanHTML;

                    let printPotonganHTML = '';
                    if (state.potonganDetails.length > 0) {
                        state.potonganDetails.forEach(item => {
                            printPotonganHTML += `<tr><td style="padding: 4px 0;">${escapeHtml(item.title)}</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(item.amount)}</td></tr>`;
                        });
                    }
                    document.getElementById('print-potongan-list').innerHTML = printPotonganHTML;

                    document.getElementById('print-total-penerimaan').textContent = formatRupiah(totalPenerimaan);
                    document.getElementById('print-total-potongan').textContent = formatRupiah(state.total_potongan);
                    document.getElementById('print-thp').textContent = formatRupiah(state.total_gaji_bersih);
                }

                function openSlipModal(id) {
                    selectRow(id);
                    renderSlip(id);
                    slipModal.classList.remove('hidden');
                    slipModal.classList.add('flex');
                    setTimeout(() => {
                        slipModal.classList.remove('opacity-0');
                        slipModalInner.classList.remove('scale-95');
                        slipModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.printSlip = function () {
                    const container = document.getElementById('print-container');
                    document.body.appendChild(container);
                    window.print();
                }

                window.closeSlipModal = function () {
                    slipModal.classList.add('opacity-0');
                    slipModalInner.classList.remove('scale-100');
                    slipModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        slipModal.classList.add('hidden');
                        slipModal.classList.remove('flex');
                    }, 300);
                }

                function processPayroll(id) {
                    const state = ensureState(id);
                    state.status_pembayaran = 'Dibayar';
                    state.tanggal_pembayaran = new Date().toLocaleDateString('id-ID');
                    recalculateState(id);
                    renderTableRow(id);
                    renderDetail(id);
                    updatePayrollSummaryCards();
                    sortPayrollRows();
                    applyPayrollFilters();
                }

                function sortPayrollRows() {
                    if (!payrollTableBody) return;
                    rows
                        .slice()
                        .sort((a, b) => {
                            const first = recalculateState(a.dataset.karyawanId);
                            const second = recalculateState(b.dataset.karyawanId);
                            return statusPriority(first.status_pembayaran) - statusPriority(second.status_pembayaran)
                                || Number(a.dataset.rowIndex || 0) - Number(b.dataset.rowIndex || 0);
                        })
                        .forEach(row => payrollTableBody.appendChild(row));

                    if (payrollEmptyFilterRow) {
                        payrollTableBody.appendChild(payrollEmptyFilterRow);
                    }
                }

                function normalizeFilterText(value) {
                    return String(value || '').trim().toLowerCase();
                }

                function rowMatchesPayrollFilters(row) {
                    const searchValue = normalizeFilterText(payrollSearch?.value);
                    const bagianValue = normalizeFilterText(payrollBagianFilter?.value);
                    const rowName = normalizeFilterText(row.dataset.karyawanName);
                    const rowBagian = normalizeFilterText(row.dataset.karyawanBagian);

                    return (!searchValue || rowName.includes(searchValue))
                        && (!bagianValue || rowBagian === bagianValue);
                }

                function applyPayrollFilters() {
                    const matchedRows = rows.filter(rowMatchesPayrollFilters);
                    const totalPages = Math.max(1, Math.ceil(matchedRows.length / payrollRowsPerPage));

                    payrollCurrentPage = Math.min(Math.max(1, payrollCurrentPage), totalPages);
                    const startIndex = (payrollCurrentPage - 1) * payrollRowsPerPage;
                    const endIndex = startIndex + payrollRowsPerPage;
                    const currentRows = matchedRows.slice(startIndex, endIndex);

                    rows.forEach(row => {
                        const isVisible = currentRows.includes(row);
                        row.classList.toggle('hidden', !isVisible);
                    });

                    if (payrollEmptyFilterRow) {
                        payrollEmptyFilterRow.classList.toggle('hidden', matchedRows.length > 0);
                    }

                    renderPayrollPagination(matchedRows.length, totalPages, startIndex, currentRows.length);
                    updateBatchBar();
                }

                function renderPayrollPagination(totalRows, totalPages, startIndex, currentCount) {
                    if (payrollPaginationInfo) {
                        const startNumber = totalRows === 0 ? 0 : startIndex + 1;
                        const endNumber = totalRows === 0 ? 0 : startIndex + currentCount;
                        payrollPaginationInfo.textContent = `Menampilkan ${startNumber}-${endNumber} dari ${totalRows} data`;
                    }

                    if (!payrollPagination) return;

                    if (totalRows === 0) {
                        payrollPagination.innerHTML = '';
                        return;
                    }

                    const prevDisabled = payrollCurrentPage <= 1;
                    const nextDisabled = payrollCurrentPage >= totalPages;

                    let buttons = `
                        <button type="button" data-payroll-page="${payrollCurrentPage - 1}" ${prevDisabled ? 'disabled' : ''}
                            class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 ${prevDisabled ? 'opacity-50 pointer-events-none' : ''}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l-6 6l6 6" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>
                    `;

                    for (let page = 1; page <= totalPages; page++) {
                        const isActive = page === payrollCurrentPage;
                        buttons += `
                            <button type="button" data-payroll-page="${page}"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-md border text-sm font-medium shadow-sm transition-all ${isActive ? 'bg-hunter-green-600 text-white shadow-hunter-green-200 border-hunter-green-600' : 'border-carbon-black-200 bg-white text-carbon-black-700 hover:bg-carbon-black-50'}">
                                ${page}
                            </button>
                        `;
                    }

                    buttons += `
                        <button type="button" data-payroll-page="${payrollCurrentPage + 1}" ${nextDisabled ? 'disabled' : ''}
                            class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 ${nextDisabled ? 'opacity-50 pointer-events-none' : ''}">
                            <span>Berikutnya</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 6l6 6l-6 6" />
                            </svg>
                        </button>
                    `;

                    payrollPagination.innerHTML = buttons;
                }

                function selectRow(id) {
                    activeKaryawanId = id;
                    rows.forEach(row => {
                        const isActive = String(row.dataset.karyawanId) === String(id);
                        row.classList.toggle('bg-hunter-green-50', isActive);
                        row.classList.toggle('ring-1', isActive);
                        row.classList.toggle('ring-hunter-green-300', isActive);
                    });
                    renderDetail(id);
                }

                // Batch Selection Logic
                function updateBatchBar() {
                    const checkedBoxes = Array.from(document.querySelectorAll('.row-checkbox:checked'));
                    const count = checkedBoxes.length;
                    if (count > 0) {
                        batchBar.classList.remove('hidden');
                        batchSelectedCount.textContent = count;
                    } else {
                        batchBar.classList.add('hidden');
                    }
                    if (checkSelectAll) {
                        const visibleBoxes = Array.from(document.querySelectorAll('.payroll-row:not(.hidden) .row-checkbox'));
                        checkSelectAll.checked = visibleBoxes.length > 0 && visibleBoxes.every(cb => cb.checked);
                    }
                }

                if (checkSelectAll) {
                    checkSelectAll.addEventListener('change', function () {
                        const visibleBoxes = document.querySelectorAll('.payroll-row:not(.hidden) .row-checkbox');
                        visibleBoxes.forEach(cb => cb.checked = checkSelectAll.checked);
                        updateBatchBar();
                    });
                }

                document.addEventListener('change', function (e) {
                    if (e.target && e.target.classList.contains('row-checkbox')) {
                        updateBatchBar();
                    }
                });

                if (btnBatchDeselect) {
                    btnBatchDeselect.addEventListener('click', function () {
                        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
                        if (checkSelectAll) checkSelectAll.checked = false;
                        updateBatchBar();
                    });
                }

                if (btnBatchPaySelected) {
                    btnBatchPaySelected.addEventListener('click', function () {
                        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                        checkedBoxes.forEach(cb => {
                            const id = cb.dataset.id;
                            if (id) {
                                const state = ensureState(id);
                                state.status_pembayaran = 'Dibayar';
                                state.tanggal_pembayaran = new Date().toLocaleDateString('id-ID');
                                recalculateState(id);
                                renderTableRow(id);
                            }
                        });
                        if (activeKaryawanId) renderDetail(activeKaryawanId);
                        updatePayrollSummaryCards();
                        sortPayrollRows();
                        applyPayrollFilters();
                        btnBatchDeselect.click();
                    });
                }

                if (btnBatchProcessAll) {
                    btnBatchProcessAll.addEventListener('click', function () {
                        if (confirm('Apakah Anda yakin ingin memproses dan menandai Lunas seluruh gaji karyawan untuk periode ini?')) {
                            rows.forEach(row => {
                                const id = row.dataset.karyawanId;
                                const state = ensureState(id);
                                state.status_pembayaran = 'Dibayar';
                                state.tanggal_pembayaran = new Date().toLocaleDateString('id-ID');
                                recalculateState(id);
                                renderTableRow(id);
                            });
                            if (activeKaryawanId) renderDetail(activeKaryawanId);
                            updatePayrollSummaryCards();
                            sortPayrollRows();
                            applyPayrollFilters();
                        }
                    });
                }

                function formatUsageValue(value, satuan) {
                    return Number(value || 0).toLocaleString('id-ID') + ' ' + satuan;
                }

                function getSelectedIds(container, selectClass, exceptSelect = null) {
                    return Array.from(container.querySelectorAll(selectClass))
                        .filter(select => select !== exceptSelect && select.value)
                        .map(select => String(select.value));
                }

                function refreshUniqueOptions(container, selectClass, data, placeholderText, createOptionsCallback) {
                    container.querySelectorAll(selectClass).forEach(select => {
                        const currentValue = select.value;
                        select.innerHTML = `
                            <option value="" disabled ${currentValue ? '' : 'selected'}>${placeholderText}</option>
                            ${createOptionsCallback(currentValue, select)}
                        `;
                        select.value = currentValue;
                    });
                }

                function getSelectedBonus(row) {
                    const select = row.querySelector('.bonus-type');
                    return bonusData.find(item => String(item.id) === String(select.value));
                }

                function createBonusOptions(selectedId = '', currentSelect = null) {
                    const selectedIds = getSelectedIds(bonusRows, '.bonus-type', currentSelect);
                    const autoBonusIds = [1, 2, 3, 4, 5, 6, 8];

                    return bonusData.map(item => {
                        if (autoBonusIds.includes(Number(item.id))) return '';
                        if (selectedIds.includes(String(item.id)) && String(item.id) !== String(selectedId)) {
                            return '';
                        }
                        const selected = String(item.id) === String(selectedId) ? 'selected' : '';
                        return `<option value="${item.id}" ${selected}>${escapeHtml(item.nama)}</option>`;
                    }).join('');
                }

                function refreshBonusOptions() {
                    refreshUniqueOptions(bonusRows, '.bonus-type', bonusData, 'Jenis Bonus', createBonusOptions);
                }

                function updateBonusRow(row) {
                    const bonus = getSelectedBonus(row);
                    const usageInput = row.querySelector('.bonus-usage');
                    const usageHint = row.querySelector('.bonus-usage-hint');
                    const subtotalText = row.querySelector('.bonus-subtotal');

                    if (!bonus) {
                        usageInput.value = '';
                        usageInput.disabled = true;
                        usageHint.textContent = 'Pilih jenis bonus terlebih dahulu.';
                        subtotalText.textContent = 'Rp 0';
                        return;
                    }

                    const satuan = String(bonus.satuan || '').toLowerCase();
                    const isAttendanceBonus = satuan === 'hari';
                    const isFixedBonus = satuan === 'kali';
                    usageInput.disabled = false;

                    if (isAttendanceBonus) {
                        const hariKerja = Number(karyawanLookup[activeKaryawanId]?.hari_kerja ?? 0);
                        usageInput.value = Math.max(0, hariKerja - Number(bonus.batas_normal || 0));
                        usageInput.readOnly = true;
                        usageInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                        usageHint.textContent = 'Bonus kehadiran dihitung otomatis jika hadir lebih dari ' + bonus.batas_normal + ' hari.';
                    } else if (isFixedBonus) {
                        usageInput.value = 1;
                        usageInput.readOnly = true;
                        usageInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                        usageHint.textContent = 'Bonus ini dihitung 1 kali sesuai nominal tarif.';
                    } else {
                        usageInput.readOnly = false;
                        usageInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed');
                        usageInput.placeholder = 'Selisih ' + bonus.satuan;
                        usageHint.textContent = 'Isi selisih di atas batas normal ' + formatUsageValue(bonus.batas_normal, bonus.satuan) + '.';
                    }

                    subtotalText.textContent = formatRupiah(Number(usageInput.value || 0) * Number(bonus.tarif || 0));
                    updateBonusModalTotal();
                }

                function updateBonusModalTotal() {
                    const data = karyawanLookup[activeKaryawanId] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0 };
                    let total = Number(periodBonus.total || 0);

                    bonusRows.querySelectorAll('.bonus-row').forEach(row => {
                        const bonus = getSelectedBonus(row);
                        const usage = Number(row.querySelector('.bonus-usage').value || 0);
                        if (bonus) {
                            total += usage * Number(bonus.tarif || 0);
                        }
                    });

                    bonusModalTotal.textContent = Number(total).toLocaleString('id-ID');
                    return total;
                }

                function collectBonusDetails() {
                    const details = [];
                    const manualRows = [];

                    bonusRows.querySelectorAll('.bonus-row').forEach(row => {
                        const bonus = getSelectedBonus(row);
                        const usage = Number(row.querySelector('.bonus-usage').value || 0);

                        if (!bonus || usage <= 0) return;

                        const amount = usage * Number(bonus.tarif || 0);
                        const isFixedBonus = String(bonus.satuan || '').toLowerCase() === 'kali';
                        details.push({
                            title: bonus.nama,
                            description: isFixedBonus
                                ? `Dihitung 1 kali sebesar ${formatRupiah(bonus.tarif)}`
                                : `Sejumlah ${formatUsageValue(usage, bonus.satuan)} x ${formatRupiah(bonus.tarif)}`,
                            amount,
                        });
                        manualRows.push({ id: bonus.id, usage });
                    });

                    return { details, manualRows };
                }

                function addBonusRow(selectedId = '', usageValue = '') {
                    const row = document.createElement('div');
                    row.className = 'bonus-row grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-white border border-carbon-black-100 rounded-xl p-3.5 shadow-sm';
                    row.innerHTML = `
                        <div class="relative">
                            <select class="bonus-type w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                                <option value="" disabled ${selectedId ? '' : 'selected'}>Jenis Bonus</option>
                                ${createBonusOptions(selectedId)}
                            </select>
                            <p class="bonus-usage-hint text-xs font-semibold text-carbon-black-400 mt-1">Pilih jenis bonus terlebih dahulu.</p>
                        </div>
                        <input type="number" min="0" step="0.01" class="bonus-usage w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg" placeholder="Jumlah" disabled>
                        <div class="px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 rounded-lg text-sm font-bold text-carbon-black-800">
                            <span class="bonus-subtotal">Rp 0</span>
                        </div>
                        <button type="button" class="remove-bonus-row h-11 w-11 inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 transition-colors" title="Kurangi bonus">
                            <x-tabler-minus class="w-4 h-4" />
                        </button>
                    `;

                    bonusRows.appendChild(row);
                    if (selectedId) {
                        row.querySelector('.bonus-type').value = selectedId;
                        row.querySelector('.bonus-usage').value = usageValue;
                    }
                    row.querySelector('.bonus-type').addEventListener('change', () => {
                        updateBonusRow(row);
                        refreshBonusOptions();
                    });
                    row.querySelector('.bonus-usage').addEventListener('input', () => updateBonusRow(row));
                    row.querySelector('.remove-bonus-row').addEventListener('click', () => {
                        row.remove();
                        if (!bonusRows.querySelector('.bonus-row')) addBonusRow();
                        refreshBonusOptions();
                        updateBonusModalTotal();
                    });

                    if (selectedId) updateBonusRow(row);
                    refreshBonusOptions();
                }

                function openBonusModal() {
                    if (!activeKaryawanId) return;
                    editingType = 'bonus';
                    const state = ensureState(activeKaryawanId);
                    const data = karyawanLookup[activeKaryawanId] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0, details: [] };

                    bonusRows.innerHTML = '';

                    (periodBonus.details || []).forEach(item => {
                        const row = document.createElement('div');
                        row.className = 'grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-carbon-black-50 border border-carbon-black-100 rounded-xl p-3.5 shadow-sm opacity-90';
                        row.innerHTML = `
                            <div class="relative">
                                <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed" value="${escapeHtml(item.title)}" readonly>
                                <p class="text-xs font-semibold text-carbon-black-400 mt-1">Bawaan sistem (Otomatis)</p>
                            </div>
                            <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed truncate" value="${escapeHtml(item.description)}" readonly title="${escapeHtml(item.description)}">
                            <div class="px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 rounded-lg text-sm font-bold text-carbon-black-800">
                                <span>${formatRupiah(item.amount)}</span>
                            </div>
                            <div class="h-11 w-11 inline-flex items-center justify-center rounded-lg bg-carbon-black-100 border border-carbon-black-200 text-carbon-black-400 cursor-not-allowed">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </div>
                        `;
                        bonusRows.appendChild(row);
                    });

                    if (state.manualBonusRows.length > 0) {
                        state.manualBonusRows.forEach(item => addBonusRow(item.id, item.usage));
                    } else if ((periodBonus.details || []).length === 0) {
                        addBonusRow();
                    }

                    updateBonusModalTotal();
                    bonusModal.classList.remove('hidden');
                    bonusModal.classList.add('flex');
                    setTimeout(() => {
                        bonusModal.classList.remove('opacity-0');
                        bonusModalInner.classList.remove('scale-95');
                        bonusModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.closeBonusModal = function () {
                    bonusModal.classList.add('opacity-0');
                    bonusModalInner.classList.remove('scale-100');
                    bonusModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        bonusModal.classList.add('hidden');
                        bonusModal.classList.remove('flex');
                        editingType = null;
                    }, 300);
                }

                function getSelectedPotongan(row) {
                    const select = row.querySelector('.potongan-type');
                    return potonganData.find(item => String(item.id) === String(select.value));
                }

                function createPotonganOptions(selectedId = '', currentSelect = null) {
                    const selectedIds = getSelectedIds(potonganRows, '.potongan-type', currentSelect);
                    return potonganData.map(item => {
                        const tipe = String(item.tipe_potongan || '').toLowerCase();
                        if (tipe === 'tetap' || tipe === 'persentase') return '';
                        if (selectedIds.includes(String(item.id)) && String(item.id) !== String(selectedId)) {
                            return '';
                        }
                        const selected = String(item.id) === String(selectedId) ? 'selected' : '';
                        return `<option value="${item.id}" ${selected}>${escapeHtml(item.nama)}</option>`;
                    }).join('');
                }

                function refreshPotonganOptions() {
                    refreshUniqueOptions(potonganRows, '.potongan-type', potonganData, 'Jenis Potongan', createPotonganOptions);
                }

                function updatePotonganRow(row) {
                    const potongan = getSelectedPotongan(row);
                    const jumlahInput = row.querySelector('.potongan-jumlah');
                    const hint = row.querySelector('.potongan-hint');
                    const subtotalText = row.querySelector('.potongan-subtotal');

                    if (!potongan) {
                        jumlahInput.value = '';
                        jumlahInput.disabled = true;
                        hint.textContent = 'Pilih jenis potongan terlebih dahulu.';
                        subtotalText.textContent = 'Rp 0';
                        return;
                    }

                    const isFixed = String(potongan.tipe_potongan || '').toLowerCase() === 'tetap' || String(potongan.tipe_potongan || '').toLowerCase() === 'persentase';
                    jumlahInput.disabled = false;

                    if (isFixed) {
                        jumlahInput.value = 1;
                        jumlahInput.readOnly = true;
                        jumlahInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                        hint.textContent = 'Potongan ini dihitung secara otomatis per proses penggajian.';
                    } else {
                        jumlahInput.readOnly = false;
                        jumlahInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed');
                        jumlahInput.placeholder = 'Jumlah kejadian';
                        hint.textContent = 'Isi jumlah kejadian/pemakaian untuk potongan kondisional.';
                    }

                    subtotalText.textContent = formatRupiah(Number(jumlahInput.value || 0) * Number(potongan.tarif || 0));
                    updatePotonganModalTotal();
                }

                function updatePotonganModalTotal() {
                    let total = 0;
                    const state = activeKaryawanId ? ensureState(activeKaryawanId) : { gaji_pokok: 0 };

                    potonganData.forEach(p => {
                        const tipe = String(p.tipe_potongan || '').toLowerCase();
                        if (tipe === 'tetap') {
                            total += Number(p.tarif || 0);
                        } else if (tipe === 'persentase') {
                            total += Number(state.gaji_pokok || 0) * (Number(p.tarif || 0) / 100);
                        }
                    });

                    potonganRows.querySelectorAll('.potongan-row').forEach(row => {
                        const potongan = getSelectedPotongan(row);
                        const jumlah = Number(row.querySelector('.potongan-jumlah').value || 0);
                        if (potongan) {
                            total += jumlah * Number(potongan.tarif || 0);
                        }
                    });

                    potonganModalTotal.textContent = Number(total).toLocaleString('id-ID');
                    return total;
                }

                function collectPotonganDetails() {
                    const details = [];
                    const manualRows = [];

                    potonganRows.querySelectorAll('.potongan-row').forEach(row => {
                        const potongan = getSelectedPotongan(row);
                        const jumlah = Number(row.querySelector('.potongan-jumlah').value || 0);

                        if (!potongan || jumlah <= 0) return;

                        const tipe = String(potongan.tipe_potongan || '').toLowerCase();
                        let amount = 0;
                        let description = '';

                        if (tipe === 'tetap') {
                            amount = Number(potongan.tarif || 0);
                            description = `Potongan tetap sebesar ${formatRupiah(potongan.tarif)}`;
                        } else if (tipe === 'persentase') {
                            const state = activeKaryawanId ? ensureState(activeKaryawanId) : { gaji_pokok: 0 };
                            amount = Number(state.gaji_pokok || 0) * (Number(potongan.tarif || 0) / 100);
                            description = `Dihitung ${potongan.tarif}% dari Gaji Pokok`;
                        } else {
                            amount = jumlah * Number(potongan.tarif || 0);
                            description = `${formatNumber(jumlah)} kejadian x ${formatRupiah(potongan.tarif)}`;
                        }

                        details.push({
                            title: potongan.nama,
                            description: description,
                            amount,
                        });
                        manualRows.push({ id: potongan.id, jumlah });
                    });

                    return { details, manualRows };
                }

                function addPotonganRow(selectedId = '', jumlahValue = '') {
                    const row = document.createElement('div');
                    row.className = 'potongan-row grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-white border border-carbon-black-100 rounded-xl p-3.5 shadow-sm';
                    row.innerHTML = `
                        <div class="relative">
                            <select class="potongan-type w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                                <option value="" disabled ${selectedId ? '' : 'selected'}>Jenis Potongan</option>
                                ${createPotonganOptions(selectedId)}
                            </select>
                            <p class="potongan-hint text-xs font-semibold text-carbon-black-400 mt-1">Pilih jenis potongan terlebih dahulu.</p>
                        </div>
                        <input type="number" min="0" step="1" class="potongan-jumlah w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg" placeholder="Jumlah" disabled>
                        <div class="px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 rounded-lg text-sm font-bold text-carbon-black-800">
                            <span class="potongan-subtotal">Rp 0</span>
                        </div>
                        <button type="button" class="remove-potongan-row h-11 w-11 inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 transition-colors" title="Kurangi potongan">
                            <x-tabler-minus class="w-4 h-4" />
                        </button>
                    `;

                    potonganRows.appendChild(row);
                    if (selectedId) {
                        row.querySelector('.potongan-type').value = selectedId;
                        row.querySelector('.potongan-jumlah').value = jumlahValue;
                    }
                    row.querySelector('.potongan-type').addEventListener('change', () => {
                        updatePotonganRow(row);
                        refreshPotonganOptions();
                    });
                    row.querySelector('.potongan-jumlah').addEventListener('input', () => updatePotonganRow(row));
                    row.querySelector('.remove-potongan-row').addEventListener('click', () => {
                        row.remove();
                        if (!potonganRows.querySelector('.potongan-row')) addPotonganRow();
                        refreshPotonganOptions();
                        updatePotonganModalTotal();
                    });

                    if (selectedId) updatePotonganRow(row);
                    refreshPotonganOptions();
                }

                function openPotonganModal() {
                    if (!activeKaryawanId) return;
                    editingType = 'potongan';
                    const state = ensureState(activeKaryawanId);
                    potonganRows.innerHTML = '';

                    potonganData.forEach(p => {
                        const tipe = String(p.tipe_potongan || '').toLowerCase();
                        if (tipe === 'tetap' || tipe === 'persentase') {
                            let amountStr = '0';
                            let descStr = 'Potongan tetap (Otomatis)';

                            if (tipe === 'tetap') {
                                amountStr = formatRupiah(p.tarif);
                            } else if (tipe === 'persentase') {
                                const amount = Number(state.gaji_pokok || 0) * (Number(p.tarif || 0) / 100);
                                amountStr = formatRupiah(amount);
                                descStr = `Potongan ${p.tarif}% dari Gaji Pokok`;
                            }

                            const row = document.createElement('div');
                            row.className = 'grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-carbon-black-50 border border-carbon-black-100 rounded-xl p-3.5 shadow-sm opacity-90';
                            row.innerHTML = `
                                <div class="relative">
                                    <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed text-rose-700 truncate" value="${escapeHtml(p.nama)}" readonly title="${escapeHtml(p.nama)}">
                                    <p class="text-xs font-semibold text-carbon-black-400 mt-1">Bawaan sistem (Otomatis)</p>
                                </div>
                                <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed truncate" value="${escapeHtml(descStr)}" readonly title="${escapeHtml(descStr)}">
                                <div class="px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 rounded-lg text-sm font-bold text-rose-600">
                                    <span>${amountStr}</span>
                                </div>
                                <div class="h-11 w-11 inline-flex items-center justify-center rounded-lg bg-carbon-black-100 border border-carbon-black-200 text-carbon-black-400 cursor-not-allowed">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                </div>
                            `;
                            potonganRows.appendChild(row);
                        }
                    });

                    if (state.manualPotonganRows.length > 0) {
                        state.manualPotonganRows.forEach(item => addPotonganRow(item.id, item.jumlah));
                    } else {
                        const hasOnlyTetap = potonganData.filter(p => {
                            const t = String(p.tipe_potongan || '').toLowerCase();
                            return t !== 'tetap' && t !== 'persentase';
                        }).length === 0;
                        if (!hasOnlyTetap) addPotonganRow();
                    }

                    updatePotonganModalTotal();
                    potonganModal.classList.remove('hidden');
                    potonganModal.classList.add('flex');
                    setTimeout(() => {
                        potonganModal.classList.remove('opacity-0');
                        potonganModalInner.classList.remove('scale-95');
                        potonganModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.closePotonganModal = function () {
                    potonganModal.classList.add('opacity-0');
                    potonganModalInner.classList.remove('scale-100');
                    potonganModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        potonganModal.classList.add('hidden');
                        potonganModal.classList.remove('flex');
                        editingType = null;
                    }, 300);
                }

                // Attach Row Handlers
                rows.forEach(row => {
                    const id = row.dataset.karyawanId;
                    ensureState(id);
                    updatePeriodBonus(id);
                    renderTableRow(id);
                    row.addEventListener('click', (e) => {
                        if (e.target.closest('button') || e.target.closest('input[type="checkbox"]')) return;
                        selectRow(id);
                        openPayrollDrawer(id);
                    });
                });

                updatePayrollSummaryCards();
                sortPayrollRows();
                applyPayrollFilters();

                document.querySelectorAll('[data-action="process"]').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        processPayroll(this.dataset.karyawanId);
                    });
                });

                document.querySelectorAll('[data-action="open-drawer"]').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        openPayrollDrawer(this.dataset.karyawanId);
                    });
                });

                document.querySelectorAll('[data-action="slip"]').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        openSlipModal(this.dataset.karyawanId);
                    });
                });

                monthSelect?.addEventListener('change', function () {
                    rows.forEach(row => {
                        updatePeriodBonus(row.dataset.karyawanId);
                        renderTableRow(row.dataset.karyawanId);
                    });
                    updatePayrollSummaryCards();
                    sortPayrollRows();
                    applyPayrollFilters();
                    if (activeKaryawanId) renderDetail(activeKaryawanId);
                });

                yearSelect?.addEventListener('change', function () {
                    rows.forEach(row => {
                        updatePeriodBonus(row.dataset.karyawanId);
                        renderTableRow(row.dataset.karyawanId);
                    });
                    updatePayrollSummaryCards();
                    sortPayrollRows();
                    applyPayrollFilters();
                    if (activeKaryawanId) renderDetail(activeKaryawanId);
                });

                payrollPagination?.addEventListener('click', function (event) {
                    const button = event.target.closest('[data-payroll-page]');
                    if (!button || button.disabled) return;
                    payrollCurrentPage = Number(button.dataset.payrollPage || 1);
                    applyPayrollFilters();
                });

                payrollSearch?.addEventListener('input', function () {
                    payrollCurrentPage = 1;
                    applyPayrollFilters();
                });

                payrollBagianFilter?.addEventListener('change', function () {
                    payrollCurrentPage = 1;
                    applyPayrollFilters();
                });

                detailEditBonus.addEventListener('click', openBonusModal);
                detailEditPotongan.addEventListener('click', openPotonganModal);
                detailSavePayroll.addEventListener('click', function () {
                    if (!activeKaryawanId) return;
                    processPayroll(activeKaryawanId);
                });

                salaryDetailList.addEventListener('click', function (event) {
                    const button = event.target.closest('[data-component-detail]');
                    if (!button) return;
                    openComponentDetailModal(button.dataset.componentDetail);
                });

                addBonusRowButton.addEventListener('click', () => addBonusRow());
                addPotonganRowButton.addEventListener('click', () => addPotonganRow());

                saveBonusModalButton.addEventListener('click', function () {
                    if (!activeKaryawanId || editingType !== 'bonus') return;
                    const state = ensureState(activeKaryawanId);
                    const result = collectBonusDetails();
                    state.bonusDetails = result.details;
                    state.manualBonusRows = result.manualRows;
                    state.total_bonus = updateBonusModalTotal();
                    recalculateState(activeKaryawanId);
                    renderTableRow(activeKaryawanId);
                    renderDetail(activeKaryawanId);
                    updatePayrollSummaryCards();
                    closeBonusModal();
                });

                savePotonganModalButton.addEventListener('click', function () {
                    if (!activeKaryawanId || editingType !== 'potongan') return;
                    const state = ensureState(activeKaryawanId);
                    const result = collectPotonganDetails();
                    state.potonganDetails = result.details;
                    state.manualPotonganRows = result.manualRows;
                    state.total_potongan = updatePotonganModalTotal();
                    recalculateState(activeKaryawanId);
                    renderTableRow(activeKaryawanId);
                    renderDetail(activeKaryawanId);
                    updatePayrollSummaryCards();
                    closePotonganModal();
                });

                if (activeKaryawanId) {
                    renderDetail(activeKaryawanId);
                }
            });
        </script>
    @endpush
@endsection