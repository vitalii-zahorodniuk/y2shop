<?php

use xz1mefx\multilang\models\Language;
use yii\db\Migration;

class m170208_144615_add_product_filters extends Migration
{

    public function up()
    {
        $this->down();

        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%filter}}', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment("If set to 0 - it is the name of the filter, if more than 0 - it is the filter value"),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),

            'order' => $this->integer()->notNull()->defaultValue(0),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('filter_parent_id', '{{%filter}}', 'parent_id');
        $this->createIndex('filter_created_by', '{{%filter}}', 'created_by');
        $this->createIndex('filter_updated_by', '{{%filter}}', 'updated_by');
        $this->addForeignKey('filter_created_by_fk', '{{%filter}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('filter_updated_by_fk', '{{%filter}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');


        $this->createTable('{{%filter_translate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'filter_id' => $this->integer()->unsigned()->notNull(),
            'language_id' => $this->integer()->unsigned()->notNull(),

            'name' => $this->string()->notNull(),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('filter_translate_filter_id', '{{%filter_translate}}', 'filter_id');
        $this->createIndex('filter_translate_language_id', '{{%filter_translate}}', 'language_id');
        $this->createIndex('filter_translate_created_by', '{{%filter_translate}}', 'created_by');
        $this->createIndex('filter_translate_updated_by', '{{%filter_translate}}', 'updated_by');
        $this->addForeignKey('filter_translate_filter_id_fk', '{{%filter_translate}}', 'filter_id', '{{%filter}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('filter_translate_language_id_fk', '{{%filter_translate}}', 'language_id', Language::TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('filter_translate_created_by_fk', '{{%filter_translate}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('filter_translate_updated_by_fk', '{{%filter_translate}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');


        $this->createTable('{{%product_filter}}', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'filter_id' => $this->integer()->unsigned()->notNull(),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('product_filter_product_filter_language_id', '{{%product_filter}}', ['product_id', 'filter_id'], TRUE);
        $this->createIndex('product_filter_created_by', '{{%product_filter}}', 'created_by');
        $this->createIndex('product_filter_updated_by', '{{%product_filter}}', 'updated_by');
        $this->addForeignKey('product_filter_product_id_fk', '{{%product_filter}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_filter_filter_id_fk', '{{%product_filter}}', 'filter_id', '{{%filter}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_filter_created_by_fk', '{{%product_filter}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('product_filter_updated_by_fk', '{{%product_filter}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%product_filter}}') !== NULL) {
            $this->dropTable('{{%product_filter}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%filter_translate}}') !== NULL) {
            $this->dropTable('{{%filter_translate}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%filter}}') !== NULL) {
            $this->dropTable('{{%filter}}');
        }
    }

}
