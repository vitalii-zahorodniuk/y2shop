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
        ],
        'security' => [
            'passwordHashCost' => 5,
        ],
        'urlManager' => [
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
    ],
];
