<?php

return [
    'fatura1' => [
        'id' => 1,
        'total' => 35.0,
        'created_at' => '2025-01-15 10:30:00',
        'estado' => 'paga',
        'metodospagamentos_id' => 2, // MBWay
        'userprofiles_id' => 1,
        'eliminado' => 0,
    ],
    'fatura2' => [
        'id' => 2,
        'total' => 50.0,
        'created_at' => '2025-01-16 14:45:00',
        'estado' => 'paga',
        'metodospagamentos_id' => 1, // Dinheiro
        'userprofiles_id' => 2,
        'eliminado' => 0,
    ],
    'fatura3' => [
        'id' => 3,
        'total' => 150.0,
        'created_at' => '2025-01-17 09:00:00',
        'estado' => 'pendente',
        'metodospagamentos_id' => null,
        'userprofiles_id' => 1,
        'eliminado' => 0,
    ],
];
