<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auditoria;
use Carbon\Carbon;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        $registros = $query->paginate(15)->appends($request->all());

        // Obtener acciones únicas para el selector del filtro
        $acciones = Auditoria::select('accion')->distinct()->pluck('accion');

        return view('admin.auditoria.index', compact('registros', 'acciones'));
    }

    public function exportar(Request $request)
    {
        $query = $this->buildQuery($request);
        $registros = $query->get();

        $fileName = 'auditoria_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Fecha y Hora', 'Acción', 'Usuario Relacionado', 'Correo Intentado', 'Detalle', 'IP', 'Resultado'];

        $callback = function() use($registros, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($registros as $row) {
                $nombreUsuario = $row->usuario ? $row->usuario->name . ' (' . $row->usuario->email . ')' : 'N/A';
                fputcsv($file, [
                    $row->fecha_hora->format('Y-m-d H:i:s'),
                    $row->accion,
                    $nombreUsuario,
                    $row->correo_intentado ?? 'N/A',
                    $row->detalle,
                    $row->direccion_ip,
                    $row->resultado
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function buildQuery(Request $request)
    {
        $query = Auditoria::with('usuario')->orderBy('fecha_hora', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('detalle', 'like', "%{$keyword}%")
                  ->orWhere('correo_intentado', 'like', "%{$keyword}%")
                  ->orWhereHas('usuario', function($u) use ($keyword) {
                      $u->where('email', 'like', "%{$keyword}%")
                        ->orWhere('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha_hora', '>=', Carbon::parse($request->fecha_inicio)->startOfDay());
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha_hora', '<=', Carbon::parse($request->fecha_fin)->endOfDay());
        }

        return $query;
    }
}
