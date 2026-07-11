@extends('layouts.app')

@section('title', 'Data Gaji Pokok & Lembur')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            DATA GAJI POKOK & LEMBUR
        </h1>

        <div class="flex justify-between items-end gap-4">
            <form method="GET" action="{{ route('gaji_pokok.index') }}" class="flex-1 max-w-md">
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan nama tarif..." class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-base font-medium" autocomplete="off">
                    <button type="submit" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>
            </form>
            
            <button type="button" onclick="openTarifModal()" class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>

        @if(session('success'))
            <div id="toast-alert" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 shadow-sm transition-opacity duration-500 opacity-100">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Gaji Pokok Table -->
    <section class="space-y-4">
        <h2 class="text-xl font-black uppercase tracking-widest text-carbon-black-800">Gaji Pokok</h2>

        <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative">
            <table class="w-full border-collapse">
                <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                    <tr>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-20">NO</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TARIF</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-carbon-black-100">
                    @forelse($gajiPokok as $g)
                        <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                            <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $g['nama'] }}</td>
                            <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ number_format($g['tarif'], 2, '.', '') }}</td>
                            <td class="px-6 py-5 text-center text-base">
                                @php
                                    $statusColor = match(strtolower($g['status'])) {
                                        'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'inaktif' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                    {{ $g['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openEditModal({{ $g['id'] }}, '{{ addslashes($g['nama']) }}', {{ $g['tarif'] }}, 'gaji_pokok')" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                        <x-tabler-pencil class="w-4 h-4" />
                                        <span>Ubah</span>
                                    </button>
                                    @if(isset($g['is_used']) && $g['is_used'])
                                        <form action="{{ route('gaji_pokok.toggle_status', $g['id']) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            @if(strtolower($g['status']) === 'aktif')
                                                <button type="button" onclick="confirmToggleStatus('Nonaktifkan', '{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Nonaktifkan">
                                                    <x-tabler-power class="w-4 h-4" />
                                                    <span>Nonaktifkan</span>
                                                </button>
                                            @else
                                                <button type="button" onclick="confirmToggleStatus('Aktifkan', '{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-emerald-200" title="Aktifkan">
                                                    <x-tabler-power class="w-4 h-4" />
                                                    <span>Aktifkan</span>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                    @if(isset($g['is_default']) && $g['is_default'])
                                        <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 shadow-sm text-xs font-bold uppercase tracking-wider border border-gray-200 cursor-not-allowed" title="Data Bawaan Sistem Tidak Dapat Dihapus">
                                            <x-tabler-lock class="w-4 h-4" />
                                            <span>Terkunci</span>
                                        </div>
                                    @else
                                        <form action="{{ route('gaji_pokok.destroy', $g['id']) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmToggleStatus('Hapus', '{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
                                                <x-tabler-trash class="w-4 h-4" />
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm font-bold text-carbon-black-500">
                                @if(!empty($search))
                                    Tidak ada data gaji pokok yang cocok dengan pencarian "{{ $search }}".
                                @else
                                    Belum ada data gaji pokok.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Gaji Lembur Table -->
    <section class="space-y-4">
        <h2 class="text-xl font-black uppercase tracking-widest text-carbon-black-800">Gaji Lembur</h2>

        <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative">
            <table class="w-full border-collapse">
                <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                    <tr>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-20">NO</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TARIF</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">KETERANGAN</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                        <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-carbon-black-100">
                    @forelse($gajiLembur as $g)
                        <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                            <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $g['nama'] }}</td>
                            <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ number_format($g['tarif'], 2, '.', '') }}</td>
                            <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $g['keterangan'] }}</td>
                            <td class="px-6 py-5 text-center text-base">
                                @php
                                    $statusColor = match(strtolower($g['status'])) {
                                        'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'inaktif' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                    {{ $g['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openEditModal({{ $g['id'] }}, '{{ addslashes($g['nama']) }}', {{ $g['tarif'] }}, 'gaji_lembur', '{{ addslashes($g['keterangan']) }}')" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                        <x-tabler-pencil class="w-4 h-4" />
                                        <span>Ubah</span>
                                    </button>
                                    @if(isset($g['is_used']) && $g['is_used'])
                                        <form action="{{ route('gaji_lembur.toggle_status', $g['id']) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            @if(strtolower($g['status']) === 'aktif')
                                                <button type="button" onclick="confirmToggleStatus('Nonaktifkan', '{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Nonaktifkan">
                                                    <x-tabler-power class="w-4 h-4" />
                                                    <span>Nonaktifkan</span>
                                                </button>
                                            @else
                                                <button type="button" onclick="confirmToggleStatus('Aktifkan', '{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-emerald-200" title="Aktifkan">
                                                    <x-tabler-power class="w-4 h-4" />
                                                    <span>Aktifkan</span>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                    @if(isset($g['is_default']) && $g['is_default'])
                                        <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 shadow-sm text-xs font-bold uppercase tracking-wider border border-gray-200 cursor-not-allowed" title="Data Bawaan Sistem Tidak Dapat Dihapus">
                                            <x-tabler-lock class="w-4 h-4" />
                                            <span>Terkunci</span>
                                        </div>
                                    @else
                                        <form action="{{ route('gaji_lembur.destroy', $g['id']) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmToggleStatus('Hapus', '{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
                                                <x-tabler-trash class="w-4 h-4" />
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm font-bold text-carbon-black-500">
                                @if(!empty($search))
                                    Tidak ada data gaji lembur yang cocok dengan pencarian "{{ $search }}".
                                @else
                                    Belum ada data gaji lembur.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Tambah Tarif Modal -->
    <div id="modal-tambah-tarif" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-start gap-6">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Tambah Tarif Gaji</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Isi data tarif untuk gaji pokok atau gaji lembur.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeTarifModal()">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <form method="POST" action="{{ route('gaji_pokok.store') }}">
                @csrf
                <div class="px-6 py-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="jenis_tarif" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Jenis Tarif</label>
                            <div class="relative">
                                <select id="jenis_tarif" name="jenis_tarif"
                                    class="w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                                    <option value="gaji_pokok" {{ old('jenis_tarif') === 'gaji_pokok' ? 'selected' : '' }}>Gaji Pokok</option>
                                    <option value="gaji_lembur" {{ old('jenis_tarif') === 'gaji_lembur' ? 'selected' : '' }}>Gaji Lembur</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-carbon-black-400">
                                    <x-tabler-chevron-down class="h-4 w-4" />
                                </div>
                            </div>
                            @error('jenis_tarif')
                                <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Status</label>
                            <input type="hidden" name="status" value="Aktif">
                            <div>
                                <span class="inline-flex px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest border shadow-sm bg-emerald-50 text-emerald-700 border-emerald-200">
                                    Aktif
                                </span>
                                <p class="mt-2 text-xs font-semibold text-carbon-black-400">* Status otomatis diatur sebagai "Aktif" untuk data baru.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="nama" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Nama Tarif</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Gaji Pokok Produksi per Jam"
                            class="w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg">
                        @error('nama')
                            <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="tarif" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Tarif</label>
                        <div class="relative">
                            <input type="number" min="0" step="0.01" id="tarif" name="tarif" value="{{ old('tarif') }}" placeholder="Contoh: 15000"
                                class="w-full px-4 py-3 pl-12 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-black text-carbon-black-500">Rp</span>
                        </div>
                        @error('tarif')
                            <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="keterangan-lembur-field" class="space-y-2 hidden">
                        <label for="keterangan" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Keterangan Lembur</label>
                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Tarif standar lembur per jam"
                            class="w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg resize-none">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeTarifModal()" class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-black-forest-700 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-black-forest-800 hover:shadow-lg transition-all active:scale-95">
                        Simpan Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Tarif Modal -->
    <div id="modal-edit-tarif" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-start gap-6">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Ubah Tarif Gaji</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Perbarui data tarif untuk gaji pokok atau gaji lembur.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeEditModal()">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <form id="form-edit-tarif" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-4">
                    <div class="space-y-2">
                        <label for="edit_nama" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Nama Tarif</label>
                        <input type="text" id="edit_nama" name="nama" placeholder="Contoh: Gaji Pokok Produksi per Jam"
                            class="w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg">
                    </div>

                    <div class="space-y-2">
                        <label for="edit_tarif" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Tarif</label>
                        <div class="relative">
                            <input type="number" min="0" step="0.01" id="edit_tarif" name="tarif" placeholder="Contoh: 15000"
                                class="w-full px-4 py-3 pl-12 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-black text-carbon-black-500">Rp</span>
                        </div>
                    </div>

                    <div id="edit-keterangan-lembur-field" class="space-y-2 hidden">
                        <label for="edit_keterangan" class="text-xs font-black uppercase tracking-widest text-carbon-black-500 ml-1">Keterangan Lembur</label>
                        <textarea id="edit_keterangan" name="keterangan" rows="3" placeholder="Contoh: Tarif standar lembur per jam"
                            class="w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg resize-none"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirm Status Modal -->
    <div id="modal-confirm-status" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-start gap-6">
                <div>
                    <h3 id="confirm-modal-title" class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Konfirmasi</h3>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeConfirmModal()">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>
            
            <div class="px-6 py-6 text-center">
                <div id="confirm-icon-warning" class="mx-auto w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mb-4 text-amber-600 hidden">
                    <x-tabler-alert-triangle class="w-8 h-8" stroke-width="2.5" />
                </div>
                <div id="confirm-icon-success" class="mx-auto w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4 text-emerald-600 hidden">
                    <x-tabler-check class="w-8 h-8" stroke-width="2.5" />
                </div>
                <div id="confirm-icon-danger" class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4 text-red-600 hidden">
                    <x-tabler-trash class="w-8 h-8" stroke-width="2.5" />
                </div>
                <p id="confirm-modal-message" class="text-sm font-semibold text-carbon-black-600">Apakah Anda yakin ingin mengubah status data ini?</p>
            </div>
            
            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-center gap-3 rounded-b-xl">
                <button type="button" onclick="closeConfirmModal()" class="px-5 py-2 border border-carbon-black-300 text-carbon-black-600 text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-modal-btn" class="px-5 py-2 bg-black-forest-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-black-forest-800 transition-colors">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const tarifModal = document.getElementById('modal-tambah-tarif');
    const tarifModalInner = tarifModal.querySelector('div');
    const jenisTarifSelect = document.getElementById('jenis_tarif');
    const keteranganLemburField = document.getElementById('keterangan-lembur-field');

    function toggleKeteranganLembur() {
        if (!jenisTarifSelect || !keteranganLemburField) {
            return;
        }

        keteranganLemburField.classList.toggle('hidden', jenisTarifSelect.value !== 'gaji_lembur');
    }

    window.openTarifModal = function() {
        toggleKeteranganLembur();
        tarifModal.classList.remove('hidden');
        tarifModal.classList.add('flex');
        setTimeout(() => {
            tarifModal.classList.remove('opacity-0');
            tarifModalInner.classList.remove('scale-95');
            tarifModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeTarifModal = function() {
        tarifModal.classList.add('opacity-0');
        tarifModalInner.classList.remove('scale-100');
        tarifModalInner.classList.add('scale-95');
        setTimeout(() => {
            tarifModal.classList.add('hidden');
            tarifModal.classList.remove('flex');
        }, 300);
    }

    if (jenisTarifSelect) {
        jenisTarifSelect.addEventListener('change', toggleKeteranganLembur);
        toggleKeteranganLembur();
    }

    // Edit Modal Logic
    const editModal = document.getElementById('modal-edit-tarif');
    const editModalInner = editModal ? editModal.querySelector('div') : null;
    const formEditTarif = document.getElementById('form-edit-tarif');
    const editNamaInput = document.getElementById('edit_nama');
    const editTarifInput = document.getElementById('edit_tarif');
    const editKeteranganField = document.getElementById('edit-keterangan-lembur-field');
    const editKeteranganInput = document.getElementById('edit_keterangan');

    window.openEditModal = function(id, nama, tarif, jenis, keterangan = '') {
        if (!editModal) return;
        
        editNamaInput.value = nama;
        editTarifInput.value = tarif;
        
        if (jenis === 'gaji_lembur') {
            formEditTarif.action = `/gaji-lembur/${id}`;
            editKeteranganField.classList.remove('hidden');
            editKeteranganInput.value = keterangan;
        } else {
            formEditTarif.action = `/gaji-pokok/${id}`;
            editKeteranganField.classList.add('hidden');
            editKeteranganInput.value = '';
        }

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
        setTimeout(() => {
            editModal.classList.remove('opacity-0');
            editModalInner.classList.remove('scale-95');
            editModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeEditModal = function() {
        if (!editModal) return;
        
        editModal.classList.add('opacity-0');
        editModalInner.classList.remove('scale-100');
        editModalInner.classList.add('scale-95');
        setTimeout(() => {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }, 300);
    }

    // Confirm Status Modal Logic
    const confirmModal = document.getElementById('modal-confirm-status');
    const confirmModalInner = confirmModal ? confirmModal.querySelector('div') : null;
    const confirmTitle = document.getElementById('confirm-modal-title');
    const confirmMessage = document.getElementById('confirm-modal-message');
    const iconWarning = document.getElementById('confirm-icon-warning');
    const iconSuccess = document.getElementById('confirm-icon-success');
    const iconDanger = document.getElementById('confirm-icon-danger');
    const confirmBtn = document.getElementById('confirm-modal-btn');
    let formToSubmit = null;

    window.confirmToggleStatus = function(action, entityName, formElement) {
        if (!confirmModal) return;
        
        formToSubmit = formElement;
        
        if (action === 'Nonaktifkan') {
            confirmTitle.textContent = 'Nonaktifkan Data';
            confirmMessage.textContent = `Apakah Anda yakin ingin menonaktifkan tarif "${entityName}"?`;
            confirmIcon.className = 'mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4 bg-amber-100 text-amber-600';
            confirmIcon.innerHTML = '<svg class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>';
            confirmBtn.className = 'px-5 py-2 bg-amber-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-amber-700 transition-colors';
            confirmBtn.textContent = 'Ya, Nonaktifkan';
        } else if (action === 'Hapus') {
            confirmTitle.textContent = 'Hapus Permanen?';
            confirmMessage.textContent = `Apakah Anda yakin ingin menghapus tarif "${entityName}" secara permanen?`;
            confirmIcon.className = 'mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4 bg-red-100 text-red-600';
            confirmIcon.innerHTML = '<svg class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';
            confirmBtn.className = 'px-5 py-2 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-red-700 transition-colors';
            confirmBtn.textContent = 'Ya, Hapus';
        } else {
            confirmTitle.textContent = 'Aktifkan Data';
            confirmMessage.textContent = `Apakah Anda yakin ingin mengaktifkan tarif "${entityName}"?`;
            confirmIcon.className = 'mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4 bg-emerald-100 text-emerald-600';
            confirmIcon.innerHTML = '<svg class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>';
            confirmBtn.className = 'px-5 py-2 bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-emerald-700 transition-colors';
            confirmBtn.textContent = 'Ya, Aktifkan';
        }

        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
        setTimeout(() => {
            confirmModal.classList.remove('opacity-0');
            confirmModalInner.classList.remove('scale-95');
            confirmModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeConfirmModal = function() {
        if (!confirmModal) return;
        
        confirmModal.classList.add('opacity-0');
        confirmModalInner.classList.remove('scale-100');
        confirmModalInner.classList.add('scale-95');
        setTimeout(() => {
            confirmModal.classList.add('hidden');
            confirmModal.classList.remove('flex');
        }, 300);
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    }

    // Toast Alert Auto-Dismiss Logic
    function dismissToast() {
        const toast = document.getElementById('toast-alert');
        if (toast) {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
            setTimeout(() => {
                toast.style.display = 'none';
            }, 500); // Wait for transition to finish
        }
    }

    // Auto dismiss after 3.5 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('toast-alert');
        if (toast) {
            setTimeout(() => {
                dismissToast();
            }, 3500);
        }
    });

    @if($errors->any())
        openTarifModal();
    @endif
</script>
@endpush
@endsection
