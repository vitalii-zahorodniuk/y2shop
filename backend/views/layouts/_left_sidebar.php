<?php
use backend\models\User;
use xz1mefx\adminlte\widgets\SidebarMenu;

/* @var $this \yii\web\View */
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <?php /*
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img
                    src="<?= Yii::$app->assetManager->getPublishedUrl('@vendor/xz1mefx/yii2-adminlte/assets') ?>/adminlte/img/user2-160x160.jpg"
                    class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        */ ?>

        <?= SidebarMenu::widget([
//            'headerLabel' => 'My menu',
            'menuItems' => [
                [
                    'label' => Yii::t('admin-side', 'Catalog'),
                    'visible' => Yii::$app->user->can([
                        User::ROLE_ROOT,
                        User::PERM_CATEGORY_CAN_VIEW_LIST,
                        User::PERM_PRODUCT_CAN_VIEW_LIST,
                        User::PERM_ATTRIBUTE_CAN_VIEW_LIST,
                        User::PERM_FILTER_GROUP_CAN_VIEW_LIST,
                        User::PERM_FILTER_CAN_VIEW_LIST,
                        User::PERM_OPTION_GROUP_CAN_VIEW_LIST,
                        User::PERM_OPTION_CAN_VIEW_LIST,
                    ]),
                    'icon' => 'tags',
                    'iconOptions' => ['prefix' => 'fa fa-'],
                    'items' => [
                        [
                            'label' => Yii::t('admin-side', 'Categories'),
                            'url' => ['category/index'],
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_CATEGORY_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'sitemap',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                        ],
                        [
                            'label' => Yii::t('admin-side', 'Products'),
                            'url' => ['product/index'],
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_PRODUCT_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'cubes',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                        ],
                        [
                            'label' => Yii::t('admin-side', 'Attributes'),
                            'url' => ['attribute/index'],
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_ATTRIBUTE_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'list',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                        ],
                        [
                            'label' => Yii::t('admin-side', 'Filters'),
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_FILTER_GROUP_CAN_VIEW_LIST,
                                User::PERM_FILTER_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'filter',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                            'items' => [
                                [
                                    'label' => Yii::t('admin-side', 'Filter groups'),
                                    'url' => ['filter-group/index'],
                                    'visible' => Yii::$app->user->can([
                                        User::ROLE_ROOT,
                                        User::PERM_FILTER_GROUP_CAN_VIEW_LIST,
                                    ]),
                                    'icon' => 'th',
                                    'iconOptions' => ['prefix' => 'fa fa-'],
                                ],
                                [
                                    'label' => Yii::t('admin-side', 'Filters'),
                                    'url' => ['filter/index'],
                                    'visible' => Yii::$app->user->can([
                                        User::ROLE_ROOT,
                                        User::PERM_FILTER_CAN_VIEW_LIST,
                                    ]),
                                    'icon' => 'th-list',
                                    'iconOptions' => ['prefix' => 'fa fa-'],
                                ],
                            ],
                        ],
                        [
                            'label' => Yii::t('admin-side', 'Options'),
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_OPTION_GROUP_CAN_VIEW_LIST,
                                User::PERM_OPTION_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'check-square-o',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                            'items' => [
                                [
                                    'label' => Yii::t('admin-side', 'Option groups'),
                                    'url' => ['option-group/index'],
                                    'visible' => Yii::$app->user->can([
                                        User::ROLE_ROOT,
                                        User::PERM_OPTION_GROUP_CAN_VIEW_LIST,
                                    ]),
                                    'icon' => 'th',
                                    'iconOptions' => ['prefix' => 'fa fa-'],
                                ],
                                [
                                    'label' => Yii::t('admin-side', 'Options'),
                                    'url' => ['option/index'],
                                    'visible' => Yii::$app->user->can([
                                        User::ROLE_ROOT,
                                        User::PERM_OPTION_CAN_VIEW_LIST,
                                    ]),
                                    'icon' => 'th-list',
                                    'iconOptions' => ['prefix' => 'fa fa-'],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'label' => Yii::t('admin-side', 'Users'),
                    'url' => ['user/index'],
                    'visible' => Yii::$app->user->can([
                        User::ROLE_ROOT,
                        User::PERM_USER_CAN_VIEW_LIST,
                    ]),
                    'icon' => 'user',
                    'iconOptions' => ['prefix' => 'fa fa-'],
                ],
                [
                    'label' => Yii::t('admin-side', 'Settings'),
                    'visible' => Yii::$app->user->can([
                        User::ROLE_ROOT,
                        User::PERM_LANGUAGE_CAN_VIEW_LIST,
                        User::PERM_TRANSLATE_CAN_VIEW_LIST,
                        User::PERM_CURRENCY_CAN_VIEW_LIST,
                    ]),
                    'icon' => 'cogs',
                    'iconOptions' => ['prefix' => 'fa fa-'],
                    'items' => [
                        [
                            'label' => Yii::t('admin-side', 'Currencies'),
                            'url' => ['currency/index'],
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_CURRENCY_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'dollar',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                        ],
                        [
                            'label' => Yii::t('admin-side', 'Languages'),
                            'visible' => Yii::$app->user->can([
                                User::ROLE_ROOT,
                                User::PERM_LANGUAGE_CAN_VIEW_LIST,
                                User::PERM_TRANSLATE_CAN_VIEW_LIST,
                            ]),
                            'icon' => 'language',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                            'items' => [
                                [
                                    'label' => Yii::t('admin-side', 'System languages'),
                                    'url' => ['language/index'],
                                    'visible' => Yii::$app->user->can([
                                        User::ROLE_ROOT,
                                        User::PERM_LANGUAGE_CAN_VIEW_LIST,
                                    ]),
                                ],
                                [
                                    'label' => Yii::t('admin-side', 'Interface translations'),
                                    'url' => ['translation/index'],
                                    'visible' => Yii::$app->user->can([
                                        User::ROLE_ROOT,
                                        User::PERM_TRANSLATE_CAN_VIEW_LIST,
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]) ?>
    </section>
</aside>
