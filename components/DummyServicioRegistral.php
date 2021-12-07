<?php

/*
 * Clase para interactuar con el servicio de solicitudes de la oficina judicial
 *
 */

namespace app\components;
use yii\base\Component;
use GuzzleHttp\Client;
use Exception;


/**
 * Description of ServicioSolicitudComponent
 *
 * @author mboisselier
 */
class DummyServicioRegistral extends Component implements IServicioRegistral
{
    public $base_uri;
    private $_client;
   
    public function __construct(Client $guzzleClient, $config=[])
    {
        parent::__construct($config);
        $this->_client = $guzzleClient;
    }
   
    /**
     *
     * @param string $legajo
     * @param string $organismo
     * @param string $fiscalAnterior
     * @param string $fiscalActual
     * @return string $id Es el id de la persona
     */
    public function crearPersona($data)
    {
        return 100;
       
    }
    
    public function actualizarPersona($data)
    {        
        return 100;
       
    }
    
    public function buscarPersonaPorNroDocumento($nrodocumento)
    {
        #injectamos el array de datos (mock)
        $data = require(\Yii::getAlias('@app').'/components/DataPersona.php');
        
        #preparamos el resultado
        $resultado = array(
            "estado"=>FALSE,
            "resultado"=>array()
        );
        
        #filtramos por la clave el array $data
        $modelEncontrado = Help::filter_by_value($data, 'nro_documento', $nrodocumento);        
        
        if($modelEncontrado){
            $resultado['estado'] = true;
            $resultado['resultado'] = $modelEncontrado;
        }
        
        return $resultado;
        
       
    }

    public function buscarPersonaPorCuil($cuil)
    {
        return ['servicio dummy'];
       
    }
    
    public function buscarPersonaPorId($id)
    {
        #injectamos el array de datos (mock)
        $data = require(\Yii::getAlias('@app').'/components/DataPersona.php');
        
        #preparamos el resultado
        $resultado = array(
            "estado"=>FALSE,
            "resultado"=>array()
        );
        
        #filtramos por la clave el array $data
        $modelEncontrado = Help::filter_by_value($data, 'id', $id);        
        
        if($modelEncontrado){
            $resultado['estado'] = true;
            $resultado['resultado'] = $modelEncontrado;
        }
        
        return $resultado;
    }
    
    
    /**
     * Busca el nucleo que tenga el mismo hogarid y con el nombre = 'Predeterminado'
     * @param int $hogarid
     * @param string $nombre
     * @return obtenemos una respuesta de registral
     */
    public function buscarNucleo($param)
    {
        #injectamos el array de datos (mock)
        $data = require(\Yii::getAlias('@app').'/components/DataPersona.php');
        
        #preparamos el resultado
        $resultado = array(
            "estado"=>FALSE,
            "resultado"=>array()
        );
        
        #filtramos por la clave el array $data
        $modelEncontrado = Help::filter_by_value($data, 'id', $param['id']);        
        
        if($modelEncontrado){
            $resultado['estado'] = true;
            $resultado['resultado'] = $modelEncontrado;
        }
        
        return $resultado;
       
    }
    
    public function buscarNucleoPorId($id)
    {
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/nucleos?id='.$id, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    public function buscarNivelEducativoPorId($id)
    {
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/nivel-educativo?id='.$id, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    

    public function buscarHogar($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/hogar?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    public function buscarPersona($param)
    {
        #injectamos el array de datos (mock)
        $persona_data = require(\Yii::getAlias('@app').'/components/DataPersona.php');
        
        #set page
        $page = 0;
        if(isset($param['page']) && is_numeric($param['page'])){
            $page = $param['page'];
        }
        
        #preparamos el resultado
        $resultado = array(
            "estado"=>FALSE,
            "resultado"=>array()
        );
        
        $persona_data_paginado = array_chunk($persona_data, 20);
        
        $resultado['estado'] = true;
        $resultado['pagesize'] = 20;
        $resultado['total_filtrado'] = count($persona_data);
        $resultado['pages'] = ceil($resultado['total_filtrado']/20)-1;
        $resultado['resultado'] = $persona_data_paginado[$page];
        return $resultado;
        
        
       
    }
    
    /**
     * Se devuelve una coleccion de Sexos.
     * NOTA!... Hay que tener en cuenta que el SexoController del sistema Registral no soporta filtrado, es decir que los parametros enviados va a ser inrrelevantes
     * @param array $param
     * @return boolean
     */
    public function buscarSexo($param)
    {
        
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
//                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/sexo?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    /**
     * Se devuelve una coleccion de Genero.
     * NOTA!... Hay que tener en cuenta que el GeneroController del sistema Registral no soporta filtrado, es decir que los parametros enviados va a ser inrrelevantes
     * @param array $param
     * @return boolean
     */
    public function buscarGenero($param)
    {
        
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
//                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/genero?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    /**
     * Se devuelve una coleccion de Estados Civiles.
     * NOTA!... Hay que tener en cuenta que el EstadoCivilController del sistema Registral no soporta filtrado, es decir que los parametros enviados va a ser inrrelevantes
     * @param array $param
     * @return boolean
     */
    public function buscarEstadoCivil($param)
    {
        
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
//                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/estado-civil?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    public function buscarNivelEducativo($param){
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_REGISTRAL'],
//                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://api.registral.local/api/nivel-educativos?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::error($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                return false;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
    }
    
    /**
     * crear un string con los criterio de busquedad por ejemplo: localidadid=1&calle=mata negra&altura=123
     * @param array $param
     * @return string
     */
    public function crearCriterioBusquedad($param){
        //funcion armar url con criterio de busquedad
//        $criterio = '';
//        $primeraVez = true;
//        foreach ($param as $key => $value) {
//            if($primeraVez){
//                $criterio.=$key.'='.$value;
//                $primeraVez = false;
//            }else{
//                $criterio.='&'.$key.'='.$value;
//            }            
//        }
//        
        return 0;
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