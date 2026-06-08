<aside
    class="fixed top-0 left-0 h-[125vh] w-72 bg-gradient-to-b from-black-forest-950 via-black-forest-900 to-black-forest-950 text-white flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.3)] z-20 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-hunter-green-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-aquamarine-500/5 rounded-full blur-3xl -ml-20 -mb-20"></div>

    <!-- User Profile Area -->
    <div class="p-8 flex flex-col items-center relative">
        <div class="relative group">

            <div
                class="relative w-28 h-28 rounded-full border-2 border-white/10 overflow-hidden bg-black-forest-800 shadow-2xl flex items-center justify-center p-1 backdrop-blur-sm">
                <div
                    class="w-full h-full rounded-full bg-gradient-to-br from-carbon-black-800 to-black-forest-900 flex items-center justify-center">
                    <!-- Profile Placeholder -->
                    <x-tabler-user
                        class="w-16 h-16 text-carbon-black-400/50 group-hover:text-hunter-green-400 transition-colors duration-500" />
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-hunter-green-400 mb-1">
                Selamat Datang
            </h3>
            <p class="text-sm font-bold text-white tracking-wide uppercase">
                @php
                    $role = session('user_role');
                    if (!$role) {
                        $role = request()->is('pimpinan*') ? 'pimpinan' : 'admin_penggajian';
                    }
                    if ($role === 'admin_penggajian')
                        echo 'Admin';
                    else if ($role === 'pimpinan')
                        echo 'Pimpinan';
                    else
                        echo str_replace('_', ' ', $role);
                @endphp
            </p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto custom-scrollbar">
        @php
            if ($role === 'pimpinan') {
                $menuItems = [
                    ['name' => 'Dashboard', 'route' => 'pimpinan.dashboard', 'icon' => 'dashboard'],
                    ['name' => 'Data Karyawan', 'route' => 'pimpinan.karyawan.index', 'icon' => 'users'],
                    ['name' => 'Laporan Penggajian', 'route' => 'pimpinan.laporan_penggajian.index', 'icon' => 'file-description'],
                ];
            } else {
                $menuItems = [
                    ['name' => 'Data Karyawan', 'route' => 'karyawan.index', 'icon' => 'users'],
                    ['name' => 'Data Presensi', 'route' => 'presensi.index', 'icon' => 'calendar-check'],
                    ['name' => 'Gaji Pokok & Lembur', 'route' => 'gaji_pokok.index', 'icon' => 'cash'],
                    ['name' => 'Gaji Bonus', 'route' => 'gaji_bonus.index', 'icon' => 'gift'],
                    ['name' => 'Potongan Gaji', 'route' => 'potongan_gaji.index', 'icon' => 'cash-off'],
                    ['name' => 'Penggajian', 'route' => 'penggajian.index', 'icon' => 'report-money'],
                    ['name' => 'Detail Penggajian', 'route' => 'detail_penggajian.index', 'icon' => 'file-analytics'],
                    ['name' => 'Laporan Penggajian', 'route' => 'laporan_penggajian.index', 'icon' => 'file-description'],
                ];
            }
        @endphp

        @foreach($menuItems as $item)
            @php
                $isActive = Route::currentRouteName() == $item['route'] || (isset($active) && $active == $item['name']);
            @endphp
            <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}" class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 relative
                   {{ $isActive
            ? 'bg-gradient-to-r from-hunter-green-600 to-hunter-green-500 text-white shadow-[0_4px_15px_rgba(98,164,91,0.3)]'
            : 'text-carbon-black-300 hover:bg-white/5 hover:text-white' }}">

                @if($isActive)
                    <div class="absolute left-0 w-1 h-6 bg-white rounded-r-full"></div>
                @endif

                @php $iconName = "tabler-" . $item['icon']; @endphp
                <x-dynamic-component :component="$iconName"
                    class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-carbon-black-500 group-hover:text-hunter-green-400' }}" />

                <span class="text-sm font-bold tracking-wide">{{ $item['name'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- Logout Button -->
    <div class="p-6">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full group relative overflow-hidden bg-white/5 hover:bg-rose-600/10 border border-white/10 hover:border-rose-500/50 rounded-xl py-3 px-4 transition-all duration-300 active:scale-95">
                <div class="relative flex items-center justify-center space-x-3">
                    <x-tabler-logout
                        class="w-5 h-5 text-rose-500 transition-transform duration-300 group-hover:-translate-x-1" />
                    <span
                        class="text-xs font-black uppercase tracking-widest text-rose-500 group-hover:text-rose-400">Keluar
                        Sistem</span>
                </div>
            </button>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</aside>
