<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'user' => [
            'class' => \xz1mefx\base\web\User::className(),
        ],
        'authManager' => [
            'class' => \yii\rbac\DbManager::className(),
            'cache' => \yii\caching\FileCache::className(),
        ],
        'assetManager' => [
            'linkAssets' => TRUE,
            'appendTimestamp' => TRUE,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::className(),
        ],
        'formatter' => [
            'dateFormat' => 'php:Y-m-d',
            'timeFormat' => 'php:H:i:s',
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'nullDisplay' => '&nbsp;',
        ],
        'security' => [
            'passwordHashCost' => 5,
        ],
        'multilangCache' => [
            'class' => \xz1mefx\multilang\caching\MultilangCache::className(),
        ],
        'urlManager' => [
            'class' => \xz1mefx\multilang\web\UrlManager::className(),
            'enablePrettyUrl' => TRUE,
            'showScriptName' => FALSE,
            'suffix' => '/',
            'normalizer' => FALSE,
            'rules' => [],
        ],
        'request' => [
            'class' => \xz1mefx\multilang\web\Request::className(),
        ],
        'i18n' => [
            'class' => \xz1mefx\multilang\i18n\I18N::className(),
        ],
        'lang' => [
            'class' => \xz1mefx\multilang\components\Lang::className(),
        ],
        'ufu' => [
            'class' => \xz1mefx\ufu\components\UFU::className(),
            'urlTypes' => [
                [
                    'id' => \common\models\Product::TYPE_ID,
                    'name' => 'Product',
                ],
                [
                    'id' => 2,
                    'name' => 'Blog',
                ],
            ],
        ],
    ],
];
