<?php

use console\controllers\RbacController;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        // -------------------------------------------
        // Create RBAC tables
        // -------------------------------------------

        $authManager = new \yii\rbac\DbManager();

        $this->createTable($authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        // Init rbac
        RbacController::initRbac();


        // -------------------------------------------
        // Create users table
        // -------------------------------------------

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),

            'img' => $this->string()->null(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string()->null(),

            'config' => $this->text()->null(),

            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),

            'is_deleted' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Add root
        $this->insert('{{%user}}', [
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_hash' => \Yii::$app->security->generatePasswordHash('root'),
            'email' => 'root@localhost',
            'name' => 'Root',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_ROOT), 1);

        // Add admin
        $this->insert('{{%user}}', [
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'admin@localhost',
            'name' => 'Administrator',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_ADMIN), 2);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');

        $authManager = new \yii\rbac\DbManager();
        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}
