@extends('layouts.app')

@section('title', 'Laporan Penggajian')

@section('content')
@php
    $monthLabels = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
@endphp

<div class="space-y-8">
    <div class="space-y-6">
        <h1 class="text-3xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            LAPORAN PENGGAJIAN
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6 gap-4">
            <div class="bg-white border border-carbon-black-200 shadow-md rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Karyawan Penerima Gaji</p>
                        <p class="mt-3 text-3xl font-black text-carbon-black-900">{{ $summary['total_karyawan'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                        <x-tabler-users class="w-5 h-5" />
                    </div>
                </div>
            </div>

            <div class="bg-white border border-carbon-black-200 shadow-md rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Gaji Pokok</p>
                        <p class="mt-3 text-base 2xl:text-lg font-black text-carbon-black-900 whitespace-nowrap">Rp {{ number_format($summary['total_gaji_pokok'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-hunter-green-50 text-hunter-green-700 flex items-center justify-center border border-hunter-green-100">
                        <x-tabler-cash class="w-5 h-5" />
                    </div>
                </div>
            </div>

            <div class="bg-white border border-carbon-black-200 shadow-md rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Lembur</p>
                        <p class="mt-3 text-base 2xl:text-lg font-black text-blue-700 whitespace-nowrap">Rp {{ number_format($summary['total_lembur'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                        <x-tabler-clock class="w-5 h-5" />
                    </div>
                </div>
            </div>

            <div class="bg-white border border-carbon-black-200 shadow-md rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Bonus</p>
                        <p class="mt-3 text-base 2xl:text-lg font-black text-amber-700 whitespace-nowrap">Rp {{ number_format($summary['total_bonus'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                        <x-tabler-gift class="w-5 h-5" />
                    </div>
                </div>
            </div>

            <div class="bg-white border border-carbon-black-200 shadow-md rounded-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Potongan</p>
                        <p class="mt-3 text-base 2xl:text-lg font-black text-rose-600 whitespace-nowrap">Rp {{ number_format($summary['total_potongan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                        <x-tabler-receipt-2 class="w-5 h-5" />
                    </div>
                </div>
            </div>

            <div class="bg-black-forest-700 border border-black-forest-800 shadow-md rounded-lg p-5 text-white">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-hunter-green-100">Total Pengeluaran Gaji</p>
                        <p class="mt-3 text-base 2xl:text-lg font-black whitespace-nowrap">Rp {{ number_format($summary['total_gaji_bersih'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white/10 text-white flex items-center justify-center border border-white/10">
                        <x-tabler-wallet class="w-5 h-5" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-carbon-black-200 p-4">
            <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_1fr_1fr_1fr_auto] gap-4 items-end">
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Cari Laporan</label>
                    <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                        <input type="text" id="search" value="{{ request('search') }}" placeholder="Nama / ID laporan / ID penggajian" class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 text-sm font-medium" autocomplete="off">
                        <button id="search-btn" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                            <x-tabler-search class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Bulan</label>
                    <div class="relative shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden">
                        <select id="bulan-filter" class="w-full appearance-none bg-transparent pl-4 pr-10 py-2 text-carbon-black-800 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Bulan</option>
                            @foreach($monthLabels as $monthValue => $monthLabel)
                                <option value="{{ $monthValue }}" {{ $bulan === $monthValue ? 'selected' : '' }}>{{ $monthLabel }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Tahun</label>
                    <div class="relative shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden">
                        <select id="tahun-filter" class="w-full appearance-none bg-transparent pl-4 pr-10 py-2 text-carbon-black-800 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Tahun</option>
                            <option value="2026" {{ $tahun === '2026' ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ $tahun === '2025' ? 'selected' : '' }}>2025</option>
                            <option value="2024" {{ $tahun === '2024' ? 'selected' : '' }}>2024</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Status</label>
                    <div class="relative shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden">
                        <select id="status-filter" class="w-full appearance-none bg-transparent pl-4 pr-10 py-2 text-carbon-black-800 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Status</option>
                            <option value="Diproses" {{ $status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Dibayar" {{ $status === 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                </div>

                <button type="button" class="inline-flex items-center justify-center gap-2 px-5 h-[42px] bg-black-forest-700 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-md hover:bg-black-forest-800 active:scale-95 transition-all">
                    <x-tabler-file-import class="w-4 h-4" />
                    Excel
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-x-auto relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">ID LAPORAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA KARYAWAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">PERIODE</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">HARI KERJA</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">LEMBUR</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TOTAL BERSIH</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @forelse($allLaporan as $l)
                    @php
                        $statusColor = match(strtolower($l['status_pembayaran'])) {
                            'dibayar' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                        };
                    @endphp
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-center text-base font-black text-carbon-black-900">{{ $l['id_laporan'] }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $l['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $monthLabels[$l['bulan']] }} {{ $l['tahun'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $l['jumlah_hari_kerja'] }} Hari</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $l['jumlah_hari_lembur'] }} Jam</td>
                        <td class="px-6 py-5 text-center text-base font-black text-carbon-black-900">Rp {{ number_format($l['total_gaji_bersih'], 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                {{ $l['status_pembayaran'] }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <button type="button" onclick='openLaporanModal(@json($l), @json($monthLabels[$l["bulan"]]))' class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat Detail">
                                <x-tabler-eye class="w-4 h-4" />
                                <span>Detail</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-carbon-black-500 font-medium">Tidak ada data laporan penggajian yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="modal-laporan" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Detail Laporan Penggajian</h3>
                    <p id="modal-subtitle" class="text-xs font-semibold text-carbon-black-500 mt-1">-</p>
                </div>
                <div class="flex items-center gap-6">
                    <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-md hover:bg-blue-700 active:scale-95 transition-all">
                        <x-tabler-printer class="w-4 h-4" />
                        Cetak Laporan
                    </button>
                    <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeLaporanModal()">
                        <x-tabler-x class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-carbon-black-100 bg-carbon-black-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Nama Karyawan</p>
                        <p id="modal-nama-karyawan" class="mt-2 text-lg font-black text-carbon-black-900">-</p>
                    </div>
                    <div class="rounded-lg border border-carbon-black-100 bg-carbon-black-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Bagian</p>
                        <p id="modal-bagian" class="mt-2 text-lg font-black text-carbon-black-900">-</p>
                    </div>
                    <div class="rounded-lg border border-carbon-black-100 bg-carbon-black-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Tanggal Pembayaran</p>
                        <p id="modal-tanggal" class="mt-2 text-lg font-black text-carbon-black-900">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="rounded-lg border border-hunter-green-100 bg-hunter-green-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-hunter-green-700">Gaji Pokok</p>
                        <p class="mt-2 text-xl font-black text-carbon-black-900">Rp <span id="modal-gaji-pokok">0</span></p>
                    </div>
                    <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-blue-700">Total Lembur</p>
                        <p class="mt-2 text-xl font-black text-carbon-black-900">Rp <span id="modal-total-lembur">0</span></p>
                    </div>
                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-amber-700">Total Bonus</p>
                        <p class="mt-2 text-xl font-black text-carbon-black-900">Rp <span id="modal-total-bonus">0</span></p>
                    </div>
                    <div class="rounded-lg border border-rose-100 bg-rose-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-rose-700">Total Potongan</p>
                        <p class="mt-2 text-xl font-black text-carbon-black-900">Rp <span id="modal-total-potongan">0</span></p>
                    </div>
                </div>

                <div class="rounded-lg border border-carbon-black-100 bg-white p-4">
                    <div class="flex items-center justify-between gap-4 border-b border-carbon-black-100 pb-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Rincian Komponen Gaji</p>
                            <p class="mt-1 text-xs font-semibold text-carbon-black-400">Asal nominal lembur, bonus, dan potongan pada laporan ini.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 pt-4">
                        <div class="rounded-lg border border-blue-100 bg-blue-50/60 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-blue-700">Rincian Lembur</p>
                            <div id="modal-rincian-lembur" class="mt-3 space-y-2"></div>
                        </div>
                        <div class="rounded-lg border border-amber-100 bg-amber-50/70 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-amber-700">Rincian Bonus</p>
                            <div id="modal-rincian-bonus" class="mt-3 space-y-2"></div>
                        </div>
                        <div class="rounded-lg border border-rose-100 bg-rose-50/70 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-rose-700">Rincian Potongan</p>
                            <div id="modal-rincian-potongan" class="mt-3 space-y-2"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-carbon-black-100 bg-white p-4">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Keterangan</p>
                    <p id="modal-keterangan" class="mt-2 text-sm font-semibold leading-relaxed text-carbon-black-800">-</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between gap-6 rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Gaji Bersih</p>
                    <p class="text-2xl font-black text-carbon-black-900">Rp <span id="modal-total-bersih">0</span></p>
                </div>
                <span id="modal-status" class="inline-flex px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm"></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const bulanFilter = document.getElementById('bulan-filter');
        const tahunFilter = document.getElementById('tahun-filter');
        const statusFilter = document.getElementById('status-filter');

        function triggerFilter() {
            const url = new URL(window.location.href);

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

            if (statusFilter.value) {
                url.searchParams.set('status', statusFilter.value);
            } else {
                url.searchParams.delete('status');
            }

            window.location.href = url.toString();
        }

        searchBtn.addEventListener('click', triggerFilter);
        bulanFilter.addEventListener('change', triggerFilter);
        tahunFilter.addEventListener('change', triggerFilter);
        statusFilter.addEventListener('change', triggerFilter);

        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(triggerFilter, 600);
        });

        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    });

    const laporanModal = document.getElementById('modal-laporan');
    const laporanModalInner = laporanModal.querySelector('div');

    function formatRupiah(value) {
        return Number(value).toLocaleString('id-ID');
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function emptyDetailText(text) {
        return `<p class="text-xs font-semibold text-carbon-black-400">${text}</p>`;
    }

    function renderLemburDetails(items) {
        if (!items || items.length === 0) {
            return emptyDetailText('Tidak ada lembur.');
        }

        return items.map(item => `
            <div class="rounded-lg border border-blue-100 bg-white px-3 py-2">
                <p class="text-sm font-black text-carbon-black-900">${escapeHtml(item.keterangan)}</p>
                <p class="mt-1 text-xs font-semibold text-carbon-black-500">${escapeHtml(item.jumlah_jam)} jam x Rp ${formatRupiah(item.tarif)}</p>
                <p class="mt-2 text-sm font-black text-blue-700">Rp ${formatRupiah(item.subtotal)}</p>
            </div>
        `).join('');
    }

    function renderBonusDetails(items) {
        if (!items || items.length === 0) {
            return emptyDetailText('Tidak ada bonus.');
        }

        return items.map(item => `
            <div class="rounded-lg border border-amber-100 bg-white px-3 py-2">
                <p class="text-sm font-black text-carbon-black-900">${escapeHtml(item.nama)}</p>
                <p class="mt-1 text-xs font-semibold text-carbon-black-500">${escapeHtml(item.jumlah)} ${escapeHtml(item.satuan)} x Rp ${formatRupiah(item.tarif)}</p>
                <p class="mt-2 text-sm font-black text-amber-700">Rp ${formatRupiah(item.subtotal)}</p>
            </div>
        `).join('');
    }

    function renderPotonganDetails(items) {
        if (!items || items.length === 0) {
            return emptyDetailText('Tidak ada potongan.');
        }

        return items.map(item => `
            <div class="rounded-lg border border-rose-100 bg-white px-3 py-2">
                <p class="text-sm font-black text-carbon-black-900">${escapeHtml(item.nama)}</p>
                <p class="mt-1 text-xs font-semibold text-carbon-black-500">${escapeHtml(item.jumlah)} kali x Rp ${formatRupiah(item.tarif)}</p>
                <p class="mt-2 text-sm font-black text-rose-600">- Rp ${formatRupiah(item.subtotal)}</p>
            </div>
        `).join('');
    }

    function statusClasses(status) {
        const normalized = String(status).toLowerCase();
        if (normalized === 'dibayar') {
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }

        if (normalized === 'ditolak') {
            return 'bg-rose-50 text-rose-700 border-rose-200';
        }

        if (normalized === 'diproses') {
            return 'bg-blue-50 text-blue-700 border-blue-200';
        }

        return 'bg-gray-50 text-gray-700 border-gray-200';
    }

    window.openLaporanModal = function(laporan, monthName) {
        document.getElementById('modal-subtitle').textContent = `${laporan.id_laporan} - ${laporan.nama} - ${monthName} ${laporan.tahun}`;
        document.getElementById('modal-nama-karyawan').textContent = laporan.nama;
        document.getElementById('modal-bagian').textContent = laporan.bagian || '-';
        document.getElementById('modal-tanggal').textContent = laporan.tanggal_pembayaran || 'Belum dibayar';
        document.getElementById('modal-gaji-pokok').textContent = formatRupiah(laporan.gaji_pokok);
        document.getElementById('modal-total-lembur').textContent = formatRupiah(laporan.total_lembur);
        document.getElementById('modal-total-bonus').textContent = formatRupiah(laporan.total_bonus);
        document.getElementById('modal-total-potongan').textContent = formatRupiah(laporan.total_potongan);
        document.getElementById('modal-total-bersih').textContent = formatRupiah(laporan.total_gaji_bersih);
        document.getElementById('modal-keterangan').textContent = laporan.keterangan;
        document.getElementById('modal-rincian-lembur').innerHTML = renderLemburDetails(laporan.rincian_lembur);
        document.getElementById('modal-rincian-bonus').innerHTML = renderBonusDetails(laporan.rincian_bonus);
        document.getElementById('modal-rincian-potongan').innerHTML = renderPotonganDetails(laporan.rincian_potongan);

        const statusBadge = document.getElementById('modal-status');
        statusBadge.className = `inline-flex px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm ${statusClasses(laporan.status_pembayaran)}`;
        statusBadge.textContent = laporan.status_pembayaran;

        laporanModal.classList.remove('hidden');
        laporanModal.classList.add('flex');
        setTimeout(() => {
            laporanModal.classList.remove('opacity-0');
            laporanModalInner.classList.remove('scale-95');
            laporanModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeLaporanModal = function() {
        laporanModal.classList.add('opacity-0');
        laporanModalInner.classList.remove('scale-100');
        laporanModalInner.classList.add('scale-95');
        setTimeout(() => {
            laporanModal.classList.add('hidden');
            laporanModal.classList.remove('flex');
        }, 300);
    }
</script>
@endpush
@endsection
