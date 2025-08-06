{{-- Ana R. Cabrera - Vista para el listado de Parques --}}
@extends('adminlte::page')

@section('title', 'Gestión de Parques')

@section('content_header')
    <h1>Listado de Parques</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('parques.create') }}" class="btn btn-primary">Registrar Nuevo Parque</a>
        </div>
        <div class="card-body">
            @if(isset($ResulParques) && !empty($ResulParques))
                <table class="table table-bordered">
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
                        @foreach ($ResulParques as $parque)
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
                                    {{-- ANA R. CABRERA - Se ha eliminado el botón "Ver" ya que no es necesario. --}}
                                    {{-- ANA R. CABRERA - Se ha corregido la ruta del botón "Editar" para que apunte a la vista correcta. --}}
                                    <a href="{{ route('parques.edit', $parque['cod_parque']) }}" class="btn btn-sm btn-warning">Editar</a>
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
@stop
