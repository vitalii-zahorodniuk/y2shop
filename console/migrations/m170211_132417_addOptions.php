<?php

use console\controllers\RbacController;
use xz1mefx\multilang\models\Language;
use yii\db\Migration;

class m170211_132417_addOptions extends Migration
{
    public function safeUp()
    {
        $this->down();

        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option}}', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment("If set to 0 - it is the name of the option, if more than 0 - it is the option value"),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'order' => $this->integer()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('option_order', '{{%option}}', 'order');
        $this->createIndex('option_parent_id', '{{%option}}', 'parent_id');
        $this->createIndex('option_created_by', '{{%option}}', 'created_by');
        $this->createIndex('option_updated_by', '{{%option}}', 'updated_by');
        $this->addForeignKey('option_created_by_fk', '{{%option}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('option_updated_by_fk', '{{%option}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');


        $this->createTable('{{%option_translate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'option_id' => $this->integer()->unsigned()->notNull(),
            'language_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string()->notNull(),
            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('option_translate_option_id', '{{%option_translate}}', 'option_id');
        $this->createIndex('option_translate_language_id', '{{%option_translate}}', 'language_id');
        $this->createIndex('option_translate_created_by', '{{%option_translate}}', 'created_by');
        $this->createIndex('option_translate_updated_by', '{{%option_translate}}', 'updated_by');
        $this->addForeignKey('option_translate_option_id_fk', '{{%option_translate}}', 'option_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('option_translate_language_id_fk', '{{%option_translate}}', 'language_id', Language::TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('option_translate_created_by_fk', '{{%option_translate}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('option_translate_updated_by_fk', '{{%option_translate}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('{{%product_option}}', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'option_id' => $this->integer()->unsigned()->notNull(),

            'created_by' => $this->integer()->unsigned()->null(),
            'updated_by' => $this->integer()->unsigned()->null(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('product_option_product_option_language_id', '{{%product_option}}', ['product_id', 'option_id'], TRUE);
        $this->createIndex('product_option_created_by', '{{%product_option}}', 'created_by');
        $this->createIndex('product_option_updated_by', '{{%product_option}}', 'updated_by');
        $this->addForeignKey('product_option_product_id_fk', '{{%product_option}}', 'product_id', '{{%product}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_option_option_id_fk', '{{%product_option}}', 'option_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_option_created_by_fk', '{{%product_option}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('product_option_updated_by_fk', '{{%product_option}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');

        // Init rbac
        RbacController::initRbac();
        $authManager = new \yii\rbac\DbManager();
        //  root
        $authManager->assign($authManager->getRole(RbacController::ROLE_ROOT), 1);

        //  admin
        $authManager->assign($authManager->getRole(RbacController::ROLE_ADMIN), 2);

        //  manager
        $authManager->assign($authManager->getRole(RbacController::ROLE_MANAGER), 3);

        //  seller
        $authManager->assign($authManager->getRole(RbacController::ROLE_SELLER), 4);

        //  customer
        $authManager->assign($authManager->getRole(RbacController::ROLE_CUSTOMER), 5);
    }

    public function safeDown()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%product_option}}') !== NULL) {
            $this->dropTable('{{%product_option}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%option_translate}}') !== NULL) {
            $this->dropTable('{{%option_translate}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%option}}') !== NULL) {
            $this->dropTable('{{%option}}');
        }
    }
}
