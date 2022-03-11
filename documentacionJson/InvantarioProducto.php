<?php

/** Para mostrar listado 
 * Listado de productos con paginacion dinamica 
 * @url http://nucleo.local/api/inventario-productos?pagesize=10
 * @method GET
  * @arrayReturn Sin Paginacion
    [
        {
            "id": 1,
            "nombre": "Aceite de girasol",
            "codigo": "A300",
            "unidad_valor": 1.5,
            "unidad_medidaid": 3,
            "marcaid": 1,
            "categoriaid": 1,
            "activo": 1,
            "marca": "Arcor",
            "unidad_medida": "lt",
            "producto": "Aceite de girasol, 1.5lt (Arcor)",
            "categoria": "Alimento"
        },
        {
            "id": 2,
            "nombre": "Aceite de girasol",
            "codigo": "A301",
            "unidad_valor": 900,
            "unidad_medidaid": 4,
            "marcaid": 1,
            "categoriaid": 1,
            "activo": 1,
            "marca": "Arcor",
            "unidad_medida": "ml",
            "producto": "Aceite de girasol, 900ml (Arcor)",
            "categoria": "Alimento"
        }
    ]
 * @arrayReturn Con Paginacion
    {
        "pagesize": 500,
        "pages": 1,
        "total_filtrado": 9,
        "resultado": [
            {
                "id": 1,
                "nombre": "Aceite de girasol",
                "codigo": "A300",
                "unidad_valor": 1.5,
                "unidad_medidaid": 3,
                "marcaid": 1,
                "categoriaid": 1,
                "activo": 1,
                "marca": "Arcor",
                "unidad_medida": "lt",
                "producto": "Aceite de girasol, 1.5lt (Arcor)",
                "categoria": "Alimento"
            },
            {
                "id": 2,
                "nombre": "Aceite de girasol",
                "codigo": "A301",
                "unidad_valor": 900,
                "unidad_medidaid": 4,
                "marcaid": 1,
                "categoriaid": 1,
                "activo": 1,
                "marca": "Arcor",
                "unidad_medida": "ml",
                "producto": "Aceite de girasol, 900ml (Arcor)",
                "categoria": "Alimento"
            }
    }
*/

/** Para crear
* @url http://nucleo.local/api/inventario-productos 
* @method POST
* @param arrayJson
    {
        "nombre": "Aceite de girasol",
        "unidad_valor": "1,5",
        "unidad_medidaid": 3,
        "marcaid": 1,
        "categoriaid": 1,
        "unidad_medida": "lt",
    }
**/

/** Para modificar
* @url http://nucleo.local/api/inventario-productos/{$id} 
* @method PUT
* @param arrayJson
    {
        "nombre": "Aceite de girasol",
        "codigo": "A300",
        "unidad_valor": "1,5",
        "unidad_medidaid": 3,
        "marcaid": 1,
        "categoriaid": 1,
        "marca": "Arcor",
        "unidad_medida": "lt",
        "producto": "Aceite de girasol, 1,5lt (Arcor)"
    }
**/

/** Para visualizar
* @url http://nucleo.local/api/inventario-productos/{$id} 
* @method GET
* @return arrayJson
    {
        "id": 1,
        "nombre": "Aceite de girasol",
        "codigo": "A300",
        "unidad_valor": "1,5",
        "unidad_medidaid": 3,
        "marcaid": 1,
        "categoriaid": 1,
        "marca": "Arcor",
        "unidad_medida": "lt",
        "producto": "Aceite de girasol, 1,5lt (Arcor)"
    }
*/

/** Para borrar producto logicamente
* @url http://nucleo.local/api/inventario-productos/set-activo/{$id} 
* @method PUT
* @return arrayJson
    {
	    "activo": false
    }
*/
