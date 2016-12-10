<?php
/*
 * Yii2 Autocomplete Helper
 * https://github.com/iiifx-production/yii2-autocomplete-helper
 *
 * Vitaliy IIIFX Khomenko (c) 2016
 */

class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication
     */
    public static $app;
}

/**
 * @property yii\caching\FileCache $cache
 * @property xz1mefx\multilang\web\UrlManager $urlManager
 * @property xz1mefx\multilang\web\Request|yii\console\Request $request
 * @property xz1mefx\multilang\components\Lang $lang
 */
abstract class BaseApplication extends \yii\base\Application {}

/**
 * @property yii\caching\FileCache $cache
 * @property xz1mefx\multilang\web\UrlManager $urlManager
 * @property xz1mefx\multilang\web\Request|yii\console\Request $request
 * @property xz1mefx\multilang\components\Lang $lang
 */
class WebApplication extends \yii\web\Application {}

/**
 * @property yii\caching\FileCache $cache
 * @property xz1mefx\multilang\web\UrlManager $urlManager
 * @property xz1mefx\multilang\web\Request|yii\console\Request $request
 * @property xz1mefx\multilang\components\Lang $lang
 */
class ConsoleApplication extends \yii\console\Application {}
