@extends('layouts.app')
@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Información del pedido --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-chocolate text-white">
                    <h4 class="mb-0 color-cream"><i class="bi bi-basket"></i> Resumen del Pedido</h4>
                </div>
                <div class="card-body bg-sand">
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Código de pedido:</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span class="badge bg-caramel color-espresso fs-6">{{ $pedido->codigo_retiro }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Cliente:</strong>
                        </div>
                        <div class="col-6 color-espresso text-end">
                            {{ $pedido->nombre }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Subtotal:</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span class="fs-5 fw-bold color-chocolate">
                                ${{ number_format($pedido->monto_total, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario de pago --}}
            <div class="card shadow-sm">
                <div class="card-header bg-chocolate text-white">
                    <h4 class="mb-0 color-cream"><i class="bi bi-credit-card"></i> Método de Pago</h4>
                </div>
                <div class="card-body bg-sand">
                    
                    <form action="{{ route('pedido.procesar-pago', $pedido->id) }}" method="POST" id="formPago">
                        @csrf

                        <div class="alert bg-espresso color-amber">
                            <i class="bi bi-info-circle"></i>
                            <strong>Nota:</strong> Los pagos con tarjeta de débito o crédito tienen un recargo adicional.
                        </div>

                        {{-- Verificar que existan recargos --}}
                        @if(isset($recargos) && count($recargos) > 0)
                            {{-- Opciones de pago --}}
                            <div class="row g-3">
                                @foreach($recargos as $medio => $info)
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="medio_pago" id="pago_{{ $medio }}" value="{{ $medio }}" data-porcentaje="{{ $info['porcentaje'] }}" data-recargo="{{ $info['recargo'] }}" data-total="{{ $info['total'] }}" required>
                                        <label class="btn btn-cream border-chocolate w-100 p-3 medio-pago-card" for="pago_{{ $medio }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1 color-espresso">{{ $info['nombre'] }}</h5>
                                                    <small class="text-muted">{{ $info['descripcion'] }}</small>
                                                </div>
                                                <div class="text-end">
                                                    @if($info['recargo'] > 0)
                                                        <small class="text-danger d-block">
                                                            +${{ number_format($info['recargo'], 2, ',', '.') }}
                                                        </small>
                                                    @endif
                                                    <strong class="fs-5 color-chocolate">
                                                        ${{ number_format($info['total'], 2, ',', '.') }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                                Error al cargar los métodos de pago. Por favor, intenta nuevamente.
                            </div>
                        @endif

                        @error('medio_pago')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        {{-- Resumen del pago --}}
                        <div class="card mt-4 border-chocolate" id="resumenPago" style="display: none;">
                            <div class="card-body bg-sand color-espresso">
                                <h5 class="card-title">Resumen del Pago</h5>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-8">Subtotal:</div>
                                    <div class="col-4 text-end" id="subtotal">
                                        ${{ number_format($pedido->monto_total, 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="row mb-2" id="recargoRow" style="display: none;">
                                    <div class="col-8">
                                        Recargo (<span id="porcentajeRecargo">0</span>%):
                                    </div>
                                    <div class="col-4 text-end text-danger" id="montoRecargo">
                                        $0,00
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-8">
                                        <strong class="fs-5">Total a Pagar:</strong>
                                    </div>
                                    <div class="col-4 text-end">
                                        <strong class="fs-4 color-chocolate" id="totalFinal">
                                            ${{ number_format($pedido->monto_total, 2, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-lg btn-chocolate text-white" id="btnConfirmar" disabled>
                                <i class="bi bi-check-circle"></i> Confirmar Pago
                            </button>
                            <a href="{{ route('carrito') }}" class="btn btn-coffee">
                                <i class="bi bi-arrow-left"></i> Volver al Carrito
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarPago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-espresso color-cream border-0">
                <h5 class="modal-title">Confirmar Pedido</h5>
                <button type="button" class="btn-close bg-sand" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-coffee color-espresso fw-semibold border-0">
                <p id="confirmAmount" class="fs-5 mb-0"></p>
            </div>
            <div class="modal-footer bg-coffee border-0">
                <button type="button" class="btn btn-espresso" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-chocolate" id="confirmPayBtn">Confirmar Pago</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPago');
    const radios = document.querySelectorAll('input[name="medio_pago"]');
    const resumenPago = document.getElementById('resumenPago');
    const recargoRow = document.getElementById('recargoRow');
    const porcentajeRecargo = document.getElementById('porcentajeRecargo');
    const montoRecargo = document.getElementById('montoRecargo');
    const totalFinal = document.getElementById('totalFinal');
    const btnConfirmar = document.getElementById('btnConfirmar');
    const modal = new bootstrap.Modal(document.getElementById('confirmarPago'));
    const confirmAmount = document.getElementById('confirmAmount');
    const confirmPayBtn = document.getElementById('confirmPayBtn');

    let selectedTotal = 0;

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const porcentaje = parseFloat(this.dataset.porcentaje);
            const recargo = parseFloat(this.dataset.recargo);
            const total = parseFloat(this.dataset.total);
            selectedTotal = total;

            // Mostrar resumen
            resumenPago.style.display = 'block';
            btnConfirmar.disabled = false;

            // Mostrar/ocultar fila de recargo
            if (recargo > 0) {
                recargoRow.style.display = 'flex';
                porcentajeRecargo.textContent = porcentaje;
                montoRecargo.textContent = '$' + recargo.toFixed(2).replace('.', ',');
            } else {
                recargoRow.style.display = 'none';
            }

            // Actualizar total
            totalFinal.textContent = '$' + total.toFixed(2).replace('.', ',');

            // Efecto visual
            document.querySelectorAll('.medio-pago-card').forEach(label => {
                label.classList.remove('border-success');
            });
            this.nextElementSibling.classList.add('border-success');
        });
    });

    // Al hacer clic en confirmar, mostrar modal
    btnConfirmar.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        confirmAmount.textContent = `Deberás pagar: $${selectedTotal.toFixed(2).replace('.', ',')}`;
        modal.show();
    });

    // Al confirmar en el modal, enviar formulario
    confirmPayBtn.addEventListener('click', function() {
        modal.hide();
        form.submit();
    });
});
</script>

<style>
.medio-pago-card {
    transition: all 0.3s ease;
    border-width: 2px !important;
}

.medio-pago-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-check:checked + .medio-pago-card {
    border-color: var(--color-chocolate) !important;
    background-color: var(--color-cream) !important;
}
</style>
@endsection