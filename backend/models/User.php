<?php
namespace backend\models;

use Yii;

/**
 * @inheritdoc
 */
class User extends \common\models\User
{
    /**
     * @param $src
     *
     * @return string
     */
    public static function getImgSrc($src)
    {
        return empty($src) ?
            Yii::$app->assetManager->getPublishedUrl('@vendor/xz1mefx/yii2-adminlte/assets') . '/img/no-img.png' : $src;
    }
}
