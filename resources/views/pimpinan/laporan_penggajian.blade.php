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
                        <input type="text" id="search" value="{{ request('search') }}" placeholder="Nama / ID penggajian" class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 text-sm font-medium" autocomplete="off">
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

                <!-- TODO: Fitur Excel ditunda untuk pengembangan semester depan
                <button type="button" class="inline-flex items-center justify-center gap-2 px-5 h-[42px] bg-black-forest-700 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-md hover:bg-black-forest-800 active:scale-95 transition-all">
                    <x-tabler-file-import class="w-4 h-4" />
                    Excel
                </button>
                -->
            </div>
        </div>
    </div>

    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-x-auto relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
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
                        <td colspan="8" class="px-6 py-8 text-center text-carbon-black-500 text-base font-semibold">Tidak ada data laporan penggajian yang ditemukan.</td>
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
                    <button type="button" onclick="printLaporan()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-carbon-black-200 text-carbon-black-600 text-xs font-black uppercase tracking-widest rounded-lg shadow-sm hover:border-carbon-black-300 hover:bg-carbon-black-50 hover:text-carbon-black-900 active:scale-95 transition-all">
                        <x-tabler-printer class="w-4 h-4" />
                        Cetak Laporan
                    </button>
                    <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeLaporanModal()">
                        <x-tabler-x class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="rounded-xl border border-carbon-black-100 bg-carbon-black-50 p-6">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Nama Karyawan</p>
                        <p id="modal-nama-karyawan" class="mt-1.5 text-base font-black text-carbon-black-900">-</p>
                    </div>
                    <div class="rounded-xl border border-carbon-black-100 bg-carbon-black-50 p-6">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Bagian</p>
                        <p id="modal-bagian" class="mt-1.5 text-base font-black text-carbon-black-900">-</p>
                    </div>
                    <div class="rounded-xl border border-carbon-black-100 bg-carbon-black-50 p-6">
                        <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Tanggal Pembayaran</p>
                        <p id="modal-tanggal" class="mt-1.5 text-base font-black text-carbon-black-900">-</p>
                    </div>
                </div>

                <div class="border border-carbon-black-100 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100">
                        <h4 class="text-sm font-black uppercase tracking-widest text-carbon-black-600">Ringkasan Total Komponen</h4>
                    </div>
                    <div class="grid grid-cols-2 xl:grid-cols-4 divide-y xl:divide-y-0 xl:divide-x divide-carbon-black-100 bg-white">
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Gaji Pokok</p>
                            <p class="mt-2 text-lg font-black text-carbon-black-900">Rp <span id="modal-gaji-pokok">0</span></p>
                        </div>
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Total Lembur</p>
                            <p class="mt-2 text-lg font-black text-carbon-black-900">Rp <span id="modal-total-lembur">0</span></p>
                        </div>
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Total Bonus</p>
                            <p class="mt-2 text-lg font-black text-carbon-black-900">Rp <span id="modal-total-bonus">0</span></p>
                        </div>
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Total Potongan</p>
                            <p class="mt-2 text-lg font-black text-rose-600">Rp <span id="modal-total-potongan">0</span></p>
                        </div>
                    </div>
                </div>

                <div class="border border-carbon-black-100 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100">
                        <p class="text-sm font-black uppercase tracking-widest text-carbon-black-700">Rincian Komponen Gaji</p>
                        <p class="mt-1 text-xs font-semibold text-carbon-black-500 uppercase tracking-wider">Asal nominal lembur, bonus, dan potongan pada laporan ini.</p>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 divide-y xl:divide-y-0 xl:divide-x divide-carbon-black-100 bg-white">
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-hunter-green-700 mb-5">Rincian Lembur</p>
                            <div id="modal-rincian-lembur" class="space-y-4"></div>
                        </div>
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-hunter-green-700 mb-5">Rincian Bonus</p>
                            <div id="modal-rincian-bonus" class="space-y-4"></div>
                        </div>
                        <div class="p-6">
                            <p class="text-xs font-black uppercase tracking-widest text-rose-700 mb-5">Rincian Potongan</p>
                            <div id="modal-rincian-potongan" class="space-y-4"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-carbon-black-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-400">Keterangan</p>
                    <p id="modal-keterangan" class="mt-2 text-base font-semibold leading-relaxed text-carbon-black-800">-</p>
                </div>
            </div>

            <div class="px-6 py-5 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between gap-6 rounded-b-xl">
                <div>
                    <p class="text-sm font-black uppercase tracking-widest text-carbon-black-500">Total Gaji Bersih</p>
                    <p class="text-3xl font-black text-carbon-black-900 mt-1">Rp <span id="modal-total-bersih">0</span></p>
                </div>
                <span id="modal-status" class="inline-flex px-5 py-2.5 rounded-full text-sm font-black uppercase tracking-widest border shadow-sm"></span>
            </div>
        </div>
    </div>
</div>

<div id="print-laporan-container" class="hidden font-sans text-carbon-black-900 bg-white" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <!-- Print Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #e5e7eb; padding-bottom: 15px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <img src="/img/logo_st.png" alt="Logo Sahabat Ternak" style="height: 60px; width: auto; object-fit: contain;">
            <div>
                <h1 style="color: #059669; font-size: 24px; font-weight: 900; margin: 0; letter-spacing: 1px;">SAHABAT TERNAK</h1>
            </div>
        </div>
        <div style="text-align: right;">
            <h2 style="font-size: 20px; font-weight: 900; margin: 0; text-transform: uppercase; color: #111827;">Laporan Penggajian</h2>
            <p style="font-size: 11px; color: #6b7280; margin-top: 4px;">Dicetak pada: <span id="pl-tanggal-cetak"></span></p>
        </div>
    </div>

    <!-- Employee Info -->
    <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 25px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; background-color: #f9fafb;">
        <!-- Left Column -->
        <div style="width: 48%; line-height: 2;">
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Nama</span><span>: <span id="pl-nama"></span></span></div>
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Bagian</span><span>: <span id="pl-bagian"></span></span></div>
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Status Karyawan</span><span>: Karyawan</span></div>
        </div>
        <!-- Right Column -->
        <div style="width: 48%; line-height: 2;">
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Job Title</span><span>: <span id="pl-job-title"></span></span></div>
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Periode</span><span>: <span id="pl-periode"></span></span></div>
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Tgl Pembayaran</span><span>: <span id="pl-tgl"></span></span></div>
            <div style="display: flex; border-bottom: 1px border-dashed #e5e7eb;"><span style="width: 130px; font-weight: bold; color: #4b5563;">Keterangan</span><span>: <span id="pl-ket"></span></span></div>
        </div>
    </div>

    <!-- Details Table -->
    <div style="display: flex; justify-content: space-between; font-size: 12px; gap: 20px;">
        <!-- Income -->
        <div style="width: 50%; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
            <div style="background-color: #f9fafb; padding: 12px 15px; font-weight: bold; display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb; color: #111827;">
                <span>Pendapatan (Income)</span>
                <span>Jumlah (Amount)</span>
            </div>
            <div style="padding: 12px 15px; display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb;">
                <span>Gaji Pokok (Basic Salary)</span>
                <span id="pl-gapok" style="font-weight: 500;"></span>
            </div>
            <div style="padding: 12px 15px; display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb;">
                <span>Lembur (Overtime)</span>
                <span id="pl-lembur" style="font-weight: 500;"></span>
            </div>
            <div id="pl-bonus-list"></div>

            <div style="background-color: #f9fafb; padding: 12px 15px; font-weight: 900; display: flex; justify-content: space-between; border-top: 1px solid #e5e7eb; color: #111827; margin-top: auto;">
                <span>Total Pendapatan</span>
                <span id="pl-total-income"></span>
            </div>
        </div>

        <!-- Deduction -->
        <div style="width: 50%; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
            <div style="background-color: #f9fafb; padding: 12px 15px; font-weight: bold; display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb; color: #111827;">
                <span>Potongan (Deduction)</span>
                <span>Jumlah (Amount)</span>
            </div>
            <div id="pl-potongan-list" style="flex-grow: 1;"></div>

            <div style="background-color: #f9fafb; padding: 12px 15px; font-weight: 900; display: flex; justify-content: space-between; border-top: 1px solid #e5e7eb; color: #111827;">
                <span>Total Potongan</span>
                <span id="pl-total-deduction"></span>
            </div>
            <div style="background-color: #f3f4f6; padding: 15px; font-weight: 900; font-size: 14px; display: flex; justify-content: space-between; border-top: 2px solid #e5e7eb; color: #111827;">
                <span>Gaji Bersih (Take Home Pay)</span>
                <span id="pl-thp"></span>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body > :not(#print-laporan-container) {
            display: none !important;
        }
        #print-laporan-container {
            display: block !important;
            position: static;
            width: 100%;
            margin: 0 auto;
            padding: 10mm;
        }
        @page {
            size: A4 portrait;
            margin: 0;
        }
    }
</style>

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
        return `<p class="text-sm font-semibold text-carbon-black-400">${text}</p>`;
    }

    function renderLemburDetails(items) {
        if (!items || items.length === 0) {
            return emptyDetailText('Tidak ada lembur.');
        }

        return items.map(item => `
            <div class="border-b border-carbon-black-50 last:border-0 pb-4 last:pb-0">
                <p class="text-base font-black text-carbon-black-800">${escapeHtml(item.keterangan)}</p>
                <div class="flex justify-between items-end mt-1.5">
                    <p class="text-sm font-semibold text-carbon-black-500">${escapeHtml(item.jumlah_jam)} jam x Rp ${formatRupiah(item.tarif)}</p>
                    <p class="text-lg font-black text-hunter-green-700">Rp ${formatRupiah(item.subtotal)}</p>
                </div>
            </div>
        `).join('');
    }

    function renderBonusDetails(items) {
        if (!items || items.length === 0) {
            return emptyDetailText('Tidak ada bonus.');
        }

        return items.map(item => `
            <div class="border-b border-carbon-black-50 last:border-0 pb-4 last:pb-0">
                <p class="text-base font-black text-carbon-black-800">${escapeHtml(item.nama)}</p>
                <div class="flex justify-between items-end mt-1.5">
                    <p class="text-sm font-semibold text-carbon-black-500">${escapeHtml(item.jumlah)} ${escapeHtml(item.satuan)} x Rp ${formatRupiah(item.tarif)}</p>
                    <p class="text-lg font-black text-hunter-green-700">Rp ${formatRupiah(item.subtotal)}</p>
                </div>
            </div>
        `).join('');
    }

    function renderPotonganDetails(items) {
        if (!items || items.length === 0) {
            return emptyDetailText('Tidak ada potongan.');
        }

        return items.map(item => `
            <div class="border-b border-carbon-black-50 last:border-0 pb-4 last:pb-0">
                <p class="text-base font-black text-carbon-black-800">${escapeHtml(item.nama)}</p>
                <div class="flex justify-between items-end mt-1.5">
                    <p class="text-sm font-semibold text-carbon-black-500">${escapeHtml(item.jumlah)} kali x Rp ${formatRupiah(item.tarif)}</p>
                    <p class="text-lg font-black text-rose-600">- Rp ${formatRupiah(item.subtotal)}</p>
                </div>
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

        // Populate Print Template
        document.getElementById('pl-nama').textContent = laporan.nama;
        document.getElementById('pl-tanggal-cetak').textContent = new Date().toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'}) + ' ' + new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        document.getElementById('pl-bagian').textContent = laporan.bagian || '-';
        document.getElementById('pl-job-title').textContent = laporan.bagian || '-';
        document.getElementById('pl-periode').textContent = `${monthName} ${laporan.tahun}`;
        document.getElementById('pl-tgl').textContent = laporan.tanggal_pembayaran || 'Belum dibayar';
        document.getElementById('pl-ket').textContent = laporan.keterangan || '-';

        document.getElementById('pl-gapok').textContent = `Rp ${formatRupiah(laporan.gaji_pokok)}`;
        document.getElementById('pl-lembur').textContent = `Rp ${formatRupiah(laporan.total_lembur)}`;
        
        let bonusHTML = '';
        if (laporan.rincian_bonus && laporan.rincian_bonus.length > 0) {
            laporan.rincian_bonus.forEach(b => {
                bonusHTML += `<div style="padding: 12px 15px; display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb;"><span>${escapeHtml(b.nama)}</span><span style="font-weight: 500;">Rp ${formatRupiah(b.subtotal)}</span></div>`;
            });
        }
        document.getElementById('pl-bonus-list').innerHTML = bonusHTML;

        document.getElementById('pl-total-income').textContent = `Rp ${formatRupiah(Number(laporan.gaji_pokok) + Number(laporan.total_lembur) + Number(laporan.total_bonus))}`;

        let potonganHTML = '';
        if (laporan.rincian_potongan && laporan.rincian_potongan.length > 0) {
            laporan.rincian_potongan.forEach(p => {
                potonganHTML += `<div style="padding: 12px 15px; display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb;"><span>${escapeHtml(p.nama)}</span><span style="font-weight: 500;">Rp ${formatRupiah(p.subtotal)}</span></div>`;
            });
        }
        document.getElementById('pl-potongan-list').innerHTML = potonganHTML;

        document.getElementById('pl-total-deduction').textContent = `Rp ${formatRupiah(laporan.total_potongan)}`;
        document.getElementById('pl-thp').textContent = `Rp ${formatRupiah(laporan.total_gaji_bersih)}`;

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

    window.printLaporan = function() {
        const container = document.getElementById('print-laporan-container');
        document.body.appendChild(container); // Move to body root to avoid layout issues
        window.print();
    }
</script>
@endpush
@endsection
