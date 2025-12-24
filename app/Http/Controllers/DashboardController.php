<?php

namespace App\Http\Controllers;

use App\Models\{Student, Course, Setting, Debt};
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Buscamos el valor en la columna 'value' donde la 'key' sea 'bcv_rate'
        $bcvRate = Setting::where('key', 'bcv_rate')->value('value') ?? 1;

        $totalStudents = Student::count();
        $totalCourses  = Course::where('status', 'active')->count();

        $totalDebtUsd = Debt::whereIn('status', ['pending', 'partial'])->sum('amount');

        // Aplicamos truncado a 2 decimales para el monto en Bs
        $totalDebtBs = floor(((float)$totalDebtUsd * (float)$bcvRate) * 100) / 100;

        //  deudores usando agregados de Eloquent
        $debtors = Student::withSum(['debts' => function ($q) {
            $q->whereIn('status', ['pending', 'partial']);
        }], 'amount')
            ->having('debts_sum_amount', '>', 0)
            ->orderBy('debts_sum_amount', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'totalCourses',
            'totalDebtUsd',
            'totalDebtBs',
            'bcvRate',
            'debtors'
        ));
    }
}
