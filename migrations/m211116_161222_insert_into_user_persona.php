<?php

use yii\db\Migration;

/**
 * Class m211116_161222_insert_into_user_persona
 */
class m211116_161222_insert_into_user_persona extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user_persona', [
            'userid' => 1,
            'personaid' => 0,
            'localidadid' => 2626
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211116_161222_insert_into_user_persona cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211116_161222_insert_into_user_persona cannot be reverted.\n";

        return false;
    }
    */
}
