@extends('layouts.app')

@section('title', 'Data Potongan Gaji')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            DATA POTONGAN GAJI
        </h1>

        <div class="flex justify-between items-end">
            <!-- Search Bar -->
            <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                <input type="text" id="search" value="{{ request('search') }}" placeholder="Cari nama potongan..." class="w-64 px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-sm font-medium" autocomplete="off">
                <button id="search-btn" class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                    <x-tabler-search class="w-4 h-4" />
                </button>
            </div>

            <!-- Add Button -->
            <button onclick="openTambahModal()" class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>
    </div>

    <!-- Session Alerts -->
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm mb-6 flex items-start space-x-3" role="alert">
            <x-tabler-check class="w-5 h-5 flex-shrink-0 mt-0.5" />
            <p class="font-semibold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 text-rose-700 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm mb-6 flex items-start space-x-3" role="alert">
            <x-tabler-alert-triangle class="w-5 h-5 flex-shrink-0 mt-0.5" />
            <p class="font-semibold text-sm">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 text-rose-700 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm mb-6 flex items-start space-x-3" role="alert">
            <x-tabler-alert-circle class="w-5 h-5 flex-shrink-0 mt-0.5" />
            <div class="flex flex-col space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="font-semibold text-sm">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative">
        <table class="w-full border-collapse">
            <thead class="bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                <tr>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TARIF</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">TIPE POTONGAN</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                    <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-56">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-carbon-black-100">
                @foreach($allPotongan as $p)
                    <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                        <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-justify text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $p['nama'] }}</td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">
                            {{ $p['tipe_potongan'] === 'Persentase' ? number_format($p['tarif'], 2, ',', '') . '%' : 'Rp ' . number_format($p['tarif'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">{{ $p['tipe_potongan'] }}</td>
                        <td class="px-6 py-5 text-center text-base">
                            @php
                                $statusColor = match(strtolower($p['status'])) {
                                    'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'inaktif' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                {{ $p['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="openEditModal({{ $p['id'] }}, '{{ addslashes($p['nama']) }}', '{{ $p['tipe_potongan'] }}', '{{ number_format($p['tarif'], 2, '.', '') }}', {{ isset($p['is_default']) && $p['is_default'] ? 'true' : 'false' }})" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                    <x-tabler-pencil class="w-4 h-4" />
                                    <span>Ubah</span>
                                </button>
                                
                                @if(isset($p['is_default']) && $p['is_default'])
                                    <form action="{{ route('potongan_gaji.toggle_status', $p['id']) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        @if(strtolower($p['status']) === 'aktif')
                                            <button type="button" onclick="confirmToggleStatus('Nonaktifkan', '{{ addslashes($p['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Nonaktifkan">
                                                <x-tabler-power class="w-4 h-4" />
                                                <span>Nonaktifkan</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="confirmToggleStatus('Aktifkan', '{{ addslashes($p['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-emerald-200" title="Aktifkan">
                                                <x-tabler-power class="w-4 h-4" />
                                                <span>Aktifkan</span>
                                            </button>
                                        @endif
                                    </form>
                                @else
                                    <form action="{{ route('potongan_gaji.destroy', $p['id']) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmToggleStatus('Hapus', '{{ addslashes($p['nama']) }}', this.closest('form'))" class="flex items-center justify-center space-x-1.5 px-3 py-1.5 w-[140px] rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
                                            <x-tabler-trash class="w-4 h-4" />
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if(count($allPotongan) == 0)
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-carbon-black-500 font-medium font-semibold text-sm">
                            @if(!empty($search))
                                Tidak ada data potongan gaji yang cocok dengan pencarian "{{ $search }}".
                            @else
                                Belum ada data potongan gaji.
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Potongan -->
<div id="modal-tambah-potongan" class="fixed inset-0 z-[100] hidden items-center justify-center bg-carbon-black-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300 border border-carbon-black-100">
        <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-lg font-black uppercase tracking-widest text-carbon-black-900 flex items-center space-x-2">
                <x-tabler-plus class="w-6 h-6 text-hunter-green-600" stroke-width="2.5" />
                <span>Tambah Potongan Baru</span>
            </h3>
            <button onclick="closeTambahModal()" class="text-carbon-black-400 hover:text-rose-500 transition-colors outline-none">
                <x-tabler-x class="w-6 h-6" stroke-width="2.5" />
            </button>
        </div>
        
        <form action="{{ route('potongan_gaji.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label for="tambah_nama" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Nama Potongan</label>
                    <input type="text" name="nama" id="tambah_nama" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white placeholder-carbon-black-300" placeholder="Contoh: Potongan Koperasi">
                </div>

                <div>
                    <label for="tambah_tipe" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Tipe Potongan</label>
                    <select name="tipe_potongan" id="tambah_tipe" required class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white">
                        <option value="Tetap">Tetap</option>
                        <option value="Kondisional">Kondisional</option>
                        <option value="Persentase">Persentase</option>
                    </select>
                </div>

                <div>
                    <label id="label_tambah_tarif" for="tambah_tarif" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Nominal (Rp)</label>
                    <div class="relative flex items-center">
                        <span id="prefix_tambah_tarif" class="absolute left-4 text-carbon-black-500 font-bold">Rp</span>
                        <input type="number" step="0.01" min="0" name="tarif" id="tambah_tarif" required class="w-full pl-10 pr-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-hunter-green-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white text-right font-mono text-lg" placeholder="0.00">
                        <span id="suffix_tambah_tarif" class="absolute right-4 text-carbon-black-500 font-bold hidden">%</span>
                    </div>
                </div>
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

<!-- Modal Edit Potongan -->
<div id="modal-edit-potongan" class="fixed inset-0 z-[100] hidden items-center justify-center bg-carbon-black-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300 border border-carbon-black-100">
        <div class="bg-carbon-black-50 px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center">
            <h3 class="text-lg font-black uppercase tracking-widest text-carbon-black-900 flex items-center space-x-2">
                <x-tabler-pencil class="w-6 h-6 text-blue-600" stroke-width="2.5" />
                <span>Ubah Potongan Gaji</span>
            </h3>
            <button onclick="closeEditModal()" class="text-carbon-black-400 hover:text-rose-500 transition-colors outline-none">
                <x-tabler-x class="w-6 h-6" stroke-width="2.5" />
            </button>
        </div>
        
        <form id="edit-form" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            
            <div id="edit_input_group" class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label for="edit_nama" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Nama Potongan</label>
                    <input type="text" name="nama" id="edit_nama" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white">
                </div>

                <div>
                    <label for="edit_tipe" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Tipe Potongan</label>
                    <select name="tipe_potongan" id="edit_tipe" class="w-full px-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white">
                        <option value="Tetap">Tetap</option>
                        <option value="Kondisional">Kondisional</option>
                        <option value="Persentase">Persentase</option>
                    </select>
                </div>
            </div>

            <div id="readonly_info_group" class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 flex flex-col space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Nama Potongan:</span>
                    <span id="display_nama" class="text-sm font-black text-carbon-black-900"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">Tipe Potongan:</span>
                    <span id="display_tipe" class="text-sm font-black text-carbon-black-900"></span>
                </div>
                <div class="mt-2 text-[10px] text-blue-700 font-semibold uppercase tracking-widest leading-relaxed flex space-x-1.5 items-start">
                    <x-tabler-info-circle class="w-3.5 h-3.5 shrink-0 mt-0.5" />
                    <span>Data ini terintegrasi dengan rumus kalkulasi pusat. Anda hanya diizinkan mengubah Tarif.</span>
                </div>
            </div>

            <div>
                <label id="label_edit_tarif" for="edit_tarif" class="block text-xs font-bold uppercase tracking-widest text-carbon-black-600 mb-1">Nominal (Rp)</label>
                <div class="relative flex items-center">
                    <span id="prefix_edit_tarif" class="absolute left-4 text-carbon-black-500 font-bold">Rp</span>
                    <input type="number" step="0.01" min="0" name="tarif" id="edit_tarif" required class="w-full pl-10 pr-4 py-2 border-2 border-carbon-black-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 text-carbon-black-900 font-semibold transition-colors bg-white text-right font-mono text-lg">
                    <span id="suffix_edit_tarif" class="absolute right-4 text-carbon-black-500 font-bold hidden">%</span>
                </div>
            </div>

            <div class="pt-2 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-carbon-black-600 hover:bg-carbon-black-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white bg-blue-600 hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-md active:scale-95">
                    <x-tabler-device-floppy class="w-4 h-4" />
                    <span>Perbarui</span>
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
            <x-tabler-check class="w-8 h-8" stroke-width="2.5" />
        </div>
        <div id="confirm-icon-danger" class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4 text-red-600 hidden">
            <x-tabler-trash class="w-8 h-8" stroke-width="2.5" />
        </div>

        <h3 id="confirm-title" class="text-xl font-black uppercase tracking-widest text-carbon-black-900 mb-2">Konfirmasi</h3>
        <p class="text-sm text-carbon-black-500 font-medium mb-6">
            Anda yakin ingin <strong id="confirm-action-text" class="text-carbon-black-800"></strong> data <br><span id="confirm-nama-text" class="font-bold text-carbon-black-900 text-base"></span>?
        </p>

        <div class="flex justify-center space-x-3">
            <button type="button" onclick="closeConfirmModal()" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-carbon-black-600 hover:bg-carbon-black-100 transition-colors w-full border border-carbon-black-200">
                Batal
            </button>
            <button type="button" id="confirm-submit-btn" class="px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white transition-colors w-full shadow-md active:scale-95">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let activeConfirmForm = null;

    function openTambahModal() {
        const modal = document.getElementById('modal-tambah-potongan');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }

    function closeTambahModal() {
        const modal = document.getElementById('modal-tambah-potongan');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function openEditModal(id, nama, tipe, tarif, isDefault) {
        const modal = document.getElementById('modal-edit-potongan');
        const form = document.getElementById('edit-form');
        
        form.action = `/potongan-gaji/${id}`;
        document.getElementById('edit_tarif').value = tarif;
        
        const inputGroup = document.getElementById('edit_input_group');
        const readonlyInfo = document.getElementById('readonly_info_group');
        
        if (isDefault) {
            inputGroup.classList.add('hidden');
            readonlyInfo.classList.remove('hidden');
            
            document.getElementById('display_nama').innerText = nama;
            document.getElementById('display_tipe').innerText = tipe;
            
            document.getElementById('edit_nama').required = false;
            document.getElementById('edit_tipe').required = false;
        } else {
            inputGroup.classList.remove('hidden');
            readonlyInfo.classList.add('hidden');
            
            document.getElementById('edit_nama').value = nama;
            
            document.getElementById('edit_nama').required = true;
            document.getElementById('edit_tipe').required = true;
        }

        // Selalu set value edit_tipe agar listener change bisa mendeteksi nilai yang benar
        document.getElementById('edit_tipe').value = tipe;

        // trigger change event to update UI
        document.getElementById('edit_tipe').dispatchEvent(new Event('change'));

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }

    function closeEditModal() {
        const modal = document.getElementById('modal-edit-potongan');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function confirmToggleStatus(action, nama, formElement) {
        activeConfirmForm = formElement;
        
        const modal = document.getElementById('modal-confirm-status');
        const title = document.getElementById('confirm-title');
        const actionText = document.getElementById('confirm-action-text');
        const namaText = document.getElementById('confirm-nama-text');
        const submitBtn = document.getElementById('confirm-submit-btn');
        
        const iconWarning = document.getElementById('confirm-icon-warning');
        const iconSuccess = document.getElementById('confirm-icon-success');
        const iconDanger = document.getElementById('confirm-icon-danger');
        
        title.innerText = action + ' Potongan Gaji';
        actionText.innerText = action.toLowerCase();
        namaText.innerText = nama;
        
        if (action.toLowerCase() === 'nonaktifkan') {
            iconWarning.classList.remove('hidden');
            iconSuccess.classList.add('hidden');
            iconDanger.classList.add('hidden');
            submitBtn.className = 'px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white transition-colors w-full shadow-md active:scale-95 bg-amber-500 hover:bg-amber-600';
            submitBtn.innerText = 'Ya, Nonaktifkan';
        } else if (action.toLowerCase() === 'hapus') {
            iconWarning.classList.add('hidden');
            iconSuccess.classList.add('hidden');
            iconDanger.classList.remove('hidden');
            submitBtn.className = 'px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white transition-colors w-full shadow-md active:scale-95 bg-red-500 hover:bg-red-600';
            submitBtn.innerText = 'Ya, Hapus';
            actionText.innerText = 'menghapus secara permanen';
        } else {
            iconWarning.classList.add('hidden');
            iconSuccess.classList.remove('hidden');
            iconDanger.classList.add('hidden');
            submitBtn.className = 'px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-white transition-colors w-full shadow-md active:scale-95 bg-emerald-500 hover:bg-emerald-600';
            submitBtn.innerText = 'Ya, Aktifkan';
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('modal-confirm-status');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            activeConfirmForm = null;
        }, 300);
    }

    document.getElementById('confirm-submit-btn').addEventListener('click', function() {
        if (activeConfirmForm) {
            activeConfirmForm.submit();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');

        function triggerSearch() {
            let url = new URL(window.location.href);
            if (searchInput.value.trim()) {
                url.searchParams.set('search', searchInput.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }

        searchBtn.addEventListener('click', triggerSearch);
        
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                triggerSearch();
            }, 600);
        });

        if (searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        // Close alerts automatically
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-in-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);

        // Dynamic UI for Tipe Potongan (Tambah)
        const tambahTipe = document.getElementById('tambah_tipe');
        if (tambahTipe) {
            tambahTipe.addEventListener('change', function() {
                const isPersen = this.value === 'Persentase';
                document.getElementById('label_tambah_tarif').innerText = isPersen ? 'Persentase Potongan' : 'Nominal Potongan';
                document.getElementById('prefix_tambah_tarif').classList.toggle('hidden', isPersen);
                document.getElementById('suffix_tambah_tarif').classList.toggle('hidden', !isPersen);
                const input = document.getElementById('tambah_tarif');
                if (isPersen) {
                    input.max = "100";
                    input.classList.remove('pl-10');
                    input.classList.add('pl-4');
                } else {
                    input.removeAttribute('max');
                    input.classList.add('pl-10');
                    input.classList.remove('pl-4');
                }
            });
            // trigger on load
            tambahTipe.dispatchEvent(new Event('change'));
        }

        // Dynamic UI for Tipe Potongan (Edit)
        const editTipe = document.getElementById('edit_tipe');
        if (editTipe) {
            editTipe.addEventListener('change', function() {
                const isPersen = this.value === 'Persentase';
                document.getElementById('label_edit_tarif').innerText = isPersen ? 'Persentase Potongan' : 'Nominal Potongan';
                document.getElementById('prefix_edit_tarif').classList.toggle('hidden', isPersen);
                document.getElementById('suffix_edit_tarif').classList.toggle('hidden', !isPersen);
                const input = document.getElementById('edit_tarif');
                if (isPersen) {
                    input.max = "100";
                    input.classList.remove('pl-10');
                    input.classList.add('pl-4');
                } else {
                    input.removeAttribute('max');
                    input.classList.add('pl-10');
                    input.classList.remove('pl-4');
                }
            });
        }
    });
</script>
@endpush
@endsection
