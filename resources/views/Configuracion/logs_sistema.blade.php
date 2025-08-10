@extends('adminlte::page')

@section('title', 'Logs del Sistema')

@section('content_header')
    <h1>Logs del Sistema</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Logs <span class="badge badge-info" id="logsCount">0</span></h3>
        </div>
        <div class="card-body">
            <table id="logsTable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Acción / Tabla Afectada</th>
                        <th>Descripción</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
                <tfoot>
                    <tr>
                        <th>Código</th>
                        <th>Acción / Tabla Afectada</th>
                        <th>Descripción</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@section('css')
    @stop

@section('js')
   <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiURL = 'http://localhost:3000/bitacoras'; 
            const tableBody = document.querySelector('#logsTable tbody');

            function loadLogs() {
                fetch(apiURL)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al obtener los datos de la API.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if ($.fn.DataTable.isDataTable('#logsTable')) {
                            $('#logsTable').DataTable().destroy();
                        }
                        tableBody.innerHTML = '';
                        data.forEach(log => {
                            const row = document.createElement('tr');
                            // Aquí usamos los nombres de las columnas que la API nos devuelve
                            row.innerHTML = `
                                <td>${log.cod_bitacora}</td>
                                <td>${log.accion} - ${log.tabla_afectada}</td>
                                <td>${log.accion}</td>
                                <td>${new Date(log.fecha_hora).toLocaleString()}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                        document.getElementById('logsCount').innerText = data.length;
                        $('#logsTable').DataTable({
                            "responsive": true,
                            "lengthChange": true,
                            "autoWidth": false,
                            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la petición fetch:', error);
                        tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Error al cargar los datos. Por favor, revisa la consola para más detalles.</td></tr>`;
                    });
            }
            loadLogs();
        });
    </script>
@stop