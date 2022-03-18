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
    
}
