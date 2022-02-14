<?php
/**** habilitar o deshabilitar un Usuarios*****
* @url http://nucleo.local/api/inventario-asignacion/baja/47 
* @method PUT
* @param arrayJson
* @param['baja] = true or false
{
    "baja":true,
	"descripcion_baja":"Esto es una descripcion de baja de usuario"
}
**/

/**** Crear Asignaciones a Usuarios*****
* @url http://nucleo.local/api/inventario-asignacion/crear-asignacion
* @method POST
* @param arrayJson
{
	"usuarioid": 15,
	"lista_permiso":[
		{"name":"cuenta_bps_importar"},
		{"name":"cuenta_ver"}
    ]
}

/**** Listar Asignaciones a Usuarios*****
* @url http://nucleo.local/api/inventario-asignacion/listar-asignacion/14
* @method GET
* @param return
[
    {
        "tipo_convenio": "8180",
        "tipo_convenioid": "1",
        "lista_permiso": [
            "prestacion_borrar",
            "cuenta_saldo_crear",
            "cuenta_saldo_crear",
            "persona_crear",
            "cuenta_saldo_crear",
            "persona_crear",
            "prestacion_borrar"
        ],
        "usuarioid": 31
    },
    {
        "tipo_convenio": "8277",
        "tipo_convenioid": "2",
        "lista_permiso": [
            "cuenta_saldo_crear",
            "persona_crear"
        ],
        "usuarioid": 31
    }
]

/**** Borrar Asignaciones a Usuarios*****
* @url http://nucleo.local/api/inventario-asignacion/borrar-asignacion 
* @method POST
* @param arrayJson
{
    "lista_permiso": [
        "cuenta_saldo_exportar",
        "cuenta_ver"
    ],
    "usuarioid": 2
}
**/