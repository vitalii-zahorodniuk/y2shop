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
 * @property xz1mefx\base\web\User $user
 * @property yii\rbac\DbManager $authManager
 * @property yii\caching\FileCache $cache
 * @property xz1mefx\multilang\caching\MultilangCache $multilangCache
 * @property xz1mefx\multilang\web\UrlManager $urlManager
 * @property xz1mefx\multilang\web\Request|yii\console\Request $request
 * @property xz1mefx\multilang\i18n\I18N $i18n
 * @property xz1mefx\multilang\components\Lang $lang
 * @property xz1mefx\ufu\components\UFU $ufu
 */
abstract class BaseApplication extends \yii\base\Application {}

/**
 * @property xz1mefx\base\web\User $user
 * @property yii\rbac\DbManager $authManager
 * @property yii\caching\FileCache $cache
 * @property xz1mefx\multilang\caching\MultilangCache $multilangCache
 * @property xz1mefx\multilang\web\UrlManager $urlManager
 * @property xz1mefx\multilang\web\Request|yii\console\Request $request
 * @property xz1mefx\multilang\i18n\I18N $i18n
 * @property xz1mefx\multilang\components\Lang $lang
 * @property xz1mefx\ufu\components\UFU $ufu
 */
class WebApplication extends \yii\web\Application {}

/**
 * @property xz1mefx\base\web\User $user
 * @property yii\rbac\DbManager $authManager
 * @property yii\caching\FileCache $cache
 * @property xz1mefx\multilang\caching\MultilangCache $multilangCache
 * @property xz1mefx\multilang\web\UrlManager $urlManager
 * @property xz1mefx\multilang\web\Request|yii\console\Request $request
 * @property xz1mefx\multilang\i18n\I18N $i18n
 * @property xz1mefx\multilang\components\Lang $lang
 * @property xz1mefx\ufu\components\UFU $ufu
 */
class ConsoleApplication extends \yii\console\Application {}
