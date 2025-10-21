@extends('layouts.admin.admin')

@section('title', 'Nuevo Producto - Admin')
@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush
@section('content')



<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-espresso text-light border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 py-1"> Nuevo Producto</h5>
        </div>
        
        <div class="collapse show" id="collapseAccesos">
            <div class="card-body bg-caramel">
                <form id="newProductoForm" action="{{ route('admin.productos.crear') }}" method="POST" novalidate>
                    @csrf
                    <!-- Campos categoria y nombre -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label color-coffee fw-semibold">
                                Nombre: <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required class="form-control input-texto bg-sand border-chocolate color-cream @error('nombre') is-invalid @enderror">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Por favor ingresa el nombre del producto.</div>
                                <div class="valid-feedback">¡Se ve bien!</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label color-coffee fw-semibold">
                                Categoría: <span class="text-danger">*</span>
                            </label>
                            <select id="categoria" name="categoria" required class="form-control form-select input-texto bg-sand border-chocolate color-cream @error('categoria') is-invalid @enderror">
                                <option value="" disabled selected>Seleccione una categoría...</option>
                                <option value="Panaderia" {{ old('categoria', $producto->categoria ?? '') == 'Panaderia' ? 'selected' : '' }}>Panadería</option>
                                <option value="Pasteleria" {{ old('categoria', $producto->categoria ?? '') == 'Pasteleria' ? 'selected' : '' }}>Pastelería</option>
                                <option value="Salados" {{ old('categoria', $producto->categoria ?? '') == 'Salados' ? 'selected' : '' }}>Salados</option>
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Por favor seleccione una categoría.</div>
                                <div class="valid-feedback">¡Excelente!</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Campos tipo y precio -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label color-coffee fw-semibold">
                                Tipo: <span class="text-danger">*</span>
                            </label>
                            <select id="tipo" name="tipo" required class="form-control form-select input-texto bg-sand border-chocolate color-cream @error('tipo') is-invalid @enderror">
                                <option value="" disabled selected>Seleccione una categoría primero...</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Por favor seleccione un tipo.</div>
                                <div class="valid-feedback">¡Excelente!</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label color-coffee fw-semibold">
                                Precio: <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control input-texto bg-sand border-chocolate color-cream @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $producto->precio ?? '') }}" step="0.01" min="0" max="99999999.99" required>            
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Por favor ingresa un precio válido.</div>
                                <div class="valid-feedback">¡precio válido!</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Campos unidad de venta y cantidad -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unidad" class="form-label color-coffee fw-semibold">
                                Unidad de Venta: <span class="text-danger">*</span>
                            </label>
                            <select id="unidad" name="unidad" required class="form-control form-select input-texto bg-sand border-chocolate color-cream @error('unidad') is-invalid @enderror">
                                <option value="" disabled selected>Seleccione una unidad...</option>
                                <option value="unidad" {{ old('unidad', $producto->unidad_venta ?? '') == 'unidad' ? 'selected' : '' }}>Unidades</option>
                                <option value="docena" {{ old('unidad', $producto->unidad_venta ?? '') == 'docena' ? 'selected' : '' }}>Docenas</option>
                                <option value="media_docena" {{ old('unidad', $producto->unidad_venta ?? '') == 'media_docena' ? 'selected' : '' }}>Media docenas</option>
                                <option value="kg" {{ old('unidad', $producto->unidad_venta ?? '') == 'kg' ? 'selected' : '' }}>Kilogramos</option>
                            </select>
                            @error('unidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Por favor seleccione una unidad.</div>
                                <div class="valid-feedback">¡Excelente!</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad" class="form-label color-coffee fw-semibold">
                                Cantidad: <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control input-texto bg-sand border-chocolate color-cream @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad', $producto->cantidad ?? '') }}" step="0.01" min="0" max="99999999.99" required>            
                            @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">Por favor ingresa una cantidad válida.</div>
                                <div class="valid-feedback">¡cantidad válida!</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Campos descripcion y imagenes -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="descripcion" class="form-label color-coffee fw-semibold">
                                Descripcion: <span class="text-danger">*</span>
                            </label>
                            <textarea style="min-height: 200px;" class="form-control input-texto bg-sand border-chocolate color-cream @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4" required> {{ old('descripcion') }} </textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">La descripcion debe tener al menos 5 caracteres.</div>
                                <div class="valid-feedback">¡Descripcion perfecta!</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label color-coffee fw-semibold">
                                Imagen del Producto: <span class="text-danger">*</span>
                            </label>
                            <div class="drop-area-single border-chocolate bg-sand d-flex align-items-center justify-content-center flex-column p-4 @error('imagen') border-danger @enderror" 
                                style="min-height: 200px; border: 2px dashed; border-radius: 10px; cursor: pointer;">
                                <h5 class="color-chocolate mb-2" id="drag-text">Arrastra y suelta una imagen aquí</h5>
                                <span class="color-coffee mb-2">O</span>
                                <button type="button" class="btn btn-sm" style="background-color: var(--color-chocolate); color: var(--color-cream);" id="btn-browse">
                                    <i class="bi bi-folder-open"></i> Buscar imagen
                                </button>
                                <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none" id="input-imagen" name="imagen">
                                <input type="hidden" name="imagen_base64" id="imagen-base64" value="{{ old('imagen_base64') }}">
                                <small class="text-muted mt-2">Formatos: PNG, JPG, JPEG, WEBP (Max: 2MB)</small>
                            </div>
                            
                            <!-- Preview de la imagen -->
                            <div class="preview-imagen mt-2 text-center" id="preview-container" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="preview-img" class="img-fluid rounded shadow-sm" style="max-height: 120px; width: auto; border: 2px solid var(--color-chocolate);">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" id="btn-eliminar-img" style="border-radius: 50%; width: 24px; height: 24px; padding: 0;">
                                        <i class="bi bi-x-lg" style="font-size: 0.7rem;"></i>
                                    </button>
                                </div>
                                <p class="mt-1 mb-0 color-coffee fw-semibold small" id="nombre-archivo"></p>
                            </div>
                            @error('imagen')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botón enviar -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-aplicar bg-chocolate color-sand mb-2">
                            Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newProductoForm');
    const inputs = form.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });

        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = false;

        // Limpiar clases previas
        field.classList.remove('is-valid', 'is-invalid');

        if (field.name === 'precio') {
            const precioRegex = /^\d{1,8}(\.\d{1,2})?$/;
            const precioNum = parseFloat(value);
            isValid = precioRegex.test(value) && !isNaN(precioNum) && precioNum > 0 && precioNum <= 99999999.99;
        } 
        else if(field.name === 'cantidad') {
            const cantidadRegex = /^\d{1,8}(\.\d{1,2})?$/;
            const cantidadNum = parseFloat(value);
            isValid = cantidadRegex.test(value) && !isNaN(cantidadNum) && cantidadNum > 0 && cantidadNum <= 99999999.99;
        }
        else if(field.name === 'unidad'){
            const unidad = field.value;
            isValid = unidad != null && unidad !== '';
        }
        else if (field.name === 'descripcion') {
            isValid = value.length >= 10;
        } 
        else if(field.name === 'categoria'){
            const categoria = field.value;
            isValid = cantidad != null && cantidad !== '';
        }
        else if(field.name === 'tipo'){
            const tipo = field.value;
            isValid = tipo != null && tipo !== '';
        }
        else if(field.name === 'descripcion'){
            isValid = value.length >= 5;
        }
        else {
            isValid = value.length >= 3;
        }

        if (value.length > 0) {
            field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        }

        return isValid;
    }
});
</script>

<script>
const tiposPorCategoria = @json($tipos);

const categoriaActual = "{{ old('categoria', $producto->categoria ?? '') }}";
const tipoActual = "{{ old('tipo', $producto->tipo ?? '') }}";

const selectCategoria = document.getElementById('categoria');
const selectTipo = document.getElementById('tipo');

function actualizarTipos(categoria) {
    selectTipo.innerHTML = '<option value="" disabled selected>Seleccione un tipo...</option>';
    
    if (categoria && tiposPorCategoria[categoria]) {
        tiposPorCategoria[categoria].forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo;
            option.textContent = tipo;
            
            if (tipo === tipoActual) {
                option.selected = true;
            }
            
            selectTipo.appendChild(option);
        });

        selectTipo.disabled = false;
    } else {
        selectTipo.disabled = true;
    }
}

selectCategoria.addEventListener('change', function() {
    actualizarTipos(this.value);
});

document.addEventListener('DOMContentLoaded', function() {
    if (categoriaActual) {
        actualizarTipos(categoriaActual);
    }
});
</script>

<script>
(function() {
    const dropArea = document.querySelector('.drop-area-single');
    const inputImagen = document.getElementById('input-imagen');
    const btnBrowse = document.getElementById('btn-browse');
    const dragText = document.getElementById('drag-text');
    const uploadIcon = document.getElementById('upload-icon');
    const previewContainer = document.getElementById('preview-container');
    const previewImg = document.getElementById('preview-img');
    const btnEliminar = document.getElementById('btn-eliminar-img');
    const nombreArchivo = document.getElementById('nombre-archivo');
    const imagenBase64Input = document.getElementById('imagen-base64');

    // Click en el botón para abrir explorador
    btnBrowse.addEventListener('click', (e) => {
        e.preventDefault();
        inputImagen.click();
    });

    // Click en el área completa
    dropArea.addEventListener('click', (e) => {
        if (e.target !== btnBrowse && e.target !== btnEliminar) {
            inputImagen.click();
        }
    });

    // Cuando se selecciona un archivo
    inputImagen.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            procesarImagen(this.files[0]);
        }
    });

    // Drag and Drop
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.style.borderColor = 'var(--color-chocolate)';
        dropArea.style.backgroundColor = 'var(--color-cream)';
        dragText.textContent = 'Suelta la imagen aquí';
    });

    dropArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropArea.style.borderColor = '';
        dropArea.style.backgroundColor = '';
        dragText.textContent = 'Arrastra y suelta una imagen aquí';
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.style.borderColor = '';
        dropArea.style.backgroundColor = '';
        dragText.textContent = 'Arrastra y suelta una imagen aquí';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            procesarImagen(files[0]);
        }
    });

    // Procesar la imagen
    function procesarImagen(file) {
        // Validar tipo de archivo
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            mostrarError('Por favor selecciona una imagen válida (PNG, JPG, JPEG, WEBP)');
            return;
        }

        // Validar tamaño (2MB máximo)
        const maxSize = 2 * 1024 * 1024; // 2MB en bytes
        if (file.size > maxSize) {
            mostrarError('La imagen no puede ser mayor a 2MB');
            return;
        }

        // Leer el archivo
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Redimensionar si es necesario (máximo 750px de ancho)
                let canvas = document.createElement('canvas');
                let ctx = canvas.getContext('2d');
                let width = img.width;
                let height = img.height;

                if (width > 750) {
                    height = (750 * height) / width;
                    width = 750;
                }

                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);

                // Convertir a base64
                const imagenBase64 = canvas.toDataURL('image/webp', 0.9);
                
                // Guardar en input hidden
                imagenBase64Input.value = imagenBase64;

                // Mostrar preview
                mostrarPreview(imagenBase64, file.name);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Mostrar preview
    function mostrarPreview(src, nombre) {
        previewImg.src = src;
        nombreArchivo.textContent = nombre;
        dropArea.style.display = 'none';
        previewContainer.style.display = 'block';
    }

    // Eliminar imagen
    btnEliminar.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        inputImagen.value = '';
        imagenBase64Input.value = '';
        previewImg.src = '';
        nombreArchivo.textContent = '';
        dropArea.style.display = 'flex';
        previewContainer.style.display = 'none';
    });

    // Restaurar old() en caso de error de validación
    @if(old('imagen_base64'))
        mostrarPreview('{{ old('imagen_base64') }}', 'imagen-previa.webp');
    @endif
})();
</script>
@endsection