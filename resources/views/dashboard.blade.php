<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 leading-tight">
                    Panel de Control
                </h2>
                <p class="text-sm text-gray-500 font-medium">Resumen general de la academia</p>
            </div>
            
            <div class="bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-2xl flex items-center gap-3">
                <div class="bg-indigo-600 text-white p-2 rounded-xl text-xs">
                    <i class="fas fa-university"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-indigo-400 uppercase leading-none">Tasa BCV</p>
                    <p class="text-sm font-black text-indigo-700 leading-tight">
                        Bs. {{ number_format(floor($bcvRate * 100) / 100, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- TARJETAS DE ESTADÍSTICAS (3 COLUMNAS) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-2xl">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <span class="text-green-500 text-xs font-bold">+ Activos</span>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Alumnos</p>
                        <h4 class="text-3xl font-black text-gray-800">{{ $totalStudents }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-purple-100 text-purple-600 p-3 rounded-2xl">
                            <i class="fas fa-layer-group text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Grupos Activos</p>
                        <h4 class="text-3xl font-black text-gray-800">{{ $totalCourses }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between border-b-4 border-b-red-500">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-red-100 text-red-600 p-3 rounded-2xl">
                            <i class="fas fa-hand-holding-usd text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Por Cobrar (USD)</p>
                        <h4 class="text-3xl font-black text-red-600">
                            ${{ number_format(floor($totalDebtUsd * 100) / 100, 2, ',', '.') }}
                        </h4>
                    </div>
                </div>
                
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- LISTADO DE DEUDORES --}}
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Deudores Principales</h3>
                        <a href="{{ route('students.index', ['filter_status' => 'debtors']) }}" class="text-xs font-bold text-indigo-600 hover:underline">Ver todos</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($debtors as $debtor)
                            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 text-xs">
                                        {{ substr($debtor->first_name, 0, 1) }}{{ substr($debtor->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $debtor->first_name }} {{ $debtor->last_name }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $debtor->dni }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-red-600">
                                        ${{ number_format(floor($debtor->debts_sum_amount * 100) / 100, 2, ',', '.') }}
                                    </p>
                                    <a href="{{ route('students.show', $debtor->id) }}" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-600 uppercase">Ver perfil</a>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-gray-400 text-sm italic">
                                <i class="fas fa-check-circle text-2xl mb-2 text-green-200"></i>
                                <p>No hay deudas pendientes.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ACCIONES RÁPIDAS --}}
                <div class="space-y-6">
                    <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden group">
                        <i class="fas fa-rocket absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
                        <h4 class="font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-400"></i> Acciones
                        </h4>
                        <div class="space-y-3 relative z-10">
                            <a href="{{ route('students.create') }}" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 p-4 rounded-2xl transition text-sm font-medium border border-white/5">
                                <i class="fas fa-plus-circle text-blue-400"></i> Inscribir Alumno
                            </a>
                            <a href="{{ route('courses.index') }}" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 p-4 rounded-2xl transition text-sm font-medium border border-white/5">
                                <i class="fas fa-calendar-plus text-purple-400"></i> Ver Grupos
                            </a>
                            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 p-4 rounded-2xl transition text-sm font-medium border border-white/5">
                                <i class="fas fa-sync-alt text-green-400"></i> Tasa BCV
                            </a>
                        </div>
                    </div>

                  
                </div>
            </div>

        </div>
    </div>
</x-app-layout>