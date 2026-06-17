@extends('layouts.app')

@section('title', 'Penggajian Karyawan')

@section('content')
    <div class="h-[calc(125vh-10rem)] overflow-hidden flex flex-col gap-8">
        <!-- Page Header -->
        <div class="flex-shrink-0">
            <h1
                class="text-4xl font-black uppercase tracking-tighter text-carbon-black-900 border-l-8 border-hunter-green-500 pl-4 py-1">
                PENGGAJIAN KARYAWAN
            </h1>
        </div>

        <!-- Main Content: Side-by-side Layout -->
        <div class="flex flex-1 min-h-0 gap-8">
            <!-- Left Column: Form Inputs -->
            <div class="w-[420px] flex flex-col gap-4 flex-shrink-0 bg-white p-6 rounded-2xl border border-carbon-black-100 shadow-sm overflow-hidden">
                <!-- Periode Bulan & Tahun -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Periode Bulan -->
                    <div class="relative">
                        <select id="periode-bulan-select"
                            class="w-full appearance-none px-4 py-3.5 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-xl hover:border-carbon-black-300">
                            @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $currentMonthIndex = date('n') - 1;
                            @endphp
                            @foreach($months as $index => $month)
                                <option value="{{ $month }}" {{ $index === $currentMonthIndex ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-carbon-black-400">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>
                    <!-- Periode Tahun (Infinite Scroll Custom Dropdown) -->
                    <div class="relative" id="year-dropdown-container">
                        <!-- Hidden input to store the actual value -->
                        <input type="hidden" name="periode_tahun" id="periode-tahun-input" value="{{ date('Y') }}">
                        
                        <!-- Dropdown Button -->
                        <button type="button" id="year-dropdown-button"
                            class="w-full flex items-center justify-between px-4 py-3.5 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold cursor-pointer rounded-xl hover:border-carbon-black-300">
                            <span id="year-dropdown-text" class="text-carbon-black-800">{{ date('Y') }}</span>
                            <x-tabler-chevron-down class="h-4 w-4 text-carbon-black-400 pointer-events-none" />
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="year-dropdown-menu" 
                            class="hidden absolute z-50 w-full mt-2 bg-white border border-carbon-black-100 rounded-xl shadow-lg overflow-hidden flex flex-col">
                            <ul id="year-dropdown-list" class="max-h-60 overflow-y-auto custom-scrollbar py-2">
                                <!-- Years will be generated here by JS -->
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Nama Karyawan -->
                <div class="relative" id="karyawan-combobox">
                    <input type="hidden" name="karyawan_id" id="karyawan-select">
                    <input type="text" id="karyawan-search" placeholder="Cari nama karyawan..."
                        class="w-full px-4 py-3.5 pr-12 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-xl hover:border-carbon-black-300"
                        autocomplete="off">
                    <button type="button" id="karyawan-search-toggle"
                        class="absolute inset-y-0 right-2 my-auto h-9 w-9 inline-flex items-center justify-center rounded-lg text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors"
                        title="Cari karyawan">
                        <x-tabler-search class="h-4 w-4" />
                    </button>
                    <div id="karyawan-options"
                        class="hidden absolute z-50 w-full mt-2 bg-white border border-carbon-black-100 rounded-xl shadow-xl overflow-hidden">
                        <div id="karyawan-options-list" class="max-h-64 overflow-y-auto custom-scrollbar py-2">
                            @if(isset($karyawans))
                                @foreach($karyawans as $karyawan)
                                    @if($karyawan['status'] === 'Aktif')
                                        <button type="button"
                                            class="karyawan-option w-full px-4 py-3 text-left hover:bg-hunter-green-50 transition-colors"
                                            data-karyawan-option
                                            data-karyawan-id="{{ $karyawan['id'] }}"
                                            data-karyawan-name="{{ $karyawan['nama'] }}"
                                            data-karyawan-nik="{{ $karyawan['nik'] }}">
                                            <span class="block text-sm font-semibold text-carbon-black-900">{{ $karyawan['nama'] }}</span>
                                            <span class="block mt-0.5 text-xs font-semibold text-carbon-black-400">NIK: {{ $karyawan['nik'] }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div id="karyawan-no-results" class="hidden px-4 py-4 text-sm font-semibold text-carbon-black-400">
                            Karyawan tidak ditemukan.
                        </div>
                    </div>
                </div>

                <!-- Bagian Karyawan (auto-fill dari Data Karyawan) -->
                <div class="relative">
                    <input type="text" id="input-bagian-karyawan" name="bagian_karyawan" placeholder="Bagian Karyawan (otomatis)"
                        class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                        readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 text-blue-500">
                        <x-tabler-user class="h-4 w-4" />
                    </div>
                </div>

                <!-- Jumlah Hari Kerja (auto-fill dari Presensi) -->
                <div class="relative">
                    <input type="text" id="input-hari-kerja" name="jumlah_hari_kerja" placeholder="Jumlah Hari Kerja (otomatis)"
                        class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                        readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 text-hunter-green-500">
                        <x-tabler-calendar-check class="h-4 w-4" />
                    </div>
                </div>

                <!-- Gaji Pokok (auto-fill dari Data Gaji Pokok) -->
                <div class="relative">
                    <input type="text" id="input-gaji-pokok" name="gaji_pokok" placeholder="Gaji Pokok (otomatis)"
                        class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                        readonly>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 text-hunter-green-500">
                        <x-tabler-coins class="h-4 w-4" />
                    </div>
                </div>

                <!-- Lembur + Lamanya -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="text" id="input-lembur" name="lembur" placeholder="Lembur (otomatis)"
                            class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                            readonly>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 text-hunter-green-500">
                            <x-tabler-clock-play class="h-4 w-4" />
                        </div>
                    </div>
                    <input type="text" id="input-lamanya-lembur" name="jam_lembur" placeholder="Lamanya (otomatis)"
                        class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                        readonly>
                </div>

                <!-- Bonus -->
                <div class="relative">
                    <label for="bonus-select"
                        class="pointer-events-none absolute left-4 top-2 z-10 text-[10px] font-black uppercase tracking-widest text-carbon-black-400">
                        Bonus
                    </label>
                    <select id="bonus-select" name="bonus_status"
                        class="w-full appearance-none px-4 pt-6 pb-2.5 bg-carbon-black-100 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-500 cursor-not-allowed rounded-xl hover:border-carbon-black-300"
                        disabled>
                        <option value="Tidak Ada" selected>Tidak Ada</option>
                        <option value="Ada">Ada</option>
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>

                <!-- Jenis Bonus + Pemakaian -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="text" id="bonus-summary" name="bonus_summary" placeholder="Jenis Bonus"
                            class="w-full px-4 py-3.5 pr-12 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                            readonly>
                        <button type="button" id="edit-bonus-selection"
                            class="hidden absolute inset-y-0 right-2 my-auto h-8 w-8 items-center justify-center rounded-lg text-hunter-green-600 hover:bg-hunter-green-50 transition-colors disabled:pointer-events-none disabled:opacity-40"
                            title="Edit bonus" disabled>
                            <x-tabler-pencil class="h-4 w-4" />
                        </button>
                    </div>
                    <input type="text" id="bonus-total-display" name="bonus_total" placeholder="Pemakaian"
                        class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                        readonly>
                </div>

                <!-- Potongan -->
                <div class="relative">
                    <label for="potongan-select"
                        class="pointer-events-none absolute left-4 top-2 z-10 text-[10px] font-black uppercase tracking-widest text-carbon-black-400">
                        Potongan
                    </label>
                    <select id="potongan-select" name="potongan_status"
                        class="w-full appearance-none px-4 pt-6 pb-2.5 bg-carbon-black-100 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-500 cursor-not-allowed rounded-xl hover:border-carbon-black-300"
                        disabled>
                        <option value="Tidak Ada" selected>Tidak Ada</option>
                        <option value="Ada">Ada</option>
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-carbon-black-400">
                        <x-tabler-chevron-down class="h-4 w-4" />
                    </div>
                </div>

                <!-- Jenis Potongan + Total Potongan -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="text" id="potongan-summary" name="potongan_summary" placeholder="Jenis Potongan"
                            class="w-full px-4 py-3.5 pr-12 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                            readonly>
                        <button type="button" id="edit-potongan-selection"
                            class="hidden absolute inset-y-0 right-2 my-auto h-8 w-8 items-center justify-center rounded-lg text-rose-600 hover:bg-rose-50 transition-colors disabled:pointer-events-none disabled:opacity-40"
                            title="Edit potongan" disabled>
                            <x-tabler-pencil class="h-4 w-4" />
                        </button>
                    </div>
                    <input type="text" id="potongan-total-display" name="potongan_total" placeholder="Total Potongan"
                        class="w-full px-4 py-3.5 bg-carbon-black-100 border border-carbon-black-200 text-sm font-semibold text-carbon-black-600 placeholder-carbon-black-400 rounded-xl cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Right Column: Summary Box -->
            <div class="flex-1 min-h-0 flex flex-col gap-6">
                <!-- Total Gaji Card -->
                <div class="bg-white border border-carbon-black-100 rounded-2xl p-8 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow flex-1 min-h-0 flex flex-col">
                    <p class="text-xs font-black text-carbon-black-400 uppercase tracking-widest mb-2 relative z-10">Total Gaji</p>
                    <h3 class="text-4xl md:text-5xl font-black tracking-tight text-carbon-black-900 relative z-10 mt-2">
                        Rp <span id="total-gaji">-</span>
                    </h3>
                    <div id="salary-detail-list" class="relative z-10 mt-6 min-h-0 flex-1 space-y-2 overflow-y-auto pr-2 text-sm font-semibold text-carbon-black-600 custom-scrollbar">
                        <div class="rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3 text-carbon-black-400">
                            Pilih karyawan untuk melihat rincian gaji.
                        </div>
                    </div>
                </div>

                <!-- Total Gaji Bersih Card -->
                <div class="bg-gradient-to-br from-black-forest-900 to-black-forest-800 rounded-2xl p-8 shadow-xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-10">
                        <x-tabler-cash-banknote class="w-32 h-32 text-white" />
                    </div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <div class="relative z-10 flex items-end justify-between gap-6">
                        <div>
                            <p class="text-xs font-black text-white/60 uppercase tracking-widest mb-2">Total Gaji Bersih</p>
                            <h3 class="text-3xl font-black tracking-tight text-white">
                                Rp <span id="total-gaji-bersih">-</span>
                            </h3>
                        </div>
                        <button
                            class="bg-gradient-to-r from-hunter-green-500 to-hunter-green-600 text-white px-10 h-12 text-sm font-black rounded-xl shadow-[0_8px_20px_rgba(98,164,91,0.3)] hover:shadow-[0_8px_25px_rgba(98,164,91,0.5)] hover:-translate-y-0.5 transition-all duration-300 active:scale-95 uppercase tracking-widest flex items-center gap-2 shrink-0">
                            <x-tabler-check class="w-5 h-5" />
                            <span>PROSES PENGGAJIAN</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bonus -->
    <div id="bonus-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Isian Bonus Karyawan</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Pilih jenis bonus, lalu isi jumlah atau selisih sesuai ketentuan bonus.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closeBonusModal(false)">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div id="bonus-rows" class="space-y-3"></div>
                <button type="button" id="add-bonus-row"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-hunter-green-200 bg-hunter-green-50 text-hunter-green-700 text-xs font-black uppercase tracking-widest hover:bg-hunter-green-100 transition-colors">
                    <x-tabler-plus class="w-4 h-4" />
                    Tambah Bonus
                </button>
            </div>

            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Bonus</p>
                    <p class="text-xl font-black text-carbon-black-900">Rp <span id="bonus-modal-total">0</span></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors" onclick="closeBonusModal(false)">Batal</button>
                    <button type="button" id="save-bonus-modal" class="px-5 py-2.5 bg-hunter-green-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-hunter-green-700 hover:shadow-lg transition-all active:scale-95">Simpan Bonus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Potongan -->
    <div id="potongan-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="px-6 py-5 border-b border-carbon-black-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-wide text-carbon-black-900">Isian Potongan Gaji</h3>
                    <p class="text-xs font-semibold text-carbon-black-500 mt-1">Pilih jenis potongan dari data master Potongan Gaji.</p>
                </div>
                <button type="button" class="text-carbon-black-400 hover:text-rose-600 transition-colors" onclick="closePotonganModal(false)">
                    <x-tabler-x class="w-6 h-6" />
                </button>
            </div>

            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div id="potongan-rows" class="space-y-3"></div>
                <button type="button" id="add-potongan-row"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 text-xs font-black uppercase tracking-widest hover:bg-rose-100 transition-colors">
                    <x-tabler-plus class="w-4 h-4" />
                    Tambah Potongan
                </button>
            </div>

            <div class="px-6 py-4 bg-carbon-black-50 border-t border-carbon-black-100 flex items-center justify-between rounded-b-xl">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-carbon-black-500">Total Potongan</p>
                    <p class="text-xl font-black text-carbon-black-900">Rp <span id="potongan-modal-total">0</span></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-5 py-2.5 border border-carbon-black-300 text-carbon-black-600 text-sm font-bold uppercase tracking-wider rounded-lg hover:bg-carbon-black-100 transition-colors" onclick="closePotonganModal(false)">Batal</button>
                    <button type="button" id="save-potongan-modal" class="px-5 py-2.5 bg-rose-600 text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow-md hover:bg-rose-700 hover:shadow-lg transition-all active:scale-95">Simpan Potongan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('year-dropdown-button');
            const menu = document.getElementById('year-dropdown-menu');
            const list = document.getElementById('year-dropdown-list');
            const text = document.getElementById('year-dropdown-text');
            const input = document.getElementById('periode-tahun-input');
            
            let startYear = new Date().getFullYear() - 5;
            let endYear = new Date().getFullYear() + 5;
            let isOpen = false;

            function renderYears(prepend = false) {
                const fragment = document.createDocumentFragment();
                let loopStart, loopEnd;
                
                if (prepend) {
                    loopStart = startYear - 5;
                    loopEnd = startYear - 1;
                    startYear = loopStart;
                } else {
                    loopStart = endYear + 1;
                    loopEnd = endYear + 5;
                    endYear = loopEnd; 
                }

                for (let y = loopStart; y <= loopEnd; y++) {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2.5 hover:bg-carbon-black-50 text-carbon-black-800 cursor-pointer text-sm font-medium transition-colors';
                    li.textContent = y;
                    li.dataset.value = y;
                    li.addEventListener('click', function(e) {
                        e.stopPropagation();
                        text.textContent = y;
                        text.classList.remove('text-carbon-black-400');
                        text.classList.add('text-carbon-black-800');
                        input.value = y;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        closeMenu();
                    });
                    fragment.appendChild(li);
                }
                
                if (prepend) {
                    const firstChild = list.firstChild;
                    list.insertBefore(fragment, firstChild);
                } else {
                    list.appendChild(fragment);
                }
            }

            // Initial render
            for (let y = startYear; y <= endYear; y++) {
                const li = document.createElement('li');
                li.className = 'px-4 py-2.5 hover:bg-carbon-black-50 text-carbon-black-800 cursor-pointer text-sm font-medium transition-colors';
                li.textContent = y;
                li.dataset.value = y;
                li.addEventListener('click', function(e) {
                    e.stopPropagation();
                    text.textContent = y;
                    text.classList.remove('text-carbon-black-400');
                    text.classList.add('text-carbon-black-800');
                    input.value = y;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    closeMenu();
                });
                list.appendChild(li);
            }

            function toggleMenu() {
                isOpen = !isOpen;
                if (isOpen) {
                    menu.classList.remove('hidden');
                    // Scroll to middle roughly
                    list.scrollTop = (list.scrollHeight - list.clientHeight) / 2;
                } else {
                    menu.classList.add('hidden');
                }
            }

            function closeMenu() {
                isOpen = false;
                menu.classList.add('hidden');
            }

            button.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu();
            });

            document.addEventListener('click', function(e) {
                if (!document.getElementById('year-dropdown-container').contains(e.target)) {
                    closeMenu();
                }
            });

            list.addEventListener('scroll', function() {
                if (list.scrollTop === 0) {
                    const oldScrollHeight = list.scrollHeight;
                    renderYears(true);
                    list.scrollTop = list.scrollHeight - oldScrollHeight;
                } else if (list.scrollTop + list.clientHeight >= list.scrollHeight - 5) {
                    renderYears(false);
                }
            });

            // ── Auto-fill Hari Kerja & Gaji Pokok saat pilih karyawan ──
            const karyawanData = @json($karyawanLookup ?? []);

            function formatRupiah(angka) {
                return 'Rp ' + Number(angka).toLocaleString('id-ID');
            }

            const karyawanSelect = document.getElementById('karyawan-select');
            const karyawanCombobox = document.getElementById('karyawan-combobox');
            const karyawanSearch = document.getElementById('karyawan-search');
            const karyawanSearchToggle = document.getElementById('karyawan-search-toggle');
            const karyawanOptions = document.getElementById('karyawan-options');
            const karyawanOptionButtons = Array.from(document.querySelectorAll('[data-karyawan-option]'));
            const karyawanNoResults = document.getElementById('karyawan-no-results');
            const periodeBulanSelect = document.getElementById('periode-bulan-select');
            const periodeTahunInput = document.getElementById('periode-tahun-input');
            const inputBagianKaryawan = document.getElementById('input-bagian-karyawan');
            const inputHariKerja = document.getElementById('input-hari-kerja');
            const inputGajiPokok = document.getElementById('input-gaji-pokok');
            const inputLembur = document.getElementById('input-lembur');
            const inputLamanyaLembur = document.getElementById('input-lamanya-lembur');
            const totalGaji = document.getElementById('total-gaji');
            const totalGajiBersih = document.getElementById('total-gaji-bersih');
            const salaryDetailList = document.getElementById('salary-detail-list');
            const bonusSelect = document.getElementById('bonus-select');
            const bonusSummary = document.getElementById('bonus-summary');
            const editBonusSelectionButton = document.getElementById('edit-bonus-selection');
            const bonusTotalDisplay = document.getElementById('bonus-total-display');
            const bonusModal = document.getElementById('bonus-modal');
            const bonusModalInner = bonusModal.querySelector('div');
            const bonusRows = document.getElementById('bonus-rows');
            const addBonusRowButton = document.getElementById('add-bonus-row');
            const saveBonusModalButton = document.getElementById('save-bonus-modal');
            const bonusModalTotal = document.getElementById('bonus-modal-total');
            const bonusData = @json($gajiBonus ?? []);
            const potonganSelect = document.getElementById('potongan-select');
            const potonganSummary = document.getElementById('potongan-summary');
            const editPotonganSelectionButton = document.getElementById('edit-potongan-selection');
            const potonganTotalDisplay = document.getElementById('potongan-total-display');
            const potonganModal = document.getElementById('potongan-modal');
            const potonganModalInner = potonganModal.querySelector('div');
            const potonganRows = document.getElementById('potongan-rows');
            const addPotonganRowButton = document.getElementById('add-potongan-row');
            const savePotonganModalButton = document.getElementById('save-potongan-modal');
            const potonganModalTotal = document.getElementById('potongan-modal-total');
            const potonganData = @json($potonganGaji ?? []);

            let activeKaryawanData = null;
            let selectedKaryawanLabel = '';
            let currentBaseTotal = 0;
            let currentBonusTotal = 0;
            let currentPotonganTotal = 0;
            let currentBonusDetails = [];
            let currentPotonganDetails = [];
            let savedBonusCount = 0;
            let savedPotonganCount = 0;

            function setAdjustmentSelectsEnabled(isEnabled) {
                [bonusSelect, potonganSelect].forEach(select => {
                    select.disabled = !isEnabled;
                    select.classList.toggle('bg-carbon-black-50', isEnabled);
                    select.classList.toggle('text-carbon-black-800', isEnabled);
                    select.classList.toggle('cursor-pointer', isEnabled);
                    select.classList.toggle('bg-carbon-black-100', !isEnabled);
                    select.classList.toggle('text-carbon-black-500', !isEnabled);
                    select.classList.toggle('cursor-not-allowed', !isEnabled);
                });
            }

            function toggleEditButton(button, isVisible) {
                button.disabled = !isVisible;
                button.classList.toggle('hidden', !isVisible);
                button.classList.toggle('inline-flex', isVisible);
            }

            function formatNumber(value) {
                return Number(value).toLocaleString('id-ID');
            }

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function detailRow(title, description, amount, tone = 'neutral') {
                const isZero = Number(amount) <= 0;
                const amountClass = isZero ? 'text-carbon-black-500' : (tone === 'deduction' ? 'text-rose-600' : 'text-hunter-green-700');
                const prefix = tone === 'deduction' && !isZero ? '- ' : '';

                return `
                    <div class="flex items-start justify-between gap-4 rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3">
                        <div>
                            <p class="font-black text-carbon-black-800">${escapeHtml(title)}</p>
                            <p class="mt-1 text-xs font-semibold text-carbon-black-400">${escapeHtml(description)}</p>
                        </div>
                        <p class="shrink-0 text-right font-black ${amountClass}">${prefix}${formatRupiah(amount)}</p>
                    </div>
                `;
            }

            function renderSalaryDetails() {
                if (!activeKaryawanData) {
                    salaryDetailList.innerHTML = `
                        <div class="rounded-xl border border-carbon-black-100 bg-carbon-black-50 px-4 py-3 text-carbon-black-400">
                            Pilih karyawan untuk melihat rincian gaji.
                        </div>
                    `;
                    return;
                }

                const hariKerja = Number(activeKaryawanData.hari_kerja || 0);
                const tarifPokok = Number(activeKaryawanData.gaji_pokok || 0);
                const satuanGajiPokok = activeKaryawanData.satuan_gaji_pokok || 'Jam';
                const totalGajiPokok = Number(activeKaryawanData.total_gaji_pokok || 0);
                const jamLembur = Number(activeKaryawanData.jam_lembur || 0);
                const tarifLembur = Number(activeKaryawanData.tarif_lembur || 0);
                const totalLembur = Number(activeKaryawanData.total_lembur || 0);
                const gajiPokokDetail = satuanGajiPokok === 'Hari'
                    ? `${formatNumber(hariKerja)} hari x ${formatRupiah(tarifPokok)}`
                    : `${formatNumber(hariKerja)} hari x 8 jam x ${formatRupiah(tarifPokok)}`;

                const rows = [
                    detailRow(
                        'Gaji Pokok',
                        gajiPokokDetail,
                        totalGajiPokok
                    ),
                    detailRow(
                        'Lembur',
                        activeKaryawanData.lembur_aktif === false
                            ? 'Tidak berlaku untuk bagian Peternakan.'
                            : jamLembur > 0
                            ? `${formatNumber(jamLembur)} jam x ${formatRupiah(tarifLembur)}`
                            : 'Tidak ada jam lembur pada periode ini.',
                        totalLembur
                    ),
                ];

                if (currentBonusDetails.length > 0) {
                    currentBonusDetails.forEach(detail => {
                        rows.push(detailRow(detail.title, detail.description, detail.amount));
                    });
                } else {
                    rows.push(detailRow('Bonus', 'Tidak ada bonus yang dihitung.', 0));
                }

                if (currentPotonganDetails.length > 0) {
                    currentPotonganDetails.forEach(detail => {
                        rows.push(detailRow(detail.title, detail.description, detail.amount, 'deduction'));
                    });
                } else {
                    rows.push(detailRow('Potongan', 'Tidak ada potongan yang dihitung.', 0, 'deduction'));
                }

                salaryDetailList.innerHTML = rows.join('');
            }

            function updateSalaryTotals() {
                const total = currentBaseTotal + currentBonusTotal;
                const totalBersih = Math.max(0, total - currentPotonganTotal);
                totalGaji.textContent = total > 0 ? Number(total).toLocaleString('id-ID') : '-';
                totalGajiBersih.textContent = totalBersih > 0 ? Number(totalBersih).toLocaleString('id-ID') : '-';
                renderSalaryDetails();
            }

            function resetBonusState() {
                currentBonusTotal = 0;
                currentBonusDetails = [];
                savedBonusCount = 0;
                bonusSelect.value = 'Tidak Ada';
                bonusSummary.value = '-';
                bonusTotalDisplay.value = formatRupiah(0);
                bonusRows.innerHTML = '';
                toggleEditButton(editBonusSelectionButton, false);
                updateSalaryTotals();
            }

            function getSelectedPeriodKey() {
                const month = String((periodeBulanSelect?.selectedIndex ?? new Date().getMonth()) + 1).padStart(2, '0');
                const year = periodeTahunInput?.value || new Date().getFullYear();
                return `${year}-${month}`;
            }

            function applyAutomaticProductionBonus() {
                if (!activeKaryawanData) {
                    return;
                }

                const periodBonus = activeKaryawanData.bonus_produksi_by_period?.[getSelectedPeriodKey()] || {
                    total: 0,
                    details: [],
                };

                currentBonusTotal = Number(periodBonus.total || 0);
                currentBonusDetails = periodBonus.details || [];
                savedBonusCount = currentBonusDetails.length;
                bonusRows.innerHTML = '';

                if (savedBonusCount > 0 && currentBonusTotal > 0) {
                    bonusSelect.value = 'Ada';
                    bonusSummary.value = savedBonusCount + ' bonus otomatis';
                    bonusTotalDisplay.value = formatRupiah(currentBonusTotal);
                    toggleEditButton(editBonusSelectionButton, true);
                } else {
                    bonusSelect.value = 'Tidak Ada';
                    bonusSummary.value = '-';
                    bonusTotalDisplay.value = formatRupiah(0);
                    toggleEditButton(editBonusSelectionButton, false);
                }

                updateSalaryTotals();
            }

            function resetPotonganState() {
                currentPotonganTotal = 0;
                currentPotonganDetails = [];
                savedPotonganCount = 0;
                potonganSelect.value = 'Tidak Ada';
                potonganSummary.value = '-';
                potonganTotalDisplay.value = formatRupiah(0);
                potonganRows.innerHTML = '';
                toggleEditButton(editPotonganSelectionButton, false);
                updateSalaryTotals();
            }

            function getSelectedBonus(row) {
                const select = row.querySelector('.bonus-type');
                return bonusData.find(item => String(item.id) === String(select.value));
            }

            function formatUsageValue(value, satuan) {
                return Number(value).toLocaleString('id-ID') + ' ' + satuan;
            }

            function updateBonusRow(row) {
                const bonus = getSelectedBonus(row);
                const usageInput = row.querySelector('.bonus-usage');
                const usageHint = row.querySelector('.bonus-usage-hint');
                const subtotalText = row.querySelector('.bonus-subtotal');

                if (!bonus) {
                    usageInput.value = '';
                    usageInput.disabled = true;
                    usageHint.textContent = 'Pilih jenis bonus terlebih dahulu.';
                    subtotalText.textContent = 'Rp 0';
                    return;
                }

                const satuan = bonus.satuan.toLowerCase();
                const isAttendanceBonus = satuan === 'hari';
                const isFixedBonus = satuan === 'kali';
                usageInput.disabled = false;
                usageInput.placeholder = isAttendanceBonus
                    ? 'Otomatis dari hari kerja'
                    : (isFixedBonus ? 'Otomatis' : 'Selisih ' + satuan);

                if (isAttendanceBonus) {
                    const hariKerja = Number(activeKaryawanData?.hari_kerja ?? 0);
                    usageInput.value = Math.max(0, hariKerja - Number(bonus.batas_normal));
                    usageInput.readOnly = true;
                    usageInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                    usageHint.textContent = 'Bonus kehadiran dihitung otomatis jika hadir lebih dari ' + bonus.batas_normal + ' hari.';
                } else if (isFixedBonus) {
                    usageInput.value = 1;
                    usageInput.readOnly = true;
                    usageInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                    usageHint.textContent = 'Bonus ini dihitung 1 kali sesuai nominal tarif yang tercatat.';
                } else {
                    usageInput.readOnly = false;
                    usageInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed');
                    usageHint.textContent = 'Isi selisih di atas batas normal ' + formatUsageValue(bonus.batas_normal, bonus.satuan) + '.';
                }

                const usage = Number(usageInput.value || 0);
                subtotalText.textContent = formatRupiah(usage * Number(bonus.tarif));
                updateBonusModalTotal();
            }

            function updateBonusModalTotal() {
                let total = 0;
                bonusRows.querySelectorAll('.bonus-row').forEach(row => {
                    const bonus = getSelectedBonus(row);
                    const usage = Number(row.querySelector('.bonus-usage').value || 0);
                    if (bonus) {
                        total += usage * Number(bonus.tarif);
                    }
                });

                bonusModalTotal.textContent = Number(total).toLocaleString('id-ID');
                return total;
            }

            function collectBonusDetails() {
                const details = [];

                bonusRows.querySelectorAll('.bonus-row').forEach(row => {
                    const bonus = getSelectedBonus(row);
                    const usage = Number(row.querySelector('.bonus-usage').value || 0);

                    if (!bonus || usage <= 0) {
                        return;
                    }

                    const amount = usage * Number(bonus.tarif);
                    const isFixedBonus = bonus.satuan.toLowerCase() === 'kali';
                    details.push({
                        title: bonus.nama,
                        description: isFixedBonus
                            ? `Dihitung 1 kali sebesar ${formatRupiah(bonus.tarif)}`
                            : `Sejumlah ${formatUsageValue(usage, bonus.satuan)} x ${formatRupiah(bonus.tarif)}`,
                        amount,
                    });
                });

                return details;
            }

            function getSelectedIds(container, selectClass, exceptSelect = null) {
                return Array.from(container.querySelectorAll(selectClass))
                    .filter(select => select !== exceptSelect && select.value)
                    .map(select => String(select.value));
            }

            function refreshUniqueOptions(container, selectClass, data, placeholderText, createOptionsCallback) {
                container.querySelectorAll(selectClass).forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = `
                        <option value="" disabled ${currentValue ? '' : 'selected'}>${placeholderText}</option>
                        ${createOptionsCallback(currentValue, select)}
                    `;
                    select.value = currentValue;
                });
            }

            function refreshBonusOptions() {
                refreshUniqueOptions(bonusRows, '.bonus-type', bonusData, 'Jenis Bonus', createBonusOptions);
            }

            function createBonusOptions(selectedId = '', currentSelect = null) {
                const selectedIds = getSelectedIds(bonusRows, '.bonus-type', currentSelect);
                return bonusData.map(item => {
                    if (selectedIds.includes(String(item.id)) && String(item.id) !== String(selectedId)) {
                        return '';
                    }

                    const selected = String(item.id) === String(selectedId) ? 'selected' : '';
                    return `<option value="${item.id}" ${selected}>${item.nama}</option>`;
                }).join('');
            }

            function addBonusRow(selectedId = '') {
                const row = document.createElement('div');
                row.className = 'bonus-row grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-white border border-carbon-black-100 rounded-xl p-3 shadow-sm';
                row.innerHTML = `
                    <div class="relative">
                        <select class="bonus-type w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                            <option value="" disabled ${selectedId ? '' : 'selected'}>Jenis Bonus</option>
                            ${createBonusOptions(selectedId)}
                        </select>
                        <p class="bonus-usage-hint text-[11px] font-semibold text-carbon-black-400 mt-1">Pilih jenis bonus terlebih dahulu.</p>
                    </div>
                    <input type="number" min="0" step="0.01" class="bonus-usage w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-hunter-green-500 focus:ring-2 focus:ring-hunter-green-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg" placeholder="Jumlah" disabled>
                    <div class="px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 rounded-lg text-sm font-black text-carbon-black-800">
                        <span class="bonus-subtotal">Rp 0</span>
                    </div>
                    <button type="button" class="remove-bonus-row h-11 w-11 inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 transition-colors" title="Kurangi bonus">
                        <x-tabler-minus class="w-4 h-4" />
                    </button>
                `;

                bonusRows.appendChild(row);
                row.querySelector('.bonus-type').addEventListener('change', () => {
                    updateBonusRow(row);
                    refreshBonusOptions();
                });
                row.querySelector('.bonus-usage').addEventListener('input', () => updateBonusRow(row));
                row.querySelector('.remove-bonus-row').addEventListener('click', () => {
                    row.remove();
                    if (!bonusRows.querySelector('.bonus-row')) {
                        addBonusRow();
                    }
                    refreshBonusOptions();
                    updateBonusModalTotal();
                });

                if (selectedId) {
                    updateBonusRow(row);
                }

                refreshBonusOptions();
            }

            function openBonusModal() {
                if (!bonusRows.querySelector('.bonus-row')) {
                    addBonusRow();
                }

                bonusModal.classList.remove('hidden');
                bonusModal.classList.add('flex');
                setTimeout(() => {
                    bonusModal.classList.remove('opacity-0');
                    bonusModalInner.classList.remove('scale-95');
                    bonusModalInner.classList.add('scale-100');
                }, 10);
            }

            window.closeBonusModal = function(keepSelection = true) {
                bonusModal.classList.add('opacity-0');
                bonusModalInner.classList.remove('scale-100');
                bonusModalInner.classList.add('scale-95');

                setTimeout(() => {
                    bonusModal.classList.add('hidden');
                    bonusModal.classList.remove('flex');
                    if (!keepSelection && savedBonusCount === 0) {
                        bonusSelect.value = 'Tidak Ada';
                        resetBonusState();
                    }
                }, 300);
            }

            addBonusRowButton.addEventListener('click', () => addBonusRow());
            saveBonusModalButton.addEventListener('click', function() {
                currentBonusTotal = updateBonusModalTotal();
                currentBonusDetails = collectBonusDetails();
                savedBonusCount = bonusRows.querySelectorAll('.bonus-row').length;
                bonusSummary.value = savedBonusCount + ' Bonus dipilih';
                bonusTotalDisplay.value = formatRupiah(currentBonusTotal);
                toggleEditButton(editBonusSelectionButton, savedBonusCount > 0);
                updateSalaryTotals();
                closeBonusModal(true);
            });

            editBonusSelectionButton.addEventListener('click', function() {
                if (!activeKaryawanData || savedBonusCount === 0) {
                    return;
                }

                bonusSelect.value = 'Ada';
                openBonusModal();
            });

            bonusSelect.addEventListener('change', function() {
                if (this.value === 'Ada') {
                    openBonusModal();
                    return;
                }

                if (this.value === 'Tidak Ada') {
                    resetBonusState();
                }
            });

            function getSelectedPotongan(row) {
                const select = row.querySelector('.potongan-type');
                return potonganData.find(item => String(item.id) === String(select.value));
            }

            function updatePotonganRow(row) {
                const potongan = getSelectedPotongan(row);
                const jumlahInput = row.querySelector('.potongan-jumlah');
                const hint = row.querySelector('.potongan-hint');
                const subtotalText = row.querySelector('.potongan-subtotal');

                if (!potongan) {
                    jumlahInput.value = '';
                    jumlahInput.disabled = true;
                    hint.textContent = 'Pilih jenis potongan terlebih dahulu.';
                    subtotalText.textContent = 'Rp 0';
                    return;
                }

                const isFixed = potongan.tipe_potongan.toLowerCase() === 'tetap';
                jumlahInput.disabled = false;

                if (isFixed) {
                    jumlahInput.value = 1;
                    jumlahInput.readOnly = true;
                    jumlahInput.classList.add('bg-carbon-black-100', 'cursor-not-allowed');
                    hint.textContent = 'Potongan tetap dihitung 1 kali per proses penggajian.';
                } else {
                    jumlahInput.readOnly = false;
                    jumlahInput.classList.remove('bg-carbon-black-100', 'cursor-not-allowed');
                    jumlahInput.placeholder = 'Jumlah kejadian';
                    hint.textContent = 'Isi jumlah kejadian/pemakaian untuk potongan kondisional.';
                }

                const jumlah = Number(jumlahInput.value || 0);
                subtotalText.textContent = formatRupiah(jumlah * Number(potongan.tarif));
                updatePotonganModalTotal();
            }

            function updatePotonganModalTotal() {
                let total = 0;
                potonganRows.querySelectorAll('.potongan-row').forEach(row => {
                    const potongan = getSelectedPotongan(row);
                    const jumlah = Number(row.querySelector('.potongan-jumlah').value || 0);
                    if (potongan) {
                        total += jumlah * Number(potongan.tarif);
                    }
                });

                potonganModalTotal.textContent = Number(total).toLocaleString('id-ID');
                return total;
            }

            function collectPotonganDetails() {
                const details = [];

                potonganRows.querySelectorAll('.potongan-row').forEach(row => {
                    const potongan = getSelectedPotongan(row);
                    const jumlah = Number(row.querySelector('.potongan-jumlah').value || 0);

                    if (!potongan || jumlah <= 0) {
                        return;
                    }

                    const isFixed = potongan.tipe_potongan.toLowerCase() === 'tetap';
                    const amount = jumlah * Number(potongan.tarif);

                    details.push({
                        title: potongan.nama,
                        description: isFixed
                            ? `Potongan tetap sebesar ${formatRupiah(potongan.tarif)}`
                            : `${formatNumber(jumlah)} kejadian x ${formatRupiah(potongan.tarif)}`,
                        amount,
                    });
                });

                return details;
            }

            function refreshPotonganOptions() {
                refreshUniqueOptions(potonganRows, '.potongan-type', potonganData, 'Jenis Potongan', createPotonganOptions);
            }

            function createPotonganOptions(selectedId = '', currentSelect = null) {
                const selectedIds = getSelectedIds(potonganRows, '.potongan-type', currentSelect);
                return potonganData.map(item => {
                    if (selectedIds.includes(String(item.id)) && String(item.id) !== String(selectedId)) {
                        return '';
                    }

                    const selected = String(item.id) === String(selectedId) ? 'selected' : '';
                    return `<option value="${item.id}" ${selected}>${item.nama}</option>`;
                }).join('');
            }

            function addPotonganRow(selectedId = '') {
                const row = document.createElement('div');
                row.className = 'potongan-row grid grid-cols-[1fr_180px_150px_44px] gap-3 items-start bg-white border border-carbon-black-100 rounded-xl p-3 shadow-sm';
                row.innerHTML = `
                    <div class="relative">
                        <select class="potongan-type w-full appearance-none px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm font-semibold text-carbon-black-800 cursor-pointer rounded-lg">
                            <option value="" disabled ${selectedId ? '' : 'selected'}>Jenis Potongan</option>
                            ${createPotonganOptions(selectedId)}
                        </select>
                        <p class="potongan-hint text-[11px] font-semibold text-carbon-black-400 mt-1">Pilih jenis potongan terlebih dahulu.</p>
                    </div>
                    <input type="number" min="0" step="1" class="potongan-jumlah w-full px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm font-semibold text-carbon-black-800 placeholder-carbon-black-400 rounded-lg" placeholder="Jumlah" disabled>
                    <div class="px-4 py-3 bg-carbon-black-50 border border-carbon-black-200 rounded-lg text-sm font-black text-carbon-black-800">
                        <span class="potongan-subtotal">Rp 0</span>
                    </div>
                    <button type="button" class="remove-potongan-row h-11 w-11 inline-flex items-center justify-center rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 transition-colors" title="Kurangi potongan">
                        <x-tabler-minus class="w-4 h-4" />
                    </button>
                `;

                potonganRows.appendChild(row);
                row.querySelector('.potongan-type').addEventListener('change', () => {
                    updatePotonganRow(row);
                    refreshPotonganOptions();
                });
                row.querySelector('.potongan-jumlah').addEventListener('input', () => updatePotonganRow(row));
                row.querySelector('.remove-potongan-row').addEventListener('click', () => {
                    row.remove();
                    if (!potonganRows.querySelector('.potongan-row')) {
                        addPotonganRow();
                    }
                    refreshPotonganOptions();
                    updatePotonganModalTotal();
                });

                if (selectedId) {
                    updatePotonganRow(row);
                }

                refreshPotonganOptions();
            }

            function openPotonganModal() {
                if (!potonganRows.querySelector('.potongan-row')) {
                    addPotonganRow();
                }

                potonganModal.classList.remove('hidden');
                potonganModal.classList.add('flex');
                setTimeout(() => {
                    potonganModal.classList.remove('opacity-0');
                    potonganModalInner.classList.remove('scale-95');
                    potonganModalInner.classList.add('scale-100');
                }, 10);
            }

            window.closePotonganModal = function(keepSelection = true) {
                potonganModal.classList.add('opacity-0');
                potonganModalInner.classList.remove('scale-100');
                potonganModalInner.classList.add('scale-95');

                setTimeout(() => {
                    potonganModal.classList.add('hidden');
                    potonganModal.classList.remove('flex');
                    if (!keepSelection && savedPotonganCount === 0) {
                        potonganSelect.value = 'Tidak Ada';
                        resetPotonganState();
                    }
                }, 300);
            }

            addPotonganRowButton.addEventListener('click', () => addPotonganRow());
            savePotonganModalButton.addEventListener('click', function() {
                currentPotonganTotal = updatePotonganModalTotal();
                currentPotonganDetails = collectPotonganDetails();
                savedPotonganCount = potonganRows.querySelectorAll('.potongan-row').length;
                potonganSummary.value = savedPotonganCount + ' Potongan dipilih';
                potonganTotalDisplay.value = formatRupiah(currentPotonganTotal);
                toggleEditButton(editPotonganSelectionButton, savedPotonganCount > 0);
                updateSalaryTotals();
                closePotonganModal(true);
            });

            editPotonganSelectionButton.addEventListener('click', function() {
                if (!activeKaryawanData || savedPotonganCount === 0) {
                    return;
                }

                potonganSelect.value = 'Ada';
                openPotonganModal();
            });

            potonganSelect.addEventListener('change', function() {
                if (this.value === 'Ada') {
                    openPotonganModal();
                    return;
                }

                if (this.value === 'Tidak Ada') {
                    resetPotonganState();
                }
            });

            function openKaryawanOptions() {
                if (!karyawanOptions) {
                    return;
                }

                filterKaryawanOptions();
                karyawanOptions.classList.remove('hidden');
            }

            function closeKaryawanOptions() {
                if (!karyawanOptions) {
                    return;
                }

                karyawanOptions.classList.add('hidden');
            }

            function filterKaryawanOptions() {
                const query = (karyawanSearch.value || '').trim().toLowerCase();
                let visibleCount = 0;

                karyawanOptionButtons.forEach(button => {
                    const searchableText = `${button.dataset.karyawanName || ''} ${button.dataset.karyawanNik || ''}`.toLowerCase();
                    const isVisible = searchableText.includes(query);
                    button.classList.toggle('hidden', !isVisible);

                    if (isVisible) {
                        visibleCount++;
                    }
                });

                if (karyawanNoResults) {
                    karyawanNoResults.classList.toggle('hidden', visibleCount > 0);
                }
            }

            function resetSelectedKaryawanOnTyping() {
                if (!karyawanSelect.value) {
                    return;
                }

                const currentText = (karyawanSearch.value || '').trim().toLowerCase();
                if (currentText === selectedKaryawanLabel.toLowerCase()) {
                    return;
                }

                karyawanSelect.value = '';
                selectedKaryawanLabel = '';
                karyawanSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            function selectKaryawan(button) {
                const name = button.dataset.karyawanName || '';
                const nik = button.dataset.karyawanNik || '';

                selectedKaryawanLabel = `${name} (${nik})`;
                karyawanSearch.value = selectedKaryawanLabel;
                karyawanSelect.value = button.dataset.karyawanId || '';
                closeKaryawanOptions();
                karyawanSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }

            if (karyawanSearch) {
                karyawanSearch.addEventListener('focus', openKaryawanOptions);
                karyawanSearch.addEventListener('input', function() {
                    resetSelectedKaryawanOnTyping();
                    openKaryawanOptions();
                });

                karyawanSearch.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeKaryawanOptions();
                        return;
                    }

                    if (event.key === 'Enter') {
                        const firstVisibleOption = karyawanOptionButtons.find(button => !button.classList.contains('hidden'));
                        if (firstVisibleOption) {
                            event.preventDefault();
                            selectKaryawan(firstVisibleOption);
                        }
                    }
                });
            }

            if (karyawanSearchToggle) {
                karyawanSearchToggle.addEventListener('click', function() {
                    karyawanSearch.focus();
                    openKaryawanOptions();
                });
            }

            karyawanOptionButtons.forEach(button => {
                button.addEventListener('click', () => selectKaryawan(button));
            });

            document.addEventListener('click', function(event) {
                if (!karyawanCombobox || karyawanCombobox.contains(event.target)) {
                    return;
                }

                closeKaryawanOptions();
            });

            if (karyawanSelect) {
                karyawanSelect.addEventListener('change', function() {
                    const selectedId = this.value;
                    const data = karyawanData[selectedId];
                    activeKaryawanData = data || null;

                    if (data) {
                        setAdjustmentSelectsEnabled(true);
                        inputBagianKaryawan.value = data.bagian || '-';
                        inputHariKerja.value = data.hari_kerja + ' hari';
                        inputGajiPokok.value = formatRupiah(data.gaji_pokok) + ' / ' + String(data.satuan_gaji_pokok || 'Jam').toLowerCase();
                        inputLembur.value = data.lembur;
                        inputLamanyaLembur.value = data.lembur_aktif === false
                            ? '-'
                            : Number(data.jam_lembur).toLocaleString('id-ID') + ' jam';

                        const totalGajiPokok = Number(data.total_gaji_pokok);
                        const totalLembur = Number(data.total_lembur);
                        currentBaseTotal = totalGajiPokok + totalLembur;

                        applyAutomaticProductionBonus();

                        // Animasi highlight
                        [inputBagianKaryawan, inputHariKerja, inputGajiPokok, inputLembur, inputLamanyaLembur].forEach(el => {
                            el.classList.add('border-hunter-green-400', 'bg-hunter-green-50');
                            setTimeout(() => {
                                el.classList.remove('border-hunter-green-400', 'bg-hunter-green-50');
                            }, 1500);
                        });
                    } else {
                        inputHariKerja.value = '';
                        inputBagianKaryawan.value = '';
                        inputGajiPokok.value = '';
                        inputLembur.value = '';
                        inputLamanyaLembur.value = '';
                        currentBaseTotal = 0;
                        activeKaryawanData = null;
                        resetBonusState();
                        resetPotonganState();
                        setAdjustmentSelectsEnabled(false);
                    }
                });
            }

            if (periodeBulanSelect) {
                periodeBulanSelect.addEventListener('change', applyAutomaticProductionBonus);
            }

            if (periodeTahunInput) {
                periodeTahunInput.addEventListener('change', applyAutomaticProductionBonus);
            }

            resetBonusState();
            resetPotonganState();
            setAdjustmentSelectsEnabled(false);
        });
    </script>
    @endpush
@endsection
