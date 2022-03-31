<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'JWT_SECRET' => 'ZqCeBt}531',
    'REGISTRAL_JWT_SECRET' => 'ZqCeBt}531',
    'servicioRegistral'=> getenv('SERVICIO_REGISTRAL')?getenv('SERVICIO_REGISTRAL'):'app\components\DummyServicioRegistral',
    'servicioLugar'=> getenv('SERVICIO_LUGAR')?getenv('SERVICIO_LUGAR'):'app\components\DummyServicioLugar',
    'servicioInventario'=> getenv('SERVICIO_INVENTARIO')?getenv('SERVICIO_INVENTARIO'):'app\components\DummyServicioInventario',
];
