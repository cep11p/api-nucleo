<?php

namespace app\modules\api\controllers;

use app\components\ServicioInteroperable;
use app\components\VinculoInteroperableHelp;
use app\models\User;
use app\models\UserPersona;
use yii\rest\ActiveController;
use Yii;
use yii\web\Response;
use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use dektrium\user\Module;
use yii\helpers\ArrayHelper;

class UsuarioController extends ActiveController
{
    public $modelClass = 'app\models\ApiUser';

    const CONTROLLER_NAME = 'usuario';
    const SERVICIO_NAME = 'user';
    
    /** @var Finder */
    protected $finder;

    /**
     * @param string $id
     * @param Module $module
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }
    
    
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
        $behaviors['authenticator']['except'] = [
            'options',
            'login',
        ];     

        $behaviors['access'] = [
            'class' => \yii\filters\AccessControl::className(),
            'only' => ['*'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['login'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index','create','update','view','buscar-persona-por-cuil','baja', 'crear-asignacion', 'listar-asignacion','borrar-asignacion'],
                    'roles' => ['soporte'],
                ]
            ]
        ];



        return $behaviors;
    }
    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex() 
    {
        $resultado['estado']=false;
        $param = Yii::$app->request->post();
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->buscarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }

    public function actionView($id) 
    {
        $resultado['estado']=false;
        $param['id'] = $id;
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->viewRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }
    
    /**
     * Login action.
     *
     * @return Response|array
     */
    public function actionLogin(){
        $resultado['estado']=false;
        $param = Yii::$app->request->post();

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->login(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }

    public function actionUpdate($id){
        $resultado['estado']=false;
        $param = Yii::$app->request->post();
        $param['id'] = $id;

        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->modificarRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);

        return $resultado;
    }
    
    /**
     * Se registra un usuario con rol, personaid y localidadid
     *
     * @return void
     */
    public function actionCreate(){
        $resultado['estado']=false;
        $param = Yii::$app->request->post();

        // $transaction = Yii::$app->db->beginTransaction();
        try {
            $servicioInteroperable = new ServicioInteroperable();
            $resultado = $servicioInteroperable->crearRegistro(self::SERVICIO_NAME,self::CONTROLLER_NAME,$param);
            $param_rol['userid'] = $resultado['data']['id'];
            $param_rol['servicio'] = $param['usuario']['modulo']['servicio'];
            $param_rol['rol'] = $param['usuario']['rol'];
            
            if(!isset($resultado['data']['id']) || empty($resultado['data']['id'])){
                throw new \yii\web\HttpException(500, 'No se pudo registrar el usuario correctamente. Vuelva a intentarlo mas tarde.');
            }
    
            User::setRol($param_rol);
            
            return $resultado;
        }catch (\yii\web\HttpException $exc) {

            #rolBack (borramos el usuario)
            $servicioInteroperable->borrarRegistro('user','usuario',['id' => $param_rol['userid']]);
            // $this->borrarRegistro('user','usuario',);

            // $transaction->rollBack();
            $mensaje =$exc->getMessage();
            $statuCode =$exc->statusCode;
            throw new \yii\web\HttpException($statuCode, $mensaje);
        }
    }

    /**
     * Esta funcionalidad realiza la busqueda de una persona, si la persona tiene un usuario le vinculamos el usuario, 
     * sino tiene un usuario solo se devolvera la persona, en todo caso si no se encuenta ninguna 
     * de las dos cosas se devuelve success=false
     *
     * @param [int] $cuil
     * @return array
     */
    public function actionBuscarPersonaPorCuil($cuil){

        $data = User::buscarPersonaPorCuil($cuil);
        if($data!=false){
            $resultado['success'] = true;
            $resultado['resultado'] = $data;
        }else{
            $resultado['success'] = false;
        }        

        return $resultado;
    }

    /**
     * Esta funcion habilita y deshabilita un usuario
     *
     * @param [int] $id
     * @return void
     */
    public function actionBaja($id){
        $params = Yii::$app->request->post();

        $model = User::findOne(['id'=>$id]);            
        if($model==NULL){
            throw new \yii\web\HttpException(400, 'El usuario con el id '.$id.' no existe!');
        }
        
        if($params['baja']===true){
            $resultado['message'] = 'Se inhabilita el usuario correctamente.';
            if(!$model->setBaja($params)){
                $resultado['message'] = 'No se pudo inhabilitar el usuario correctamente';
            }
        }else if($params['baja']===false){
            $resultado['message'] = 'Se Habilita el usuario correctamente.';
            if(!$model->unSetBaja($params)){
                $resultado['message'] = 'No se pudo habilitar el usuario correctamente';
            }
        }
        
        return $resultado;
    }


}
