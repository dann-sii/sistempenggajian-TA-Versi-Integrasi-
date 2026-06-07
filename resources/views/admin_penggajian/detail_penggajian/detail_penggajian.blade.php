@extends('layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            DETAIL PENGGAJIAN
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
            <!-- No top button in wireframe -->
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA KARYAWAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">KOMPONEN GAJI</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JUMLAH</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">KETERANGAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @foreach($allDetail as $d)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $d['id'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $d['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $d['komponen_gaji'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ number_format($d['jumlah'], 2, '.', '') }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $d['keterangan'] }}</td>
                        <td class="px-6 py-5 text-center text-base">
                            @php
                                $statusColor = match(strtolower($d['status'])) {
                                    'dibayar' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                {{ $d['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                @if(count($allDetail) == 0)
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-carbon-black-500 font-medium">Tidak ada data detail penggajian yang ditemukan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Bottom Button - Cetak -->
    <div class="flex justify-end pt-4">
        <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center active:scale-95 group uppercase tracking-widest">
            <span>Cetak Slip Gaji</span>
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
