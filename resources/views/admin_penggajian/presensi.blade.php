@extends('layouts.app')

@section('title', 'Data Presensi')

@section('content')
<div class="flex flex-col space-y-8 overflow-hidden">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            DATA PRESENSI
        </h1>
        
        <div class="flex flex-col xl:flex-row xl:justify-between items-start xl:items-end gap-6">
            <form action="{{ route('presensi.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <!-- Search Name -->
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." class="w-72 px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-base font-medium">
                    <button type="submit" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
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
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 hover:border-carbon-black-300 transition-colors bg-white items-center">
                    <div class="pl-4 text-carbon-black-400">
                        <x-tabler-calendar class="w-5 h-5" />
                    </div>
                    <input type="date" name="date" id="date-filter" value="{{ request('date') }}" title="Pilih Tanggal Presensi" class="w-40 px-3 py-2 font-bold text-carbon-black-700 focus:outline-none bg-transparent appearance-none" onchange="this.form.submit()">
                </div>
            </form>

            <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5 ml-auto xl:ml-0">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative flex-1 min-h-0">
        <div class="max-h-[calc(125vh-24rem)] overflow-y-auto scrollbar-invisible">
        <table class="w-full border-collapse">
            <thead class="sticky top-0 z-10 bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
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
                    <tr class="{{ $isInactive ? 'bg-carbon-black-50/70 opacity-75' : 'hover:bg-hunter-green-50' }} transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">{{ (($page - 1) * 10) + $index + 1 }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">
                            <div class="flex flex-col items-start gap-1">
                                <span>{{ $p['nama'] }}</span>
                                @if($isInactive)
                                    <span class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-widest text-rose-600">Inaktif</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ date('d M Y', strtotime($p['tanggal'])) }}</td>
                        <td class="px-6 py-5 text-center">
                            <input type="time" id="masuk_{{ $p['id'] }}" name="jam_masuk_{{ $p['id'] }}" value="{{ $p['jam_masuk'] }}" onchange="calculateLembur('{{ $p['id'] }}')" title="{{ $isInactive ? 'Karyawan inaktif' : 'Ubah Jam Masuk' }}" class="w-[105px] text-center text-base font-semibold text-carbon-black-800 bg-transparent border-0 focus:ring-1 focus:ring-hunter-green-400 focus:outline-none {{ $isInactive ? 'cursor-not-allowed text-carbon-black-400' : 'cursor-pointer hover:bg-carbon-black-50' }} px-1 py-1 rounded transition-colors" {{ $isInactive ? 'disabled' : '' }}>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <input type="time" id="keluar_{{ $p['id'] }}" name="jam_keluar_{{ $p['id'] }}" value="{{ $p['jam_keluar'] }}" onchange="calculateLembur('{{ $p['id'] }}')" title="{{ $isInactive ? 'Karyawan inaktif' : 'Ubah Jam Keluar' }}" class="w-[105px] text-center text-base font-semibold text-carbon-black-800 bg-transparent border-0 focus:ring-1 focus:ring-hunter-green-400 focus:outline-none {{ $isInactive ? 'cursor-not-allowed text-carbon-black-400' : 'cursor-pointer hover:bg-carbon-black-50' }} px-1 py-1 rounded transition-colors" {{ $isInactive ? 'disabled' : '' }}>
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
                                    <label class="flex items-center space-x-1 {{ $isInactive ? 'cursor-not-allowed' : 'cursor-pointer group/radio' }}" title="{{ $isInactive ? 'Karyawan inaktif' : $val }}">
                                        <input type="radio" name="kehadiran_{{ $p['id'] }}" value="{{ $val }}" 
                                            class="w-4 h-4 {{ $opt['accent'] }} {{ $isInactive ? 'cursor-not-allowed' : 'cursor-pointer' }}" 
                                            {{ $currentStatus === $val ? 'checked' : '' }}
                                            {{ $isInactive ? 'disabled' : '' }}>
                                        <span class="text-xs font-black text-carbon-black-500 {{ $isInactive ? '' : 'group-hover/radio:text-carbon-black-900' }} transition-colors">{{ $opt['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center" id="jam_lembur_{{ $p['id'] }}">
                            @if(($p['jam_lembur'] ?? 0) > 0)
                                <span class="font-semibold text-carbon-black-800">{{ $p['jam_lembur'] }} Jam</span>
                            @else
                                <span class="text-carbon-black-300 font-medium">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick='openDetailModal(@json($p))' class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat">
                                    <x-tabler-eye class="w-4 h-4" />
                                    <span>Detail</span>
                                </button>
                                <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                    <x-tabler-pencil class="w-4 h-4" />
                                    <span>Ubah</span>
                                </button>
                                <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
                                    <x-tabler-trash class="w-4 h-4" />
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <!-- Pagination/Footer -->
        <div class="bg-carbon-black-50 border-t border-carbon-black-100 px-6 py-4 flex flex-col xl:flex-row items-center justify-between gap-4">
            <!-- Kehadiran Legend -->
            <div class="flex items-center space-x-4 text-sm font-semibold text-carbon-black-600 shrink-0">
                <span class="font-black text-carbon-black-800 uppercase tracking-widest pl-2">Keterangan :</span>
                <div class="flex items-center space-x-4">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background-color: #059669"></span>H = Hadir</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background-color: #f59e0b"></span>I = Izin</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background-color: #3b82f6"></span>S = Sakit</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background-color: #a855f7"></span>C = Cuti</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background-color: #e11d48"></span>A = Alpha</span>
                </div>
            </div>

            <nav role="navigation" aria-label="pagination" class="flex w-full justify-center xl:w-auto xl:justify-end">
                <ul class="flex flex-row items-center gap-1">
                    <li>
                        <a href="{{ $page > 1 ? request()->fullUrlWithQuery(['page' => $page - 1]) : '#' }}" class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 {{ $page <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                            <x-tabler-chevron-left class="h-4 w-4" />
                            <span>Sebelumnya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-md border text-sm font-medium shadow-sm transition-all {{ $page == 1 ? 'bg-hunter-green-600 text-white shadow-hunter-green-200 border-hunter-green-600' : 'border-carbon-black-200 bg-white text-carbon-black-700 hover:bg-carbon-black-50' }}">1</a>
                    </li>
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['page' => 2]) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-md border text-sm font-medium shadow-sm transition-all {{ $page == 2 ? 'bg-hunter-green-600 text-white shadow-hunter-green-200 border-hunter-green-600' : 'border-carbon-black-200 bg-white text-carbon-black-700 hover:bg-carbon-black-50' }}">2</a>
                    </li>
                    <li>
                        <span aria-hidden="true" class="flex h-9 w-9 items-center justify-center">
                            <x-tabler-dots class="h-4 w-4 text-carbon-black-400" />
                        </span>
                    </li>
                    <li>
                        <a href="{{ $page < 2 ? request()->fullUrlWithQuery(['page' => $page + 1]) : '#' }}" class="inline-flex items-center justify-center rounded-md border border-carbon-black-200 bg-white px-3 py-2 text-sm font-medium text-carbon-black-700 shadow-sm hover:bg-carbon-black-50 transition-all space-x-2 {{ $page >= 2 ? 'opacity-50 pointer-events-none' : '' }}">
                            <span>Berikutnya</span>
                            <x-tabler-chevron-right class="h-4 w-4" />
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Detail Presensi -->
<div id="modal-detail" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Rekapitulasi Kehadiran</h3>
            <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeDetailModal()">
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
                        <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-hunter-green-50 text-hunter-green-700 uppercase tracking-widest border border-hunter-green-200">
                            Akumulasi Bulan Ini
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #059669;">Hadir (H)</span>
                        <span id="detail-hadir" class="text-lg font-black" style="color: #047857;">0</span>
                    </div>
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #d97706;">Izin (I)</span>
                        <span id="detail-izin" class="text-lg font-black text-carbon-black-900">0</span>
                    </div>
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #2563eb;">Sakit (S)</span>
                        <span id="detail-sakit" class="text-lg font-black text-carbon-black-900">0</span>
                    </div>
                    <div class="rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm" style="background-color: #faf5ff; border: 1px solid #e9d5ff;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #9333ea;">Cuti (C)</span>
                        <span id="detail-cuti" class="text-lg font-black text-carbon-black-900">0</span>
                    </div>
                    <div class="col-span-2 rounded-lg py-1.5 px-2 flex flex-col items-center justify-center text-center shadow-sm" style="background-color: #fff1f2; border: 1px solid #fecdd3;">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-carbon-black-900">Alpha (A)</span>
                        <span id="detail-alpha" class="text-lg font-black" style="color: #e11d48;">0</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Calendar -->
            <div class="w-full lg:w-2/3 border border-carbon-black-100 rounded-lg overflow-hidden bg-white shadow-sm">
                <style>
                    /* Customizing FullCalendar Minimalist style */
                    .fc { font-family: inherit; }
                    .fc .fc-toolbar-title { font-size: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #1a1a1a; }
                    .fc .fc-button-primary { background-color: #2F4F4F; border-color: #2F4F4F; text-transform: uppercase; font-size: 0.7rem; font-weight: bold; padding: 0.3rem 0.6rem; }
                    .fc .fc-button-primary:hover { background-color: #1a2e2e; border-color: #1a2e2e; }
                    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #0d1717; border-color: #0d1717; }
                    .fc-theme-standard th { background-color: #f8fafc; padding: 6px 0; font-size: 0.75rem; text-transform: uppercase; color: #64748b; border-color: #e2e8f0; }
                    .fc-theme-standard td, .fc-theme-standard th { border-color: #e2e8f0; }
                    .fc .fc-daygrid-day-number { font-size: 0.8rem; font-weight: bold; color: #334155; padding: 4px 8px; text-decoration: none; }
                    .fc .fc-daygrid-day.fc-day-today { background-color: #f0fdf4; }
                    .fc-event { border: none !important; border-radius: 4px !important; padding: 2px !important; font-weight: bold !important; font-size: 0.65rem !important; text-align: center !important; margin: 1px 2px !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05); cursor: default; }
                    .fc .fc-daygrid-day-frame { min-height: 60px; }
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
        
        <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex justify-end rounded-b-xl">
            <button type="button" class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 active:scale-95 transition-all" onclick="closeDetailModal()">Kembali</button>
        </div>
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    function calculateLembur(id) {
        const masukInput = document.getElementById(`masuk_${id}`);
        const keluarInput = document.getElementById(`keluar_${id}`);
        const lemburEl = document.getElementById(`jam_lembur_${id}`);
        
        if (!masukInput || !keluarInput || !lemburEl) return;
        
        const masukVal = masukInput.value;
        const keluarVal = keluarInput.value;
        
        if (masukVal && keluarVal && masukVal !== '' && keluarVal !== '') {
            const [mH, mM] = masukVal.split(':').map(Number);
            const [kH, kM] = keluarVal.split(':').map(Number);
            
            let wMasuk = mH + (mM / 60);
            let wKeluar = kH + (kM / 60);
            
            // In a real frontend app, we would look up the specific date or employee role here.
            // Since this is a UI mock updating visually, we'll simulate the update without strictly parsing the date.
            // If the user presses "Simpan" (submit), the server recalculates correctly based on the exact date.
            
            // Visual mockup fallback calculation (simulating standard 7 hours)
            let totalJam = wKeluar - wMasuk;
            let lembur = totalJam > 7 ? totalJam - 7 : 0;
            
            if (lembur > 0) {
                lembur = Math.round(lembur * 10) / 10;
                lemburEl.innerHTML = `<span class="font-semibold text-carbon-black-800">${lembur} Jam</span>`;
            } else {
                lemburEl.innerHTML = '<span class="text-carbon-black-300 font-medium">-</span>';
            }
        } else {
            lemburEl.innerHTML = '<span class="text-carbon-black-300 font-medium">-</span>';
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
    
    window.openDetailModal = function(data) {
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
        
        let calendarEl = document.getElementById('employee-calendar');
        if(window.empCalendar) {
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
                allDay: true
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
