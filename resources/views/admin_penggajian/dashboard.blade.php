@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <div class="flex items-start justify-between gap-6">
            <div>
                <h1
                    class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                    DASHBOARD
                </h1>
                <p class="mt-2 text-sm font-medium text-slate-500">
                    Ringkasan operasional penggajian dan presensi bulan berjalan.
                </p>
            </div>
            <div
                class="flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-black uppercase tracking-widest text-hunter-green-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:-translate-y-1">
                <x-tabler-calendar-stats class="h-5 w-5" />
                {{ now()->translatedFormat('F Y') }}
            </div>
        </div>

        {{-- TOP METRIC CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Card 1: Karyawan Aktif --}}
            <div
                class="rounded-2xl bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-lg relative group">
                <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-hunter-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-1.5">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">KARYAWAN AKTIF</p>

                        {{-- Icon Info & Popover Breakdown Bagian --}}
                        <div class="relative" id="karyawan-info-wrapper">
                            <button type="button"
                                id="btn-info-bagian"
                                class="text-slate-400 hover:text-hunter-green-700 transition-colors p-0.5 rounded-full hover:bg-slate-100 flex items-center justify-center cursor-pointer"
                                style="outline: none;"
                                title="Klik untuk melihat rincian bagian karyawan">
                                <x-tabler-info-circle class="h-4 w-4" />
                            </button>

                            {{-- Popover Dropdown Rincian Bagian (Simple & High Readability) --}}
                            <div id="popover-info-bagian"
                                class="hidden absolute left-0 top-full mt-2 z-20 w-48 rounded-xl p-3.5 text-white shadow-xl select-none"
                                style="background-color: #0f172a; border: 1px solid #1e293b; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);">

                                {{-- Header --}}
                                <div class="flex items-center justify-between pb-2 mb-2.5 border-b" style="border-color: #1e293b;">
                                    <span class="text-xs font-black uppercase tracking-wider text-slate-200">
                                        Rincian Bagian
                                    </span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded text-emerald-400"
                                        style="background-color: rgba(16, 185, 129, 0.15);">
                                        Aktif
                                    </span>
                                </div>

                                {{-- Simple List (Ringkas & Mudah Dibaca) --}}
                                <div class="space-y-2 text-xs font-semibold">
                                    <div class="flex items-center justify-between py-0.5">
                                        <div class="flex items-center gap-2 text-slate-300">
                                            <x-tabler-tools class="h-4 w-4 text-emerald-400 shrink-0" />
                                            <span>Produksi</span>
                                        </div>
                                        <span class="text-sm font-black text-white">
                                            {{ $bagianCounts['Produksi'] ?? 0 }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-0.5">
                                        <div class="flex items-center gap-2 text-slate-300">
                                            <x-tabler-package class="h-4 w-4 text-sky-400 shrink-0" />
                                            <span>Packing</span>
                                        </div>
                                        <span class="text-sm font-black text-white">
                                            {{ $bagianCounts['Packing'] ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-2 rounded-xl bg-hunter-green-50 text-hunter-green-700">
                        <x-tabler-users class="h-5 w-5" />
                    </div>
                </div>
                <p class="mt-3 text-4xl font-black text-carbon-black-900 relative z-10">{{ count($activeKaryawan) }}</p>
                <p class="mt-2 text-xs font-medium text-slate-400 relative z-10">Karyawan yang diproses</p>
            </div>

            {{-- Card 2: Karyawan Inaktif --}}
            <div
                class="rounded-2xl bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">KARYAWAN INAKTIF</p>
                    <div class="p-2 rounded-xl bg-rose-50 text-rose-600">
                        <x-tabler-user-off class="h-5 w-5" />
                    </div>
                </div>
                <p class="mt-3 text-4xl font-black text-carbon-black-900 relative z-10">{{ count($inactiveKaryawan) }}</p>
                <p class="mt-2 text-xs font-medium text-slate-400 relative z-10">Tidak diproses </p>
            </div>

            {{-- Card 3: Data Presensi --}}
            <div
                class="rounded-2xl bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">DATA PRESENSI</p>
                    <div class="p-2 rounded-xl bg-blue-50 text-blue-600">
                        <x-tabler-calendar-check class="h-5 w-5" />
                    </div>
                </div>
                <div class="mt-3 flex items-end gap-2 relative z-10">
                    <span class="text-4xl font-black text-carbon-black-950">{{ $totalKaryawanSudahPresensi }}</span>
                    <span class="pb-1 text-2xl font-black text-slate-300">/</span>
                    <span class="pb-1 text-2xl font-black text-slate-400">{{ $totalKaryawanAktif }}</span>
                </div>
                <p class="mt-2 text-xs font-medium text-slate-400 relative z-10">Presensi terdata</p>
            </div>

            {{-- Card 4: Karyawan Berhasil Digaji --}}
            <div
                class="rounded-2xl bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 transition-all hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">GAJI SELESAI</p>
                    <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                        <x-tabler-circle-check class="h-5 w-5" />
                    </div>
                </div>
                <div class="mt-3 flex items-end gap-2 relative z-10">
                    <span class="text-4xl font-black text-carbon-black-950">{{ $totalKaryawanSudahDigaji }}</span>
                    <span class="pb-1 text-2xl font-black text-slate-300">/</span>
                    <span class="pb-1 text-2xl font-black text-slate-400">{{ $totalKaryawanAktif }}</span>
                </div>
                <p class="mt-2 text-xs font-medium text-slate-400 relative z-10">Gaji telah dibayar</p>
            </div>
        </div>

        {{-- MIDDLE SECTION: PAYROLL OVERVIEW & CURRENT PAY PERIOD (Inspired by Modern Payroll UI) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left: Gambaran Pembayaran Gaji Perusahaan (Company Payments Overview) --}}
            <div
                class="lg:col-span-7 rounded-2xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-between">
                <div>
                    {{-- Header Card --}}
                    <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                        <div>
                            <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">
                                GAMBARAN BIAYA PENGGAJIAN
                            </h2>
                            <p class="text-xs font-medium text-slate-500 mt-1">
                                Total pengeluaran per bulan
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Navigasi Geser Periode (< dan >) --}}
                            <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg p-0.5 shadow-xs">
                                <button type="button" id="btn-chart-prev" onclick="scrollPayrollChart(-1)"
                                    title="Geser ke bulan sebelumnya"
                                    class="p-1.5 rounded-md text-slate-600 hover:bg-white hover:text-slate-900 hover:shadow-xs transition-all flex items-center justify-center">
                                    <x-tabler-chevron-left class="h-4 w-4" />
                                </button>
                                <button type="button" id="btn-chart-next" onclick="scrollPayrollChart(1)"
                                    title="Geser ke bulan berikutnya"
                                    class="p-1.5 rounded-md text-slate-600 hover:bg-white hover:text-slate-900 hover:shadow-xs transition-all flex items-center justify-center">
                                    <x-tabler-chevron-right class="h-4 w-4" />
                                </button>
                            </div>

                            <a href="{{ url('admin_penggajian/laporan_penggajian') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-black uppercase tracking-wider shadow-sm transition-all">
                                <x-tabler-file-download class="h-4 w-4 text-slate-500" />
                                <span>Ekspor</span>
                            </a>
                        </div>
                    </div>

                    {{-- Chart & Legend Section (Dibuat Flex Row Sebelahan) --}}
                    @php
                        $step = 25000000; // Kelipatan standar Rp 25 Juta
                        $maxChartTotal = max(1, ...array_map(fn($m) => $m['total'], $chartMonths));
                        // Auto scaling: Default minimal 100 Jt, jika melebihi 100 Jt akan otomatis scale-up ke kelipatan 25 Jt berikutnya dengan margin 10%
                        $yMax = max(100000000, (int) (ceil(($maxChartTotal * 1.1) / $step) * $step));
                        $chartHeight = 200; // Tinggi grafik 200px
                    @endphp

                    <div class="mt-6 flex flex-row items-end gap-6 justify-between">
                        {{-- Chart Area (Sebelah Kiri) --}}
                        <div class="flex-1 min-w-0">
                            {{-- Layout 2 Kolom: Kolom Sumbu Y di Kiri, Area Grafik & Sumbu X di Kanan --}}
                            <div class="flex gap-3 w-full" style="align-items: flex-start;">
                                {{-- 1. Kolom Label Sumbu Y (Kiri - Tetap Fixed / Tidak Ikut Bergeser) --}}
                                <div class="w-16 shrink-0 relative" style="height: {{ $chartHeight }}px;">
                                    @for($val = $yMax; $val >= 0; $val -= $step)
                                        @php
                                            $topPx = round((($yMax - $val) / $yMax) * $chartHeight);
                                        @endphp
                                        <div class="absolute right-0 text-right text-[11px] font-bold text-slate-400 select-none whitespace-nowrap"
                                            style="top: {{ $topPx }}px; transform: translateY(-50%);">
                                            {{ $val > 0 ? 'Rp ' . number_format($val / 1000000, 0) . 'Jt' : 'Rp 0' }}
                                        </div>
                                    @endfor
                                </div>

                                {{-- 2. Area Kanvas Grafik & Sumbu X (Kanan - Dapat Di-slide / Di-scroll) --}}
                                <div class="flex-1 min-w-0 relative">
                                    {{-- Garis Grid Horizontal Putus-putus (Tetap di Background Kanvas) --}}
                                    <div class="absolute inset-x-0 top-0 pointer-events-none" style="height: {{ $chartHeight }}px;">
                                        @for($val = $yMax; $val > 0; $val -= $step)
                                            @php
                                                $topPx = round((($yMax - $val) / $yMax) * $chartHeight);
                                            @endphp
                                            <div class="absolute inset-x-0 border-b border-dashed border-slate-200"
                                                style="top: {{ $topPx }}px;"></div>
                                        @endfor
                                    </div>

                                    {{-- Wadah Scroll Horizontal untuk Batang Grafik & Label Bulan --}}
                                    <div id="chart-scroll-container"
                                        class="w-full overflow-x-auto scroll-smooth flex pb-1 cursor-grab active:cursor-grabbing select-none"
                                        style="scrollbar-width: none; -ms-overflow-style: none;">
                                        <div class="flex items-stretch min-w-full">
                                            @foreach($chartMonths as $m)
                                                @php
                                                    $barHeightPx = $m['total'] > 0 ? max(14, min($chartHeight, round(($m['total'] / $yMax) * $chartHeight))) : 0;
                                                    $pokokShare = $m['total'] > 0 ? max(10, min(90, round(($m['pokok'] / $m['total']) * 100))) : 80;
                                                    $lainnyaShare = 100 - $pokokShare;
                                                @endphp
                                                <div class="flex flex-col items-center shrink-0" style="width: 16.666%; min-width: 65px;">
                                                    {{-- Area Batang Grafik (Height: 200px) Berdiri di Atas Baseline --}}
                                                    <div class="w-full flex items-end justify-center relative group border-b-2 border-slate-700" style="height: {{ $chartHeight }}px;">
                                                        {{-- Tooltip Hover --}}
                                                        <div class="opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 absolute -top-12 z-30 bg-slate-900 text-white text-[11px] font-bold rounded-lg px-2.5 py-1.5 shadow-xl whitespace-nowrap -translate-y-1">
                                                            <span class="block text-slate-300 font-medium">{{ $m['label'] }}</span>
                                                            <span>Rp {{ number_format($m['total'], 0, ',', '.') }}</span>
                                                        </div>

                                                        {{-- Stacked Bar --}}
                                                        @if($m['total'] > 0)
                                                            <div class="w-8 flex flex-col-reverse rounded-t-md overflow-hidden shadow-sm transition-transform duration-200 group-hover:scale-105"
                                                                style="height: {{ $barHeightPx }}px; min-height: 14px;">
                                                                {{-- Gaji Pokok (Bawah - Amber #f59e0b) --}}
                                                                <div class="w-full shrink-0" style="height: {{ $pokokShare }}%; min-height: 8px; background-color: #f59e0b;"></div>
                                                                {{-- Lembur & Lainnya (Atas - Slate #1e293b) --}}
                                                                <div class="w-full shrink-0" style="height: {{ $lainnyaShare }}%; min-height: 4px; background-color: #1e293b;"></div>
                                                            </div>
                                                        @else
                                                            <div class="w-8 h-1 bg-slate-200 rounded-t-sm"></div>
                                                        @endif
                                                    </div>

                                                    {{-- Sumbu X: Keterangan Bulan dan Tahun Tepat di Bawah Batang --}}
                                                    <div class="w-full pt-2.5 text-center">
                                                        <span class="text-xs font-bold whitespace-nowrap {{ $m['is_current'] ? 'text-slate-800 font-black' : 'text-slate-500' }}">
                                                            {{ $m['label'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Legend & Summary Area Kanan (Sebelah Kanan - Lebar Tetap 200px) --}}
                        <div class="w-52 shrink-0 flex flex-col justify-end space-y-4 pb-3 pl-4 border-l border-slate-100">
                            {{-- Total Biaya --}}
                            <div>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Total
                                    Pengeluaran</span>
                                <div class="mt-1">
                                    <h3 class="text-2xl font-black text-slate-900">
                                        Rp {{ number_format($estimasiBruto / 1000000, 1, ',', '.') }}Jt
                                    </h3>
                                </div>
                            </div>

                            {{-- Item 1: Gaji Pokok --}}
                            <div class="pt-2 border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-sm shrink-0" style="background-color: #f59e0b;"></span>
                                    <span class="text-xs font-bold text-slate-600">Gaji Pokok</span>
                                </div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-lg font-black text-slate-900">
                                        Rp {{ number_format($totalGajiPokok / 1000000, 1, ',', '.') }}Jt
                                    </span>
                                    <span class="text-xs font-black text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded-md">
                                        {{ $estimasiBruto > 0 ? number_format(($totalGajiPokok / $estimasiBruto) * 100, 0) : 0 }}%
                                    </span>
                                </div>
                            </div>

                            {{-- Item 2: Lembur & Lainnya --}}
                            <div class="pt-2 border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-sm shrink-0" style="background-color: #1e293b;"></span>
                                    <span class="text-xs font-bold text-slate-600">Lembur & Lainnya</span>
                                </div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-lg font-black text-slate-900">
                                        Rp {{ number_format($totalLembur / 1000000, 1, ',', '.') }}Jt
                                    </span>
                                    <span class="text-xs font-black text-slate-700 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">
                                        {{ $estimasiBruto > 0 ? number_format(($totalLembur / $estimasiBruto) * 100, 0) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Periode Penggajian & Mini Kalender (Clean Pay Period) --}}
            <div
                class="lg:col-span-5 rounded-2xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-between">
                <div>
                    @php
                        $curDay = now()->day;
                        $daysTotal = now()->daysInMonth;
                        if ($curDay <= 3) {
                            $badgeLabel = 'Jadwal Penggajian';
                            $badgeStyle = 'background-color: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd;';
                        } elseif ($curDay >= ($daysTotal - 2)) {
                            $badgeLabel = 'Persiapan Hitung Gaji';
                            $badgeStyle = 'background-color: #fefce8; color: #a16207; border: 1px solid #fef08a;';
                        } else {
                            $badgeLabel = 'Operasional Berjalan';
                            $badgeStyle = 'background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0;';
                        }
                    @endphp
                    <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">PERIODE BERJALAN</h2>
                        <span class="text-xs font-black px-3 py-1 rounded-lg"
                            style="{{ $badgeStyle }}">
                            {{ $badgeLabel }}
                        </span>
                    </div>

                    {{-- Mini Calendar Grid --}}
                    <div class="mt-5">
                        @php
                            $daysInMonth = now()->daysInMonth;
                            $firstDayOfWeek = now()->startOfMonth()->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
                            $today = now()->day;
                            $prevMonthDays = now()->copy()->subMonth()->daysInMonth;
                            $leadingDaysCount = $firstDayOfWeek - 1;
                            $totalCellsSoFar = $leadingDaysCount + $daysInMonth;
                            $trailingDaysCount = ($totalCellsSoFar % 7 > 0) ? (7 - ($totalCellsSoFar % 7)) : 0;
                        @endphp
                        <div
                            class="grid grid-cols-7 gap-1 text-center text-xs font-black text-slate-400 uppercase tracking-wider mb-2">
                            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                        </div>
                        <div class="grid grid-cols-7 gap-1.5 text-center text-xs font-bold text-slate-700">
                            {{-- Sisa tanggal bulan sebelumnya (Abu-abu lembut seperti di gambar referensi) --}}
                            @for($i = $leadingDaysCount; $i >= 1; $i--)
                                <span class="py-1 text-slate-300 font-medium select-none">
                                    {{ $prevMonthDays - $i + 1 }}
                                </span>
                            @endfor

                            {{-- Tanggal bulan berjalan --}}
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $isToday = ($d === $today);
                                    $isPayDay = ($d <= 3); // 2-3 hari awal bulan
                                    $isPrepare = ($d >= ($daysInMonth - 2)); // 2-3 hari sebelum akhir bulan

                                    if ($isPayDay) {
                                        $baseBg = '#0284c7';
                                        $textColor = '#ffffff';
                                        $title = 'Jadwal Penggajian' . ($isToday ? ' (Hari Ini)' : '');
                                    } elseif ($isPrepare) {
                                        $baseBg = '#fef3c7';
                                        $textColor = '#b45309';
                                        $title = 'Persiapan Hitung Gaji' . ($isToday ? ' (Hari Ini)' : '');
                                    } else {
                                        $baseBg = '#f8fafc';
                                        $textColor = '#334155';
                                        $title = $isToday ? 'Hari Ini' : '';
                                    }
                                @endphp

                                <span class="relative py-1 rounded-none font-black transition-all select-none flex items-center justify-center shadow-xs overflow-hidden"
                                    style="background-color: {{ $baseBg }}; color: {{ $textColor }};"
                                    title="{{ $title }}">
                                    <span class="relative z-10">{{ $d }}</span>
                                    @if($isToday)
                                        {{-- Segitiga Hijau Solid di Sudut Kanan Atas --}}
                                        <span style="position: absolute; top: 0; right: 0; width: 0; height: 0; border-top: 12px solid #16a34a; border-left: 12px solid transparent;" title="Hari Ini"></span>
                                    @endif
                                </span>
                            @endfor

                            {{-- Sisa tanggal bulan berikutnya untuk melengkapi baris kalender --}}
                            @for($nd = 1; $nd <= $trailingDaysCount; $nd++)
                                <span class="py-1 text-slate-300 font-medium select-none">
                                    {{ $nd }}
                                </span>
                            @endfor
                        </div>
                    </div>

                    {{-- Legenda Warna Kalender (Sederhana & Bersih) --}}
                    <div class="mt-5 space-y-2.5 pt-4 border-t border-slate-100 text-xs font-bold text-slate-600">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: #16a34a;"></span>
                            <span>Tanggal Hari Ini</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: #d97706;"></span>
                            <span>Persiapan Hitung Gaji</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: #0284c7;"></span>
                            <span>Jadwal Penggajian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOTTOM SECTION: STATUS PRESENSI & BAGIAN KARYAWAN AKTIF (PRESERVED & ENHANCED) + CHECKS/ALERTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Section 1: STATUS PRESENSI (Left - 6 Cols) --}}
            <section
                class="lg:col-span-6 rounded-2xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                    <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">STATUS PRESENSI</h2>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <x-tabler-calendar-check class="h-5 w-5" />
                    </div>
                </div>
                <div class="mt-6 space-y-5">
                    @php
                        $totalPresensi = max(1, array_sum($attendanceSummary));
                        $colors = [
                            'Hadir' => 'bg-emerald-500',
                            'Izin' => 'bg-amber-500',
                            'Sakit' => 'bg-blue-500',
                            'Cuti' => 'bg-purple-500',
                            'Alpha' => 'bg-rose-500',
                        ];
                        $bgColors = [
                            'Hadir' => 'bg-emerald-50',
                            'Izin' => 'bg-amber-50',
                            'Sakit' => 'bg-blue-50',
                            'Cuti' => 'bg-purple-50',
                            'Alpha' => 'bg-rose-50',
                        ];
                    @endphp
                    @foreach($attendanceSummary as $label => $value)
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm font-semibold text-slate-600">
                                <span>{{ $label }}</span>
                                <span class="font-bold text-carbon-black-900">{{ $value }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full {{ $bgColors[$label] }}">
                                <div class="h-full rounded-full {{ $colors[$label] }} transition-all duration-1000 ease-out"
                                    style="width: {{ ($value / $totalPresensi) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Section 2: VALIDASI DATA PENGGAJIAN (Right - 6 Cols) --}}
            <section
                class="lg:col-span-6 rounded-2xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800 flex items-center gap-2.5">
                            <x-tabler-checklist class="h-5 w-5 text-amber-600" />
                            VALIDASI DATA PENGGAJIAN
                        </h2>
                        <span class="text-xs font-bold text-slate-400">Sebelum Hitung Gaji</span>
                    </div>
                    <div class="mt-6 space-y-3">
                        @foreach($alerts as $alert)
                            <div
                                class="p-3.5 rounded-xl border {{ $alert['value'] > 0 ? 'bg-amber-50/60 border-amber-200/70' : 'bg-slate-50/50 border-slate-100' }} flex items-start justify-between gap-3">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-carbon-black-900">{{ $alert['title'] }}</span>
                                        @if($alert['value'] > 0)
                                            <span
                                                class="px-1.5 py-0.2 bg-amber-200 text-amber-900 font-black text-[10px] rounded">Periksa</span>
                                        @else
                                            <span
                                                class="px-1.5 py-0.2 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded">OK</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium">{{ $alert['description'] }}</p>
                                </div>
                                <span
                                    class="text-sm font-black {{ $alert['value'] > 0 ? 'text-amber-700' : 'text-slate-400' }}">
                                    {{ $alert['value'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style>
        #chart-scroll-container::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('chart-scroll-container');
            if (!container) return;

            // Auto-scroll ke posisi paling kanan (bulan berjalan / terbaru) saat halaman dimuat
            setTimeout(() => {
                container.scrollLeft = container.scrollWidth;
                updateChartNav();
            }, 50);

            // Perbarui status tombol navigasi saat di-scroll
            const updateChartNav = () => {
                const btnPrev = document.getElementById('btn-chart-prev');
                const btnNext = document.getElementById('btn-chart-next');
                if (!btnPrev || !btnNext) return;

                const maxScroll = container.scrollWidth - container.clientWidth;
                const atStart = container.scrollLeft <= 5;
                const atEnd = container.scrollLeft >= maxScroll - 5;

                btnPrev.style.opacity = atStart ? '0.3' : '1';
                btnPrev.style.pointerEvents = atStart ? 'none' : 'auto';

                btnNext.style.opacity = atEnd ? '0.3' : '1';
                btnNext.style.pointerEvents = atEnd ? 'none' : 'auto';
            };

            container.addEventListener('scroll', updateChartNav);

            // Dukungan drag-to-scroll dengan mouse
            let isDown = false;
            let startX, scrollLeftPos;

            container.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - container.offsetLeft;
                scrollLeftPos = container.scrollLeft;
            });

            window.addEventListener('mouseup', () => isDown = false);

            container.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const walk = (x - startX) * 1.5;
                container.scrollLeft = scrollLeftPos - walk;
            });

            // Toggle Popover Rincian Bagian Karyawan Aktif
            const btnInfoBagian = document.getElementById('btn-info-bagian');
            const popoverInfoBagian = document.getElementById('popover-info-bagian');
            const wrapperInfoBagian = document.getElementById('karyawan-info-wrapper');

            if (btnInfoBagian && popoverInfoBagian) {
                btnInfoBagian.addEventListener('click', function (e) {
                    e.stopPropagation();
                    popoverInfoBagian.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!popoverInfoBagian.contains(e.target) && !btnInfoBagian.contains(e.target)) {
                        popoverInfoBagian.classList.add('hidden');
                    }
                });
            }
        });

        function scrollPayrollChart(direction) {
            const container = document.getElementById('chart-scroll-container');
            if (container) {
                // Geser sekitar 2-3 kolom bulan setiap kali tombol diklik
                const amount = container.clientWidth * 0.5;
                container.scrollBy({ left: direction * amount, behavior: 'smooth' });
            }
        }
    </script>
@endsection