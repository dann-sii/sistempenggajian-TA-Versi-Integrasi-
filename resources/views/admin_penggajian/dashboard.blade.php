@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex items-start justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
                DASHBOARD
            </h1>
            <p class="mt-3 text-sm font-semibold text-carbon-black-500">
                Ringkasan operasional penggajian dan presensi bulan berjalan.
            </p>
        </div>
        <div class="flex items-center gap-2 rounded-xl border border-carbon-black-200 bg-white px-4 py-3 text-sm font-black uppercase tracking-widest text-hunter-green-700 shadow-sm">
            <x-tabler-calendar-stats class="h-5 w-5" />
            {{ now()->translatedFormat('F Y') }}
        </div>
    </div>

    <div class="grid grid-cols-3 gap-5">
        <div class="rounded-xl border border-carbon-black-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Karyawan Aktif</p>
            <p class="mt-3 text-3xl font-black text-carbon-black-900">{{ count($activeKaryawan) }}</p>
        </div>
        <div class="rounded-xl border border-carbon-black-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Karyawan Inaktif</p>
            <p class="mt-3 text-3xl font-black text-carbon-black-900">{{ count($inactiveKaryawan) }}</p>
        </div>
        <div class="rounded-xl border border-carbon-black-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Data Presensi</p>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-3xl font-black text-carbon-black-950">{{ $totalKaryawanSudahPresensi }}</span>
                <span class="pb-1 text-2xl font-black text-carbon-black-400">/</span>
                <span class="pb-1 text-2xl font-black text-carbon-black-500">{{ $totalKaryawanAktif }}</span>
            </div>
            <p class="mt-2 text-xs font-bold text-carbon-black-400">karyawan sudah dipresensi</p>
        </div>
    </div>

    <div class="grid grid-cols-[1.1fr_0.9fr] gap-6">
        <section class="rounded-xl border border-carbon-black-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Status Presensi</h2>
                <x-tabler-calendar-check class="h-6 w-6 text-hunter-green-600" />
            </div>
            <div class="mt-6 space-y-4">
                @php
                    $totalPresensi = max(1, array_sum($attendanceSummary));
                    $colors = [
                        'Hadir' => 'bg-emerald-500',
                        'Izin' => 'bg-amber-500',
                        'Sakit' => 'bg-blue-500',
                        'Cuti' => 'bg-purple-500',
                        'Alpha' => 'bg-rose-500',
                    ];
                @endphp
                @foreach($attendanceSummary as $label => $value)
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm font-bold text-carbon-black-700">
                            <span>{{ $label }}</span>
                            <span>{{ $value }}</span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-carbon-black-100">
                            <div class="h-full {{ $colors[$label] }}" style="width: {{ ($value / $totalPresensi) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-xl border border-carbon-black-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Aksi Cepat</h2>
                <x-tabler-bolt class="h-6 w-6 text-hunter-green-600" />
            </div>
            <div class="mt-5 grid grid-cols-2 gap-3">
                <a href="{{ route('karyawan.index') }}" class="rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 text-sm font-black text-carbon-black-700 hover:border-hunter-green-300 hover:bg-hunter-green-50">Data Karyawan</a>
                <a href="{{ route('presensi.index') }}" class="rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 text-sm font-black text-carbon-black-700 hover:border-hunter-green-300 hover:bg-hunter-green-50">Data Presensi</a>
                <a href="{{ route('penggajian.index') }}" class="rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 text-sm font-black text-carbon-black-700 hover:border-hunter-green-300 hover:bg-hunter-green-50">Penggajian</a>
                <a href="{{ route('laporan_penggajian.index') }}" class="rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 text-sm font-black text-carbon-black-700 hover:border-hunter-green-300 hover:bg-hunter-green-50">Laporan</a>
            </div>
            <div class="mt-6 rounded-lg border border-carbon-black-100 bg-carbon-black-50 px-4 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Bagian Karyawan Aktif</p>
                    <x-tabler-users-group class="h-5 w-5 text-hunter-green-600" />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-lg border border-hunter-green-100 bg-white px-4 py-3 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Produksi</p>
                                <p class="mt-1 text-3xl font-black text-carbon-black-900">{{ $bagianCounts['Produksi'] ?? 0 }}</p>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-hunter-green-50 text-hunter-green-700">
                                <x-tabler-tools class="h-5 w-5" />
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-hunter-green-100 bg-white px-4 py-3 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Peternakan</p>
                                <p class="mt-1 text-3xl font-black text-carbon-black-900">{{ $bagianCounts['Peternakan'] ?? 0 }}</p>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-hunter-green-50 text-hunter-green-700">
                                <x-tabler-home-stats class="h-5 w-5" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="rounded-xl border border-carbon-black-100 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Jam Lembur Tertinggi</h2>
            <x-tabler-clock-play class="h-6 w-6 text-hunter-green-600" />
        </div>
        <div class="mt-5 overflow-hidden rounded-lg border border-carbon-black-100">
            <table class="w-full border-collapse">
                <thead class="bg-carbon-black-50 text-carbon-black-600">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-widest">Nama</th>
                        <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Jam Lembur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-carbon-black-100">
                    @foreach($topLembur as $item)
                        <tr>
                            <td class="px-5 py-4 text-sm font-bold text-carbon-black-800">{{ $item['nama'] }}</td>
                            <td class="px-5 py-4 text-center text-sm font-black text-hunter-green-700">{{ number_format($item['jam_lembur'], 1, ',', '.') }} Jam</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
