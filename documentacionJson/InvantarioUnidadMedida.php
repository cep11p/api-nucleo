<?php

/**** Para mostrar listado ****/
/**
* @url http://nucleo.local/api/inventario-unidad-medidas
* @method GET
* @arrayReturn
    [
        {
            "id": 1,
            "nombre": "Kilogramo",
            "simbolo": "kg"
        },
        {
            "id": 2,
            "nombre": "Gramo",
            "simbolo": "gr"
        },
        {
            "id": 3,
            "nombre": "Litro",
            "simbolo": "lt"
        }
    ]
* @arrayReturn SIN PAGINACION
{
    "pagesize": 3,
    "pages": 121,
    "total_filtrado": 361,
    "resultado": [
        {
            "id": 1,
            "nombre": "Kilogramo",
            "simbolo": "kg"
        },
        {
            "id": 2,
            "nombre": "Gramo",
            "simbolo": "gr"
        },
        {
            "id": 3,
            "nombre": "Litro",
            "simbolo": "lt"
        }
    ]
}
*/

/** Para crear
* @url http://nucleo.local/api/inventario-unidad-medidas 
* @method POST
* @param arrayJson
{
    "nombre": "Centimetros cúbicos",
    "simbolo": "cm3"
}
**/

/** Para modificar
* @url http://nucleo.local/api/inventario-unidad-medidas/{$id} 
* @method PUT
* @param arrayJson
{
    "nombre": "Centimetros cúbicos modificar",
    "simbolo": "cm3"
}
**/

/** Para visualizar
* @url http://nucleo.local/api/inventario-unidad-medidas/{$id} 
* @method GET
* @return arrayJson
{
    "id": 6,
    "nombre": "Centimetros cúbicos modificar",
    "simbolo": "cm3"
}
*/

/** Se habilita o se inhabilita. Borrado logico
* @url http://nucleo.local/api/inventario-unidad-medidas/set-activo/{$id} 
* @method PUT
* @param activo interger or bool opcional
* @return arrayJson
    {
	    "activo": 0
    }
*/
