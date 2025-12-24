<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Gestión de Cursos
            </h2>
            <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-indigo-600 font-bold transition flex items-center gap-2 text-sm">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </x-slot>

    <div class="bg-slate-100 min-h-[calc(100vh-9rem)] flex items-center justify-center px-4 py-8">
        
        <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl p-8 relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500"></div>

            <div class="mb-6 text-center">
                <h3 class="text-2xl font-black text-gray-800">Editar Grupo</h3>
                <p class="text-sm text-gray-400">Modifica los detalles del curso</p>
            </div>
            
            <form action="{{ route('courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $course->name) }}" 
                        class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none focus:ring-2 focus:ring-amber-500/50 text-gray-800 font-bold placeholder-gray-300 transition-all" 
                        required>
                    @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4 mb-5">
                    <div class="w-1/2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Precio ($)</label>
                        <input type="number" step="0.01" name="monthly_fee" value="{{ old('monthly_fee', $course->monthly_fee) }}" 
                            class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none focus:ring-2 focus:ring-green-500/50 text-gray-800 font-bold placeholder-gray-300 transition-all" 
                            required>
                    </div>

                    <div class="w-1/2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Horario</label>
                        <input type="text" name="schedule" value="{{ old('schedule', $course->schedule) }}" 
                            class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none focus:ring-2 focus:ring-amber-500/50 text-gray-800 font-bold placeholder-gray-300 transition-all" 
                            required>
                    </div>
                </div>

                <div class="mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-3 text-center">Estado del Curso</label>
                    
                    <div class="flex justify-center gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="active" class="peer sr-only" {{ $course->status == 'active' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-lg border-2 border-transparent text-gray-400 font-bold text-xs peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-200 transition-all text-center w-24">
                                <i class="fas fa-door-open mb-1 block text-lg"></i>
                                Abierto
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="inactive" class="peer sr-only" {{ $course->status == 'inactive' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-lg border-2 border-transparent text-gray-400 font-bold text-xs peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-200 transition-all text-center w-24">
                                <i class="fas fa-door-closed mb-1 block text-lg"></i>
                                Cerrado
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt"></i> Actualizar Datos
                </button>

            </form>
        </div>
    </div>
</x-app-layout>