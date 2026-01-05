<?php

return [
    'marcacao1' => [
        'id' => 1,
        'data' => '2025-02-15',
        'horainicio' => '10:00:00',
        'horafim' => '10:30:00',
        'diagnostico' => null,
        'estado' => 'pendente',
        'servicos_id' => 1, // Consulta Geral
        'animais_id' => 1, // Rex
        'userprofiles_id' => 1,
        'eliminado' => 0,
        'created_at' => '2025-01-05 09:00:00',
        'updated_at' => '2025-01-05 09:00:00',
    ],
    'marcacao2' => [
        'id' => 2,
        'data' => '2025-02-16',
        'horainicio' => '14:00:00',
        'horafim' => '14:30:00',
        'diagnostico' => 'Animal saudável, vacinação completa',
        'estado' => 'realizada',
        'servicos_id' => 2, // Vacinação
        'animais_id' => 2, // Mimi
        'userprofiles_id' => 2,
        'eliminado' => 0,
        'created_at' => '2025-01-04 10:00:00',
        'updated_at' => '2025-01-10 14:30:00',
    ],
    'marcacao3' => [
        'id' => 3,
        'data' => '2025-01-10',
        'horainicio' => '11:00:00',
        'horafim' => '11:30:00',
        'diagnostico' => null,
        'estado' => 'cancelada',
        'servicos_id' => 1, // Consulta Geral
        'animais_id' => 3, // Max
        'userprofiles_id' => 1,
        'eliminado' => 0,
        'created_at' => '2025-01-03 10:00:00',
        'updated_at' => '2025-01-09 10:00:00',
    ],
];
