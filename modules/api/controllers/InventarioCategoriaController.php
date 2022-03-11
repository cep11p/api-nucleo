<?php

namespace app\modules\api\controllers;

use app\components\ServicioInventario;
use app\components\ServicioInteroperable;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

class InventarioCategoriaController extends ActiveController
{
    public $modelClass = 'app\models\Categoria';
    /** @var ServicioInventario */ private $servicioInventario;
    /** @var ServicioInteroperable */ private $servicioInteroperable;

    const CONTROLLER_NAME = 'categoria';
    const SERVICIO_NAME = 'inventario';
    
    public function behaviors()
    {

        $behaviors = parent::behaviors();     

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className()
        ];

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        $behaviors['authenticator'] = $auth;

       $behaviors['authenticator'] = [
           'class' => \yii\filters\auth\HttpBearerAuth::className(),
       ];

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];     

       $behaviors['access'] = [
           'class' => \yii\filters\AccessControl::className(),
           'only' => ['@'],
           'rules' => []
       ];



        return $behaviors;
    }
    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['delete']);
        return $actions;
    }

    /**
     * Esta accion permite hacer una interoperabilidad con el sistema inventario y obtener un listado de marcas
     * @return array()
     */
    public function actionIndex()
    {
        $resultado['estado']=false;
        $param = Yii::$app->request->queryParams;

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->buscarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }

    public function actionView($id)
    {
        $resultado['estado']=false;
        $param = Yii::$app->request->queryParams;
        $param['id'] = $id;
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->viewRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }


     /**
     * Esta accion permite hacer una interoperabilidad con el sistema inventario
     * @return array()
     */
    public function actionCreate()
    {        
        $resultado['estado']=false;
        $param = Yii::$app->request->post();

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->crearRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }

    /**
     * Modifica los datos de un registro
     *
     * @param [int] $id
     * @return void
     */
    public function actionUpdate($id){
        
        $resultado['estado']=false;
        $param = Yii::$app->request->post();
        $param['id'] = $id;

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->modificarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
        
    }

    /**
     * 
     * Nos permite habilitar o inhabilitar un registro interoperablemente
     *
     * @param [int] $id
     * @return array
     */
    public function actionSetActivo($id)
    {
        $resultado['estado']=false;
        $param = Yii::$app->request->post();
        $param['id'] = $id;
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->setActivoRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }
}
