@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="flex flex-col space-y-8 overflow-hidden">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            DATA KARYAWAN
        </h1>
        
        <div class="flex justify-between items-end">
            <div class="flex items-end space-x-6">
                <!-- Status Filter -->
                <div class="relative min-w-48 shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden flex items-center">
                    <div class="pl-3 text-carbon-black-400">
                        <x-tabler-filter class="h-4 w-4" />
                    </div>
                    <select id="status-filter" class="w-full appearance-none bg-transparent pl-2 pr-10 py-2 text-carbon-black-300 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                        <option value="">Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="inaktif" {{ request('status') === 'inaktif' ? 'selected' : '' }}>Inaktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100 ml-2">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" id="search" value="{{ request('search') }}" placeholder="Cari..." class="w-72 px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-base font-medium" autocomplete="off">
                    <button id="search-btn" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative flex-1 min-h-0">
        <div class="max-h-[calc(125vh-24rem)] overflow-y-auto scrollbar-invisible">
        <table class="w-full border-collapse">
            <thead class="sticky top-0 z-10 bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-20">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NIK</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NPWP</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">ALAMAT</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NO. TELP</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @foreach($karyawan as $k)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">{{ $k['id'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $k['nik'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $k['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $k['npwp'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-800 max-w-xs truncate">{{ $k['alamat'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $k['no_telp'] }}</td>
                        <td class="px-6 py-5 text-center text-base">
                            @php
                                $statusColor = match($k['status']) {
                                    'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Inaktif' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                {{ $k['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick='openDetailModal(@json($k))' class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat">
                                    <x-tabler-eye class="w-4 h-4" />
                                    <span>Detail</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <!-- Pagination/Footer -->
        <div class="bg-carbon-black-50 border-t border-carbon-black-100 px-6 py-4 flex items-center justify-between">
            <nav role="navigation" aria-label="pagination" class="mx-auto flex w-full justify-center md:w-auto">
                <ul class="flex flex-row items-center gap-1">
                    <li>
                        <a href="{{ $page > 1 ? request()->fullUrlWithQuery(['page' => $page-1]) : '#' }}" class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 {{ $page <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                            <x-tabler-chevron-left class="h-4 w-4" />
                            <span>Sebelumnya</span>
                        </a>
                    </li>
                    @for($i = 1; $i <= $totalPages; $i++)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-md border text-sm font-medium shadow-sm transition-all {{ $page == $i ? 'bg-hunter-green-600 text-white shadow-hunter-green-200 border-hunter-green-600' : 'border-carbon-black-200 bg-white text-carbon-black-700 hover:bg-carbon-black-50' }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li>
                        <a href="{{ $page < $totalPages ? request()->fullUrlWithQuery(['page' => $page+1]) : '#' }}" class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 {{ $page >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                            <span>Berikutnya</span>
                            <x-tabler-chevron-right class="h-4 w-4" />
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Detail Karyawan -->
<div id="modal-detail" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300">
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Detail Data Karyawan</h3>
            <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeDetailModal()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>
        
        <div class="px-6 py-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Nama Lengkap</p>
                    <p id="detail-nama" class="text-base font-semibold text-carbon-black-900">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">NIK</p>
                    <p id="detail-nik" class="text-base font-semibold text-carbon-black-900">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">No. Telp</p>
                    <p id="detail-notelp" class="text-base font-semibold text-carbon-black-900">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">NPWP</p>
                    <p id="detail-npwp" class="text-base font-semibold text-carbon-black-900">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Alamat</p>
                    <p id="detail-alamat" class="text-base font-semibold text-carbon-black-900">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Status</p>
                    <p id="detail-status" class="text-base font-semibold text-carbon-black-900">-</p>
                </div>
                <div class="space-y-1 border-t border-carbon-black-100 pt-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Nama Bank</p>
                    <p id="detail-bank" class="text-base font-semibold text-carbon-black-900 text-hunter-green-700">-</p>
                </div>
                <div class="space-y-1 border-t border-carbon-black-100 pt-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">No. Rekening</p>
                    <p id="detail-rekening" class="text-base font-semibold text-carbon-black-900 text-hunter-green-700">-</p>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex justify-end rounded-b-xl">
            <button type="button" class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all" onclick="closeDetailModal()">Kembali</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('status-filter');
        
        function updateOpacity() {
            if (statusFilter.value === "") {
                statusFilter.classList.remove('text-carbon-black-800');
                statusFilter.classList.add('text-carbon-black-300');
            } else {
                statusFilter.classList.remove('text-carbon-black-300');
                statusFilter.classList.add('text-carbon-black-800');
            }
        }

        statusFilter.addEventListener('change', function() {
            updateOpacity();
            let url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('status', this.value);
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
        updateOpacity();

        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');

        function triggerSearch() {
            let url = new URL(window.location.href);
            if (searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('page', 1);
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

    // Detail Modal Control
    const detailModal = document.getElementById('modal-detail');
    const detailModalInner = detailModal.querySelector('div');
    
    window.openDetailModal = function(data) {
        document.getElementById('detail-nik').textContent = data.nik || '-';
        document.getElementById('detail-nama').textContent = data.nama || '-';
        document.getElementById('detail-npwp').textContent = data.npwp || '-';
        document.getElementById('detail-notelp').textContent = data.no_telp || '-';
        document.getElementById('detail-alamat').textContent = data.alamat || '-';
        
        let statusEl = document.getElementById('detail-status');
        statusEl.textContent = data.status || '-';
        if(data.status === 'Aktif') {
            statusEl.className = 'text-sm font-black text-emerald-600 uppercase tracking-widest';
        } else {
            statusEl.className = 'text-sm font-black text-rose-600 uppercase tracking-widest';
        }

        document.getElementById('detail-bank').textContent = data.nama_bank || 'Tidak Memiliki Data';
        document.getElementById('detail-rekening').textContent = data.no_rekening || 'Tidak Memiliki Data';

        detailModal.classList.remove('hidden');
        detailModal.classList.add('flex');
        setTimeout(() => {
            detailModal.classList.remove('opacity-0');
            detailModalInner.classList.remove('scale-95');
            detailModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeDetailModal = function() {
        detailModal.classList.add('opacity-0');
        detailModalInner.classList.remove('scale-100');
        detailModalInner.classList.add('scale-95');
        setTimeout(() => {
            detailModal.classList.add('hidden');
            detailModal.classList.remove('flex');
        }, 300);
    }
</script>
@endpush
@endsection
