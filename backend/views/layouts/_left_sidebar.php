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
                    'label' => Yii::t('admin-side', 'Users'),
                    'url' => ['user/index'],
                    'visible' => Yii::$app->user->can(User::PERMISSION_CAN_VIEW_ALL_USERS_LIST),
                    'icon' => 'user',
                    'iconOptions' => ['prefix' => 'fa fa-'],
                ],
                [
                    'label' => Yii::t('admin-side', 'Settings'),
                    'icon' => 'cogs',
                    'iconOptions' => ['prefix' => 'fa fa-'],
                    'items' => [
                        [
                            'label' => Yii::t('admin-side', 'Languages'),
                            'icon' => 'language',
                            'iconOptions' => ['prefix' => 'fa fa-'],
                            'items' => [
                                [
                                    'label' => Yii::t('admin-side', 'System languages'),
                                    'url' => ['language/index'],
                                ],
                                [
                                    'label' => Yii::t('admin-side', 'Interface translations'),
                                    'url' => ['translation/index'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]) ?>
    </section>
</aside>
