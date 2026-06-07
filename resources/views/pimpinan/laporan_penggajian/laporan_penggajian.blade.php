@extends('layouts.app')

@section('title', 'Laporan Penggajian')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            LAPORAN PENGGAJIAN
        </h1>

        <!-- Filter Area -->
        <div class="space-y-6">
            <!-- Search Bar (Top) -->
            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">CARI NAMA KARYAWAN</label>
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white w-fit">
                    <input type="text" id="search" value="{{ request('search') }}" placeholder="Masukkan nama..." class="w-80 px-4 py-2 focus:outline-none text-carbon-black-800 text-sm font-medium" autocomplete="off">
                    <button id="search-btn" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Select Filters (Bulan & Tahun) (Bottom) -->
            <div class="flex items-center space-x-4">
                <!-- Filter Bulan -->
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">FILTER BULAN</label>
                    <div class="relative shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden flex items-center w-fit">
                        <select id="bulan-filter" class="w-48 appearance-none bg-transparent pl-4 pr-10 py-2 text-carbon-black-800 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Bulan</option>
                            <option value="01" {{ $bulan === '01' ? 'selected' : '' }}>Januari</option>
                            <option value="02" {{ $bulan === '02' ? 'selected' : '' }}>Februari</option>
                            <option value="03" {{ $bulan === '03' ? 'selected' : '' }}>Maret</option>
                            <option value="04" {{ $bulan === '04' ? 'selected' : '' }}>April</option>
                            <option value="05" {{ $bulan === '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ $bulan === '06' ? 'selected' : '' }}>Juni</option>
                            <option value="07" {{ $bulan === '07' ? 'selected' : '' }}>Juli</option>
                            <option value="08" {{ $bulan === '08' ? 'selected' : '' }}>Agustus</option>
                            <option value="09" {{ $bulan === '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $bulan === '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $bulan === '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $bulan === '12' ? 'selected' : '' }}>Desember</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100 ml-2">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                </div>

                <!-- Filter Tahun -->
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">FILTER TAHUN</label>
                    <div class="relative shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden flex items-center w-fit">
                        <select id="tahun-filter" class="w-40 appearance-none bg-transparent pl-4 pr-10 py-2 text-carbon-black-800 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Tahun</option>
                            <option value="2026" {{ $tahun === '2026' ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ $tahun === '2025' ? 'selected' : '' }}>2025</option>
                            <option value="2024" {{ $tahun === '2024' ? 'selected' : '' }}>2024</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100 ml-2">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-x-auto relative mt-6">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA KARYAWAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JUMLAH HARI KERJA</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JUMLAH LEMBUR</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">GAJI POKOK</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TOTAL LEMBUR</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @foreach($allLaporan as $l)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $l['id'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $l['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $l['jumlah_hari_kerja'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $l['jumlah_lembur'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">Rp {{ number_format($l['gaji_pokok'], 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">Rp {{ number_format($l['total_lembur'], 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center">
                                <button type="button" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat">
                                    <x-tabler-eye class="w-4 h-4" />
                                    <span>Detail</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if(count($allLaporan) == 0)
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-carbon-black-500 font-medium">Tidak ada data laporan penggajian yang ditemukan untuk filter tersebut.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        

    </div>

    <!-- Bottom Button - Excel -->
    <div class="flex justify-end pt-4">
        <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center active:scale-95 uppercase tracking-widest">
            <x-tabler-file-import class="w-5 h-5 mr-2" />
            <span>Excel</span>
        </button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const bulanFilter = document.getElementById('bulan-filter');
        const tahunFilter = document.getElementById('tahun-filter');

        function triggerFilter() {
            let url = new URL(window.location.href);
            
            if (searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            
            if (bulanFilter.value) {
                url.searchParams.set('bulan', bulanFilter.value);
            } else {
                url.searchParams.delete('bulan');
            }
            
            if (tahunFilter.value) {
                url.searchParams.set('tahun', tahunFilter.value);
            } else {
                url.searchParams.delete('tahun');
            }
            
            window.location.href = url.toString();
        }

        searchBtn.addEventListener('click', triggerFilter);
        bulanFilter.addEventListener('change', triggerFilter);
        tahunFilter.addEventListener('change', triggerFilter);
        
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                triggerFilter();
            }, 600);
        });

        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    });
</script>
@endpush
@endsection
