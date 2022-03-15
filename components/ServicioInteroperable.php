<?php

/*
 * Clase para interactuar con el servicio de solicitudes de la oficina judicial
 *
 */

namespace app\components;

use app\models\User;
use yii\base\Component;
use GuzzleHttp\Client;
use Exception;
use Yii;

/**
 * Description of ServicioSolicitudComponent
 *
 * @author cep11p
 */
class ServicioInteroperable extends Component
{
    public $base_uri;
    private $_client;
   
    public function __construct($config=[])
    {
        parent::__construct($config);
        $this->_client = new Client();
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
     * Se crea el token para interoperar
     */
    private function crearToken(){
        
        $headers = Yii::$app->request->headers;

        if (preg_match('/^Bearer\s+(.*?)$/', $headers['authorization'], $matches)) {
            $token = $matches[1];
        } else {
            throw new \yii\web\HttpException(500, 'Token invalido');
        }
            
        return  $token;
    }
    
    /**
     * Esta funcion nos devuelve un listado de registros, donde tambien se puede aplicar filtros
     *
     * @param [string] $api
     * @param [string] $controller
     * @param [array] $param
     * @return array
     */
    public function buscarRegistro($api,$controller,$param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{

            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }

            $headers = [
                'Authorization' => 'Bearer ' .$this->crearToken(),
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', "http://$api/api/$controller"."s?".$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
       
    }

    /**
     * Esta función visualiza un registro
     *
     * @param [type] $api
     * @param [type] $controller
     * @param [type] $param
     * @return array
     */
    public function viewRegistro($api,$controller,$param)
    {
        $client =   $this->_client;
        try{

            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];          
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a visualizar");
            }
            
            $response = $client->request('GET', "http://$api/api/$controller"."s/".$param['id'], ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            #devolvemos array
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
       
    }

    /**
     * Creamos un registro nuevo
     *
     * @param [string] $api
     * @param [string] $controller
     * @param [array] $param
     * @return array
     */
    public function crearRegistro($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }

            $headers = [
                'Authorization' => 'Bearer ' .$this->crearToken(),
                'Content-Type'=>'application/json'
            ];          

            $response = $client->request('POST', "http://$api/api/$controller"."s", ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');

            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
       
    }

    /**
     * Modifica marca
     *
     * @param [array] $data
     * @return void
     */
    public function modificarRegistro($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];     
            
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a modificar!");
            }
            
            $response = $client->request('PUT', "http://$api/api/$controller"."s/".$param['id'], ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
    }

    /**
     * Se habilita o se inhabilita un registro
     *
     * @param [array] $data
     * @return void
     */
    public function setActivoRegistro($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];          
            
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a borrar!");
            }

            $response = $client->request('PUT', "http://$api/api/$controller"."s/set-activo/".$param['id'], ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
       
    }

    public function login($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
            ];          
            
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            $response = $client->request('POST', "http://$api/api/$controller"."s/login", ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
    }

    /**
     * Se realiza una asignacion de permiso a un usuario
     *
     * @param [string] $api
     * @param [string] $controller
     * @param [array] $param
     * @return array
     */
    public function crearAsignacion($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];          
            
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a borrar!");
            }
            
            $response = $client->request('POST', "http://$api/api/$controller"."s/crear-asignacion", ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
    }

    /**
     * Se obtiene un lista de asginaciones de un usuario
     *
     * @param [string] $api
     * @param [string] $controller
     * @param [array] $param
     * @return array
     */
    public function ListarAsignacion($api,$controller,$param)
    {
        $client =   $this->_client;
        try{

            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];          
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a visualizar");
            }
            
            $response = $client->request('GET', "http://$api/api/$controller"."s/listar-asignacion/".$param['id'], ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            #devolvemos array
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
       
    }

    public function borrarAsignacion($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];          
            
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a borrar!");
            }
            
            $response = $client->request('POST', "http://$api/api/$controller"."s/borrar-asignacion", ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            #devolvemos array
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
    }

    public function usuarioBaja($api,$controller,$param)
    {
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($param));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];     
            
            #validaciones
            if(!isset($api) || empty($api)){
                throw new \yii\web\HttpException(400, "Falta el nombre de la api para interoperar!");
            }
            #validaciones
            if(!isset($controller) || empty($controller)){
                throw new \yii\web\HttpException(400, "Falta el nombre del controlador para interoperar!");
            }
            
            if(!isset($param['id'])){
                throw new \yii\web\HttpException(400, "Falta el id del registro a modificar!");
            }
            
            $response = $client->request('PUT', "http://$api/api/$controller"."s/baja".$param['id'], ['json' => $param,'headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());
            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            
            #devolvemos array
            throw new \yii\web\HttpException(400, $resultado->message);
        } catch (Exception $e) {
            $mensaje =$e->getMessage();
            $statuCode = (isset($e->statusCode))?$e->statusCode:500;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
    }
       
       
}