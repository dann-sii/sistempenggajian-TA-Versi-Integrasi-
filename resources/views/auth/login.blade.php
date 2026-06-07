<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penggajian</title>
    @vite('resources/css/app.css')
</head>

<body
    class="bg-carbon-black-50 h-screen overflow-hidden flex items-center justify-center selection:bg-hunter-green-200 selection:text-hunter-green-900 font-sans">

    <!-- 1440px Max-Width Container -->
    <div class="w-full max-w-[1440px] h-full mx-auto flex items-center justify-center p-4">

        <!-- Card Container -->
        <div
            class="bg-white w-full max-w-[460px] rounded-[32px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] relative overflow-hidden border border-carbon-black-100">

            <!-- Header / Half Oval -->
            <div class="relative w-full h-[150px] bg-gradient-to-b from-hunter-green-600 to-hunter-green-700 flex items-center justify-center"
                style="border-radius: 0 0 50% 50% / 0 0 50px 50px;">

                <!-- Circular Logo Placeholder -->
                <div
                    class="w-[110px] h-[110px] bg-white rounded-full shadow-xl border-4 border-hunter-green-50 flex items-center justify-center absolute -bottom-12 z-10 transition-transform duration-300 hover:scale-105">
                    <img src="{{ asset('img/logo_st.png') }}" alt="Logo" class="w-full h-full object-cover rounded-full">
                </div>

                <!-- Decorative Accent -->
                <div
                    class="absolute top-0 left-0 w-full h-full opacity-10 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-white to-transparent">
                </div>
            </div>

            <!-- Form Section -->
            <div class="px-8 pt-16 pb-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-black text-carbon-black-900 uppercase tracking-tight">SAHABAT TERNAK</h1>
                    <p class="text-sm font-semibold text-carbon-black-500 mt-1 tracking-wide">Masukkan akun anda</p>
                </div>

                <form action="{{ route('login.post') ?? '#' }}" method="POST" class="space-y-4">
                    @csrf

                    @if($errors->has('loginError'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center gap-3 mb-2">
                        <x-tabler-alert-circle class="w-5 h-5 flex-shrink-0" stroke-width="2" />
                        <span class="text-sm font-bold">{{ $errors->first('loginError') }}</span>
                    </div>
                    @endif

                    <!-- Input Username/Email -->
                    <div class="space-y-1.5">
                        <label for="username"
                            class="block text-xs font-black text-carbon-black-700 uppercase tracking-widest pl-1">ID Pengguna / Email</label>
                        <div class="relative flex items-center group">
                            <div
                                class="absolute left-4 text-carbon-black-400 group-focus-within:text-hunter-green-600 transition-colors">
                                <x-tabler-user class="w-5 h-5" stroke-width="2.5" />
                            </div>
                            <input type="text" id="username" name="username" placeholder="Masukkan ID Pengguna" required
                                class="w-full pl-12 pr-4 py-3 bg-carbon-black-50 border-2 border-carbon-black-100 rounded-2xl text-carbon-black-900 font-bold placeholder-carbon-black-400 focus:outline-none focus:border-hunter-green-500 focus:bg-white focus:shadow-[0_0_0_4px_rgba(47,79,79,0.1)] transition-all">
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center pl-1 pr-1">
                            <label for="password"
                                class="block text-xs font-black text-carbon-black-700 uppercase tracking-widest">Kata Sandi</label>
                            <a href="#"
                                class="text-xs font-bold text-hunter-green-600 hover:text-hunter-green-700 hover:underline transition-all">Lupa sandi?</a>
                        </div>
                        <div class="relative flex items-center group">
                            <div
                                class="absolute left-4 text-carbon-black-400 group-focus-within:text-hunter-green-600 transition-colors">
                                <x-tabler-lock class="w-5 h-5" stroke-width="2.5" />
                            </div>
                            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required
                                class="w-full pl-12 pr-12 py-3 bg-carbon-black-50 border-2 border-carbon-black-100 rounded-2xl text-carbon-black-900 font-bold placeholder-carbon-black-400 focus:outline-none focus:border-hunter-green-500 focus:bg-white focus:shadow-[0_0_0_4px_rgba(47,79,79,0.1)] transition-all">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 text-carbon-black-400 hover:text-carbon-black-700 transition-colors cursor-pointer outline-none focus:text-hunter-green-600">
                                <x-tabler-eye class="w-5 h-5" stroke-width="2.5" id="eye-icon" />
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-black-forest-800 text-white font-black text-sm py-3.5 rounded-2xl uppercase tracking-[0.2em] shadow-lg shadow-black-forest-900/20 hover:bg-black-forest-900 hover:shadow-black-forest-900/40 active:scale-[0.98] transition-all flex justify-center items-center gap-2.5 group">
                            <span>Masuk</span>
                            <x-tabler-arrow-right class="w-5 h-5 group-hover:translate-x-1 transition-transform" stroke-width="2.5" />
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-xs font-bold text-carbon-black-400 tracking-wider">
                    <p>&copy; 2026 PT. Sahabat Ternak</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ideally toggle the icon here if needed, but since we're using a blade component,
                // we'll just keep the eye icon static or you can add JS to swap the SVG innerHTML.
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>

</html>