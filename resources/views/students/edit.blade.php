<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <div class="bg-amber-100 p-2 rounded-lg text-amber-600">
                    <i class="fas fa-user-edit"></i>
                </div>
                Editar Estudiante
            </h2>
            <a href="{{ route('students.show', $student->id) }}" class="text-gray-500 hover:text-indigo-600 font-bold text-sm flex items-center gap-2 transition px-3 py-2 rounded-lg hover:bg-indigo-50">
                <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Volver al Perfil</span>
            </a>
        </div>
    </x-slot>

    <div class="bg-gray-100 min-h-screen p-4 md:p-6 w-full">
        
        <form 
            action="{{ route('students.update', $student->id) }}" 
            method="POST"
            class="max-w-4xl mx-auto"
            x-data="{ 
                // Inicializamos con la fecha de la base de datos
birthdate: '{{ old('birthdate', \Carbon\Carbon::parse($student->birthdate)->format('Y-m-d')) }}',                
                get isMinor() {
                    if (!this.birthdate) return false;
                    const dob = new Date(this.birthdate);
                    const diff_ms = Date.now() - dob.getTime();
                    const age_dt = new Date(diff_ms); 
                    return Math.abs(age_dt.getUTCFullYear() - 1970) < 18;
                }
            }"
        >
            @csrf
            @method('PUT') <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Información Personal</h3>
                        <p class="text-xs text-gray-500">Actualizar datos del alumno: <span class="font-bold text-indigo-600">{{ $student->first_name }} {{ $student->last_name }}</span></p>
                    </div>
                    <i class="fas fa-pen text-gray-300 text-2xl"></i>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nombres <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="w-full rounded-lg bg-gray-50 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-gray-800 font-semibold" required>
                            @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Apellidos <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="w-full rounded-lg bg-gray-50 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-gray-800 font-semibold" required>
                            @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cédula / DNI <span class="text-red-500">*</span></label>
                            <input type="text" name="dni" value="{{ old('dni', $student->dni) }}" class="w-full rounded-lg bg-gray-50 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-gray-800 font-semibold" required>
                            @error('dni') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Teléfono <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-lg bg-gray-50 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-gray-800 font-semibold" required>
                            @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                            <input type="date" name="birthdate" x-model="birthdate" class="w-full rounded-lg bg-gray-50 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-gray-800 font-semibold" required>
                        </div>

                        <div x-show="isMinor" x-transition class="md:col-span-2 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                            <label class="block text-xs font-bold text-yellow-700 uppercase mb-1">
                                <i class="fas fa-child mr-1"></i> Representante Legal
                            </label>
                            <input type="text" name="representative" value="{{ old('representative', $student->representative) }}" placeholder="Nombre del Padre o Madre" class="w-full rounded-lg bg-white border-yellow-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-gray-800 font-semibold">
                        </div>

                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    
                    <a href="{{ route('students.show', $student->id) }}" class="w-full sm:w-auto px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-bold hover:bg-gray-50 text-center transition">
                        Cancelar
                    </a>

                    <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-md transition flex items-center justify-center gap-2">
                        <i class="fas fa-sync-alt"></i> Actualizar Datos
                    </button>
                    
                </div>
            </div>
            
            <p class="text-center text-xs text-gray-400 mt-4">
                <i class="fas fa-info-circle mr-1"></i> Para cambiar los cursos inscritos, dirígete al perfil del estudiante.
            </p>

        </form>
    </div>
</x-app-layout> 