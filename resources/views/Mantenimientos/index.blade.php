@extends('adminlte::page')

@section('title', 'Listado de Mantenimientos')

@section('content_header')
    <h1>Mantenimientos Registrados</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalNuevoMantenimiento">
            Registrar nuevo mantenimiento
        </button>

        <table class="table table-bordered table-hover" id="tabla-mantenimientos">
            <thead class="thead-dark">
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Código Recurso</th>
                    <th>Código Empleado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos se llenarán con JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Crear -->
<div class="modal fade" id="crearModal" tabindex="-1" aria-labelledby="crearModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="crearModalLabel">Registrar Mantenimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formCrear">
            <div class="mb-3">
                <label for="crear_fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="crear_fecha">
            </div>
            <div class="mb-3">
                <label for="crear_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="crear_descripcion"></textarea>
            </div>
            <div class="mb-3">
                <label for="crear_recurso" class="form-label">Código Recurso</label>
                <input type="text" class="form-control" id="crear_recurso">
            </div>
            <div class="mb-3">
                <label for="crear_empleado" class="form-label">Código Empleado</label>
                <input type="text" class="form-control" id="crear_empleado">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Mantenimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formEditar">
            <input type="hidden" id="edit_cod_mantenimiento">
            <div class="mb-3">
                <label for="edit_fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="edit_fecha">
            </div>
            <div class="mb-3">
                <label for="edit_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="edit_descripcion"></textarea>
            </div>
            <div class="mb-3">
                <label for="edit_recurso" class="form-label">Código Recurso</label>
                <input type="text" class="form-control" id="edit_recurso">
            </div>
            <div class="mb-3">
                <label for="edit_empleado" class="form-label">Código Empleado</label>
                <input type="text" class="form-control" id="edit_empleado">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('http://localhost:3000/mantenimientos')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabla-mantenimientos tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay mantenimientos registrados.</td></tr>';
                return;
            }

            data.forEach(m => {
                const row = `
                    <tr>
                        <td>${m.cod_mantenimiento}</td>
                        <td>${m.fecha_mantenimiento}</td>
                        <td>${m.descripcion}</td>
                        <td>${m.cod_recurso}</td>
                        <td>${m.cod_empleado}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal" onclick='cargarDatosEditar(${JSON.stringify(m)})'>Editar</button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => {
            console.error('Error al obtener mantenimientos:', error);
            const tbody = document.querySelector('#tabla-mantenimientos tbody');
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar los datos.</td></tr>';
        });
});

function cargarDatosEditar(m) {
    document.getElementById('edit_cod_mantenimiento').value = m.cod_mantenimiento;
    document.getElementById('edit_fecha').value = m.fecha_mantenimiento?.split('T')[0] || '';
    document.getElementById('edit_descripcion').value = m.descripcion;
    document.getElementById('edit_recurso').value = m.cod_recurso;
    document.getElementById('edit_empleado').value = m.cod_empleado;
}
</script>

<!-- Modal de Nuevo Mantenimiento -->
<div class="modal fade" id="modalNuevoMantenimiento" tabindex="-1" role="dialog" aria-labelledby="modalNuevoMantenimientoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoMantenimientoLabel">Registrar Nuevo Mantenimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-nuevo-mantenimiento">
            <div class="form-group">
            <label for="cod_mantenimiento">Código de Mantenimiento</label>
            <input type="number" class="form-control" id="cod_mantenimiento" name="cod_mantenimiento" required>
            </div>
          <div class="form-group">
            <label for="nuevaFecha">Fecha</label>
            <input type="date" class="form-control" id="nuevaFecha" required>
          </div>
          <div class="form-group">
            <label for="nuevaDescripcion">Descripción</label>
            <textarea class="form-control" id="nuevaDescripcion" required></textarea>
          </div>
          <div class="form-group">
            <label for="nuevoCodRecurso">Código Recurso</label>
            <input type="number" class="form-control" id="nuevoCodRecurso" required>
          </div>
          <div class="form-group">
            <label for="nuevoCodEmpleado">Código Empleado</label>
            <input type="number" class="form-control" id="nuevoCodEmpleado" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="form-nuevo-mantenimiento" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@stop