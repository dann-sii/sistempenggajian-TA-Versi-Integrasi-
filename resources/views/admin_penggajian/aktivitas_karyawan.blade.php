@extends('layouts.app')

@section('title', 'Aktivitas Karyawan')

@section('content')
    @php
        $statusClasses = [
            'Memenuhi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Lebih' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Normal' => 'bg-blue-50 text-blue-700 border-blue-200',
            'Kurang' => 'bg-rose-50 text-rose-700 border-rose-200',
        ];
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1
                class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                REKAP AKTIVITAS KARYAWAN
            </h1>

            <form method="GET" action="{{ route('aktivitas_karyawan.index') }}" class="flex flex-wrap items-center gap-3">
                @if(isset($riwayatSearch) && $riwayatSearch !== '')
                    <input type="hidden" name="riwayat_search" value="{{ $riwayatSearch }}">
                @endif
                @if(isset($riwayatBagian) && $riwayatBagian !== '')
                    <input type="hidden" name="riwayat_bagian" value="{{ $riwayatBagian }}">
                @endif
                <div class="relative">
                    <input type="date" name="tanggal" value="{{ $selectedTanggal }}"
                        class="rounded-lg border border-carbon-black-200 bg-white px-3 py-2 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                </div>

                <div class="relative">
                    <select name="bagian"
                        class="appearance-none rounded-lg border border-carbon-black-200 bg-white px-3 py-2 pr-8 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20">
                        <option value="Produksi" {{ $selectedBagian === 'Produksi' ? 'selected' : '' }}>Produksi</option>
                        <option value="Peternakan" {{ $selectedBagian === 'Peternakan' ? 'selected' : '' }}>Peternakan</option>
                        <option value="Packing" {{ $selectedBagian === 'Packing' ? 'selected' : '' }}>Packing</option>
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex h-[38px] items-center justify-center gap-2 rounded-lg bg-black-forest-700 px-4 text-sm font-bold uppercase tracking-widest text-white shadow-sm transition-all hover:bg-black-forest-800 active:scale-95">
                    <x-tabler-filter class="h-4 w-4" />
                    Tampilkan
                </button>
            </form>
        </div>

        @if(session('success'))
            <div
                class="rounded-lg border-l-4 border-emerald-500 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm" id="toast-success">
                {{ session('success') }}
            </div>
            <script>setTimeout(() => { let el = document.getElementById('toast-success'); if(el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.style.display = 'none', 500); } }, 3000);</script>
        @endif

        @if(session('error'))
            <div class="rounded-lg border-l-4 border-rose-500 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm" id="toast-error">
                {{ session('error') }}
            </div>
            <script>setTimeout(() => { let el = document.getElementById('toast-error'); if(el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.style.display = 'none', 500); } }, 3000);</script>
        @endif

        @if($errors->any())
            <div class="rounded-lg border-l-4 border-rose-500 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm" id="toast-validation">
                {{ $errors->first() }}
            </div>
            <script>setTimeout(() => { let el = document.getElementById('toast-validation'); if(el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.style.display = 'none', 500); } }, 3000);</script>
        @endif

        <div
            class="flex flex-wrap items-center justify-between gap-6 rounded-xl border border-carbon-black-200 bg-white px-6 py-4 shadow-sm">
            <div class="flex flex-1 items-center justify-between gap-6 md:justify-start md:gap-12">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-carbon-black-500">Baris Aktivitas</p>
                    <p class="mt-0.5 text-xl font-black text-carbon-black-900">
                        {{ number_format($summary['rows'], 0, ',', '.') }}</p>
                </div>
                <div class="hidden h-8 w-px bg-carbon-black-100 md:block"></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Status Memenuhi</p>
                    <p class="mt-0.5 text-xl font-black text-emerald-700">
                        {{ number_format($summary['lebih'], 0, ',', '.') }}</p>
                </div>
                <div class="hidden h-8 w-px bg-carbon-black-100 md:block"></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-600">Status Kurang</p>
                    <p class="mt-0.5 text-xl font-black text-rose-700">{{ number_format($summary['kurang'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('aktivitas_karyawan.batch_store') }}" id="piket-form">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
            <input type="hidden" name="bagian" value="{{ $selectedBagian }}">

            <section class="overflow-hidden rounded-none border border-carbon-black-200 bg-white shadow-xl">
                <div
                    class="flex items-center justify-between gap-4 border-b border-carbon-black-100 bg-carbon-black-50 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">
                            {{ $selectedBagian }} - {{ date('d M Y', strtotime($selectedTanggal)) }}
                        </h2>
                        <p class="mt-1 text-sm font-semibold text-carbon-black-500">
                            @if($selectedBagian === 'Produksi')
                                Target Produksi: {{ number_format($targets['produksi_tp_gram'], 0, ',', '.') }} gram
                            @elseif($selectedBagian === 'Packing')
                                Target Pengemasan: {{ number_format($targets['packing_gram'], 0, ',', '.') }} gram
                            @else
                                Target Setoran: {{ number_format($targets['peternakan_liter'], 2, ',', '.') }} liter
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-72">
                            <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white h-11">
                                <input type="text" id="fast-search-karyawan" onkeyup="filterKaryawanTable()" placeholder="Cari nama/NIK..."
                                    class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-sm font-semibold" autocomplete="off">
                                <div class="bg-carbon-black-50 px-4 border-l border-carbon-black-200 text-carbon-black-400 flex items-center justify-center">
                                    <x-tabler-search class="w-4 h-4" />
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="inline-flex h-11 items-center gap-2 rounded-xl bg-hunter-green-600 px-6 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-hunter-green-700 active:scale-95">
                            <x-tabler-device-floppy class="h-5 w-5" />
                            Simpan Rekap
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-[1100px] w-full border-collapse">
                        <thead class="bg-carbon-black-50 text-carbon-black-600">
                            <tr class="border-b border-carbon-black-200">
                                <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest w-16">No</th>
                                <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-widest">Karyawan</th>
                                @if($selectedBagian === 'Produksi')
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Susu (Liter)
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Gula (Gram)
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Rasa</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Perasa (Gram)
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Hasil
                                        Produksi (Gram)</th>
                                 @elseif($selectedBagian === 'Packing')
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Sachet 25 Gram</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Plastik 250 Gram</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Plastik 500 Gram</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Plastik 1 Kilogram</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Sachet Rusak</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Plastik 250g Rusak</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Plastik 500g Rusak</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-36">Plastik 1kg Rusak</th>
                                     <th class="px-2 py-4 text-center text-xs font-black uppercase tracking-widest w-40">Total Kemasan (Gram)</th>
                                @else
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Susu Disetor
                                        (Liter)</th>
                                @endif
                                <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-widest">Status
                                    Capaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-carbon-black-100">
                            @forelse($batchKaryawans as $index => $karyawan)
                                @php
                                    $existing = $existingPiketByKaryawan[$karyawan['id']] ?? [];
                                    $status = $existing['status_piket'] ?? 'Kurang';
                                    $statusClass = $statusClasses[$status] ?? 'bg-carbon-black-50 text-carbon-black-600 border-carbon-black-200';
                                    $isActive = ($existing['liter_susu'] ?? 0) > 0 || ($existing['liter_setor'] ?? 0) > 0 || ($existing['tp_gram'] ?? 0) > 0;
                                    $isPastDate = $selectedTanggal < date('Y-m-d');
                                    $isLocked = $isActive;
                                    $inputClass = $isLocked ? 'bg-carbon-black-100 text-carbon-black-500 cursor-not-allowed border-transparent shadow-inner' : 'bg-carbon-black-50 text-carbon-black-800 border-carbon-black-200 focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20';
                                @endphp
                                <tr class="piket-row transition-all duration-200 hover:bg-hunter-green-50/50 {{ $isActive ? 'bg-hunter-green-50/30 shadow-sm border-y border-hunter-green-200' : '' }}"
                                    data-target="{{ $selectedBagian === 'Produksi' ? $targets['produksi_tp_gram'] : ($selectedBagian === 'Packing' ? $targets['packing_gram'] : $targets['peternakan_liter']) }}">
                                    <td
                                        class="bg-carbon-black-50/50 px-4 py-4 text-center text-sm font-black text-hunter-green-700">
                                        {{ $index + 1 }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-black text-carbon-black-900">{{ $karyawan['nama'] }}</p>
                                                <p class="mt-0.5 text-xs font-semibold text-carbon-black-400">NIK: {{ $karyawan['nik'] }}</p>
                                            </div>
                                            @if($isLocked && !$isPastDate)
                                                <button type="button" class="btn-unlock rounded p-1 text-carbon-black-400 hover:bg-hunter-green-50 hover:text-hunter-green-600 transition-colors" title="Edit Data Hari Ini">
                                                    <x-tabler-pencil class="h-4 w-4" />
                                                </button>
                                            @elseif($isLocked && $isPastDate)
                                                <div class="rounded p-1 text-rose-400" title="Terkunci (Beda Hari)">
                                                    <x-tabler-lock class="h-4 w-4" />
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                     @if($selectedBagian === 'Produksi')
                                         <td class="px-3 py-4 text-center">
                                             <input type="number" min="0" step="0.01"
                                                 name="entries[{{ $karyawan['id'] }}][liter_susu]"
                                                 value="{{ $existing['liter_susu'] ?? '' }}" placeholder="Jml Susu"
                                                 class="piket-input piket-susu w-28 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-3 py-4 text-center">
                                             <input type="number" min="0" step="0.01"
                                                 name="entries[{{ $karyawan['id'] }}][gula_gram]"
                                                 value="{{ $existing['gula_gram'] ?? '' }}" placeholder="Jml Gula"
                                                 class="piket-input piket-gula w-28 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-3 py-4 text-center">
                                             <div class="relative inline-block text-left">
                                                 <select name="entries[{{ $karyawan['id'] }}][perasa_jenis]"
                                                     class="piket-input piket-rasa w-36 appearance-none rounded-lg border px-3 py-2 pr-8 text-sm font-semibold focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                                     <option value="">Rasa</option>
                                                     @foreach($perasaOptions as $perasa)
                                                         <option value="{{ $perasa }}" {{ ($existing['perasa_jenis'] ?? '') === $perasa ? 'selected' : '' }}>{{ $perasa }}</option>
                                                     @endforeach
                                                 </select>
                                                 <div
                                                     class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400">
                                                     <x-tabler-chevron-down class="h-4 w-4" />
                                                 </div>
                                             </div>
                                         </td>
                                         <td class="px-3 py-4 text-center">
                                             <input type="number" min="0" step="0.01"
                                                 name="entries[{{ $karyawan['id'] }}][perasa_gram]"
                                                 value="{{ $existing['perasa_gram'] ?? '' }}" placeholder="Jml Perasa"
                                                 class="piket-input piket-perasa w-28 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-3 py-4 text-center">
                                             <input type="number" min="0" step="0.01" name="entries[{{ $karyawan['id'] }}][tp_gram]"
                                                 value="{{ $existing['tp_gram'] ?? '' }}" placeholder="Hasil Kemas (g)"
                                                 class="piket-input piket-actual w-28 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                     @elseif($selectedBagian === 'Packing')
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][sachet_qty]"
                                                 value="{{ $existing['sachet_qty'] ?? '' }}" placeholder="Sachet"
                                                 class="piket-input piket-sachet w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][plastik_250_qty]"
                                                 value="{{ $existing['plastik_250_qty'] ?? '' }}" placeholder="250g"
                                                 class="piket-input piket-p250 w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][plastik_500_qty]"
                                                 value="{{ $existing['plastik_500_qty'] ?? '' }}" placeholder="500g"
                                                 class="piket-input piket-p500 w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][plastik_1000_qty]"
                                                 value="{{ $existing['plastik_1000_qty'] ?? '' }}" placeholder="1kg"
                                                 class="piket-input piket-p1000 w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][kemasan_rusak_sachet_qty]"
                                                 value="{{ $existing['sachet_rusak_qty'] ?? '' }}" placeholder="Sch. Rsk"
                                                 class="piket-input piket-sachet-rusak w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][kemasan_rusak_p250_qty]"
                                                 value="{{ $existing['plastik_250_rusak_qty'] ?? '' }}" placeholder="P.250 Rsk"
                                                 class="piket-input piket-p250-rusak w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][kemasan_rusak_p500_qty]"
                                                 value="{{ $existing['plastik_500_rusak_qty'] ?? '' }}" placeholder="P.500 Rsk"
                                                 class="piket-input piket-p500-rusak w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" min="0"
                                                 name="entries[{{ $karyawan['id'] }}][kemasan_rusak_p1000_qty]"
                                                 value="{{ $existing['plastik_1000_rusak_qty'] ?? '' }}" placeholder="P.1k Rsk"
                                                 class="piket-input piket-p1000-rusak w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                         <td class="px-1 py-4 text-center">
                                             <input type="number" readonly
                                                 name="entries[{{ $karyawan['id'] }}][tp_gram]"
                                                 value="{{ $existing['tp_gram'] ?? '' }}" placeholder="Total (g)"
                                                 class="piket-input piket-actual w-40 bg-carbon-black-100 text-carbon-black-500 rounded-lg border px-3 py-2 text-right text-sm font-bold placeholder-carbon-black-300 focus:outline-none cursor-not-allowed">
                                         </td>
                                     @else
                                         <td class="px-3 py-4 text-center">
                                             <input type="number" min="0" step="0.01"
                                                 name="entries[{{ $karyawan['id'] }}][liter_setor]"
                                                 value="{{ $existing['liter_setor'] ?? '' }}" placeholder="Jml Setor"
                                                 class="piket-input piket-actual w-32 rounded-lg border px-3 py-2 text-right text-sm font-semibold placeholder-carbon-black-300 focus:outline-none transition-colors {{ $inputClass }}" {{ $isLocked ? 'disabled' : '' }}>
                                         </td>
                                     @endif

                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="piket-status inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                            <span class="status-text">{{ $status }}</span>
                                        </span>
                                    </td>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $selectedBagian === 'Produksi' ? 8 : 4 }}"
                                        class="px-6 py-10 text-center text-sm font-bold text-carbon-black-500">
                                        Tidak ada karyawan aktif untuk bagian ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </form>

        <section class="rounded-xl border border-carbon-black-200 bg-white shadow-sm">
            <div class="border-b border-carbon-black-100 bg-carbon-black-50 px-5 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Riwayat Aktivitas Terbaru</h2>
                
                <form method="GET" action="{{ route('aktivitas_karyawan.index') }}" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                    <input type="hidden" name="bagian" value="{{ $selectedBagian }}">
                    
                    <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                        <input type="text" name="riwayat_search" value="{{ $riwayatSearch ?? '' }}" placeholder="Cari nama/NIK..." class="w-full md:w-48 px-3 py-1.5 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-sm font-medium" autocomplete="off" onchange="this.form.submit()">
                        <button type="submit" class="bg-carbon-black-50 px-3 py-1.5 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                            <x-tabler-search class="w-4 h-4" />
                        </button>
                    </div>
                    
                    <div class="relative">
                        <select name="riwayat_bagian" class="appearance-none rounded-lg border border-carbon-black-200 bg-white px-3 py-1.5 pr-8 text-sm font-semibold text-carbon-black-800 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 shadow-sm" onchange="this.form.submit()">
                            <option value="">Semua Bagian</option>
                            <option value="Produksi" {{ ($riwayatBagian ?? '') === 'Produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="Peternakan" {{ ($riwayatBagian ?? '') === 'Peternakan' ? 'selected' : '' }}>Peternakan</option>
                            <option value="Packing" {{ ($riwayatBagian ?? '') === 'Packing' ? 'selected' : '' }}>Packing</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                    
                    <button type="submit" class="hidden">Filter</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full border-collapse">
                    <thead class="bg-white text-carbon-black-500">
                        <tr class="border-b border-carbon-black-100">
                            <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-widest">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-widest">Karyawan</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Bagian</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Aktual</th>
                            <th class="px-5 py-3 text-center text-xs font-black uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-carbon-black-100">
                        @forelse($recentPiket as $row)
                            @php
                                $status = $row['status_piket'] ?? 'Kurang';
                                $statusClass = $statusClasses[$status] ?? 'bg-carbon-black-50 text-carbon-black-600 border-carbon-black-200';
                                $actual = ($row['bagian'] ?? '') === 'Produksi'
                                    ? number_format($row['tp_gram'] ?? 0, 0, ',', '.') . ' gram'
                                    : number_format($row['liter_setor'] ?? 0, 2, ',', '.') . ' liter';
                            @endphp
                            <tr class="hover:bg-hunter-green-50/50">
                                <td class="px-5 py-4 text-sm font-bold text-carbon-black-700">
                                    {{ date('d M Y', strtotime($row['tanggal'])) }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-carbon-black-900">{{ $row['nama'] }}</p>
                                    <p class="text-xs font-semibold text-carbon-black-400">NIK: {{ $row['nik'] }}</p>
                                </td>
                                <td class="px-5 py-4 text-center text-sm font-bold text-carbon-black-700">{{ $row['bagian'] }}
                                </td>
                                <td class="px-5 py-4 text-center text-sm font-bold text-carbon-black-700">{{ $actual }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">{{ $status }}</span>
                                </td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm font-bold text-carbon-black-500">Belum ada
                                    rekap aktivitas batch.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            const resepProduk = @json($produkResep ?? []);

            // Fast Search Karyawan
            function filterKaryawanTable() {
                const input = document.getElementById("fast-search-karyawan");
                const filter = input.value.toLowerCase();
                const rows = document.querySelectorAll(".piket-row");

                rows.forEach(row => {
                    const infoElement = row.querySelector("td:nth-child(2)");
                    if (infoElement) {
                        const infoText = infoElement.textContent.toLowerCase();
                        if (infoText.indexOf(filter) > -1) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const statusClasses = {
                    Memenuhi: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    Lebih: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    Kurang: 'bg-rose-50 text-rose-700 border-rose-200',
                };

                function formatNumber(value, maximumFractionDigits = 0) {
                    return Number(value || 0).toLocaleString('id-ID', {
                        maximumFractionDigits,
                        minimumFractionDigits: maximumFractionDigits > 0 && Number(value || 0) % 1 !== 0 ? 1 : 0,
                    });
                }

                function customRound(value) {
                    return Math.floor(value);
                }

                const selectedBagian = "{{ $selectedBagian }}";

                function updateRow(row) {
                    const target = Number(row.dataset.target || 0);
                    const actualInput = row.querySelector('.piket-actual');
                    const statusElement = row.querySelector('.piket-status');
                    const statusTextElement = statusElement?.querySelector('.status-text');
                    const diffElement = statusElement?.querySelector('.piket-diff');

                    const susuInput = row.querySelector('.piket-susu');
                    const rasaSelect = row.querySelector('.piket-rasa');
                    const gulaHidden = row.querySelector('.piket-gula');
                    const perasaHidden = row.querySelector('.piket-perasa');

                    if (selectedBagian === 'Packing') {
                        const sachet = Number(row.querySelector('.piket-sachet')?.value || 0);
                        const p250 = Number(row.querySelector('.piket-p250')?.value || 0);
                        const p500 = Number(row.querySelector('.piket-p500')?.value || 0);
                        const p1000 = Number(row.querySelector('.piket-p1000')?.value || 0);
                        const totalWeight = (sachet * 25) + (p250 * 250) + (p500 * 500) + (p1000 * 1000);
                        if (actualInput) {
                            actualInput.value = totalWeight;
                        }
                    }

                    const actual = Number(actualInput?.value || 0);
                    const rawDiff = actual - target;
                    const diff = customRound(Math.max(0, rawDiff));
                    let status = 'Kurang';

                    if (actual >= target && actual > 0) {
                        status = 'Memenuhi';
                    }

                    if (statusElement) {
                        statusElement.className = `piket-status inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest ${statusClasses[status]}`;
                        if (statusTextElement) statusTextElement.textContent = status;

                        if (diffElement) {
                            diffElement.textContent = '';
                        }
                    }

                    // Highlight row logic
                    const isPackingFilled = selectedBagian === 'Packing' && (
                        Number(row.querySelector('.piket-sachet')?.value || 0) > 0 ||
                        Number(row.querySelector('.piket-p250')?.value || 0) > 0 ||
                        Number(row.querySelector('.piket-p500')?.value || 0) > 0 ||
                        Number(row.querySelector('.piket-p1000')?.value || 0) > 0
                    );
                    if (actual > 0 || (susuInput && Number(susuInput.value) > 0) || isPackingFilled) {
                        row.classList.add('bg-hunter-green-50/30', 'shadow-sm', 'border-y', 'border-hunter-green-200');
                    } else {
                        row.classList.remove('bg-hunter-green-50/30', 'shadow-sm', 'border-y', 'border-hunter-green-200');
                    }

                }

                document.querySelectorAll('.piket-row').forEach(row => {
                    row.querySelectorAll('.piket-input').forEach(input => {
                        input.addEventListener('input', () => updateRow(row));
                        input.addEventListener('change', () => updateRow(row));
                    });

                    const unlockBtn = row.querySelector('.btn-unlock');
                    if (unlockBtn) {
                        unlockBtn.addEventListener('click', function() {
                            row.querySelectorAll('.piket-input').forEach(input => {
                                input.disabled = false;
                                input.classList.remove('bg-carbon-black-100', 'text-carbon-black-500', 'cursor-not-allowed', 'border-transparent', 'shadow-inner');
                                input.classList.add('bg-carbon-black-50', 'text-carbon-black-800', 'border-carbon-black-200', 'focus:border-hunter-green-500', 'focus:ring-2', 'focus:ring-hunter-green-500/20');
                            });
                            this.style.display = 'none';
                        });
                    }

                    updateRow(row);
                });
            });
        </script>
    @endpush
@endsection