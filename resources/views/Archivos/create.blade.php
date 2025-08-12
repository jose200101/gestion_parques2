<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos del Parque Forestal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        #drop-zone {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        #drop-zone.highlight {
            border-color: #3b82f6;
            background-color: #e0f2fe;
        }
        .spinner {
            border-top-color: #3b82f6;
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="p-8 flex items-center justify-center min-h-screen">

<div class="max-w-4xl w-full space-y-8 lg:flex lg:space-x-8 lg:space-y-0">
    <!-- Columna Izquierda: Subir Archivos -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden flex-1">
        <div class="bg-blue-600 text-white p-4 font-bold text-lg text-center">
            Subir Documentos del Parque
        </div>
        <form id="uploadForm" action="/archivos/store" method="POST" enctype="multipart/form-data" class="p-6">
            <div class="mb-4">
                <label for="cod_parque" class="block text-sm font-medium text-gray-700">Seleccione el Parque</label>
                <select id="cod_parque" name="cod_parque" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>-- Seleccionar Parque --</option>
                    {{-- Bucle para generar las opciones de los parques dinámicamente --}}
                    @foreach ($parques as $parque)
                        <option value="{{ $parque->id }}">{{ $parque->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div id="drop-zone"
                 class="mt-3 p-8 border-2 border-dashed border-gray-300 rounded-lg text-center text-gray-500 hover:border-blue-500 hover:bg-blue-50">
                <p class="mb-2">Arrastra tus archivos aquí o haz clic para seleccionar</p>
                <input type="file" id="file-input" name="documentos[]" multiple hidden>
            </div>

            <div id="file-list" class="mt-4 space-y-2">
                {{-- Aquí se mostrará la lista de archivos seleccionados --}}
            </div>

            <div class="mt-6 text-center">
                <button type="submit"
                        class="bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Guardar Archivos
                </button>
            </div>
        </form>
    </div>

    <!-- Columna Derecha: Archivos Existentes -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden flex-1">
        <div class="bg-teal-600 text-white p-4 font-bold text-lg text-center">
            Archivos del Parque Seleccionado
        </div>
        <div id="archivos-existentes" class="p-6 text-center text-gray-500">
            Seleccione un parque para ver sus archivos.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        const parqueSelect = document.getElementById('cod_parque');
        const archivosExistentes = document.getElementById('archivos-existentes');
        const uploadForm = document.getElementById('uploadForm');

        let filesToUpload = new DataTransfer();

        dropZone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight() {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            preventDefaults(e);
            const droppedFiles = e.dataTransfer.files;
            addFilesToUpload(droppedFiles);
        }

        fileInput.addEventListener('change', function() {
            const selectedFiles = this.files;
            filesToUpload = new DataTransfer();
            addFilesToUpload(selectedFiles);
        });

        function addFilesToUpload(files) {
            Array.from(files).forEach(file => {
                filesToUpload.items.add(file);
            });
            updateFileInput();
        }

        function updateFileInput() {
            fileInput.files = filesToUpload.files;
            renderSelectedFiles();
        }

        function renderSelectedFiles() {
            const files = filesToUpload.files;
            if (fileList) {
                fileList.innerHTML = '';
                Array.from(files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between p-3 mb-2 rounded-lg bg-gray-100 shadow-sm border border-gray-200';
                    fileItem.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file text-gray-400"></i>
                            <div>
                                <p class="mb-0 font-medium text-gray-800">${file.name}</p>
                                <small class="text-gray-500">${(file.size / 1024).toFixed(2)} KB</small>
                            </div>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 transition-colors remove-file" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                    fileList.appendChild(fileItem);
                });
                fileList.querySelectorAll('.remove-file').forEach(button => {
                    button.addEventListener('click', removeFile);
                });
            }
        }

        function removeFile(e) {
            const indexToRemove = e.target.closest('.remove-file').dataset.index;
            const newFilesToUpload = new DataTransfer();
            Array.from(filesToUpload.files).forEach((file, index) => {
                if (index != indexToRemove) {
                    newFilesToUpload.items.add(file);
                }
            });
            filesToUpload = newFilesToUpload;
            updateFileInput();
        }

        uploadForm.addEventListener('submit', function(e) {
            if (filesToUpload.files.length === 0) {
                e.preventDefault();
                // Usar un modal o una notificación en lugar de alert()
                console.log('Por favor, seleccione al menos un archivo para subir.');
            }
            if (!parqueSelect.value) {
                e.preventDefault();
                // Usar un modal o una notificación en lugar de alert()
                console.log('Por favor, seleccione un parque.');
            }
        });

        parqueSelect.addEventListener('change', function() {
            const parqueId = this.value;
            if (parqueId) {
                fetchArchivos(parqueId);
            } else {
                archivosExistentes.innerHTML = '<p class="text-center text-gray-500">Seleccione un parque para ver sus archivos.</p>';
            }
        });

        async function fetchArchivos(parqueId) {
            archivosExistentes.innerHTML = '<div class="text-center p-6"><i class="fas fa-spinner fa-spin fa-2x text-blue-500"></i></div>';
            
            try {
                const response = await fetch(`http://localhost:3000/parques/${parqueId}/archivos`);
                
                if (response.ok) {
                    const archivos = await response.json();
                    renderArchivos(archivos);
                } else {
                    archivosExistentes.innerHTML = '<p class="text-red-500 text-center">Error al cargar los archivos.</p>';
                    console.error('Error fetching files:', response.statusText);
                }
            } catch (error) {
                archivosExistentes.innerHTML = '<p class="text-red-500 text-center">Error al conectar con la API.</p>';
                console.error('Error fetching files:', error);
            }
        }

        function renderArchivos(archivos) {
            if (archivos.length === 0) {
                archivosExistentes.innerHTML = '<p class="text-center text-gray-500">No hay archivos para este parque.</p>';
                return;
            }

            archivosExistentes.innerHTML = '';
            archivos.forEach(archivo => {
                let iconHtml = '';
                if (archivo.mimetype.startsWith('image/')) {
                    iconHtml = '<i class="fas fa-file-image fa-2x text-blue-500 mr-2"></i>';
                } else if (archivo.mimetype.includes('pdf')) {
                    iconHtml = '<i class="fas fa-file-pdf fa-2x text-red-500 mr-2"></i>';
                } else {
                    iconHtml = '<i class="fas fa-file fa-2x text-gray-400 mr-2"></i>';
                }

                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 mb-2 rounded-lg bg-gray-100 shadow-sm border border-gray-200';
                fileItem.innerHTML = `
                    <div class="flex items-center space-x-3">
                        ${iconHtml}
                        <div>
                            <p class="mb-0 font-medium text-gray-800">${archivo.nombre}</p>
                            <small class="text-gray-500">${(archivo.tamano / 1024).toFixed(2)} KB</small>
                        </div>
                    </div>
                    <a href="${archivo.url}" target="_blank" class="text-blue-500 hover:text-blue-700 transition-colors">
                        <i class="fas fa-eye"></i>
                    </a>
                `;
                archivosExistentes.appendChild(fileItem);
            });
        }
    });
</script>
</body>
</html>
