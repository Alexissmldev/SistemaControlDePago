<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="rounded-lg bg-indigo-600 p-2">
                <i class="fas fa-layer-group text-lg text-white"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Gestión de Cursos</h2>
        </div>
    </x-slot>

    <div class="py-10" x-data="courseManager()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col items-center justify-between gap-4 md:flex-row">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Administra los detalles de los cursos disponibles.</h3>
                </div>
                <button
                    @click="openCreateModal()"
                    class="flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 font-bold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <i class="fas fa-plus"></i>
                    Nuevo Curso
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <div
                        class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
                    >
                        <div class="{{ $course->status === 'active' ? 'bg-indigo-500' : 'bg-gray-400' }} h-1.5"></div>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="mb-4 flex items-start justify-between">
                                <h4 class="text-lg font-bold leading-tight text-gray-800">{{ $course->name }}</h4>
                                @if ($course->status === 'inactive')
                                    <span
                                        class="rounded border border-red-100 bg-red-50 px-2 py-1 text-[10px] font-black uppercase text-red-600"
                                    >
                                        Cerrado
                                    </span>
                                @endif
                            </div>

                            <div class="mb-6 space-y-2 text-sm text-gray-600">
                                <p class="flex items-center gap-2">
                                    <i class="far fa-clock text-indigo-400"></i>
                                    {{ $course->schedule }}
                                </p>
                                <p class="flex items-center gap-2 font-semibold text-green-600">
                                    <i class="fas fa-tag"></i>
                                    ${{ number_format($course->monthly_fee, 2) }}
                                </p>
                            </div>

                            <div class="mt-auto flex gap-2 border-t border-gray-50 pt-4">
                                <button
                                    @click="openEditModal({{ json_encode($course) }})"
                                    class="flex-1 rounded-md bg-gray-50 py-2 text-xs font-bold uppercase text-gray-600 transition hover:bg-gray-100"
                                >
                                    Editar
                                </button>
                                <a
                                    href="{{ route('students.index', ['course_id' => $course->id]) }}"
                                    class="flex-1 rounded-md bg-indigo-50 py-2 text-center text-xs font-bold uppercase text-indigo-700 transition hover:bg-indigo-100"
                                >
                                    Alumnos
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        </div>

        {{-- Modal Único --}}
        <x-modal name="course-form-modal" focusable>
            <div class="p-6">
                <h2
                    class="mb-5 border-b pb-3 text-lg font-bold text-gray-800"
                    x-text="isEdit ? 'Editar Curso' : 'Registrar Nuevo Curso'"
                ></h2>

                <form :action="formUrl" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase text-gray-500">Nombre</label>
                            <input
                                type="text"
                                name="name"
                                x-model="formData.name"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500"
                                required
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-xs font-bold uppercase text-gray-500">Horario</label>
                                <input
                                    type="text"
                                    name="schedule"
                                    x-model="formData.schedule"
                                    class="w-full rounded-lg border-gray-300 text-sm"
                                    required
                                />
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-bold uppercase text-gray-500">Costo ($)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="monthly_fee"
                                    x-model="formData.monthly_fee"
                                    class="w-full rounded-lg border-gray-300 text-sm font-bold"
                                    required
                                />
                            </div>
                        </div>

                        <div x-show="isEdit" class="rounded-lg border bg-gray-50 p-4">
                            <p class="mb-3 text-center text-xs font-bold uppercase text-gray-500">
                                Estado de inscripción
                            </p>
                            <div class="flex justify-center gap-6">
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="active"
                                        x-model="formData.status"
                                        class="text-indigo-600"
                                    />
                                    <span class="text-sm text-gray-700">Abierto</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="inactive"
                                        x-model="formData.status"
                                        class="text-red-600"
                                    />
                                    <span class="text-sm text-gray-700">Cerrado</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$dispatch('close-modal', 'course-form-modal')"
                            class="text-sm font-bold text-gray-500 hover:text-gray-700"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-6 py-2 text-sm font-bold text-white shadow-md transition hover:bg-indigo-700"
                        >
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    @push('scripts')
        <script>
            function courseManager() {
                return {
                    isEdit: false,
                    formUrl: '{{ route('courses.store') }}',
                    formData: {
                        name: '',
                        schedule: '',
                        monthly_fee: '',
                        status: 'active',
                    },

                    openCreateModal() {
                        this.isEdit = false;
                        this.formUrl = '{{ route('courses.store') }}';
                        this.formData = { name: '', schedule: '', monthly_fee: '', status: 'active' };
                        this.$dispatch('open-modal', 'course-form-modal');
                    },

                    openEditModal(course) {
                        this.isEdit = true;
                        this.formUrl = `{{ url('courses') }}/${course.id}`;
                        this.formData = {
                            name: course.name,
                            schedule: course.schedule,
                            monthly_fee: course.monthly_fee,
                            status: course.status,
                        };
                        this.$dispatch('open-modal', 'course-form-modal');
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
