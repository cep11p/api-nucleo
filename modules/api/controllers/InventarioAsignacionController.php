<?php

namespace app\modules\api\controllers;

use app\components\ServicioInteroperable;
use app\components\ServicioInventario;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

class InventarioAsignacionController extends ActiveController
{

    const CONTROLLER_NAME = 'usuario';
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
     * Se asignan permisos por programa a un usuario
     *
     * @return void
     */
    public function actionCrearAsignacion(){
        $param = Yii::$app->request->post();

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->crearAsignacion(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }

    /**
     * Listamos todos los permisos asignados a un usuario, Este listado esta agrupado
     *
     * @param [int] $id
     * @return array
     */
    public function actionListarAsignacion($id){
        $param = Yii::$app->request->queryParams;
        $param['id'] = $id;
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->listarAsignacion(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }

    /**
     * Se borran los permisos asignados a un usuario
     *
     * @return void
     */
    public function actionBorrarAsignacion(){
        $param = Yii::$app->request->post();

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->borrarAsignacion(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }

    /**
     * Esta funcion habilita y deshabilita un usuario
     *
     * @param [int] $id
     * @return void
     */
    public function actionBaja($id){
        $param = Yii::$app->request->post();
        $param['id'] = $id;

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->usuarioBaja(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }
}
