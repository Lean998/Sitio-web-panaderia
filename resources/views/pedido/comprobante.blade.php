<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        /* ===== PALETA ===== */
        :root {
            --color-chocolate: #5C3A21;
            --color-coffee: #A47148;
            --color-caramel: #d4a373;
            --color-cream: #f7f3ef;
            --color-sand: #e6d5c3;
        }

        /* ===== RESETEO ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "DejaVu Sans", sans-serif;
        }

        body {
            background-color: var(--color-cream);
            color: #333;
            padding: 20px;
            font-size: 13px;
        }

        /* ===== CARD ===== */
        .card {
            border: 1px solid var(--color-sand);
            border-radius: 6px;
            overflow: hidden;
            background-color: white;
        }

        .card-header {
            background-color: var(--color-chocolate);
            color: white;
            padding: 10px 16px;
        }

        .card-header h5 {
            margin: 0;
            font-size: 16px;
        }

        /* ===== TABLA ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--color-sand);
            color: var(--color-chocolate);
        }

        tbody tr:nth-child(even) {
            background-color: #f6f1ec;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        th, td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--color-sand);
        }

        th {
            text-align: left;
            font-weight: bold;
            font-size: 13px;
        }

        td {
            font-size: 13px;
        }

        .text-end { text-align: right; }
        .text-center { text-align: center; }

        .color-chocolate { color: var(--color-chocolate); }
        .text-danger { color: #b02a37; }

        tfoot tr td {
            background-color: var(--color-cream);
            font-weight: bold;
        }

        tfoot tr:last-child td {
            border-top: 2px solid var(--color-chocolate);
            background-color: var(--color-caramel);
            color: var(--color-chocolate);
            font-size: 15px;
        }

        /* ===== ENCABEZADO ===== */
        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        .header h2 {
            color: var(--color-chocolate);
            margin-bottom: 5px;
        }

        .header p {
            color: var(--color-coffee);
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="color: var(--color-coffee)">El Funito - Panaderia</h1>
        <h2>Comprobante de Pago</h2>
    </div>

    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-around;">
            <h5>Detalle del Pedido</h5>
            <p style="color: var(--color-cream)">Número de pedido: {{ $pedido->codigo_pedido }} <br> Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }} </p>
        </div>

        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->items as $item)
                        <tr>
                            <td><strong>{{ $item->nombre_producto }}</strong></td>
                            <td class="text-center">
                                {{ number_format($item->cantidad, 2, ',', '.') }}
                                {{ config('unidades.unidadMedida.'.$item->unidad_venta) }}
                            </td>
                            <td class="text-end">
                                ${{ number_format($item->precio_unitario, 2, ',', '.') }}
                            </td>
                            <td class="text-end">
                                ${{ number_format($item->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Subtotal:</td>
                        <td class="text-end">
                            ${{ number_format($pedido->monto_total, 2, ',', '.') }}
                        </td>
                    </tr>

                    @if($pedido->monto_final > $pedido->monto_total)
                        <tr>
                            <td colspan="3" class="text-end text-danger">
                                Recargo ({{ ucfirst($pedido->medio_pago) }}):
                            </td>
                            <td class="text-end text-danger">
                                ${{ number_format($pedido->monto_final - $pedido->monto_total, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="3" class="text-end">TOTAL:</td>
                        <td class="text-end color-chocolate">
                            ${{ number_format($pedido->monto_final, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>