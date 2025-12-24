<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Reglas de validación para la actualización de datos del alumno
    public function rules(): array
    {

        $student = $this->route('student');
        $studentId = is_object($student) ? $student->id : $student;

        return [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'dni'            => [
                'required',
                'string',
                'max:20',
                // Validamos unicidad ignorando el registro actual
                Rule::unique('students', 'dni')->ignore($studentId),
            ],
            'phone'          => 'required|string|max:20',
            'birthdate'      => 'nullable|date',
            'representative' => 'nullable|string|max:255',
        ];
    }
}
