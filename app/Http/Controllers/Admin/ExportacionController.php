<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Subscripcion;
use App\Models\Auditoria;

class ExportacionController extends Controller
{
    public function index()
    {
        return view('admin.exportar.index');
    }

    public function procesar(Request $request)
    {
        $request->validate([
            'modulo' => 'required|in:usuarios,suscripciones,auditoria',
            'columnas' => 'required|array|min:1',
            'formato' => 'required|in:csv,pdf'
        ]);

        $modulo = $request->modulo;
        $columnas = $request->columnas;
        $formato = $request->formato;

        $datos = $this->obtenerDatos($modulo, $columnas);

        if ($formato === 'csv') {
            return $this->exportarCSV($modulo, $columnas, $datos);
        } else {
            return $this->exportarPDF($modulo, $columnas, $datos);
        }
    }

    private function obtenerDatos($modulo, $columnas)
    {
        switch ($modulo) {
            case 'usuarios':
                return User::select($columnas)->get();
            case 'suscripciones':
                return Subscripcion::select($columnas)->with('usuario')->get();
            case 'auditoria':
                return Auditoria::select($columnas)->with('usuario')->get();
            default:
                return collect([]);
        }
    }

    private function exportarCSV($modulo, $columnas, $datos)
    {
        $fileName = 'export_' . $modulo . '_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($columnas, $datos, $modulo) {
            $file = fopen('php://output', 'w');
            
            // Escribir cabeceras usando las claves pasadas
            fputcsv($file, array_map('ucfirst', str_replace('_', ' ', $columnas)));

            foreach ($datos as $row) {
                $filaCsv = [];
                foreach ($columnas as $columna) {
                    if ($columna === 'usuario_id' && in_array($modulo, ['suscripciones', 'auditoria'])) {
                        $filaCsv[] = $row->usuario ? $row->usuario->name : 'N/A';
                    } else {
                        $filaCsv[] = $row->$columna;
                    }
                }
                fputcsv($file, $filaCsv);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportarPDF($modulo, $columnas, $datos)
    {
        $pdf = Pdf::loadView('admin.exportar.pdf', compact('modulo', 'columnas', 'datos'));
        // Orientación horizontal para que quepan mejor las columnas
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('export_' . $modulo . '_' . date('Ymd_His') . '.pdf');
    }
}
