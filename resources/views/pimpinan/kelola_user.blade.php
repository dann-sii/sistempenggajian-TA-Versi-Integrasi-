@extends('layouts.app')

@section('title', 'KELOLA PENGGUNA')

@section('content')
    <div class="flex flex-col space-y-8 overflow-hidden">
        <!-- Page Header -->
        <div class="space-y-6">
            <h1
                class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
                KELOLA PENGGUNA
            </h1>

            <div class="flex justify-between items-end">
                <div class="flex items-end space-x-4">
                    <!-- Status Filter -->
                    <div
                        class="relative min-w-40 shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden flex items-center">
                        <div class="pl-3 text-carbon-black-400">
                            <x-tabler-filter class="h-4 w-4" />
                        </div>
                        <select id="status-filter"
                            class="w-full appearance-none bg-transparent pl-2 pr-10 py-2 text-carbon-black-300 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="inaktif" {{ request('status') === 'inaktif' ? 'selected' : '' }}>Inaktif</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100 ml-2">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div
                        class="relative min-w-48 shadow-sm rounded-lg border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white overflow-hidden flex items-center">
                        <div class="pl-3 text-carbon-black-400">
                            <x-tabler-users class="h-4 w-4" />
                        </div>
                        <select id="role-filter"
                            class="w-full appearance-none bg-transparent pl-2 pr-10 py-2 text-carbon-black-300 font-bold uppercase tracking-wide text-xs cursor-pointer focus:outline-none">
                            <option value="">Semua Role</option>
                            <option value="admin_penggajian" {{ request('role') === 'admin_penggajian' ? 'selected' : '' }}>
                                Admin Penggajian</option>
                            <option value="pimpinan" {{ request('role') === 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-carbon-black-400 border-l border-carbon-black-100 ml-2">
                            <x-tabler-chevron-down class="h-4 w-4" />
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div
                        class="flex shadow-sm rounded-lg overflow-hidden border-2 border-carbon-black-200 focus-within:border-hunter-green-400 transition-colors bg-white">
                        <input type="text" id="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau username..."
                            class="w-72 px-4 py-2 focus:outline-none text-carbon-black-800 placeholder-carbon-black-300 text-sm font-medium"
                            autocomplete="off">
                        <button id="search-btn"
                            class="bg-carbon-black-50 px-4 py-2 border-l border-carbon-black-200 text-carbon-black-400 hover:text-hunter-green-600 hover:bg-hunter-green-50 transition-colors">
                            <x-tabler-search class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <button onclick="openAddModal()"
                    class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                    <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                    <span>Tambah Pengguna</span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm" id="toast-success">
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
            <script>setTimeout(() => { let el = document.getElementById('toast-success'); if (el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.style.display = 'none', 500); } }, 3000);</script>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm" id="toast-error">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-tabler-alert-circle class="h-5 w-5 text-rose-500" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-rose-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
            <script>setTimeout(() => { let el = document.getElementById('toast-error'); if (el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.style.display = 'none', 500); } }, 3000);</script>
        @endif

        <!-- Data Table Container -->
        <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative flex-1 min-h-0">
            <div class="max-h-[calc(125vh-24rem)] overflow-y-auto scrollbar-invisible">
                <table class="w-full border-collapse">
                    <thead
                        class="sticky top-0 z-10 bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                        <tr>
                            <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center w-20">NO</th>
                            <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">NAMA LENGKAP</th>
                            <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">USERNAME</th>
                            <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">ROLE</th>
                            <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                            <th class="px-6 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-carbon-black-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                                <td
                                    class="px-6 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-20">
                                    {{ $loop->iteration }}</td>
                                <td class="px-6 py-5 text-center text-base font-black text-carbon-black-900">{{ $user['nama'] }}
                                </td>
                                <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">
                                    {{ $user['username'] }}</td>
                                <td class="px-6 py-5 text-center text-base font-semibold text-carbon-black-800">
                                    @php
                                        $roleDisplay = str_replace('_', ' ', $user['role']);
                                        $roleColor = match ($user['role']) {
                                            'admin_penggajian' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'pimpinan' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $roleColor }}">
                                        {{ $roleDisplay }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center text-base">
                                    @php
                                        $statusColor = match ($user['status']) {
                                            'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Inaktif' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" onclick='openEditModal(@json($user))'
                                            class="w-24 flex justify-center items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200"
                                            title="Edit">
                                            <x-tabler-pencil class="w-4 h-4" />
                                            <span>Ubah</span>
                                        </button>
                                        <button type="button"
                                            onclick="openDeleteModal({{ $user['id'] }}, '{{ addslashes($user['nama']) }}')"
                                            class="w-24 flex justify-center items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200"
                                            title="Hapus">
                                            <x-tabler-trash class="w-4 h-4" />
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-carbon-black-500 font-medium">Tidak ada data
                                    pengguna yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination/Footer -->
            <div class="bg-carbon-black-50 border-t border-carbon-black-100 px-6 py-4 flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-widest text-carbon-black-500">
                    Menampilkan {{ count($users) }} user
                </p>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div id="add-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300">
            <form action="{{ route(request()->is('pimpinan*') ? 'pimpinan.kelola_user.store' : 'kelola_user.store') }}"
                method="POST">
                @csrf
                <div
                    class="px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center bg-carbon-black-50 rounded-t-xl">
                    <h3 class="text-lg font-black uppercase tracking-widest text-carbon-black-900 flex items-center gap-2">
                        <x-tabler-user-plus class="w-5 h-5 text-hunter-green-600" />
                        Tambah Pengguna Baru
                    </h3>
                    <button type="button" onclick="closeAddModal()"
                        class="text-carbon-black-400 hover:text-rose-600 transition-colors">
                        <x-tabler-x class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Nama
                            Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required
                            class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Username
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" required
                            class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Password <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="add-password" minlength="8" required class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                                <button type="button" onclick="togglePassword('add-password', 'add-eye-on', 'add-eye-off')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-carbon-black-400 hover:text-carbon-black-600 focus:outline-none">
                                    <x-tabler-eye id="add-eye-on" class="w-5 h-5" />
                                    <x-tabler-eye-off id="add-eye-off" class="w-5 h-5 hidden" />
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Konfirmasi Password <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="add-password-confirm" minlength="8" required class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                                <button type="button" onclick="togglePassword('add-password-confirm', 'add-eye-confirm-on', 'add-eye-confirm-off')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-carbon-black-400 hover:text-carbon-black-600 focus:outline-none">
                                    <x-tabler-eye id="add-eye-confirm-on" class="w-5 h-5" />
                                    <x-tabler-eye-off id="add-eye-confirm-off" class="w-5 h-5 hidden" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Role
                                <span class="text-rose-500">*</span></label>
                            <select name="role" required
                                class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium bg-white">
                                <option value="admin_penggajian">Admin Penggajian</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Status
                                <span class="text-rose-500">*</span></label>
                            <select name="status" required
                                class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium bg-white">
                                <option value="Aktif">Aktif</option>
                                <option value="Inaktif">Inaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    class="px-6 py-4 border-t border-carbon-black-100 flex justify-end gap-3 bg-carbon-black-50 rounded-b-xl">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-bold text-carbon-black-600 bg-white border border-carbon-black-200 rounded-lg hover:bg-carbon-black-50 transition-colors uppercase tracking-wider">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-bold text-white bg-hunter-green-600 rounded-lg hover:bg-hunter-green-700 shadow-md active:scale-95 transition-all uppercase tracking-wider">Simpan
                        Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="edit-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300">
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div
                    class="px-6 py-4 border-b border-carbon-black-100 flex justify-between items-center bg-carbon-black-50 rounded-t-xl">
                    <h3 class="text-lg font-black uppercase tracking-widest text-carbon-black-900 flex items-center gap-2">
                        <x-tabler-pencil class="w-5 h-5 text-blue-600" />
                        Ubah data pengguna
                    </h3>
                    <button type="button" onclick="closeEditModal()"
                        class="text-carbon-black-400 hover:text-rose-600 transition-colors">
                        <x-tabler-x class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Nama
                            Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" id="edit-nama" required
                            class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Username
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" id="edit-username" required
                            class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="edit-password" minlength="8" placeholder="Opsional..." class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                                <button type="button" onclick="togglePassword('edit-password', 'edit-eye-on', 'edit-eye-off')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-carbon-black-400 hover:text-carbon-black-600 focus:outline-none">
                                    <x-tabler-eye id="edit-eye-on" class="w-5 h-5" />
                                    <x-tabler-eye-off id="edit-eye-off" class="w-5 h-5 hidden" />
                                </button>
                            </div>
                            <p class="text-xs text-carbon-black-400 mt-1.5 font-medium italic">* Kosongkan jika tidak ingin diubah</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="edit-password-confirm" minlength="8" placeholder="Opsional..." class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium">
                                <button type="button" onclick="togglePassword('edit-password-confirm', 'edit-eye-confirm-on', 'edit-eye-confirm-off')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-carbon-black-400 hover:text-carbon-black-600 focus:outline-none">
                                    <x-tabler-eye id="edit-eye-confirm-on" class="w-5 h-5" />
                                    <x-tabler-eye-off id="edit-eye-confirm-off" class="w-5 h-5 hidden" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Role
                                <span class="text-rose-500">*</span></label>
                            <select name="role" id="edit-role" required
                                class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium bg-white">
                                <option value="admin_penggajian">Admin Penggajian</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-carbon-black-600 mb-2">Status
                                <span class="text-rose-500">*</span></label>
                            <select name="status" id="edit-status" required
                                class="w-full rounded-lg border-2 border-carbon-black-200 px-4 py-2.5 text-sm focus:border-hunter-green-500 focus:outline-none transition-colors font-medium bg-white">
                                <option value="Aktif">Aktif</option>
                                <option value="Inaktif">Inaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div
                    class="px-6 py-4 border-t border-carbon-black-100 flex justify-end gap-3 bg-carbon-black-50 rounded-b-xl">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-sm font-bold text-carbon-black-600 bg-white border border-carbon-black-200 rounded-lg hover:bg-carbon-black-50 transition-colors uppercase tracking-wider">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md active:scale-95 transition-all uppercase tracking-wider">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Pengguna -->
    <div id="delete-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-carbon-black-900/50 backdrop-blur-sm transition-opacity opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform scale-95 transition-transform duration-300">
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="p-6 text-center space-y-4">
                    <div
                        class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto border-4 border-rose-100">
                        <x-tabler-alert-triangle class="w-10 h-10 text-rose-500" />
                    </div>
                    <h3 class="text-xl font-black uppercase tracking-widest text-carbon-black-900">Hapus Pengguna?</h3>
                    <p class="text-sm font-medium text-carbon-black-500">Anda yakin ingin mengHapus Pengguna <span
                            id="delete-name" class="font-bold text-carbon-black-900"></span>? Data ini tidak dapat
                        dikembalikan lagi.</p>
                </div>
                <div
                    class="px-6 py-4 border-t border-carbon-black-100 flex justify-center gap-3 bg-carbon-black-50 rounded-b-xl">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 text-sm font-bold text-carbon-black-600 bg-white border border-carbon-black-200 rounded-lg hover:bg-carbon-black-50 transition-colors uppercase tracking-wider">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-bold text-white bg-rose-600 rounded-lg hover:bg-rose-700 shadow-md active:scale-95 transition-all uppercase tracking-wider">Ya,
                        Hapus</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('search');
                const searchBtn = document.getElementById('search-btn');
                const statusFilter = document.getElementById('status-filter');
                const roleFilter = document.getElementById('role-filter');

                function triggerFilter() {
                    const url = new URL(window.location.href);

                    if (searchInput.value.trim()) {
                        url.searchParams.set('search', searchInput.value.trim());
                    } else {
                        url.searchParams.delete('search');
                    }

                    if (statusFilter.value) {
                        url.searchParams.set('status', statusFilter.value);
                    } else {
                        url.searchParams.delete('status');
                    }

                    if (roleFilter.value) {
                        url.searchParams.set('role', roleFilter.value);
                    } else {
                        url.searchParams.delete('role');
                    }

                    window.location.href = url.toString();
                }

                searchBtn.addEventListener('click', triggerFilter);
                statusFilter.addEventListener('change', triggerFilter);
                roleFilter.addEventListener('change', triggerFilter);

                let searchTimeout;
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(triggerFilter, 600);
                });

                if (searchInput.value) {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }
            });

            // Add Modal Logic
            const addModal = document.getElementById('add-modal');
            const addModalInner = addModal.querySelector('div');

            window.openAddModal = function () {
                addModal.classList.remove('hidden');
                addModal.classList.add('flex');
                setTimeout(() => {
                    addModal.classList.remove('opacity-0');
                    addModalInner.classList.remove('scale-95');
                    addModalInner.classList.add('scale-100');
                }, 10);
            }

            window.closeAddModal = function () {
                addModal.classList.add('opacity-0');
                addModalInner.classList.remove('scale-100');
                addModalInner.classList.add('scale-95');
                setTimeout(() => {
                    addModal.classList.add('hidden');
                    addModal.classList.remove('flex');
                }, 300);
            }

            // Edit Modal Logic
            const editModal = document.getElementById('edit-modal');
            const editModalInner = editModal.querySelector('div');
            const editForm = document.getElementById('edit-form');
            const rolePrefix = '{{ request()->is("pimpinan*") ? "/pimpinan" : "" }}';

            window.openEditModal = function (user) {
                document.getElementById('edit-nama').value = user.nama;
                document.getElementById('edit-username').value = user.username;
                document.getElementById('edit-password').value = '';
                document.getElementById('edit-password-confirm').value = '';
                document.getElementById('edit-role').value = user.role;
                document.getElementById('edit-status').value = user.status;

                editForm.action = `${rolePrefix}/kelola-user/${user.id}`;

                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
                setTimeout(() => {
                    editModal.classList.remove('opacity-0');
                    editModalInner.classList.remove('scale-95');
                    editModalInner.classList.add('scale-100');
                }, 10);
            }

            window.closeEditModal = function () {
                editModal.classList.add('opacity-0');
                editModalInner.classList.remove('scale-100');
                editModalInner.classList.add('scale-95');
                setTimeout(() => {
                    editModal.classList.add('hidden');
                    editModal.classList.remove('flex');
                }, 300);
            }

            // Delete Modal Logic
            const deleteModal = document.getElementById('delete-modal');
            const deleteModalInner = deleteModal.querySelector('div');
            const deleteForm = document.getElementById('delete-form');

            window.openDeleteModal = function (id, name) {
                document.getElementById('delete-name').textContent = name;
                deleteForm.action = `${rolePrefix}/kelola-user/${id}`;

                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                setTimeout(() => {
                    deleteModal.classList.remove('opacity-0');
                    deleteModalInner.classList.remove('scale-95');
                    deleteModalInner.classList.add('scale-100');
                }, 10);
            }

            window.closeDeleteModal = function () {
                deleteModal.classList.add('opacity-0');
                deleteModalInner.classList.remove('scale-100');
                deleteModalInner.classList.add('scale-95');
                setTimeout(() => {
                    deleteModal.classList.add('hidden');
                    deleteModal.classList.remove('flex');
                }, 300);
            }
            
            window.togglePassword = function (inputId, eyeOnId, eyeOffId) {
                const input = document.getElementById(inputId);
                const eyeOn = document.getElementById(eyeOnId);
                const eyeOff = document.getElementById(eyeOffId);
                if (input.type === 'password') {
                    input.type = 'text';
                    eyeOn.classList.add('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeOn.classList.remove('hidden');
                    eyeOff.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection