<?php

/**** Para mostrar listado ****/
/**
* @url http://nucleo.local/api/inventario-marcas
* @method GET
* @arrayReturn SIN PAGINACION
    [
        {
            "id": 230,
            "nombre": "1 2 3 listo"
        },
        {
            "id": 338,
            "nombre": "1888"
        },
        {
            "id": 68,
            "nombre": "3 arroyos"
        },
        {
            "id": 256,
            "nombre": "3arroyos"
        }
    ]

* @arrayReturn CON PAGINACION
{
    "pagesize": 1000,
    "pages": 1,
    "total_filtrado": 362,
    "resultado": [
        {
            "id": 230,
            "nombre": "1 2 3 listo"
        },
        {
            "id": 338,
            "nombre": "1888"
        },
        {
            "id": 68,
            "nombre": "3 arroyos"
        },
        {
            "id": 256,
            "nombre": "3arroyos"
        }
    ]
}
*/

/** Para crear
* @url http://nucleo.local/api/inventario-marcas
* @method POST
* @param arrayJson
 * {
        "nombre": "nuevaMarca"
    }
**/

/** Para modificar
* @url http://nucleo.local/api/inventario-marcas/{$id} 
* @method PUT
* @param arrayJson
 * {
        "nombre": "MarcaModificada"
    }
**/

/** Para visualizar
* @url http://nucleo.local/api/inventario-marcas/{$id} 
* @method GET
* @return arrayJson
 * {
    "id": 1,
    "nombre": "Arcor"
    }
*/

/** Para borrar una localidad
* @url http://nucleo.local/api/inventario-marcas/set-activo/{$id} 
* @method PUT
* @param json
    {
        "activo" : 0
    }
* @return arrayJson
    {
        "message": "Se borra una Marca"
    }
*/
