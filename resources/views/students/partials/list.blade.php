{{-- Vista Móvil (Cards) --}}
<div class="space-y-4 md:hidden">
    @forelse ($students as $student)
        @php
            $hasDebt = $student->debts_sum_amount > 0;
            $initials = strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1));
        @endphp

        <div
            class="{{ $hasDebt ? 'border-l-red-500' : 'border-l-green-500' }} rounded-xl border-l-4 bg-white p-4 shadow-sm"
        >
            <div class="mb-3 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700"
                    >
                        {{ $initials }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold leading-tight text-gray-800">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h3>
                        <p class="font-mono text-xs text-gray-500">{{ $student->dni }}</p>
                    </div>
                </div>

                <span
                    class="{{ $hasDebt ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }} rounded px-2 py-0.5 text-[10px] font-bold uppercase"
                >
                    {{ $hasDebt ? 'Pendiente' : 'Solvente' }}
                </span>
            </div>

            <div class="flex items-end justify-between border-t border-gray-100 pt-3">
                <div>
                    <span class="block text-[10px] font-black uppercase text-gray-400">Deuda</span>
                    <span class="{{ $hasDebt ? 'text-red-600' : 'text-gray-700' }} text-lg font-black">
                        ${{ number_format($student->debts_sum_amount ?? 0, 2) }}
                    </span>
                </div>

                <div class="flex gap-2">
                    <a
                        href="{{ route('students.show', $student->id) }}"
                        class="rounded-lg bg-gray-50 p-2 text-gray-400 transition hover:text-indigo-600"
                    >
                        <i class="fas fa-eye text-sm"></i>
                    </a>

                    @if ($hasDebt)
                        <button
                            type="button"
                            @click="openPaymentModal('{{ $student->id }}', '{{ addslashes($student->first_name . ' ' . $student->last_name) }}', '{{ $student->debts_sum_amount }}')"
                            class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <i class="fas fa-dollar-sign"></i>
                            Pagar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-xl border border-dashed border-gray-300 bg-white py-8 text-center">
            <p class="text-sm text-gray-400">No se encontraron estudiantes</p>
        </div>
    @endforelse
</div>

{{-- Vista Desktop (Tabla) --}}
<div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm md:block">
    <table class="w-full text-left">
        <thead class="border-b border-gray-200 bg-gray-50">
            <tr class="text-[11px] font-bold uppercase tracking-widest text-gray-500">
                <th class="px-6 py-4">Estudiante</th>
                <th class="px-6 py-4 text-center">Estado</th>
                <th class="px-6 py-4 text-right">Saldo Pendiente</th>
                <th class="px-6 py-4 text-center">Acciones</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse ($students as $student)
                @php
                    $hasDebt = $student->debts_sum_amount > 0;
                @endphp

                <tr class="group transition duration-150 hover:bg-gray-50/50">
                    <td class="whitespace-nowrap px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-indigo-100 bg-indigo-50 text-[10px] font-bold text-indigo-600"
                            >
                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="mb-1 text-sm font-bold leading-none text-gray-800">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </div>
                                <div class="font-mono text-[10px] text-gray-400">{{ $student->dni }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span
                            class="{{ $hasDebt ? 'border-red-100 bg-red-50 text-red-600' : 'border-green-100 bg-green-50 text-green-600' }} inline-flex items-center rounded border px-2.5 py-0.5 text-[10px] font-bold uppercase"
                        >
                            {{ $hasDebt ? 'Pendiente' : 'Solvente' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <span class="{{ $hasDebt ? 'text-red-600' : 'text-gray-600' }} text-sm font-black">
                            ${{ number_format($student->debts_sum_amount ?? 0, 2) }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a
                                href="{{ route('students.show', $student->id) }}"
                                class="rounded-md p-2 text-gray-400 transition hover:bg-indigo-50 hover:text-indigo-600"
                                title="Ver Perfil"
                            >
                                <i class="fas fa-eye text-sm"></i>
                            </a>

                            @if ($hasDebt)
                                <button
                                    type="button"
                                    @click="openPaymentModal('{{ $student->id }}', '{{ addslashes($student->first_name . ' ' . $student->last_name) }}', '{{ $student->debts_sum_amount }}')"
                                    class="flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-600 shadow-sm transition hover:border-indigo-500 hover:text-indigo-600"
                                >
                                    <i class="fas fa-hand-holding-dollar text-xs"></i>
                                    <span>Pagar</span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-user-slash mb-3 text-4xl text-gray-200"></i>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">
                                No hay alumnos registrados
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
@if ($students->hasPages())
    <div class="mt-6 px-2">
        {{ $students->links() }}
    </div>
@endif
