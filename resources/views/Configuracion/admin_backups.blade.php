@extends('adminlte::page')

@section('title', 'Administración de Backups')

@section('content_header')
    <h1>Administración de Backups</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Card con la tabla y el botón para abrir el modal --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Listado de Backups <span class="badge badge-info" id="backupCount">0</span>
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#addBackupModal">
                                <i class="fas fa-plus"></i> Agregar Nuevo Backup
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="backupsTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Fecha</th>
                                    <th>Ruta del Archivo</th>
                                    <th>Tipo</th>
                                    <th>Usuario</th>
                                    <th style="width: 120px;">Acciones</th> </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Agregar Nuevo Backup --}}
    <div class="modal fade" id="addBackupModal" tabindex="-1" role="dialog" aria-labelledby="addBackupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header btn-success">
                    <h5 class="modal-title" id="addBackupModalLabel">Agregar Nuevo Backup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addBackupForm">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cod_backup">Código del Backup</label>
                                <input type="number" class="form-control" id="cod_backup" name="cod_backup" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_backup">Fecha del Backup</label>
                                <input type="date" class="form-control" id="fecha_backup" name="fecha_backup" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ruta_archivo">Ruta del Archivo</label>
                                <input type="text" class="form-control" id="ruta_archivo" name="ruta_archivo" placeholder="Ej: backups/backup_01.sql" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipo_backup">Tipo de Backup</label>
                                <select id="tipo_backup" name="tipo_backup" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                    <option value="Completo">Completo</option>
                                    <option value="Incremental">Incremental</option>
                                    <option value="Diferencial">Diferencial</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cod_usuario">Código de Usuario</label>
                            <input type="number" class="form-control" id="cod_usuario" name="cod_usuario" placeholder="Ej: 1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="saveBackupBtn" class="btn btn-success"">
                            <span id="saveBackupText">Guardar Backup</span>
                            <span id="saveBackupSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal para Editar Backup --}}
    <div class="modal fade" id="editBackupModal" tabindex="-1" role="dialog" aria-labelledby="editBackupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="editBackupModalLabel">Editar Backup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editBackupForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_cod_backup_hidden" name="cod_backup">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="edit_cod_backup">Código del Backup</label>
                                <input type="number" class="form-control" id="edit_cod_backup" name="cod_backup" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_fecha_backup">Fecha del Backup</label>
                                <input type="date" class="form-control" id="edit_fecha_backup" name="fecha_backup" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="edit_ruta_archivo">Ruta del Archivo</label>
                                <input type="text" class="form-control" id="edit_ruta_archivo" name="ruta_archivo" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_tipo_backup">Tipo de Backup</label>
                                <select id="edit_tipo_backup" name="tipo_backup" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                    <option value="Completo">Completo</option>
                                    <option value="Incremental">Incremental</option>
                                    <option value="Diferencial">Diferencial</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_cod_usuario">Código de Usuario</label>
                            <input type="number" class="form-control" id="edit_cod_usuario" name="cod_usuario" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // URL de tu API (Asegúrate de que sea la correcta)
            const apiURL = 'http://localhost:3000/backups'; 
            
            const tableBody = document.querySelector('#backupsTable tbody');
            const addBackupForm = document.getElementById('addBackupForm');
            const editBackupForm = document.getElementById('editBackupForm');

            // Variable global para guardar los datos originales del registro que se está editando
            let originalBackupData = {};

            // Función para cargar los datos desde la API
           function loadBackups() {
                fetch(apiURL)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al obtener los datos de la API.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Destruimos la instancia de DataTable si ya existe para evitar errores
                        if ($.fn.DataTable.isDataTable('#backupsTable')) {
                            $('#backupsTable').DataTable().destroy();
                        }

                        document.getElementById('backupCount').innerText = data.length;

                        tableBody.innerHTML = '';
                        
                        data.forEach(backup => {
                            const row = document.createElement('tr');
                            const fecha = new Date(backup.fecha_backup).toISOString().split('T')[0];
                            row.innerHTML = `
                                <td>${backup.cod_backup}</td>
                                <td>${fecha}</td>
                                <td>${backup.ruta_archivo}</td>
                                <td>${backup.tipo_backup}</td>
                                <td>${backup.cod_usuario}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm btn-edit font-weight-bold" data-cod-backup="${backup.cod_backup}">
                                        Modificar
                                    </button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });

                        // Inicializamos DataTables en la tabla
                        $('#backupsTable').DataTable({
                            "dom": 'Bfrtip',
                            "responsive": true,
                            "lengthChange": true,
                            "autoWidth": false,
                            "buttons": ["copy", "csv", "excel", "pdf", "print"],
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la petición fetch:', error);
                        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error al cargar los datos. Por favor, revisa la consola para más detalles.</td></tr>`;
                    });

            }

            // Escuchar el evento de envío del formulario de AGREGAR
            addBackupForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const saveButton = document.getElementById('saveBackupBtn');
                const saveSpinner = document.getElementById('saveBackupSpinner');
                const saveText = document.getElementById('saveBackupText');

                // **Mostrar el spinner y deshabilitar el botón**
                saveSpinner.style.display = 'inline-block';
                saveText.style.display = 'none';
                saveButton.disabled = true;

                const formData = new FormData(addBackupForm);
                const data = {
                    cod_backup: parseInt(formData.get('cod_backup')),
                    fecha_backup: formData.get('fecha_backup'),
                    ruta_archivo: formData.get('ruta_archivo'),
                    tipo_backup: formData.get('tipo_backup'),
                    cod_usuario: parseInt(formData.get('cod_usuario'))
                };

                fetch(apiURL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.respuesta || 'Error desconocido'); });
                    }
                    return response.json();
                })
                .then(result => {
                    toastr.success(result.respuesta, 'Éxito');
                    loadBackups();
                    addBackupForm.reset();
                    $('#addBackupModal').modal('hide'); 
                })
                .catch(error => {
                    console.error('Error al guardar el backup:', error);
                    toastr.error('Error al guardar el backup: ' + error.message, 'Error');
                })
                .finally(() => {
                    // **Ocultar el spinner y habilitar el botón, sin importar si fue éxito o error**
                    saveSpinner.style.display = 'none';
                    saveText.style.display = 'inline';
                    saveButton.disabled = false;
                });
            });

            // Código para abrir el modal de edición y cargar los datos
            tableBody.addEventListener('click', function (event) {
                if (event.target.closest('.btn-edit')) {
                    const button = event.target.closest('.btn-edit');
                    const codBackup = button.getAttribute('data-cod-backup');
                    
                    fetch(`${apiURL}/${codBackup}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al obtener los datos para editar.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Guardamos los datos originales en un objeto para comparación posterior
                            originalBackupData = data;
                            
                            // Formateamos la fecha para que el input type="date" funcione correctamente
                            const fecha = new Date(data.fecha_backup).toISOString().split('T')[0];

                            // Rellenar el formulario del modal de edición
                            document.getElementById('edit_cod_backup_hidden').value = data.cod_backup;
                            document.getElementById('edit_cod_backup').value = data.cod_backup;
                            document.getElementById('edit_fecha_backup').value = fecha;
                            document.getElementById('edit_ruta_archivo').value = data.ruta_archivo;
                            document.getElementById('edit_tipo_backup').value = data.tipo_backup;
                            document.getElementById('edit_cod_usuario').value = data.cod_usuario;

                            // Abrir el modal de edición
                            $('#editBackupModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error al obtener datos para editar:', error);
                            toastr.error('Error al cargar los datos del backup.', 'Error');
                        });
                }
            });

            // Código para enviar los datos de edición con múltiples peticiones PUT
            editBackupForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const codBackup = document.getElementById('edit_cod_backup_hidden').value;
                const formData = new FormData(editBackupForm);
                
                // Obtenemos los nuevos datos del formulario
                const newData = {
                    fecha_backup: formData.get('fecha_backup'),
                    ruta_archivo: formData.get('ruta_archivo'),
                    tipo_backup: formData.get('tipo_backup'),
                    cod_usuario: parseInt(formData.get('cod_usuario'))
                };

                let updates = [];

                // Comparamos los datos nuevos con los originales y preparamos las peticiones
                if (newData.fecha_backup !== originalBackupData.fecha_backup) {
                    updates.push({ dato_actualizar: 'fecha_backup', nuevo_dato: newData.fecha_backup, condicion: 'cod_backup', v_condicion: codBackup });
                }
                if (newData.ruta_archivo !== originalBackupData.ruta_archivo) {
                    updates.push({ dato_actualizar: 'ruta_archivo', nuevo_dato: newData.ruta_archivo, condicion: 'cod_backup', v_condicion: codBackup });
                }
                if (newData.tipo_backup !== originalBackupData.tipo_backup) {
                    updates.push({ dato_actualizar: 'tipo_backup', nuevo_dato: newData.tipo_backup, condicion: 'cod_backup', v_condicion: codBackup });
                }
                if (newData.cod_usuario !== originalBackupData.cod_usuario) {
                    updates.push({ dato_actualizar: 'cod_usuario', nuevo_dato: newData.cod_usuario, condicion: 'cod_backup', v_condicion: codBackup });
                }

                // Si no hay cambios, no hacemos nada
                if (updates.length === 0) {
                    toastr.info('No se detectaron cambios para guardar.', 'Info');
                    $('#editBackupModal').modal('hide');
                    return;
                }

                // Hacemos todas las peticiones PUT en paralelo
                Promise.all(updates.map(update =>
                    fetch(apiURL, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(update)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.respuesta || 'Error al actualizar.'); });
                        }
                        return response.json();
                    })
                ))
                .then(() => {
                    toastr.success('Registro actualizado correctamente.', 'Éxito');
                    loadBackups();
                    $('#editBackupModal').modal('hide');
                })
                .catch(error => {
                    console.error('Error al actualizar un campo del backup:', error);
                    toastr.error('Error al actualizar el backup: ' + error.message, 'Error');
                });
            });

            // Llamamos a la función para cargar los datos al iniciar la página
            loadBackups();
        });
    </script>
@endpush