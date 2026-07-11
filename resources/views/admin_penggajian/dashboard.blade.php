@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex items-start justify-between gap-6">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                DASHBOARD
            </h1>
            <p class="mt-2 text-sm font-medium text-slate-500">
                Ringkasan operasional penggajian dan presensi bulan berjalan.
            </p>
        </div>
        <div class="flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-black uppercase tracking-widest text-hunter-green-700 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:-translate-y-1">
            <x-tabler-calendar-stats class="h-5 w-5" />
            {{ now()->translatedFormat('F Y') }}
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="rounded-2xl bg-white p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-hunter-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400 relative z-10">KARYAWAN AKTIF</p>
            <p class="mt-3 text-4xl font-black text-carbon-black-900 relative z-10">{{ count($activeKaryawan) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400 relative z-10">KARYAWAN INAKTIF</p>
            <p class="mt-3 text-4xl font-black text-carbon-black-900 relative z-10">{{ count($inactiveKaryawan) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400 relative z-10">DATA PRESENSI</p>
            <div class="mt-3 flex items-end gap-2 relative z-10">
                <span class="text-4xl font-black text-carbon-black-950">{{ $totalKaryawanSudahPresensi }}</span>
                <span class="pb-1 text-2xl font-black text-slate-300">/</span>
                <span class="pb-1 text-2xl font-black text-slate-400">{{ $totalKaryawanAktif }}</span>
            </div>
            <p class="mt-2 text-xs font-bold text-carbon-black-400 relative z-10">karyawan sudah dipresensi</p>
        </div>
    </div>

    <div class="grid grid-cols-[1.1fr_0.9fr] gap-6">
        <section class="rounded-2xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
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
                            <div class="h-full rounded-full {{ $colors[$label] }} transition-all duration-1000 ease-out" style="width: {{ ($value / $totalPresensi) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl bg-white p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">BAGIAN KARYAWAN AKTIF</h2>
                <div class="p-2 bg-hunter-green-50 text-hunter-green-600 rounded-lg">
                    <x-tabler-users-group class="h-5 w-5" />
                </div>
            </div>
            <div class="mt-6 flex flex-col gap-4">
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5 transition-all hover:bg-hunter-green-50/30 hover:border-hunter-green-100 group">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">PRODUKSI</p>
                            <p class="mt-1 text-3xl font-black text-carbon-black-900">{{ $bagianCounts['Produksi'] ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-hunter-green-600 shadow-sm border border-slate-100 group-hover:border-hunter-green-200 transition-colors">
                            <x-tabler-tools class="h-6 w-6" />
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5 transition-all hover:bg-hunter-green-50/30 hover:border-hunter-green-100 group">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">PETERNAKAN</p>
                            <p class="mt-1 text-3xl font-black text-carbon-black-900">{{ $bagianCounts['Peternakan'] ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-hunter-green-600 shadow-sm border border-slate-100 group-hover:border-hunter-green-200 transition-colors">
                            <x-tabler-home-stats class="h-6 w-6" />
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5 transition-all hover:bg-hunter-green-50/30 hover:border-hunter-green-100 group">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">PACKING</p>
                            <p class="mt-1 text-3xl font-black text-carbon-black-900">{{ $bagianCounts['Packing'] ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-hunter-green-600 shadow-sm border border-slate-100 group-hover:border-hunter-green-200 transition-colors">
                            <x-tabler-package class="h-6 w-6" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
