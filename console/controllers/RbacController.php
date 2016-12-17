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
        ${self::PERMISSION_VIEW_ALL_USERS_LIST} = $auth->createPermission(self::PERMISSION_VIEW_ALL_USERS_LIST);
        $auth->add(${self::PERMISSION_VIEW_ALL_USERS_LIST});

        /*
         * Roles
         */
        ${self::ROLE_CUSTOMER} = $auth->createRole(self::ROLE_CUSTOMER);
        ${self::ROLE_CUSTOMER}->description = ucfirst(self::ROLE_CUSTOMER);
        $auth->add(${self::ROLE_CUSTOMER});

        ${self::ROLE_SELLER} = $auth->createRole(self::ROLE_SELLER);
        ${self::ROLE_SELLER}->description = ucfirst(self::ROLE_SELLER);
        $auth->add(${self::ROLE_SELLER});

        ${self::ROLE_MANAGER} = $auth->createRole(self::ROLE_MANAGER);
        ${self::ROLE_MANAGER}->description = ucfirst(self::ROLE_MANAGER);
        $auth->add(${self::ROLE_MANAGER});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::ROLE_CUSTOMER});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::ROLE_SELLER});

        ${self::ROLE_ADMIN} = $auth->createRole(self::ROLE_ADMIN);
        ${self::ROLE_ADMIN}->description = ucfirst(self::ROLE_ADMIN);
        $auth->add(${self::ROLE_ADMIN});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::ROLE_MANAGER});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERMISSION_VIEW_ALL_USERS_LIST});

        ${self::ROLE_ROOT} = $auth->createRole(self::ROLE_ROOT);
        ${self::ROLE_ROOT}->description = ucfirst(self::ROLE_ROOT);
        $auth->add(${self::ROLE_ROOT});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_ADMIN});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_MANAGER});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_SELLER});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_CUSTOMER});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERMISSION_VIEW_ALL_USERS_LIST});
    }

}
