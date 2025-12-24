<section class="space-y-6">
    <header>
        <div class="flex items-center gap-2 text-red-600">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <h2 class="text-lg font-bold">ZONA DE PELIGRO: Eliminar Cuenta</h2>
        </div>

        <p class="mt-2 text-sm leading-relaxed text-gray-600">
            Si decides eliminar tu cuenta, todos los datos asociados se borrarán de forma permanente. Te recomendamos
            descargar cualquier información importante antes de continuar.
        </p>
    </header>

    <button
        x-data=""
        @click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-red-700 active:scale-95"
    >
        <i class="fas fa-trash-alt"></i>
        Eliminar definitivamente
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <div class="text-center md:text-left">
                <h2 class="flex items-center justify-center gap-3 text-xl font-black text-gray-800 md:justify-start">
                    <span class="rounded-lg bg-red-100 p-2 text-red-600">
                        <i class="fas fa-user-shield"></i>
                    </span>
                    Confirmar Identidad
                </h2>

                <p class="mt-4 text-sm text-gray-500">
                    Por tu seguridad, necesitamos confirmar que eres tú. Por favor, ingresa tu contraseña para proceder
                    con la eliminación de la cuenta.
                </p>
            </div>

            <div class="mt-6">
                <label for="password" class="mb-2 ml-1 block text-[10px] font-bold uppercase text-gray-400">
                    Tu Contraseña
                </label>

                <div class="group relative">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 transition-colors group-focus-within:text-red-500"
                    >
                        <i class="fas fa-lock text-sm"></i>
                    </div>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-10 font-bold text-gray-700 shadow-sm transition-all focus:border-red-500 focus:bg-white focus:ring-red-500"
                        placeholder="••••••••••••"
                        required
                    />
                </div>

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2 text-xs font-bold text-red-600"
                />
            </div>

            <div class="mt-8 flex flex-col justify-end gap-3 md:flex-row">
                <button
                    type="button"
                    @click="$dispatch('close')"
                    class="order-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-xs font-bold uppercase text-gray-500 transition hover:bg-gray-50 md:order-1"
                >
                    No, me arrepentí
                </button>

                <button
                    type="submit"
                    class="order-1 transform rounded-xl bg-red-600 px-6 py-3 text-xs font-black uppercase tracking-wider text-white shadow-lg shadow-red-100 transition hover:bg-red-700 active:scale-95 md:order-2"
                >
                    Sí, eliminar todo
                </button>
            </div>
        </form>
    </x-modal>
</section>
