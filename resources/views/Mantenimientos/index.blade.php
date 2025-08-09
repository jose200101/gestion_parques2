@extends('adminlte::page')

@section('title', 'Listado de Mantenimientos')

@section('content_header')
    <h1>Mantenimientos Registrados</h1>
@stop

@section('content')
<div class="card">  
    <div class="card-body">
        {{-- Abre el modal para crear un mantenimiento --}}
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoMantenimiento">
            Registrar nuevo mantenimiento
        </button>

        {{-- La tabla se llena desde JS --}}
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
                {{-- Filas generadas en cargarMantenimientos() --}} 
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: crear -->
<div class="modal fade" id="modalNuevoMantenimiento" tabindex="-1" aria-labelledby="modalNuevoMantenimientoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoMantenimientoLabel">Registrar Nuevo Mantenimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="form-nuevo-mantenimiento">
          <div class="form-group">
            <label for="cod_mantenimiento">Código de Mantenimiento</label>
            <input type="number" class="form-control" id="cod_mantenimiento" required>
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

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Editar Mantenimiento  -->
<div class="modal fade" id="modalEditarMantenimiento" tabindex="-1" aria-labelledby="modalEditarMantenimientoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarMantenimientoLabel">Editar Mantenimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarMantenimiento">
            <input type="hidden" id="edit_cod_mantenimiento">
            <div class="mb-3">
                <label for="edit_fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="edit_fecha" required>
            </div>
            <div class="mb-3">
                <label for="edit_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="edit_descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label for="edit_cod_recurso" class="form-label">Código Recurso</label>
                <input type="number" class="form-control" id="edit_cod_recurso" required>
            </div>
            <div class="mb-3">
                <label for="edit_cod_empleado" class="form-label">Código Empleado</label>
                <input type="number" class="form-control" id="edit_cod_empleado" required>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@stop

@section('js')
{{-- Bootstrap 5 --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script>
// ===== Metodo para "Ocultar" los datos de la tabla) =====
function getOcultosMantenimientos() {
  try {
    const arr = JSON.parse(localStorage.getItem('ocultos_mantenimientos') || '[]');
    // Normalizamos TODO a string por si hay números guardados de versiones anteriores
    return new Set(arr.map(v => String(v)));
  } catch {
    return new Set();
  }
}

function setOcultosMantenimientos(set) {
  localStorage.setItem('ocultos_mantenimientos', JSON.stringify([...set].map(v => String(v))));
}

// Oculta una fila de la tabla y recuerda el id para no mostrarlo después
function ocultarMantenimiento(id, btn) {
  if (!confirm('¿Eliminar este mantenimiento de la lista?')) return;
  const tr = btn.closest('tr');
  if (tr) tr.remove();

  const ocultos = getOcultosMantenimientos();
  ocultos.add(String(id));           // <-- string
  setOcultosMantenimientos(ocultos);
}

// Saca un id de la lista de ocultos (se usa al crear de nuevo ese código)
function quitarOcultoMantenimiento(id) {
  const ocultos = getOcultosMantenimientos();
  // Intentamos borrar tanto la versión string como por si quedó como número
  ocultos.delete(String(id));
  ocultos.delete(Number(id));        // por compatibilidad hacia atrás
  setOcultosMantenimientos(ocultos);
}
</script>

<script>

// Carga/recarga la tabla desde la API y salta los ids “ocultos”
document.addEventListener('DOMContentLoaded', function () {
  cargarMantenimientos();
});

function cargarMantenimientos() {
  const ocultos = getOcultosMantenimientos();
  fetch('http://localhost:3000/mantenimientos')
    .then(response => response.json())
    .then(data => {
      const tbody = document.querySelector('#tabla-mantenimientos tbody');
      tbody.innerHTML = '';

      if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay mantenimientos registrados.</td></tr>';
        return;
      }

      data.forEach(m => {
        if (ocultos.has(String(m.cod_mantenimiento))) return;
        const row = `
          <tr>
            <td>${m.cod_mantenimiento}</td>
            <td>${m.fecha_mantenimiento}</td>
            <td>${m.descripcion}</td>
            <td>${m.cod_recurso}</td>
            <td>${m.cod_empleado}</td>
            <td>
             {{-- Abre modal y rellena con los datos de esta fila --}}
              <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal" data-bs-target="#modalEditarMantenimiento"
                onclick='cargarDatosEditar(${JSON.stringify(m)})'>
                Editar
              </button>
              {{-- Oculta visualmente la fila y recuerda el id --}}
              <button class="btn btn-sm btn-danger ms-1"
                onclick="ocultarMantenimiento(${m.cod_mantenimiento}, this)">
                Eliminar
              </button>
            </td>
          </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
      });
    })
    .catch(error => {
      console.error('Error al obtener mantenimientos:', error);
      const tbody = document.querySelector('#tabla-mantenimientos tbody');
      tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar los datos.</td></tr>';
    });
}

// ===== Rellenar modal de edición con los datos de la fila=====
function cargarDatosEditar(m) {
  document.getElementById('edit_cod_mantenimiento').value = m.cod_mantenimiento;
  document.getElementById('edit_fecha').value = (m.fecha_mantenimiento || '').split('T')[0] || '';
  document.getElementById('edit_descripcion').value = m.descripcion || '';
  document.getElementById('edit_cod_recurso').value = m.cod_recurso ?? '';
  document.getElementById('edit_cod_empleado').value = m.cod_empleado ?? '';
}
</script>

<script>
// ===== Crear nuevo mantenimiento =====
document.getElementById('form-nuevo-mantenimiento').addEventListener('submit', async function (e) {
  e.preventDefault();

   // Se toman los valores del formulario
  const cod_mantenimiento = document.getElementById('cod_mantenimiento').value;
  const fecha_mantenimiento = document.getElementById('nuevaFecha').value;
  const descripcion = document.getElementById('nuevaDescripcion').value;
  const cod_recurso = document.getElementById('nuevoCodRecurso').value;
  const cod_empleado = document.getElementById('nuevoCodEmpleado').value;

  if (!cod_mantenimiento || !fecha_mantenimiento || !descripcion || !cod_recurso || !cod_empleado) {
    alert('Todos los campos son obligatorios.');
    return;
  }

  const mantenimiento = {
    cod_mantenimiento,
    fecha_mantenimiento,
    descripcion,
    cod_recurso,
    cod_empleado
  };

  try {
    const response = await fetch('http://localhost:3000/mantenimientos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(mantenimiento)
    });

    const raw = await response.text();
    if (!response.ok) throw new Error(raw || 'Error en la solicitud');

    alert('✅Mantenimiento registrado exitosamente');

    // Cierra modal y limpia el form
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoMantenimiento'));
    if (modal) modal.hide();

    document.getElementById('form-nuevo-mantenimiento').reset();

    quitarOcultoMantenimiento(cod_mantenimiento);

    // Refresca la tabla
    cargarMantenimientos();

  } catch (error) {
    console.error("Error al registrar mantenimiento:", error.message);
    alert("Hubo un error al registrar el mantenimiento: " + error.message);
  }
});
</script>

<script>
// ===== Editar mantenimiento (PUT por campo) =====
async function actualizarCampoMantenimiento(campo, valor, codMantenimiento) {
  const numericos = ['cod_recurso', 'cod_empleado'];
  let nuevoValor = valor;
  if (numericos.includes(campo)) {
    const n = Number(valor);
    if (!Number.isNaN(n)) nuevoValor = n;
  }

  const payload = {
    dato_actualizar: campo,
    nuevo_dato: nuevoValor,
    condicion: 'cod_mantenimiento',
    v_condicion: Number(codMantenimiento)
  };

  const resp = await fetch('http://localhost:3000/mantenimientos', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });

  const raw = await resp.text();
  if (!resp.ok) throw new Error(raw || `Error al actualizar ${campo}`);
  return true;
}

// Envía los cambios del modal de edición (uno por campo) y recarga
document.addEventListener('DOMContentLoaded', function () {
  const formEdit = document.getElementById('formEditarMantenimiento');

  formEdit.addEventListener('submit', async function (e) {
    e.preventDefault();

    // Lee campos del modal
    const cod_mantenimiento = document.getElementById('edit_cod_mantenimiento').value;
    const fecha_mantenimiento = document.getElementById('edit_fecha').value;
    const descripcion = document.getElementById('edit_descripcion').value.trim();
    const cod_recurso = document.getElementById('edit_cod_recurso').value;
    const cod_empleado = document.getElementById('edit_cod_empleado').value;

    if (!cod_mantenimiento || !fecha_mantenimiento || !descripcion || !cod_recurso || !cod_empleado) {
      alert('Todos los campos son obligatorios.');
      return;
    }

     // Lista de cambios a mandar
    const cambios = [
      { campo: 'fecha_mantenimiento', valor: fecha_mantenimiento },
      { campo: 'descripcion',         valor: descripcion },
      { campo: 'cod_recurso',         valor: cod_recurso },
      { campo: 'cod_empleado',        valor: cod_empleado }
    ];

    let ok = 0;
    for (const c of cambios) {
      try { if (await actualizarCampoMantenimiento(c.campo, c.valor, cod_mantenimiento)) ok++; }
      catch (err) { console.error(`Error en ${c.campo}:`, err); }
    }

    // Feedback rápido
    if (ok === cambios.length) {
      alert('✅Mantenimiento actualizado correctamente.');
    } else if (ok > 0) {
      alert(`Actualizado parcialmente (${ok}/${cambios.length}). Revisa consola.`);
    } else {
      alert('No se pudo actualizar el mantenimiento.');
    }

    // Cierra modal, limpia y recarga
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarMantenimiento'));
    if (modal) modal.hide();
    formEdit.reset();
    cargarMantenimientos();
  });
});
</script>

@stop