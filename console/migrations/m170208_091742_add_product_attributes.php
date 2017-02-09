<?php

use xz1mefx\multilang\models\Language;
use yii\db\Migration;

class m170208_091742_add_product_attributes extends Migration
{

    public function up()
    {
        $this->down();

        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%attribute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('attribute_created_by', '{{%attribute}}', 'created_by');
        $this->createIndex('attribute_updated_by', '{{%attribute}}', 'updated_by');
        $this->addForeignKey('attribute_created_by_fk', '{{%attribute}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('attribute_updated_by_fk', '{{%attribute}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');


        $this->createTable('{{%attribute_translate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'attribute_id' => $this->integer()->unsigned()->notNull(),
            'language_id' => $this->integer()->unsigned()->notNull(),

            'name' => $this->string()->notNull(),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('attribute_translate_attribute_id', '{{%attribute_translate}}', 'attribute_id');
        $this->createIndex('attribute_translate_language_id', '{{%attribute_translate}}', 'language_id');
        $this->createIndex('attribute_translate_created_by', '{{%attribute_translate}}', 'created_by');
        $this->createIndex('attribute_translate_updated_by', '{{%attribute_translate}}', 'updated_by');
        $this->addForeignKey('attribute_translate_attribute_id_fk', '{{%attribute_translate}}', 'attribute_id', '{{%attribute}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('attribute_translate_language_id_fk', '{{%attribute_translate}}', 'language_id', Language::TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('attribute_translate_created_by_fk', '{{%attribute_translate}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('attribute_translate_updated_by_fk', '{{%attribute_translate}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');


        $this->createTable('{{%product_attribute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'attribute_id' => $this->integer()->unsigned()->notNull(),
            'language_id' => $this->integer()->unsigned()->notNull(),

            'value' => $this->string()->notNull(),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('product_attribute_product_attribute_language_id', '{{%product_attribute}}', ['product_id', 'attribute_id', 'language_id'], TRUE);
        $this->createIndex('product_attribute_created_by', '{{%product_attribute}}', 'created_by');
        $this->createIndex('product_attribute_updated_by', '{{%product_attribute}}', 'updated_by');
        $this->addForeignKey('product_attribute_product_id_fk', '{{%product_attribute}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_attribute_attribute_id_fk', '{{%product_attribute}}', 'attribute_id', '{{%attribute}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_attribute_language_id_fk', '{{%product_attribute}}', 'language_id', Language::TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_attribute_created_by_fk', '{{%product_attribute}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('product_attribute_updated_by_fk', '{{%product_attribute}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%product_attribute}}') !== NULL) {
            $this->dropTable('{{%product_attribute}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%attribute_translate}}') !== NULL) {
            $this->dropTable('{{%attribute_translate}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%attribute}}') !== NULL) {
            $this->dropTable('{{%attribute}}');
        }
    }

}
