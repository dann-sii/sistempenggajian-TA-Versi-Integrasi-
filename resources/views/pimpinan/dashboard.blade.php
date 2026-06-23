@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-2">
        <h1 class="text-3xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            DASHBOARD OWNER
        </h1>
        <p class="text-carbon-black-500 font-medium pl-6">Ringkasan data dan performa perusahaan</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-carbon-black-100 flex items-center space-x-5 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-hunter-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="bg-hunter-green-100 text-hunter-green-700 p-4 rounded-xl z-10">
                <x-tabler-users class="w-8 h-8" stroke-width="2" />
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-carbon-black-500 uppercase tracking-widest mb-1">Total Karyawan</p>
                <h3 class="text-3xl font-black text-carbon-black-900">{{ $stats['total_karyawan'] ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-carbon-black-100 flex items-center space-x-5 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="bg-emerald-100 text-emerald-600 p-4 rounded-xl z-10">
                <x-tabler-user-check class="w-8 h-8" stroke-width="2" />
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-carbon-black-500 uppercase tracking-widest mb-1">Hadir Hari Ini</p>
                <h3 class="text-3xl font-black text-carbon-black-900">{{ $stats['hadir_hari_ini'] ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-carbon-black-100 flex items-center space-x-5 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="bg-amber-100 text-amber-600 p-4 rounded-xl z-10">
                <x-tabler-user-off class="w-8 h-8" stroke-width="2" />
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-carbon-black-500 uppercase tracking-widest mb-1">Izin / Sakit</p>
                <h3 class="text-3xl font-black text-carbon-black-900">{{ $stats['izin_sakit'] ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-carbon-black-100 flex items-center space-x-5 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="bg-rose-100 text-rose-600 p-4 rounded-xl z-10">
                <x-tabler-user-minus class="w-8 h-8" stroke-width="2" />
            </div>
            <div class="z-10">
                <p class="text-xs font-bold text-carbon-black-500 uppercase tracking-widest mb-1">Karyawan Inaktif</p>
                <h3 class="text-3xl font-black text-carbon-black-900">{{ $stats['karyawan_inaktif'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Extra Section (E.g. Recent Activity) -->
    <div class="bg-white rounded-2xl shadow-xl border border-carbon-black-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-carbon-black-100 bg-carbon-black-50/50 flex justify-between items-center">
            <h2 class="text-lg font-black text-carbon-black-900 uppercase tracking-wide">Laporan Aktivitas Penggajian</h2>
            <button class="text-xs font-bold text-hunter-green-600 hover:text-hunter-green-800 uppercase tracking-widest">Lihat Semua</button>
        </div>
        <div class="p-8">
            <div class="flex flex-col items-center justify-center py-12 text-carbon-black-400">
                <x-tabler-chart-bar class="w-16 h-16 mb-4 opacity-50" stroke-width="1.5" />
                <p class="font-medium text-center max-w-sm">Grafik statistik atau laporan aktivitas bulan ini akan ditampilkan di area ini.</p>
            </div>
        </div>
    </div>

    <section class="bg-white rounded-2xl shadow-xl border border-carbon-black-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-carbon-black-100 bg-carbon-black-50/50 flex justify-between items-center">
            <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Jam Lembur Tertinggi</h2>
            <x-tabler-clock-play class="h-6 w-6 text-hunter-green-600" />
        </div>
        <div class="p-0">
            <table class="w-full border-collapse">
                <thead class="bg-carbon-black-50 text-carbon-black-600 border-b border-carbon-black-100">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-black uppercase tracking-widest">Nama</th>
                        <th class="px-8 py-4 text-center text-xs font-black uppercase tracking-widest">Jam Lembur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-carbon-black-100">
                    @foreach($topLembur as $item)
                        <tr class="hover:bg-carbon-black-50 transition-colors">
                            <td class="px-8 py-4 text-sm font-bold text-carbon-black-800">{{ $item['nama'] }}</td>
                            <td class="px-8 py-4 text-center text-sm font-black text-hunter-green-700">{{ number_format($item['jam_lembur'], 1, ',', '.') }} Jam</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
