@extends('layouts.app')

@section('title', 'Data Gaji Pokok & Lembur')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="space-y-6">
        <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
            DATA GAJI POKOK & LEMBUR
        </h1>

        <div class="flex justify-end items-end">
            <button type="button" onclick="openTarifModal()" class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 shadow-sm">
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
                    @foreach($gajiPokok as $g)
                        <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                            <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">{{ $g['id'] }}</td>
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
                                    <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat">
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
                    @foreach($gajiLembur as $g)
                        <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                            <td class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">{{ $g['id'] }}</td>
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
                                    <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat">
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

    @if($errors->any())
        openTarifModal();
    @endif
</script>
@endpush
@endsection
