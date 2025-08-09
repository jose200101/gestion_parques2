@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
    <h1 class="fw-bold text-success text-center">MI PERFIL</h1>
@endsection

@section('content')
<div class="container">
    @if(!$user)
        <div class="alert alert-warning">
            No se pudo identificar al usuario actual. Inicia sesión o configura el nombre en sesión.
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3" style="font-size:72px; color:#28a745;">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $user['nombre_usuario'] ?? 'N/A' }}</h4>
                            <span class="badge badge-success" style="font-size:0.95rem;">
                                {{ $permisoNombre ?? 'Permiso N/D' }}
                            </span>
                            <div class="mt-2 text-muted">
                                <small>Código: {{ $user['cod_usuario'] ?? 'N/A' }}</small><br>
                                <small>Estado: {{ $user['estado_usuario'] ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ url('/') }}" class="btn btn-success">
                            <i class="fas fa-arrow-left mr-1"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
