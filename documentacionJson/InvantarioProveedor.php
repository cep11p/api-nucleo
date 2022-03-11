<?php

/**** Para mostrar listado ****/
/**
* @url http://nucleo.local/api/inventario-proveedor
* @method GET
* @arrayReturn
* @arrayReturn
    [
        {
            "id": 2,
            "nombre": "provedor1",
            "cuit": "20368498689"
        },
        {
            "id": 3,
            "nombre": "provedor2",
            "cuit": "20368498689"
        },
        {
            "id": 4,
            "nombre": "provedor3",
            "cuit": "20368498689"
        }
    ]
* @arrayReturn SIN PAGINACION
{
    "pagesize": 3,
    "pages": 121,
    "total_filtrado": 361,
    "resultado": [
        {
            "id": 2,
            "nombre": "provedor1",
            "cuit": "20368498689"
        },
        {
            "id": 3,
            "nombre": "provedor2",
            "cuit": "20368498689"
        },
        {
            "id": 4,
            "nombre": "provedor3",
            "cuit": "20368498689"
        }
    ]
}
*/

/** Para crear
* @url http://nucleo.local/api/inventario-proveedor 
* @method POST
* @param arrayJson
    {
        "nombre": "provedor3",
        "cuit": "20368498689"
    }
* @return json
    {
        "nombre": "Provedor3",
        "cuit": "20368498689"
    }
**/

/** Para modificar
* @url http://nucleo.local/api/inventario-proveedor/{$id} 
* @method PUT
* @param arrayJson
    {
        "nombre": "provedor3",
        "cuit": "20368498689"
    }
* @return json
{
    "nombre": "Provedor modificado",
    "cuit": "20368498321"
}
**/

/** Para visualizar
* @url http://nucleo.local/api/inventario-proveedor/{$id} 
* @method GET
* @return arrayJson
    {
        "id": 3,
        "nombre": "Provedor modificado",
        "cuit": "20368498321"
    }
*/

/** Se habilita o se inhabilita. Borrado logico
* @url http://nucleo.local/api/inventario-proveedor/set-activo/{$id} 
* @method PUT
* @param activo interger or bool opcional
* @return arrayJson
    {
	    "activo": 0
    }
*/