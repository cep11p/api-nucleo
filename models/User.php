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

    public function setRol($rol)
    {
        #Chequeamos si el rol existe
        if(AuthItem::findOne(['name'=>$rol,'type'=>AuthItem::ROLE])==NULL){
            throw new \yii\web\HttpException(400, json_encode([['rol'=>'El rol '.$rol.' no existe']]));
        }

        ######### Asignamos el Rol ###########
        //Si el usuario tiene rol borramos y dsp lo recreamos
        AuthAssignment::deleteAll(['user_id'=>$this->id, 'item_name'=>User::USUARIO]);
        AuthAssignment::deleteAll(['user_id'=>$this->id, 'item_name'=>User::SOPORTE]);
        AuthAssignment::deleteAll(['user_id'=>$this->id, 'item_name'=>User::ADMIN]);
        

        $auth_assignment = new AuthAssignment();
        $auth_assignment->setAttributes(['item_name'=>$rol,'user_id'=>strval($this->id)]);
        if(!$auth_assignment->save()){
            throw new \yii\web\HttpException(400, json_encode([$auth_assignment->errors]));
        }

        ######### Fin de asignacion de Rol ###########

    }
    
}
