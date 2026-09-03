<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/suscripciones', fn() => view('admin.suscripciones.index'))->name('suscripciones.index');
    Route::get('/usuarios', fn() => view('admin.usuarios.index'))->name('usuarios.index');
    Route::get('/pagos', fn() => view('admin.pagos.index'))->name('pagos.index');
    Route::get('/demostraciones', fn() => view('admin.demostraciones.index'))->name('demostraciones.index');
    Route::get('/exportar', fn() => view('admin.exportar.index'))->name('exportar.index');
    Route::get('/sesiones', fn() => view('admin.sesiones.index'))->name('sesiones.index');
    Route::get('/auditoria', fn() => view('admin.auditoria.index'))->name('auditoria.index');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
