<?php

use yii\db\Migration;

/**
 * Class m211203_145851_set_roles
 */
class m211203_145851_set_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = 'auth_item';        
        $this->insert($tableName, ['name'=>'admin','type'=>1,'description'=>'Encargado de gestionar todo el sistema','created_at'=>time()]);

        $this->insert('auth_assignment', [
            'item_name' => 'admin',
            'user_id' => '1'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211203_145851_set_roles cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211203_145851_set_roles cannot be reverted.\n";

        return false;
    }
    */
}
