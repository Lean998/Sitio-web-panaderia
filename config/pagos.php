<?php

return [

    // Configuración de recargos según medio de pago
    'recargos' => [
        'efectivo' => 0.00,
        'transferencia' => 0.00,
        'credito' => 10,
        'debito' => 5,
    ],
    
    'descripciones' => [
        'efectivo' => 'Sin recargo',
        'transferencia' => 'Sin recargo',
        'debito' => '5% de recargo',
        'credito' => '10% de recargo',
    ],
];
