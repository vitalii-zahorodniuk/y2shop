<?php

namespace console\controllers;

use yii\console\Controller;
use yii\rbac\DbManager;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{

    const ROLE_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_SELLER = 'seller';
    const ROLE_BLOGGER = 'blogger';
    const ROLE_CUSTOMER = 'customer';

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

        $blogger = $auth->createRole(self::ROLE_BLOGGER);
        $blogger->description = ucfirst(self::ROLE_BLOGGER);
        $auth->add($blogger);

        $seller = $auth->createRole(self::ROLE_SELLER);
        $seller->description = ucfirst(self::ROLE_SELLER);
        $auth->add($seller);

        $manager = $auth->createRole(self::ROLE_MANAGER);
        $manager->description = ucfirst(self::ROLE_MANAGER);
        $auth->add($manager);
        $auth->addChild($manager, $customer);
        $auth->addChild($manager, $blogger);
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
