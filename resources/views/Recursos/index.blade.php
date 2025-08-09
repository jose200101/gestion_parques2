@extends('adminlte::page')

@section('title', 'Listado de Recursos')

@section('content_header')
    <h1>Recursos Registrados</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
      <!-- Abre el modal para crear un recurso -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
            Registrar nuevo recurso
        </button>
        <!-- La tabla se llena con JS -->
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
                <!-- Filas generadas en cargarRecursos() -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para registrar un nuevo recurso -->
<div class="modal fade" id="modalCrearRecurso" tabindex="-1" aria-labelledby="modalCrearRecursoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Título y botón para cerrar -->
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
        <!-- Cancelar y enviar (el form se envía por atributo form) -->
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
        <!-- Título y botón para cerrar -->
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
        <!-- Cancelar y guardar cambios -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formEditarRecurso" class="btn btn-success">Guardar Cambios</button>
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

            // Si no hay datos, mostramos un mensaje y listo
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay recursos registrados.</td></tr>';
                return;
            }

            // Armamos cada fila a partir de la respuesta
            data.forEach(r => {
                const row = `
                    <tr>
                        <td>${r.cod_recurso}</td>
                        <td>${r.nombre}</td>
                        <td>${r.descripcion}</td>
                        <td>${r.fecha_registro}</td>
                        <td>${r.estado_actual}</td>
                        <td>
                          <!-- Abre modal de edición con los datos de esta fila -->
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

<!-- Agregar Bootstrap 5 JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script>
// ===== Metodo para "Ocultar" los datos de la tabla =====
function getOcultosRecursos() {
  try {
    const arr = JSON.parse(localStorage.getItem('ocultos_recursos') || '[]');
    return new Set(arr.map(v => String(v)));
  } catch {
    return new Set();
  }
}

function setOcultosRecursos(set) {
  localStorage.setItem('ocultos_recursos', JSON.stringify([...set].map(v => String(v))));
}

function ocultarRecurso(id, btn) {
  if (!confirm('¿Eliminar este recurso de la lista?')) return;
  const tr = btn.closest('tr');
  if (tr) tr.remove();

  const ocultos = getOcultosRecursos();
  ocultos.add(String(id)); // guardar como string
  setOcultosRecursos(ocultos);
}

// Saca un código de la lista de ocultos (y se usa al crear uno nuevo)
function quitarOcultoRecurso(id) {
  const ocultos = getOcultosRecursos();
  ocultos.delete(String(id));   
  ocultos.delete(Number(id));   
  setOcultosRecursos(ocultos);
}
</script>

<script>
  // Carga/recarga la tabla, respetando la lista de ocultos
function cargarRecursos() {
  const ocultos = getOcultosRecursos();
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
               if (ocultos.has(Number(r.cod_recurso))) return;
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
                            <button class="btn btn-sm btn-danger ms-1"
                                onclick="ocultarRecurso(${r.cod_recurso}, this)">
                                Eliminar
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
}
// Rellena el modal con la fila que se va a editar
function cargarDatosEditar(r) {
    document.getElementById('edit_cod_recurso').value = r.cod_recurso;
    document.getElementById('edit_nombre').value = r.nombre;
    document.getElementById('edit_descripcion').value = r.descripcion;
    document.getElementById('edit_fecha').value = r.fecha_registro?.split('T')[0];
    document.getElementById('edit_estado').value = r.estado_actual;
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    cargarRecursos();

    const form = document.getElementById('formCrearRecurso');
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Rellena el modal con la fila que se va a editar
        const cod_recurso = parseInt(document.getElementById('nuevo_cod_recurso').value);
        const nombre = document.getElementById('nuevo_nombre').value;
        const descripcion = document.getElementById('nuevo_descripcion').value;
        const fecha_registro = document.getElementById('nuevo_fecha').value;
        const estado_actual = document.getElementById('nuevo_estado').value;

        if (!cod_recurso || !nombre || !descripcion || !fecha_registro || !estado_actual) {
            alert('Todos los campos son obligatorios.');
            return;
        }

        // se arma el JSON tal cual se espera del backend
        const nuevoRecurso = {
            cod_recurso,
            nombre,
            descripcion,
            fecha_registro,
            estado_actual
        };

        try {
          // Se envia el POST
            const response = await fetch('http://localhost:3000/recursos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(nuevoRecurso)
            });

            const respuestaTexto = await response.text();

            if (!response.ok) {
                throw new Error(respuestaTexto || 'Error desconocido al registrar el recurso');
            }

            alert('✅Recurso registrado exitosamente');

            // Cerrar el modal
            const modalElement = document.getElementById('modalCrearRecurso');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();

            // Limpiar formulario
            form.reset();

            quitarOcultoRecurso(String(cod_recurso));

            // Recargar tabla
            cargarRecursos();

        } catch (error) {
            console.error('[ERROR] Fallo en el registro:', error);
            alert('Hubo un error al registrar el recurso:\n' + error.message);
        }
    });
});
</script>

<script>
// --- EDITAR RECURSO usando PUT /recursos (dato_actualizar, nuevo_dato, condicion, v_condicion) ---
document.addEventListener('DOMContentLoaded', function () {
  const formEdit = document.getElementById('formEditarRecurso');

  // Función auxiliar: actualiza un solo campo
  async function actualizarCampoRecurso(campo, valor, codRecurso) {
    // Convertir tipo numérico si aplica
    let nuevoValor = valor;
    if (campo === 'estado_actual' || campo === 'cod_recurso') {
      const num = Number(valor);
      if (!isNaN(num)) nuevoValor = num;
    }

    const payload = {
      dato_actualizar: campo,
      nuevo_dato: nuevoValor,
      condicion: 'cod_recurso',
      v_condicion: Number(codRecurso)
    };

    try {
      const resp = await fetch('http://localhost:3000/recursos', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const raw = await resp.text();
      if (!resp.ok) throw new Error(raw || `Error al actualizar ${campo}`);
      console.log(`✔ Campo "${campo}" actualizado con éxito`);
      return true;
    } catch (err) {
      console.error(`✘ Error actualizando "${campo}":`, err);
      return false;
    }
  }

  // Al guardar el modal, mandamos los cambios campo por campo
  formEdit.addEventListener('submit', async function (e) {
    e.preventDefault();

    // 1️⃣ Tomar valores del modal
    const cod_recurso    = document.getElementById('edit_cod_recurso').value;
    const nombre         = document.getElementById('edit_nombre').value.trim();
    const descripcion    = document.getElementById('edit_descripcion').value.trim();
    const fecha_registro = document.getElementById('edit_fecha').value;
    const estado_actual  = document.getElementById('edit_estado').value.trim();

    if (!cod_recurso || !nombre || !descripcion || !fecha_registro || !estado_actual) {
      alert('Todos los campos son obligatorios.');
      return;
    }

    // Lista de campos a actualizar
    const campos = [
      { campo: 'nombre',         valor: nombre },
      { campo: 'descripcion',    valor: descripcion },
      { campo: 'fecha_registro', valor: fecha_registro },
      { campo: 'estado_actual',  valor: estado_actual }
    ];

    let exitosos = 0;
    for (const c of campos) {
      const ok = await actualizarCampoRecurso(c.campo, c.valor, cod_recurso);
      if (ok) exitosos++;
    }

    // Mostrar resultado final
    if (exitosos === campos.length) {
      alert('✅ Recurso actualizado correctamente.');
    } else if (exitosos > 0) {
      alert(`⚠ Recurso actualizado parcialmente (${exitosos}/${campos.length} campos). Revisa la consola.`);
    } else {
      alert('❌ No se pudo actualizar el recurso.');
    }

    // Cerrar modal y recargar tabla
    const modalEl = document.getElementById('modalEditarRecurso');
    const modal   = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();

    formEdit.reset();
    cargarRecursos();
  });
});
</script>

<script>
function getOcultosRecursos() {
  try { return new Set(JSON.parse(localStorage.getItem('ocultos_recursos') || '[]')); }
  catch { return new Set(); }
}
function setOcultosRecursos(set) {
  localStorage.setItem('ocultos_recursos', JSON.stringify([...set]));
}
function ocultarRecurso(id, btn) {
  if (!confirm('¿Ocultar este recurso de la lista?')) return;
  const tr = btn.closest('tr');
  if (tr) tr.remove();
  const ocultos = getOcultosRecursos();
  ocultos.add(Number(id));
  setOcultosRecursos(ocultos);
}
</script>

@stop

