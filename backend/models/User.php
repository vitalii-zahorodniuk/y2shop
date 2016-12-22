<?php
namespace backend\models;

use xz1mefx\adminlte\helpers\Html;
use Yii;

/**
 * @inheritdoc
 * @property string $statusHtmlLabel
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

    /**
     * @return string
     */
    public function getStatusHtmlLabel()
    {
        return Html::infoLabel(self::statusCssClass($this->status), self::statusesLabels($this->status));
    }

    /**
     * @param $status string
     *
     * @return string
     */
    public static function statusCssClass($status)
    {
        switch ($status) {
            case self::STATUS_DELETED:
                return 'bg-red-gradient';
            case self::STATUS_ON_HOLD:
                return 'bg-aqua-gradient';
            case self::STATUS_ACTIVE:
                return 'bg-green-gradient';
        }
        return 'label-default';
    }
}
