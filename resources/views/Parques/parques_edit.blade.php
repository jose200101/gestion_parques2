{{-- Ana R. Cabrera - Vista para el formulario de edición de un parque existente --}}
@extends('adminlte::page')

@section('title', 'Editar Parque')

@section('content_header')
    <h1>Editar Parque</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formulario de Edición</h3>
        </div>
        <div class="card-body">
            {{-- El formulario usa el método PUT para actualizar los datos --}}
            <form action="{{ route('parques.update', $parque['cod_parque']) }}" method="POST">
                @csrf
                @method('PUT') {{-- Directiva de Laravel para simular una petición PUT --}}

                <div class="form-group">
                    <label for="nombre_parque">Nombre del Parque</label>
                    <input type="text" name="nombre_parque" id="nombre_parque" class="form-control" value="{{ $parque['nombre_parque'] }}" required>
                </div>
                
                <div class="form-group">
                    <label for="ubicacion_parque">Ubicación del Parque</label>
                    <input type="text" name="ubicacion_parque" id="ubicacion_parque" class="form-control" value="{{ $parque['ubicacion_parque'] }}" required>
                </div>

                <div class="form-group">
                    <label for="fecha_inauguracion">Fecha de Inauguración</label>
                    <input type="date" name="fecha_inauguracion" id="fecha_inauguracion" class="form-control" value="{{ $parque['fecha_inauguracion'] }}">
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="1" {{ $parque['estado'] == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ $parque['estado'] == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Actualizar Parque</button>
                <a href="{{ route('parques.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop