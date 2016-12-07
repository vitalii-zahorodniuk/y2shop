<?php

/* @var $this \yii\web\View */

use xz1mefx\adminlte\widgets\SidebarMenu;

?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img
                    src="<?= Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist') ?>/img/user2-160x160.jpg"
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

        <?= SidebarMenu::widget([
            'headerLabel' => 'My menu',
            'menuItems' => [
                ['url' => 'site/index', 'icon' => 'th-list', 'label' => 'Dashboard', 'stickers' => [
                    ['bgClass' => 'bg-red', 'label' => 'red'],
                    ['bgClass' => 'label-success', 'label' => 's'],
                    ['bgClass' => 'label-success', 'label' => 's'],
                    ['bgClass' => 'bg-purple', 'label' => 'p'],
                ]],
                ['label' => 'Menu level 1', 'items' => [
                    ['label' => 'Menu level 2', 'items' => [
                        ['label' => 'Menu level 3', 'items' => [
                            ['label' => 'Menu level 4', 'icon' => 'user', 'items' => [
                                ['label' => 'Lvl4 page 1', 'url' => ['site/index']],
                                ['label' => 'Lvl4 page 2', 'url' => ['site/index']],
                            ]],
                        ]],
                        ['url' => ['site/index'], 'label' => 'Lvl2 page'],
                    ]],
                ]],
                ['url' => '//www.ukr.net', 'label' => 'ukr.net', 'icon' => 'user', 'iconOptions' => ['prefix' => 'fa fa-']],
            ],
        ]) ?>
    </section>
    <!-- /.sidebar -->
</aside>
