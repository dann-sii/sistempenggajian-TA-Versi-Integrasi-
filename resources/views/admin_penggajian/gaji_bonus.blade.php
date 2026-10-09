@extends('layouts.app')

@section('title', 'Data Gaji Bonus')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            DATA GAJI BONUS
        </h1>

        <div class="flex justify-between items-end gap-4">
            <form method="GET" action="{{ route('gaji_bonus.index') }}" class="flex-1 max-w-md">
                <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan nama bonus..." class="w-full px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-base font-medium" autocomplete="off">
                    <button type="submit" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                        <x-tabler-search class="w-4 h-4" />
                    </button>
                </div>
            </form>
            
            <button type="button" onclick="openTambahModal()" class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>

        @if(session('success'))
            <div id="toast-alert" class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 shadow-sm transition-opacity duration-500 opacity-100">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="toast-alert-error" class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700 shadow-sm transition-opacity duration-500 opacity-100">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">BATAS NORMAL</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">SATUAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TARIF</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">KETERANGAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @forelse($gajiBonus as $g)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">
                            @if($g['id'] == 2 && $g['is_default'])
                                200 - 250
                            @else
                                {{ number_format($g['batas_normal'], 0, ',', '.') }}
                            @endif
                            
                            @if($g['is_default'])
                                <div class="mt-1.5 flex items-center justify-center">
                                     @if(in_array($g['id'], [1, 2, 3, 4, 5, 6]))
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-hunter-green-50 text-hunter-green-700 border border-hunter-green-200">
                                            Sesuai Resep
                                        </span>
                                    @elseif($g['id'] == 8)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                            Target Aktivitas
                                        </span>
                                    @elseif($g['id'] == 7)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                            Hari Raya
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $g['satuan'] }}</td>
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
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $g['keterangan'] }}</td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" onclick="openEditModal({{ $g['id'] }}, '{{ addslashes($g['nama']) }}', '{{ $g['batas_normal'] }}', '{{ addslashes($g['satuan']) }}', '{{ number_format($g['tarif'], 2, '.', '') }}', '{{ addslashes($g['keterangan']) }}', {{ $g['is_default'] ? 'true' : 'false' }})" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                    <x-tabler-pencil class="w-4 h-4" />
                                    <span>Ubah</span>
                                </button>
                                
                                @if($g['is_default'])
                                    <!-- Toggle Status for Default Data -->
                                    <form action="{{ route('gaji_bonus.toggle_status', $g['id']) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return false;">
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
                                @else
                                    <!-- Delete for Custom Data -->
                                    <form action="{{ route('gaji_bonus.destroy', $g['id']) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return false;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ addslashes($g['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
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
                        <td colspan="8" class="px-6 py-10 text-center text-base font-semibold text-carbon-black-500">
                            @if(!empty($search))
                                Tidak ada data gaji bonus yang cocok dengan pencarian "{{ $search }}".
                            @else
                                Belum ada data gaji bonus.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Tarif -->
<div id="modal-tambah-tarif" class="fixed inset-0 z-[100] hidden items-center justify-center bg-carbon-black-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300 border border-carbon-black-100">
        <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-lg font-black uppercase tracking-widest text-carbon-black-900 flex items-center space-x-2">
                <x-tabler-plus class="w-6 h-6 text-hunter-green-600" stroke-width="2.5" />
                <span>Tambah Gaji Bonus</span>
            </h3>
            <button type="button" onclick="closeTambahModal()" class="text-carbon-black-400 hover:text-rose-600 transition-colors p-1 rounded-lg hover:bg-rose-50">
                <x-tabler-x class="w-5 h-5" stroke-width="2.5" />
            </button>
        </div>
        
        <form action="{{ route('gaji_bonus.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label for="nama" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Nama Bonus</label>
                <input type="text" name="nama" id="nama" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white" placeholder="Contoh: Bonus Kehadiran">
                @error('nama')
                    <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="batas_normal" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Batas Normal</label>
                    <input type="number" step="1" min="0" name="batas_normal" id="batas_normal" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white text-right font-mono" placeholder="0">
                    @error('batas_normal')
                        <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="satuan" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Satuan</label>
                    <input type="text" name="satuan" id="satuan" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white" placeholder="Contoh: Hari, Kali">
                    @error('satuan')
                        <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="tarif" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Tarif (Rp)</label>
                <input type="number" step="0.01" min="0" name="tarif" id="tarif" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white text-right font-mono text-lg" placeholder="0.00">
                @error('tarif')
                    <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="2" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white" placeholder="Keterangan opsional..."></textarea>
                @error('keterangan')
                    <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex justify-end space-x-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-carbon-black-600 hover:bg-carbon-black-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white bg-hunter-green-600 hover:bg-hunter-green-700 transition-colors flex items-center space-x-2 shadow-md active:scale-95">
                    <x-tabler-device-floppy class="w-4 h-4" />
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Tarif -->
<div id="modal-edit-tarif" class="fixed inset-0 z-[100] hidden items-center justify-center bg-carbon-black-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300 border border-carbon-black-100">
        <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-lg font-black uppercase tracking-widest text-carbon-black-900 flex items-center space-x-2">
                <x-tabler-pencil class="w-6 h-6 text-blue-600" stroke-width="2.5" />
                <span>Ubah Gaji Bonus</span>
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-carbon-black-400 hover:text-rose-600 transition-colors p-1 rounded-lg hover:bg-rose-50">
                <x-tabler-x class="w-5 h-5" stroke-width="2.5" />
            </button>
        </div>
        
        <form id="form-edit-tarif" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            
            <div id="edit_nama_group" class="hidden">
                <label for="edit_nama" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Nama Bonus</label>
                <input type="text" name="nama" id="edit_nama" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div id="edit_batas_normal_group" class="hidden">
                    <label for="edit_batas_normal" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Batas Normal</label>
                    <input type="number" step="1" min="0" name="batas_normal" id="edit_batas_normal" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white text-right font-mono">
                </div>
                <div id="edit_satuan_group" class="hidden">
                    <label for="edit_satuan" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Satuan</label>
                    <input type="text" name="satuan" id="edit_satuan" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white">
                </div>
            </div>

            <div id="readonly_info_group" class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 flex flex-col space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Nama:</span>
                    <span id="display_nama" class="text-base font-bold text-carbon-black-900"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Batas Normal:</span>
                    <span id="display_batas_normal" class="text-base font-bold text-carbon-black-900"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Satuan:</span>
                    <span id="display_satuan" class="text-base font-bold text-carbon-black-900"></span>
                </div>
                <div class="mt-2 text-xs text-blue-800 font-semibold leading-relaxed flex space-x-1.5 items-start">
                    <x-tabler-info-circle class="w-3.5 h-3.5 shrink-0 mt-0.5" />
                    <span>Data ini terintegrasi dengan rumus kalkulasi perusahaan. Anda hanya diizinkan mengubah Tarif dan Keterangan.</span>
                </div>
            </div>

            <div>
                <label for="edit_tarif" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Tarif (Rp)</label>
                <input type="number" step="0.01" min="0" name="tarif" id="edit_tarif" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white text-right font-mono text-lg">
            </div>

            <div>
                <label for="edit_keterangan" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" rows="2" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white"></textarea>
            </div>

            <div class="pt-2 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-carbon-black-600 hover:bg-carbon-black-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white bg-blue-600 hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-md active:scale-95">
                    <x-tabler-device-floppy class="w-4 h-4" />
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Status -->
<div id="modal-confirm-status" class="fixed inset-0 z-[110] hidden items-center justify-center bg-carbon-black-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform duration-300 border border-carbon-black-100 p-6 text-center">
        
        <div id="confirm-icon-warning" class="mx-auto w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mb-4 text-amber-600 hidden">
            <x-tabler-alert-triangle class="w-8 h-8" stroke-width="2.5" />
        </div>
        
        <div id="confirm-icon-success" class="mx-auto w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4 text-emerald-600 hidden">
            <x-tabler-check class="w-8 h-8" stroke-width="3" />
        </div>
        
        <div id="confirm-icon-danger" class="mx-auto w-16 h-16 rounded-full bg-rose-100 flex items-center justify-center mb-4 text-rose-600 hidden">
            <x-tabler-trash class="w-8 h-8" stroke-width="2.5" />
        </div>

        <h3 id="confirm-modal-title" class="text-xl font-black text-carbon-black-900 mb-2"></h3>
        <p id="confirm-modal-message" class="text-sm font-medium text-carbon-black-600 mb-6"></p>

        <div class="flex justify-center space-x-3">
            <button type="button" onclick="closeConfirmModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-carbon-black-600 bg-carbon-black-50 border border-carbon-black-200 hover:bg-carbon-black-100 transition-colors w-1/2">
                Batal
            </button>
            <button type="button" id="confirm-modal-btn" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white shadow-md active:scale-95 transition-all w-1/2">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Modal State Variables
        let formToSubmit = null;

        // Tambah Modal Logic
        const modalTambah = document.getElementById('modal-tambah-tarif');
        const modalTambahInner = modalTambah ? modalTambah.querySelector('div') : null;

        window.openTambahModal = function() {
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

        window.closeTambahModal = function() {
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

        // Edit Modal Logic
        const editModal = document.getElementById('modal-edit-tarif');
        const editModalInner = editModal ? editModal.querySelector('div') : null;
        const formEditTarif = document.getElementById('form-edit-tarif');
        const editNamaInput = document.getElementById('edit_nama');
        const editBatasNormalInput = document.getElementById('edit_batas_normal');
        const editSatuanInput = document.getElementById('edit_satuan');
        const editTarifInput = document.getElementById('edit_tarif');
        const editKeteranganInput = document.getElementById('edit_keterangan');

        const editNamaGroup = document.getElementById('edit_nama_group');
        const editBatasNormalGroup = document.getElementById('edit_batas_normal_group');
        const editSatuanGroup = document.getElementById('edit_satuan_group');
        const readonlyInfoGroup = document.getElementById('readonly_info_group');
        
        const displayNama = document.getElementById('display_nama');
        const displayBatasNormal = document.getElementById('display_batas_normal');
        const displaySatuan = document.getElementById('display_satuan');

        window.openEditModal = function(id, nama, batas_normal, satuan, tarif, keterangan, isDefault) {
            if (!editModal) return;
            
            formEditTarif.action = `/gaji-bonus/${id}`;
            editTarifInput.value = tarif;
            editKeteranganInput.value = keterangan;

            if (isDefault) {
                // For default data, only tarif and keterangan are editable
                editNamaGroup.classList.add('hidden');
                editBatasNormalGroup.classList.add('hidden');
                editSatuanGroup.classList.add('hidden');
                readonlyInfoGroup.classList.remove('hidden');

                displayNama.innerText = nama;
                displayBatasNormal.innerText = batas_normal;
                displaySatuan.innerText = satuan;

                editNamaInput.required = false;
                editBatasNormalInput.required = false;
                editSatuanInput.required = false;
            } else {
                // For custom data, everything is editable
                editNamaGroup.classList.remove('hidden');
                editBatasNormalGroup.classList.remove('hidden');
                editSatuanGroup.classList.remove('hidden');
                readonlyInfoGroup.classList.add('hidden');

                editNamaInput.value = nama;
                editBatasNormalInput.value = batas_normal;
                editSatuanInput.value = satuan;

                editNamaInput.required = true;
                editBatasNormalInput.required = true;
                editSatuanInput.required = true;
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

        // Confirm Modal Logic
        const confirmModal = document.getElementById('modal-confirm-status');
        const confirmModalInner = confirmModal ? confirmModal.querySelector('div') : null;
        const confirmTitle = document.getElementById('confirm-modal-title');
        const confirmMessage = document.getElementById('confirm-modal-message');
        const confirmBtn = document.getElementById('confirm-modal-btn');
        
        const iconWarning = document.getElementById('confirm-icon-warning');
        const iconSuccess = document.getElementById('confirm-icon-success');
        const iconDanger = document.getElementById('confirm-icon-danger');

        window.confirmToggleStatus = function(action, name, formElement) {
            formToSubmit = formElement;
            
            iconWarning.classList.add('hidden');
            iconSuccess.classList.add('hidden');
            iconDanger.classList.add('hidden');

            if (action === 'Nonaktifkan') {
                iconWarning.classList.remove('hidden');
                confirmTitle.innerText = 'Nonaktifkan Tarif?';
                confirmMessage.innerHTML = `Apakah Anda yakin ingin menonaktifkan tarif <strong>${name}</strong>?`;
                confirmBtn.className = 'px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white shadow-md active:scale-95 transition-all w-1/2 bg-amber-600 hover:bg-amber-700';
                confirmBtn.innerText = 'Ya, Nonaktifkan';
            } else {
                iconSuccess.classList.remove('hidden');
                confirmTitle.innerText = 'Aktifkan Tarif?';
                confirmMessage.innerHTML = `Apakah Anda yakin ingin mengaktifkan tarif <strong>${name}</strong>?`;
                confirmBtn.className = 'px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white shadow-md active:scale-95 transition-all w-1/2 bg-emerald-600 hover:bg-emerald-700';
                confirmBtn.innerText = 'Ya, Aktifkan';
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

        window.confirmDelete = function(name, formElement) {
            formToSubmit = formElement;
            
            iconWarning.classList.add('hidden');
            iconSuccess.classList.add('hidden');
            iconDanger.classList.remove('hidden');

            confirmTitle.innerText = 'Hapus Permanen?';
            confirmMessage.innerHTML = `Apakah Anda yakin ingin menghapus tarif <strong>${name}</strong> secara permanen? Tindakan ini tidak dapat dibatalkan.`;
            confirmBtn.className = 'px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white shadow-md active:scale-95 transition-all w-1/2 bg-rose-600 hover:bg-rose-700';
            confirmBtn.innerText = 'Ya, Hapus';

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

        // Toast Alert Auto-Dismiss Logic
        function dismissToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 500);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { dismissToast('toast-alert'); }, 3500);
            setTimeout(() => { dismissToast('toast-alert-error'); }, 3500);
        });

        @if($errors->any())
            openTambahModal();
        @endif
    </script>
@endpush
@endsection
