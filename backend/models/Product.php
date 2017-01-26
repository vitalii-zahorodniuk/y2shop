<?php
namespace backend\models;

use common\models\ProductImage;
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\base\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * @inheritdoc
 * @property string $statusHtmlLabel
 */
class Product extends \common\models\Product
{

    public $mainImage;
    public $galleryImage;

    /**
     * @param $attribute
     *
     * @return string
     */
    public static function uploadTmpImage($attribute)
    {
        if (empty($attribute)) {
            return '';
        }

        if (Yii::$app->request->get('p')) {
            $model = self::findOne(Yii::$app->request->get('p'));
            if ($model === NULL) {
                return '';
            }
            $uniqueId = $model->id;
        } else {
            $model = new self();
            $uniqueId = Yii::$app->request->get('t');
            if (empty($uniqueId)) {
                return '';
            }
        }

        $directoryUrl = "/img/tmp/product/$uniqueId/$attribute/";
        $directoryPath = Yii::getAlias('@frontend/web') . $directoryUrl;
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, TRUE);
        }

        $uploadedFile = UploadedFile::getInstance($model, $attribute);
        if ($uploadedFile === NULL) {
            return '';
        }

        $fileName = Yii::$app->security->generateRandomString() . ".{$uploadedFile->extension}";
        $filePath = $directoryPath . $fileName;
        if ($uploadedFile->saveAs($filePath)) {
            return Json::encode([
                'files' => [[
                    'name' => $fileName,
                    'size' => $uploadedFile->size,
                    "url" => $directoryUrl . $fileName,
                    "thumbnailUrl" => $directoryUrl . $fileName,
                    "deleteUrl" => Url::to([
                        $attribute == 'mainImage' ? 'main-image-delete' : 'gallery-image-delete',
                        'name' => $fileName,
                        't' => Yii::$app->request->get('t'),
                        'p' => ArrayHelper::getValue($model, 'id'),
                    ]),
                    "deleteType" => "POST",
                ]],
            ]);
        }
        return '';
    }

    /**
     * @param $attribute string
     * @param $name      string
     *
     * @return string
     */
    public static function deleteImageByName($attribute, $name)
    {
        if (empty($attribute) || empty($name)) {
            return '';
        }

        $model = NULL;
        if (Yii::$app->request->get('p')) {
            $model = self::findOne(Yii::$app->request->get('p'));
            if ($model === NULL) {
                return '';
            }
            $uniqueId = $model->id;
        } else {
            $uniqueId = Yii::$app->request->get('t');
            if (empty($uniqueId)) {
                return '';
            }
        }

        $directoryUrl = "/img/product/$uniqueId/$attribute/";
        $directoryPath = Yii::getAlias('@frontend/web') . $directoryUrl;
        if (is_file($directoryPath . $name)) {
            unlink($directoryPath . $name);
        }

        $directoryUrl = "/img/tmp/product/$uniqueId/$attribute/";
        $directoryPath = Yii::getAlias('@frontend/web') . $directoryUrl;
        if (is_file($directoryPath . $name)) {
            unlink($directoryPath . $name);
        }

        if ($model) {
            switch ($attribute) {
                case 'mainImage':
                    $model->image_src = NULL;
                    $model->save(FALSE);
                    break;
                case 'galleryImage':
                    ProductImage::deleteAll([
                        'product_id' => $model->id,
                        'image_src' => $name,
                    ]);
                    break;
            }
        }

        return self::getImage($attribute);
    }

    /**
     * @param $attribute
     *
     * @return string
     */
    public static function getImage($attribute)
    {
        if (empty($attribute)) {
            return '';
        }

        if (Yii::$app->request->get('p')) {
            $model = self::findOne(Yii::$app->request->get('p'));
            if ($model === NULL) {
                return '';
            }
            $uniqueId = $model->id;
        } else {
            $model = new self();
            $uniqueId = Yii::$app->request->get('t');
            if (empty($uniqueId)) {
                return '';
            }
        }

        $output = [];

        $directoryUrl = "/img/product/$uniqueId/$attribute/";
        $directoryPath = Yii::getAlias('@frontend/web') . $directoryUrl;
        if (is_dir($directoryPath)) {
            $files = FileHelper::findFiles($directoryPath);
            foreach ($files as $file) {
                $fileBaseName = basename($file);
                $output['files'][] = [
                    'name' => $fileBaseName,
                    'size' => filesize($file),
                    "url" => $directoryUrl . $fileBaseName,
                    "thumbnailUrl" => $directoryUrl . $fileBaseName,
                    "deleteUrl" => Url::to([
                        $attribute == 'mainImage' ? 'main-image-delete' : 'gallery-image-delete',
                        'name' => $fileBaseName,
                        't' => Yii::$app->request->get('t'),
                        'p' => ArrayHelper::getValue($model, 'id'),
                    ]),
                    "deleteType" => "POST",
                ];
            }
        }

        $directoryUrl = "/img/tmp/product/$uniqueId/$attribute/";
        $directoryPath = Yii::getAlias('@frontend/web') . $directoryUrl;
        if (is_dir($directoryPath)) {
            $files = FileHelper::findFiles($directoryPath);
            foreach ($files as $file) {
                $fileBaseName = basename($file);
                $output['files'][] = [
                    'name' => $fileBaseName,
                    'size' => filesize($file),
                    "url" => $directoryUrl . $fileBaseName,
                    "thumbnailUrl" => $directoryUrl . $fileBaseName,
                    "deleteUrl" => Url::to([
                        $attribute == 'mainImage' ? 'main-image-delete' : 'gallery-image-delete',
                        'name' => $fileBaseName,
                        't' => Yii::$app->request->get('t'),
                        'p' => ArrayHelper::getValue($model, 'id'),
                    ]),
                    "deleteType" => "POST",
                ];
            }
        }

        return Json::encode($output);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['mainImage', 'galleryImage'], 'string'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        self::saveImages($insert);
    }

    public function saveImages($insert)
    {
        if ($insert) {
            $uniqueId = Yii::$app->request->get('t');
            if (empty($uniqueId)) {
                return;
            }
        } else {
            $uniqueId = $this->id;
        }

        foreach (['mainImage', 'galleryImage'] as $folder) {
            $directoryTmp = Yii::getAlias('@frontend/web') . "/img/tmp/product/$uniqueId/$folder/";
            $directoryNew = Yii::getAlias('@frontend/web') . "/img/product/$this->id/$folder/";

            if (is_dir($directoryTmp)) {
                $files = FileHelper::findFiles($directoryTmp);
                foreach ($files as $file) {
                    if (!is_dir($directoryNew)) {
                        mkdir($directoryNew, 0777, TRUE);
                    }
                    rename($directoryTmp . basename($file), $directoryNew . basename($file));
                    switch ($folder) {
                        case 'mainImage':
                            $this->image_src = basename($file);
                            $this->save(FALSE); // disable validation for
                            break;
                        case 'galleryImage':
                            $pi = new ProductImage();
                            $pi->product_id = $this->id;
                            $pi->image_src = basename($file);
                            $pi->save();
                            break;
                    }
                }
            }
        }
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
