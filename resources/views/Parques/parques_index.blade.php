{{-- Ana R. Cabrera - Esta vista muestra el listado de todos los parques --}}
@extends('adminlte::page') {{-- Extiende la plantilla de AdminLTE --}}

@section('title', 'Gestión de Parques') {{-- Título de la página --}}

@section('content_header')
    <h1>Listado de Parques</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('parques.create') }}" class="btn btn-primary">Registrar Nuevo Parque</a>
        </div>
        <div class="card-body">
            {{-- Mensajes de éxito o error --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(isset($ResalParques) && !empty($ResalParques))
                {{-- Ana R. Cabrera - El ID 'parquesTable' es importante para DataTables --}}
                <table id="parquesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Fecha de Inauguración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ResalParques as $parque)
                            <tr>
                                <td>{{ $parque['cod_parque'] }}</td>
                                <td>{{ $parque['nombre_parque'] }}</td>
                                <td>{{ $parque['ubicacion_parque'] }}</td>
                                <td>{{ $parque['fecha_inauguracion'] }}</td>
                                <td>
                                    @if ($parque['estado'] == 1)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Botón para editar --}}
                                    <a href="{{ route('parques.edit', $parque['cod_parque']) }}" class="btn btn-sm btn-warning">Editar</a>

                                    {{-- Ana R. Cabrera - Botón para abrir el modal de confirmación de eliminación --}}
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-parque-id="{{ $parque['cod_parque'] }}">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No se encontraron parques.</p>
            @endif
        </div>
    </div>

    {{-- Ana R. Cabrera - Modal de Confirmación para Eliminar --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar este parque? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" action="" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- Ana R. Cabrera - Sección de scripts para DataTables y el modal --}}
@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicialización de DataTables
            $('#parquesTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json" // Traduce DataTables a español
                }
            });

            // Lógica del modal de confirmación
            $('#confirmDeleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var parqueId = button.data('parque-id');
                var form = $('#deleteForm');
                var actionUrl = "{{ url('parques') }}/" + parqueId;
                form.attr('action', actionUrl);
            });
        });
    </script>
@stop

{{-- Ana R. Cabrera - Sección de CSS para DataTables --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <style>
        /* Ana R. Cabrera - Estilos personalizados para el encabezado de la tabla */
        #parquesTable thead {
            background-color: #28a745; /* Verde de Bootstrap */
            color: white;
        }
    </style>
@stop


