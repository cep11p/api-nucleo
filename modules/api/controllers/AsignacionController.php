<?php

namespace app\modules\api\controllers;

use app\components\ServicioInventario;
use app\components\ServicioInteroperable;
use app\models\Asignacion;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

/**
 * Esta controlador hace referencia a la tabla modulo de api-user
 */
class AsignacionController extends ActiveController
{
    public $modelClass = 'app\models\Asignacion';
    /** @var ServicioInventario */ private $servicioInventario;
    /** @var ServicioInteroperable */ private $servicioInteroperable;

    const CONTROLLER_NAME = 'modulo';
    const SERVICIO_NAME = 'user';
    
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
        die('lista de asignaciones');
        $resultado['estado']=false;
        $param = Yii::$app->request->queryParams;

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->buscarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }

    public function actionView($id)
    {
        die('Vista de una asignacion');
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
        $resultado['mensaje']='Se crea una asignacion!';

        $param = Yii::$app->request->post();
    
        $model = new Asignacion();
        $model->crearAsginacion($param);

        return $resultado;
    }

    /**
     * Modifica los datos de un registro
     *
     * @param [int] $id
     * @return void
     */
    public function actionUpdate($id){
        
        die('Se modigica asignacion');
        $resultado['estado']=false;
        $param = Yii::$app->request->post();
        $param['id'] = $id;

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->modificarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
        
    }

    public function actionDelete($id)
    {
        die('borramos una asignacion');
        $resultado['estado']=false;
        $param = Yii::$app->request->queryParams;
        $param['id'] = $id;
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->borrarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
        
        return $resultado;
    }
}
