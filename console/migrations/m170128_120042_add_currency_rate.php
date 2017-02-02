<?php

use yii\db\Migration;

class m170128_120042_add_currency_rate extends Migration
{

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%currency}}', 'is_default', $this->smallInteger(1)->defaultValue(0)->after('id'));

        $this->update('{{%currency}}', ['is_default' => 1], ['id' => 1]);

        $this->createTable('{{%currency_rate}}', [
            'id' => $this->primaryKey()->unsigned(),

            'currency_from_id' => $this->integer()->unsigned()->notNull(),
            'currency_to_id' => $this->integer()->unsigned()->notNull(),

            'coefficient' => $this->decimal(15, 6)->notNull()->defaultValue(1),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('currency_rate_currency_from_id', '{{%currency_rate}}', ['currency_from_id', 'currency_to_id'], TRUE);
        $this->createIndex('currency_rate_created_by', '{{%currency_rate}}', 'created_by');
        $this->createIndex('currency_rate_updated_by', '{{%currency_rate}}', 'updated_by');
        $this->addForeignKey('currency_rate_currency_from_id_fk', '{{%currency_rate}}', 'currency_from_id', '{{%currency}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('currency_rate_currency_to_id_fk', '{{%currency_rate}}', 'currency_to_id', '{{%currency}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('currency_rate_created_by_fk', '{{%currency_rate}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('currency_rate_updated_by_fk', '{{%currency_rate}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->insert('{{%currency_rate}}', [
            'currency_from_id' => 1,
            'currency_to_id' => 2,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%currency_rate}}', [
            'currency_from_id' => 1,
            'currency_to_id' => 3,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%currency_rate}}', [
            'currency_from_id' => 2,
            'currency_to_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%currency_rate}}', [
            'currency_from_id' => 2,
            'currency_to_id' => 3,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%currency_rate}}', [
            'currency_from_id' => 3,
            'currency_to_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%currency_rate}}', [
            'currency_from_id' => 3,
            'currency_to_id' => 2,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%currency_rate}}') !== NULL) {
            $this->dropTable('{{%currency_rate}}');
        }

        $this->dropColumn('{{%currency}}', 'is_default');
    }

}
