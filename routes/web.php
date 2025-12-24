<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    StudentController,
    CourseController,
    SettingController,
    PaymentController,
    DashboardController
};

Route::get('/', fn() => redirect()->route('login'));

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de Perfil Administrativo
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Módulo de Estudiantes
    Route::resource('students', StudentController::class);
    Route::prefix('students/{id}')->group(function () {
        Route::post('toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggleStatus');
        Route::post('attach-course', [StudentController::class, 'attachCourse'])->name('students.attachCourse');
        Route::post('detach-course', [StudentController::class, 'detachCourse'])->name('students.detachCourse');
    });

    // Módulo de Cursos 
    Route::resource('courses', CourseController::class);

    // Configuración General y Tasa BCV
    Route::controller(SettingController::class)->prefix('settings')->group(function () {
        Route::get('/', 'index')->name('settings.index');
        Route::post('update', 'update')->name('settings.update');
        Route::post('sync', 'sync')->name('settings.sync');
    });

    // Procesamiento de Pagos
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
});

require __DIR__ . '/auth.php';
