@extends('layouts.admin.admin')

@section('title', 'Crear Empleado')

@push('styles')
    @vite (['resources/css/inputsYBotones.css'])
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-chocolate color-cream">
                    <h4 class="mb-0">Crear Nuevo Empleado</h4>
                </div>
                <div class="card-body bg-caramel color-espresso" >
                    <form action="{{ route('admin.usuarios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" name="name" id="name" class="form-control input-texto  @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" name="email" id="email" class="input-texto form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password" class="input-texto form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="input-texto form-control">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-chocolate" style="color:var(--color-cream);">
                                Crear Empleado
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-coffee" style="color:var(--color-cream);">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection