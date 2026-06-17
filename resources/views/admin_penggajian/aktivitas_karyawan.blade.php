@extends('layouts.app')

@section('title', 'Aktivitas Karyawan')

@section('content')
<div class="space-y-8">
    <div class="flex items-start justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
                AKTIVITAS KARYAWAN
            </h1>
            <p class="mt-3 text-sm font-semibold text-carbon-black-500">
                Pencatatan aktivitas produksi harian berdasarkan produk dan jumlah yang dihasilkan.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg border-l-4 border-emerald-500 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border-l-4 border-rose-500 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('aktivitas_karyawan.store') }}" method="POST" class="grid items-start gap-8" style="grid-template-columns: 380px minmax(0, 1fr);">
        @csrf

        <!-- Search Karyawan -->
        <section class="rounded-xl border border-carbon-black-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Cari Karyawan</h2>
            <div class="mt-5 space-y-4">
                <div class="relative" id="karyawan-combobox">
                    <input type="hidden" name="karyawan_id" id="selected-karyawan-id" value="{{ old('karyawan_id', $lastAktivitas['karyawan_id'] ?? '') }}">
                    <input type="text" id="karyawan-search" placeholder="Cari nama karyawan..."
                        value="{{ old('karyawan_search', $lastAktivitas['nama'] ?? '') }}"
                        class="w-full rounded-xl border border-carbon-black-200 bg-carbon-black-50 px-4 py-3.5 pr-12 text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 transition-all hover:border-carbon-black-300 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20"
                        autocomplete="off">
                    <button type="button" id="karyawan-search-toggle"
                        class="absolute inset-y-0 right-2 my-auto inline-flex h-9 w-9 items-center justify-center rounded-lg text-carbon-black-400 transition-colors hover:bg-hunter-green-50 hover:text-hunter-green-600"
                        title="Cari karyawan">
                        <x-tabler-search class="h-4 w-4" />
                    </button>

                    <div id="karyawan-options"
                        class="absolute z-50 mt-2 hidden w-full overflow-hidden rounded-xl border border-carbon-black-100 bg-white shadow-xl">
                        <div id="karyawan-options-list" class="max-h-72 overflow-y-auto scrollbar-invisible py-2">
                            @foreach($activeKaryawans as $karyawan)
                                <button type="button"
                                    class="karyawan-option w-full px-4 py-3 text-left transition-colors hover:bg-hunter-green-50"
                                    data-karyawan-option
                                    data-karyawan-id="{{ $karyawan['id'] }}"
                                    data-karyawan-name="{{ $karyawan['nama'] }}"
                                    data-karyawan-nik="{{ $karyawan['nik'] }}"
                                    data-karyawan-bagian="{{ $karyawan['bagian'] ?? '-' }}">
                                    <span class="block text-sm font-black text-carbon-black-900">{{ $karyawan['nama'] }}</span>
                                    <span class="mt-0.5 block text-xs font-semibold text-carbon-black-400">NIK: {{ $karyawan['nik'] }}</span>
                                </button>
                            @endforeach
                        </div>
                        <div id="karyawan-no-results" class="hidden px-4 py-4 text-sm font-semibold text-carbon-black-400">
                            Karyawan tidak ditemukan.
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <input type="text" id="bagian-karyawan" readonly placeholder="Bagian Karyawan (otomatis)"
                        value="{{ old('bagian_karyawan', $lastAktivitas['bagian'] ?? '') }}"
                        class="w-full rounded-xl border border-carbon-black-200 bg-carbon-black-100 px-4 py-3.5 pr-11 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 focus:outline-none">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-blue-500">
                        <x-tabler-user class="h-4 w-4" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Input Aktivitas -->
        <section class="rounded-xl border border-carbon-black-100 bg-white p-6 shadow-sm">
            <div>
                <h2 class="text-lg font-black uppercase tracking-widest text-carbon-black-800">Input & Rekap Produksi Harian</h2>
                <p id="selected-karyawan-info" class="mt-2 text-sm font-semibold text-carbon-black-400">
                    @if($lastAktivitas)
                        {{ $lastAktivitas['nama'] }} - {{ $lastAktivitas['nik'] }} - {{ $lastAktivitas['bagian'] }}
                    @else
                        Pilih karyawan terlebih dahulu.
                    @endif
                </p>
            </div>

            <div class="mt-6 grid grid-cols-3 gap-4">
                <div>
                    <label for="tanggal" class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" placeholder="dd/mm/yyyy"
                        value="{{ old('tanggal', $lastAktivitas['tanggal'] ?? '') }}"
                        @if(!$lastAktivitas) disabled @endif
                        class="mt-2 w-full rounded-xl border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20 disabled:cursor-not-allowed disabled:bg-carbon-black-100 disabled:text-carbon-black-400">
                </div>
                <div>
                    <label for="nama-produk" class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Nama Produk</label>
                    <div class="relative mt-2" id="produk-combobox">
                        <input type="hidden" name="id_produk" id="selected-produk-id" value="{{ old('id_produk', $lastAktivitas['id_produk'] ?? '') }}">
                        <input type="text" id="nama-produk" placeholder="Cari nama produk..."
                            value="{{ old('produk_search', $lastAktivitas['nama_produk'] ?? '') }}"
                            @if(!$lastAktivitas) disabled @endif
                            class="w-full rounded-xl border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 pr-12 text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20 disabled:cursor-not-allowed disabled:bg-carbon-black-100 disabled:text-carbon-black-400"
                            autocomplete="off">
                        <button type="button" id="produk-search-toggle"
                            class="absolute inset-y-0 right-2 my-auto inline-flex h-9 w-9 items-center justify-center rounded-lg text-carbon-black-400 transition-colors hover:bg-hunter-green-50 hover:text-hunter-green-600"
                            @if(!$lastAktivitas) disabled @endif
                            title="Cari produk">
                            <x-tabler-search class="h-4 w-4" />
                        </button>

                        <div id="produk-options"
                            class="absolute z-40 mt-2 hidden w-full overflow-hidden rounded-xl border border-carbon-black-100 bg-white shadow-xl">
                            <div class="max-h-72 overflow-y-auto scrollbar-invisible py-2">
                                @foreach($produkResep as $produk)
                                    <button type="button"
                                        class="produk-option w-full px-4 py-3 text-left transition-colors hover:bg-hunter-green-50"
                                        data-produk-option
                                        data-produk-id="{{ $produk['id_produk'] }}"
                                        data-produk-name="{{ $produk['nama_produk'] }}"
                                        data-jumlah-standar="{{ $produk['jumlah_standar'] }}"
                                        data-satuan-produk="{{ $produk['satuan_produk'] }}"
                                        data-susu-liter="{{ $produk['susu_liter'] }}"
                                        data-gula-gram="{{ $produk['gula_gram'] }}"
                                        data-perasa-jenis="{{ $produk['perasa_jenis'] }}"
                                        data-perasa-gram="{{ $produk['perasa_gram'] }}">
                                        <span class="block text-sm font-black text-carbon-black-900">{{ $produk['nama_produk'] }}</span>
                                        <span class="mt-0.5 block text-xs font-semibold text-carbon-black-400">
                                            Standar {{ number_format($produk['jumlah_standar'], 0, ',', '.') }} {{ $produk['satuan_produk'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                            <div id="produk-no-results" class="hidden px-4 py-4 text-sm font-semibold text-carbon-black-400">
                                Produk tidak ditemukan.
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="jumlah-produksi" class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Jumlah Diproduksi</label>
                    <input type="number" id="jumlah-produksi" name="jumlah_produksi" min="0" step="1" placeholder="0"
                        value="{{ old('jumlah_produksi', $lastAktivitas['jumlah_produksi'] ?? '') }}"
                        @if(!$lastAktivitas) disabled @endif
                        class="mt-2 w-full rounded-xl border border-carbon-black-200 bg-carbon-black-50 px-4 py-3 text-sm font-semibold text-carbon-black-800 focus:border-hunter-green-500 focus:outline-none focus:ring-2 focus:ring-hunter-green-500/20 disabled:cursor-not-allowed disabled:bg-carbon-black-100 disabled:text-carbon-black-400">
                </div>
            </div>

            <div id="produk-resep-preview" class="mt-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3 text-sm font-semibold text-carbon-black-500">
                Pilih produk untuk melihat resep bahan baku otomatis.
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" id="save-activity-button" @if(!$lastAktivitas) disabled @endif
                    class="inline-flex h-12 items-center gap-2 rounded-xl bg-hunter-green-600 px-8 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-hunter-green-700 disabled:cursor-not-allowed disabled:opacity-40">
                    <x-tabler-device-floppy class="h-5 w-5" />
                    Simpan Aktivitas
                </button>
            </div>
        </section>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const karyawanCombobox = document.getElementById('karyawan-combobox');
        const searchInput = document.getElementById('karyawan-search');
        const searchToggle = document.getElementById('karyawan-search-toggle');
        const karyawanOptions = document.getElementById('karyawan-options');
        const karyawanNoResults = document.getElementById('karyawan-no-results');
        const karyawanButtons = Array.from(document.querySelectorAll('[data-karyawan-option]'));
        const selectedKaryawanId = document.getElementById('selected-karyawan-id');
        const selectedKaryawanInfo = document.getElementById('selected-karyawan-info');
        const bagianKaryawanInput = document.getElementById('bagian-karyawan');
        const tanggalInput = document.getElementById('tanggal');
        const produkCombobox = document.getElementById('produk-combobox');
        const produkSearch = document.getElementById('nama-produk');
        const produkSearchToggle = document.getElementById('produk-search-toggle');
        const produkOptions = document.getElementById('produk-options');
        const produkNoResults = document.getElementById('produk-no-results');
        const produkButtons = Array.from(document.querySelectorAll('[data-produk-option]'));
        const selectedProdukId = document.getElementById('selected-produk-id');
        const produkResepPreview = document.getElementById('produk-resep-preview');
        const jumlahProduksiInput = document.getElementById('jumlah-produksi');
        const saveButton = document.getElementById('save-activity-button');
        const latestActivities = @json($latestAktivitasByKaryawan);
        let selectedKaryawanName = @json(old('karyawan_search', $lastAktivitas['nama'] ?? ''));
        let selectedProdukName = @json(old('produk_search', $lastAktivitas['nama_produk'] ?? ''));

        function formatNumber(value) {
            return Number(value || 0).toLocaleString('id-ID');
        }

        function setActivityInputsEnabled(isEnabled) {
            [tanggalInput, produkSearch, produkSearchToggle, jumlahProduksiInput].forEach(element => {
                element.disabled = !isEnabled;
            });

            if (!isEnabled) {
                closeProdukOptions();
            }
        }

        function closeKaryawanOptions() {
            karyawanOptions.classList.add('hidden');
        }

        function filterKaryawanOptions() {
            const keyword = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            karyawanButtons.forEach(button => {
                const searchableText = `${button.dataset.karyawanName || ''} ${button.dataset.karyawanNik || ''}`.toLowerCase();
                const isVisible = searchableText.includes(keyword);
                button.classList.toggle('hidden', !isVisible);

                if (isVisible) {
                    visibleCount++;
                }
            });

            karyawanNoResults.classList.toggle('hidden', visibleCount > 0);
        }

        function openKaryawanOptions() {
            filterKaryawanOptions();
            karyawanOptions.classList.remove('hidden');
        }

        function resetSelectedKaryawanOnTyping() {
            if (!selectedKaryawanId.value) {
                return;
            }

            if (searchInput.value.trim().toLowerCase() === selectedKaryawanName.toLowerCase()) {
                return;
            }

            selectedKaryawanId.value = '';
            bagianKaryawanInput.value = '';
            tanggalInput.value = '';
            resetProdukSelection();
            jumlahProduksiInput.value = '';
            setActivityInputsEnabled(false);
            saveButton.disabled = true;
            selectedKaryawanInfo.textContent = 'Pilih karyawan terlebih dahulu.';
        }

        function closeProdukOptions() {
            produkOptions.classList.add('hidden');
        }

        function filterProdukOptions() {
            const keyword = produkSearch.value.trim().toLowerCase();
            let visibleCount = 0;

            produkButtons.forEach(button => {
                const isVisible = (button.dataset.produkName || '').toLowerCase().includes(keyword);
                button.classList.toggle('hidden', !isVisible);

                if (isVisible) {
                    visibleCount++;
                }
            });

            produkNoResults.classList.toggle('hidden', visibleCount > 0);
        }

        function openProdukOptions() {
            filterProdukOptions();
            produkOptions.classList.remove('hidden');
        }

        function resetProdukSelection() {
            selectedProdukId.value = '';
            selectedProdukName = '';
            produkSearch.value = '';
            renderProdukPreview(null);
        }

        function resetSelectedProdukOnTyping() {
            if (!selectedProdukId.value) {
                return;
            }

            if (produkSearch.value.trim().toLowerCase() === selectedProdukName.toLowerCase()) {
                return;
            }

            selectedProdukId.value = '';
            selectedProdukName = '';
            renderProdukPreview(null);
        }

        function getProdukButtonById(id) {
            return produkButtons.find(button => String(button.dataset.produkId) === String(id));
        }

        function getProdukButtonByName(name) {
            return produkButtons.find(button => (button.dataset.produkName || '').toLowerCase() === String(name || '').toLowerCase());
        }

        function selectProduk(button) {
            if (!button) {
                resetProdukSelection();
                return;
            }

            selectedProdukId.value = button.dataset.produkId || '';
            selectedProdukName = button.dataset.produkName || '';
            produkSearch.value = selectedProdukName;
            renderProdukPreview(button);
            closeProdukOptions();
        }

        function renderProdukPreview(button) {
            if (!button) {
                produkResepPreview.textContent = 'Pilih produk untuk melihat resep bahan baku otomatis.';
                return;
            }

            const jumlahStandar = Number(button.dataset.jumlahStandar || 0);
            const jumlahProduksi = Number(jumlahProduksiInput.value || 0);
            const faktor = jumlahStandar > 0 && jumlahProduksi > 0 ? jumlahProduksi / jumlahStandar : 1;
            const susu = Number(button.dataset.susuLiter || 0) * faktor;
            const gula = Number(button.dataset.gulaGram || 0) * faktor;
            const perasaJenis = button.dataset.perasaJenis || '-';
            const perasa = Number(button.dataset.perasaGram || 0) * faktor;

            produkResepPreview.textContent = `Resep otomatis: ${formatNumber(jumlahStandar)} ${button.dataset.satuanProduk} standar memakai ${formatNumber(button.dataset.susuLiter)} liter susu, ${formatNumber(button.dataset.gulaGram)} gram gula, dan ${formatNumber(button.dataset.perasaGram)} gram perasa ${perasaJenis}. Estimasi pemakaian input ini: ${formatNumber(susu)} liter susu, ${formatNumber(gula)} gram gula, ${formatNumber(perasa)} gram perasa ${perasaJenis}.`;
        }

        function selectKaryawan(button) {
            const id = button.dataset.karyawanId || '';
            const name = button.dataset.karyawanName || '';
            const nik = button.dataset.karyawanNik || '';
            const bagian = button.dataset.karyawanBagian || '-';
            const savedActivity = latestActivities[id] || null;

            selectedKaryawanId.value = id;
            selectedKaryawanName = name;
            searchInput.value = name;
            bagianKaryawanInput.value = bagian;
            selectedKaryawanInfo.textContent = name + ' - ' + nik + ' - ' + bagian;
            tanggalInput.value = savedActivity ? savedActivity.tanggal : '';
            jumlahProduksiInput.value = savedActivity ? savedActivity.jumlah_produksi : '';
            setActivityInputsEnabled(true);
            selectProduk(savedActivity
                ? getProdukButtonById(savedActivity.id_produk) || getProdukButtonByName(savedActivity.nama_produk)
                : null);
            saveButton.disabled = false;

            closeKaryawanOptions();
        }

        tanggalInput.addEventListener('click', function () {
            if (typeof tanggalInput.showPicker === 'function') {
                try {
                    tanggalInput.showPicker();
                } catch (error) {
                    tanggalInput.focus();
                }
            }
        });

        searchInput.addEventListener('focus', openKaryawanOptions);
        searchInput.addEventListener('click', openKaryawanOptions);
        searchInput.addEventListener('input', function () {
            resetSelectedKaryawanOnTyping();
            openKaryawanOptions();
        });

        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeKaryawanOptions();
                return;
            }

            if (event.key === 'Enter') {
                const firstVisibleOption = karyawanButtons.find(button => !button.classList.contains('hidden'));
                if (firstVisibleOption) {
                    event.preventDefault();
                    selectKaryawan(firstVisibleOption);
                }
            }
        });

        searchToggle.addEventListener('click', function () {
            searchInput.focus();
            openKaryawanOptions();
        });

        produkSearch.addEventListener('focus', openProdukOptions);
        produkSearch.addEventListener('click', openProdukOptions);
        produkSearch.addEventListener('input', function () {
            resetSelectedProdukOnTyping();
            openProdukOptions();
        });

        produkSearch.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeProdukOptions();
                return;
            }

            if (event.key === 'Enter') {
                const firstVisibleOption = produkButtons.find(button => !button.classList.contains('hidden'));
                if (firstVisibleOption) {
                    event.preventDefault();
                    selectProduk(firstVisibleOption);
                }
            }
        });

        produkSearchToggle.addEventListener('click', function () {
            produkSearch.focus();
            openProdukOptions();
        });

        produkButtons.forEach(button => {
            button.addEventListener('click', function () {
                selectProduk(this);
            });
        });

        jumlahProduksiInput.addEventListener('input', function () {
            renderProdukPreview(getProdukButtonById(selectedProdukId.value));
        });

        karyawanButtons.forEach(button => {
            button.addEventListener('click', function () {
                selectKaryawan(this);
            });
        });

        document.addEventListener('click', function (event) {
            if (!karyawanCombobox.contains(event.target)) {
                closeKaryawanOptions();
            }

            if (!produkCombobox.contains(event.target)) {
                closeProdukOptions();
            }
        });

        if (selectedProdukId.value) {
            selectProduk(getProdukButtonById(selectedProdukId.value));
        } else if (produkSearch.value.trim()) {
            selectProduk(getProdukButtonByName(produkSearch.value.trim()));
        }

        setActivityInputsEnabled(Boolean(selectedKaryawanId.value));

    });
</script>
@endpush
@endsection
