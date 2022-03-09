<?php

use yii\db\Migration;

/**
 * Class m220304_153810_rols
 */
class m220304_153810_rols extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = 'auth_item';        
        $this->insert($tableName, ['name'=>'soporte','type'=>1,'description'=>'Encargado de gestionar los usuarios y permisos de los mismos','created_at'=>time()]);
        $this->insert($tableName, ['name'=>'usuario','type'=>1,'description'=>'Encargado de usar la aplicacion como herramienta','created_at'=>time()]);
   
        #admin hereda todo los rols
        $this->insert('auth_item_child', ['parent' =>'admin', 'child' => 'soporte']);
        $this->insert('auth_item_child', ['parent' =>'admin', 'child' => 'usuario']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220304_153810_rols cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220304_153810_rols cannot be reverted.\n";

        return false;
    }
    */
}
