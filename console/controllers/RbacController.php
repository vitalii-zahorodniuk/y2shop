<?php
namespace console\controllers;

use common\models\UserInterface;
use yii\console\Controller;
use yii\rbac\DbManager;

/**
 * Class RbacController
 * @package console\controllers
 */
class RbacController extends Controller implements UserInterface
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * Initial RBAC action
     */
    public function actionInit()
    {
        self::initRbac();
    }

    public static function initRbac()
    {
        $auth = new DbManager;
        $auth->init();

        $auth->removeAll();

        /*
         * Permissions
         */
//        $permission = $auth->createPermission('perm');

        /*
         * Roles
         */
        $customer = $auth->createRole(self::ROLE_CUSTOMER);
        $customer->description = ucfirst(self::ROLE_CUSTOMER);
        $auth->add($customer);

        $seller = $auth->createRole(self::ROLE_SELLER);
        $seller->description = ucfirst(self::ROLE_SELLER);
        $auth->add($seller);

        $manager = $auth->createRole(self::ROLE_MANAGER);
        $manager->description = ucfirst(self::ROLE_MANAGER);
        $auth->add($manager);
        $auth->addChild($manager, $customer);
        $auth->addChild($manager, $seller);

        $admin = $auth->createRole(self::ROLE_ADMIN);
        $admin->description = ucfirst(self::ROLE_ADMIN);
        $auth->add($admin);
        $auth->addChild($admin, $manager);

        $root = $auth->createRole(self::ROLE_ROOT);
        $root->description = ucfirst(self::ROLE_ROOT);
        $auth->add($root);
        $auth->addChild($root, $admin);
    }

}
