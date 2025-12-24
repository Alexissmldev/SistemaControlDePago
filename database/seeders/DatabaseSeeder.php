<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Setting;
use App\Models\Debt;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'), 
        ]);

        Setting::create([
            'key' => 'bcv_rate',
            'value' => '50.00'
        ]);

        // 3. Crear Cursos Reales
        $salsa = Course::create([
            'name' => 'Salsa Casino Nivel 1',
            'monthly_fee' => 20.00,
            'schedule' => 'Lun-Mie 04:00 PM'
        ]);

        $ballet = Course::create([
            'name' => 'Ballet Infantil',
            'monthly_fee' => 25.00,
            'schedule' => 'Mar-Jue 03:00 PM'
        ]);

        $urbano = Course::create([
            'name' => 'Género Urbano',
            'monthly_fee' => 15.00,
            'schedule' => 'Viernes 05:00 PM'
        ]);

        // 4. Crear 10 Estudiantes Falsos
        $students = Student::factory(10)->create();

        // 5. Asignar Cursos y Crear Deudas a los estudiantes
        foreach ($students as $index => $student) {

            // Inscribir a todos en algún curso aleatorio
            $curso = match ($index % 3) {
                0 => $salsa,
                1 => $ballet,
                2 => $urbano,
            };

            $student->courses()->attach($curso->id);

            // A los primeros 5 les creamos una DEUDA PENDIENTE (Morosos)
            if ($index < 5) {
                Debt::create([
                    'student_id' => $student->id,
                    'concept' => 'Mensualidad ' . now()->format('F'),
                    'amount' => $curso->monthly_fee,
                    'status' => 'pending',
                    'due_date' => now()->addDays(5)
                ]);
            } else {
                // A los otros 5, simulamos que ya pagaron (Deuda pagada)
                Debt::create([
                    'student_id' => $student->id,
                    'concept' => 'Inscripción',
                    'amount' => 10.00,
                    'status' => 'paid',
                    'due_date' => now()->subDays(10)
                ]);
            }
        }
    }
}
