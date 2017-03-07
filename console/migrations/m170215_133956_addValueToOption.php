<?php

use yii\db\Migration;

class m170215_133956_addValueToOption extends Migration
{
    public function up()
    {
        $this->addColumn('{{%option}}', 'value', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%option}}', 'value');
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
