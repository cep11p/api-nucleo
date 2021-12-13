<?php

namespace app\modules\api\controllers;

use app\components\ServicioInventario;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

class MarcaController extends ActiveController
{
    public $modelClass = 'app\models\Marca';
    /** @var ServicioInventario */ private $servicioInventario;
    
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
        return $actions;
    }

     /**
     * Esta accion permite hacer una interoperabilidad con el sistema inventario
     * @return array()
     */
    public function actionIndex()
    {
        $resultado['estado']=false;
        $param = Yii::$app->request->queryParams;

        $servicioInventario = new ServicioInventario();
        $resultado = $servicioInventario->buscarMarca($param);
        
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
        $servicioInventario = new ServicioInventario();
        $resultado = $servicioInventario->crearMarca($param);
        
        return $resultado;
    }

    public function actionUpdate($id){
        
        $resultado['estado']=false;
        $param = Yii::$app->request->post();
        $servicioInventario = new ServicioInventario();
        $resultado = $servicioInventario->modificarMarca($param);
        
        return $resultado;
        
    }
}
