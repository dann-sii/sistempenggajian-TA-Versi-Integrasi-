@extends('layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            DETAIL PENGGAJIAN
        </h1>

        <div class="flex justify-between items-end">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-black uppercase tracking-widest text-carbon-black-800">Cari Penggajian</label>
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" id="search" value="{{ request('search') }}" placeholder="Nama / ID / Periode" class="w-72 px-4 py-2 focus:outline-none text-carbon-black-800 text-sm font-medium" autocomplete="off">
                    <button id="search-btn" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">ID PENGGAJIAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA KARYAWAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">PERIODE</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TOTAL KOMPONEN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TOTAL GAJI BERSIH</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @forelse($summaries as $summary)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group" data-penggajian-row="{{ $summary['id_penggajian'] }}">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-center text-base font-black text-carbon-black-900">{{ $summary['id_penggajian'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $summary['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $summary['periode'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $summary['jumlah_komponen'] }} Komponen</td>
                        <td class="px-6 py-5 text-center text-base font-black text-carbon-black-900">Rp {{ number_format($summary['total_gaji'], 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-center text-base">
                            @php
                                $statusColor = match(strtolower($summary['status'])) {
                                    'dibayar' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span data-status-badge="{{ $summary['id_penggajian'] }}" class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                {{ $summary['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <button type="button" onclick='openDetailModal(@json($summary))' class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat Detail">
                                <x-tabler-eye class="w-4 h-4" />
                                <span>Detail</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-carbon-black-500 text-base font-semibold">Tidak ada data detail penggajian yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Detail Modal -->
    <div id="modal-detail-penggajian" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Rincian Komponen Gaji</h3>
                    <p id="modal-subtitle" class="text-xs font-semibold text-carbon-black-500 mt-1">-</p>
                </div>
                <div class="flex items-center gap-6">
                    <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-md hover:bg-blue-700 active:scale-95 transition-all">
                        <x-tabler-printer class="w-4 h-4" />
                        Cetak Slip Gaji
                    </button>
                    <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeDetailModal()">
                        <x-tabler-x class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[65vh] overflow-y-auto custom-scrollbar">
                <div id="modal-detail-list" class="space-y-3"></div>
            </div>

            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between gap-8 rounded-b-xl">
                <div class="shrink-0 min-w-64">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Gaji Bersih</p>
                    <p class="text-2xl font-black text-carbon-black-900">Rp <span id="modal-total-gaji">0</span></p>
                </div>
                <div class="flex flex-1 items-center justify-end gap-3">
                    <button type="button" onclick="updatePaymentStatus('Ditolak')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-rose-700 active:scale-95 transition-all">
                        <x-tabler-x class="w-4 h-4" />
                        Tandai Ditolak
                    </button>
                    <button type="button" onclick="updatePaymentStatus('Dibayar')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-emerald-700 active:scale-95 transition-all">
                        <x-tabler-check class="w-4 h-4" />
                        Tandai Dibayar
                    </button>
                </div>
            </div>
        </div>
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
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(triggerSearch, 600);
        });

        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    });

    const detailModal = document.getElementById('modal-detail-penggajian');
    const detailModalInner = detailModal.querySelector('div');
    const modalSubtitle = document.getElementById('modal-subtitle');
    const modalDetailList = document.getElementById('modal-detail-list');
    const modalTotalGaji = document.getElementById('modal-total-gaji');
    let activePenggajianId = null;

    function formatRupiah(value) {
        return Number(value).toLocaleString('id-ID');
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function componentTone(type) {
        const normalized = String(type).toLowerCase();
        if (normalized.includes('potongan')) {
            return {
                badge: 'bg-rose-50 text-rose-700 border-rose-200',
                amount: 'text-rose-600',
                prefix: '- ',
            };
        }

        if (normalized.includes('bonus')) {
            return {
                badge: 'bg-amber-50 text-amber-700 border-amber-200',
                amount: 'text-hunter-green-700',
                prefix: '',
            };
        }

        return {
            badge: 'bg-blue-50 text-blue-700 border-blue-200',
            amount: 'text-hunter-green-700',
            prefix: '',
        };
    }

    function statusClasses(status) {
        const normalized = String(status).toLowerCase();
        if (normalized === 'dibayar') {
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }

        if (normalized === 'ditolak') {
            return 'bg-rose-50 text-rose-700 border-rose-200';
        }

        if (normalized === 'menunggu') {
            return 'bg-amber-50 text-amber-700 border-amber-200';
        }

        if (normalized === 'diproses') {
            return 'bg-blue-50 text-blue-700 border-blue-200';
        }

        return 'bg-gray-50 text-gray-700 border-gray-200';
    }

    window.updatePaymentStatus = function(status) {
        if (!activePenggajianId) {
            return;
        }

        const badge = document.querySelector(`[data-status-badge="${activePenggajianId}"]`);
        if (!badge) {
            return;
        }

        badge.className = `inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm ${statusClasses(status)}`;
        badge.textContent = status;
    }

    window.openDetailModal = function(summary) {
        activePenggajianId = summary.id_penggajian;
        modalSubtitle.textContent = `${summary.id_penggajian} - ${summary.nama} - ${summary.periode}`;
        modalTotalGaji.textContent = formatRupiah(summary.total_gaji);

        modalDetailList.innerHTML = summary.details.map(detail => {
            const tone = componentTone(detail.tipe_komponen);
            const amount = Number(detail.jumlah);
            const visibleAmount = Math.abs(amount);

            return `
                <div class="flex items-start justify-between gap-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3">
                    <div>
                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm ${tone.badge}">
                            ${escapeHtml(detail.tipe_komponen)}
                        </span>
                        <p class="mt-2 text-sm font-black text-carbon-black-900">${escapeHtml(detail.keterangan)}</p>
                        <p class="mt-1 text-xs font-semibold text-carbon-black-400">ID Detail: ${escapeHtml(detail.id_detail)}</p>
                    </div>
                    <p class="shrink-0 text-right text-base font-black ${tone.amount}">
                        ${amount < 0 ? tone.prefix : ''}Rp ${formatRupiah(visibleAmount)}
                    </p>
                </div>
            `;
        }).join('');

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
            activePenggajianId = null;
        }, 300);
    }
</script>
@endpush
@endsection
