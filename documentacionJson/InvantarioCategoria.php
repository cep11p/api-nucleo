<?php

/** Para mostrar listado
* @url http://inventario.local/api/inventario-categorias
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
        }
    ]
* @arrayReturn SIN PAGINACION
{
    "pagesize": 3,
    "pages": 121,
    "total_filtrado": 361,
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
        }
    ]
}
*/

/** Para crear
* @url http://inventario.local/api/inventario-categorias 
* @method POST
* @param arrayJson
{    
	"nombre" : "micategoria"        
}

* @return
{
    "message": "Se registra una nueva Categoria",
    "id": 4
}
**/

/** Para modificar
* @url http://inventario.local/api/inventario-categorias/{$id} 
* @method PUT
* @param arrayJson
{    
	"nombre" : "micategoria"        
}

* @return
{
    "message": "Se registra una nueva Categoria",
    "id": 4
}
**/

/** Para visualizar
* @url http://inventario.local/api/inventario-categorias/{$id} 
* @method GET
* @return arrayJson
{
    "id": 1,
    "nombre": "Alimento"
}
*/

/** Se habilita o se inhabilita. Borrado logico
* @url http://inventario.local/api/inventario-categorias/set-activo/{$id} 
* @method PUT
* @param activo interger or bool opcional
* @return arrayJson
    {
	    "activo": 0
    }
*/
