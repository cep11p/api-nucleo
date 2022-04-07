<?php

namespace app\models;

use app\components\ServicioInteroperable;
use app\models\ApiUser;
use app\models\User as ModelsUser;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 */
class User extends ApiUser
{    
    const ADMIN = 'admin';
    const SOPORTE = 'soporte';
    const USUARIO = 'usuario';

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            []
        );
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # vinculamos el audit
                'bedezign\yii2\audit\AuditTrailBehavior',
            ]
        );
    }

    /**
     * Se utiliza el id del usuario del sistema api-user
     * @param int $id
     * @return User
     */
    static function findByUid($id){
        $model = new ModelsUser();
        $model->id = $id;

        return $model;
    }    

    /**
     * Vamos a setear el rol en el modulo instanciado
     *
     * @param [array] $param
     * @return void
     */
    static function setRol($param)
    {
        $servicioInteroperable = new ServicioInteroperable();
        $servicioInteroperable->setRol($param['servicio'],'usuario',$param);
    }

    static function buscarPersonaPorCuil($cuil){
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->buscarPersonaPorCuil($cuil);
        // $resultado = \Yii::$app->registral->buscarPersonaPorCuil($cuil);
                
        if(count($resultado)>0){    
            $data['id'] = $resultado['id'];       
            $data['nro_documento'] = $resultado['nro_documento'];       
            $data['cuil'] = $resultado['cuil'];       
            $data['nombre'] = $resultado['nombre'];       
            $data['apellido'] = $resultado['apellido'];

            $usuarioPersona = UserPersona::findOne(['personaid'=>$resultado['id']]);
            if($usuarioPersona!=null){
                $data['usuario'] = User::findOne(['id'=>$usuarioPersona->userid])->toArray();
                $data['usuario']['personaid'] = $usuarioPersona->personaid;
                $data['usuario']['localidadid'] = $usuarioPersona->localidadid;
                unset($data['usuario']['password_hash']);
            }
            
        }else{
            $data = false;  
        }
        
        return $data;
    }

    /**
     * Vamos desvincular un usuario de un modulo y del rol en ese modulo
     *
     * @param [array] $param
     * @return void
     */
    static function unsetRolConModulo($param)
    {
        if(!isset($param['modulo']['servicio']) || empty($param['modulo']['servicio'])){
            throw new \yii\web\HttpException(400, "Falta el modulo.");
        }
        $servicioInteroperable = new ServicioInteroperable();
        $servicioInteroperable->unsetRol($param['modulo']['servicio'],'usuario',$param);

        return true;
    }

    /**
     * Vamos a crear las asignacion de un usuario. Esto se vincula con el modulo (api user), permiso (modulo actual), rule (modulo actualo)
     * Para ello se debe hacer una interoperabilidad con el servicio api-user y con el modulo deseado
     * @param [array] $param
     * @return array respuesta de la ultima interoperabilidad
     */
    static function crearAsignacion($parametros){
        #validamos los parametros
        $param = self::validarParametros($parametros);

        self::vincularModulo($param);
        self::setRol($param);
        $resultado = self::vincularPermiso($param);

        return $resultado;
    }

    /**
     * Vamos a crear las asignacion de un usuario. Esto se vincula con el modulo (api user), permiso (modulo actual), rule (modulo actualo)
     * Para ello se debe hacer una interoperabilidad con el servicio api-user y con el modulo deseado
     * @param [array] $param
     * @return array respuesta de la ultima interoperabilidad
     */
    static function validarParametros($param){
        #Validamos que venga el servicio para la interoperablidad dinamica
        if(!isset($param['servicio']) || empty($param['servicio'])){
            throw new \yii\web\HttpException(400, "Falta el modulo a asignar.");
        }

        #validamos modulo
        if(!isset($param['moduloid']) || empty($param['moduloid'])){
            throw new \yii\web\HttpException(400, "Falta el modulo a asignar.");
        }

        #validamos usuario
        if(!isset($param['usuarioid']) || empty($param['usuarioid'])){
            throw new \yii\web\HttpException(400, "Falta el usuario a asignar.");
        }else{ // Si la validacion del usuarioid es correcta seteo el parametro userid para le modulo
            $param['userid'] = $param['usuarioid'];
        }

        return $param;
    }

    /**
     * Se vincula el modulo al usuario en api-user. Otra interoperabilidad
     *
     * @param [array] $param
     * @return void
     */
    static function vincularModulo($param){
        $servicioInteroperable = new ServicioInteroperable();
        $servicioInteroperable->asignarModulo('user', 'usuario',$param);
    }

    /**
     * Se realiza la asignaciones de permisos para un usuario. Esto interopera dinamicamente. Por lo tanto
     * se necesita el servicio como parametro, para realizar el ruteo adecuado
     *
     * @param [array] $param
     * @return void
     */
    static function vincularPermiso($param){
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->crearAsignacion($param['servicio'],'usuario',$param);

        return $resultado;
    }
    
}
