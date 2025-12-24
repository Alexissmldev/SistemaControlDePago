<?php

namespace App\Http\Controllers;

use App\Models\{Student, Course, Setting};
use App\Http\Requests\{StoreStudentRequest, UpdateStudentRequest};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{Cache, DB};
use Illuminate\View\View;

class StudentController extends Controller
{
    // Obtiene la tasa de cambio actual desde la caché o la base de datos
    private function getExchangeRate(): float
    {
        return (float) Cache::remember(
            'setting_bcv_rate',
            3600,
            fn() =>
            Setting::where('key', 'bcv_rate')->value('value') ?? 0
        );
    }

    // Listado administrativo con filtros dinámicos y soporte para carga vía AJAX
    public function index(Request $request)
    {
        $courses = Cache::remember(
            'all_courses_list',
            3600,
            fn() =>
            Course::select('id', 'name')->get()
        );

        $students = Student::with('courses')
            ->withSum(['debts' => fn($q) => $q->whereIn('status', ['pending', 'partial'])], 'amount')
            ->filterByCourse($request->course_id)
            ->filterByStatus($request->filter_status)
            ->search($request->search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Respuesta parcial para actualizaciones de tabla sin recargar la página
        if ($request->ajax()) {
            return view('students.partials.list', compact('students'))->render();
        }

        return view('students.index', [
            'students'     => $students,
            'courses'      => $courses,
            'exchangeRate' => $this->getExchangeRate()
        ]);
    }

    public function create(): View
    {
        $courses = Cache::remember(
            'active_courses_list',
            3600,
            fn() =>
            Course::where('status', 'active')->get()
        );
        return view('students.create', compact('courses'));
    }

    // registro de alumno y generación de obligaciones financieras iniciales
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        return DB::transaction(function () use ($request) {
            $student = Student::create($request->validated() + ['status' => 'active']);

            if ($request->has('courses')) {
                $student->courses()->attach($request->courses);
            }

            // Centralizamos la creación de deudas para evitar redundancia
            $this->createInitialDebts($student, $request);

            return redirect()->route('students.show', $student)
                ->with('success', 'Estudiante registrado y deudas iniciales generadas.');
        });
    }

    // Perfil del estudiante con carga de relaciones y cálculo de deuda total
    public function show(Student $student): View
    {
        $student->load(['courses', 'payments.paymentMethods', 'debts']);

        $totalDebt = $student->debts->whereIn('status', ['pending', 'partial'])->sum('amount');

        // Cargamos configuración global para el cálculo de montos en vista
        $settings = Cache::remember(
            'app_settings',
            3600,
            fn() =>
            Setting::pluck('value', 'key')->toArray()
        );

        // Cursos disponibles en los que el alumno aún no participa
        $availableCourses = Course::where('status', 'active')
            ->whereDoesntHave('students', fn($q) => $q->where('student_id', $student->id))
            ->get();

        return view('students.show', [
            'student'          => $student,
            'totalDebt'        => $totalDebt,
            'availableCourses' => $availableCourses,
            'exchangeRate'     => $this->getExchangeRate(),
            'settings'         => $settings
        ]);
    }

    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());
        return redirect()->route('students.show', $student)->with('success', 'Perfil actualizado.');
    }

    // estatus operativo del alumno
    public function toggleStatus(Student $student): RedirectResponse
    {
        $student->update(['status' => $student->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Estatus modificado correctamente.');
    }

    // Inscripción manual en curso individual con generación automática de deuda mensual
    public function attachCourse(Request $request, Student $student): RedirectResponse
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);
        $course = Course::findOrFail($request->course_id);

        if (!$student->courses->contains($course->id)) {
            $student->courses()->attach($course->id);

            if ($course->monthly_fee > 0) {
                $student->debts()->create([
                    'amount'  => $course->monthly_fee,
                    'status'  => 'pending',
                    'concept' => "Mensualidad: {$course->name} (" . now()->locale('es')->monthName . ")",
                ]);
            }
            return back()->with('success', 'Curso vinculado y cargo generado.');
        }

        return back()->with('info', 'El estudiante ya se encuentra en este curso.');
    }

    // Retiro de curso y depuración de deudas pendientes asociadas
    public function detachCourse(Request $request, Student $student): RedirectResponse
    {
        $course = Course::findOrFail($request->course_id);
        $student->courses()->detach($course->id);

        // Eliminamos cargos pendientes que aún no han sido procesados
        $student->debts()
            ->where('status', 'pending')
            ->where('concept', 'LIKE', "%{$course->name}%")
            ->delete();

        return back()->with('success', 'Retiro procesado y deudas pendientes eliminadas.');
    }

    // Método privado para manejar la lógica de cargos administrativos de nuevo ingreso
    private function createInitialDebts(Student $student, $request): void
    {
        if ($request->amount_inscription > 0) {
            $student->debts()->create([
                'amount'  => $request->amount_inscription,
                'status'  => 'pending',
                'concept' => 'Inscripción',
            ]);
        }

        if ($request->amount_month > 0) {
            $month = ucfirst(now()->locale('es')->monthName);
            $student->debts()->create([
                'amount'  => $request->amount_month,
                'status'  => 'pending',
                'concept' => "Mensualidad {$month} " . now()->year,
            ]);
        }
    }
}
