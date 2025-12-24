<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'         => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'dni'                => 'required|string|unique:students,dni|max:20',
            'birthdate'          => 'required|date',
            'phone'              => 'required|string|max:20',
            'representative'     => 'nullable|string|max:255',
            'amount_inscription' => 'nullable|numeric|min:0',
            'amount_month'       => 'nullable|numeric|min:0',
            'courses'            => 'required|array|min:1',
            'courses.*'          => 'exists:courses,id',
        ];
    }

    // Mensajes de error 
    public function messages(): array
    {
        return [
            'dni.unique'       => 'La cédula ingresada ya se encuentra en nuestra base de datos.',
            'courses.required' => 'Es obligatorio asignar al menos un curso al estudiante.',
            'courses.*.exists' => 'Uno de los cursos seleccionados no es válido o no existe.',
        ];
    }
}
