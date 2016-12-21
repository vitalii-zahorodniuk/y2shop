<?php
use console\controllers\RbacController;
use yii\db\Migration;

/**
 * Class m161221_135401_common_init
 */
class m161221_135401_common_init extends Migration
{
    public function up()
    {
        $this->down();

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
            'id' => $this->primaryKey()->unsigned(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),

            'img' => $this->string()->null(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string()->null(),

            'config' => $this->text()->null(),

            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Add root
        $this->insert('{{%user}}', [
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('root'),
            'email' => 'root@localhost',
            'name' => 'Root',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_ROOT), 1);

        // Add admin
        $this->insert('{{%user}}', [
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'admin@localhost',
            'name' => 'Administrator',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_ADMIN), 2);

        // Add manager
        $this->insert('{{%user}}', [
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('manager'),
            'email' => 'manager@localhost',
            'name' => 'Manager',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_MANAGER), 3);

        // Add seller
        $this->insert('{{%user}}', [
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('seller'),
            'email' => 'seller@localhost',
            'name' => 'Seller',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_SELLER), 4);

        // Add customer
        $this->insert('{{%user}}', [
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('customer'),
            'email' => 'customer@localhost',
            'name' => 'Customer',
            'phone' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $authManager->assign($authManager->getRole(RbacController::ROLE_CUSTOMER), 5);


        // -------------------------------------------
        // Create currency table
        // -------------------------------------------

        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey()->unsigned(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),

            'name' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'value' => $this->decimal(15, 8)->notNull()->defaultValue(0),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);


        // -------------------------------------------
        // Create products tables
        // -------------------------------------------

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'currency_id' => $this->integer()->unsigned()->notNull(),
            'seller_id' => $this->integer()->unsigned()->null(),

            'image_src' => $this->string()->null(),
            'price' => $this->decimal(15, 3)->notNull(),

            'viewed_count' => $this->integer()->unsigned()->defaultValue(0),
            'viewed_date' => $this->dateTime()->null(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('product_currency_id', '{{%product}}', 'currency_id');
        $this->addForeignKey('product_currency_id_fk', '{{%product}}', 'currency_id', '{{%currency}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createIndex('product_seller_id', '{{%product}}', 'seller_id');
        $this->addForeignKey('product_seller_id_fk', '{{%product}}', 'seller_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('{{%product_translate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'language_id' => $this->integer()->unsigned()->notNull(),

            'name' => $this->string()->notNull(),
            'description' => $this->text()->null(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('product_translate_product_id', '{{%product_translate}}', 'product_id');
        $this->addForeignKey('product_translate_product_id_fk', '{{%product_translate}}', 'product_id', '{{%product}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createIndex('product_translate_language_id', '{{%product_translate}}', 'language_id');
        $this->addForeignKey('product_translate_language_id_fk', '{{%product_translate}}', 'language_id', \xz1mefx\multilang\models\Language::TABLE_NAME, 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('{{%product_image}}', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned()->notNull(),

            'image_src' => $this->string()->null(),

            'sort_order' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('product_image_product_id', '{{%product_image}}', 'product_id');
        $this->addForeignKey('product_image_product_id_fk', '{{%product_image}}', 'product_id', '{{%product}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%product_image}}') !== NULL) {
            $this->dropTable('{{%product_image}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%product_translate}}') !== NULL) {
            $this->dropTable('{{%product_translate}}');
        }
        if (Yii::$app->db->schema->getTableSchema('{{%product}}') !== NULL) {
            $this->dropTable('{{%product}}');
        }

        if (Yii::$app->db->schema->getTableSchema('{{%currency}}') !== NULL) {
            $this->dropTable('{{%currency}}');
        }

        if (Yii::$app->db->schema->getTableSchema('{{%user}}') !== NULL) {
            $this->dropTable('{{%user}}');
        }

        $authManager = new \yii\rbac\DbManager();
        if (Yii::$app->db->schema->getTableSchema($authManager->assignmentTable) !== NULL) {
            $this->dropTable($authManager->assignmentTable);
        }
        if (Yii::$app->db->schema->getTableSchema($authManager->itemChildTable) !== NULL) {
            $this->dropTable($authManager->itemChildTable);
        }
        if (Yii::$app->db->schema->getTableSchema($authManager->itemTable) !== NULL) {
            $this->dropTable($authManager->itemTable);
        }
        if (Yii::$app->db->schema->getTableSchema($authManager->ruleTable) !== NULL) {
            $this->dropTable($authManager->ruleTable);
        }
    }
}
