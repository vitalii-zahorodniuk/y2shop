<?php
namespace common\models;

/**
 * Interface UserInterface
 * @package common\models
 */
interface UserInterface
{

    const PERMISSION_CAN_VIEW_ALL_USERS_LIST = 'can_view_all_users_list';

    const ROLE_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_SELLER = 'seller';
    const ROLE_CUSTOMER = 'customer';

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_ON_HOLD = 2;

    const PASSWORD_LENGTH_MIN = 4;
    const PASSWORD_LENGTH_MAX = 32;

}
