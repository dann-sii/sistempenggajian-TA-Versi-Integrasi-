@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="flex flex-col space-y-8 overflow-hidden">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
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

            <button onclick="openModal()" class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-tabler-check class="h-5 w-5 text-emerald-500" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-emerald-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

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
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">BAGIAN</th>
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
                                $bagianColor = match($k['bagian'] ?? '') {
                                    'Produksi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Peternakan' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $bagianColor }}">
                                {{ $k['bagian'] ?? '-' }}
                            </span>
                        </td>
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
                                <button type="button" onclick='openEditModal(@json($k))' class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                    <x-tabler-pencil class="w-4 h-4" />
                                    <span>Ubah</span>
                                </button>
                                <button type="button" onclick="openDeleteModal({{ $k['id'] }}, '{{ addslashes($k['nama']) }}')" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
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

<!-- Modal Tambah Data Karyawan -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300">
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Tambah Data Karyawan</h3>
            <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeModal()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>
        
        <form action="{{ route('karyawan.store') }}" method="POST" id="form-tambah">
            @csrf
            <div class="px-6 py-6 space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <!-- NIK -->
                    <div class="space-y-1.5">
                        <label for="nik" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">NIK <span class="text-rose-500">*</span></label>
                        <input type="text" id="nik" name="nik" required pattern="\d{16}" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/\D/g, '').slice(0,16)" placeholder="16 Digit Angka" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <!-- NAMA -->
                    <div class="space-y-1.5">
                        <label for="nama" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama" name="nama" required class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <!-- NPWP -->
                    <div class="space-y-1.5">
                        <label for="npwp" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">NPWP <span class="text-rose-500">*</span></label>
                        <input type="text" id="npwp" name="npwp" required placeholder="16 Digit Angka" pattern="\d{16}" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/\D/g, '').slice(0,16)" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <!-- NO TELP -->
                    <div class="space-y-1.5">
                        <label for="no_telp" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">No. Telp</label>
                        <input type="text" id="no_telp" name="no_telp" pattern="08\d{8,11}" title="Harus diawali dengan 08" minlength="10" maxlength="13" oninput="let v=this.value.replace(/\D/g, ''); if(v.length>0 && v[0]!=='0') v='08'+v; if(v.length>1 && v[1]!=='8') v='08'+v.slice(2); this.value=v.slice(0,13);" placeholder="08XXXXXXXXXX" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <!-- BAGIAN -->
                    <div class="space-y-1.5 relative col-span-2">
                        <label for="bagian" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Bagian <span class="text-rose-500">*</span></label>
                        <select id="bagian" name="bagian" required class="w-full appearance-none px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50 cursor-pointer">
                            <option value="" disabled selected>Pilih Bagian</option>
                            <option value="Produksi">Produksi</option>
                            <option value="Peternakan">Peternakan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-[26px] text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                    <!-- ALAMAT (Full width) -->
                    <div class="space-y-1.5 col-span-2">
                        <label for="alamat" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Alamat <span class="text-rose-500">*</span></label>
                        <textarea id="alamat" name="alamat" required rows="3" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50"></textarea>
                    </div>
                    <!-- NAMA BANK -->
                    <div class="space-y-1.5 relative">
                        <label for="nama_bank" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Nama Bank <span class="text-rose-500">*</span></label>
                        <select id="nama_bank" name="nama_bank" required class="w-full appearance-none px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50 cursor-pointer">
                            <option value="" disabled selected>Pilih Bank</option>
                            <option value="Tidak Memiliki">Tidak Memiliki</option>
                            <option value="BCA">BCA (Bank Central Asia)</option>
                            <option value="BNI">BNI (Bank Negara Indonesia)</option>
                            <option value="BRI">BRI (Bank Rakyat Indonesia)</option>
                            <option value="Mandiri">Bank Mandiri</option>
                            <option value="BSI">BSI (Bank Syariah Indonesia)</option>
                            <option value="CIMB">CIMB Niaga</option>
                            <option value="Permata">Bank Permata</option>
                            <option value="Danamon">Bank Danamon</option>
                            <option value="BTN">BTN (Bank Tabungan Negara)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-[26px] text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                    <!-- NOMOR REKENING -->
                    <div class="space-y-1.5">
                        <label for="no_rekening" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">No. Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" id="no_rekening" name="no_rekening" required oninput="this.value=this.value.replace(/\D/g, '')" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <!-- STATUS (Readonly) -->
                    <div class="space-y-1.5 col-span-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Status</label>
                        <div class="px-3 py-2.5 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center shadow-sm w-max">
                            <span class="text-xs font-black uppercase tracking-widest text-emerald-700">Aktif</span>
                            <input type="hidden" name="status" value="Aktif">
                        </div>
                        <p class="text-[12.5px] text-carbon-black-400 mt-1">* Status otomatis diatur sebagai "Aktif" untuk data baru.</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex justify-end space-x-3 rounded-b-xl">
                <button type="button" class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors" onclick="closeModal()">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 hover:shadow-lg transition-all active:scale-95">Simpan Data</button>
            </div>
        </form>
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
                    <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Bagian</p>
                    <p id="detail-bagian" class="text-base font-semibold text-carbon-black-900">-</p>
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

<!-- Modal Ubah Data Karyawan -->
<div id="modal-ubah" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300">
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Ubah Data Karyawan</h3>
            <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeEditModal()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>
        
        <form method="POST" id="form-ubah">
            @csrf
            @method('PUT')
            <div class="px-6 py-6 space-y-5 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="edit_nik" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">NIK <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_nik" name="nik" required pattern="\d{16}" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/\D/g, '').slice(0,16)" placeholder="16 Digit Angka" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_nama" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_nama" name="nama" required class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_npwp" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">NPWP <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_npwp" name="npwp" required placeholder="16 Digit Angka" pattern="\d{16}" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/\D/g, '').slice(0,16)" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_no_telp" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">No. Telp</label>
                        <input type="text" id="edit_no_telp" name="no_telp" pattern="08\d{8,11}" title="Harus diawali dengan 08" minlength="10" maxlength="13" oninput="let v=this.value.replace(/\D/g, ''); if(v.length>0 && v[0]!=='0') v='08'+v; if(v.length>1 && v[1]!=='8') v='08'+v.slice(2); this.value=v.slice(0,13);" placeholder="08XXXXXXXXXX" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>
                    <div class="space-y-1.5 relative col-span-2">
                        <label for="edit_bagian" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Bagian <span class="text-rose-500">*</span></label>
                        <select id="edit_bagian" name="bagian" required class="w-full appearance-none px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50 cursor-pointer">
                            <option value="Produksi">Produksi</option>
                            <option value="Peternakan">Peternakan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-[26px] text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                    <div class="space-y-1.5 col-span-2">
                        <label for="edit_alamat" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Alamat <span class="text-rose-500">*</span></label>
                        <textarea id="edit_alamat" name="alamat" required rows="3" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50"></textarea>
                    </div>
                    
                    <div class="space-y-1.5 relative">
                        <label for="edit_nama_bank" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Nama Bank <span class="text-rose-500">*</span></label>
                        <select id="edit_nama_bank" name="nama_bank" required class="w-full appearance-none px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50 cursor-pointer">
                            <option value="" disabled>Pilih Bank</option>
                            <option value="Tidak Memiliki">Tidak Memiliki</option>
                            <option value="BCA">BCA (Bank Central Asia)</option>
                            <option value="BNI">BNI (Bank Negara Indonesia)</option>
                            <option value="BRI">BRI (Bank Rakyat Indonesia)</option>
                            <option value="Mandiri">Bank Mandiri</option>
                            <option value="BSI">BSI (Bank Syariah Indonesia)</option>
                            <option value="CIMB">CIMB Niaga</option>
                            <option value="Permata">Bank Permata</option>
                            <option value="Danamon">Bank Danamon</option>
                            <option value="BTN">BTN (Bank Tabungan Negara)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-[26px] text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="edit_no_rekening" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">No. Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_no_rekening" name="no_rekening" required oninput="this.value=this.value.replace(/\D/g, '')" class="w-full px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50">
                    </div>

                    <!-- STATUS -->
                    <div class="space-y-1.5 relative col-span-2">
                        <label for="edit_status" class="text-xs font-bold uppercase tracking-widest text-carbon-black-600">Status <span class="text-rose-500">*</span></label>
                        <select id="edit_status" name="status" required class="w-full appearance-none px-3 py-2.5 border border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-1 focus:ring-hunter-green-500 transition-colors text-sm text-carbon-black-800 bg-carbon-black-50/50 cursor-pointer">
                            <option value="Aktif">Aktif</option>
                            <option value="Inaktif">Inaktif</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-[26px] text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex justify-end space-x-3 rounded-b-xl">
                <button type="button" class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 hover:shadow-lg transition-all active:scale-95">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Data Karyawan -->
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-transform duration-300">
        <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-wide text-rose-600 flex items-center gap-2">
                <x-tabler-alert-triangle class="w-6 h-6" />
                Hapus Data
            </h3>
            <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeDeleteModal()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>
        
        <div class="px-6 py-6">
            <p class="text-carbon-black-700 text-base font-medium text-center">
                Apakah Anda yakin ingin menghapus data karyawan <br> <span id="hapus_nama_karyawan" class="font-black text-carbon-black-900 text-lg mt-1 block"></span>
            </p>
            <p class="text-sm text-carbon-black-500 text-center mt-3">
                Operasi ini menghapus data secara permanen.
            </p>
        </div>
        
        <form method="POST" id="form-hapus">
            @csrf
            @method('DELETE')
            <div class="px-6 py-4 bg-rose-50 border-t border-rose-100 flex justify-end space-x-3 rounded-b-xl">
                <button type="button" class="px-5 py-2.5 bg-white border border-rose-200 text-rose-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-rose-100 transition-colors" onclick="closeDeleteModal()">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-rose-700 hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <x-tabler-trash class="w-4 h-4" />
                    Hapus
                </button>
            </div>
        </form>
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
        updateOpacity(); // Initial check

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
            }, 600); // 600ms delay to avoid reloading on every single keystroke instantly
        });

        // Preserve input focus and place cursor at the end after reload
        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    });

    // Modal Control & Formatting JS
    const modal = document.getElementById('modal-tambah');
    const modalInner = modal.querySelector('div');
    
    window.openModal = function() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalInner.classList.remove('scale-95');
            modalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeModal = function() {
        modal.classList.add('opacity-0');
        modalInner.classList.remove('scale-100');
        modalInner.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('form-tambah').reset();
        }, 300);
    }

    window.applyRekeningRule = function(bankSelect, rekInput) {
        const rules = {
            'BCA': 10,
            'BNI': 10,
            'BRI': 15,
            'Mandiri': 13,
            'BSI': 10,
            'CIMB': 13,
            'Permata': 10,
            'Danamon': 10,
            'BTN': 16
        };

        if (bankSelect.value === 'Tidak Memiliki' || !bankSelect.value) {
            rekInput.value = '';
            rekInput.disabled = true;
            rekInput.removeAttribute('required');
            rekInput.removeAttribute('pattern');
            rekInput.removeAttribute('minlength');
            rekInput.removeAttribute('maxlength');
            rekInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed', 'opacity-60');
            rekInput.placeholder = "";
            rekInput.oninput = null;
        } else {
            rekInput.disabled = false;
            rekInput.setAttribute('required', 'required');
            rekInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed', 'opacity-60');
            
            let len = rules[bankSelect.value];
            if (len) {
                rekInput.setAttribute('pattern', `\\d{${len}}`);
                rekInput.setAttribute('minlength', len);
                rekInput.setAttribute('maxlength', len);
                rekInput.placeholder = `${len} Digit Angka`;
                rekInput.value = rekInput.value.replace(/\D/g, '').substring(0, len);
                rekInput.oninput = function() {
                    this.value = this.value.replace(/\D/g, '').substring(0, len);
                };
            }
        }
    }

    // Logic for Bank & Rekening - Tambah
    document.addEventListener('DOMContentLoaded', function() {
        const bankSelect = document.getElementById('nama_bank');
        const rekInput = document.getElementById('no_rekening');
        
        if(bankSelect && rekInput) {
            bankSelect.addEventListener('change', function() {
                applyRekeningRule(bankSelect, rekInput);
            });
        }
    });

    // Detail Modal Control
    const detailModal = document.getElementById('modal-detail');
    const detailModalInner = detailModal.querySelector('div');
    
    window.openDetailModal = function(data) {
        // Populate data
        document.getElementById('detail-nik').textContent = data.nik || '-';
        document.getElementById('detail-nama').textContent = data.nama || '-';
        document.getElementById('detail-npwp').textContent = data.npwp || '-';
        document.getElementById('detail-notelp').textContent = data.no_telp || '-';
        document.getElementById('detail-bagian').textContent = data.bagian || '-';
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

        // Show modal
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

    // Modal Ubah Control
    const editModal = document.getElementById('modal-ubah');
    const editModalInner = editModal.querySelector('div');
    const editBankSelect = document.getElementById('edit_nama_bank');
    const editRekInput = document.getElementById('edit_no_rekening');
    
    if(editBankSelect && editRekInput) {
        editBankSelect.addEventListener('change', function() {
            applyRekeningRule(editBankSelect, editRekInput);
        });
    }

    window.openEditModal = function(data) {
        // Set action URL dynamically based on ID
        document.getElementById('form-ubah').action = '/karyawan/' + data.id;

        document.getElementById('edit_nik').value = data.nik || '';
        document.getElementById('edit_nama').value = data.nama || '';
        document.getElementById('edit_npwp').value = data.npwp || '';
        document.getElementById('edit_no_telp').value = data.no_telp !== '-' ? data.no_telp : '';
        document.getElementById('edit_bagian').value = data.bagian || 'Produksi';
        document.getElementById('edit_alamat').value = data.alamat || '';
        document.getElementById('edit_status').value = data.status || 'Aktif';

        if(data.nama_bank) {
            editBankSelect.value = data.nama_bank;
        } else {
            editBankSelect.value = 'Tidak Memiliki';
        }
        
        if (data.no_rekening && data.no_rekening !== '-') {
            editRekInput.value = data.no_rekening;
        } else {
            editRekInput.value = '';
        }

        // Trigger change event to initialize disabled state
        editBankSelect.dispatchEvent(new Event('change'));

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
        setTimeout(() => {
            editModal.classList.remove('opacity-0');
            editModalInner.classList.remove('scale-95');
            editModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeEditModal = function() {
        editModal.classList.add('opacity-0');
        editModalInner.classList.remove('scale-100');
        editModalInner.classList.add('scale-95');
        setTimeout(() => {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
            document.getElementById('form-ubah').reset();
        }, 300);
    }

    // Modal Hapus Control
    const deleteModal = document.getElementById('modal-hapus');
    const deleteModalInner = deleteModal.querySelector('div');
    
    window.openDeleteModal = function(id, nama) {
        document.getElementById('form-hapus').action = '/karyawan/' + id;
        document.getElementById('hapus_nama_karyawan').textContent = nama;
        
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        setTimeout(() => {
            deleteModal.classList.remove('opacity-0');
            deleteModalInner.classList.remove('scale-95');
            deleteModalInner.classList.add('scale-100');
        }, 10);
    }

    window.closeDeleteModal = function() {
        deleteModal.classList.add('opacity-0');
        deleteModalInner.classList.remove('scale-100');
        deleteModalInner.classList.add('scale-95');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        }, 300);
    }
</script>
@endpush
@endsection
