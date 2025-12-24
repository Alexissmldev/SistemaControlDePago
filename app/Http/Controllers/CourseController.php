<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    // Muestra el listado de cursos priorizando los activos

    public function index(): View
    {
        $courses = Course::withCount('students')
            ->orderBy('status', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('courses.create');
    }

    // Registra un nuevo curso con estatus inicial activo

    public function store(CourseRequest $request): RedirectResponse
    {
        Course::create($request->validated() + ['status' => 'active']);

        return redirect()
            ->route('courses.index')
            ->with('success', '¡Curso creado exitosamente!');
    }

    // Formulario de edición de un curso existente

    public function edit(Course $course): View
    {
        return view('courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('courses.index')
            ->with('success', 'Información del curso actualizada.');
    }

    // Elimina el curso o lo desactiva si posee alumnos vinculados

    public function destroy(Course $course): RedirectResponse
    {
        // Cargamos el conteo de alumnos para validar integridad
        $course->loadCount('students');

        if ($course->students_count > 0) {
            $course->update(['status' => 'inactive']);

            return back()->with('warning', 'El curso tiene alumnos activos. Se ha marcado como cerrado para nuevas inscripciones.');
        }

        $course->delete();

        return back()->with('success', 'Registro eliminado permanentemente.');
    }
}