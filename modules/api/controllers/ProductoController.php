<?php

namespace app\modules\api\controllers;

use app\components\ServicioInventario;
use Yii;
use yii\web\Response;
use yii\rest\ActiveController;

class ProductoController extends ActiveController
{
    public $modelClass = 'app\models\Producto';
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
       unset($actions['delete']);
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
        $resultado = $servicioInventario->buscarProducto($param);
        
        return $resultado;

    }
}
