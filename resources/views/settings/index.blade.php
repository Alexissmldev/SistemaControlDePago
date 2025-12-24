<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="rounded-lg bg-indigo-600 p-2 shadow-md">
                <i class="fas fa-cogs text-white"></i>
            </div>
            <h2 class="text-xl font-bold leading-tight text-gray-800">Configuración del Sistema</h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10" x-data="settingsManager()">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="space-y-8">
                    {{-- Gestión de Tasa Cambiaria --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div
                            class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
                        >
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="mb-1 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Tasa de Cambio Oficial
                                    </h3>
                                    <p class="text-xs text-gray-500">Referencia actual del BCV.</p>
                                </div>
                                <div class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>

                            <div class="py-10 text-center">
                                <div class="flex items-baseline justify-center gap-2">
                                    <span class="text-2xl font-bold text-gray-400">Bs.</span>
                                    <span class="text-6xl font-black tracking-tighter text-gray-800">
                                        {{ number_format($exchangeRate, 2) }}
                                    </span>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="syncRate()"
                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50 py-3 font-bold text-indigo-700 transition hover:bg-indigo-100"
                            >
                                <i class="fas fa-sync-alt" :class="syncing ? 'fa-spin' : ''"></i>
                                <span class="text-sm">Actualizar desde API</span>
                            </button>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-800">Ajuste Manual</h3>
                                <p class="text-sm text-gray-400">Establecer valor personalizado.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-[10px] font-bold uppercase text-gray-400">
                                        Precio de la Divisa
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"
                                        >
                                            <span class="font-bold text-gray-400">Bs.</span>
                                        </div>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="bcv_rate"
                                            value="{{ $exchangeRate }}"
                                            class="w-full rounded-xl border-gray-100 bg-gray-50 py-4 pl-12 pr-4 text-2xl font-black text-gray-800 transition-all focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                                        />
                                    </div>
                                </div>

                                <div class="flex gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                                    <i class="fas fa-info-circle mt-0.5 text-xs text-blue-500"></i>
                                    <p class="text-[10px] font-medium leading-relaxed text-blue-700">
                                        Este valor es fundamental para el cálculo de facturas y deudas en Bolívares.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Datos de Recepción de Pagos --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-8 flex items-center gap-3">
                            <div class="rounded-xl bg-green-100 p-2.5 text-green-600 shadow-sm">
                                <i class="fas fa-mobile-screen-button text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Cuentas de Pago Móvil</h3>
                                <p class="text-xs italic text-gray-500">Se mostrarán en los recibos de cobro.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            @foreach (['pay_bank' => 'Banco Destino', 'pay_dni' => 'Cédula / RIF', 'pay_phone' => 'Teléfono Receptor'] as $name => $label)
                                <div>
                                    <label
                                        class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-gray-400"
                                    >
                                        {{ $label }}
                                    </label>
                                    <input
                                        type="text"
                                        name="{{ $name }}"
                                        value="{{ $globalSettings[$name] ?? '' }}"
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-semibold focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mensajería Automatizada --}}
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-8 flex items-center gap-3">
                            <div class="rounded-xl bg-emerald-100 p-2.5 text-emerald-600">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Plantillas de WhatsApp</h3>
                                <p class="text-xs text-gray-500">Configura los mensajes para cada acción.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            @php
                                $templates = [
                                    'cobro' => ['title' => 'Recordatorio de Cobro', 'icon' => 'fa-file-invoice-dollar', 'color' => 'indigo', 'tags' => ['{nombre}', '{monto}', '{datos_pago}']],
                                    'welcome' => ['title' => 'Bienvenida', 'icon' => 'fa-star', 'color' => 'amber', 'tags' => ['{nombre}']],
                                    'schedule' => ['title' => 'Horarios', 'icon' => 'fa-calendar', 'color' => 'blue', 'tags' => ['{nombre}', '{lista_cursos}']],
                                ];
                            @endphp

                            @foreach ($templates as $key => $data)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-5">
                                    <div class="mb-4 flex items-center gap-2">
                                        <i class="fas {{ $data['icon'] }} text-{{ $data['color'] }}-500 text-sm"></i>
                                        <span class="text-sm font-bold text-gray-700">{{ $data['title'] }}</span>
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                                        <div class="lg:col-span-1">
                                            <label class="mb-2 block text-[10px] font-bold uppercase text-gray-400">
                                                Etiqueta del Botón
                                            </label>
                                            <input
                                                type="text"
                                                name="ws_title_{{ $key }}"
                                                value="{{ $globalSettings['ws_title_' . $key] ?? '' }}"
                                                class="w-full rounded-xl border-gray-200 text-sm font-bold shadow-sm"
                                            />
                                        </div>
                                        <div class="lg:col-span-3">
                                            <div class="mb-2 flex items-center justify-between">
                                                <label class="block text-[10px] font-bold uppercase text-gray-400">
                                                    Cuerpo del Mensaje
                                                </label>
                                                <div class="flex gap-1">
                                                    @foreach ($data['tags'] as $tag)
                                                        <button
                                                            type="button"
                                                            @click="insertTag('ws_msg_{{ $key }}', '{{ $tag }}')"
                                                            class="rounded-md border border-gray-200 bg-white px-2 py-1 text-[9px] font-bold text-indigo-600 transition hover:border-indigo-500"
                                                        >
                                                            + {{ str_replace(['{', '}'], '', $tag) }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <textarea
                                                id="ws_msg_{{ $key }}"
                                                name="ws_msg_{{ $key }}"
                                                rows="4"
                                                class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:ring-indigo-500"
                                            >
{{ $globalSettings['ws_msg_' . $key] ?? '' }}</textarea
                                            >
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Acción de Guardado --}}
                    <div class="sticky bottom-6 z-30">
                        <div
                            class="flex items-center justify-between rounded-2xl border border-white/10 bg-gray-900/90 p-4 shadow-2xl backdrop-blur-md"
                        >
                            <p class="ml-4 hidden text-xs italic text-white/60 md:block">
                                Asegúrate de revisar la tasa BCV antes de guardar.
                            </p>
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-3 rounded-xl bg-indigo-600 px-12 py-3 font-bold text-white shadow-lg transition hover:bg-indigo-500 md:w-auto"
                            >
                                <i class="fas fa-save"></i>
                                GUARDAR CAMBIOS
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <form id="sync-form" action="{{ route('settings.sync') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>

    @push('scripts')
        <script>
            function settingsManager() {
                return {
                    syncing: false,

                    syncRate() {
                        this.syncing = true;
                        document.getElementById('sync-form').submit();
                    },

                    insertTag(fieldId, tag) {
                        const el = document.getElementById(fieldId);
                        const start = el.selectionStart;
                        const end = el.selectionEnd;
                        const text = el.value;

                        el.value = text.substring(0, start) + tag + text.substring(end);
                        el.focus();

                        // Reposicionar el cursor después del tag insertado
                        this.$nextTick(() => {
                            el.selectionStart = el.selectionEnd = start + tag.length;
                        });
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
