<?php

namespace app\modules\api\controllers;

use app\components\ServicioInteroperable;
use app\components\ServicioInventario;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

class InventarioProductoController extends ActiveController
{
    public $modelClass = 'app\models\Producto';
    /** @var ServicioInventario */ private $servicioInventario;

    const CONTROLLER_NAME = 'producto';
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
        unset($actions['delete']);
        unset($actions['index']);
        return $actions;
    }

     /**
     * Esta accion permite hacer una interoperabilidad con otro sistema y nos arma el listado deseado
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


    /**
     * Nos permite hacer una interoperabilidad y ver un registro
     *
     * @param [int] $id
     * @return array()
     */
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
     * Esta accion permite hacer una interoperabilidad y registrar un nuevo registro
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
     * @return array
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
     * Nos permite borrar un registro interoperablemente
     *
     * @param [int] $id
     * @return array
     */
    public function actionDelete($id)
    {
        $resultado['estado']=false;
        $param = Yii::$app->request->queryParams;
        $param['id'] = $id;
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->borrarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }
}
