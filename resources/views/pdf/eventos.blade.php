<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Eventos Ambientales</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 20px; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Reporte de Eventos Ambientales</h1>
    <table>
        <thead>
            <tr>
                <th>Código de reporte</th>
                <th>Fecha y hora</th>
                <th>Descripción</th>
                <th>Parque</th>
                <th>Evento</th>
                <th>Especie</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row[0] }}</td>
                    <td>{{ $row[1] }}</td>
                    <td>{{ $row[2] }}</td>
                    <td>{{ $row[3] }}</td>
                    <td>{{ $row[4] }}</td>
                    <td>{{ $row[5] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>