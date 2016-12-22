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
        ${self::PERM_USER_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_USER_CAN_VIEW_LIST);
        $auth->add(${self::PERM_USER_CAN_VIEW_LIST});

        ${self::PERM_USER_CAN_UPDATE} = $auth->createPermission(self::PERM_USER_CAN_UPDATE);
        $auth->add(${self::PERM_USER_CAN_UPDATE});
        $auth->addChild(${self::PERM_USER_CAN_UPDATE}, ${self::PERM_USER_CAN_VIEW_LIST});

        ${self::PERM_LANGUAGE_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_LANGUAGE_CAN_VIEW_LIST);
        $auth->add(${self::PERM_LANGUAGE_CAN_VIEW_LIST});

        ${self::PERM_LANGUAGE_CAN_UPDATE} = $auth->createPermission(self::PERM_LANGUAGE_CAN_UPDATE);
        $auth->add(${self::PERM_LANGUAGE_CAN_UPDATE});
        $auth->addChild(${self::PERM_LANGUAGE_CAN_UPDATE}, ${self::PERM_LANGUAGE_CAN_VIEW_LIST});

        ${self::PERM_TRANSLATE_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_TRANSLATE_CAN_VIEW_LIST);
        $auth->add(${self::PERM_TRANSLATE_CAN_VIEW_LIST});

        ${self::PERM_TRANSLATE_CAN_UPDATE} = $auth->createPermission(self::PERM_TRANSLATE_CAN_UPDATE);
        $auth->add(${self::PERM_TRANSLATE_CAN_UPDATE});
        $auth->addChild(${self::PERM_TRANSLATE_CAN_UPDATE}, ${self::PERM_TRANSLATE_CAN_VIEW_LIST});


        /*
         * Roles
         */
        ${self::ROLE_CUSTOMER} = $auth->createRole(self::ROLE_CUSTOMER);
        ${self::ROLE_CUSTOMER}->description = ucfirst(self::ROLE_CUSTOMER);
        $auth->add(${self::ROLE_CUSTOMER});

        ${self::ROLE_SELLER} = $auth->createRole(self::ROLE_SELLER);
        ${self::ROLE_SELLER}->description = ucfirst(self::ROLE_SELLER);
        $auth->add(${self::ROLE_SELLER});
        $auth->addChild(${self::ROLE_SELLER}, ${self::ROLE_CUSTOMER});

        ${self::ROLE_MANAGER} = $auth->createRole(self::ROLE_MANAGER);
        ${self::ROLE_MANAGER}->description = ucfirst(self::ROLE_MANAGER);
        $auth->add(${self::ROLE_MANAGER});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::ROLE_CUSTOMER});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::ROLE_SELLER});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_USER_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_USER_CAN_UPDATE});
//        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_LANGUAGE_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_LANGUAGE_CAN_UPDATE});
//        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_TRANSLATE_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_TRANSLATE_CAN_UPDATE});

        ${self::ROLE_ADMIN} = $auth->createRole(self::ROLE_ADMIN);
        ${self::ROLE_ADMIN}->description = ucfirst(self::ROLE_ADMIN);
        $auth->add(${self::ROLE_ADMIN});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::ROLE_MANAGER});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::ROLE_CUSTOMER});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_USER_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_USER_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_LANGUAGE_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_LANGUAGE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_TRANSLATE_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_TRANSLATE_CAN_UPDATE});

        ${self::ROLE_ROOT} = $auth->createRole(self::ROLE_ROOT);
        ${self::ROLE_ROOT}->description = ucfirst(self::ROLE_ROOT);
        $auth->add(${self::ROLE_ROOT});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_ADMIN});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_MANAGER});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_SELLER});
        $auth->addChild(${self::ROLE_ROOT}, ${self::ROLE_CUSTOMER});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_USER_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_USER_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_LANGUAGE_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_LANGUAGE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_TRANSLATE_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_TRANSLATE_CAN_UPDATE});
    }

}
