<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold leading-tight text-gray-800">Nuevo Curso</h2>
            <a
                href="{{ route('courses.index') }}"
                class="flex items-center gap-2 text-sm font-bold text-gray-500 transition hover:text-indigo-600"
            >
                <i class="fas fa-arrow-left"></i>
                Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="flex min-h-screen items-start justify-center bg-gray-50 px-4 py-12">
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            {{-- Línea decorativa de marca --}}
            <div class="h-1.5 w-full bg-indigo-600"></div>

            <div class="p-8">
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Detalles del Grupo</h3>
                    <p class="text-sm text-gray-500">Define los parámetros básicos para las nuevas inscripciones.</p>
                </div>

                <form action="{{ route('courses.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="mb-2 ml-1 block text-xs font-bold uppercase text-gray-500">
                            Nombre de la Clase
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Ej. Salsa Casino Nivel 1"
                            class="w-full rounded-xl border-gray-200 px-4 py-3 font-medium text-gray-700 transition-all focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        @error('name')
                            <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 ml-1 block text-xs font-bold uppercase text-gray-500">
                                Mensualidad ($)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                name="monthly_fee"
                                value="{{ old('monthly_fee') }}"
                                placeholder="0.00"
                                class="w-full rounded-xl border-gray-200 px-4 py-3 text-right font-bold text-gray-700 transition-all focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            />
                            @error('monthly_fee')
                                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 ml-1 block text-xs font-bold uppercase text-gray-500">Horario</label>
                            <input
                                type="text"
                                name="schedule"
                                value="{{ old('schedule') }}"
                                placeholder="Ej. Mar-Jue 5pm"
                                class="w-full rounded-xl border-gray-200 px-4 py-3 text-sm text-gray-700 transition-all focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            />
                            @error('schedule')
                                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 py-3.5 font-bold text-white shadow-sm transition-all hover:bg-indigo-700"
                        >
                            <i class="fas fa-save text-sm"></i>
                            Registrar Curso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
