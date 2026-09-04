<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comprobantes;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function index()
    {
        $comprobantes = Comprobantes::with(['suscripcion.usuario', 'administrador'])
            ->orderBy('fecha_carga', 'desc')
            ->paginate(10);

        return view('admin.pagos.index', compact('comprobantes'));
    }

    public function download($id)
    {
        $comprobante = Comprobantes::findOrFail($id);
        
        if (Storage::disk('public')->exists($comprobante->ruta_archivo)) {
            return Storage::disk('public')->download($comprobante->ruta_archivo, $comprobante->nombre_archivo);
        }

        return back()->with('error', 'El archivo no existe.');
    }
}
