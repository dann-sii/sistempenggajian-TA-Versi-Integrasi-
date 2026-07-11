@extends('layouts.app')

@section('title', 'Penggajian Karyawan')

@section('content')
    @php
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $currentMonthIndex = date('n') - 1;
        $currentYear = (int) date('Y');
        $activeKaryawans = array_values(array_filter($karyawans ?? [], function ($karyawan) {
            return strtolower($karyawan['status'] ?? '') === 'aktif';
        }));
        $bagianOptions = array_values(array_unique(array_filter(array_map(function ($karyawan) use ($karyawanLookup) {
            return $karyawanLookup[$karyawan['id']]['bagian'] ?? ($karyawan['bagian'] ?? null);
        }, $activeKaryawans))));
        sort($bagianOptions);
    @endphp

    <div class="min-h-[calc(125vh-10rem)] overflow-visible flex flex-col gap-4">
        <div class="flex flex-shrink-0 items-start justify-between gap-6">
            <div>
                <h1
                    class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                    PENGGAJIAN KARYAWAN
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <select id="periode-bulan-select"
                        class="w-48 appearance-none px-4 py-3.5 bg-white border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-xl shadow-sm">
                        @foreach($months as $index => $month)
                            <option value="{{ $month }}" data-month-number="{{ $index + 1 }}" {{ $index === $currentMonthIndex ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>

                <div class="relative">
                    <select id="periode-tahun-select"
                        class="w-40 appearance-none px-4 py-3.5 bg-white border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-xl shadow-sm">
                        @for($year = $currentYear - 5; $year <= $currentYear + 5; $year++)
                            <option value="{{ $year }}" {{ $year === $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>
            </div>
        </div>

        <section id="payroll-detail-panel"
            class="flex-shrink-0 rounded-2xl border border-carbon-black-100 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-[minmax(0,1fr)_360px] gap-4">
                <div class="min-w-0">
                    <div
                        class="flex items-start justify-between gap-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3">
                        <div>
                            <h2 id="detail-employee-name" class="mt-1 text-3xl font-black text-carbon-black-900">Pilih
                                karyawan</h2>
                            <p id="detail-employee-meta" class="mt-1 text-sm font-bold text-carbon-black-500">Klik salah
                                satu baris tabel untuk melihat rincian.</p>
                        </div>
                        <span id="detail-status-badge"
                            class="rounded-full border border-carbon-black-200 bg-carbon-black-50 px-4 py-1.5 text-sm font-black uppercase tracking-widest text-carbon-black-500">
                            Belum Dibayar
                        </span>
                    </div>

                    <div id="payroll-detail-grid" class="mt-3 grid grid-cols-3 gap-3">
                        <div
                            class="rounded-xl border border-carbon-black-100 bg-white p-3 shadow-[0_1px_0_rgba(0,0,0,0.03)]">
                            <div class="flex items-center gap-2 border-b border-carbon-black-100 pb-2">
                                <x-tabler-id class="h-4 w-4 text-hunter-green-600" />
                                <p class="text-sm font-black uppercase tracking-widest text-carbon-black-500">Identitas</p>
                            </div>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black uppercase tracking-widest text-carbon-black-400">ID
                                        Penggajian</span>
                                    <span id="detail-id-penggajian"
                                        class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black uppercase tracking-widest text-carbon-black-400">ID
                                        Karyawan</span>
                                    <span id="detail-id-karyawan" class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Tanggal
                                        Bayar</span>
                                    <span id="detail-tanggal-pembayaran"
                                        class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-xl border border-carbon-black-100 bg-white p-3 shadow-[0_1px_0_rgba(0,0,0,0.03)]">
                            <div class="flex items-center gap-2 border-b border-carbon-black-100 pb-2">
                                <x-tabler-calendar-stats class="h-4 w-4 text-hunter-green-600" />
                                <p class="text-sm font-black uppercase tracking-widest text-carbon-black-500">Periode &
                                    Waktu</p>
                            </div>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Bulan</span>
                                    <span id="detail-periode-bulan"
                                        class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Tahun</span>
                                    <span id="detail-periode-tahun"
                                        class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Hari
                                        Kerja</span>
                                    <span id="detail-hari-kerja" class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Lembur</span>
                                    <span id="detail-jumlah-lembur"
                                        class="text-lg font-black text-carbon-black-900">-</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-xl border border-carbon-black-100 bg-white p-3 shadow-[0_1px_0_rgba(0,0,0,0.03)]">
                            <div class="flex items-center gap-2 border-b border-carbon-black-100 pb-2">
                                <x-tabler-report-money class="h-4 w-4 text-hunter-green-600" />
                                <p class="text-sm font-black uppercase tracking-widest text-carbon-black-500">Komponen</p>
                            </div>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Gaji
                                        Pokok</span>
                                    <span id="detail-gaji-pokok" class="text-lg font-black text-carbon-black-900">Rp
                                        -</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Lembur</span>
                                    <span id="detail-total-lembur" class="text-lg font-black text-carbon-black-900">Rp
                                        -</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Bonus</span>
                                    <span id="detail-total-bonus" class="text-lg font-black text-hunter-green-700">Rp
                                        -</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-carbon-black-400">Potongan</span>
                                    <span id="detail-total-potongan" class="text-lg font-black text-rose-600">Rp -</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-sm font-black uppercase tracking-widest text-carbon-black-500">Rincian Komponen Gaji
                        </p>
                        <p class="text-sm font-bold text-carbon-black-400">Menyesuaikan jumlah rincian</p>
                    </div>
                    <div id="salary-detail-list"
                        class="mt-2 grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-2 overflow-visible">
                        <div
                            class="rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-2 text-sm font-bold text-carbon-black-400">
                            Detail akan tampil setelah baris karyawan dipilih.
                        </div>
                    </div>
                </div>

                <div
                    class="grid h-full grid-cols-2 grid-rows-[1fr_1fr_auto_auto] gap-3 rounded-xl border border-carbon-black-100 bg-carbon-black-50 p-3">
                    <div
                        class="col-span-2 flex flex-col justify-center rounded-xl border border-carbon-black-100 bg-white p-4">
                        <p class="text-base font-black uppercase tracking-widest text-carbon-black-400">Total Gaji</p>
                        <p class="mt-2 text-2xl font-black text-carbon-black-900" id="detail-gross-total">Rp -</p>
                    </div>
                    <div
                        class="col-span-2 flex flex-col justify-center rounded-xl bg-gradient-to-br from-black-forest-900 to-black-forest-800 p-4 text-white shadow-md">
                        <p class="text-base font-black uppercase tracking-widest text-white/60">Total Gaji Bersih</p>
                        <p class="mt-2 text-2xl font-black" id="detail-net-total">Rp -</p>
                    </div>
                    <button type="button" id="detail-edit-bonus"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-hunter-green-200 bg-hunter-green-50 px-4 py-3 text-xs font-black uppercase tracking-widest text-hunter-green-700 transition-colors hover:bg-hunter-green-100 disabled:cursor-not-allowed disabled:opacity-50"
                        disabled>
                        <x-tabler-pencil class="h-4 w-4" />
                        Edit Bonus
                    </button>
                    <button type="button" id="detail-edit-potongan"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-black uppercase tracking-widest text-rose-700 transition-colors hover:bg-rose-100 disabled:cursor-not-allowed disabled:opacity-50"
                        disabled>
                        <x-tabler-pencil class="h-4 w-4" />
                        Edit Potongan
                    </button>
                    <button type="button" id="detail-save-payroll"
                        class="col-span-2 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-hunter-green-500 to-hunter-green-600 px-5 py-3 text-sm font-black uppercase tracking-widest text-white shadow-[0_8px_20px_rgba(98,164,91,0.3)] transition-all hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(98,164,91,0.5)] active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:shadow-[0_8px_20px_rgba(98,164,91,0.3)] disabled:active:scale-100">
                        <x-tabler-check class="h-5 w-5" />
                        Simpan
                    </button>
                </div>
            </div>
        </section>

        <section class="flex-none overflow-visible rounded-none border border-carbon-black-200 bg-white shadow-xl">
            <div
                class="flex items-center justify-between gap-4 border-b border-carbon-black-100 bg-carbon-black-50 px-4 py-2">
                <div
                    class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white w-full max-w-md">
                    <input type="text" id="payroll-search" placeholder="Cari nama karyawan..."
                        class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-sm font-semibold"
                        autocomplete="off">
                    <div
                        class="grid place-items-center bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400">
                        <x-tabler-search class="w-4 h-4" />
                    </div>
                </div>

                <div class="relative w-52 shrink-0">
                    <select id="payroll-bagian-filter"
                        class="w-full appearance-none rounded-lg border border-carbon-black-200 bg-white px-3 py-2 pr-10 text-sm font-semibold text-carbon-black-800 transition-all focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                        <option value="">Semua Bagian</option>
                        @foreach($bagianOptions as $bagian)
                            <option value="{{ $bagian }}">{{ $bagian }}</option>
                        @endforeach
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto overflow-y-visible scrollbar-invisible">
                <table class="min-w-[980px] w-full border-collapse">
                    <thead
                        class="sticky top-0 z-10 border-b border-carbon-black-200 bg-carbon-black-50 text-carbon-black-600">
                        <tr>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider">Bagian</th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider">Hari Kerja</th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider">Total Gaji Bersih
                            </th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider">Status Pembayaran
                            </th>
                            <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="payroll-table-body" class="divide-y divide-carbon-black-100">
                        @forelse($activeKaryawans as $index => $karyawan)
                            @php
                                $lookup = $karyawanLookup[$karyawan['id']] ?? [];
                            @endphp
                            <tr class="payroll-row cursor-pointer transition-colors hover:bg-hunter-green-50"
                                data-karyawan-id="{{ $karyawan['id'] }}" data-karyawan-name="{{ $karyawan['nama'] }}"
                                data-karyawan-bagian="{{ $lookup['bagian'] ?? ($karyawan['bagian'] ?? '-') }}"
                                data-row-index="{{ $index + 1 }}">
                                <td class="px-4 py-5 text-left text-base font-bold text-carbon-black-900 bg-carbon-black-50/50"
                                    data-field="nama_karyawan">{{ $karyawan['nama'] }}</td>
                                <td class="px-4 py-5 text-center text-base font-semibold text-carbon-black-800"
                                    data-field="bagian">{{ $lookup['bagian'] ?? ($karyawan['bagian'] ?? '-') }}</td>
                                <td class="px-4 py-5 text-center text-base font-semibold text-carbon-black-800"
                                    data-field="periode">{{ $months[$currentMonthIndex] }} {{ $currentYear }}</td>
                                <td class="px-4 py-5 text-center text-base font-semibold text-carbon-black-800"
                                    data-field="jumlah_hari_kerja">{{ $lookup['hari_kerja'] ?? 0 }}</td>
                                <td class="px-4 py-5 text-right text-base font-black text-carbon-black-900"
                                    data-field="total_gaji_bersih">Rp 0</td>
                                <td class="px-4 py-5 text-center" data-field="status_pembayaran">
                                    <span
                                        class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11px] font-black uppercase tracking-widest text-amber-600">Belum
                                        Dibayar</span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-action="process" data-karyawan-id="{{ $karyawan['id'] }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-hunter-green-200 bg-hunter-green-50 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-hunter-green-700 transition-colors hover:bg-hunter-green-100">
                                            <x-tabler-check class="h-4 w-4" />
                                            Proses
                                        </button>
                                        <button type="button" data-action="slip" data-karyawan-id="{{ $karyawan['id'] }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-amber-600 transition-colors hover:bg-amber-100">
                                            <x-tabler-eye class="h-4 w-4" />
                                            Slip
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm font-bold text-carbon-black-400">
                                    Tidak ada karyawan aktif untuk diproses.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="payroll-empty-filter-row" class="hidden">
                            <td colspan="7" class="px-6 py-10 text-center text-base font-bold text-carbon-black-400">
                                Tidak ada data penggajian yang sesuai dengan pencarian/filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                class="flex items-center justify-between gap-4 border-t border-carbon-black-100 bg-carbon-black-50 px-4 py-3">
                <p id="payroll-pagination-info" class="text-sm font-bold text-carbon-black-500">Menampilkan 0 data</p>
                <div id="payroll-pagination" class="flex items-center justify-end gap-2"></div>
            </div>
        </section>
    </div>

    <div id="bonus-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Isian Bonus Karyawan</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Pilih jenis bonus, lalu isi jumlah atau
                        selisih sesuai ketentuan bonus.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                    onclick="closeBonusModal(false)">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto scrollbar-invisible">
                <div id="bonus-rows" class="space-y-3"></div>
                <button type="button" id="add-bonus-row"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-hunter-green-200 bg-hunter-green-50 text-hunter-green-700 text-xs font-black uppercase tracking-widest hover:bg-hunter-green-100 transition-colors">
                    <x-tabler-plus class="w-4 h-4" />
                    Tambah Bonus
                </button>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Bonus</p>
                    <p class="text-xl font-black text-carbon-black-900">Rp <span id="bonus-modal-total">0</span></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors"
                        onclick="closeBonusModal(false)">Batal</button>
                    <button type="button" id="save-bonus-modal"
                        class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 hover:shadow-lg transition-all active:scale-95">Simpan
                        Bonus</button>
                </div>
            </div>
        </div>
    </div>

    <div id="potongan-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Isian Potongan Gaji</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Pilih jenis potongan dari data master
                        Potongan Gaji.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                    onclick="closePotonganModal(false)">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto scrollbar-invisible">
                <div id="potongan-rows" class="space-y-3"></div>
                <button type="button" id="add-potongan-row"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 text-xs font-black uppercase tracking-widest hover:bg-rose-100 transition-colors">
                    <x-tabler-plus class="w-4 h-4" />
                    Tambah Potongan
                </button>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Potongan</p>
                    <p class="text-xl font-black text-carbon-black-900">Rp <span id="potongan-modal-total">0</span></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors"
                        onclick="closePotonganModal(false)">Batal</button>
                    <button type="button" id="save-potongan-modal"
                        class="px-5 py-2.5 bg-rose-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-rose-700 hover:shadow-lg transition-all active:scale-95">Simpan
                        Potongan</button>
                </div>
            </div>
        </div>
    </div>

    <div id="slip-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Slip Gaji Karyawan</h3>
                    <p id="slip-subtitle" class="text-xs font-semibold text-carbon-black-500 mt-1">-</p>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" onclick="printSlip()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-carbon-black-200 text-carbon-black-600 text-xs font-black uppercase tracking-widest rounded-lg shadow-sm hover:border-carbon-black-300 hover:bg-carbon-black-50 hover:text-carbon-black-900 active:scale-95 transition-all">
                        <x-tabler-printer class="w-4 h-4" />
                        Cetak Slip
                    </button>
                    <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                        onclick="closeSlipModal()">
                        <x-tabler-x class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[65vh] overflow-y-auto scrollbar-invisible">
                <div id="slip-detail-list" class="space-y-3"></div>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Gaji Bersih</p>
                    <p class="text-2xl font-black text-carbon-black-900" id="slip-total-net">Rp 0</p>
                </div>
                <button type="button"
                    class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all"
                    onclick="closeSlipModal()">Kembali</button>
            </div>
        </div>
    </div>

    <div id="component-detail-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 id="component-detail-title"
                        class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Detail Komponen</h3>
                    <p id="component-detail-subtitle" class="text-sm font-semibold text-carbon-black-500 mt-1">-</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                    onclick="closeComponentDetailModal()">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6">
                <div id="component-detail-content" class="space-y-3"></div>
            </div>

            <div
                class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-end rounded-b-xl">
                <button type="button"
                    class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all"
                    onclick="closeComponentDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <div id="print-container" class="hidden font-sans text-black bg-white" style="max-width: 800px; margin: 0 auto;">
        <!-- Print Header -->
        <table style="width: 100%; border-bottom: 2px solid black; margin-bottom: 10px; padding-bottom: 10px;">
            <tr>
                <td style="vertical-align: top;">
                    <h1
                        style="font-size: 24px; font-weight: 900; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        SAHABAT TERNAK</h1>
                </td>
                <td style="vertical-align: bottom; text-align: right;">
                    <p style="font-size: 12px; margin: 0;">Tanggal Cetak: <span id="print-tanggal-cetak"></span></p>
                </td>
            </tr>
        </table>

        <h2
            style="text-align: center; font-size: 18px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px;">
            SLIP GAJI KARYAWAN</h2>

        <table style="width: 100%; margin-bottom: 20px; font-size: 12px; font-weight: 600;">
            <tr>
                <td style="width: 120px;">NAMA</td>
                <td style="width: 10px;">:</td>
                <td id="print-nama"></td>

                <td style="width: 100px;">PERIODE</td>
                <td style="width: 10px;">:</td>
                <td id="print-periode"></td>
            </tr>
            <tr>
                <td>BAGIAN</td>
                <td>:</td>
                <td id="print-bagian"></td>

                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <!-- Details Table -->
        <table style="width: 100%; border: 2px solid black; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th
                        style="border: 2px solid black; padding: 8px; text-transform: uppercase; letter-spacing: 1px; width: 50%;">
                        PENERIMAAN</th>
                    <th
                        style="border: 2px solid black; padding: 8px; text-transform: uppercase; letter-spacing: 1px; width: 50%;">
                        POTONGAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-right: 2px solid black; padding: 12px; vertical-align: top; height: 150px;">
                        <table style="width: 100%; border-collapse: collapse;" id="print-penerimaan-list">
                            <!-- populated by js -->
                        </table>
                    </td>
                    <td style="padding: 12px; vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;" id="print-potongan-list">
                            <!-- populated by js -->
                        </table>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid black; font-weight: bold;">
                    <td style="border-right: 2px solid black; padding: 8px;">
                        <table style="width: 100%;">
                            <tr>
                                <td>TOTAL PENERIMAAN</td>
                                <td style="text-align: right;" id="print-total-penerimaan"></td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 8px;">
                        <table style="width: 100%;">
                            <tr>
                                <td>TOTAL POTONGAN</td>
                                <td style="text-align: right;" id="print-total-potongan"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- THP -->
        <table
            style="width: 50%; margin-top: 15px; font-size: 16px; font-weight: bold; border-top: 2px solid black; border-bottom: 2px solid black;">
            <tr>
                <td style="padding: 8px 0; width: 160px; text-transform: uppercase; letter-spacing: 1px;">TAKE HOME PAY</td>
                <td style="padding: 8px 0;">: <span id="print-thp"></span></td>
            </tr>
        </table>

        <!-- Signatures -->
        <table style="width: 100%; margin-top: 50px; text-align: center; font-size: 12px; font-weight: 600;">
            <tr>
                <td style="width: 50%;">
                    <p style="margin-bottom: 70px;">Dibuat Oleh,</p>
                    <p style="border-bottom: 1px solid black; width: 180px; margin: 0 auto;"></p>
                    <p style="margin-top: 5px; text-transform: uppercase;">Payroll Admin</p>
                </td>
                <td style="width: 50%;">
                    <p style="margin-bottom: 70px;">Diterima Oleh,</p>
                    <p style="border-bottom: 1px solid black; width: 180px; margin: 0 auto;" id="print-sign-nama"></p>
                    <p style="margin-top: 5px; text-transform: uppercase;">Karyawan</p>
                </td>
            </tr>
        </table>
    </div>

    <style>
        @media print {
            body> :not(#print-container) {
                display: none !important;
            }

            #print-container {
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
            document.addEventListener('DOMContentLoaded', function () {
                const karyawanLookup = @json($karyawanLookup ?? []);
                const karyawanList = @json($activeKaryawans ?? []);
                const bonusData = @json($gajiBonus ?? []);
                const potonganData = @json($potonganGaji ?? []);
                const months = @json($months);

                const monthSelect = document.getElementById('periode-bulan-select');
                const yearSelect = document.getElementById('periode-tahun-select');
                const payrollSearch = document.getElementById('payroll-search');
                const payrollBagianFilter = document.getElementById('payroll-bagian-filter');
                const rows = Array.from(document.querySelectorAll('.payroll-row'));
                const payrollTableBody = document.getElementById('payroll-table-body');
                const payrollEmptyFilterRow = document.getElementById('payroll-empty-filter-row');
                const payrollPagination = document.getElementById('payroll-pagination');
                const payrollPaginationInfo = document.getElementById('payroll-pagination-info');
                const detailName = document.getElementById('detail-employee-name');
                const detailMeta = document.getElementById('detail-employee-meta');
                const detailStatusBadge = document.getElementById('detail-status-badge');
                const detailGrossTotal = document.getElementById('detail-gross-total');
                const detailNetTotal = document.getElementById('detail-net-total');
                const salaryDetailList = document.getElementById('salary-detail-list');
                const detailEditBonus = document.getElementById('detail-edit-bonus');
                const detailEditPotongan = document.getElementById('detail-edit-potongan');
                const detailSavePayroll = document.getElementById('detail-save-payroll');
                const detailIdPenggajian = document.getElementById('detail-id-penggajian');
                const detailIdKaryawan = document.getElementById('detail-id-karyawan');
                const detailHariKerja = document.getElementById('detail-hari-kerja');
                const detailJumlahLembur = document.getElementById('detail-jumlah-lembur');
                const detailTanggalPembayaran = document.getElementById('detail-tanggal-pembayaran');
                const detailGajiPokok = document.getElementById('detail-gaji-pokok');
                const detailTotalLembur = document.getElementById('detail-total-lembur');
                const detailTotalBonus = document.getElementById('detail-total-bonus');
                const detailTotalPotongan = document.getElementById('detail-total-potongan');
                const detailPeriodeBulan = document.getElementById('detail-periode-bulan');
                const detailPeriodeTahun = document.getElementById('detail-periode-tahun');

                const bonusModal = document.getElementById('bonus-modal');
                const bonusModalInner = bonusModal.querySelector('div');
                const bonusRows = document.getElementById('bonus-rows');
                const addBonusRowButton = document.getElementById('add-bonus-row');
                const saveBonusModalButton = document.getElementById('save-bonus-modal');
                const bonusModalTotal = document.getElementById('bonus-modal-total');

                const potonganModal = document.getElementById('potongan-modal');
                const potonganModalInner = potonganModal.querySelector('div');
                const potonganRows = document.getElementById('potongan-rows');
                const addPotonganRowButton = document.getElementById('add-potongan-row');
                const savePotonganModalButton = document.getElementById('save-potongan-modal');
                const potonganModalTotal = document.getElementById('potongan-modal-total');
                const slipModal = document.getElementById('slip-modal');
                const slipModalInner = slipModal.querySelector('div');
                const slipSubtitle = document.getElementById('slip-subtitle');
                const slipDetailList = document.getElementById('slip-detail-list');
                const slipTotalNet = document.getElementById('slip-total-net');
                const componentDetailModal = document.getElementById('component-detail-modal');
                const componentDetailModalInner = componentDetailModal.querySelector('div');
                const componentDetailTitle = document.getElementById('component-detail-title');
                const componentDetailSubtitle = document.getElementById('component-detail-subtitle');
                const componentDetailContent = document.getElementById('component-detail-content');

                const payrollState = {};
                const payrollRowsPerPage = 10;
                let payrollCurrentPage = 1;
                let activeKaryawanId = rows[0]?.dataset.karyawanId || null;
                let editingType = null;

                function formatRupiah(value) {
                    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                }

                function formatNumber(value) {
                    return Number(value || 0).toLocaleString('id-ID');
                }

                function formatDecimal(value) {
                    return Number(value || 0).toLocaleString('id-ID', {
                        minimumFractionDigits: Number(value || 0) % 1 === 0 ? 0 : 1,
                        maximumFractionDigits: 2,
                    });
                }

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function statusBadgeClasses(status) {
                    const normalized = String(status || '').toLowerCase();
                    let classes = 'border-amber-200 bg-amber-50 text-amber-600';
                    if (normalized === 'diproses') {
                        classes = 'border-blue-200 bg-blue-50 text-blue-700';
                    } else if (normalized === 'dibayar' || normalized === 'selesai') {
                        classes = 'border-emerald-200 bg-emerald-50 text-emerald-700';
                    } else if (normalized === 'ditolak') {
                        classes = 'border-rose-200 bg-rose-50 text-rose-700';
                    }

                    return classes;
                }

                function statusBadge(status) {
                    const classes = statusBadgeClasses(status);
                    return `<span class="rounded-full border px-3 py-1 text-[11px] font-black uppercase tracking-widest ${classes}">${escapeHtml(status)}</span>`;
                }

                function statusPriority(status) {
                    const normalized = String(status || '').toLowerCase();
                    if (normalized === 'dibayar' || normalized === 'selesai') return 2;
                    if (normalized === 'diproses') return 1;
                    return 0;
                }

                function getEmployee(id) {
                    return karyawanList.find(item => String(item.id) === String(id)) || {};
                }

                function getSelectedPeriodKey() {
                    const month = String((monthSelect?.selectedIndex ?? new Date().getMonth()) + 1).padStart(2, '0');
                    const year = yearSelect?.value || new Date().getFullYear();
                    return `${year}-${month}`;
                }

                function getSelectedMonthName() {
                    return monthSelect?.value || months[new Date().getMonth()];
                }

                function getSelectedYear() {
                    return yearSelect?.value || new Date().getFullYear();
                }

                function buildInitialState(id, index) {
                    const data = karyawanLookup[id] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0, details: [] };
                    const totalPokok = Number(data.total_gaji_pokok || 0);
                    const totalLembur = Number(data.total_lembur || 0);

                    return {
                        id_penggajian: `PGJ-${String(index + 1).padStart(3, '0')}`,
                        id_karyawan: id,
                        periode_bulan: getSelectedMonthName(),
                        periode_tahun: getSelectedYear(),
                        jumlah_hari_kerja: Number(data.hari_kerja || 0),
                        jumlah_lembur: Number(data.jam_lembur || 0),
                        gaji_pokok: totalPokok,
                        total_lembur: totalLembur,
                        total_bonus: Number(periodBonus.total || 0),
                        total_potongan: 0,
                        total_gaji_kotor: totalPokok + totalLembur + Number(periodBonus.total || 0),
                        total_gaji_bersih: totalPokok + totalLembur + Number(periodBonus.total || 0),
                        tanggal_pembayaran: '-',
                        status_pembayaran: 'Belum Dibayar',
                        bonusDetails: periodBonus.details || [],
                        potonganDetails: [],
                        manualBonusRows: [],
                        manualPotonganRows: [],
                    };
                }

                function ensureState(id) {
                    if (!payrollState[id]) {
                        const row = rows.find(item => String(item.dataset.karyawanId) === String(id));
                        payrollState[id] = buildInitialState(id, Number(row?.dataset.rowIndex || 1) - 1);
                    }

                    return payrollState[id];
                }

                function recalculateState(id) {
                    const state = ensureState(id);
                    const data = karyawanLookup[id] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0, details: [] };

                    // Rebuild Bonus
                    let manualBonusTotal = 0;
                    const manualBonusDetails = [];
                    state.manualBonusRows.forEach(item => {
                        const bonus = bonusData.find(b => String(b.id) === String(item.id));
                        if (bonus) {
                            const amount = item.usage * Number(bonus.tarif || 0);
                            manualBonusTotal += amount;
                            const isFixed = String(bonus.satuan || '').toLowerCase() === 'kali';
                            manualBonusDetails.push({
                                title: bonus.nama,
                                description: isFixed ? `Dihitung 1 kali sebesar ${formatRupiah(bonus.tarif)}` : `Sejumlah ${formatUsageValue(item.usage, bonus.satuan)} x ${formatRupiah(bonus.tarif)}`,
                                amount
                            });
                        }
                    });
                    state.total_bonus = Number(periodBonus.total || 0) + manualBonusTotal;
                    state.bonusDetails = [...(periodBonus.details || []), ...manualBonusDetails];

                    // Rebuild Potongan
                    let automaticPotonganTotal = 0;
                    const automaticPotonganDetails = [];
                    potonganData.forEach(p => {
                        if (String(p.tipe_potongan || '').toLowerCase() === 'tetap') {
                            automaticPotonganTotal += Number(p.tarif || 0);
                            automaticPotonganDetails.push({
                                title: p.nama,
                                description: `Potongan tetap (Otomatis)`,
                                amount: Number(p.tarif || 0)
                            });
                        } else if (String(p.tipe_potongan || '').toLowerCase() === 'persentase') {
                            const percentage = Number(p.tarif || 0);
                            const amount = (percentage / 100) * Number(state.gaji_pokok || 0);
                            automaticPotonganTotal += amount;
                            automaticPotonganDetails.push({
                                title: p.nama,
                                description: `Potongan persentase ${percentage}% dari Gaji Pokok (Otomatis)`,
                                amount: amount
                            });
                        }
                    });

                    const periodWastage = data.wastage_deductions_by_period?.[getSelectedPeriodKey()] || { amount: 0, details: [] };
                    if (periodWastage.amount > 0) {
                        automaticPotonganTotal += periodWastage.amount;
                        periodWastage.details.forEach(detail => {
                            automaticPotonganDetails.push({
                                title: 'Potongan Wastage Kemasan',
                                description: `${detail.description} (Piket harian tgl ${detail.date})`,
                                amount: detail.amount
                            });
                        });
                    }
                    let manualPotonganTotal = 0;
                    const manualPotonganDetails = [];
                    state.manualPotonganRows.forEach(item => {
                        const potongan = potonganData.find(p => String(p.id) === String(item.id));
                        if (potongan) {
                            const amount = item.jumlah * Number(potongan.tarif || 0);
                            manualPotonganTotal += amount;
                            manualPotonganDetails.push({
                                title: potongan.nama,
                                description: `${formatNumber(item.jumlah)} kejadian x ${formatRupiah(potongan.tarif)}`,
                                amount
                            });
                        }
                    });
                    state.total_potongan = automaticPotonganTotal + manualPotonganTotal;
                    state.potonganDetails = [...automaticPotonganDetails, ...manualPotonganDetails];

                    state.periode_bulan = getSelectedMonthName();
                    state.periode_tahun = getSelectedYear();
                    if (String(state.status_pembayaran || '').toLowerCase() === 'selesai') {
                        state.status_pembayaran = 'Dibayar';
                    }
                    state.total_gaji_kotor = Number(state.gaji_pokok) + Number(state.total_lembur) + Number(state.total_bonus);
                    state.total_gaji_bersih = Math.max(0, Number(state.total_gaji_kotor) - Number(state.total_potongan));
                    return state;
                }

                function updatePeriodBonus(id) {
                    recalculateState(id);
                }

                function setCell(row, field, value) {
                    const cell = row.querySelector(`[data-field="${field}"]`);
                    if (cell) {
                        cell.innerHTML = value;
                    }
                }

                function renderTableRow(id) {
                    const state = recalculateState(id);
                    const row = rows.find(item => String(item.dataset.karyawanId) === String(id));
                    if (!row) return;

                    setCell(row, 'bagian', escapeHtml(karyawanLookup[id]?.bagian || getEmployee(id).bagian || '-'));
                    setCell(row, 'periode', `${escapeHtml(state.periode_bulan)} ${escapeHtml(state.periode_tahun)}`);
                    setCell(row, 'jumlah_hari_kerja', formatNumber(state.jumlah_hari_kerja));
                    setCell(row, 'total_gaji_bersih', formatRupiah(state.total_gaji_bersih));
                    setCell(row, 'status_pembayaran', statusBadge(state.status_pembayaran));

                    const processButton = row.querySelector('[data-action="process"]');
                    if (processButton) {
                        const paid = String(state.status_pembayaran || '').toLowerCase() === 'dibayar';
                        processButton.disabled = paid;
                        processButton.className = paid
                            ? 'inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-emerald-700 opacity-80'
                            : 'inline-flex items-center gap-1.5 rounded-lg border border-hunter-green-200 bg-hunter-green-50 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-hunter-green-700 transition-colors hover:bg-hunter-green-100';
                        processButton.lastChild.textContent = paid ? ' Dibayar' : ' Proses';
                    }
                }

                function detailRow(title, description, amount, tone = 'neutral', detailType = '') {
                    const isZero = Number(amount) <= 0;
                    const amountClass = isZero ? 'text-carbon-black-500' : (tone === 'deduction' ? 'text-rose-600' : 'text-hunter-green-700');
                    const accentClass = tone === 'deduction' ? 'border-l-rose-300' : 'border-l-hunter-green-300';
                    const prefix = tone === 'deduction' && !isZero ? '- ' : '';
                    const detailButton = detailType ? `
                        <button type="button" data-component-detail="${detailType}" class="mt-2 inline-flex items-center gap-1.5 rounded-lg border border-carbon-black-200 bg-carbon-black-50 px-3 py-1 text-xs font-black uppercase tracking-widest text-carbon-black-600 transition-colors hover:border-hunter-green-300 hover:bg-hunter-green-50 hover:text-hunter-green-700">
                            Detail
                        </button>
                    ` : '';

                    return `
                        <div class="min-w-0 rounded-xl border border-l-4 border-carbon-black-100 ${accentClass} bg-white px-3 py-2 shadow-[0_1px_0_rgba(0,0,0,0.03)]">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-base font-black leading-tight text-carbon-black-800">${escapeHtml(title)}</p>
                                    <p class="mt-1 text-base font-semibold leading-tight text-carbon-black-400">${escapeHtml(description)}</p>
                                    ${detailButton}
                                </div>
                                <p class="shrink-0 text-right text-base font-black leading-tight ${amountClass}">${prefix}${formatRupiah(amount)}</p>
                            </div>
                        </div>
                    `;
                }

                function componentCard(title, tone = 'neutral', detailType = '') {
                    return `
                        <div class="flex items-center justify-between gap-2 rounded-xl border border-carbon-black-100 bg-white px-4 py-3 shadow-[0_1px_0_rgba(0,0,0,0.03)] h-full">
                            <p class="text-base font-black leading-tight text-carbon-black-800">${escapeHtml(title)}</p>
                            <button type="button" data-component-detail="${detailType}" class="inline-flex shrink-0 items-center gap-1 rounded-md border border-carbon-black-200 bg-carbon-black-50 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-carbon-black-600 transition-colors hover:border-hunter-green-300 hover:bg-hunter-green-50 hover:text-hunter-green-700" title="Detail ${escapeHtml(title)}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </button>
                        </div>
                    `;
                }

                function infoRow(title, value) {
                    return `
                        <div class="flex items-center justify-between gap-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3">
                            <p class="text-sm font-black text-carbon-black-800">${escapeHtml(title)}</p>
                            <p class="text-right text-sm font-black text-carbon-black-600">${escapeHtml(value)}</p>
                        </div>
                    `;
                }

                function renderDetail(id) {
                    const state = recalculateState(id);
                    const data = karyawanLookup[id] || {};
                    const employee = getEmployee(id);
                    const satuanGajiPokok = data.satuan_gaji_pokok || 'Jam';
                    const hariKerja = Number(data.hari_kerja || 0);
                    const tarifPokok = Number(data.gaji_pokok || 0);
                    const jamLembur = Number(data.jam_lembur || 0);
                    const tarifLembur = Number(data.tarif_lembur || 0);
                    const gajiPokokDetail = satuanGajiPokok === 'Hari'
                        ? `${formatNumber(hariKerja)} hari x ${formatRupiah(tarifPokok)}`
                        : `${formatNumber(hariKerja)} hari x 7 jam x ${formatRupiah(tarifPokok)}`;

                    detailName.textContent = employee.nama || data.nama || 'Karyawan';
                    detailMeta.textContent = `${data.bagian || employee.bagian || '-'} - ${state.periode_bulan} ${state.periode_tahun}`;
                    detailStatusBadge.textContent = state.status_pembayaran;
                    detailStatusBadge.className = `rounded-full border px-3 py-1 text-xs font-black uppercase tracking-widest ${statusBadgeClasses(state.status_pembayaran)}`;
                    detailGrossTotal.textContent = formatRupiah(state.total_gaji_kotor);
                    detailNetTotal.textContent = formatRupiah(state.total_gaji_bersih);
                    detailIdPenggajian.textContent = state.id_penggajian;
                    detailIdKaryawan.textContent = state.id_karyawan;
                    detailHariKerja.textContent = `${formatNumber(state.jumlah_hari_kerja)} hari`;
                    detailJumlahLembur.textContent = data.lembur_aktif === false ? '-' : `${formatDecimal(state.jumlah_lembur)} jam`;
                    detailTanggalPembayaran.textContent = state.tanggal_pembayaran;
                    detailGajiPokok.textContent = formatRupiah(state.gaji_pokok);
                    detailTotalLembur.textContent = formatRupiah(state.total_lembur);
                    detailTotalBonus.textContent = formatRupiah(state.total_bonus);
                    detailTotalPotongan.textContent = formatRupiah(state.total_potongan);
                    detailPeriodeBulan.textContent = state.periode_bulan;
                    detailPeriodeTahun.textContent = state.periode_tahun;
                    
                    const isPaid = String(state.status_pembayaran || '').toLowerCase() === 'dibayar';
                    detailEditBonus.disabled = isPaid;
                    detailEditPotongan.disabled = isPaid;
                    if (detailSavePayroll) detailSavePayroll.disabled = isPaid;

                    const details = [
                        componentCard('Gaji Pokok', 'neutral', 'gaji_pokok'),
                        componentCard('Lembur', 'neutral', 'lembur'),
                        componentCard('Bonus', 'neutral', 'bonus'),
                        componentCard('Potongan', 'deduction', 'potongan')
                    ];

                    salaryDetailList.innerHTML = details.join('');
                }

                function componentDetailItem(title, description, amount, tone = 'neutral') {
                    const amountClass = tone === 'deduction' ? 'text-rose-600' : 'text-hunter-green-700';
                    const prefix = tone === 'deduction' && Number(amount) > 0 ? '- ' : '';

                    return `
                        <div class="flex items-start justify-between gap-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3">
                            <div>
                                <p class="text-base font-black text-carbon-black-800">${escapeHtml(title)}</p>
                                <p class="mt-1 text-sm font-semibold text-carbon-black-500">${escapeHtml(description)}</p>
                            </div>
                            <p class="shrink-0 text-right text-base font-black ${amountClass}">${prefix}${formatRupiah(amount)}</p>
                        </div>
                    `;
                }

                function openComponentDetailModal(type) {
                    if (!activeKaryawanId) return;

                    const state = recalculateState(activeKaryawanId);
                    const data = karyawanLookup[activeKaryawanId] || {};
                    const employee = getEmployee(activeKaryawanId);
                    const normalizedType = String(type || '').toLowerCase();
                    let title = 'Detail Komponen';
                    let items = [];

                    if (normalizedType === 'gaji_pokok') {
                        title = 'Detail Gaji Pokok';
                        const satuanGajiPokok = data.satuan_gaji_pokok || 'Jam';
                        const hariKerja = Number(data.hari_kerja || 0);
                        const tarifPokok = Number(data.gaji_pokok || 0);
                        const gajiPokokDetail = satuanGajiPokok === 'Hari'
                            ? `${formatNumber(hariKerja)} hari x ${formatRupiah(tarifPokok)}`
                            : `${formatNumber(hariKerja)} hari x 7 jam x ${formatRupiah(tarifPokok)}`;
                        items = [
                            componentDetailItem('Gaji Pokok', gajiPokokDetail, state.gaji_pokok)
                        ];
                    } else if (normalizedType === 'lembur') {
                        title = 'Detail Lembur';
                        items = [
                            componentDetailItem(
                                data.lembur_aktif === false ? 'Status Lembur' : 'Jam Lembur',
                                data.lembur_aktif === false ? 'Tidak berlaku untuk bagian Peternakan.' : `${formatDecimal(state.jumlah_lembur)} jam x ${formatRupiah(data.tarif_lembur || 0)}`,
                                state.total_lembur
                            ),
                        ];
                    } else if (normalizedType === 'bonus') {
                        title = 'Detail Bonus';
                        items = state.bonusDetails.length > 0
                            ? state.bonusDetails.map(item => componentDetailItem(item.title, item.description, item.amount))
                            : [componentDetailItem('Bonus', 'Tidak ada bonus yang dihitung.', 0)];
                    } else if (normalizedType === 'potongan') {
                        title = 'Detail Potongan';
                        items = state.potonganDetails.length > 0
                            ? state.potonganDetails.map(item => componentDetailItem(item.title, item.description, item.amount, 'deduction'))
                            : [componentDetailItem('Potongan', 'Tidak ada potongan yang dihitung.', 0, 'deduction')];
                    }

                    componentDetailTitle.textContent = title;
                    componentDetailSubtitle.textContent = `${employee.nama || data.nama || 'Karyawan'} - ${state.periode_bulan} ${state.periode_tahun}`;
                    componentDetailContent.innerHTML = items.join('');
                    componentDetailModal.classList.remove('hidden');
                    componentDetailModal.classList.add('flex');
                    setTimeout(() => {
                        componentDetailModal.classList.remove('opacity-0');
                        componentDetailModalInner.classList.remove('scale-95');
                        componentDetailModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.closeComponentDetailModal = function () {
                    componentDetailModal.classList.add('opacity-0');
                    componentDetailModalInner.classList.remove('scale-100');
                    componentDetailModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        componentDetailModal.classList.add('hidden');
                        componentDetailModal.classList.remove('flex');
                    }, 300);
                }

                function renderSlip(id) {
                    const state = recalculateState(id);
                    const data = karyawanLookup[id] || {};
                    const employee = getEmployee(id);

                    const bonusItemsHTML = state.bonusDetails.length > 0
                        ? state.bonusDetails.map(item => `
                            <div class="px-5 py-4 flex justify-between items-start">
                                <div>
                                    <p class="text-base font-black text-carbon-black-800">${escapeHtml(item.title)}</p>
                                    <p class="text-sm font-semibold text-carbon-black-500 mt-1">${escapeHtml(item.description)}</p>
                                </div>
                                <p class="text-lg font-black text-hunter-green-700">${formatRupiah(item.amount)}</p>
                            </div>
                        `).join('')
                        : `
                            <div class="px-5 py-4 flex justify-between items-center">
                                <p class="text-base font-bold text-carbon-black-400">Bonus</p>
                                <p class="text-lg font-bold text-carbon-black-400">Rp 0</p>
                            </div>
                        `;

                    const potonganItemsHTML = state.potonganDetails.length > 0
                        ? state.potonganDetails.map(item => `
                            <div class="px-5 py-4 flex justify-between items-start">
                                <div>
                                    <p class="text-base font-black text-carbon-black-800">${escapeHtml(item.title)}</p>
                                    <p class="text-sm font-semibold text-carbon-black-500 mt-1">${escapeHtml(item.description)}</p>
                                </div>
                                <p class="text-lg font-black text-rose-600">- ${formatRupiah(item.amount)}</p>
                            </div>
                        `).join('')
                        : `
                            <div class="px-5 py-4 flex justify-between items-center">
                                <p class="text-base font-bold text-carbon-black-400">Potongan</p>
                                <p class="text-lg font-bold text-carbon-black-400">Rp 0</p>
                            </div>
                        `;

                    const html = `
                        <div class="space-y-6">
                            <div class="bg-carbon-black-50 rounded-xl border border-carbon-black-100 p-6 grid grid-cols-2 gap-y-5 gap-x-8">
                                <div>
                                    <p class="text-xs font-black uppercase text-carbon-black-400 tracking-widest">ID Penggajian</p>
                                    <p class="text-base font-black text-carbon-black-900 mt-1.5">${escapeHtml(state.id_penggajian)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase text-carbon-black-400 tracking-widest">ID Karyawan</p>
                                    <p class="text-base font-black text-carbon-black-900 mt-1.5">${escapeHtml(state.id_karyawan)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase text-carbon-black-400 tracking-widest">Periode</p>
                                    <p class="text-base font-black text-carbon-black-900 mt-1.5">${escapeHtml(state.periode_bulan)} ${escapeHtml(state.periode_tahun)}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase text-carbon-black-400 tracking-widest">Status Pembayaran</p>
                                    <div class="mt-1.5">${statusBadge(state.status_pembayaran)}</div>
                                </div>
                            </div>

                            <div class="border border-carbon-black-100 rounded-xl overflow-hidden shadow-sm">
                                <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100 flex items-center justify-between">
                                    <h4 class="text-sm font-black uppercase tracking-widest text-hunter-green-700">Pendapatan</h4>
                                    <p class="text-sm font-black text-hunter-green-700">${formatRupiah(Number(state.gaji_pokok) + Number(state.total_lembur) + Number(state.total_bonus))}</p>
                                </div>
                                <div class="divide-y divide-carbon-black-50 bg-white">
                                    <div class="px-6 py-4 flex justify-between items-start">
                                        <div>
                                            <p class="text-base font-black text-carbon-black-800">Gaji Pokok</p>
                                            <p class="text-sm font-semibold text-carbon-black-500 mt-1">${formatNumber(state.jumlah_hari_kerja)} hari kerja</p>
                                        </div>
                                        <p class="text-lg font-black text-hunter-green-700">${formatRupiah(state.gaji_pokok)}</p>
                                    </div>

                                    <div class="px-6 py-4 flex justify-between items-start">
                                        <div>
                                            <p class="text-base font-black text-carbon-black-800">Lembur</p>
                                            <p class="text-sm font-semibold text-carbon-black-500 mt-1">${data.lembur_aktif === false ? 'Tidak berlaku' : `${formatDecimal(state.jumlah_lembur)} jam lembur`}</p>
                                        </div>
                                        <p class="text-lg font-black text-hunter-green-700">${formatRupiah(state.total_lembur)}</p>
                                    </div>

                                    ${bonusItemsHTML}
                                </div>

                                <div class="bg-carbon-black-50 px-6 py-4 border-y border-carbon-black-100 flex items-center justify-between">
                                    <h4 class="text-sm font-black uppercase tracking-widest text-rose-700">Potongan</h4>
                                    <p class="text-sm font-black text-rose-700">- ${formatRupiah(state.total_potongan)}</p>
                                </div>
                                <div class="divide-y divide-carbon-black-50 bg-white">
                                    ${potonganItemsHTML}
                                </div>
                            </div>
                        </div>
                    `;

                    slipSubtitle.textContent = `${state.id_penggajian} - ${employee.nama || data.nama || 'Karyawan'} - ${data.bagian || employee.bagian || '-'}`;
                    slipDetailList.innerHTML = html;
                    slipTotalNet.textContent = formatRupiah(state.total_gaji_bersih);

                    // Populate Print Template
                    const totalPenerimaan = Number(state.gaji_pokok) + Number(state.total_lembur) + Number(state.total_bonus);

                    document.getElementById('print-tanggal-cetak').textContent = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                    document.getElementById('print-nama').textContent = employee.nama || data.nama || 'Karyawan';
                    document.getElementById('print-bagian').textContent = data.bagian || employee.bagian || '-';
                    document.getElementById('print-periode').textContent = `${state.periode_bulan} ${state.periode_tahun}`;
                    document.getElementById('print-sign-nama').textContent = employee.nama || data.nama || 'Karyawan';

                    // Print Penerimaan
                    let printPenerimaanHTML = `
                        <tr><td style="padding: 4px 0;">Gaji Pokok</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(state.gaji_pokok)}</td></tr>
                        <tr><td style="padding: 4px 0;">Lembur</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(state.total_lembur)}</td></tr>
                    `;
                    if (state.bonusDetails.length > 0) {
                        state.bonusDetails.forEach(item => {
                            printPenerimaanHTML += `<tr><td style="padding: 4px 0;">${escapeHtml(item.title)}</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(item.amount)}</td></tr>`;
                        });
                    }
                    document.getElementById('print-penerimaan-list').innerHTML = printPenerimaanHTML;

                    // Print Potongan
                    let printPotonganHTML = '';
                    if (state.potonganDetails.length > 0) {
                        state.potonganDetails.forEach(item => {
                            printPotonganHTML += `<tr><td style="padding: 4px 0;">${escapeHtml(item.title)}</td><td style="text-align: right; padding: 4px 0;">${formatRupiah(item.amount)}</td></tr>`;
                        });
                    }
                    document.getElementById('print-potongan-list').innerHTML = printPotonganHTML;

                    // Print Totals
                    document.getElementById('print-total-penerimaan').textContent = formatRupiah(totalPenerimaan);
                    document.getElementById('print-total-potongan').textContent = formatRupiah(state.total_potongan);
                    document.getElementById('print-thp').textContent = formatRupiah(state.total_gaji_bersih);
                }

                function openSlipModal(id) {
                    selectRow(id);
                    renderSlip(id);
                    slipModal.classList.remove('hidden');
                    slipModal.classList.add('flex');
                    setTimeout(() => {
                        slipModal.classList.remove('opacity-0');
                        slipModalInner.classList.remove('scale-95');
                        slipModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.printSlip = function () {
                    const container = document.getElementById('print-container');
                    document.body.appendChild(container); // Move to body root to avoid layout issues
                    window.print();
                }

                window.closeSlipModal = function () {
                    slipModal.classList.add('opacity-0');
                    slipModalInner.classList.remove('scale-100');
                    slipModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        slipModal.classList.add('hidden');
                        slipModal.classList.remove('flex');
                    }, 300);
                }

                function processPayroll(id) {
                    const state = ensureState(id);
                    state.status_pembayaran = 'Dibayar';
                    state.tanggal_pembayaran = new Date().toLocaleDateString('id-ID');
                    recalculateState(id);
                    renderTableRow(id);
                    selectRow(id);
                    sortPayrollRows();
                    applyPayrollFilters();
                }

                function sortPayrollRows() {
                    if (!payrollTableBody) return;

                    rows
                        .slice()
                        .sort((a, b) => {
                            const first = recalculateState(a.dataset.karyawanId);
                            const second = recalculateState(b.dataset.karyawanId);
                            return statusPriority(first.status_pembayaran) - statusPriority(second.status_pembayaran)
                                || Number(a.dataset.rowIndex || 0) - Number(b.dataset.rowIndex || 0);
                        })
                        .forEach(row => payrollTableBody.appendChild(row));

                    if (payrollEmptyFilterRow) {
                        payrollTableBody.appendChild(payrollEmptyFilterRow);
                    }
                }

                function normalizeFilterText(value) {
                    return String(value || '').trim().toLowerCase();
                }

                function rowMatchesPayrollFilters(row) {
                    const searchValue = normalizeFilterText(payrollSearch?.value);
                    const bagianValue = normalizeFilterText(payrollBagianFilter?.value);
                    const rowName = normalizeFilterText(row.dataset.karyawanName);
                    const rowBagian = normalizeFilterText(row.dataset.karyawanBagian);

                    return (!searchValue || rowName.includes(searchValue))
                        && (!bagianValue || rowBagian === bagianValue);
                }

                function applyPayrollFilters() {
                    const matchedRows = rows.filter(rowMatchesPayrollFilters);
                    const totalPages = Math.max(1, Math.ceil(matchedRows.length / payrollRowsPerPage));

                    payrollCurrentPage = Math.min(Math.max(1, payrollCurrentPage), totalPages);
                    const startIndex = (payrollCurrentPage - 1) * payrollRowsPerPage;
                    const endIndex = startIndex + payrollRowsPerPage;
                    const currentRows = matchedRows.slice(startIndex, endIndex);

                    rows.forEach(row => {
                        const isVisible = currentRows.includes(row);
                        row.classList.toggle('hidden', !isVisible);
                    });

                    if (payrollEmptyFilterRow) {
                        payrollEmptyFilterRow.classList.toggle('hidden', matchedRows.length > 0);
                    }

                    renderPayrollPagination(matchedRows.length, totalPages, startIndex, currentRows.length);
                }

                function paginationButton(label, page, disabled = false, active = false) {
                    return `
                        <button type="button" data-payroll-page="${page}" ${disabled ? 'disabled' : ''}
                            class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border px-3 text-sm font-black transition-colors
                            ${active
                            ? 'border-hunter-green-600 bg-hunter-green-600 text-white'
                            : 'border-carbon-black-200 bg-white text-carbon-black-700 hover:border-hunter-green-300 hover:bg-hunter-green-50 disabled:cursor-not-allowed disabled:opacity-50'}">
                            ${label}
                        </button>
                    `;
                }

                function renderPayrollPagination(totalRows, totalPages, startIndex, currentCount) {
                    if (payrollPaginationInfo) {
                        const startNumber = totalRows === 0 ? 0 : startIndex + 1;
                        const endNumber = totalRows === 0 ? 0 : startIndex + currentCount;
                        payrollPaginationInfo.textContent = `Menampilkan ${startNumber}-${endNumber} dari ${totalRows} data`;
                    }

                    if (!payrollPagination) return;

                    if (totalRows <= payrollRowsPerPage) {
                        payrollPagination.innerHTML = '';
                        return;
                    }

                    let buttons = paginationButton('Sebelumnya', payrollCurrentPage - 1, payrollCurrentPage === 1);
                    for (let page = 1; page <= totalPages; page++) {
                        buttons += paginationButton(page, page, false, page === payrollCurrentPage);
                    }
                    buttons += paginationButton('Berikutnya', payrollCurrentPage + 1, payrollCurrentPage === totalPages);
                    payrollPagination.innerHTML = buttons;
                }

                function selectRow(id) {
                    activeKaryawanId = id;
                    rows.forEach(row => {
                        const isActive = String(row.dataset.karyawanId) === String(id);
                        row.classList.toggle('bg-hunter-green-50', isActive);
                        row.classList.toggle('ring-1', isActive);
                        row.classList.toggle('ring-hunter-green-300', isActive);
                    });
                    renderDetail(id);
                }

                function formatUsageValue(value, satuan) {
                    return Number(value || 0).toLocaleString('id-ID') + ' ' + satuan;
                }

                function getSelectedIds(container, selectClass, exceptSelect = null) {
                    return Array.from(container.querySelectorAll(selectClass))
                        .filter(select => select !== exceptSelect && select.value)
                        .map(select => String(select.value));
                }

                function refreshUniqueOptions(container, selectClass, data, placeholderText, createOptionsCallback) {
                    container.querySelectorAll(selectClass).forEach(select => {
                        const currentValue = select.value;
                        select.innerHTML = `
                            <option value="" disabled ${currentValue ? '' : 'selected'}>${placeholderText}</option>
                            ${createOptionsCallback(currentValue, select)}
                        `;
                        select.value = currentValue;
                    });
                }

                function getSelectedBonus(row) {
                    const select = row.querySelector('.bonus-type');
                    return bonusData.find(item => String(item.id) === String(select.value));
                }

                function createBonusOptions(selectedId = '', currentSelect = null) {
                    const selectedIds = getSelectedIds(bonusRows, '.bonus-type', currentSelect);
                    // ID 1-6 dan 8 adalah bonus terkait produksi/bahan baku yang sudah dihitung otomatis oleh sistem
                    const autoBonusIds = [1, 2, 3, 4, 5, 6, 8];

                    return bonusData.map(item => {
                        if (autoBonusIds.includes(Number(item.id))) return '';
                        if (selectedIds.includes(String(item.id)) && String(item.id) !== String(selectedId)) {
                            return '';
                        }

                        const selected = String(item.id) === String(selectedId) ? 'selected' : '';
                        return `<option value="${item.id}" ${selected}>${escapeHtml(item.nama)}</option>`;
                    }).join('');
                }

                function refreshBonusOptions() {
                    refreshUniqueOptions(bonusRows, '.bonus-type', bonusData, 'Jenis Bonus', createBonusOptions);
                }

                function updateBonusRow(row) {
                    const bonus = getSelectedBonus(row);
                    const usageInput = row.querySelector('.bonus-usage');
                    const usageHint = row.querySelector('.bonus-usage-hint');
                    const subtotalText = row.querySelector('.bonus-subtotal');

                    if (!bonus) {
                        usageInput.value = '';
                        usageInput.disabled = true;
                        usageHint.textContent = 'Pilih jenis bonus terlebih dahulu.';
                        subtotalText.textContent = 'Rp 0';
                        return;
                    }

                    const satuan = String(bonus.satuan || '').toLowerCase();
                    const isAttendanceBonus = satuan === 'hari';
                    const isFixedBonus = satuan === 'kali';
                    usageInput.disabled = false;

                    if (isAttendanceBonus) {
                        const hariKerja = Number(karyawanLookup[activeKaryawanId]?.hari_kerja ?? 0);
                        usageInput.value = Math.max(0, hariKerja - Number(bonus.batas_normal || 0));
                        usageInput.readOnly = true;
                        usageInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                        usageHint.textContent = 'Bonus kehadiran dihitung otomatis jika hadir lebih dari ' + bonus.batas_normal + ' hari.';
                    } else if (isFixedBonus) {
                        usageInput.value = 1;
                        usageInput.readOnly = true;
                        usageInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                        usageHint.textContent = 'Bonus ini dihitung 1 kali sesuai nominal tarif.';
                    } else {
                        usageInput.readOnly = false;
                        usageInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed');
                        usageInput.placeholder = 'Selisih ' + bonus.satuan;
                        usageHint.textContent = 'Isi selisih di atas batas normal ' + formatUsageValue(bonus.batas_normal, bonus.satuan) + '.';
                    }

                    subtotalText.textContent = formatRupiah(Number(usageInput.value || 0) * Number(bonus.tarif || 0));
                    updateBonusModalTotal();
                }

                function updateBonusModalTotal() {
                    const data = karyawanLookup[activeKaryawanId] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0 };
                    let total = Number(periodBonus.total || 0);

                    bonusRows.querySelectorAll('.bonus-row').forEach(row => {
                        const bonus = getSelectedBonus(row);
                        const usage = Number(row.querySelector('.bonus-usage').value || 0);
                        if (bonus) {
                            total += usage * Number(bonus.tarif || 0);
                        }
                    });

                    bonusModalTotal.textContent = Number(total).toLocaleString('id-ID');
                    return total;
                }

                function collectBonusDetails() {
                    const details = [];
                    const manualRows = [];

                    bonusRows.querySelectorAll('.bonus-row').forEach(row => {
                        const bonus = getSelectedBonus(row);
                        const usage = Number(row.querySelector('.bonus-usage').value || 0);

                        if (!bonus || usage <= 0) {
                            return;
                        }

                        const amount = usage * Number(bonus.tarif || 0);
                        const isFixedBonus = String(bonus.satuan || '').toLowerCase() === 'kali';
                        details.push({
                            title: bonus.nama,
                            description: isFixedBonus
                                ? `Dihitung 1 kali sebesar ${formatRupiah(bonus.tarif)}`
                                : `Sejumlah ${formatUsageValue(usage, bonus.satuan)} x ${formatRupiah(bonus.tarif)}`,
                            amount,
                        });
                        manualRows.push({ id: bonus.id, usage });
                    });

                    return { details, manualRows };
                }

                function addBonusRow(selectedId = '', usageValue = '') {
                    const row = document.createElement('div');
                    row.className = 'bonus-row grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-white border border-carbon-black-100 rounded-xl p-3 shadow-sm';
                    row.innerHTML = `
                        <div class="relative">
                            <select class="bonus-type w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                                <option value="" disabled ${selectedId ? '' : 'selected'}>Jenis Bonus</option>
                                ${createBonusOptions(selectedId)}
                            </select>
                            <p class="bonus-usage-hint text-[11px] font-semibold text-carbon-black-400 mt-1">Pilih jenis bonus terlebih dahulu.</p>
                        </div>
                        <input type="number" min="0" step="0.01" class="bonus-usage w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg" placeholder="Jumlah" disabled>
                        <div class="px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 rounded-lg text-sm font-black text-carbon-black-800">
                            <span class="bonus-subtotal">Rp 0</span>
                        </div>
                        <button type="button" class="remove-bonus-row h-11 w-11 inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 transition-colors" title="Kurangi bonus">
                            <x-tabler-minus class="w-4 h-4" />
                        </button>
                    `;

                    bonusRows.appendChild(row);
                    if (selectedId) {
                        row.querySelector('.bonus-type').value = selectedId;
                        row.querySelector('.bonus-usage').value = usageValue;
                    }
                    row.querySelector('.bonus-type').addEventListener('change', () => {
                        updateBonusRow(row);
                        refreshBonusOptions();
                    });
                    row.querySelector('.bonus-usage').addEventListener('input', () => updateBonusRow(row));
                    row.querySelector('.remove-bonus-row').addEventListener('click', () => {
                        row.remove();
                        if (!bonusRows.querySelector('.bonus-row')) addBonusRow();
                        refreshBonusOptions();
                        updateBonusModalTotal();
                    });

                    if (selectedId) updateBonusRow(row);
                    refreshBonusOptions();
                }

                function openBonusModal() {
                    if (!activeKaryawanId) return;
                    editingType = 'bonus';
                    const state = ensureState(activeKaryawanId);
                    const data = karyawanLookup[activeKaryawanId] || {};
                    const periodBonus = data.bonus_produksi_by_period?.[getSelectedPeriodKey()] || { total: 0, details: [] };

                    bonusRows.innerHTML = '';

                    (periodBonus.details || []).forEach(item => {
                        const row = document.createElement('div');
                        row.className = 'grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-carbon-black-50 border border-carbon-black-100 rounded-xl p-3 shadow-sm opacity-90';
                        row.innerHTML = `
                            <div class="relative">
                                <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed" value="${escapeHtml(item.title)}" readonly>
                                <p class="text-[11px] font-semibold text-carbon-black-400 mt-1">Bawaan sistem (Otomatis)</p>
                            </div>
                            <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed truncate" value="${escapeHtml(item.description)}" readonly title="${escapeHtml(item.description)}">
                            <div class="px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 rounded-lg text-sm font-black text-carbon-black-800">
                                <span>${formatRupiah(item.amount)}</span>
                            </div>
                            <div class="h-11 w-11 inline-flex items-center justify-center rounded-lg bg-carbon-black-100 border border-carbon-black-200 text-carbon-black-400 cursor-not-allowed">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </div>
                        `;
                        bonusRows.appendChild(row);
                    });

                    if (state.manualBonusRows.length > 0) {
                        state.manualBonusRows.forEach(item => addBonusRow(item.id, item.usage));
                    } else if ((periodBonus.details || []).length === 0) {
                        addBonusRow();
                    }

                    updateBonusModalTotal();
                    bonusModal.classList.remove('hidden');
                    bonusModal.classList.add('flex');
                    setTimeout(() => {
                        bonusModal.classList.remove('opacity-0');
                        bonusModalInner.classList.remove('scale-95');
                        bonusModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.closeBonusModal = function () {
                    bonusModal.classList.add('opacity-0');
                    bonusModalInner.classList.remove('scale-100');
                    bonusModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        bonusModal.classList.add('hidden');
                        bonusModal.classList.remove('flex');
                        editingType = null;
                    }, 300);
                }

                function getSelectedPotongan(row) {
                    const select = row.querySelector('.potongan-type');
                    return potonganData.find(item => String(item.id) === String(select.value));
                }

                function createPotonganOptions(selectedId = '', currentSelect = null) {
                    const selectedIds = getSelectedIds(potonganRows, '.potongan-type', currentSelect);
                    return potonganData.map(item => {
                        const tipe = String(item.tipe_potongan || '').toLowerCase();
                        if (tipe === 'tetap' || tipe === 'persentase') return '';
                        if (selectedIds.includes(String(item.id)) && String(item.id) !== String(selectedId)) {
                            return '';
                        }

                        const selected = String(item.id) === String(selectedId) ? 'selected' : '';
                        return `<option value="${item.id}" ${selected}>${escapeHtml(item.nama)}</option>`;
                    }).join('');
                }

                function refreshPotonganOptions() {
                    refreshUniqueOptions(potonganRows, '.potongan-type', potonganData, 'Jenis Potongan', createPotonganOptions);
                }

                function updatePotonganRow(row) {
                    const potongan = getSelectedPotongan(row);
                    const jumlahInput = row.querySelector('.potongan-jumlah');
                    const hint = row.querySelector('.potongan-hint');
                    const subtotalText = row.querySelector('.potongan-subtotal');

                    if (!potongan) {
                        jumlahInput.value = '';
                        jumlahInput.disabled = true;
                        hint.textContent = 'Pilih jenis potongan terlebih dahulu.';
                        subtotalText.textContent = 'Rp 0';
                        return;
                    }

                    const isFixed = String(potongan.tipe_potongan || '').toLowerCase() === 'tetap' || String(potongan.tipe_potongan || '').toLowerCase() === 'persentase';
                    jumlahInput.disabled = false;

                    if (isFixed) {
                        jumlahInput.value = 1;
                        jumlahInput.readOnly = true;
                        jumlahInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                        hint.textContent = 'Potongan ini dihitung secara otomatis per proses penggajian.';
                    } else {
                        jumlahInput.readOnly = false;
                        jumlahInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed');
                        jumlahInput.placeholder = 'Jumlah kejadian';
                        hint.textContent = 'Isi jumlah kejadian/pemakaian untuk potongan kondisional.';
                    }

                    subtotalText.textContent = formatRupiah(Number(jumlahInput.value || 0) * Number(potongan.tarif || 0));
                    updatePotonganModalTotal();
                }

                function updatePotonganModalTotal() {
                    let total = 0;
                    const state = activeKaryawanId ? ensureState(activeKaryawanId) : { gaji_pokok: 0 };

                    potonganData.forEach(p => {
                        const tipe = String(p.tipe_potongan || '').toLowerCase();
                        if (tipe === 'tetap') {
                            total += Number(p.tarif || 0);
                        } else if (tipe === 'persentase') {
                            total += Number(state.gaji_pokok || 0) * (Number(p.tarif || 0) / 100);
                        }
                    });

                    potonganRows.querySelectorAll('.potongan-row').forEach(row => {
                        const potongan = getSelectedPotongan(row);
                        const jumlah = Number(row.querySelector('.potongan-jumlah').value || 0);
                        if (potongan) {
                            total += jumlah * Number(potongan.tarif || 0);
                        }
                    });

                    potonganModalTotal.textContent = Number(total).toLocaleString('id-ID');
                    return total;
                }

                function collectPotonganDetails() {
                    const details = [];
                    const manualRows = [];

                    potonganRows.querySelectorAll('.potongan-row').forEach(row => {
                        const potongan = getSelectedPotongan(row);
                        const jumlah = Number(row.querySelector('.potongan-jumlah').value || 0);

                        if (!potongan || jumlah <= 0) {
                            return;
                        }

                        const tipe = String(potongan.tipe_potongan || '').toLowerCase();
                        const isFixed = tipe === 'tetap' || tipe === 'persentase';
                        let amount = 0;
                        let description = '';

                        if (tipe === 'tetap') {
                            amount = Number(potongan.tarif || 0);
                            description = `Potongan tetap sebesar ${formatRupiah(potongan.tarif)}`;
                        } else if (tipe === 'persentase') {
                            // Manual selection of persentase is normally disabled, but just in case
                            const state = activeKaryawanId ? ensureState(activeKaryawanId) : { gaji_pokok: 0 };
                            amount = Number(state.gaji_pokok || 0) * (Number(potongan.tarif || 0) / 100);
                            description = `Dihitung ${potongan.tarif}% dari Gaji Pokok`;
                        } else {
                            amount = jumlah * Number(potongan.tarif || 0);
                            description = `${formatNumber(jumlah)} kejadian x ${formatRupiah(potongan.tarif)}`;
                        }

                        details.push({
                            title: potongan.nama,
                            description: description,
                            amount,
                        });
                        manualRows.push({ id: potongan.id, jumlah });
                    });

                    return { details, manualRows };
                }

                function addPotonganRow(selectedId = '', jumlahValue = '') {
                    const row = document.createElement('div');
                    row.className = 'potongan-row grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-white border border-carbon-black-100 rounded-xl p-3 shadow-sm';
                    row.innerHTML = `
                        <div class="relative">
                            <select class="potongan-type w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                                <option value="" disabled ${selectedId ? '' : 'selected'}>Jenis Potongan</option>
                                ${createPotonganOptions(selectedId)}
                            </select>
                            <p class="potongan-hint text-[11px] font-semibold text-carbon-black-400 mt-1">Pilih jenis potongan terlebih dahulu.</p>
                        </div>
                        <input type="number" min="0" step="1" class="potongan-jumlah w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg" placeholder="Jumlah" disabled>
                        <div class="px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 rounded-lg text-sm font-black text-carbon-black-800">
                            <span class="potongan-subtotal">Rp 0</span>
                        </div>
                        <button type="button" class="remove-potongan-row h-11 w-11 inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 transition-colors" title="Kurangi potongan">
                            <x-tabler-minus class="w-4 h-4" />
                        </button>
                    `;

                    potonganRows.appendChild(row);
                    if (selectedId) {
                        row.querySelector('.potongan-type').value = selectedId;
                        row.querySelector('.potongan-jumlah').value = jumlahValue;
                    }
                    row.querySelector('.potongan-type').addEventListener('change', () => {
                        updatePotonganRow(row);
                        refreshPotonganOptions();
                    });
                    row.querySelector('.potongan-jumlah').addEventListener('input', () => updatePotonganRow(row));
                    row.querySelector('.remove-potongan-row').addEventListener('click', () => {
                        row.remove();
                        if (!potonganRows.querySelector('.potongan-row')) addPotonganRow();
                        refreshPotonganOptions();
                        updatePotonganModalTotal();
                    });

                    if (selectedId) updatePotonganRow(row);
                    refreshPotonganOptions();
                }

                function openPotonganModal() {
                    if (!activeKaryawanId) return;
                    editingType = 'potongan';
                    const state = ensureState(activeKaryawanId);
                    potonganRows.innerHTML = '';

                    potonganData.forEach(p => {
                        const tipe = String(p.tipe_potongan || '').toLowerCase();
                        if (tipe === 'tetap' || tipe === 'persentase') {
                            let amountStr = '0';
                            let descStr = 'Potongan tetap (Otomatis)';

                            if (tipe === 'tetap') {
                                amountStr = formatRupiah(p.tarif);
                            } else if (tipe === 'persentase') {
                                const amount = Number(state.gaji_pokok || 0) * (Number(p.tarif || 0) / 100);
                                amountStr = formatRupiah(amount);
                                descStr = `Potongan ${p.tarif}% dari Gaji Pokok`;
                            }

                            const row = document.createElement('div');
                            row.className = 'grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-carbon-black-50 border border-carbon-black-100 rounded-xl p-3 shadow-sm opacity-90';
                            row.innerHTML = `
                                <div class="relative">
                                    <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed text-rose-700 truncate" value="${escapeHtml(p.nama)}" readonly title="${escapeHtml(p.nama)}">
                                    <p class="text-[11px] font-semibold text-carbon-black-400 mt-1">Bawaan sistem (Otomatis)</p>
                                </div>
                                <input type="text" class="w-full px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-800 rounded-lg cursor-not-allowed truncate" value="${escapeHtml(descStr)}" readonly title="${escapeHtml(descStr)}">
                                <div class="px-4 py-3 bg-carbon-black-100 border border-carbon-black-200 rounded-lg text-sm font-black text-rose-600">
                                    <span>${amountStr}</span>
                                </div>
                                <div class="h-11 w-11 inline-flex items-center justify-center rounded-lg bg-carbon-black-100 border border-carbon-black-200 text-carbon-black-400 cursor-not-allowed">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                </div>
                            `;
                            potonganRows.appendChild(row);
                        }
                    });

                    if (state.manualPotonganRows.length > 0) {
                        state.manualPotonganRows.forEach(item => addPotonganRow(item.id, item.jumlah));
                    } else {
                        const hasOnlyTetap = potonganData.filter(p => {
                            const t = String(p.tipe_potongan || '').toLowerCase();
                            return t !== 'tetap' && t !== 'persentase';
                        }).length === 0;
                        if (!hasOnlyTetap) addPotonganRow();
                    }

                    updatePotonganModalTotal();
                    potonganModal.classList.remove('hidden');
                    potonganModal.classList.add('flex');
                    setTimeout(() => {
                        potonganModal.classList.remove('opacity-0');
                        potonganModalInner.classList.remove('scale-95');
                        potonganModalInner.classList.add('scale-100');
                    }, 10);
                }

                window.closePotonganModal = function () {
                    potonganModal.classList.add('opacity-0');
                    potonganModalInner.classList.remove('scale-100');
                    potonganModalInner.classList.add('scale-95');
                    setTimeout(() => {
                        potonganModal.classList.add('hidden');
                        potonganModal.classList.remove('flex');
                        editingType = null;
                    }, 300);
                }

                rows.forEach(row => {
                    const id = row.dataset.karyawanId;
                    ensureState(id);
                    updatePeriodBonus(id);
                    renderTableRow(id);
                    row.addEventListener('click', () => selectRow(id));
                });
                sortPayrollRows();
                applyPayrollFilters();

                document.querySelectorAll('[data-action="process"]').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        processPayroll(this.dataset.karyawanId);
                    });
                });

                document.querySelectorAll('[data-action="slip"]').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        openSlipModal(this.dataset.karyawanId);
                    });
                });

                monthSelect?.addEventListener('change', function () {
                    rows.forEach(row => {
                        updatePeriodBonus(row.dataset.karyawanId);
                        renderTableRow(row.dataset.karyawanId);
                    });
                    sortPayrollRows();
                    applyPayrollFilters();
                    if (activeKaryawanId) renderDetail(activeKaryawanId);
                });

                yearSelect?.addEventListener('change', function () {
                    rows.forEach(row => {
                        updatePeriodBonus(row.dataset.karyawanId);
                        renderTableRow(row.dataset.karyawanId);
                    });
                    sortPayrollRows();
                    applyPayrollFilters();
                    if (activeKaryawanId) renderDetail(activeKaryawanId);
                });

                payrollPagination?.addEventListener('click', function (event) {
                    const button = event.target.closest('[data-payroll-page]');
                    if (!button || button.disabled) return;
                    payrollCurrentPage = Number(button.dataset.payrollPage || 1);
                    applyPayrollFilters();
                });

                payrollSearch?.addEventListener('input', function () {
                    payrollCurrentPage = 1;
                    applyPayrollFilters();
                });
                payrollBagianFilter?.addEventListener('change', function () {
                    payrollCurrentPage = 1;
                    applyPayrollFilters();
                });

                detailEditBonus.addEventListener('click', openBonusModal);
                detailEditPotongan.addEventListener('click', openPotonganModal);
                detailSavePayroll.addEventListener('click', function () {
                    if (!activeKaryawanId) return;
                    recalculateState(activeKaryawanId);
                    renderTableRow(activeKaryawanId);
                    renderDetail(activeKaryawanId);
                });
                salaryDetailList.addEventListener('click', function (event) {
                    const button = event.target.closest('[data-component-detail]');
                    if (!button) return;
                    openComponentDetailModal(button.dataset.componentDetail);
                });
                addBonusRowButton.addEventListener('click', () => addBonusRow());
                addPotonganRowButton.addEventListener('click', () => addPotonganRow());

                saveBonusModalButton.addEventListener('click', function () {
                    if (!activeKaryawanId || editingType !== 'bonus') return;
                    const state = ensureState(activeKaryawanId);
                    const result = collectBonusDetails();
                    state.bonusDetails = result.details;
                    state.manualBonusRows = result.manualRows;
                    state.total_bonus = updateBonusModalTotal();
                    recalculateState(activeKaryawanId);
                    renderTableRow(activeKaryawanId);
                    renderDetail(activeKaryawanId);
                    closeBonusModal();
                });

                savePotonganModalButton.addEventListener('click', function () {
                    if (!activeKaryawanId || editingType !== 'potongan') return;
                    const state = ensureState(activeKaryawanId);
                    const result = collectPotonganDetails();
                    state.potonganDetails = result.details;
                    state.manualPotonganRows = result.manualRows;
                    state.total_potongan = updatePotonganModalTotal();
                    recalculateState(activeKaryawanId);
                    renderTableRow(activeKaryawanId);
                    renderDetail(activeKaryawanId);
                    closePotonganModal();
                });

                if (activeKaryawanId) {
                    selectRow(activeKaryawanId);
                }
            });
        </script>
    @endpush
@endsection