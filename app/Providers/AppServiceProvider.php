<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function ($event) {
                \App\Models\Sesion::create([
                    'usuario_id' => $event->user->id,
                    'dispositivo' => request()->userAgent(),
                    'direccion_ip' => request()->ip(),
                    'fecha_inicio' => now(),
                    'ultima_actividad' => now(),
                    'activa' => true,
                ]);
            }
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            function ($event) {
                if ($event->user) {
                    \App\Models\Sesion::where('usuario_id', $event->user->id)
                        ->where('activa', true)
                        ->where('dispositivo', request()->userAgent())
                        ->where('direccion_ip', request()->ip())
                        ->update([
                            'activa' => false,
                            'fecha_cierre' => now()
                        ]);
                }
            }
        );
    }
}
