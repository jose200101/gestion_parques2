@extends('adminlte::page')

@section('title', 'Cambiar Contraseña')

@section('content_header')
    <h1 class="text-center" style="color: #2d5a27; font-weight: bold;">AJUSTES DE CONTRASEÑA</h1>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm" style="border-top: 4px solid #28a745;">
                <div class="card-header text-white text-center" style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-key mr-2"></i>Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body" style="background-color: #f8fcf8;">
                    @if(session('success'))
                        <div class="alert alert-dismissible fade show" role="alert" style="background-color: #d4edda; border-color: #28a745; color: #155724;">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #155724;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('usuarios.cambiar-password') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="usuario" style="color: #2d5a27; font-weight: 600;">
                                <i class="fas fa-user mr-1" style="color: #28a745;"></i>Usuario:
                            </label>
                            <input type="text" class="form-control" id="usuario" value="{{ $usuario }}" readonly 
                                   style="background-color: #e8f5e8; border-color: #28a745;">
                        </div>

                        <div class="form-group">
                            <label for="password_actual" style="color: #2d5a27; font-weight: 600;">
                                <i class="fas fa-lock mr-1" style="color: #28a745;"></i>Contraseña Actual *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_actual') is-invalid @enderror" 
                                       id="password_actual" 
                                       name="password_actual" 
                                       placeholder="Ingresa tu contraseña actual"
                                       style="border-color: #28a745;"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn toggle-password" type="button" data-target="password_actual" 
                                            style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); color: white; border-color: #28a745;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_nuevo" style="color: #2d5a27; font-weight: 600;">
                                <i class="fas fa-key mr-1" style="color: #28a745;"></i>Nueva Contraseña *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_nuevo') is-invalid @enderror" 
                                       id="password_nuevo" 
                                       name="password_nuevo" 
                                       placeholder="Mínimo 6 caracteres"
                                       style="border-color: #28a745;"
                                       minlength="6"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn toggle-password" type="button" data-target="password_nuevo"
                                            style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); color: white; border-color: #28a745;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text" style="color: #2d5a27;">La contraseña debe tener al menos 6 caracteres.</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmacion" style="color: #2d5a27; font-weight: 600;">
                                <i class="fas fa-check mr-1" style="color: #28a745;"></i>Confirmar Nueva Contraseña *
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_confirmacion') is-invalid @enderror" 
                                       id="password_confirmacion" 
                                       name="password_confirmacion" 
                                       placeholder="Repite la nueva contraseña"
                                       style="border-color: #28a745;"
                                       required>
                                <div class="input-group-append">
                                    <button class="btn toggle-password" type="button" data-target="password_confirmacion"
                                            style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); color: white; border-color: #28a745;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-block text-white" 
                                    style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); border-color: #28a745; box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);">
                                <i class="fas fa-save mr-2"></i>Guardar nueva contraseña
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center" style="background-color: #f1f8f1; border-top-color: #28a745;">
                    <a href="{{ url('/') }}" class="btn" 
                       style="background: linear-gradient(135deg, #28a745 0%, #2d5a27 100%); color: white; border: none;">
                        <i class="fas fa-home mr-1"></i>Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Script para mostrar/ocultar contraseñas
    $(document).ready(function() {
        $('.toggle-password').click(function() {
            const targetInput = $(this).data('target');
            const input = $('#' + targetInput);
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Validación en tiempo real para confirmar contraseña
        $('#password_confirmacion').on('keyup', function() {
            const password = $('#password_nuevo').val();
            const confirmPassword = $(this).val();
            
            if (confirmPassword && password !== confirmPassword) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Las contraseñas no coinciden</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });
    });
</script>
@endsection