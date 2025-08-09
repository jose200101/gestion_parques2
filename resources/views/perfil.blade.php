@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
    <h1 class="text-center" style="color: #2d5a27; font-weight: bold;">MI PERFIL</h1>
@endsection

@section('content')
<div class="container">
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(!$user)
        <div class="alert alert-warning">
            <i class="fas fa-user-times mr-2"></i>No se pudo identificar al usuario actual. Inicia sesión o configura el nombre en sesión.
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm" style="border-top: 4px solid #28a745;">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle mr-2"></i>Información del Perfil
                        </h5>
                    </div>
                    <div class="card-body" style="background-color: #f8fcf8;">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div style="font-size:72px; color:#28a745; text-shadow: 2px 2px 4px rgba(45, 90, 39, 0.3);">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <strong style="color: #2d5a27;"><i class="fas fa-user mr-1" style="color: #28a745;"></i>Usuario:</strong>
                                        <p class="text-muted mb-0" style="font-weight: 600;">{{ $user['nombre_usuario'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <strong style="color: #2d5a27;"><i class="fas fa-id-badge mr-1" style="color: #28a745;"></i>Código:</strong>
                                        <p class="text-muted mb-0" style="font-weight: 600;">{{ $user['cod_usuario'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <strong style="color: #2d5a27;"><i class="fas fa-shield-alt mr-1" style="color: #28a745;"></i>Rol:</strong>
                                        <p class="mb-0">
                                            <span class="badge text-white" style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); font-size:0.9rem; padding: 5px 10px;">
                                                {{ $permisoNombre ?? 'Permiso N/D' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <strong style="color: #2d5a27;"><i class="fas fa-toggle-on mr-1" style="color: #28a745;"></i>Estado:</strong>
                                        <p class="mb-0">
                                            @if($user['estado_usuario'] === 'A')
                                                <span class="badge text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">Activo</span>
                                            @elseif($user['estado_usuario'] === 'I')
                                                <span class="badge badge-danger">Inactivo</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $user['estado_usuario'] ?? 'N/A' }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <strong style="color: #2d5a27;"><i class="fas fa-key mr-1" style="color: #28a745;"></i>Primer Acceso:</strong>
                                        <p class="mb-0">
                                            @if($user['primer_acceso'] == 1)
                                                <span class="badge badge-warning">Sí</span>
                                            @else
                                                <span class="badge" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">No</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <strong style="color: #2d5a27;"><i class="fas fa-code mr-1" style="color: #28a745;"></i>Código Bitácora:</strong>
                                        <p class="text-muted mb-0" style="font-weight: 600;">{{ $user['cod_bitacora'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" style="background-color: #f1f8f1; border-top-color: #28a745;">
                        <div class="text-center">
                            <a href="{{ url('/') }}" class="btn text-white" style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); border: none; box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3); padding: 10px 30px;">
                                <i class="fas fa-arrow-left mr-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
