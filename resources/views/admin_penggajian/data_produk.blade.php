@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
    <div class="flex flex-col space-y-8 overflow-hidden">
        <div class="space-y-6">
            <h1
                class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                Data Produk
            </h1>

            <div class="flex justify-between items-end gap-4">
                <form method="GET" action="{{ route('data_produk.index') }}" class="flex-1 max-w-md">
                    <div
                        class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari berdasarkan nama produk..."
                            class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-base font-medium"
                            autocomplete="off">
                        <button type="submit"
                            class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                            <x-tabler-search class="w-4 h-4" />
                        </button>
                    </div>
                </form>
                <button onclick="openAddModal()"
                    class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                    <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                    <span>Tambah</span>
                </button>
            </div>
        </div>
        
        @if(session('success'))
            <div id="toast-alert" class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg flex items-center justify-between shadow-sm transition-opacity duration-500 opacity-100">
                <div class="flex items-center gap-3">
                    <div class="text-emerald-500 bg-white p-2 rounded-full shadow-sm">
                        <x-tabler-check class="w-5 h-5" stroke-width="3" />
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-emerald-800 uppercase tracking-wider mb-0.5">Berhasil</h4>
                        <p class="text-xs font-bold text-emerald-600">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" onclick="dismissToast()" class="text-emerald-600 hover:bg-emerald-100 p-1.5 rounded-lg transition-colors active:scale-95">
                    <x-tabler-x class="w-5 h-5" />
                </button>
            </div>
        @endif

        <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative flex-1 min-h-0">
            <div class="max-h-[calc(125vh-24rem)] overflow-y-auto scrollbar-invisible">
                <table class="w-full border-collapse">
                    <thead
                        class="sticky top-0 z-10 bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                        <tr>
                            <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                            <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-left">NAMA PRODUK</th>
                            <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">TARGET PRODUKSI
                            </th>
                            <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                            <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-carbon-black-100">
                        @forelse($produkResep as $index => $produk)
                            <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                                <td
                                    class="px-5 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-5">
                                    <p class="text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">
                                        {{ $produk['nama_produk'] }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 text-center text-base font-semibold text-carbon-black-800">
                                    {{ number_format($produk['target_produksi'], 0, ',', '.') }} {{ $produk['satuan_produk'] }}
                                </td>
                                <td class="px-5 py-5 text-center text-base">
                                    @php
                                        $statusColor = strtolower($produk['status']) === 'aktif'
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                            : 'bg-rose-50 text-rose-700 border-rose-200';
                                    @endphp
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                        {{ $produk['status'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" onclick="openDetailModal('{{ addslashes($produk['nama_produk']) }}', '{{ strtolower($produk['status']) }}', '{{ number_format($produk['target_produksi'], 0, ',', '.') }} {{ $produk['satuan_produk'] }}', '{{ number_format($produk['susu_liter'], 2, ',', '.') }} Liter', '{{ number_format($produk['gula_gram'], 0, ',', '.') }} Gram', '{{ addslashes($produk['perasa_jenis'] ?? '-') }}', '{{ number_format($produk['perasa_gram'], 0, ',', '.') }} Gram')"
                                            class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200"
                                            title="Lihat">
                                            <x-tabler-eye class="w-4 h-4" />
                                            <span>Detail</span>
                                        </button>

                                        @if($produk['is_used'])
                                            @if(strtolower($produk['status']) === 'aktif')
                                                <form action="{{ route('data_produk.toggle_status', $produk['id_produk']) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" onclick="confirmToggleStatus('Nonaktifkan', '{{ addslashes($produk['nama_produk']) }}', this.closest('form'))"
                                                        class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200"
                                                        title="Nonaktifkan">
                                                        <x-tabler-power class="w-4 h-4" />
                                                        <span>Nonaktifkan</span>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('data_produk.toggle_status', $produk['id_produk']) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" onclick="confirmToggleStatus('Aktifkan', '{{ addslashes($produk['nama_produk']) }}', this.closest('form'))"
                                                        class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-emerald-200"
                                                        title="Aktifkan">
                                                        <x-tabler-power class="w-4 h-4" />
                                                        <span>Aktifkan</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            @if(strtolower($produk['status']) === 'aktif')
                                                <button onclick="openEditModal('{{ addslashes($produk['nama_produk']) }}', {{ $produk['target_produksi'] }}, {{ $produk['susu_liter'] }}, {{ $produk['gula_gram'] }}, '{{ addslashes($produk['perasa_jenis'] ?? '') }}', {{ $produk['perasa_gram'] ?? 0 }}, false)"
                                                    class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200"
                                                    title="Edit">
                                                    <x-tabler-pencil class="w-4 h-4" />
                                                    <span>Ubah</span>
                                                </button>
                                            @else
                                                <button disabled
                                                    class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-carbon-black-50 text-carbon-black-400 cursor-not-allowed shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-carbon-black-200"
                                                    title="Data Inaktif">
                                                    <x-tabler-pencil class="w-4 h-4" />
                                                    <span>Ubah</span>
                                                </button>
                                            @endif
                                            <form action="{{ route('data_produk.destroy', $produk['id_produk']) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data produk ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-rose-200"
                                                    title="Hapus Permanen">
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
                                <td colspan="5" class="px-5 py-10 text-center text-sm font-bold text-carbon-black-500">
                                    @if(!empty($search))
                                        Tidak ada produk yang cocok dengan pencarian "{{ $search }}".
                                    @else
                                        Belum ada data produk.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Produk -->
    <div id="modal-detail" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <div class="px-6 py-5 flex justify-between items-center border-b border-carbon-black-100">
                <h2 class="text-[17px] font-black text-carbon-black-900 uppercase tracking-widest">DETAIL DATA PRODUK</h2>
                <button type="button" onclick="closeDetailModal()" class="text-carbon-black-400 hover:text-rose-600 transition-colors p-1">
                    <x-tabler-x class="w-5 h-5" />
                </button>
            </div>
            
            <!-- Body Info -->
            <div class="p-6 bg-white">
                <div class="grid grid-cols-2 gap-y-7 gap-x-8">
                    <!-- Row 1 -->
                    <div>
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">NAMA PRODUK</p>
                        <p id="detail-nama-produk" class="text-base font-semibold text-carbon-black-900">Nama Produk</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">TARGET PRODUKSI</p>
                        <p id="detail-jumlah-standar" class="text-base font-semibold text-carbon-black-900">0 Gram</p>
                    </div>
                    
                    <!-- Row 2 -->
                    <div>
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">BAHAN UTAMA: SUSU</p>
                        <p id="detail-susu" class="text-base font-semibold text-carbon-black-900">0 Liter</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">BAHAN UTAMA: GULA</p>
                        <p id="detail-gula" class="text-base font-semibold text-carbon-black-900">0 Gram</p>
                    </div>
                    
                    <!-- Row 3 -->
                    <div>
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">VARIAN PERASA</p>
                        <p id="detail-perasa-jenis" class="text-base font-semibold text-carbon-black-900">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">JUMLAH PERASA</p>
                        <p id="detail-perasa-gram" class="text-base font-semibold text-carbon-black-900">0 Gram</p>
                    </div>
                    
                    <!-- Row 4 (Separator) -->
                    <div class="col-span-2 pt-6 border-t border-carbon-black-100 mt-2">
                        <p class="text-[11px] font-black text-hunter-green-600 uppercase tracking-widest mb-2.5 opacity-70">STATUS DATA</p>
                        <p id="detail-status" class="text-base font-bold text-emerald-600">AKTIF</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 flex justify-end bg-hunter-green-50/50 border-t border-carbon-black-100">
                <button type="button" onclick="closeDetailModal()" class="px-6 py-2.5 text-[13px] font-bold text-white bg-hunter-green-600 hover:bg-hunter-green-700 rounded-lg shadow-sm transition-all active:scale-95 uppercase tracking-wider">
                    KEMBALI
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Produk -->
    <div id="modal-ubah"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div
                class="px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center bg-carbon-black-50 rounded-t-xl">
                <h2 class="text-lg font-black text-carbon-black-900 tracking-wider">UBAH DATA PRODUK</h2>
                <button type="button" onclick="closeEditModal()"
                    class="text-carbon-black-400 hover:text-rose-600 transition-colors">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto">
                <form id="form-ubah" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <!-- Locked Banner (Hidden by default) -->
                    <div id="edit-locked-banner" class="hidden mb-6 bg-slate-50 border-l-4 border-slate-500 p-4 rounded-r-lg flex gap-3 shadow-sm">
                        <div class="text-slate-500 mt-0.5">
                            <x-tabler-lock class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="text-base font-black text-slate-800 uppercase tracking-wider mb-1">Data Terkunci</h4>
                            <p class="text-[15px] font-semibold text-slate-700 leading-relaxed">
                                Data produk ini telah digunakan dalam riwayat aktivitas produksi sehingga <strong>tidak dapat diubah</strong> untuk menjaga integritas data historis. Anda hanya dapat mengubah status aktif/nonaktif di tabel.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Section 1: Identitas Produk -->
                        <div>
                            <h3 class="text-sm font-black text-carbon-black-800 uppercase tracking-widest border-b border-carbon-black-100 pb-2 mb-4">1. Identitas Produk</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5">Nama Produk</label>
                                    <input type="text" id="edit-nama-produk" name="nama_produk" required
                                        class="edit-input w-full px-4 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all disabled:opacity-60 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="edit-target-produksi" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Target Produksi (1x Resep)</label>
                                    <div class="relative flex items-center">
                                        <input type="number" id="edit-target-produksi" name="target_produksi" required min="1" step="1"
                                            class="edit-input w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all disabled:opacity-60 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Gram</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Komposisi Utama -->
                        <div>
                            <h3 class="text-sm font-black text-carbon-black-800 uppercase tracking-widest border-b border-carbon-black-100 pb-2 mb-4">2. Komposisi Wajib</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5 flex items-center gap-2"><x-tabler-milk class="w-4 h-4 text-blue-500" /> Susu Murni</label>
                                    <div class="relative">
                                        <input type="number" id="edit-susu-liter" name="susu_liter" required min="0" step="0.1"
                                            class="edit-input w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all disabled:opacity-60 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Liter</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5 flex items-center gap-2"><x-tabler-cube class="w-4 h-4 text-amber-500" /> Gula Pasir</label>
                                    <div class="relative">
                                        <input type="number" id="edit-gula-gram" name="gula_gram" required min="0" step="1"
                                            class="edit-input w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all disabled:opacity-60 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Gram</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Komposisi Tambahan -->
                        <div>
                            <h3 class="text-sm font-black text-carbon-black-800 uppercase tracking-widest border-b border-carbon-black-100 pb-2 mb-4">3. Komposisi Tambahan (Opsional)</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5 flex items-center gap-2"><x-tabler-leaf class="w-4 h-4 text-emerald-500" /> Jenis Varian / Perasa</label>
                                    <input type="text" id="edit-perasa-jenis" name="perasa_jenis"
                                        class="edit-input w-full px-4 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all disabled:opacity-60 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5">Jumlah Perasa</label>
                                    <div class="relative">
                                        <input type="number" id="edit-perasa-gram" name="perasa_gram" min="0" step="1"
                                            class="edit-input w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all disabled:opacity-60 disabled:bg-slate-100 disabled:cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Gram</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-carbon-black-100 flex justify-end bg-carbon-black-50 rounded-b-xl">
                <div class="flex gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2.5 text-sm font-bold text-carbon-black-600 bg-transparent hover:bg-carbon-black-200 rounded-lg transition-all active:scale-95 uppercase tracking-wider">
                        Tutup
                    </button>
                    <button type="button" id="btn-simpan-ubah" onclick="closeEditModal()"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all active:scale-95 uppercase tracking-wider flex items-center gap-2">
                        <x-tabler-device-floppy class="w-5 h-5" />
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div id="modal-tambah"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div
                class="px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center bg-hunter-green-50 rounded-t-xl">
                <h2 class="text-lg font-black text-hunter-green-800 tracking-wider flex items-center gap-2">
                    <x-tabler-circle-plus class="w-5 h-5" /> TAMBAH DATA PRODUK BARU
                </h2>
                <button type="button" onclick="closeAddModal()"
                    class="text-carbon-black-400 hover:text-rose-600 transition-colors">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto">
                <form id="form-tambah" method="POST" action="{{ route('data_produk.store') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Section 1: Identitas Produk -->
                        <div>
                            <h3 class="text-sm font-black text-carbon-black-800 uppercase tracking-widest border-b border-carbon-black-100 pb-2 mb-4">1. Identitas Produk</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5">Nama Produk</label>
                                    <input type="text" name="nama_produk" required
                                        class="w-full px-4 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all"
                                        placeholder="Contoh: Susu Kambing Original">
                                </div>
                                <div>
                                    <label for="tambah-target-produksi" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Target Produksi (1x Resep)</label>
                                    <div class="relative flex items-center">
                                        <input type="number" name="target_produksi" required min="1" step="1"
                                            class="w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Gram</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Komposisi Utama -->
                        <div>
                            <h3 class="text-sm font-black text-carbon-black-800 uppercase tracking-widest border-b border-carbon-black-100 pb-2 mb-4">2. Komposisi Wajib</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5 flex items-center gap-2"><x-tabler-milk class="w-4 h-4 text-blue-500" /> Susu Murni</label>
                                    <div class="relative">
                                        <input type="number" name="susu_liter" required min="0" step="0.1"
                                            class="w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Liter</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5 flex items-center gap-2"><x-tabler-cube class="w-4 h-4 text-amber-500" /> Gula Pasir</label>
                                    <div class="relative">
                                        <input type="number" name="gula_gram" required min="0" step="1"
                                            class="w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Gram</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Komposisi Tambahan -->
                        <div>
                            <h3 class="text-sm font-black text-carbon-black-800 uppercase tracking-widest border-b border-carbon-black-100 pb-2 mb-4">3. Komposisi Tambahan (Opsional)</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5 flex items-center gap-2"><x-tabler-leaf class="w-4 h-4 text-emerald-500" /> Jenis Varian / Perasa</label>
                                    <input type="text" name="perasa_jenis"
                                        class="w-full px-4 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all"
                                        placeholder="Contoh: Jahe">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-carbon-black-600 uppercase tracking-widest mb-1.5">Jumlah Perasa</label>
                                    <div class="relative">
                                        <input type="number" name="perasa_gram" min="0" step="1"
                                            class="w-full pl-4 pr-16 py-2.5 bg-carbon-black-50 border-2 border-carbon-black-200 rounded-lg text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 outline-none transition-all"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-xs font-bold text-carbon-black-500 uppercase">Gram</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-carbon-black-100 flex justify-end gap-3 bg-carbon-black-50 rounded-b-xl">
                <button type="button" onclick="closeAddModal()"
                    class="px-6 py-2.5 text-sm font-bold text-carbon-black-600 bg-transparent hover:bg-carbon-black-200 rounded-lg transition-all active:scale-95 uppercase tracking-wider">
                    Batal
                </button>
                <button type="submit" form="form-tambah"
                    class="px-6 py-2.5 text-sm font-bold text-white bg-hunter-green-600 hover:bg-hunter-green-700 rounded-lg shadow-md transition-all active:scale-95 uppercase tracking-wider flex items-center gap-2">
                    <x-tabler-device-floppy class="w-5 h-5" />
                    Simpan Data
                </button>
            </div>
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
                <div id="confirm-modal-icon" class="mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4 bg-amber-100 text-amber-600">
                    <x-tabler-alert-triangle class="w-6 h-6" />
                </div>
                <p id="confirm-modal-message" class="text-base font-semibold text-carbon-black-700">Apakah Anda yakin ingin mengubah status data ini?</p>
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

    @push('scripts')
        <script>
            // Modal Detail Logic
            const modalDetail = document.getElementById('modal-detail');
            const modalDetailInner = modalDetail ? modalDetail.querySelector('div') : null;

            function openDetailModal(nama, status, jumlah, susu, gula, perasaJenis, perasaGram) {
                document.getElementById('detail-nama-produk').textContent = nama;
                document.getElementById('detail-jumlah-standar').textContent = jumlah;
                document.getElementById('detail-susu').textContent = susu;
                document.getElementById('detail-gula').textContent = gula;
                document.getElementById('detail-perasa-jenis').textContent = perasaJenis;
                document.getElementById('detail-perasa-gram').textContent = perasaGram;
                
                const statusBadge = document.getElementById('detail-status');
                statusBadge.textContent = status.toUpperCase();
                if(status === 'aktif') {
                    statusBadge.className = 'text-[15px] font-bold text-emerald-600';
                } else {
                    statusBadge.className = 'text-[15px] font-bold text-rose-600';
                }

                if (modalDetail) {
                    modalDetail.classList.remove('hidden');
                    modalDetail.classList.add('flex');
                    setTimeout(() => {
                        modalDetail.classList.remove('opacity-0');
                        if (modalDetailInner) {
                            modalDetailInner.classList.remove('scale-95');
                            modalDetailInner.classList.add('scale-100');
                        }
                    }, 10);
                }
            }

            function closeDetailModal() {
                if (modalDetail) {
                    modalDetail.classList.add('opacity-0');
                    if (modalDetailInner) {
                        modalDetailInner.classList.remove('scale-100');
                        modalDetailInner.classList.add('scale-95');
                    }
                    setTimeout(() => {
                        modalDetail.classList.add('hidden');
                        modalDetail.classList.remove('flex');
                    }, 300);
                }
            }

            // Modal Ubah Logic
            const modalUbah = document.getElementById('modal-ubah');
            const modalUbahInner = modalUbah ? modalUbah.querySelector('div') : null;

            window.openEditModal = function(nama, targetProduksi, susuLiter, gulaGram, perasaJenis, perasaGram, isUsed) {
                document.getElementById('edit-nama-produk').value = nama;
                document.getElementById('edit-target-produksi').value = targetProduksi;
                document.getElementById('edit-susu-liter').value = susuLiter;
                document.getElementById('edit-gula-gram').value = gulaGram;
                document.getElementById('edit-perasa-jenis').value = perasaJenis === '-' ? '' : perasaJenis;
                document.getElementById('edit-perasa-gram').value = perasaGram;

                const banner = document.getElementById('edit-locked-banner');
                const inputs = document.querySelectorAll('.edit-input');
                const btnSimpan = document.getElementById('btn-simpan-ubah');

                if (isUsed) {
                    banner.classList.remove('hidden');
                    inputs.forEach(input => input.disabled = true);
                    btnSimpan.classList.add('hidden');
                } else {
                    banner.classList.add('hidden');
                    inputs.forEach(input => input.disabled = false);
                    btnSimpan.classList.remove('hidden');
                }

                if (modalUbah) {
                    modalUbah.classList.remove('hidden');
                    modalUbah.classList.add('flex');
                    setTimeout(() => {
                        modalUbah.classList.remove('opacity-0');
                        if (modalUbahInner) {
                            modalUbahInner.classList.remove('scale-95');
                            modalUbahInner.classList.add('scale-100');
                        }
                    }, 10);
                }
            }

            function closeEditModal() {
                if (modalUbah) {
                    modalUbah.classList.add('opacity-0');
                    if (modalUbahInner) {
                        modalUbahInner.classList.remove('scale-100');
                        modalUbahInner.classList.add('scale-95');
                    }
                    setTimeout(() => {
                        modalUbah.classList.add('hidden');
                        modalUbah.classList.remove('flex');
                    }, 300);
                }
            }
            
            // Modal Tambah Logic
            const modalTambah = document.getElementById('modal-tambah');
            const modalTambahInner = modalTambah ? modalTambah.querySelector('div') : null;

            function openAddModal() {
                if (modalTambah) {
                    modalTambah.classList.remove('hidden');
                    modalTambah.classList.add('flex');
                    setTimeout(() => {
                        modalTambah.classList.remove('opacity-0');
                        if (modalTambahInner) {
                            modalTambahInner.classList.remove('scale-95');
                            modalTambahInner.classList.add('scale-100');
                        }
                    }, 10);
                }
            }

            function closeAddModal() {
                if (modalTambah) {
                    modalTambah.classList.add('opacity-0');
                    if (modalTambahInner) {
                        modalTambahInner.classList.remove('scale-100');
                        modalTambahInner.classList.add('scale-95');
                    }
                    setTimeout(() => {
                        modalTambah.classList.add('hidden');
                        modalTambah.classList.remove('flex');
                    }, 300);
                }
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
            // Confirm Status Modal Logic
            const confirmModal = document.getElementById('modal-confirm-status');
            const confirmModalInner = confirmModal ? confirmModal.querySelector('div') : null;
            const confirmTitle = document.getElementById('confirm-modal-title');
            const confirmMessage = document.getElementById('confirm-modal-message');
            const confirmIcon = document.getElementById('confirm-modal-icon');
            const confirmBtn = document.getElementById('confirm-modal-btn');
            let formToSubmit = null;

            window.confirmToggleStatus = function(action, entityName, formElement) {
                if (!confirmModal) return;
                
                formToSubmit = formElement;
                
                if (action === 'Nonaktifkan') {
                    confirmTitle.textContent = 'Nonaktifkan Data';
                    confirmMessage.textContent = `Apakah Anda yakin ingin menonaktifkan "${entityName}"?`;
                    confirmIcon.className = 'mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4 bg-amber-100 text-amber-600';
                    confirmIcon.innerHTML = '<svg class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>';
                    confirmBtn.className = 'px-5 py-2 bg-amber-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-amber-700 transition-colors';
                    confirmBtn.textContent = 'Ya, Nonaktifkan';
                } else {
                    confirmTitle.textContent = 'Aktifkan Data';
                    confirmMessage.textContent = `Apakah Anda yakin ingin mengaktifkan "${entityName}"?`;
                    confirmIcon.className = 'mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4 bg-emerald-100 text-emerald-600';
                    confirmIcon.innerHTML = '<svg class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>';
                    confirmBtn.className = 'px-5 py-2 bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-emerald-700 transition-colors';
                    confirmBtn.textContent = 'Ya, Aktifkan';
                }

                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
                setTimeout(() => {
                    confirmModal.classList.remove('opacity-0');
                    if (confirmModalInner) {
                        confirmModalInner.classList.remove('scale-95');
                        confirmModalInner.classList.add('scale-100');
                    }
                }, 10);
            }

            window.closeConfirmModal = function() {
                if (!confirmModal) return;
                
                confirmModal.classList.add('opacity-0');
                if (confirmModalInner) {
                    confirmModalInner.classList.remove('scale-100');
                    confirmModalInner.classList.add('scale-95');
                }
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
        </script>
    @endpush

@endsection
