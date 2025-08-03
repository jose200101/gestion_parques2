<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Estilo personalizado para el encabezado de la tabla */
        .table-primary-blue thead {
            background-color: #007bff; /* Un azul primario de Bootstrap */
            color: white; /* Texto blanco para contraste */
        }
        /* Efecto hover para las filas de la tabla */
        .table-hover tbody tr:hover {
            background-color: #e0f2f7; /* Un azul muy claro al pasar el ratón */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4 text-primary">Listado de Usuarios</h1>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-primary-blue">
                <thead>
                    <tr>
                        <th>Código de Usuario</th>
                        <th>Código de Permiso</th>
                        <th>Nombre de Usuario</th>
                        <th>Contraseña</th>
                        <th>Estado</th>
                        <th>Primer Acceso</th>
                        <th>Código de Bitácora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ResulUsuarios as $usuario)
                    <tr>
                        <td>{{ $usuario['cod_usuario'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['cod_permiso'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['nombre_usuario'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['contrasena'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['estado_usuario'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['primer_acceso'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['cod_bitacora'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>