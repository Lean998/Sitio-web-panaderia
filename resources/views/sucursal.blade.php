@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-3">
    <div class="card shadow-lg">
        <div class="row g-0">
            <div class="col-md-6 d-flex bg-sand border-chocolate rounded-start shadow-inherit flex-column justify-content-center p-4">
                <h2 class="mb-3 color-chocolate fw-bold">Nuestra Sucursal</h2>
                <p class="mb-2"><strong class="color-chocolate">Dirección:</strong> Clavel Del Aire, D5700 San Luis</p>
                <p class="mb-2"><strong class="color-chocolate">Teléfono:</strong> 2664-235465</p>
                <p class="mb-2"><strong class="color-chocolate">Email:</strong> elfunitopanaderia@gmail.com</p>
                <p class="mb-4"><strong class="color-chocolate">Horario:</strong> Martes a Sábado de 08:00 a 14:00 y de 17:00 a 21:00 <br>Domingo de 08:00 a 14:00</p>
                <ul class="list-group mb-4">
                    <li class="list-group-item bg-chocolate color-sand"><strong>Servicios</strong></li>
                    <li class="list-group-item bg-chocolate color-sand">Venta de pan dulce y salado</li>
                    <li class="list-group-item bg-chocolate color-sand">Pasteleria</li>
                    <li class="list-group-item bg-chocolate color-sand">Pedidos especiales</li>
                </ul>
                <div class="mb-4">
                    <strong class="color-chocolate">Redes Sociales:</strong>
                    <div>
                        <a href="#" class="me-2"><i class="bi bi-facebook"></i> Facebook</a>
                        <a href="#" class="me-2"><i class="bi bi-instagram"></i> Instagram</a>
                        <a href="#"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-stretch border-chocolate rounded-end shadow-inherit">
                <div class="ratio ratio-4x3 mb-3 w-100 h-100" style="min-height: 100%;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!4v1759168339119!6m8!1m7!1s5eLRPZn2KkuTHaQl8ZiyaA!2m2!1d-33.26477967864727!2d-66.31184180965309!3f348.31049425075093!4f-2.347261375041086!5f0.7820865974627469"   
                        style="border:0; width:100%; height:100%; display:block;"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection