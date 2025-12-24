<section>
    <header class="mb-6">
        <div class="flex items-center gap-3">
            <div class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Seguridad de la Cuenta</h2>
        </div>

        <p class="mt-2 text-sm leading-relaxed text-gray-500">
            Cambia tu contraseña periódicamente para mantener tu acceso protegido. Se recomienda usar una combinación de
            letras, números y símbolos.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label
                for="update_password_current_password"
                class="mb-1 ml-1 block text-[10px] font-black uppercase tracking-wider text-gray-400"
            >
                Contraseña Actual
            </label>
            <div class="group relative">
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors group-focus-within:text-indigo-500"
                >
                    <i class="fas fa-key text-xs"></i>
                </div>
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-9 font-bold text-gray-700 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                    autocomplete="current-password"
                />
            </div>
            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2 text-xs font-bold"
            />
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label
                    for="update_password_password"
                    class="mb-1 ml-1 block text-[10px] font-black uppercase tracking-wider text-gray-400"
                >
                    Nueva Contraseña
                </label>
                <div class="group relative">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors group-focus-within:text-indigo-500"
                    >
                        <i class="fas fa-lock text-xs"></i>
                    </div>
                    <input
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-9 font-bold text-gray-700 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                        autocomplete="new-password"
                    />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs font-bold" />
            </div>

            <div>
                <label
                    for="update_password_password_confirmation"
                    class="mb-1 ml-1 block text-[10px] font-black uppercase tracking-wider text-gray-400"
                >
                    Confirmar Nueva Contraseña
                </label>
                <div class="group relative">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors group-focus-within:text-indigo-500"
                    >
                        <i class="fas fa-check-double text-xs"></i>
                    </div>
                    <input
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-9 font-bold text-gray-700 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                        autocomplete="new-password"
                    />
                </div>
                <x-input-error
                    :messages="$errors->updatePassword->get('password_confirmation')"
                    class="mt-2 text-xs font-bold"
                />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button
                type="submit"
                class="flex transform items-center gap-3 rounded-xl bg-gray-900 px-10 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg transition hover:bg-black active:scale-95"
            >
                <i class="fas fa-save"></i>
                Actualizar Contraseña
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 3000)"
                    class="flex items-center gap-2 text-sm font-bold text-green-600"
                >
                    <i class="fas fa-check-circle"></i>
                    <span>Cambios guardados</span>
                </div>
            @endif
        </div>
    </form>
</section>
