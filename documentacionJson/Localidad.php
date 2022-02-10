<?php

/**** Para mostrar listado ****/
/**
* @url http://lugar.local/api/localidads?
* @method GET
* @param pagesize = 4 si el valor es igual a 0 o null la paginacion no se realiza
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

/*****Para crear****
* @url http://lugar.local/api/localidads 
* @method POST
* @param arrayJson
    {
        "nombre":"Milocali",
        "departamentoid":1,
        "codigo_postal":"8200"
    }
**/

/**** Para modificar*****
* @url http://lugar.local/api/localidads/{$id} 
* @method PUT
* @param arrayJson
    {
        "nombre":"Milocali",
        "departamentoid":1,
        "codigo_postal":"8200"
    }
**/

/****** Para visualizar*****
* @url http://lugar.local/api/localidads/{$id} 
* @method GET
* @return arrayJson
*/

/****** Para borrar una localidad *****
* @url http://lugar.local/api/localidads/{$id} 
* @method Delete
* @return arrayJson
*/
