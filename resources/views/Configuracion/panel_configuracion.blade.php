@extends('adminlte::page')

@section('title', 'Panel de Configuración')

@section('content_header')
    <h1 class="m-0 text-dark">Panel de Configuración</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configuración de Backups</h3>
                    <div class="card-tools">
                        {{-- Aquí puedes agregar botones para acciones globales --}}
                    </div>
                </div>
                <div class="card-body">
                    <p>
                        Gestiona y visualiza la información de los backups de tu sistema.
                        Mantén un registro de las copias de seguridad para garantizar la integridad de tus datos.
                    </p>
                    <div class="mt-4">
                        <h5>Estado Actual de Backups</h5>
                        <ul>
                            <li><strong>Último Backup Realizado:</strong> 3 de Agosto de 2025, 10:30 AM</li>
                            <li><strong>Próxima Ejecución Programada:</strong> 10 de Agosto de 2025</li>
                            <li><strong>Tipo de Backup Programado:</strong> Completo Semanal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Opciones del Sistema</h3>
                    <div class="card-tools">
                        {{-- Puedes agregar un botón para forzar un backup manual --}}
                        <button class="btn btn-primary btn-sm" id="btnCrearBackup">
                            <i class="fas fa-database"></i> Crear Backup Automatico
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p>
                        Accion clave de administración para el sistema. Puedes realizar
                        la creación automatica de backups en cualquier momento.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configuración de la Base de Datos</h3>
                    <div class="card-tools">
                        {{-- Botón para verificar el estado de la conexión --}}
                        <button class="btn btn-info btn-sm" id="btnCheckDB">
                            <i class="fas fa-database"></i> Verificar Conexión
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p>
                        Verifica el estado de la conexión a la base de datos para diagnosticar posibles problemas.
                    </p>
                    <div class="mt-4">
                        <strong>Estado de Conexión:</strong>
                        <span id="dbStatus" class="badge badge-warning">Verificando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @section('js')
    <script>
        // Lógica para el botón de crear backup manual
        document.addEventListener('DOMContentLoaded', function () {
            const btnCrearBackup = document.getElementById('btnCrearBackup');
            const apiURL = 'http://localhost:3000/backups'; // Asegúrate de que esta URL sea la correcta

            btnCrearBackup.addEventListener('click', function() {
                if (confirm('¿Estás seguro de que quieres crear un backup automatico?')) {

                    // Obtenemos los datos necesarios para el backup
                    // En un escenario real, estos datos podrían venir de inputs o del usuario autenticado
                    const backupData = {
                        cod_backup: Math.floor(Math.random() * 1000) + 1, // Ejemplo de ID, idealmente lo maneja la DB
                        fecha_backup: new Date().toISOString().slice(0, 10), // Fecha actual
                        ruta_archivo: '/backups/manual_backup_' + new Date().getTime() + '.sql', // Ruta de archivo dinámica
                        tipo_backup: 'Automatico', // Tipo de backup
                        cod_usuario: 1 // Asumimos que el usuario 1 es el que lo crea, esto debería ser dinámico
                    };

                    // Hacemos la llamada fetch a la API
                    fetch(apiURL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(backupData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al crear el backup. Revisa la consola.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert('¡Backup creado con éxito!');
                        console.log('Respuesta de la API:', data);
                        // Puedes redirigir a la vista de logs después de crear el backup
                        // window.location.href = '/configuracion/logs_sistema';
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la petición fetch:', error);
                        alert('Ocurrió un error al crear el backup. Revisa la consola para más detalles.');
                    });
                }
            });


            const btnCheckDB = document.getElementById('btnCheckDB');
            const dbStatus = document.getElementById('dbStatus');
            const dbApiURL = 'http://localhost:3000/status'; // Este endpoint lo crearemos

            btnCheckDB.addEventListener('click', function() {
                dbStatus.innerHTML = '<span class="badge badge-warning">Estado: Verificando...</span>';

                fetch(dbApiURL)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al verificar la conexión.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'ok') {
                            dbStatus.innerHTML = '<span class="badge badge-success">Estado: Conectado</span>';
                        } else {
                            dbStatus.innerHTML = '<span class="badge badge-danger">Estado: Desconectado</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error al verificar la conexión:', error);
                        dbStatus.innerHTML = '<span class="badge badge-danger">Estado: Error</span>';
                    });
            });
       });
    </script>
@stop
@stop