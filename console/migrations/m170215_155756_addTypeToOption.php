<?php

use yii\db\Migration;

class m170215_155756_addTypeToOption extends Migration
{
    public function up()
    {
        $this->addColumn('{{%option}}', 'type', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%option}}', 'type');
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
