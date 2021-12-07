<?php

/*
 * Clase para interactuar con el servicio de solicitudes de lugar (sistemaLugar)
 *
 */

namespace app\components;
use yii\base\Component;
use GuzzleHttp\Client;
use Exception;
use app\components\Help;


//$data = require(\Yii::getAlias('@app').'/components/DummyDataLugar.php');

/**
 * Description of ServicioSolicitudComponent
 *
 * @author cep11p
 */
class DummyServicioLugar extends Component implements IServicioLugar
{
    public $base_uri;
    private $_client;
   
    public function __construct(Client $guzzleClient, $config=[])
    {
        parent::__construct($config);
        $this->_client = $guzzleClient;        
    }
   
    /**
     * Buscar la localidad por id y devuelve los datos de la localidad encontrada
     * @param int $id
     * @return boolean
     */
    public function buscarLocalidadPorId($id)
    {
       
        $resultado['success'] = true;
        $resultado['resultado'] = [];

        return $resultado;
       
    }
    
    /**
     * Si el lugar a agregar no existe, se crea un lugar nuevo.
     * Caso contrario se notificará que existe y para reutilizar el lugar debe setearse un flag $usarLugarEncontrado=true
     * @param type $data
     * @return boolean
     */
    public function crearLugar($data)
    {
        
        return 1;
       
    }
    
    
    public function buscarLugar($param)
    {
        #injectamos el array de datos (mock)
        $data = require(\Yii::getAlias('@app').'/components/DummyDataLugar.php');   
        
        
        $arrayEncontrado = Help::filtrarArrayAsociativo($data, $param);
        
        $response = $coleccion = array(
            "success"=>false,
            "resultado"=>array(),
        );
        if(isset($arrayEncontrado)){
            $response['success'] = true;
            $response['resultado'][] = $arrayEncontrado;
        }
        
        return $response;
       
    }
    
    public function buscarLocalidad($param)
    {
        
        $resultado['success'] = true;
        $resultado['resultado'] = [];

        return $resultado;
       
    }
    
    public function buscarLugarPorId($id)
    {
        #injectamos el array de datos (mock)
        $data = require(\Yii::getAlias('@app').'/components/DummyDataLugar.php');
        
        #preparamos el resultado
        $resultado = array(
            "success"=>FALSE,
            "resultado"=>array()
        );
        
        #filtramos por la clave el array $data
        $lugarEncontrado = Help::filter_by_value($data, 'id', $id); 
        
        if($lugarEncontrado){
            $resultado['success'] = true;
            $resultado['resultado'] = $lugarEncontrado;
        }

        return $resultado;
        
        
       
    }
    
    
    /**
     * crear un string con los criterio de busquedad por ejemplo: localidadid=1&calle=mata negra&altura=123
     * @param array $param
     * @return string
     */
    public function crearCriterioBusquedad($param){
        //funcion armar url con criterio de busquedad
        $criterio = '';
        $primeraVez = true;
        foreach ($param as $key => $value) {
            if($primeraVez){
                $criterio.=$key.'='.$value;
                $primeraVez = false;
            }else{
                $criterio.='&'.$key.'='.$value;
            }            
        }
        
        return $criterio;
    }
    
    
    
    /**
     * 
     * @param int $nro_documento
     * @return int personaid
     */
    public static function buscarPersonaEnRegistralPorNumeroDocuemento($nro_documento)
    {
        $resultado = null;
        
        return $resultado;
    }
   
   
       
}