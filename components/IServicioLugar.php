<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 *
 * @author carlos
 */
interface IServicioLugar {
   
    /**
     * Buscar la localidad por id y devuelve los datos de la localidad encontrada
     * @param int $id
     * @return boolean
     */
    public function buscarLocalidadPorId($id);
    
    /**
     * Si el lugar a agregar no existe, se crea un lugar nuevo.
     * Caso contrario se notificará que existe y para reutilizar el lugar debe setearse un flag $usarLugarEncontrado=true
     * @param type $data
     * @return boolean
     */
    public function crearLugar($data);
    
    
    public function buscarLugar($param);
    
    public function buscarProvincia($param);
    public function buscarDepartamento($param);
    
    public function buscarLugarPorId($id);

    public function buscarLocalidad($param);
    public function crearLocalidad($data);
    public function modificarLocalidad($data);

    public function buscarLocalidadExtra($param);
    public function crearLocalidadExtra($param);
    public function borrarLocalidadExtra($param);
    
    
    /**
     * crear un string con los criterio de busquedad por ejemplo: localidadid=1&calle=mata negra&altura=123
     * @param array $param
     * @return string
     */
    public function crearCriterioBusquedad($param);
    
    
    
    /**
     * 
     * @param int $nro_documento
     * @return int personaid
     */
    public static function buscarPersonaEnRegistralPorNumeroDocuemento($nro_documento);
    
}
