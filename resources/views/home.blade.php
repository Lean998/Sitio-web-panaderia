@extends('layouts.app')

@section('title', 'Inicio')

    
@section('content')
    <div id="carrousel" class="carousel slide">
        <div class="carousel-inner" style="height: auto;">
            <div class="carousel-item active bg-banner" style="background-image: url('{{ asset('images/homeBackground.webP') }}')">
            
            </div>
        </div>
    </div>

    <div class="row py-5 bg-chocolate mx-0 color-sand fs-body fw-semibold justify-content-center">
        <p class="text-start text-break banner-text fs-body fw-semibold">
            Cada uno de nuestros productos se prepara con dedicación y cariño por sus propios dueños. Fresco, casero y lleno de sabor, pensado para acompañarte en todos los momentos del día.
        </p>
    </div>

    <section class="container py-5" id="productos">
    
    <div class="row mt-5 pt-5">
        <div class="col-12">
            <h2 class="fs-h1 fw-bold color-coffee">Productos</h2>
        </div>
    </div>

    <div class="row mb-5" id="categorias"> 
        <div class="col-12 col-md-6 col-lg-4">
            <x-categoria-card 
                ruta="{{ route('productos', ['categoria' => 'Panaderia']) }}"
                imagen="{{ asset('images/categorias/panaderia.webP') }}" 
                categoria="Panadería" />
        </div>
        <div class="col-12 col-md-6 col-lg-4 ">
            <x-categoria-card 
                ruta="{{ route('productos', ['categoria' => 'Pasteleria']) }}"
                imagen="{{ asset('images/categorias/reposteria.webP') }}" 
                categoria="Pastelería" />
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-categoria-card 
                ruta="{{ route('productos', ['categoria' => 'Salados']) }}"
                imagen="{{ asset('images/categorias/salados.webP') }}" 
                categoria="Salados" />
        </div>
    </div>

    <div class="row mt-5 pt-5">
        <div class="col-12">
            <h2 class="fs-h1 fw-bold color-coffee">Sucursal</h2>
        </div>
    </div>

    <!-- Mapa -->
    <div class="row mb-5">
        <div class="col-12">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d701.3207034668537!2d-66.3119090459805!3d-33.264861755759824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95d43f0049231ddb%3A0xffb7711ab34ceff!2sEL%20FUNITO!5e0!3m2!1ses!2sar!4v1755826728633!5m2!1ses!2sar" 
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

@endsection

