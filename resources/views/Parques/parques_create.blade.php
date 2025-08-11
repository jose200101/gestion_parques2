{{-- Ana R. Cabrera - Vista para el formulario de creación de un nuevo parque --}}
@extends('adminlte::page')

@section('title', 'Registrar Nuevo Parque')

@section('content_header')
    <h1>Registrar Nuevo Parque</h1>
@stop

@section('content')
    {{-- Aquí se mostrará el mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Éxito!</h5>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formulario de Creación</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('parques.store') }}" method="POST">
                @csrf {{-- Campo de seguridad obligatorio en Laravel --}}

                <div class="form-group">
                    <label for="nombre_parque">Nombre del Parque</label>
                    <input type="text" name="nombre_parque" id="nombre_parque" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="ubicacion_parque">Ubicación del Parque</label>
                    <input type="text" name="ubicacion_parque" id="ubicacion_parque" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="fecha_inauguracion">Fecha de Inauguración</label>
                    <input type="date" name="fecha_inauguracion" id="fecha_inauguracion" class="form-control">
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Parque</button>
                <a href="{{ route('parques.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
