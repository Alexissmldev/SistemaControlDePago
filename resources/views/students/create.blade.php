<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 p-2 rounded-lg shadow-md">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    Registrar Nuevo Estudiante
                </h2>
            </div>
            <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-indigo-600 font-bold text-sm transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="studentForm()">
        <form action="{{ route('students.store') }}" method="POST" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Bloque 1: Datos Personales --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">1. Información Personal</h3>
                            <i class="fas fa-address-card text-gray-400"></i>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nombres
                                </label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm" placeholder="Ej: Juan José" required>
                                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Apellidos</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm" placeholder="Ej: Pérez" required>
                                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cédula / DNI</label>
                                <input type="text" name="dni" value="{{ old('dni') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm" placeholder="V-00000000" required>
                                @error('dni') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Teléfono</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" 
                                       class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm" placeholder="0412-0000000" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha de Nacimiento</label>
                                <input type="date" name="birthdate" x-model="birthdate" 
                                       class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm" required>
                            </div>

                            {{-- Sección dinámica para representante --}}
                            <div x-show="isMinor()" x-transition class="md:col-span-2 bg-amber-50 p-4 rounded-lg border border-amber-200">
                                <div class="flex items-center gap-2 mb-2 text-amber-700">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="text-xs font-bold uppercase">Datos del Representante</span>
                                </div>
                                <input type="text" name="representative" value="{{ old('representative') }}" 
                                       placeholder="Nombre completo del representante" 
                                       class="w-full rounded-lg border-amber-300 focus:ring-amber-500 shadow-sm">
                                <p class="text-[10px] text-amber-600 mt-1 italic">* Requerido para alumnos menores de 18 años.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bloque 2: Cursos --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">2. Selección de Cursos</h3>
                            <i class="fas fa-layer-group text-gray-400"></i>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($courses as $course)
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none transition hover:bg-gray-50"
                                           :class="selectedCourses.includes('{{ $course->id }}') ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-200'">
                                        
                                        <input type="checkbox" name="courses[]" value="{{ $course->id }}" 
                                               x-model="selectedCourses" class="sr-only">
                                        
                                        <div class="flex w-full justify-between items-center">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900 uppercase">{{ $course->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $course->schedule }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-black text-indigo-600">${{ number_format($course->monthly_fee, 2) }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('courses') <p class="text-red-500 text-sm mt-4 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Resumen y Acción --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-4">
                        
                        <div class="bg-gray-800 rounded-2xl shadow-xl text-white p-6 relative overflow-hidden">
                            <div class="relative z-10">
                                <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                                    <i class="fas fa-file-invoice-dollar text-indigo-400"></i>
                                    Resumen de Ingreso
                                </h3>

                                <div class="space-y-4">
                                    {{-- Control de Inscripción --}}
                                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl border border-white/10">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="charge_inscription" value="1" x-model="chargeInscription" 
                                                   class="rounded text-indigo-500 bg-transparent border-gray-500 focus:ring-0">
                                            <span class="text-sm font-medium" :class="!chargeInscription && 'text-gray-500 line-through'">Inscripción</span>
                                        </div>
                                        <div x-show="chargeInscription" class="flex items-center gap-1">
                                            <span class="text-xs text-gray-400">$</span>
                                            <input type="number" name="amount_inscription" step="0.01" value="20.00" 
                                                   class="w-16 bg-transparent border-none p-0 text-right font-bold text-white focus:ring-0">
                                        </div>
                                    </div>

                                    {{-- Total Mensualidad --}}
                                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl border border-white/10">
                                        <div>
                                            <span class="block text-sm font-medium">Mensualidad</span>
                                            <span class="text-[10px] text-indigo-300" x-text="selectedCourses.length + ' curso(s) seleccionado(s)'"></span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-indigo-400 font-bold">$</span>
                                            <span class="text-xl font-black" x-text="calculateTotal()"></span>
                                            <input type="hidden" name="amount_month" :value="calculateTotal()">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-white/10">
                                    <p class="text-[10px] text-gray-400 text-center italic">
                                        * Al guardar se generarán los cargos correspondientes en la cuenta del alumno.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-save"></i>
                            FINALIZAR REGISTRO
                        </button>

                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function studentForm() {
            return {
                birthdate: '{{ old('birthdate') }}',
                chargeInscription: true,
                selectedCourses: @json(old('courses', [])),
                coursesList: @json($courses),

                isMinor() {
                    if (!this.birthdate) return false;
                    const birth = new Date(this.birthdate);
                    const today = new Date();
                    let age = today.getFullYear() - birth.getFullYear();
                    const m = today.getMonth() - birth.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }
                    return age < 18;
                },

                calculateTotal() {
                    let total = 0;
                    this.coursesList.forEach(course => {
                        if (this.selectedCourses.includes(course.id.toString())) {
                            total += parseFloat(course.monthly_fee);
                        }
                    });
                    return total.toFixed(2);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>