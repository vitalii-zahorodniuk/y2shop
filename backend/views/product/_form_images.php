<?php
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<label class="control-label"><?= $model->getAttributeLabel('mainImage') ?></label>
<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'galleryImage',
    'url' => [
        'gallery-image-upload',
        't' => Yii::$app->request->get('t'),
        'p' => ArrayHelper::getValue($model, 'id'),
    ],
    'gallery' => TRUE,
    'load' => TRUE,
    'fieldOptions' => [
        'accept' => 'image/*',
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000,
    ],
    // ...
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
    ],
]); ?>
