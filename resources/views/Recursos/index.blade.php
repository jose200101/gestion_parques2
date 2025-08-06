@extends('adminlte::page')

@section('title', 'Listado de Recursos')

@section('content_header')
    <h1>Recursos Registrados</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
            Registrar nuevo recurso
        </button>

        <table class="table table-bordered table-hover" id="tabla-recursos">
            <thead class="thead-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha de Registro</th>
                    <th>Estado Actual</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos se llenarán con JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para registrar nuevo recurso -->
<div class="modal fade" id="modalCrearRecurso" tabindex="-1" aria-labelledby="modalCrearRecursoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrar Nuevo Recurso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formCrearRecurso">
          <div class="mb-3">
            <label for="nuevo_cod_recurso" class="form-label">Código</label>
            <input type="number" class="form-control" id="nuevo_cod_recurso" required>
          </div>
          <div class="mb-3">
            <label for="nuevo_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nuevo_nombre" required>
          </div>
          <div class="mb-3">
            <label for="nuevo_descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="nuevo_descripcion" rows="2" required></textarea>
          </div>
          <div class="mb-3">
            <label for="nuevo_fecha" class="form-label">Fecha de Registro</label>
            <input type="date" class="form-control" id="nuevo_fecha" required>
          </div>
          <div class="mb-3">
            <label for="nuevo_estado" class="form-label">Estado Actual</label>
            <input type="text" class="form-control" id="nuevo_estado" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formCrearRecurso" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Editar Recurso -->
<div class="modal fade" id="modalEditarRecurso" tabindex="-1" aria-labelledby="modalEditarRecursoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Recurso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarRecurso">
            <input type="hidden" id="edit_cod_recurso">
            <div class="mb-3">
                <label for="edit_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="edit_nombre" required>
            </div>
            <div class="mb-3">
                <label for="edit_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="edit_descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label for="edit_fecha" class="form-label">Fecha de Registro</label>
                <input type="date" class="form-control" id="edit_fecha" required>
            </div>
            <div class="mb-3">
                <label for="edit_estado" class="form-label">Estado Actual</label>
                <input type="text" class="form-control" id="edit_estado" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('js')
<script>
// Carga dinámica de la tabla de recursos
document.addEventListener('DOMContentLoaded', function () {
    fetch('http://localhost:3000/recursos')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabla-recursos tbody');
            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay recursos registrados.</td></tr>';
                return;
            }

            data.forEach(r => {
                const row = `
                    <tr>
                        <td>${r.cod_recurso}</td>
                        <td>${r.nombre}</td>
                        <td>${r.descripcion}</td>
                        <td>${r.fecha_registro}</td>
                        <td>${r.estado_actual}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarRecurso"
                                onclick='cargarDatosEditar(${JSON.stringify(r)})'>
                                Editar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => {
            console.error('Error al obtener recursos:', error);
            const tbody = document.querySelector('#tabla-recursos tbody');
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar los datos.</td></tr>';
        });
});

// Cargar datos en el modal de edición
function cargarDatosEditar(r) {
    document.getElementById('edit_cod_recurso').value = r.cod_recurso;
    document.getElementById('edit_nombre').value = r.nombre;
    document.getElementById('edit_descripcion').value = r.descripcion;
    document.getElementById('edit_fecha').value = r.fecha_registro?.split('T')[0];
    document.getElementById('edit_estado').value = r.estado_actual;
}
</script>

<!-- Agregar Bootstrap 5 JS y Popper.js si no están cargados -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
@stop
