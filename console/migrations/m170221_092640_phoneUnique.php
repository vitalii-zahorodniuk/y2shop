<?php

use yii\db\Migration;

class m170221_092640_phoneUnique extends Migration
{
    public function up()
    {
        $this->createIndex('phone', '{{%user}}', 'phone', true);
    }

    public function down()
    {
        $this->dropIndex('phone', '{{%user}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
