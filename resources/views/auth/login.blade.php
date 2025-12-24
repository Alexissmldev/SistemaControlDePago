<x-guest-layout>
    <div class="relative">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

        <div class="relative">
            <div class="mb-10 text-center">
                <div class="inline-flex mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-indigo-500 rounded-2xl rotate-6 opacity-10"></div>
                        <div class="relative bg-white border border-gray-100 p-4 rounded-2xl shadow-sm">
                            <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                        </div>
                    </div>
                </div>
                
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                    Iniciar Sesión
                </h2>
                <p class="text-sm font-medium text-gray-400 mt-2">
                    Introduce tus credenciales para continuar
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="group">
                    <label for="email" class="block ml-1 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 group-focus-within:text-indigo-600 transition-colors">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 placeholder-gray-300 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none"
                            placeholder="ejemplo@correo.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-red-500" />
                </div>

                <div class="group">
                    <div class="flex justify-between items-end mb-1">
                        <label for="password" class="block ml-1 text-[10px] font-black uppercase tracking-widest text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                            Contraseña
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-black text-indigo-500 hover:text-indigo-700 uppercase transition-colors" href="{{ route('password.request') }}">
                                ¿La olvidaste?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 placeholder-gray-300 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none"
                            placeholder="••••••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-red-500" />
                </div>

                <div class="flex items-center px-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-0 transition-all cursor-pointer">
                        <span class="ms-3 text-xs font-bold text-gray-500 group-hover:text-gray-700 transition-colors uppercase tracking-wide">Recordarme en este equipo</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-4 rounded-2xl shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-3 uppercase tracking-widest text-xs">
                        <span>Entrar al Portal</span>
                        <i class="fas fa-sign-in-alt text-xs opacity-50"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(20px, -30px) scale(1.05); }
            66% { transform: translate(-10px, 10px) scale(0.95); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 10s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</x-guest-layout>