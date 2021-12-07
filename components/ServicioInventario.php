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
class ServicioInventario extends Component
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
    
   
    public function buscarProducto($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .$this->crearToken(),
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://inventario/api/producto?'.$criterio, ['headers' => $headers]);
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
     * Buscamos una lista de marcas
     *
     * @param [array] $param
     * @return void
     */
    public function buscarMarca($param)
    {
        $criterio = $this->crearCriterioBusquedad($param);
        $client =   $this->_client;
        try{
            $headers = [
                'Authorization' => 'Bearer ' .$this->crearToken(),
                'Content-Type'=>'application/json'
            ];          
            
            $response = $client->request('GET', 'http://inventario/api/marcas?'.$criterio, ['headers' => $headers]);
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
       
}