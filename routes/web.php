<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuscripcionController;
use App\Http\Controllers\Admin\UsuarioController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    
    // Rutas de Suscripciones
    Route::get('/suscripciones', [SuscripcionController::class, 'index'])->name('suscripciones.index');
    Route::post('/suscripciones/{id}/dias', [SuscripcionController::class, 'gestionarDias'])->name('suscripciones.dias');
    
    // Rutas de Pagos
    Route::get('/pagos', [\App\Http\Controllers\Admin\PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/{id}/download', [\App\Http\Controllers\Admin\PagoController::class, 'download'])->name('pagos.download');

    // Rutas de Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
    // Rutas de Demostraciones Manuales
    Route::get('/demostraciones', [\App\Http\Controllers\Admin\ExcepcionDemoController::class, 'index'])->name('demostraciones.index');
    Route::post('/demostraciones/index', [\App\Http\Controllers\Admin\ExcepcionDemoController::class, 'otorgar'])->name('demostraciones.otorgar');

    // Rutas de Exportación
    Route::get('/exportar', [\App\Http\Controllers\Admin\ExportacionController::class, 'index'])->name('exportar.index');
    Route::post('/exportar/procesar', [\App\Http\Controllers\Admin\ExportacionController::class, 'procesar'])->name('exportar.procesar');

    // Rutas de Configuración del Sistema
    Route::get('/configuracion', [\App\Http\Controllers\Admin\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/general', [\App\Http\Controllers\Admin\ConfiguracionController::class, 'guardarGeneral'])->name('configuracion.general');
    Route::post('/configuracion/plan', [\App\Http\Controllers\Admin\ConfiguracionController::class, 'guardarPlan'])->name('configuracion.plan');
    Route::post('/configuracion/permisos/{id_rol}', [\App\Http\Controllers\Admin\ConfiguracionController::class, 'actualizarPermisos'])->name('configuracion.permisos');

    // Rutas de Sesiones
    Route::get('/sesiones', [\App\Http\Controllers\Admin\SesionController::class, 'index'])->name('sesiones.index');
    Route::delete('/sesiones/{id}', [\App\Http\Controllers\Admin\SesionController::class, 'destroy'])->name('sesiones.destroy');
    Route::post('/sesiones/destroy-all', [\App\Http\Controllers\Admin\SesionController::class, 'destroyAll'])->name('sesiones.destroy-all');
    // Rutas de Auditoria
    Route::get('/auditoria', [\App\Http\Controllers\Admin\AuditoriaController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/exportar', [\App\Http\Controllers\Admin\AuditoriaController::class, 'exportar'])->name('auditoria.exportar');

    // Rutas de Soporte
    Route::get('/soporte', [\App\Http\Controllers\Admin\SoporteController::class, 'index'])->name('soporte.index');
    Route::post('/soporte/tickets', [\App\Http\Controllers\Admin\SoporteController::class, 'crearTicket'])->name('soporte.tickets.crear');
    Route::put('/soporte/tickets/{id}/estado', [\App\Http\Controllers\Admin\SoporteController::class, 'cambiarEstado'])->name('soporte.tickets.estado');
    Route::post('/soporte/tickets/{id}/responder', [\App\Http\Controllers\Admin\SoporteController::class, 'responderTicket'])->name('soporte.tickets.responder');
    Route::post('/soporte/notificaciones/{id}/leida', [\App\Http\Controllers\Admin\SoporteController::class, 'marcarLeida'])->name('soporte.notificaciones.leida');
    Route::post('/soporte/notificaciones/leer-todas', [\App\Http\Controllers\Admin\SoporteController::class, 'marcarTodasLeidas'])->name('soporte.notificaciones.leer-todas');
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
