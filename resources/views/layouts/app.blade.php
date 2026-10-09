<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Penggajian - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "San Francisco Pro", "SF Pro Text", "SF Pro Display", system-ui, ui-sans-serif, sans-serif;
        }

        .scrollbar-invisible {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scrollbar-invisible::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="bg-carbon-black-50 text-carbon-black-950 antialiased" style="zoom: 0.8;">
    <!-- Sidebar (Fixed) -->
    <x-sidebar />

    <!-- Main Wrapper: offset by sidebar width (w-72) -->
    <div class="ml-72 flex min-h-screen flex-col">
        <!-- Header (Fixed) -->
        <header class="fixed top-0 right-0 left-72 z-30 h-20 bg-white border-b border-carbon-black-200 flex items-center justify-between px-8 shadow-sm">
            <div class="flex items-center space-x-4">
                <span class="bg-black-forest-700 text-white px-4 py-2 rounded-lg text-lg font-bold uppercase tracking-widest">
                    Sahabat Ternak
                </span>
            </div>
            <div class="flex items-center space-x-6 text-sm text-carbon-black-700">
                <div class="flex items-center space-x-2 text-hunter-green-600 font-bold border-r border-carbon-black-200 pr-6">
                    <x-tabler-clock class="w-5 h-5" />
                    <span id="realtime-clock" class="tracking-widest">00:00:00</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="opacity-60 italic">Halo,</span>
                    <span class="font-black uppercase tracking-widest bg-carbon-black-50 px-3 py-1 rounded-full text-xs">
                        @php
                            $role = session('user_role');
                            if (!$role) {
                                $role = request()->is('pimpinan*') ? 'pimpinan' : 'admin_penggajian';
                            }
                            if ($role === 'admin_penggajian') echo 'ADMIN';
                            else if ($role === 'pimpinan') echo 'OWNER';
                            else echo strtoupper(str_replace('_', ' ', $role));
                        @endphp
                    </span>
                </div>
            </div>
        </header>

        <!-- Page Content: offset top by header height (h-20 = 5rem) -->
        <main class="flex-1 p-10 mt-20 overflow-x-hidden">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clockElement = document.getElementById('realtime-clock');
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        
        setInterval(updateClock, 1000);
        updateClock(); // initial call
    </script>
</body>
</html>
