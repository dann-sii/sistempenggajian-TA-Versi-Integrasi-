@extends('layouts.app')

@section('title', 'Laporan Penggajian')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            LAPORAN PENGGAJIAN
        </h1>

        <div class="flex justify-between items-end">
            <!-- Search Bar -->
            <div class="flex items-center space-x-4">
                <label class="text-sm font-black uppercase tracking-widest text-carbon-black-800">Nama Karyawan</label>
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" id="search" value="{{ request('search') }}" class="w-64 px-4 py-2 focus:outline-none text-carbon-black-800 text-sm font-medium" autocomplete="off">
                    <button id="search-btn" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-x-auto relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA KARYAWAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JUMLAH HARI KERJA</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JUMLAH LEMBUR</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">GAJI POKOK</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TOTAL LEMBUR</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @foreach($allLaporan as $l)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $l['id'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $l['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $l['jumlah_hari_kerja'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $l['jumlah_lembur'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ number_format($l['gaji_pokok'], 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ number_format($l['total_lembur'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @if(count($allLaporan) == 0)
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-carbon-black-500 font-medium">Tidak ada data laporan penggajian yang ditemukan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Bottom Buttons - Simpan, Cetak, Kirim -->
    <div class="flex justify-end space-x-3 pt-4">
        <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center active:scale-95 uppercase tracking-widest">
            <span>Simpan</span>
        </button>
        <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center active:scale-95 uppercase tracking-widest">
            <span>Cetak</span>
        </button>
        <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center active:scale-95 uppercase tracking-widest">
            <span>Kirim</span>
        </button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');

        function triggerSearch() {
            let url = new URL(window.location.href);
            if (searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }

        searchBtn.addEventListener('click', triggerSearch);
        
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                triggerSearch();
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
