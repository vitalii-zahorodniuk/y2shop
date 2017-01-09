<?php
namespace backend\models;

use xz1mefx\base\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * Class Product
 * @package backend\models
 */
class Product extends \common\models\Product
{

    public $mainImage;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['mainImage', 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $directoryTmp = Yii::getAlias('@frontend/web/img/tmp/product') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        $directoryNew = Yii::getAlias('@frontend/web/img/product') . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR;
        $files = FileHelper::findFiles($directoryTmp);
        foreach ($files as $file) {
            if (!is_dir($directoryNew)) {
                mkdir($directoryNew, 0777, TRUE);
            }
            rename($directoryTmp . basename($file), $directoryNew . basename($file));
            $this->image_src = basename($file);
            $this->save();
        }
    }

    /**
     * @return string
     */
    public function uploadMainTmpImage()
    {
        $imageFile = UploadedFile::getInstance($this, 'mainImage');
        $directory = Yii::getAlias('@frontend/web/img/tmp/product') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, TRUE);
        }
        if ($imageFile) {
            $fileName = Yii::$app->security->generateRandomString() . ".{$imageFile->extension}";
            $filePath = $directory . $fileName;
            if ($imageFile->saveAs($filePath)) {
                $path = '/img/tmp/product/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
                return Json::encode([
                    'files' => [[
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        "url" => $path,
                        "thumbnailUrl" => $path,
                        "deleteUrl" => 'main-image-delete?name=' . $fileName,
                        "deleteType" => "POST",
                    ]],
                ]);
            }
        }
        return '';
    }

    /**
     * @return string
     */
    public static function getMainTmpImage()
    {
        $directory = Yii::getAlias('@frontend/web/img/tmp/product') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;

        return self::findMainTmpImage($directory);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function deleteTmpImageByName($name)
    {
        $directory = Yii::getAlias('@frontend/web/img/tmp/product') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        if (is_file($directory . $name)) {
            unlink($directory . $name);
        }
        return self::findMainTmpImage($directory);
    }

    /**
     * @param $dir
     *
     * @return string
     */
    private static function findMainTmpImage($dir)
    {
        $files = FileHelper::findFiles($dir);
        $output = [];
        foreach ($files as $file) {
            $path = '/img/tmp/product/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . basename($file);
            $output['files'][] = [
                'name' => basename($file),
                'size' => filesize($file),
                "url" => $path,
                "thumbnailUrl" => $path,
                "deleteUrl" => Url::to(['main-image-delete', 'name' => basename($file)]),
                "deleteType" => "POST",
            ];
        }
        return Json::encode($output);
    }

}
