<?php

/**** Para mostrar listado con Paginacion ****/
/**
* @url http://nucleo.local/api/lugar-localidads?pagesize=4
* @method GET
* @param extra=1
* @param nombre=Viedma
* @arrayReturn
{
    "pagesize": 4,
    "pages": 1007,
    "total_filtrado": 4027,
    "resultado": [
        {
            "id": 381,
            "nombre": "16 De Julio",
            "regionid": null,
            "departamentoid": 8,
            "municipioid": null,
            "codigo_postal": null
        },
        {
            "id": 640,
            "nombre": "17 De Agosto",
            "regionid": null,
            "departamentoid": 85,
            "municipioid": null,
            "codigo_postal": null
        },
        {
            "id": 809,
            "nombre": "20 De Junio",
            "regionid": null,
            "departamentoid": 129,
            "municipioid": null,
            "codigo_postal": null
        },
        {
            "id": 2084,
            "nombre": "25 De Mayo",
            "regionid": null,
            "departamentoid": 295,
            "municipioid": null,
            "codigo_postal": null
        }
    ]
}
*/

/**** Para mostrar listado sin Paginacion ****/
/**
* @url http://nucleo.local/api/lugar-localidads
* @method GET
* @param extra=1
* @param nombre=Viedma
* @arrayReturn
[
    {
        "id": 381,
        "nombre": "16 De Julio",
        "regionid": null,
        "departamentoid": 8,
        "municipioid": null,
        "codigo_postal": null
    },
    {
        "id": 640,
        "nombre": "17 De Agosto",
        "regionid": null,
        "departamentoid": 85,
        "municipioid": null,
        "codigo_postal": null
    },
    {
        "id": 809,
        "nombre": "20 De Junio",
        "regionid": null,
        "departamentoid": 129,
        "municipioid": null,
        "codigo_postal": null
    },
    {
        "id": 2084,
        "nombre": "25 De Mayo",
        "regionid": null,
        "departamentoid": 295,
        "municipioid": null,
        "codigo_postal": null
    }
]

*/

/*****Para crear****
* @url http://nucleo.local/api/lugar-localidads 
* @method POST
* @param arrayJson
    {
        "nombre":"Milocali",
        "departamentoid":1,
        "codigo_postal":"8200"
    }
**/

/**** Para modificar*****
* @url http://nucleo.local/api/lugar-localidads/{$id} 
* @method PUT
* @param arrayJson
    {
        "nombre":"Milocali",
        "departamentoid":1,
        "codigo_postal":"8200"
    }
**/

/****** Para visualizar*****
* @url http://nucleo.local/api/lugar-localidads/2
* @method GET
* @return arrayJson
{
    "id": 2,
    "nombre": "Villa Lugano",
    "regionid": null,
    "departamentoid": 1,
    "municipioid": null,
    "codigo_postal": null,
    "departamento": "Capital Federal",
    "provincia": "Capital Federal"
}
*/

/****** Para borrar una localidad *****
* @url http://nucleo.local/api/lugar-localidads/{$id} 
* @method Delete
* @return arrayJson
*/
