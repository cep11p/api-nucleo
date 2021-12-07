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
class ServicioLugar extends Component
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
       
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
//                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://lugar/api/localidads/'.$id, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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
     * Si el lugar a agregar no existe, se crea un lugar nuevo.
     * Caso contrario se notificará que existe y para reutilizar el lugar debe setearse un flag $usarLugarEncontrado=true
     * @param type $data
     * @return boolean
     */
    public function crearLugar($data)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($data));
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'], 
                'Content-Type'=>'application/json'
           ];          
            
            
            $response = $client->request('POST', 'http://lugar/api/lugars', ['json' => $data,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta['data']['id'];
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $resultado = json_decode($e->getResponse()->getBody()->getContents());
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                
                #devolvemos un objeto
                return $resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }
    
    
    public function buscarLugar($param,$page)
    {
        
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
                'Content-Type'=>'application/json'
            ];          
            
            $url = 'http://lugar/api/lugars?page='.$page.'&'.$criterio;
            
            $response = $client->request('GET', $url, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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
    
    public function buscarLocalidad($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://lugar/api/localidad?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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
    
    public function buscarLugarPorId($id)
    {
        
//        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://lugar/api/lugar?id='.$id, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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
     * Se registra una localidad remotamente en el sistema Lugar
     * @param array $data
     * @return boolea
     * @throws Exception
     */
    public function crearLocalidad($data)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($data));
            $headers = [
                'Content-Type'=>'application/json'
            ];          
            
            
            $response = $client->request('POST', 'http://lugar/api/localidads', ['json' => $data,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta['data']['id'];
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $resultado = json_decode($e->getResponse()->getBody()->getContents());
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                
                #devolvemos array
                return (array)$resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }

    public function modificarLocalidad($data)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($data));
            $headers = [
                'Content-Type'=>'application/json'
            ];          
            
            if(!isset($data['id'])){
                throw new \yii\web\HttpException(400, 'Falta el id de la localidad');
            }
            
            $response = $client->request('PUT', 'http://lugar/api/localidads/'.$data['id'], ['json' => $data,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta['data']['id'];
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $resultado = json_decode($e->getResponse()->getBody()->getContents());
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                
                #devolvemos array
                return (array)$resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }

    public function buscarProvincia($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://lugar/api/provincias?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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

    public function buscarLocalidadExtra($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://lugar/api/localidad-extras?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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

    public function crearLocalidadExtra($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('POST', 'http://lugar/api/localidad-extras', ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            #devolvemos array
            return (array)$resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }

    public function borrarLocalidadExtra($data)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($data));
            $headers = [
                'Content-Type'=>'application/json'
            ];          
            
            if(!isset($data['id'])){
                throw new \yii\web\HttpException(400, 'Falta el id de la localidad');
            }
            
            $response = $client->request('DELETE', 'http://lugar/api/localidad-extras/'.$data['id'], ['json' => $data,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta['data']['id'];
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $resultado = json_decode($e->getResponse()->getBody()->getContents());
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
                \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
                
                #devolvemos array
                return (array)$resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }

    public function buscarDepartamento($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
//                'Authorization' => 'Bearer ' .\Yii::$app->params['JWT_LUGAR'],
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://lugar/api/departamentos?'.$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
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