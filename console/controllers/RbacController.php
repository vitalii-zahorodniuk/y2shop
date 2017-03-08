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

        // user
        ${self::PERM_USER_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_USER_CAN_VIEW_LIST);
        $auth->add(${self::PERM_USER_CAN_VIEW_LIST});

        ${self::PERM_USER_CAN_UPDATE} = $auth->createPermission(self::PERM_USER_CAN_UPDATE);
        $auth->add(${self::PERM_USER_CAN_UPDATE});
        $auth->addChild(${self::PERM_USER_CAN_UPDATE}, ${self::PERM_USER_CAN_VIEW_LIST});

        // language
        ${self::PERM_LANGUAGE_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_LANGUAGE_CAN_VIEW_LIST);
        $auth->add(${self::PERM_LANGUAGE_CAN_VIEW_LIST});

        ${self::PERM_LANGUAGE_CAN_UPDATE} = $auth->createPermission(self::PERM_LANGUAGE_CAN_UPDATE);
        $auth->add(${self::PERM_LANGUAGE_CAN_UPDATE});
        $auth->addChild(${self::PERM_LANGUAGE_CAN_UPDATE}, ${self::PERM_LANGUAGE_CAN_VIEW_LIST});

        // translate
        ${self::PERM_TRANSLATE_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_TRANSLATE_CAN_VIEW_LIST);
        $auth->add(${self::PERM_TRANSLATE_CAN_VIEW_LIST});

        ${self::PERM_TRANSLATE_CAN_UPDATE} = $auth->createPermission(self::PERM_TRANSLATE_CAN_UPDATE);
        $auth->add(${self::PERM_TRANSLATE_CAN_UPDATE});
        $auth->addChild(${self::PERM_TRANSLATE_CAN_UPDATE}, ${self::PERM_TRANSLATE_CAN_VIEW_LIST});

        // category
        ${self::PERM_CATEGORY_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_CATEGORY_CAN_VIEW_LIST);
        $auth->add(${self::PERM_CATEGORY_CAN_VIEW_LIST});

        ${self::PERM_CATEGORY_CAN_UPDATE} = $auth->createPermission(self::PERM_CATEGORY_CAN_UPDATE);
        $auth->add(${self::PERM_CATEGORY_CAN_UPDATE});
        $auth->addChild(${self::PERM_CATEGORY_CAN_UPDATE}, ${self::PERM_CATEGORY_CAN_VIEW_LIST});

        // currency
        ${self::PERM_CURRENCY_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_CURRENCY_CAN_VIEW_LIST);
        $auth->add(${self::PERM_CURRENCY_CAN_VIEW_LIST});

        ${self::PERM_CURRENCY_CAN_UPDATE} = $auth->createPermission(self::PERM_CURRENCY_CAN_UPDATE);
        $auth->add(${self::PERM_CURRENCY_CAN_UPDATE});
        $auth->addChild(${self::PERM_CURRENCY_CAN_UPDATE}, ${self::PERM_CURRENCY_CAN_VIEW_LIST});

        // product
        ${self::PERM_PRODUCT_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_PRODUCT_CAN_VIEW_LIST);
        $auth->add(${self::PERM_PRODUCT_CAN_VIEW_LIST});

        ${self::PERM_PRODUCT_CAN_UPDATE} = $auth->createPermission(self::PERM_PRODUCT_CAN_UPDATE);
        $auth->add(${self::PERM_PRODUCT_CAN_UPDATE});
        $auth->addChild(${self::PERM_PRODUCT_CAN_UPDATE}, ${self::PERM_PRODUCT_CAN_VIEW_LIST});

        // attribute
        ${self::PERM_ATTRIBUTE_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_ATTRIBUTE_CAN_VIEW_LIST);
        $auth->add(${self::PERM_ATTRIBUTE_CAN_VIEW_LIST});

        ${self::PERM_ATTRIBUTE_CAN_UPDATE} = $auth->createPermission(self::PERM_ATTRIBUTE_CAN_UPDATE);
        $auth->add(${self::PERM_ATTRIBUTE_CAN_UPDATE});
        $auth->addChild(${self::PERM_ATTRIBUTE_CAN_UPDATE}, ${self::PERM_ATTRIBUTE_CAN_VIEW_LIST});

        // filter group
        ${self::PERM_FILTER_GROUP_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_FILTER_GROUP_CAN_VIEW_LIST);
        $auth->add(${self::PERM_FILTER_GROUP_CAN_VIEW_LIST});

        ${self::PERM_FILTER_GROUP_CAN_UPDATE} = $auth->createPermission(self::PERM_FILTER_GROUP_CAN_UPDATE);
        $auth->add(${self::PERM_FILTER_GROUP_CAN_UPDATE});
        $auth->addChild(${self::PERM_FILTER_GROUP_CAN_UPDATE}, ${self::PERM_FILTER_GROUP_CAN_VIEW_LIST});

        // filter
        ${self::PERM_FILTER_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_FILTER_CAN_VIEW_LIST);
        $auth->add(${self::PERM_FILTER_CAN_VIEW_LIST});

        ${self::PERM_FILTER_CAN_UPDATE} = $auth->createPermission(self::PERM_FILTER_CAN_UPDATE);
        $auth->add(${self::PERM_FILTER_CAN_UPDATE});
        $auth->addChild(${self::PERM_FILTER_CAN_UPDATE}, ${self::PERM_FILTER_CAN_VIEW_LIST});

        // option group
        ${self::PERM_OPTION_GROUP_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_OPTION_GROUP_CAN_VIEW_LIST);
        $auth->add(${self::PERM_OPTION_GROUP_CAN_VIEW_LIST});

        ${self::PERM_OPTION_GROUP_CAN_UPDATE} = $auth->createPermission(self::PERM_OPTION_GROUP_CAN_UPDATE);
        $auth->add(${self::PERM_OPTION_GROUP_CAN_UPDATE});
        $auth->addChild(${self::PERM_OPTION_GROUP_CAN_UPDATE}, ${self::PERM_OPTION_GROUP_CAN_VIEW_LIST});

        // option
        ${self::PERM_OPTION_CAN_VIEW_LIST} = $auth->createPermission(self::PERM_OPTION_CAN_VIEW_LIST);
        $auth->add(${self::PERM_OPTION_CAN_VIEW_LIST});

        ${self::PERM_OPTION_CAN_UPDATE} = $auth->createPermission(self::PERM_OPTION_CAN_UPDATE);
        $auth->add(${self::PERM_OPTION_CAN_UPDATE});
        $auth->addChild(${self::PERM_OPTION_CAN_UPDATE}, ${self::PERM_OPTION_CAN_VIEW_LIST});


        /*
         * Roles
         */

        // customer
        ${self::ROLE_CUSTOMER} = $auth->createRole(self::ROLE_CUSTOMER);
        ${self::ROLE_CUSTOMER}->description = ucfirst(self::ROLE_CUSTOMER);
        $auth->add(${self::ROLE_CUSTOMER});

        // seller
        ${self::ROLE_SELLER} = $auth->createRole(self::ROLE_SELLER);
        ${self::ROLE_SELLER}->description = ucfirst(self::ROLE_SELLER);
        $auth->add(${self::ROLE_SELLER});
        $auth->addChild(${self::ROLE_SELLER}, ${self::ROLE_CUSTOMER});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_USER_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_USER_CAN_UPDATE});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_LANGUAGE_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_LANGUAGE_CAN_UPDATE});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_TRANSLATE_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_TRANSLATE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_CATEGORY_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_CATEGORY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_CURRENCY_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_CURRENCY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_PRODUCT_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_PRODUCT_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_ATTRIBUTE_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_ATTRIBUTE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_FILTER_GROUP_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_FILTER_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_FILTER_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_FILTER_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_OPTION_GROUP_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_OPTION_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_OPTION_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_SELLER}, ${self::PERM_OPTION_CAN_UPDATE});

        // manager
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
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_CATEGORY_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_CATEGORY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_CURRENCY_CAN_VIEW_LIST});
//        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_CURRENCY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_PRODUCT_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_PRODUCT_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_ATTRIBUTE_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_ATTRIBUTE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_FILTER_GROUP_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_FILTER_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_FILTER_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_FILTER_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_OPTION_GROUP_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_OPTION_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_OPTION_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_MANAGER}, ${self::PERM_OPTION_CAN_UPDATE});

        // admin
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
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_CATEGORY_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_CATEGORY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_CURRENCY_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_CURRENCY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_PRODUCT_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_PRODUCT_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_ATTRIBUTE_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_ATTRIBUTE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_FILTER_GROUP_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_FILTER_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_FILTER_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_FILTER_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_OPTION_GROUP_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_OPTION_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_OPTION_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ADMIN}, ${self::PERM_OPTION_CAN_UPDATE});

        // root
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
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_CATEGORY_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_CATEGORY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_CURRENCY_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_CURRENCY_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_PRODUCT_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_PRODUCT_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_ATTRIBUTE_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_ATTRIBUTE_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_FILTER_GROUP_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_FILTER_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_FILTER_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_FILTER_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_OPTION_GROUP_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_OPTION_GROUP_CAN_UPDATE});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_OPTION_CAN_VIEW_LIST});
        $auth->addChild(${self::ROLE_ROOT}, ${self::PERM_OPTION_CAN_UPDATE});
    }

}
