<section>
    <header class="mb-6">
        <div class="flex items-center gap-3">
            <div class="rounded-lg bg-indigo-600 p-2 shadow-md">
                <i class="fas fa-id-card text-sm text-white"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Información del Perfil</h2>
        </div>

        <p class="mt-2 text-sm leading-relaxed text-gray-500">
            Administra los datos públicos de tu cuenta como tu nombre de usuario y correo electrónico institucional.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="mb-1 ml-1 block text-[10px] font-black uppercase tracking-wider text-gray-400">
                Nombre Completo
            </label>
            <div class="group relative">
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors group-focus-within:text-indigo-500"
                >
                    <i class="fas fa-user text-xs"></i>
                </div>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-9 font-bold text-gray-700 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                />
            </div>
            <x-input-error class="mt-2 text-xs font-bold" :messages="$errors->get('name')" />
        </div>

        {{-- Correo Electrónico --}}
        <div>
            <label for="email" class="mb-1 ml-1 block text-[10px] font-black uppercase tracking-wider text-gray-400">
                Correo Electrónico
            </label>
            <div class="group relative">
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors group-focus-within:text-indigo-500"
                >
                    <i class="fas fa-envelope text-xs"></i>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-9 font-bold text-gray-700 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                />
            </div>
            <x-input-error class="mt-2 text-xs font-bold" :messages="$errors->get('email')" />

            {{-- Estado de Verificación --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 flex items-start gap-3 rounded-2xl border border-amber-100 bg-amber-50 p-4">
                    <i class="fas fa-exclamation-circle mt-0.5 text-amber-500"></i>
                    <div>
                        <p class="text-xs font-bold text-amber-800">Tu correo electrónico aún no ha sido verificado.</p>
                        <button
                            form="send-verification"
                            class="mt-1 text-xs font-black uppercase tracking-tighter text-amber-600 underline transition hover:text-amber-700"
                        >
                            Reenviar enlace de verificación
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-[10px] font-black uppercase text-green-600">
                                <i class="fas fa-paper-plane mr-1"></i>
                                Enlace enviado con éxito.
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Acciones --}}
        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="flex transform items-center gap-3 rounded-xl bg-gray-900 px-10 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg transition hover:bg-black active:scale-95"
            >
                <i class="fas fa-save"></i>
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 3000)"
                    class="flex items-center gap-2 text-sm font-bold text-green-600"
                >
                    <i class="fas fa-check-circle"></i>
                    <span>Perfil actualizado</span>
                </div>
            @endif
        </div>
    </form>
</section>
