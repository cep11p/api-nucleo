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

    private function crearToken(){
        $payload = [
            'exp'=>time()+3600,
            'usuario'=>\Yii::$app->params['USER_APP'],
            'uid' => \Yii::$app->params['USERID_APP'],
//            'usuario_real'=>\Yii::$app->user->identity->username //comentado para DEV
        ];
        
        $token = \Firebase\JWT\JWT::encode($payload, \Yii::$app->params['INVENTARIO_JWT_SECRET']);   
            
        return  $token;
    }
    
    /**
     * Esta funcion nos devuelve un listado de registros, donde tambien se puede aplicar filtros
     *
     * @param [string] $api
     * @param [string] $controller
     * @param [array] $param
     * @return void
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
            
            $response = $client->request('GET', "http://$api/api/$controller?".$criterio, ['headers' => $headers]);
            $respuesta = json_decode($response->getBody()->getContents(), true);
            \Yii::info($respuesta);
            
            return $respuesta;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $resultado = json_decode($e->getResponse()->getBody()->getContents());

            \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e->getResponse()->getBody()));
            \Yii::error('Error de integración:'.$e->getResponse()->getBody(), $category='apioj');
            return $resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
        }
       
    }

    /**
     * Esta función visualiza un registro
     *
     * @param [type] $api
     * @param [type] $controller
     * @param [type] $param
     * @return void
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
                return (array)$resultado;
            } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');

                return false;
        }
       
    }

    /**
     * Creamos un registro nuevo
     *
     * @param [string] $api
     * @param [string] $controller
     * @param [array] $param
     * @return void
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

            return $resultado;
        } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');
                return false;
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
                
                #devolvemos array
                return $resultado;
            } catch (Exception $e) {
                \Yii::$app->getModule('audit')->data('catchedexc', \yii\helpers\VarDumper::dumpAsString($e));
                \Yii::error('Error inesperado: se produjo:'.$e->getMessage(), $category='apioj');

                return $e->getMessage();
        }
       
    }

    /**
     * Borrar marca
     *
     * @param [array] $data
     * @return void
     */
    public function borrarMarca($data)
    {
        $controller_name = 'marca';
        $client =   $this->_client;
        try{
            \Yii::error(json_encode($data));
            $headers = [
                'Content-Type'=>'application/json',
                'Authorization' => 'Bearer ' .$this->crearToken(),
            ];          
            
            if(!isset($data['id'])){
                throw new \yii\web\HttpException(400, "Falta el id de la $controller_name");
            }
            
            $response = $client->request('DELETE', "http://inventario/api/$controller_name"."s/".$data['id'], ['json' => $data,'headers' => $headers]);
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
       
}