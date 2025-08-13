@extends('adminlte::page')

@section('title', 'Reporte de Eventos Ambientales')

@section('content_header')
    <h1>Reporte de Eventos Ambientales</h1>
@stop

@section('css')
    {{-- Estilos de DataTables para Bootstrap 4 (compatible con AdminLTE) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
    {{-- Estilos para toastr.js (mensajes de notificación) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Eventos Ambientales <span class="badge badge-info" id="logsCount">0</span></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addEventoModal">
                    <i class="fas fa-plus"></i> Agregar Evento
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="tabla-eventos" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Código reporte</th>
                        <th>Fecha y hora</th>
                        <th>Descripción</th>
                        <th>Parque</th>
                        <th>Evento</th>
                        <th>Especie</th>
                        <th style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Código reporte</th>
                        <th>Fecha y hora</th>
                        <th>Descripción</th>
                        <th>Parque</th>
                        <th>Evento</th>
                        <th>Especie</th>
                        <th>Acciones</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Nuevo contenedor para el gráfico --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Eventos por Parque y Tipo de Evento</h3>
        </div>
        <div class="card-body">
            <canvas id="eventosChart" style="height:300px;"></canvas>
        </div>
    </div>

    {{-- Modal para Agregar Nuevo Evento --}}
<div class="modal fade" id="addEventoModal" tabindex="-1" role="dialog" aria-labelledby="addEventoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addEventoModalLabel">Agregar Nuevo Evento Ambiental</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addEventoForm">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cod_evento">Código del Evento</label>
                            <input type="number" class="form-control" id="cod_evento" name="cod_evento" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_hora_evento">Fecha y Hora</label>
                            <input type="datetime-local" class="form-control" id="fecha_hora_evento" name="fecha_hora_evento" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Descripcion">Descripción</label>
                        <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cod_tipo_evento">Tipo de Evento</label>
                            <select id="cod_tipo_evento" name="cod_tipo_evento" class="form-control" required>
                                <option value="">Selecciona...</option>
                                {{-- Las opciones se llenarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cod_parque">Parque</label>
                            <select id="cod_parque" name="cod_parque" class="form-control" required>
                                <option value="">Selecciona...</option>
                                {{-- Las opciones se llenarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cod_especie">Especie</label>
                            <select id="cod_especie" name="cod_especie" class="form-control" >
                                <option value="">Ninguna</option>
                                {{-- Las opciones se llenarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="saveEventoBtn" class="btn btn-success">
                        <span id="saveEventoText">Guardar Evento</span>
                        <span id="saveEventoSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="emailModalLabel">Enviar reporte por correo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="emailForm">
            @csrf
            <div class="form-group">
                <label for="recipient-email" class="col-form-label">Correo electrónico del destinatario:</label>
                <input type="email" class="form-control" id="recipient-email" required>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="sendEmailBtn">Enviar</button>
        </div>
        </div>
    </div>
</div>

{{-- Modal para Actualizar Evento --}}
<div class="modal fade" id="editEventoModal" tabindex="-1" role="dialog" aria-labelledby="editEventoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editEventoModalLabel">Actualizar Evento Ambiental</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editEventoForm">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_cod_evento">Código del Evento</label>
                            <input type="number" class="form-control" id="edit_cod_evento" name="cod_evento" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_fecha_hora_evento">Fecha y Hora</label>
                            <input type="datetime-local" class="form-control" id="edit_fecha_hora_evento" name="fecha_hora_evento" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_Descripcion">Descripción</label>
                        <textarea class="form-control" id="edit_Descripcion" name="Descripcion" rows="3" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="edit_cod_tipo_evento">Tipo de Evento</label>
                            <select id="edit_cod_tipo_evento" name="cod_tipo_evento" class="form-control" required>
                                <option value="">Selecciona...</option>
                                {{-- Las opciones se llenarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="edit_cod_parque">Parque</label>
                            <select id="edit_cod_parque" name="cod_parque" class="form-control" required>
                                <option value="">Selecciona...</option>
                                {{-- Las opciones se llenarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="edit_cod_especie">Especie</label>
                            <select id="edit_cod_especie" name="cod_especie" class="form-control" >
                                <option value="">Ninguna</option>
                                {{-- Las opciones se llenarán dinámicamente con JavaScript --}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="updateEventoBtn" class="btn btn-warning">
                        <span id="updateEventoText">Actualizar Evento</span>
                        <span id="updateEventoSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Confirmación de Eliminación --}}
<div class="modal fade" id="deleteEventoModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="deleteEventoModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.
                <input type="hidden" id="delete_cod_evento">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')

{{-- Scripts de DataTables y sus extensiones para Bootstrap 4 --}}
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

{{-- Scripts de los botones de DataTables para Bootstrap 4 --}}
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>

{{-- Necesitas moment.js para el formato de fecha y hora en el modal de edición --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
{{-- Script para toastr.js (mensajes de notificación) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
{{-- Script para Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiURL = 'http://localhost:3000/eventos_ambientales';
        const tableBody = document.querySelector('#tabla-eventos tbody');

        // Variable para almacenar la instancia de DataTable
        let tablaEventos; 
        // Variable para almacenar la instancia del gráfico
        let eventosChartInstance;

        function loadEventos() {
            fetch(apiURL)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener los datos de la API.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Actualiza el conteo de logs
                    document.getElementById('logsCount').innerText = data.length;

                    // Si la instancia de DataTable ya existe, la actualizamos; de lo contrario, la inicializamos.
                    if ($.fn.DataTable.isDataTable('#tabla-eventos')) {
                        // Limpia los datos existentes
                        tablaEventos.clear(); 
                        // Añade las nuevas filas
                        // Asegúrate de que 'data' sea un array de arrays o un array de objetos
                        // donde cada objeto tiene las propiedades en el orden de las columnas de la tabla.
                        // Si 'data' es un array de objetos, DataTables puede mapearlos directamente si las columnas se definen por 'data' propiedad
                        // En tu caso, estás construyendo el HTML manualmente, así que necesitarás mapear `data`
                        // a un formato que DataTables pueda añadir directamente si no estás re-inicializando.

                        // Ya que el HTML se construye manualmente por `data.forEach`, 
                        // volvamos a la lógica de destruir y recrear si el problema persiste,
                        // o lo mejor es que DataTables reciba los datos como objetos y maneje la renderización
                        // por sí mismo a través de la opción `columns`.

                        // Por ahora, para una solución más rápida manteniendo tu estructura:
                        // Destruimos y recreamos la tabla si ya existe, ya que estás generando el tbody manualmente.
                        // Si en el futuro quieres optimizar, pasarías 'data' directamente a DataTables.

                        $('#tabla-eventos').DataTable().destroy();
                        tableBody.innerHTML = ''; // Limpia el HTML del cuerpo de la tabla
                    } else {
                        tableBody.innerHTML = ''; // Limpia el HTML del cuerpo de la tabla por primera vez
                    }

                    // Llena la tabla con los nuevos datos (si la tabla ya se destruyó o es la primera carga)
                    data.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${log.cod_evento}</td>
                            <td>${new Date(log.fecha_hora_evento).toLocaleString()}</td>
                            <td>${log.Descripcion}</td>
                            <td>${log.nombre_parque}</td>
                            <td>${log.nombre_evento}</td>
                            <td>${log.nombre_especie}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm btn-edit" data-cod-evento="${log.cod_evento}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-delete" data-cod-evento="${log.cod_evento}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Inicializa DataTables con los botones y asigna a la variable global.
                    tablaEventos = $('#tabla-eventos').DataTable({
                        "responsive": true,
                        "lengthChange": true,
                        "autoWidth": false,
                        "buttons": [
                            {
                                extend: 'copy',
                                className: 'btn btn-warning'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-warning'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-warning'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-warning'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-warning'
                            },
                            {
                                extend: 'colvis',
                                className: 'btn btn-warning'
                            },
                            {
                                text: 'Enviar por Correo',
                                className: 'btn btn-success',
                                action: function ( e, dt, node, config ) {
                                    $('#emailModal').modal('show');
                                }
                            }
                        ],
                        "language": {
                            "url": "{{ asset('js/i18n/Spanish.json') }}"
                        },
                        "dom": 'Bfrtip'
                    });

                    $('#sendEmailBtn').on('click', function() {
                        const recipientEmail = $('#recipient-email').val();
                        
                        if (!recipientEmail) {
                            alert('Por favor, ingresa una dirección de correo electrónico.');
                            return;
                        }

                        // Obtener los datos visibles de la tabla, incluyendo encabezados.
                        // Usamos la API de DataTables para exportar los datos.
                        const tableData = tablaEventos.buttons.exportData();

                        // Petición AJAX al backend
                        $.ajax({
                            url: '{{ route('send.report.email') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                data: JSON.stringify(tableData.body), 
                                headers: JSON.stringify(tableData.header),
                                recipient: recipientEmail 
                            },
                            success: function(response) {
                                alert(response.message);
                                $('#emailModal').modal('hide'); 
                            },
                            error: function(xhr, status, error) { 
                                console.error('Error AJAX:', status, error, xhr.responseText);
                                toastr.error('Hubo un error al enviar el correo. Revisa la consola para más detalles.', 'Error de Envío');
                            }
                        });
                    });

                    // Después de cargar y dibujar la tabla, actualiza el gráfico
                    updateChart(data);

                })
                .catch(error => {
                    console.error('Hubo un problema con la petición fetch en loadEventos:', error);
                    tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error al cargar los datos. Por favor, revisa la consola para más detalles.</td></tr>`;
                    toastr.error('Error al cargar los datos de la tabla.', 'Error de Carga');
                });
        }
        
        loadEventos(); // Carga la tabla al inicio
        
        const apiURLParques = 'http://localhost:3000/parques';
        const apiURLEventos = 'http://localhost:3000/tipo_evento';
        const apiURLEspecies = 'http://localhost:3000/catalogo_especies';
        const addEventoForm = document.getElementById('addEventoForm');
        const codEventoInput = document.getElementById('cod_evento');

        function generarCodigoEvento() {
             fetch(apiURL)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const maxCod = Math.max(...data.map(evento => evento.cod_evento));
                        codEventoInput.value = maxCod + 1;
                    } else {
                        codEventoInput.value = 1;
                    }
                })
                .catch(error => {
                    console.error('Error al generar el código de evento:', error);
                    codEventoInput.value = '';
                    toastr.error('Error al generar código de evento.', 'Error');
                });
        }
        function cargarSelectores() {
            $.ajax({
                url: apiURLEventos,
                method: 'GET',
                success: function(data) {
                    const select = $('#cod_tipo_evento');
                    select.find('option:not(:first)').remove(); // Mantener la opción "Selecciona..."
                    data.forEach(item => {
                        select.append(`<option value="${item.cod_tipo_evento}">${item.nombre_evento}</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando tipos de evento:', error);
                    toastr.error('Error al cargar tipos de evento.', 'Error');
                }
            });
            $.ajax({
                url: apiURLParques,
                method: 'GET',
                success: function(data) {
                    const select = $('#cod_parque');
                    select.find('option:not(:first)').remove(); // Mantener la opción "Selecciona..."
                    data.forEach(item => {
                        select.append(`<option value="${item.cod_parque}">${item.nombre_parque}</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando parques:', error);
                    toastr.error('Error al cargar parques.', 'Error');
                }
            });
            $.ajax({
                url: apiURLEspecies,
                method: 'GET',
                success: function(data) {
                    const select = $('#cod_especie');
                    select.find('option:not(:first)').remove(); // Mantener la opción "Ninguna"
                    data.forEach(item => {
                        select.append(`<option value="${item.cod_especie}">${item.nombre_especie}</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando especies:', error);
                    toastr.error('Error al cargar especies.', 'Error');
                }
            });
        }

        $('#addEventoModal').on('shown.bs.modal', function () {
            cargarSelectores();
            generarCodigoEvento();
        });

        addEventoForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const saveButton = document.getElementById('saveEventoBtn');
            const saveSpinner = document.getElementById('saveEventoSpinner');
            const saveText = document.getElementById('saveEventoText');
            saveSpinner.style.display = 'inline-block';
            saveText.style.display = 'none';
            saveButton.disabled = true;
            const formData = new FormData(addEventoForm);
            const data = {
                cod_evento: parseInt(formData.get('cod_evento')),
                fecha_hora_evento: formData.get('fecha_hora_evento'),
                Descripcion: formData.get('Descripcion'),
                cod_tipo_evento: parseInt(formData.get('cod_tipo_evento')),
                cod_parque: parseInt(formData.get('cod_parque')),
                cod_especie: formData.get('cod_especie') ? parseInt(formData.get('cod_especie')) : null // Asegura null si no se selecciona
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
                loadEventos(); // Recarga la tabla y el gráfico
                addEventoForm.reset();
                $('#addEventoModal').modal('hide');
            })
            .catch(error => {
                console.error('Error al guardar el evento:', error);
                toastr.error('Error al guardar el evento: ' + error.message, 'Error');
            })
            .finally(() => {
                saveSpinner.style.display = 'none';
                saveText.style.display = 'inline';
                saveButton.disabled = false;
            });
        });

        // --- Lógica para Modales de Editar y Eliminar ---

        // Eventos para los botones de editar y eliminar
        $('#tabla-eventos tbody').on('click', '.btn-edit', function() {
            const codEvento = $(this).data('cod-evento');
            
            // Cargar los datos del evento específico desde la API
            // Nota: Se asume que tu API GET puede filtrar por cod_evento si se lo pasas como query param.
            // Si no, deberías obtener todos los eventos y buscar el que coincida.
            fetch(`${apiURL}?cod_evento=${codEvento}`) 
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.respuesta || 'Error al obtener datos para edición'); });
                    }
                    return response.json();
                })
                .then(data => {
                    const evento = data.find(e => e.cod_evento == codEvento); 
                    if (evento) {
                        $('#edit_cod_evento').val(evento.cod_evento);
                        $('#edit_fecha_hora_evento').val(moment(evento.fecha_hora_evento).format('YYYY-MM-DDTHH:mm'));
                        $('#edit_Descripcion').val(evento.Descripcion);
                        
                        cargarSelectoresEdit(evento.cod_tipo_evento, evento.cod_parque, evento.cod_especie);
                        
                        $('#editEventoModal').modal('show');
                    } else {
                        toastr.error('Evento no encontrado para edición.', 'Error');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar datos para edición:', error);
                    toastr.error('Error al cargar los datos del evento para editar: ' + error.message, 'Error');
                });
        });

        // Este es el manejador para el botón de 'basurero' en cada fila de la tabla
        $('#tabla-eventos tbody').on('click', '.btn-delete', function() {
            const codEvento = $(this).data('cod-evento');
            $('#delete_cod_evento').val(codEvento); // Guarda el código del evento en el campo oculto del modal de eliminación
            $('#deleteEventoModal').modal('show'); // Muestra el modal de confirmación
        });

        // Lógica para cargar y seleccionar valores en los selectores del modal de edición
        function cargarSelectoresEdit(selectedTipoEvento, selectedParque, selectedEspecie) {
            const selectTipoEvento = $('#edit_cod_tipo_evento');
            const selectParque = $('#edit_cod_parque');
            const selectEspecie = $('#edit_cod_especie');

            // Cargar Tipos de Evento
            $.ajax({
                url: apiURLEventos, 
                method: 'GET',
                success: function(data) {
                    selectTipoEvento.find('option:not(:first)').remove(); 
                    data.forEach(item => {
                        selectTipoEvento.append(`<option value="${item.cod_tipo_evento}">${item.nombre_evento}</option>`);
                    });
                    selectTipoEvento.val(selectedTipoEvento); 
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando tipos de evento para edición:', error);
                    toastr.error('Error al cargar tipos de evento.', 'Error');
                }
            });

            // Cargar Parques
            $.ajax({
                url: apiURLParques, 
                method: 'GET',
                success: function(data) {
                    // Cuidado: Usar selectParque aquí, no `const select = $('#cod_parque');`
                    selectParque.find('option:not(:first)').remove(); 
                    data.forEach(item => {
                        selectParque.append(`<option value="${item.cod_parque}">${item.nombre_parque}</option>`);
                    });
                    selectParque.val(selectedParque); 
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando parques para edición:', error);
                    toastr.error('Error al cargar parques.', 'Error');
                }
            });
            
            // Cargar Especies
            $.ajax({
                url: apiURLEspecies, 
                method: 'GET',
                success: function(data) {
                    selectEspecie.find('option:not(:first)').remove(); 
                    data.forEach(item => {
                        selectEspecie.append(`<option value="${item.cod_especie}">${item.nombre_especie}</option>`);
                    });
                    selectEspecie.val(selectedEspecie || ''); 
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando especies para edición:', error);
                    toastr.error('Error al cargar especies.', 'Error');
                }
            });
        }

        // Manejar el envío del formulario de actualización
        $('#editEventoForm').on('submit', function(event) {
            event.preventDefault();
            const updateButton = document.getElementById('updateEventoBtn');
            const updateSpinner = document.getElementById('updateEventoSpinner');
            const updateText = document.getElementById('updateEventoText');
            updateSpinner.style.display = 'inline-block';
            updateText.style.display = 'none';
            updateButton.disabled = true;

            const codEvento = $('#edit_cod_evento').val();
            
            const fieldsToUpdate = [
                { dato_actualizar: 'fecha_hora_evento', nuevo_dato: $('#edit_fecha_hora_evento').val() },
                { dato_actualizar: 'Descripcion', nuevo_dato: $('#edit_Descripcion').val() },
                { dato_actualizar: 'cod_tipo_evento', nuevo_dato: parseInt($('#edit_cod_tipo_evento').val()) },
                { dato_actualizar: 'cod_parque', nuevo_dato: parseInt($('#edit_cod_parque').val()) },
                { dato_actualizar: 'cod_especie', nuevo_dato: $('#edit_cod_especie').val() ? parseInt($('#edit_cod_especie').val()) : null }
            ];

            let updatePromises = [];

            fieldsToUpdate.forEach(field => {
                const updateData = {
                    dato_actualizar: field.dato_actualizar,
                    nuevo_dato: field.nuevo_dato,
                    condicion: 'cod_evento',
                    v_condicion: parseInt(codEvento) 
                };
                
                updatePromises.push(
                    fetch(apiURL, { 
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(updateData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.respuesta || 'Error desconocido al actualizar campo: ' + field.dato_actualizar); });
                        }
                        return response.json();
                    })
                );
            });

            Promise.all(updatePromises)
                .then(results => {
                    toastr.success('Evento actualizado correctamente.', 'Éxito');
                    $('#editEventoModal').modal('hide');
                })
                .catch(error => {
                    console.error('Error al actualizar el evento:', error);
                    toastr.error('Error al actualizar el evento: ' + error.message, 'Error');
                })
                .finally(() => {
                    // Mover loadEventos() aquí para asegurar que la tabla se recargue siempre.
                    loadEventos(); 
                    updateSpinner.style.display = 'none';
                    updateText.style.display = 'inline';
                    updateButton.disabled = false;
                });
        });

        // Manejar la confirmación de eliminación (este es el botón dentro del modal de eliminar)
        $('#confirmDeleteBtn').on('click', function() {
            const codEventoToDelete = $('#delete_cod_evento').val();
            
            // Realiza la petición DELETE a la API de Node.js
            fetch(`${apiURL}?cod_evento=${codEventoToDelete}`, { 
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.respuesta || 'Error desconocido al eliminar'); });
                }
                return response.json();
            })
            .then(result => {
                // Ahora, tanto el mensaje de éxito como el de "no encontrado" vienen como 200 OK.
                if (result.error) { // Si el backend marcó un "error: true" incluso con 200 OK
                    toastr.error(result.respuesta, 'Error');
                } else {
                    toastr.success(result.respuesta || 'Registro eliminado correctamente.', 'Éxito');
                }
                loadEventos(); 
                $('#deleteEventoModal').modal('hide');
            })
            .catch(error => {
                console.error('Error al eliminar el evento:', error);
                toastr.error('Error al eliminar el evento: ' + error.message, 'Error');
            });
        });

        // Función para actualizar el gráfico de eventos
        function updateChart(eventosData) {
            // Agrupamos los eventos por nombre de parque y contamos los tipos de eventos
            const chartData = {};

            eventosData.forEach(evento => {
                const parque = evento.nombre_parque || 'Desconocido';
                const tipoEvento = evento.nombre_evento || 'Sin Tipo';

                if (!chartData[parque]) {
                    chartData[parque] = {};
                }
                chartData[parque][tipoEvento] = (chartData[parque][tipoEvento] || 0) + 1;
            });

            const labels = Object.keys(chartData); // Nombres de los parques
            const datasets = [];
            const colors = [ // Colores predefinidos para los tipos de evento
                'rgba(255, 99, 132, 0.7)', // Rojo
                'rgba(54, 162, 235, 0.7)', // Azul
                'rgba(255, 206, 86, 0.7)', // Amarillo
                'rgba(75, 192, 192, 0.7)', // Verde
                'rgba(153, 102, 255, 0.7)',// Púrpura
                'rgba(255, 159, 64, 0.7)'  // Naranja
            ];
            const borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ];

            // Obtener todos los tipos de eventos únicos para el eje Y o leyendas
            const allEventTypes = new Set();
            eventosData.forEach(evento => {
                allEventTypes.add(evento.nombre_evento || 'Sin Tipo');
            });
            const sortedEventTypes = Array.from(allEventTypes).sort();

            sortedEventTypes.forEach((type, index) => {
                const dataForType = labels.map(parque => chartData[parque][type] || 0);
                datasets.push({
                    label: type,
                    data: dataForType,
                    backgroundColor: colors[index % colors.length],
                    borderColor: borderColors[index % borderColors.length],
                    borderWidth: 1
                });
            });

            const ctx = document.getElementById('eventosChart').getContext('2d');

            if (eventosChartInstance) {
                eventosChartInstance.destroy(); // Destruye la instancia anterior del gráfico
            }

            eventosChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Nombres de los parques
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Cantidad de Eventos por Parque y Tipo de Evento'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            stacked: true, // Barras apiladas por tipo de evento
                            title: {
                                display: true,
                                text: 'Parque'
                            }
                        },
                        y: {
                            stacked: true, // Barras apiladas por tipo de evento
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Eventos'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@stop


