
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-primary-blue thead {
            background-color: #007bff;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #e0f2f7;
        }
    </style>
</head>
<body>
<div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center fw-bold" style="color: #28a745;">
                Listado de Usuarios
            </h1>

            <!-- Botón Salir -->
            <a href="{{ url('/') }}" class="btn btn-success d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.146 12.354a.5.5 0 0 0 .708-.708L8.707 10H14a.5.5 0 0 0 0-1H8.707l2.147-1.646a.5.5 0 1 0-.708-.708l-3 2.5a.5.5 0 0 0 0 .708l3 2.5z"/>
                    <path fill-rule="evenodd" d="M4.5 14A1.5 1.5 0 0 1 3 12.5v-9A1.5 1.5 0 0 1 4.5 2h7A1.5 1.5 0 0 1 13 3.5V6a.5.5 0 0 1-1 0V3.5a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V10a.5.5 0 0 1 1 0v2.5a1.5 1.5 0 0 1-1.5 1.5h-7z"/>
                </svg>
                Volver al inicio
            </a>
        </div>
    <!-- MENSAJES -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

        <!-- BUSCADOR DE USUARIOS -->
            <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex gap-2" method="GET" action="{{ route('usuarios.index') }}">
            <input type="text"
                name="buscar"
                value="{{ $buscar ?? request('buscar') }}"
                class="form-control"
                placeholder="Buscar usuario...">
            <button class="btn btn-success" type="submit">Buscar</button>
            @if(!empty($buscar))
                <a class="btn btn-outline-secondary" href="{{ route('usuarios.index') }}">Limpiar</a>
            @endif
        </form>

        {{-- (Opcional) Botón crear que ya tenías --}}
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrear">+ Nuevo usuario</button>
    </div>

            @if(isset($buscar) && $buscar !== '' && count($ResulUsuarios) === 0)
        <div class="alert alert-warning">No se encontraron usuarios con “{{ $buscar }}”.</div>
    @endif

        <!-- TABLA -->
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
                        <th>Acciones</th>
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
                        <td>
                            <!-- Botón Editar -->
                            <button class="btn btn-info  btn-sm"
                                data-bs-toggle="modal" data-bs-target="#modalEditar"
                                onclick="cargarDatosEditar({{ json_encode($usuario) }})">
                                Editar
                            </button>

                            <!-- Botón Inactivar -->
                            <form action="{{ route('usuarios.destroy', $usuario['cod_usuario']) }}"
                                method="POST"
                                style="display:inline-block;"
                                onsubmit="return confirm('¿Seguro que deseas inactivar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-secondary btn-sm">Inactivar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<!-- MODAL CREAR USUARIO Y PERSONA -->
<div class="modal fade" id="modalCrear" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Registrar Nuevo Usuario</h5>
            </div>
            <div class="modal-body">
                <!-- DATOS DE LA PERSONA -->
                <h6 class="text-success fw-bold">Datos Personales</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Apellido</label>
                        <input name="apellido" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Nacionalidad</label>
                        <input name="nacionalidad" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">DNI</label>
                        <input name="dni" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label">Genero</label>
                        <select name="genero" class="form-control" required>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Teléfono</label>
                        <input name="telefono" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Dirección</label>
                        <input name="direccion" class="form-control" required>
                    </div>
                </div>

                <hr>

                <!-- DATOS DE USUARIO -->
                <h6 class="text-success fw-bold">Datos de Usuario</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Nombre de Usuario</label>
                        <input name="nombre_usuario" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Estado</label>
                        <select name="estado_usuario" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Código de Permiso</label>
                        <select name="cod_permiso" class="form-control" required>
                            <option value="1">Administrador</option>
                            <option value="2">Empleado</option>
                            <option value="3">Visitante</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
  </div>
</div>


<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('usuarios.update') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Editar Usuario</h5></div>
            <div class="modal-body">
                <input type="hidden" name="cod_usuario" id="edit_cod_usuario">

                <div class="mb-2">
                    <label class="form-label">Código de Permiso</label>
                    <input name="cod_permiso" id="edit_cod_permiso" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Nombre de Usuario</label>
                    <input name="nombre_usuario" id="edit_nombre_usuario" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Contraseña</label>
                    <input name="contrasena" id="edit_contrasena" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Estado</label>
                    <select name="estado_usuario" id="edit_estado_usuario" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Primer Acceso</label>
                    <select name="primer_acceso" id="edit_primer_acceso" class="form-control">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Código de Bitácora</label>
                    <input name="cod_bitacora" id="edit_cod_bitacora" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-warning" type="submit">Actualizar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

function cargarDatosEditar(usuario) {
    document.getElementById('edit_cod_usuario').value = usuario.cod_usuario;
    document.getElementById('edit_cod_permiso').value = usuario.cod_permiso;
    document.getElementById('edit_nombre_usuario').value = usuario.nombre_usuario;
    document.getElementById('edit_contrasena').value = usuario.contrasena;
    document.getElementById('edit_estado_usuario').value = usuario.estado_usuario;
    document.getElementById('edit_primer_acceso').value = usuario.primer_acceso;
    document.getElementById('edit_cod_bitacora').value = usuario.cod_bitacora;
}

</script>
</body>
</html>