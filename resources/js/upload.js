document.addEventListener('DOMContentLoaded', function() {

    // Referencias a los elementos del DOM
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileList = document.getElementById('file-list');
    const parqueSelect = document.getElementById('cod_parque');
    const archivosExistentes = document.getElementById('archivos-existentes');
    const uploadForm = document.getElementById('uploadForm');

    // Usamos un objeto DataTransfer global para manejar los archivos arrastrados y seleccionados
    let filesToUpload = new DataTransfer();

    // Manejar el clic en la zona de drag and drop para abrir el selector de archivos
    dropZone.addEventListener('click', () => fileInput.click());

    // Event listeners para la funcionalidad de arrastrar y soltar
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Funciones para manejar los efectos visuales (clases de Tailwind)
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

    // Manejar el evento de soltar archivos
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        preventDefaults(e);
        const droppedFiles = e.dataTransfer.files;
        addFilesToUpload(droppedFiles);
    }

    // Manejar el cambio del input de archivos
    fileInput.addEventListener('change', function() {
        const selectedFiles = this.files;
        // Limpiamos la lista de archivos anterior para evitar duplicados si se selecciona desde el input
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

    // Función para mostrar los archivos seleccionados en la UI (clases de Tailwind)
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

    // Manejar el envío del formulario
    uploadForm.addEventListener('submit', function(e) {
        if (filesToUpload.files.length === 0) {
            e.preventDefault();
            alert('Por favor, seleccione al menos un archivo para subir.');
        }
        if (!parqueSelect.value) {
            e.preventDefault();
            alert('Por favor, seleccione un parque.');
        }
    });

    // Lógica para mostrar archivos existentes (clases de Tailwind)
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