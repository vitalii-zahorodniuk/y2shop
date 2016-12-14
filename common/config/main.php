<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::className(),
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'timeFormat' => 'H:i:ss',
            'datetimeFormat' => 'yyyy-MM-dd H:i:ss',
            'nullDisplay' => '',
        ],
        'security' => [
            'passwordHashCost' => 5,
        ],
        'multilangCache' => [
            'class' => \xz1mefx\multilang\caching\MultilangCache::className(),
        ],
        'urlManager' => [
            'class' => \xz1mefx\multilang\web\UrlManager::className(),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => \yii\web\UrlNormalizer::className(),
            ],
            'rules' => [
                '' => 'site/index',
                '<controller:\w+(-\w+)*>' => '<controller>/index',
                '<controller:\w+(-\w+)*>/<id:\d+>' => '<controller>/view',
                '<controller:\w+(-\w+)*>/<action:\w+(-\w+)*>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+(-\w+)*>/<action:\w+(-\w+)*>' => '<controller>/<action>',
            ],
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
    ],
];
