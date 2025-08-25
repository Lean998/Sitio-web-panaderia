@props(['ruta','imagen', 'categoria'])
<div class="col-12 col-lg-4 col-md-6 mb-4 w-100" title="{{ $categoria }}">
    <a href="{{ $ruta }}" class="text-decoration-none">
        <div class="card shadow-sm border-0 h-100">
            <img src="{{ $imagen }}" class="card-img-top" alt="{{ $categoria }}" style="object-fit: cover; height: 300px;">
            <div class="card-body bg-coffee text-center rounded-bottom">
                <p class="m-0 color-sand fs-h3 fw-semibold">{{ $categoria }}</p>
            </div>
        </div>
    </a>
</div>
