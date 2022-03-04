<?php

namespace app\models;

use app\components\ServicioInteroperable;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * This is the model class for table "user".
 */
class Asignacion extends Model
{    
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
     * Creamos la asigacion de modulo y persmios. Esta funcion Interopera con microservicios
     *
     * @param [array] $params
     * @return array
     */
    public function crearAsginacion($params){
        $resultado = [];

        #Validaciones
        //validamos modulo
        if(!isset($params['moduloid']) || empty($params['moduloid'])){
            throw new HttpException(400,'Por favor ingrese un modulo');
        }
        $servicioInteroperable = new ServicioInteroperable();
        $modulo = $servicioInteroperable->viewRegistro('user','modulo', ['id' => $params['moduloid']]);

        //validamos la instancia del modulo
        if(!isset($modulo) || empty($modulo)){
            throw new HttpException(400,'No se encuentra el modulo '.$params['moduloid']);
        }

        //validamos que exista userid
        if(!isset($params['userid']) || empty($params['userid'])){
            throw new HttpException(400,'Por favor seleccione un usuario');
        }

        //validamos que exista lista_permiso
        if(!isset($params['lista_permiso']) || empty($params['lista_permiso'])){
            throw new HttpException(400,'Por favor ingrese la lista de permiso');
        }
        #fin


        

        
        
        #Vinculamos el modulo
        $usuario_modulo = $servicioInteroperable->buscarRegistro('user','usuario-modulo', [
            'userid' => $params['userid'],
            'moduloid' => $params['moduloid']
        ]);

        //si no existe el modulo asiganado al usuario realizamos la asignacion de modulos
        if(count($usuario_modulo)==0){
            $usuario_modulo = $servicioInteroperable->crearRegistro('user','usuario-modulo', [
                'userid' => $params['userid'],
                'moduloid' => $params['moduloid']
            ]);
        }
        #fin

            
        #realizamos la asignaicon de permisos y roles
        $resultado = [];
        switch ($modulo['servicio']) {
            case 'gcb':

                $resultado = $servicioInteroperable->crearAsignacion($modulo['servicio'],'usuario', [
                    'usuarioid' => $params['userid'],
                    'tipo_convenioid' => (isset($params['convenioid']))?$params['convenioid']:'',
                    'lista_permiso' => (isset($params['lista_permiso']))?$params['lista_permiso']:[]
                ]);
                break;

            case 'inventario':
                $resultado = $servicioInteroperable->crearAsignacion($modulo['servicio'],'usuario', [
                    'usuarioid' => $params['userid'],
                    'lista_permiso' => (isset($params['lista_permiso']))?$params['lista_permiso']:[]
                ]);
                break;

            case 'recurso-social':
                $resultado = $servicioInteroperable->crearAsignacion($modulo['servicio'],'usuario', [
                    'usuarioid' => $params['userid'],
                    'programaid' => (isset($params['programaid']))?$params['programaid']:'',
                    'lista_permiso' => (isset($params['lista_permiso']))?$params['lista_permiso']:[]
                ]);
                break;
            
            default:
                throw new HttpException(400,'No se encuentra el servicio '.$modulo['servicio']);
                break;
        }
        #fin

        return $resultado;
    }

    public function fields()
    {
        $fields = ArrayHelper::merge(parent::fields(), [
            "confirmed_at" => function () {
                return date('Y-m-d',$this->confirmed_at);
            },
            "created_at" => function () {
                return date('Y-m-d',$this->created_at);
            },
            "updated_at" => function () {
                return date('Y-m-d',$this->updated_at);
            },
            "last_login_at" => function () {
                return date('Y-m-d H:i:s',$this->last_login_at);
            },
            "last_login_ip" => function () {
                return $this->userPersona->last_login_ip;
            },
            "personaid" => function () {
                return $this->userPersona->personaid;
            },
            "fecha_baja" => function () {
                return ($this->userPersona->fecha_baja)?$this->userPersona->fecha_baja:'';
            },
            "baja" => function () {
                return ($this->userPersona->fecha_baja)?true:false;
            },
            "descripcion_baja" => function () {
                return ($this->userPersona->descripcion_baja)?$this->userPersona->descripcion_baja:'';
            },
            "localidadid" => function () {
                return $this->userPersona->localidadid;
            },
            "rol"

        ]);
        
        unset($fields['password_hash'],$fields['auth_key']);

        return $fields;
    }
    
}
