@extends('adminlte::page')

@section('title', 'Subir Archivos')

@section('content_header')
    <h1 class="text-3xl font-weight-bold text-dark">Subir Archivos a un Parque Forestal</h1>
@stop

@section('content')
    <div class="card card-primary card-outline shadow-lg">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5><i class="icon fas fa-check"></i> Éxito!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <form id="upload-form" action="{{ route('archivos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Paso 1 - Selección del Parque -->
                <div class="form-group">
                    <label for="parque-select">Seleccione el Parque:</label>
                    <select id="parque-select" name="cod_parque" class="form-control select2bs4" style="width: 100%;">
                        <option value="">-- Seleccionar Parque --</option>
                        @foreach($parques as $parque)
                            <option value="{{ $parque['cod_parque'] }}">{{ $parque['nombre_parque'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Paso 2 - Área de Carga de Archivos -->
                <div id="upload-area" class="border border-dashed p-5 text-center bg-light rounded" style="display:none; cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                    <p class="text-muted font-weight-bold">Arrastre y suelte archivos aquí o haga clic para seleccionarlos</p>
                    <input type="file" id="file-input" name="documento[]" style="display:none;" multiple>
                </div>

                <!-- Sección para mostrar la vista previa de los archivos -->
                <div id="preview-area" class="mt-4 row">
                    <!-- Los archivos se renderizarán aquí -->
                </div>
                
                <div class="mt-4 text-right">
                    <button type="submit" class="btn btn-primary" id="submit-button" disabled>
                        <i class="fas fa-upload mr-2"></i> Subir Documentos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Nueva sección para mostrar los archivos existentes -->
    <div class="card card-secondary card-outline shadow-lg mt-5">
        <div class="card-header">
            <h2 class="card-title font-weight-bold">Archivos Existentes</h2>
        </div>
        <div class="card-body" id="existing-files-area">
            <p class="text-muted">Seleccione un parque para ver los archivos existentes.</p>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const parqueSelect = document.getElementById('parque-select');
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('file-input');
            const previewArea = document.getElementById('preview-area');
            const submitButton = document.getElementById('submit-button');
            const existingFilesArea = document.getElementById('existing-files-area');
            
            // Este es el array que ahora almacena todos los archivos
            let selectedFiles = new DataTransfer();

            // Inicializar Select2 para el menú desplegable
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
            
            // Función para verificar el estado del formulario y habilitar/deshabilitar el botón
            function checkFormStatus() {
                // El botón se habilita solo si hay un parque seleccionado Y archivos en el array
                submitButton.disabled = !(parqueSelect.value && selectedFiles.files.length > 0);
            }

            // Llamar a checkFormStatus al cargar la página para el estado inicial del botón
            checkFormStatus();

            parqueSelect.addEventListener('change', function() {
                if (parqueSelect.value) {
                    uploadArea.style.display = 'block';
                    fetchExistingFiles(parqueSelect.value);
                } else {
                    uploadArea.style.display = 'none';
                    existingFilesArea.innerHTML = '<p class="text-muted">Seleccione un parque para ver los archivos existentes.</p>';
                }
                // Limpiar la vista previa y el array de archivos cuando se cambia de parque
                previewArea.innerHTML = '';
                fileInput.value = ''; // Esto es importante para limpiar el input de tipo file
                selectedFiles = new DataTransfer(); // Reiniciar el array
                checkFormStatus();
            });

            // Nueva función para obtener y mostrar los archivos existentes
            function fetchExistingFiles(cod_parque) {
                // Aquí deberías hacer una llamada AJAX a tu API
                // Por ahora, simularemos una respuesta
                existingFilesArea.innerHTML = '<p class="text-center"><i class="fas fa-sync-alt fa-spin"></i> Cargando archivos...</p>';

                // Simulación de una respuesta de la API
                setTimeout(() => {
                    const mockFiles = [
                        { name: 'documento_parque_A.pdf', type: 'application/pdf', url: '#' },
                        { name: 'mapa_parque.jpg', type: 'image/jpeg', url: '#' },
                        { name: 'video_presentacion.mp4', type: 'video/mp4', url: '#' },
                        { name: 'reglamento.docx', type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', url: '#' },
                        { name: 'plantilla.xlsx', type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', url: '#' }
                    ];

                    existingFilesArea.innerHTML = '';
                    const fileGrid = document.createElement('div');
                    fileGrid.className = 'row';

                    if (mockFiles.length > 0) {
                        mockFiles.forEach(file => {
                            let fileIcon = 'fas fa-file text-secondary';
                            const maxFilenameLength = 20;
                            const displayName = file.name.length > maxFilenameLength ?
                                file.name.substring(0, maxFilenameLength) + '...' : file.name;

                            if (file.type.startsWith('image/')) {
                                fileIcon = 'fas fa-file-image text-info';
                            } else if (file.type === 'application/pdf') {
                                fileIcon = 'fas fa-file-pdf text-danger';
                            } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || file.type === 'application/msword') {
                                fileIcon = 'fas fa-file-word text-primary';
                            } else if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || file.type === 'application/vnd.ms-excel') {
                                fileIcon = 'fas fa-file-excel text-success';
                            } else if (file.type.startsWith('video/')) {
                                fileIcon = 'fas fa-file-video text-warning';
                            }

                            const fileItem = document.createElement('div');
                            fileItem.className = 'col-md-3 mb-3';
                            fileItem.innerHTML = `
                                <div class="card p-2 text-center h-100">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 100px; overflow: hidden;">
                                        <i class="${fileIcon} fa-3x mb-2"></i>
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text text-truncate font-weight-bold mt-2 mb-0">${displayName}</p>
                                        <a href="${file.url}" class="btn btn-sm btn-info mt-2" download>
                                            <i class="fas fa-download mr-1"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                            `;
                            fileGrid.appendChild(fileItem);
                        });
                        existingFilesArea.appendChild(fileGrid);
                    } else {
                        existingFilesArea.innerHTML = '<p class="text-muted">No hay archivos existentes para este parque.</p>';
                    }
                }, 1000);
            }

            uploadArea.addEventListener('click', () => fileInput.click());

            ['dragover', 'dragleave', 'drop'].forEach(event => {
                uploadArea.addEventListener(event, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (event === 'dragover') {
                        uploadArea.classList.add('border-primary');
                        uploadArea.classList.add('bg-white');
                    } else if (event === 'dragleave' || event === 'drop') {
                        uploadArea.classList.remove('border-primary');
                        uploadArea.classList.remove('bg-white');
                    }
                    if (event === 'drop') {
                        addFiles(e.dataTransfer.files);
                    }
                });
            });

            fileInput.addEventListener('change', () => {
                addFiles(fileInput.files);
            });

            // Función para agregar los archivos a la lista de seleccionados
            function addFiles(files) {
                // Agregar cada archivo de la nueva lista al objeto DataTransfer
                for (const file of files) {
                    selectedFiles.items.add(file);
                }
                
                // Asignar los archivos al input para que se envíen con el formulario
                fileInput.files = selectedFiles.files;

                // Luego, renderizar la vista previa completa desde el array
                renderPreviews(selectedFiles.files);

                // Actualizar el estado del botón
                checkFormStatus();
            }

            function renderPreviews(files) {
                // Limpiar el área de vista previa antes de renderizar de nuevo
                previewArea.innerHTML = '';
                
                // Iterar sobre todos los archivos en el array
                for (const file of files) {
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'col-md-3 mb-3';

                            let content = '';
                            let fileIcon = 'fas fa-file text-secondary';
                            const maxFilenameLength = 20;
                            const displayName = file.name.length > maxFilenameLength ?
                                file.name.substring(0, maxFilenameLength) + '...' : file.name;

                            if (file.type.startsWith('image/')) {
                                content = `<img src="${e.target.result}" class="img-fluid rounded" alt="${file.name}">`;
                            } else if (file.type.startsWith('video/')) {
                                content = `<video src="${e.target.result}" controls class="img-fluid rounded"></video>`;
                            } else {
                                if (file.type === 'application/pdf') {
                                    fileIcon = 'fas fa-file-pdf text-danger';
                                } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || file.type === 'application/msword') {
                                    fileIcon = 'fas fa-file-word text-primary';
                                } else if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || file.type === 'application/vnd.ms-excel') {
                                    fileIcon = 'fas fa-file-excel text-success';
                                }
                                content = `<i class="${fileIcon} fa-3x mb-2"></i>`;
                            }

                            fileItem.innerHTML = `
                                <div class="card p-2 text-center h-100">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 100px; overflow: hidden;">
                                        ${content}
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text text-truncate font-weight-bold mt-2 mb-0">${displayName}</p>
                                    </div>
                                </div>
                            `;
                            previewArea.appendChild(fileItem);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@stop

@section('plugins.FontAwesome', true)
@section('plugins.Select2', true)



