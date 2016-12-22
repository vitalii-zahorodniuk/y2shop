<?php
namespace common\models;

/**
 * Interface UserInterface
 * @package common\models
 */
interface UserInterface
{

    const PERMISSION_VIEW_ALL_USERS_LIST = 'permission_view_all_users_list';
    const PERMISSION_EDIT_LANGUAGES = 'permission_edit_languages';
    const PERMISSION_EDIT_TRANSLATES = 'permission_edit_translates';

    const ROLE_ROOT = 'role_root';
    const ROLE_ADMIN = 'role_admin';
    const ROLE_MANAGER = 'role_manager';
    const ROLE_SELLER = 'role_seller';
    const ROLE_CUSTOMER = 'role_customer';

    const STATUS_DELETED = -1;
    const STATUS_ON_HOLD = 0;
    const STATUS_ACTIVE = 1;

    const PASSWORD_LENGTH_MIN = 4;
    const PASSWORD_LENGTH_MAX = 32;

}
