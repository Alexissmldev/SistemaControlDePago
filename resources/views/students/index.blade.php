<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-indigo-600 p-2 shadow-md">
                    <i class="fas fa-users text-white"></i>
                </div>
                <h2 class="text-xl font-bold leading-tight text-gray-800">Directorio de Estudiantes</h2>
            </div>

            <a
                href="{{ route('students.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700"
            >
                <i class="fas fa-plus"></i>
                <span class="hidden md:inline">Inscribir Alumno</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="studentDirectory()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Filtros de búsqueda --}}
            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="relative md:col-span-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="text"
                            x-model.debounce.400ms="filters.search"
                            placeholder="Buscar por nombre o cédula..."
                            class="w-full rounded-lg border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <div>
                        <select
                            x-model="filters.course_id"
                            class="w-full rounded-lg border-gray-200 bg-gray-50 py-2.5 text-sm font-medium text-gray-700 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Todos los cursos</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select
                            x-model="filters.status"
                            class="w-full rounded-lg border-gray-200 bg-gray-50 py-2.5 text-sm font-medium text-gray-700 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Estatus (Todos)</option>
                            <option value="debtors">Solo Deudores</option>
                            <option value="solvent">Solo Solventes</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Área de resultados --}}
            <div class="relative min-h-[300px]">
                {{-- Spinner de carga --}}
                <div
                    x-show="loading"
                    x-transition.opacity
                    class="absolute inset-0 z-20 flex items-center justify-center rounded-xl bg-white/60 backdrop-blur-[1px]"
                >
                    <div class="text-center">
                        <i class="fas fa-circle-notch fa-spin mb-2 text-3xl text-indigo-600"></i>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-500">Cargando...</p>
                    </div>
                </div>

                <div id="students-list">
                    @include('students.partials.list')
                </div>
            </div>
        </div>

        {{-- Componente del Modal de Pago --}}
        <x-payment-modal />
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('studentDirectory', () => ({
                    loading: false,
                    filters: {
                        search: '{{ request('search') }}',
                        course_id: '{{ request('course_id') }}',
                        status: '{{ request('filter_status') }}',
                    },

                    init() {
                        // Detecta cambios en los filtros y recarga la lista
                        this.$watch('filters.search', () => this.fetchStudents());
                        this.$watch('filters.course_id', () => this.fetchStudents());
                        this.$watch('filters.status', () => this.fetchStudents());
                    },

                    async fetchStudents() {
                        this.loading = true;

                        const params = new URLSearchParams({
                            search: this.filters.search,
                            course_id: this.filters.course_id,
                            filter_status: this.filters.status,
                        });

                        try {
                            const response = await fetch(`{{ route('students.index') }}?${params.toString()}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            });

                            const html = await response.text();
                            document.getElementById('students-list').innerHTML = html;

                            // Re-activamos Alpine para que los botones @click funcionen en el nuevo HTML
                            this.$nextTick(() => {
                                Alpine.discoverUninitializedComponents((el) => Alpine.initializeComponent(el));
                            });
                        } catch (error) {
                            console.error('Error al cargar datos:', error);
                        } finally {
                            this.loading = false;
                        }
                    },

                    // Esta función ahora sí es accesible desde los botones con @click
                    openPaymentModal(id, name, debt) {
                        window.dispatchEvent(
                            new CustomEvent('open-payment-modal', {
                                detail: { id, name, debt },
                            }),
                        );
                    },
                }));
            });
        </script>
    @endpush
</x-app-layout>
