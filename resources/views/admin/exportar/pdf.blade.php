<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - {{ ucfirst($modulo) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #111;
            margin-bottom: 5px;
        }
        p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #444;
            text-transform: uppercase;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Reporte de {{ ucfirst($modulo) }}</h1>
    <p>Generado el: {{ date('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                @foreach($columnas as $columna)
                    <th>{{ ucfirst(str_replace('_', ' ', $columna)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $row)
                <tr>
                    @foreach($columnas as $columna)
                        <td>
                            @if($columna === 'usuario_id' && in_array($modulo, ['suscripciones', 'auditoria']))
                                {{ $row->usuario ? $row->usuario->name : 'N/A' }}
                            @else
                                {{ $row->$columna }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
