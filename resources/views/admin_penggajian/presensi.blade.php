@extends('layouts.app')

@section('title', 'Data Presensi')

@section('content')
<div class="flex flex-col space-y-8 overflow-hidden">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1
            class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            DATA PRESENSI
        </h1>

        <div class="flex flex-col xl:flex-row xl:justify-between items-start xl:items-end gap-6">
            <form action="{{ route('presensi.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <!-- Search Name -->
                <div
                    class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama karyawan..."
                        class="w-72 px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-base font-medium">
                    <button type="submit"
                        class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>

                <!-- Date Filter -->
                <style>
                    /* Enlarge the native Webkit date picker popup by increasing base font-size,
                       while maintaining the display size of the input text */
                    #date-filter {
                        font-size: 1.3rem !important;
                    }

                    #date-filter::-webkit-datetime-edit {
                        font-size: 1rem !important;
                    }

                    #date-filter::-webkit-calendar-picker-indicator {
                        cursor: pointer;
                        font-size: 1rem !important;
                    }
                </style>
                <div
                    class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 hover:border-carbon-black-300 transition-colors bg-white items-center">
                    <div class="pl-4 text-carbon-black-400">
                        <x-tabler-calendar class="w-5 h-5" />
                    </div>
                    <input type="date" name="date" id="date-filter" value="{{ $date }}" title="Pilih Tanggal Presensi"
                        class="w-40 px-3 py-2 font-bold text-carbon-black-700 focus:outline-none bg-transparent appearance-none"
                        onchange="this.form.submit()">
                </div>
            </form>

            <button onclick="openKoreksiModal()"
                class="bg-amber-500 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-amber-600 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5 ml-auto xl:ml-0"
                title="Koreksi Presensi">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Koreksi Presensi</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div
            class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <x-tabler-circle-check class="w-5 h-5 text-emerald-600 shrink-0" />
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                <x-tabler-x class="w-4 h-4" />
            </button>
        </div>
    @endif
    @if(session('error'))
        <div
            class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <x-tabler-alert-triangle class="w-5 h-5 text-rose-600 shrink-0" />
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1">
                <x-tabler-x class="w-4 h-4" />
            </button>
        </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative flex-1 min-h-0">
        <div class="max-h-[calc(125vh-24rem)] overflow-y-auto scrollbar-invisible">
            <table class="w-full border-collapse">
                <thead
                    class="sticky top-0 z-10 bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                    <tr>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-20">NO</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JAM MASUK</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">JAM KELUAR</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">KEHADIRAN</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">LEMBUR</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-carbon-black-100">
                    @foreach($presensi as $index => $p)
                        @php
                            $isInactive = $p['is_inactive'] ?? false;
                        @endphp
                        <tr
                            class="{{ $isInactive ? 'bg-carbon-black-50/70 opacity-75' : 'hover:bg-hunter-green-50' }} transition-colors duration-150 group">
                            <td
                                class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">
                                {{ (($page - 1) * 10) + $index + 1 }}</td>
                            <td
                                class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">
                                <div class="flex flex-col items-start gap-1">
                                    <span>{{ $p['nama'] }}</span>
                                    @if($isInactive)
                                        <span
                                            class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-widest text-rose-600">Inaktif</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">
                                {{ date('d M Y', strtotime($p['tanggal'])) }}</td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center">
                                    <input type="time" id="masuk_{{ $p['id'] }}" name="jam_masuk_{{ $p['id'] }}"
                                        value="{{ $p['jam_masuk'] }}" onchange="calculateLembur('{{ $p['id'] }}', true)"
                                        title="{{ $isInactive ? 'Karyawan inaktif' : 'Ubah Jam Masuk' }}"
                                        class="w-[105px] text-center text-base font-semibold text-carbon-black-800 bg-transparent border-0 focus:ring-1 focus:ring-hunter-green-400 focus:outline-none {{ $isInactive ? 'cursor-not-allowed text-carbon-black-400' : 'cursor-pointer hover:bg-carbon-black-50' }} px-1 py-1 rounded transition-colors"
                                        {{ $isInactive ? 'disabled' : '' }}>
                                    <div id="badge_terlambat_{{ $p['id'] }}">
                                        @if(isset($p['menit_terlambat']) && $p['menit_terlambat'] > 15)
                                            <span
                                                class="inline-block text-[10px] font-black uppercase tracking-wider text-amber-700 bg-amber-100 border border-amber-300 px-1.5 py-0.5 rounded mt-0.5"
                                                title="Terlambat melebihi batas toleransi 15 menit">
                                                Terlambat {{ $p['menit_terlambat'] }}m
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <input type="time" id="keluar_{{ $p['id'] }}" name="jam_keluar_{{ $p['id'] }}"
                                    value="{{ $p['jam_keluar'] }}" onchange="calculateLembur('{{ $p['id'] }}', true)"
                                    title="{{ $isInactive ? 'Karyawan inaktif' : 'Ubah Jam Keluar' }}"
                                    class="w-[105px] text-center text-base font-semibold text-carbon-black-800 bg-transparent border-0 focus:ring-1 focus:ring-hunter-green-400 focus:outline-none {{ $isInactive ? 'cursor-not-allowed text-carbon-black-400' : 'cursor-pointer hover:bg-carbon-black-50' }} px-1 py-1 rounded transition-colors"
                                    {{ $isInactive ? 'disabled' : '' }}>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center space-x-2.5">
                                    @php
                                        $options = [
                                            'Hadir' => ['label' => 'H', 'accent' => 'accent-emerald-600'],
                                            'Izin' => ['label' => 'I', 'accent' => 'accent-amber-500'],
                                            'Sakit' => ['label' => 'S', 'accent' => 'accent-blue-500'],
                                            'Cuti' => ['label' => 'C', 'accent' => 'accent-purple-500'],
                                            'Alpha' => ['label' => 'A', 'accent' => 'accent-rose-600'],
                                        ];
                                        $currentStatus = $p['kehadiran'] === 'Alfa' ? 'Alpha' : $p['kehadiran'];
                                    @endphp
                                    @foreach($options as $val => $opt)
                                        <label
                                            class="flex items-center space-x-1 {{ $isInactive ? 'cursor-not-allowed' : 'cursor-pointer group/radio' }}"
                                            title="{{ $isInactive ? 'Karyawan inaktif' : $val }}">
                                            <input type="radio" name="kehadiran_{{ $p['id'] }}" value="{{ $val }}"
                                                class="w-4 h-4 {{ $opt['accent'] }} {{ $isInactive ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                                {{ $currentStatus === $val ? 'checked' : '' }} {{ $isInactive ? 'disabled' : '' }}
                                                onclick="handleStatusChange(this, '{{ $p['id'] }}', '{{ $p['nama'] }}', '{{ $val }}')">
                                            <span
                                                class="text-xs font-black text-carbon-black-500 {{ $isInactive ? '' : 'group-hover/radio:text-carbon-black-900' }} transition-colors">{{ $opt['label'] }}</span>
                                        </label>
                                    @endforeach
                                    <input type="hidden" id="keterangan_harian_{{ $p['id'] }}"
                                        name="keterangan_{{ $p['id'] }}" value="{{ $p['keterangan'] ?? '' }}">
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center" id="jam_lembur_{{ $p['id'] }}">
                                @if(($p['jam_lembur'] ?? 0) > 0)
                                    <span class="text-base font-semibold text-carbon-black-800">{{ $p['jam_lembur'] }} Jam</span>
                                @else
                                    <span class="text-base text-carbon-black-300 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" onclick='openDetailModal(@json($p))'
                                        class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200"
                                        title="Lihat">
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
        <div
            class="bg-carbon-black-50 border-t border-carbon-black-100 px-6 py-4 flex flex-col xl:flex-row items-center justify-between gap-4">
            <!-- Kehadiran Legend -->
            <div class="flex items-center space-x-4 text-sm font-semibold text-carbon-black-600 shrink-0">
                <span class="font-bold text-carbon-black-800 uppercase tracking-widest pl-2">Keterangan :</span>
                <div class="flex items-center space-x-4">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full"
                            style="background-color: #059669"></span>H = Hadir</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full"
                            style="background-color: #f59e0b"></span>I = Izin</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full"
                            style="background-color: #3b82f6"></span>S = Sakit</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full"
                            style="background-color: #a855f7"></span>C = Cuti</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full"
                            style="background-color: #e11d48"></span>A = Alpha</span>
                </div>
            </div>

            <nav role="navigation" aria-label="pagination" class="flex w-full justify-center xl:w-auto xl:justify-end">
                <ul class="flex flex-row items-center gap-1">
                    <li>
                        <a href="{{ $page > 1 ? request()->fullUrlWithQuery(['page' => $page - 1]) : '#' }}"
                            class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 {{ $page <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                            <x-tabler-chevron-left class="h-4 w-4" />
                            <span>Sebelumnya</span>
                        </a>
                    </li>
                    @for($i = 1; $i <= $totalPages; $i++)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-md border text-sm font-medium shadow-sm transition-all {{ $page == $i ? 'bg-hunter-green-600 text-white shadow-hunter-green-200 border-hunter-green-600' : 'border-carbon-black-200 bg-white text-carbon-black-700 hover:bg-carbon-black-50' }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li>
                        <a href="{{ $page < $totalPages ? request()->fullUrlWithQuery(['page' => $page + 1]) : '#' }}"
                            class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 {{ $page >= $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                            <span>Berikutnya</span>
                            <x-tabler-chevron-right class="h-4 w-4" />
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Prompt Keterangan Harian -->
<div id="modal-prompt-keterangan"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/60 backdrop-blur-sm transition-opacity opacity-0">
    <div id="prompt-modal-card" style="max-width: 520px !important; width: 100% !important; margin: auto !important;"
        class="bg-white rounded-2xl shadow-2xl w-full mx-auto transform scale-95 transition-all duration-300 flex flex-col overflow-hidden border border-carbon-black-200">

        <!-- Header -->
        <div class="px-6 pt-5 pb-4 flex justify-between items-center bg-white border-b border-carbon-black-100">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-hunter-green-50 text-hunter-green-700 shrink-0 border border-hunter-green-100 shadow-2xs">
                    <x-tabler-file-description class="w-5 h-5" />
                </div>
                <h3 id="prompt-modal-title" class="text-base font-black tracking-wide text-carbon-black-900">
                    Keterangan Izin Karyawan
                </h3>
            </div>
            <button type="button"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-carbon-black-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                onclick="closePromptModal()" title="Tutup">
                <x-tabler-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-4 flex flex-col gap-4">
            <!-- Employee & Status Identity Box -->
            <div
                class="bg-carbon-black-50/80 border border-carbon-black-200/80 rounded-xl p-3.5 flex items-center justify-between gap-3 shadow-inner">
                <div class="flex flex-col min-w-0">
                    <span class="text-[10px] font-black uppercase tracking-widest text-carbon-black-400">Karyawan</span>
                    <span id="prompt-nama-label" class="text-sm font-black text-carbon-black-900 truncate">Budi
                        Santoso</span>
                </div>
                <div class="shrink-0 flex items-center">
                    <span id="prompt-status-badge"
                        class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider border shadow-sm transition-all duration-200 bg-amber-100 text-amber-800 border-amber-300">
                        IZIN (I)
                    </span>
                </div>
            </div>

            <input type="hidden" id="prompt-active-id">

            <!-- Detail Note Textarea -->
            <div class="flex flex-col gap-1.5">
                <label for="prompt-keterangan-input"
                    class="text-xs font-bold uppercase tracking-wider text-carbon-black-700 flex items-center gap-1.5">
                    <x-tabler-notes class="w-3.5 h-3.5 text-carbon-black-500" />
                    Rincian Alasan
                </label>
                <div class="relative">
                    <textarea id="prompt-keterangan-input" rows="3"
                        placeholder="Tuliskan alasan ketidakhadiran secara lengkap..."
                        class="w-full px-3.5 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold text-carbon-black-800 focus:outline-none focus:border-hunter-green-500 focus:ring-4 focus:ring-hunter-green-100 resize-none transition-all shadow-sm bg-white placeholder:text-carbon-black-300 placeholder:font-normal leading-relaxed"></textarea>
                </div>
                <div class="flex items-center justify-between text-[11px] text-carbon-black-400 pt-0.5">
                    <span class="flex items-center gap-1">
                        <x-tabler-info-circle class="w-3.5 h-3.5 shrink-0" />
                        Informasi tercatat sebagai jejak audit.
                    </span>
                    <button type="button" onclick="document.getElementById('prompt-keterangan-input').value = ''"
                        class="hover:text-rose-500 font-semibold transition-colors text-[10px] uppercase tracking-wider">
                        Kosongkan
                    </button>
                </div>
            </div>

            <!-- Upload Bukti / Foto Surat Fisik -->
            <div class="flex flex-col gap-1.5">
                <label
                    class="text-xs font-bold uppercase tracking-wider text-carbon-black-700 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <x-tabler-photo class="w-4 h-4 text-carbon-black-500" />
                        Unggah Bukti / Foto Surat (Opsional)
                    </span>
                    <span class="text-[10px] text-carbon-black-400 font-normal">JPG, PNG, PDF (Maks. 5MB)</span>
                </label>
                <div class="relative border-2 border-dashed border-carbon-black-200 hover:border-hunter-green-500 rounded-xl p-3 bg-carbon-black-50/50 hover:bg-hunter-green-50/20 transition-all text-center group cursor-pointer"
                    onclick="document.getElementById('prompt-file-input').click()">
                    <input type="file" id="prompt-file-input" accept="image/*,.pdf" class="hidden"
                        onchange="handlePromptFileSelect(this)">

                    <!-- Placeholder State -->
                    <div id="prompt-file-empty"
                        class="flex items-center justify-center gap-2 py-1 text-carbon-black-500 group-hover:text-hunter-green-700 transition-colors">
                        <x-tabler-cloud-upload
                            class="w-5 h-5 text-carbon-black-400 group-hover:text-hunter-green-600 transition-colors" />
                        <span class="text-xs font-semibold">Pilih atau seret foto surat/keterangan di sini</span>
                    </div>

                    <!-- File Selected State -->
                    <div id="prompt-file-selected"
                        class="hidden items-center justify-between bg-white border border-carbon-black-200 rounded-lg p-2 shadow-2xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <x-tabler-file-check class="w-4 h-4 text-emerald-600 shrink-0" />
                            <span id="prompt-file-name"
                                class="text-xs font-bold text-carbon-black-800 truncate">foto_surat.jpg</span>
                            <span id="prompt-file-size" class="text-[10px] text-carbon-black-400 shrink-0">(120
                                KB)</span>
                        </div>
                        <button type="button" onclick="event.stopPropagation(); clearPromptFile();"
                            class="text-carbon-black-400 hover:text-rose-600 p-1 rounded transition-colors"
                            title="Hapus berkas">
                            <x-tabler-trash class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div
            class="px-6 py-4 flex items-center justify-end space-x-3 bg-carbon-black-50/70 border-t border-carbon-black-100 rounded-b-2xl">
            <button type="button" onclick="closePromptModal()"
                class="px-4 py-2.5 rounded-xl border border-carbon-black-200 text-carbon-black-600 font-bold text-xs uppercase tracking-wider hover:bg-white hover:border-carbon-black-300 hover:text-carbon-black-900 transition-all active:scale-95 shadow-sm">
                Lewati / Nanti
            </button>
            <button type="button" id="prompt-save-btn" onclick="savePromptKeterangan()"
                class="px-5 py-2.5 text-white bg-hunter-green-600 hover:bg-hunter-green-700 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
                <x-tabler-device-floppy class="w-4 h-4" />
                <span>Simpan Keterangan</span>
            </button>
        </div>
    </div>
</div>



<!-- Modal Susulan / Koreksi Presensi -->
<div id="modal-koreksi"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div id="modal-koreksi-card" style="max-width: 640px !important; width: 100% !important; margin: auto !important;"
        class="bg-white rounded-2xl shadow-2xl w-full mx-4 transform scale-95 transition-all duration-300 max-h-[90vh] flex flex-col overflow-hidden border border-carbon-black-200">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center bg-white">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center border border-amber-200 shadow-2xs">
                    <x-tabler-calendar-time class="w-5 h-5" />
                </div>
                <div class="flex flex-col">
                    <h3 class="text-lg font-black uppercase tracking-wide text-carbon-black-900">Koreksi Presensi</h3>
                </div>
            </div>
            <button type="button"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-carbon-black-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                onclick="closeKoreksiModal()" title="Tutup">
                <x-tabler-x class="w-5 h-5" />
            </button>
        </div>

        <form action="{{ route('presensi.store_koreksi') }}" method="POST" class="flex flex-col flex-1 overflow-hidden"
            onsubmit="return validateKoreksiForm()">
            @csrf
            <div class="px-6 py-5 overflow-y-auto space-y-4">
                <!-- Audit & Cut-off Notice Box -->
                <div
                    class="bg-amber-50/70 border border-amber-200 text-amber-900 px-4 py-3 rounded-xl flex items-start gap-3 shadow-2xs">
                    <x-tabler-info-circle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                    <div class="space-y-1">
                        <h4 class="text-xs font-bold text-amber-950 leading-tight">Aturan Periode Berjalan & Sistem Jejak Audit</h4>
                        <p class="text-xs text-amber-900/85 leading-relaxed">
                            Koreksi presensi hanya diperkenankan untuk <strong>periode bulan berjalan</strong> sebelum
                            perhitungan penggajian. Segala revisi akan tercatat permanen di <strong>Jejak Audit</strong>
                            dan memerlukan alasan yang valid.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Searchable Employee Input (Autocomplete) -->
                    <div class="space-y-1.5 md:col-span-2 relative" id="koreksi-karyawan-wrapper">
                        <label
                            class="text-xs font-bold uppercase tracking-wider text-carbon-black-700 block">
                            Nama Karyawan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="koreksi-nama-search"
                                placeholder="Ketik nama karyawan..." autocomplete="off"
                                class="w-full pl-10 pr-10 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all bg-white placeholder:text-carbon-black-300"
                                oninput="handleKoreksiSearch(this.value)">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-carbon-black-400">
                                <x-tabler-search class="w-4 h-4" />
                            </div>
                            <button type="button" id="koreksi-clear-nama" onclick="clearKoreksiNama()"
                                class="hidden absolute inset-y-0 right-0 pr-3.5 flex items-center text-carbon-black-400 hover:text-rose-600 transition-colors"
                                title="Hapus pilihan">
                                <x-tabler-x class="w-4 h-4" />
                            </button>
                            <input type="hidden" name="nama" id="koreksi-nama-val" required>
                        </div>

                        <!-- Autocomplete Dropdown List -->
                        <div id="koreksi-autocomplete-list"
                            class="hidden absolute left-0 right-0 top-full mt-1 max-h-48 overflow-y-auto bg-white border border-carbon-black-200 rounded-xl shadow-xl z-50 divide-y divide-carbon-black-100">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Tanggal Revisi with Smart Constraints -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-carbon-black-700">Tanggal Revisi
                            <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" id="koreksi-tanggal" required min="{{ $currentMonthMin }}"
                            max="{{ $currentMonthMax }}" value="{{ $date }}"
                            class="w-full px-3.5 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all bg-white"
                            onchange="handleKoreksiDateChange()">
                        <span class="text-xs text-carbon-black-500 flex items-center gap-1.5 mt-1.5 font-medium">
                            <x-tabler-calendar-event class="w-4 h-4 text-amber-500 shrink-0" />
                            <span>Periode: {{ date('01 M Y', strtotime($currentMonthMin)) }} s/d
                            {{ date('d M Y', strtotime($currentMonthMax)) }}</span>
                        </span>
                    </div>

                    <!-- Status Kehadiran Baru -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-carbon-black-700">Status Kehadiran
                            Baru <span class="text-rose-500">*</span></label>
                        <select name="status" id="koreksi-status" required
                            class="w-full px-3.5 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all bg-white"
                            onchange="handleKoreksiStatusChange(this.value)">
                            <option value="Hadir">Hadir (H)</option>
                            <option value="Izin">Izin (I)</option>
                            <option value="Sakit">Sakit (S)</option>
                            <option value="Cuti">Cuti (C)</option>
                            <option value="Alpha">Alpha (A)</option>
                        </select>
                    </div>

                    <!-- Live Preview of Existing Record (Dynamic) -->
                    <div id="koreksi-preview-wrapper" class="md:col-span-2 hidden">
                        <div
                            class="bg-carbon-black-50/90 border border-carbon-black-200/80 rounded-xl p-3 flex items-center justify-between gap-3 shadow-inner">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div
                                    class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                    <x-tabler-history class="w-4 h-4" />
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-carbon-black-400">Data
                                        Tercatat Pada Tanggal Ini</span>
                                    <span id="koreksi-preview-text"
                                        class="text-xs font-black text-carbon-black-900 truncate">-</span>
                                </div>
                            </div>
                            <span id="koreksi-preview-badge"
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border shrink-0 bg-emerald-100 text-emerald-800 border-emerald-300">
                                Tercatat
                            </span>
                        </div>
                    </div>

                    <!-- Jam Masuk -->
                    <div class="space-y-1.5" id="koreksi-jam-masuk-group">
                        <label class="text-xs font-bold uppercase tracking-wider text-carbon-black-700">Jam
                            Masuk</label>
                        <input type="time" name="jam_masuk" id="koreksi-jam-masuk" value="08:00"
                            class="w-full px-3.5 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all bg-white">
                    </div>

                    <!-- Jam Keluar -->
                    <div class="space-y-1.5" id="koreksi-jam-keluar-group">
                        <label class="text-xs font-bold uppercase tracking-wider text-carbon-black-700">Jam
                            Keluar</label>
                        <input type="time" name="jam_keluar" id="koreksi-jam-keluar" value="16:00"
                            class="w-full px-3.5 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all bg-white">
                    </div>

                    <!-- Status Non-Hadir Notice (Hidden when Hadir) -->
                    <div id="koreksi-nonhadir-notice"
                        class="md:col-span-2 hidden bg-blue-50/70 border border-blue-200 rounded-xl p-2.5 text-xs text-blue-800 flex items-center gap-2">
                        <x-tabler-info-circle class="w-4 h-4 text-blue-600 shrink-0" />
                        <span>Jam masuk dan keluar otomatis diatur ke <strong>00:00 - 00:00</strong> untuk status
                            non-hadir.</span>
                    </div>

                    <!-- Alasan Koreksi -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label
                            class="text-xs font-bold uppercase tracking-wider text-carbon-black-700 flex items-center justify-between">
                            <span>Alasan Koreksi <span class="text-rose-500">*</span></span>
                            <span class="text-[10px] text-carbon-black-400 font-normal">Wajib diisi untuk Jejak
                                Audit</span>
                        </label>
                        <textarea name="alasan" id="koreksi-alasan" required rows="3"
                            placeholder="Contoh: Karyawan baru menyerahkan surat izin/keterangan dokter hari ini, atau koreksi kekeliruan pencatatan kehadiran..."
                            class="w-full px-3.5 py-2.5 border-2 border-carbon-black-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-100 transition-all bg-white resize-none placeholder:text-carbon-black-300 placeholder:font-normal leading-relaxed"></textarea>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="px-6 py-4 border-t border-carbon-black-100 flex justify-end space-x-3 bg-carbon-black-50/70 rounded-b-2xl">
                <button type="button" onclick="closeKoreksiModal()"
                    class="px-4 py-2.5 rounded-xl border border-carbon-black-200 text-carbon-black-600 font-bold text-xs uppercase tracking-wider hover:bg-white hover:border-carbon-black-300 hover:text-carbon-black-900 transition-all active:scale-95 shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-white bg-amber-600 hover:bg-amber-700 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <x-tabler-device-floppy class="w-4 h-4" />
                    <span>Simpan Koreksi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Presensi -->
<div id="modal-detail"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div
        class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Rekapitulasi Kehadiran</h3>
            <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors"
                onclick="closeDetailModal()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>

        <div class="px-6 py-6 flex flex-col lg:flex-row gap-6 items-start">
            <!-- Left Side: Summary & Stats -->
            <div class="w-full lg:w-1/3 flex flex-col gap-5">
                <div class="flex flex-col justify-center items-center pb-5 border-b border-carbon-black-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500 mb-1">Nama Karyawan</p>
                    <p id="detail-nama" class="text-xl font-black text-carbon-black-900 text-center">-</p>
                    <div class="w-full flex justify-center">
                        <span
                            class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-hunter-green-50 text-hunter-green-700 uppercase tracking-widest border border-hunter-green-200">
                            Akumulasi Bulan Ini
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm"
                        style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #059669;">Hadir
                            (H)</span>
                        <span id="detail-hadir" class="text-lg font-black" style="color: #047857;">0</span>
                    </div>
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm"
                        style="background-color: #fffbeb; border: 1px solid #fde68a;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #d97706;">Izin
                            (I)</span>
                        <span id="detail-izin" class="text-lg font-black text-carbon-black-900">0</span>
                    </div>
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm"
                        style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #2563eb;">Sakit
                            (S)</span>
                        <span id="detail-sakit" class="text-lg font-black text-carbon-black-900">0</span>
                    </div>
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm"
                        style="background-color: #faf5ff; border: 1px solid #e9d5ff;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #9333ea;">Cuti
                            (C)</span>
                        <span id="detail-cuti" class="text-lg font-black text-carbon-black-900">0</span>
                    </div>
                    <div class="col-span-2 rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm"
                        style="background-color: #fff1f2; border: 1px solid #fecdd3;">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-carbon-black-900">Alpha
                            (A)</span>
                        <span id="detail-alpha" class="text-lg font-black" style="color: #e11d48;">0</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Calendar -->
            <div class="w-full lg:w-2/3 border border-carbon-black-100 rounded-lg overflow-hidden bg-white shadow-sm">
                <style>
                    /* Customizing FullCalendar Minimalist style */
                    .fc {
                        font-family: inherit;
                    }

                    .fc .fc-toolbar-title {
                        font-size: 1rem;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        color: #1a1a1a;
                    }

                    .fc .fc-button-primary {
                        background-color: #2F4F4F;
                        border-color: #2F4F4F;
                        text-transform: uppercase;
                        font-size: 0.7rem;
                        font-weight: bold;
                        padding: 0.3rem 0.6rem;
                    }

                    .fc .fc-button-primary:hover {
                        background-color: #1a2e2e;
                        border-color: #1a2e2e;
                    }

                    .fc .fc-button-primary:not(:disabled):active,
                    .fc .fc-button-primary:not(:disabled).fc-button-active {
                        background-color: #0d1717;
                        border-color: #0d1717;
                    }

                    .fc-theme-standard th {
                        background-color: #f8fafc;
                        padding: 6px 0;
                        font-size: 0.75rem;
                        text-transform: uppercase;
                        color: #64748b;
                        border-color: #e2e8f0;
                    }

                    .fc-theme-standard td,
                    .fc-theme-standard th {
                        border-color: #e2e8f0;
                    }

                    .fc .fc-daygrid-day-number {
                        font-size: 0.8rem;
                        font-weight: bold;
                        color: #334155;
                        padding: 4px 8px;
                        text-decoration: none;
                    }

                    .fc .fc-daygrid-day.fc-day-today {
                        background-color: #f0fdf4;
                    }

                    .fc-event {
                        border: none !important;
                        border-radius: 4px !important;
                        padding: 2px !important;
                        font-weight: bold !important;
                        font-size: 0.65rem !important;
                        text-align: center !important;
                        margin: 1px 2px !important;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                        cursor: default;
                    }

                    .fc .fc-daygrid-day-frame {
                        min-height: 60px;
                    }

                    .fc .fc-view-harness,
                    .fc .fc-daygrid,
                    .fc .fc-daygrid-body-unbalanced,
                    .fc .fc-scrollgrid,
                    .fc .fc-col-header,
                    .fc .fc-daygrid-body,
                    .fc .fc-scrollgrid-sync-table {
                        width: 100% !important;
                    }

                    .fc .fc-scrollgrid,
                    .fc .fc-col-header,
                    .fc .fc-scrollgrid-sync-table {
                        table-layout: fixed !important;
                    }
                </style>
                <div id="employee-calendar" class="p-3 bg-white"></div>
            </div>
        </div>

        <!-- Audit Trail Section in Detail Modal -->
        <div class="px-6 pb-6 pt-2 border-t border-carbon-black-100 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-tabler-history class="w-5 h-5 text-hunter-green-600" />
                    <h4 class="text-sm font-black uppercase tracking-wider text-carbon-black-900">Jejak Audit & Riwayat
                        Pengecualian</h4>
                </div>
                <span class="text-[11px] font-bold text-carbon-black-400 uppercase tracking-widest">Sistem Audit Trail
                    Presensi</span>
            </div>

            <div id="detail-audit-container"
                class="max-h-48 overflow-y-auto rounded-lg border border-carbon-black-100 bg-carbon-black-50/50 p-3 space-y-2">
                <!-- Injected via JavaScript from data.audit_trail -->
            </div>
        </div>

        <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex justify-end rounded-b-xl">
            <button type="button"
                class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all"
                onclick="closeDetailModal()">Kembali</button>
        </div>
    </div>
</div>

<!-- Floating Notification Toast for Inline Saves -->
<div id="inline-toast"
    class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 bg-carbon-black-900 text-white px-4 py-2.5 rounded-lg shadow-xl flex items-center space-x-2 text-sm font-semibold pointer-events-none">
    <x-tabler-check class="w-4 h-4 text-emerald-400" />
    <span id="inline-toast-msg">Data presensi tersimpan</span>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    let toastTimer;
    function showInlineToast(msg) {
        const toast = document.getElementById('inline-toast');
        const toastMsg = document.getElementById('inline-toast-msg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 2200);
    }

    function saveInlinePresensi(empId, data = {}) {
        const masukInput = document.getElementById(`masuk_${empId}`);
        const keluarInput = document.getElementById(`keluar_${empId}`);
        const checkedRadio = document.querySelector(`input[name="kehadiran_${empId}"]:checked`);
        const hiddenInput = document.getElementById(`keterangan_harian_${empId}`);

        const payload = {
            id: empId,
            jam_masuk: data.jam_masuk !== undefined ? data.jam_masuk : (masukInput ? masukInput.value : '08:00'),
            jam_keluar: data.jam_keluar !== undefined ? data.jam_keluar : (keluarInput ? keluarInput.value : '16:00'),
            kehadiran: data.kehadiran !== undefined ? data.kehadiran : (checkedRadio ? checkedRadio.value : 'Hadir'),
            keterangan: data.keterangan !== undefined ? data.keterangan : (hiddenInput ? hiddenInput.value : '')
        };

        fetch('{{ route('presensi.update_inline') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(resData => {
                const badgeEl = document.getElementById(`badge_terlambat_${empId}`);
                if (badgeEl) {
                    if (resData.is_terlambat && resData.menit_terlambat > 15) {
                        badgeEl.innerHTML = `<span class="inline-block text-[10px] font-black uppercase tracking-wider text-amber-700 bg-amber-100 border border-amber-300 px-1.5 py-0.5 rounded mt-0.5" title="Terlambat melebihi batas toleransi 15 menit">Terlambat ${resData.menit_terlambat}m</span>`;
                    } else {
                        badgeEl.innerHTML = '';
                    }
                }
            })
            .catch(err => console.error('Gagal menyimpan perubahan:', err));
    }

    function calculateLembur(id, autoSave = false) {
        const masukInput = document.getElementById(`masuk_${id}`);
        const keluarInput = document.getElementById(`keluar_${id}`);
        const lemburEl = document.getElementById(`jam_lembur_${id}`);
        const badgeEl = document.getElementById(`badge_terlambat_${id}`);

        if (!masukInput || !keluarInput || !lemburEl) return;

        const masukVal = masukInput.value;
        const keluarVal = keluarInput.value;

        if (masukVal && keluarVal && masukVal !== '' && keluarVal !== '' && masukVal !== '00:00' && keluarVal !== '00:00') {
            const [mH, mM] = masukVal.split(':').map(Number);
            const [kH, kM] = keluarVal.split(':').map(Number);

            let wMasuk = mH + (mM / 60);
            let wKeluar = kH + (kM / 60);

            let durasiKotor = Math.max(0, wKeluar - wMasuk);

            // Deteksi Hari Sabtu dari date filter aktif
            const dateFilterInput = document.getElementById('date-filter');
            let isSabtu = false;
            if (dateFilterInput && dateFilterInput.value) {
                const day = new Date(dateFilterInput.value + 'T00:00:00').getDay();
                isSabtu = (day === 6);
            }

            // Potongan 1 Jam Istirahat Siang (12:00 - 13:00)
            let potonganIstirahat = 0;
            if (!isSabtu) {
                if (wMasuk <= 12 && wKeluar >= 13) {
                    potonganIstirahat = 1;
                }
            } else {
                if (wMasuk <= 12 && wKeluar > 13) {
                    potonganIstirahat = 1;
                }
            }

            let durasiAktual = Math.max(0, durasiKotor - potonganIstirahat);
            let standarJam = isSabtu ? 5 : 7;
            let lembur = durasiAktual > standarJam ? durasiAktual - standarJam : 0;

            if (lembur > 0) {
                lembur = Math.round(lembur * 10) / 10;
                lemburEl.innerHTML = `<span class="font-semibold text-carbon-black-800">${lembur} Jam</span>`;
            } else {
                lemburEl.innerHTML = '<span class="text-carbon-black-300 font-medium">-</span>';
            }

            // Batas Toleransi Keterlambatan 15 Menit dari Jam 08:00
            const selisihMenit = (mH * 60 + mM) - 480;
            if (badgeEl) {
                if (selisihMenit > 15) {
                    badgeEl.innerHTML = `<span class="inline-block text-[10px] font-black uppercase tracking-wider text-amber-700 bg-amber-100 border border-amber-300 px-1.5 py-0.5 rounded mt-0.5" title="Terlambat melebihi batas toleransi 15 menit">Terlambat ${selisihMenit}m</span>`;
                } else {
                    badgeEl.innerHTML = '';
                }
            }
        } else {
            lemburEl.innerHTML = '<span class="text-carbon-black-300 font-medium">-</span>';
            if (badgeEl) badgeEl.innerHTML = '';
        }

        if (autoSave) {
            saveInlinePresensi(id, { jam_masuk: masukVal, jam_keluar: keluarVal });
            showInlineToast('Jam kerja berhasil diperbarui');
        }
    }

    // Auto-calculate on initial page load for all items in case of dynamically populated data
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type="time"][id^="masuk_"]').forEach(el => {
            const id = el.id.split('_')[1];
            calculateLembur(id);
        });
    });

    // Detail Modal Control
    const detailModal = document.getElementById('modal-detail');
    const detailModalInner = detailModal.querySelector('div');

    window.openDetailModal = function (data) {
        document.getElementById('detail-nama').textContent = data.nama || '-';
        if (data.summary) {
            document.getElementById('detail-hadir').textContent = data.summary.H || '0';
            document.getElementById('detail-izin').textContent = data.summary.I || '0';
            document.getElementById('detail-sakit').textContent = data.summary.S || '0';
            document.getElementById('detail-cuti').textContent = data.summary.C || '0';
            document.getElementById('detail-alpha').textContent = data.summary.A || '0';
        } else {
            document.getElementById('detail-hadir').textContent = '0';
            document.getElementById('detail-izin').textContent = '0';
            document.getElementById('detail-sakit').textContent = '0';
            document.getElementById('detail-cuti').textContent = '0';
            document.getElementById('detail-alpha').textContent = '0';
        }

        // Render Audit Trail List
        const auditContainer = document.getElementById('detail-audit-container');
        if (auditContainer) {
            const logs = data.audit_trail || [];
            if (logs.length > 0) {
                auditContainer.innerHTML = logs.map(item => `
                    <div class="bg-white p-3 rounded-lg border border-carbon-black-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-2 text-xs">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded font-black text-[10px] uppercase tracking-wider ${item.tipe && item.tipe.includes('Keterlambatan') ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-rose-100 text-rose-800 border border-rose-200'}">
                                    ${item.tipe || 'Deviasi'}
                                </span>
                                <span class="font-bold text-carbon-black-900">${item.perubahan || '-'}</span>
                            </div>
                            <p class="text-carbon-black-600 font-medium italic">
                                Alasan: "${item.alasan || '-'}"
                            </p>
                        </div>
                        <div class="text-right text-[11px] text-carbon-black-400 shrink-0">
                            <p class="font-bold text-carbon-black-700">${item.timestamp || item.tanggal || '-'}</p>
                            <p>Oleh: ${item.user || 'Admin'}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                auditContainer.innerHTML = `
                    <div class="py-6 text-center flex flex-col items-center justify-center">
                        <x-tabler-circle-check class="w-7 h-7 text-emerald-500 mb-1.5" />
                        <span class="text-sm font-extrabold text-carbon-black-800">Tidak ada deviasi / pengecualian tercatat</span>
                        <span class="text-xs text-carbon-black-500 mt-0.5">Karyawan tercatat hadir normal sesuai jam kerja standar</span>
                    </div>
                `;
            }
        }

        let calendarEl = document.getElementById('employee-calendar');
        if (window.empCalendar) {
            window.empCalendar.destroy();
            window.empCalendar = null;
        }
        calendarEl.innerHTML = '<div class="rounded-lg bg-carbon-black-50 px-4 py-8 text-center text-sm font-bold text-carbon-black-400">Tidak ada data presensi aktif.</div>';

        if (data.history && data.history.length > 0) {
            const colorMap = {
                'Hadir': '#059669', // emerald
                'Izin': '#f59e0b',  // amber
                'Sakit': '#3b82f6', // blue
                'Cuti': '#a855f7',  // purple
                'Alpha': '#e11d48'  // rose
            };

            let events = data.history.map(item => ({
                title: item.status.toUpperCase(),
                start: item.date,
                color: colorMap[item.status] || '#1a1a1a',
                allDay: true,
                extendedProps: {
                    keterangan: item.keterangan || ''
                }
            }));

            calendarEl.innerHTML = '';

            window.empCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: data.history[0].date, // Based on earliest record
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today'
                },
                events: events,
                contentHeight: "auto",
                firstDay: 1, // Start on Monday
                eventDidMount: function (info) {
                    if (info.event.extendedProps.keterangan) {
                        info.el.setAttribute('title', info.event.title + ' - ' + info.event.extendedProps.keterangan);
                    } else {
                        info.el.setAttribute('title', info.event.title);
                    }
                    if (info.event.extendedProps.keterangan) {
                        info.el.style.cursor = 'help';
                    }
                }
            });
        }

        detailModal.classList.remove('hidden');
        detailModal.classList.add('flex');
        setTimeout(() => {
            detailModal.classList.remove('opacity-0');
            detailModalInner.classList.remove('scale-95');
            detailModalInner.classList.add('scale-100');

            // Render calendar after modal transition
            if (window.empCalendar) {
                setTimeout(() => {
                    window.empCalendar.render();
                    window.empCalendar.updateSize();
                }, 100);
            }
        }, 10);
    }

    window.closeDetailModal = function () {
        detailModal.classList.add('opacity-0');
        detailModalInner.classList.remove('scale-100');
        detailModalInner.classList.add('scale-95');
        setTimeout(() => {
            detailModal.classList.add('hidden');
            detailModal.classList.remove('flex');
        }, 300);
    }

    // Koreksi Modal Control & Autocomplete
    const koreksiModal = document.getElementById('modal-koreksi');
    const koreksiModalCard = document.getElementById('modal-koreksi-card') || (koreksiModal ? koreksiModal.querySelector('div') : null);

    @php
        $presensiDatasetJs = collect($fullDataset ?? [])->map(function ($p) {
            return [
                'nama' => $p['nama'] ?? '',
                'tanggal' => $p['tanggal'] ?? '',
                'kehadiran' => $p['kehadiran'] ?? '',
                'jam_masuk' => $p['jam_masuk'] ?? '',
                'jam_keluar' => $p['jam_keluar'] ?? ''
            ];
        })->values();
    @endphp
    const activeEmployees = @json($activeKaryawans ?? []);
    const presensiDataset = @json($presensiDatasetJs);

    window.openKoreksiModal = function() {
        if(!koreksiModal) return;
        const listEl = document.getElementById('koreksi-autocomplete-list');
        if (listEl) {
            listEl.classList.add('hidden');
            listEl.innerHTML = '';
        }
        handleKoreksiDateChange();
        koreksiModal.classList.remove('hidden');
        koreksiModal.classList.add('flex');
        setTimeout(() => {
            koreksiModal.classList.remove('opacity-0');
            if(koreksiModalCard) {
                koreksiModalCard.classList.remove('scale-95');
                koreksiModalCard.classList.add('scale-100');
            }
            const searchInput = document.getElementById('koreksi-nama-search');
            if (searchInput) searchInput.focus();
        }, 10);
    };

    window.closeKoreksiModal = function() {
        if(!koreksiModal) return;
        koreksiModal.classList.add('opacity-0');
        if(koreksiModalCard) {
            koreksiModalCard.classList.remove('scale-100');
            koreksiModalCard.classList.add('scale-95');
        }
        setTimeout(() => {
            koreksiModal.classList.add('hidden');
            koreksiModal.classList.remove('flex');
            clearKoreksiNama();
            const alasanInput = document.getElementById('koreksi-alasan');
            if (alasanInput) alasanInput.value = '';
        }, 300);
    };

     window.handleKoreksiSearch = function(query) {
        const listEl = document.getElementById('koreksi-autocomplete-list');
        const clearBtn = document.getElementById('koreksi-clear-nama');
        if (!listEl) return;

        const q = (query || '').toLowerCase().trim();
        if (clearBtn) {
            clearBtn.classList.toggle('hidden', q === '');
        }

        // Jangan buka dropdown jika belum ada karakter yang diketik
        if (q === '') {
            listEl.classList.add('hidden');
            listEl.innerHTML = '';
            return;
        }

        const filtered = activeEmployees.filter(emp => emp.nama.toLowerCase().includes(q));

        if (filtered.length === 0) {
            listEl.innerHTML = `
                <div class="px-4 py-3 text-xs text-carbon-black-400 text-center font-medium">
                    Tidak ada karyawan aktif yang cocok
                </div>
            `;
            listEl.classList.remove('hidden');
            return;
        }

        listEl.innerHTML = filtered.map(emp => `
            <div onclick="selectKoreksiKaryawan('${emp.nama.replace(/'/g, "\\'")}', '${(emp.bagian || 'Produksi').replace(/'/g, "\\'")}')" class="px-3.5 py-2.5 hover:bg-amber-50/70 cursor-pointer flex items-center justify-between border-b border-carbon-black-100 last:border-0 transition-colors">
                <div class="flex flex-col text-left">
                    <span class="text-xs font-bold text-carbon-black-900">${emp.nama}</span>
                    <span class="text-[10px] text-carbon-black-500">${emp.bagian || 'Produksi'}</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wider text-hunter-green-700 bg-hunter-green-50 px-2 py-0.5 rounded border border-hunter-green-200">
                    Aktif
                </span>
            </div>
        `).join('');

        listEl.classList.remove('hidden');
    };

     window.selectKoreksiKaryawan = function(nama, bagian) {
        const inputEl = document.getElementById('koreksi-nama-search');
        const hiddenEl = document.getElementById('koreksi-nama-val');
        const listEl = document.getElementById('koreksi-autocomplete-list');
        const clearBtn = document.getElementById('koreksi-clear-nama');

        if (inputEl) inputEl.value = nama;
        if (hiddenEl) hiddenEl.value = nama;
        if (listEl) listEl.classList.add('hidden');
        if (clearBtn) clearBtn.classList.remove('hidden');

        checkExistingPresensi();
    };

     window.clearKoreksiNama = function() {
        const inputEl = document.getElementById('koreksi-nama-search');
        const hiddenEl = document.getElementById('koreksi-nama-val');
        const listEl = document.getElementById('koreksi-autocomplete-list');
        const clearBtn = document.getElementById('koreksi-clear-nama');

        if (inputEl) {
            inputEl.value = '';
            inputEl.focus();
        }
        if (hiddenEl) hiddenEl.value = '';
        if (listEl) listEl.classList.add('hidden');
        if (clearBtn) clearBtn.classList.add('hidden');

        checkExistingPresensi();
    };

    // Close autocomplete when clicking outside 
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('koreksi-karyawan-wrapper');
        const listEl = document.getElementById('koreksi-autocomplete-list');
        if (wrapper && listEl && !wrapper.contains(e.target)) {
            listEl.classList.add('hidden');
        }
    });

     window.checkExistingPresensi = function() {
        const nama = (document.getElementById('koreksi-nama-val') || {}).value;
        const tanggal = (document.getElementById('koreksi-tanggal') || {}).value;
        const wrapper = document.getElementById('koreksi-preview-wrapper');
        const textEl = document.getElementById('koreksi-preview-text');
        const badgeEl = document.getElementById('koreksi-preview-badge');

        if (!nama || !tanggal || !wrapper) {
            if (wrapper) wrapper.classList.add('hidden');
            return;
        }

        const record = presensiDataset.find(p => p.nama.toLowerCase() === nama.toLowerCase() && p.tanggal === tanggal);

        wrapper.classList.remove('hidden');
        if (record) {
            const timeDesc = (record.jam_masuk && record.jam_masuk !== '00:00') ? ` (${record.jam_masuk} - ${record.jam_keluar})` : '';
            textEl.textContent = `${record.kehadiran}${timeDesc}`;
            badgeEl.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border shrink-0 bg-emerald-100 text-emerald-800 border-emerald-300';
            badgeEl.textContent = 'Data Tercatat';
        } else {
            textEl.textContent = 'Belum ada catatan presensi pada tanggal ini (Dicatat sebagai susulan)';
            badgeEl.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border shrink-0 bg-amber-100 text-amber-800 border-amber-300';
            badgeEl.textContent = 'Susulan Baru';
        }
    };

     window.handleKoreksiStatusChange = function(status) {
        const masukGroup = document.getElementById('koreksi-jam-masuk-group');
        const keluarGroup = document.getElementById('koreksi-jam-keluar-group');
        const noticeGroup = document.getElementById('koreksi-nonhadir-notice');
        const masukInput = document.getElementById('koreksi-jam-masuk');
        const keluarInput = document.getElementById('koreksi-jam-keluar');

        if (status === 'Hadir') {
            if (masukGroup) masukGroup.classList.remove('opacity-50');
            if (keluarGroup) keluarGroup.classList.remove('opacity-50');
            if (noticeGroup) noticeGroup.classList.add('hidden');
            if (masukInput) {
                masukInput.disabled = false;
                if (!masukInput.value || masukInput.value === '00:00') masukInput.value = '08:00';
            }
            if (keluarInput) {
                keluarInput.disabled = false;
                if (!keluarInput.value || keluarInput.value === '00:00') {
                    const tglInput = document.getElementById('koreksi-tanggal');
                    const isSabtu = tglInput && tglInput.value ? (new Date(tglInput.value + 'T00:00:00').getDay() === 6) : false;
                    keluarInput.value = isSabtu ? '13:00' : '16:00';
                }
            }
        } else {
            if (masukGroup) masukGroup.classList.add('opacity-50');
            if (keluarGroup) keluarGroup.classList.add('opacity-50');
            if (noticeGroup) noticeGroup.classList.remove('hidden');
            if (masukInput) {
                masukInput.value = '00:00';
                masukInput.disabled = true;
            }
            if (keluarInput) {
                keluarInput.value = '00:00';
                keluarInput.disabled = true;
            }
        }
    };

    window.handleKoreksiDateChange = function() {
        const tglInput = document.getElementById('koreksi-tanggal');
        const keluarInput = document.getElementById('koreksi-jam-keluar');
        const statusSelect = document.getElementById('koreksi-status');
        
        if (tglInput && tglInput.value && keluarInput && (!statusSelect || statusSelect.value === 'Hadir')) {
            const day = new Date(tglInput.value + 'T00:00:00').getDay();
            if (day === 6) {
                if (keluarInput.value === '16:00') {
                    keluarInput.value = '13:00';
                }
            } else {
                if (keluarInput.value === '13:00') {
                    keluarInput.value = '16:00';
                }
            }
        }
        checkExistingPresensi();
    };

     window.validateKoreksiForm = function() {
        const nama = (document.getElementById('koreksi-nama-val') || {}).value;
        if (!nama) {
            alert('Silakan pilih nama karyawan dari daftar rekomendasi.');
            document.getElementById('koreksi-nama-search').focus();
            return false;
        }
        return true;
};
    
    // Fitur pencarian otomatis per huruf
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let searchTimeout;
         searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 600); // Jeda 0.6 detik sebelum submit otomatis
        });

        // Letakkan kursor di akhir teks saat halaman dimuat ulang setelah pencarian
        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
}
    
    // Prompt Keterangan Control
    const promptModal = document.getElementById('modal-prompt-keterangan');

    const statusTitleMap = {
        'Izin': 'Keterangan Izin Karyawan',
        'Sakit': 'Keterangan Sakit Karyawan',
        'Cuti': 'Keterangan Cuti Karyawan',
        'Alpha': 'Keterangan Alpha Karyawan'
    };

    const statusBadgeMap = {
        'Izin': { class: 'bg-amber-100 text-amber-800 border-amber-300', text: 'IZIN (I)' },
        'Sakit': { class: 'bg-blue-100 text-blue-800 border-blue-300', text: 'SAKIT (S)' },
        'Cuti': { class: 'bg-purple-100 text-purple-800 border-purple-300', text: 'CUTI (C)' },
        'Alpha': { class: 'bg-rose-100 text-rose-800 border-rose-300', text: 'ALPHA (A)' }
    };

     window.handlePromptFileSelect = function(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const emptyEl = document.getElementById('prompt-file-empty');
        const selectedEl = document.getElementById('prompt-file-selected');
        const nameEl = document.getElementById('prompt-file-name');
        const sizeEl = document.getElementById('prompt-file-size');

        if (nameEl) nameEl.textContent = file.name;
        if (sizeEl) {
            const kb = Math.round(file.size / 1024);
            sizeEl.textContent = `(${kb > 1024 ? (kb / 1024).toFixed(1) + ' MB' : kb + ' KB'})`;
        }
        if (emptyEl) emptyEl.classList.add('hidden');
        if (selectedEl) {
            selectedEl.classList.remove('hidden');
            selectedEl.classList.add('flex');
        }
    };

     window.clearPromptFile = function() {
        const fileInput = document.getElementById('prompt-file-input');
        if (fileInput) fileInput.value = '';
        const emptyEl = document.getElementById('prompt-file-empty');
        const selectedEl = document.getElementById('prompt-file-selected');
        if (emptyEl) emptyEl.classList.remove('hidden');
        if (selectedEl) {
            selectedEl.classList.add('hidden');
            selectedEl.classList.remove('flex');
        }
    };

     window.handleStatusChange = function(radioEl, empId, empName, status) {
        const masukInput = document.getElementById(`masuk_${empId}`);
        const keluarInput = document.getElementById(`keluar_${empId}`);
        const lemburEl = document.getElementById(`jam_lembur_${empId}`);
        const badgeEl = document.getElementById(`badge_terlambat_${empId}`);
        const hiddenInput = document.getElementById(`keterangan_harian_${empId}`);

        if (status === 'Hadir') {
            if (masukInput && (!masukInput.value || masukInput.value === '00:00')) masukInput.value = '08:00';
            if (keluarInput && (!keluarInput.value || keluarInput.value === '00:00')) keluarInput.value = '16:00';
            if (badgeEl) badgeEl.innerHTML = '';
calculateLembur(empId);
            
            if (hiddenInput) hiddenInput.value = '';

            saveInlinePresensi(empId, {
                kehadiran: 'Hadir',
                jam_masuk: masukInput ? masukInput.value : '08:00',
                jam_keluar: keluarInput ? keluarInput.value : '16:00',
                keterangan: ''
            });
            showInlineToast(`Status ${empName} diubah ke Hadir`);
            return;
}
        
        // Status non-hadir (Izin, Sakit, Cuti, Alpha)
        if (masukInput) masukInput.value = '00:00';
        if (keluarInput) keluarInput.value = '00:00';
        if (lemburEl) lemburEl.innerHTML = '<span class="text-carbon-black-300 font-medium">-</span>';
        if (badgeEl) badgeEl.innerHTML = '';

        const modalTitle = document.getElementById('prompt-modal-title');
        if (modalTitle) modalTitle.textContent = statusTitleMap[status] || `Keterangan ${status} Karyawan`;

        const statusBadge = document.getElementById('prompt-status-badge');
        const badgeConfig = statusBadgeMap[status] || statusBadgeMap['Izin'];
        if (statusBadge) {
            statusBadge.className = `px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider border shadow-sm transition-all duration-200 ${badgeConfig.class}`;
            statusBadge.textContent = badgeConfig.text;
        }

        const namaLabel = document.getElementById('prompt-nama-label');
        if (namaLabel) namaLabel.textContent = empName;

        clearPromptFile();

        document.getElementById('prompt-active-id').value = empId;
document.getElementById('prompt-keterangan-input').value = hiddenInput ? hiddenInput.value : '';
        
        if (promptModal) {
            const card = document.getElementById('prompt-modal-card') || promptModal.querySelector('div');
            promptModal.classList.remove('hidden');
            promptModal.classList.add('flex');
            setTimeout(() => {
                promptModal.classList.remove('opacity-0');
                if (card) {
                    card.classList.remove('scale-95');
                    card.classList.add('scale-100');
                }
                document.getElementById('prompt-keterangan-input').focus();
            }, 10);
        }
    };

     window.closePromptModal = function() {
        if (!promptModal) return;
        const empId = document.getElementById('prompt-active-id').value;
        if (empId) {
            const checkedRadio = document.querySelector(`input[name="kehadiran_${empId}"]:checked`);
            if (checkedRadio) {
                const status = checkedRadio.value;
                const hiddenInput = document.getElementById(`keterangan_harian_${empId}`);
                saveInlinePresensi(empId, {
                    kehadiran: status,
                    jam_masuk: status === 'Hadir' ? '08:00' : '00:00',
                    jam_keluar: status === 'Hadir' ? '16:00' : '00:00',
                    keterangan: hiddenInput ? hiddenInput.value : ''
                });
                showInlineToast(`Status ${status} tersimpan`);
            }
        }

        const card = document.getElementById('prompt-modal-card') || promptModal.querySelector('div');
        promptModal.classList.add('opacity-0');
        if (card) {
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
        setTimeout(() => {
            promptModal.classList.add('hidden');
            promptModal.classList.remove('flex');
            clearPromptFile();
        }, 300);
    };

     window.savePromptKeterangan = function() {
        const empId = document.getElementById('prompt-active-id').value;
let ketVal = document.getElementById('prompt-keterangan-input').value.trim();
        
        const fileInput = document.getElementById('prompt-file-input');
        if (fileInput && fileInput.files && fileInput.files[0]) {
            const fileName = fileInput.files[0].name;
            if (ketVal) {
                ketVal += ` [Lampiran: ${fileName}]`;
            } else {
                ketVal = `[Lampiran: ${fileName}]`;
            }
        }

        const hiddenInput = document.getElementById(`keterangan_harian_${empId}`);
        if (hiddenInput) {
            hiddenInput.value = ketVal;
        }

        const checkedRadio = document.querySelector(`input[name="kehadiran_${empId}"]:checked`);
        const status = checkedRadio ? checkedRadio.value : 'Hadir';

        saveInlinePresensi(empId, {
            kehadiran: status,
            jam_masuk: '00:00',
            jam_keluar: '00:00',
            keterangan: ketVal
        });
showInlineToast(`Status ${status} disimpan ke Audit Trail`);
        
        const card = document.getElementById('prompt-modal-card') || promptModal.querySelector('div');
        promptModal.classList.add('opacity-0');
        if (card) {
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
        setTimeout(() => {
            promptModal.classList.add('hidden');
            promptModal.classList.remove('flex');
            document.getElementById('prompt-active-id').value = '';
            clearPromptFile();
        }, 300);
    };
</script>
@endpush
@endsection
