<?php

namespace app\models;

use app\components\ServicioInteroperable;
use app\models\ApiUser;
use app\models\User as ModelsUser;
use Exception;
use Yii;
use yii\db\Query;
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
     * Se busca el usuario mediante una interoperabilidad
     * @param int $id
     * @return User
     */
    static function findByUid($id){
        $servicioInteroperable = new ServicioInteroperable();
        $resultado = $servicioInteroperable->viewRegistro('user','usuario',['id' => $id]);

        $model = new ModelsUser();
        $model->setAttributes($resultado);
        $model->id = $resultado['id'];

        return $model;
    }    

    /**
     * Realiamos un seteo de roles interoperablemente en el modulo deseado
     */
    static function setRol($param)
    {
        if(!isset($param['servicio']) || empty($param['servicio'])){
            throw new \yii\web\HttpException(400, "Falta el modulo a asignar.");
        }
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
    
}
