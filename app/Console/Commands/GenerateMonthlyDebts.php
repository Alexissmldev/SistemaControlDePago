<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyDebts extends Command
{
    protected $signature = 'debts:generate-monthly';
    protected $description = 'Genera los cargos mensuales según los cursos inscritos de cada alumno';

    public function handle()
    {
        $this->info('Iniciando generación de deudas mensuales...');

        $students = Student::where('status', 'active')->with('courses')->get();

        $mes = now()->translatedFormat('F');
        $anio = now()->year;
        $concepto = "Mensualidad de " . ucfirst($mes) . " " . $anio;

        $generados = 0;

        DB::transaction(function () use ($students, $concepto, &$generados) {
            foreach ($students as $student) {

                // Suma del costo de todos sus cursos inscritos
                $montoTotal = $student->courses->sum('monthly_fee');

                if ($montoTotal <= 0) continue;

                // Evitar duplicados para el mismo mes
                $existeDeuda = Debt::where('student_id', $student->id)
                    ->where('concept', $concepto)
                    ->exists();

                if (!$existeDeuda) {
                    Debt::create([
                        'student_id' => $student->id,
                        'amount'     => $montoTotal,
                        'status'     => 'pending',
                        'concept'    => $concepto,
                    ]);
                    $generados++;
                }
            }
        });

        $this->info("Proceso completado. Se crearon {$generados} registros de deuda para {$mes}.");
    }
}
