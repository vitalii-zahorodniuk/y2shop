<?php
use console\controllers\RbacController;
use yii\db\Migration;
use yii\rbac\DbManager;

class m170308_081659_update_user_roles extends Migration
{

    public function up()
    {
        // flush all cache
        Yii::$app->cache->cachePath = Yii::getAlias('@backend/runtime/cache');
        Yii::$app->cache->flush();
        Yii::$app->cache->cachePath = Yii::getAlias('@frontend/runtime/cache');
        Yii::$app->cache->flush();
        Yii::$app->cache->cachePath = Yii::getAlias('@runtime/cache');
        Yii::$app->cache->flush();

        // Init rbac
        RbacController::initRbac();
        $authManager = new DbManager();

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

}
