<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-indigo-600 p-2 shadow-md">
                    <i class="fas fa-user-graduate text-white"></i>
                </div>
                <h2 class="text-xl font-bold leading-tight text-gray-800">Expediente del Estudiante</h2>
            </div>
            <a
                href="{{ route('students.index') }}"
                class="flex items-center gap-2 text-sm font-bold text-gray-500 transition hover:text-indigo-600"
            >
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </x-slot>

    @php
        $phoneClean = preg_replace('/[^0-9]/', '', $student->phone);
        $initials = strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1));

        $datosPago = "*Datos de Pago:*\n" . 'Banco: ' . ($globalSettings['pay_bank'] ?? 'N/A') . "\n" . 'C.I: ' . ($globalSettings['pay_dni'] ?? 'N/A') . "\n" . 'Tel: ' . ($globalSettings['pay_phone'] ?? 'N/A');

        $templates = [
            'cobro' => str_replace(['{nombre}', '{monto}', '{datos_pago}'], [$student->first_name, "*$" . number_format($totalDebt, 2) . '*', $datosPago], $globalSettings['ws_msg_cobro'] ?? ''),
            'welcome' => str_replace('{nombre}', $student->first_name, $globalSettings['ws_msg_welcome'] ?? ''),
            'schedule' => str_replace(['{nombre}', '{lista_cursos}'], [$student->first_name, $student->courses->pluck('name')->implode(', ')], $globalSettings['ws_msg_schedule'] ?? ''),
        ];
    @endphp

    <div class="py-8" x-data="{ openHistory: false, openCalendar: false, showEnroll: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-6 flex flex-col items-start justify-between gap-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:flex-row md:items-center"
            >
                <div class="flex items-center gap-5">
                    <div
                        class="flex h-16 w-16 -rotate-2 transform items-center justify-center rounded-2xl bg-indigo-600 text-2xl font-black text-white shadow-lg"
                    >
                        {{ $initials }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-black leading-tight text-gray-800">{{ $student->fullName }}</h1>
                        <div class="mt-1 flex items-center gap-3">
                            <span
                                class="{{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider"
                            >
                                {{ $student->status === 'active' ? 'Activo' : 'Retirado' }}
                            </span>
                            <span class="font-mono text-xs text-gray-400">DNI: {{ $student->dni }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex w-full gap-2 md:w-auto">
                    <div class="relative flex-1 md:flex-none" x-data="{ openWs: false }">
                        <button
                            @click="openWs = !openWs"
                            @click.away="openWs = false"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-emerald-600"
                        >
                            <i class="fab fa-whatsapp text-lg"></i>
                            WhatsApp
                        </button>

                        <div
                            x-show="openWs"
                            x-cloak
                            x-transition
                            class="absolute right-0 z-50 mt-2 w-72 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl"
                        >
                            @foreach ($templates as $key => $msg)
                                @if ($key != 'cobro' || $totalDebt > 0)
                                    <a
                                        href="https://wa.me/{{ $phoneClean }}?text={{ urlencode($msg) }}"
                                        target="_blank"
                                        class="block border-b border-gray-50 px-4 py-3 transition last:border-0 hover:bg-indigo-50"
                                    >
                                        <p class="text-sm font-bold text-gray-700">
                                            <i class="fas fa-paper-plane mr-2 text-indigo-400"></i>
                                            {{ $globalSettings['ws_title_' . $key] ?? ucfirst($key) }}
                                        </p>
                                        <p class="mt-0.5 truncate text-[10px] text-gray-400">{{ $msg }}</p>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <a
                        href="{{ route('students.edit', $student->id) }}"
                        class="rounded-xl bg-gray-100 p-2.5 text-gray-500 transition hover:text-indigo-600"
                    >
                        <i class="fas fa-user-edit"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6">
                    <div
                        class="{{ $totalDebt > 0 ? 'border-red-200 bg-red-50/20' : 'border-green-200 bg-green-50/20' }} rounded-2xl border bg-white p-6 shadow-sm"
                    >
                        <h3 class="mb-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                            Deuda Pendiente
                        </h3>
                        <div class="{{ $totalDebt > 0 ? 'text-red-600' : 'text-green-600' }} mb-4 text-4xl font-black">
                            ${{ number_format($totalDebt, 2) }}
                        </div>

                        <button
                            @click="$dispatch('open-payment-modal', { id: '{{ $student->id }}', name: '{{ addslashes($student->fullName) }}', debt: '{{ $totalDebt }}' })"
                            class="flex w-full items-center justify-center gap-3 rounded-xl bg-indigo-600 py-4 text-xs font-black uppercase tracking-widest text-white shadow-lg transition hover:bg-indigo-700"
                        >
                            <i class="fas fa-hand-holding-dollar"></i>
                            Registrar Pago
                        </button>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-5 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800">Cursos</h3>
                            <button
                                @click="showEnroll = !showEnroll"
                                class="text-[10px] font-black uppercase text-indigo-600 hover:underline"
                            >
                                + Inscribir
                            </button>
                        </div>

                        <div x-show="showEnroll" x-collapse x-cloak class="mb-5 rounded-xl border bg-gray-50 p-4">
                            <form action="{{ route('students.attachCourse', $student->id) }}" method="POST">
                                @csrf
                                <select
                                    name="course_id"
                                    class="mb-3 w-full rounded-lg border-gray-200 text-xs focus:ring-indigo-500"
                                >
                                    @foreach ($availableCourses as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->name }} - ${{ $course->monthly_fee }}
                                        </option>
                                    @endforeach
                                </select>
                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-gray-800 py-2 text-[10px] font-bold uppercase text-white"
                                >
                                    Confirmar
                                </button>
                            </form>
                        </div>

                        <div class="space-y-3">
                            @forelse ($student->courses as $course)
                                <div
                                    class="flex items-center justify-between rounded-xl border border-gray-100 p-3 transition hover:bg-gray-50"
                                >
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $course->name }}</p>
                                        <p class="text-[10px] font-bold uppercase text-gray-400">
                                            {{ $course->schedule }}
                                        </p>
                                    </div>
                                    <form
                                        action="{{ route('students.detachCourse', $student->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Retirar del curso?');"
                                    >
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}" />
                                        <button type="submit" class="px-2 text-gray-300 transition hover:text-red-500">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="py-4 text-center text-xs italic text-gray-400">Sin cursos activos</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800">Estado de Solvencia</h3>
                                <p class="text-xs font-medium text-gray-400">Últimos meses registrados</p>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    @click="openHistory = true"
                                    class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-bold uppercase text-gray-600 transition hover:bg-gray-200"
                                >
                                    Recibos
                                </button>
                                <button
                                    @click="openCalendar = true"
                                    class="rounded-lg bg-indigo-50 px-4 py-2 text-[10px] font-bold uppercase text-indigo-600 transition hover:bg-indigo-100"
                                >
                                    Ver Calendario
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            @foreach (range(max(1, date('n') - 3), date('n')) as $m)
                                @php
                                    $status = $student->getMonthStatus($m, date('Y'));
                                    $monthName = \Carbon\Carbon::create()
                                        ->month($m)
                                        ->locale('es')->monthName;
                                @endphp

                                <div
                                    class="{{ $status['class'] }} flex flex-col items-center justify-center rounded-2xl border-2 p-4 transition"
                                >
                                    <span class="mb-2 text-[10px] font-black uppercase opacity-60">
                                        {{ substr($monthName, 0, 3) }}
                                    </span>
                                    <i class="fas {{ $status['icon'] }} mb-2 text-xl"></i>
                                    <span class="text-[9px] font-black uppercase">{{ $status['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4">
                        <form
                            action="{{ route('students.toggleStatus', $student->id) }}"
                            method="POST"
                            onsubmit="return confirm('¿Cambiar estatus del alumno?');"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="{{ $student->status === 'active' ? 'border-red-100 text-red-600 hover:bg-red-50' : 'border-green-100 text-green-600 hover:bg-green-50' }} w-full rounded-xl border-2 py-4 text-xs font-black uppercase tracking-widest transition"
                            >
                                {{ $student->status === 'active' ? 'Retirar Alumno' : 'Reincorporar Alumno' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal de Historial --}}
        <div x-show="openHistory" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openHistory = false"></div>
            <div class="relative max-h-[90vh] w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl">
                <div class="flex items-center justify-between bg-gray-800 p-5 text-white">
                    <h3 class="flex items-center gap-2 font-bold italic">
                        <i class="fas fa-receipt text-indigo-400"></i>
                        Historial de Pagos
                    </h3>
                    <button @click="openHistory = false" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="max-h-[70vh] overflow-y-auto">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 z-10 border-b border-gray-100 bg-gray-50">
                            <tr class="text-[10px] font-black uppercase text-gray-400">
                                <th class="py-4 pl-6">Nº</th>
                                <th class="py-4">Fecha</th>
                                <th class="py-4">Métodos</th>
                                <th class="py-4 pr-6 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($student->payments as $payment)
                                <tr class="text-xs transition hover:bg-gray-50">
                                    <td class="py-4 pl-6 font-mono font-bold text-indigo-600">
                                        #{{ $payment->receipt_number }}
                                    </td>
                                    <td class="py-4">{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td class="py-4">
                                        @foreach ($payment->paymentMethods as $method)
                                            <span
                                                class="mr-1 rounded bg-indigo-50 px-2 py-0.5 text-[9px] font-black text-indigo-600"
                                            >
                                                {{ $method->method }}:
                                                {{ $method->currency == 'BS' ? $method->amount . ' Bs' : '$' . $method->amount }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="py-4 pr-6 text-right font-black text-gray-800">
                                        ${{ number_format($payment->total_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center italic text-gray-400">
                                        No hay registros de pago.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-payment-modal />
</x-app-layout>
