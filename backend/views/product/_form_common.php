<?php
use backend\models\User;
use common\models\Currency;
use dosamigos\fileupload\FileUploadUI;
use xz1mefx\ufu\widgets\UfuWidget;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?= UfuWidget::widget([
    'model' => $model,
    'form' => $form,
    'type' => $model::TYPE_ID,
    'categoryMultiselect' => TRUE,
]) ?>

<?php if (Yii::$app->user->can(User::ROLE_MANAGER)): ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusesLabels()) ?>
<?php endif; ?>

<label class="control-label"><?= $model->getAttributeLabel('mainImage') ?></label>
<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'mainImage',
    'url' => [
        'main-image-upload',
        't' => Yii::$app->request->get('t'),
        'p' => ArrayHelper::getValue($model, 'id'),
    ],
    'gallery' => FALSE,
    'load' => TRUE,
    'fieldOptions' => [
        'accept' => 'image/*',
    ],
    'clientOptions' => [
        'maxNumberOfFiles' => 1,
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

<label class="control-label"><?= $model->getAttributeLabel('name') ?></label>
<div class="panel panel-default" style="background-color: #f6f8fa;">
    <div class="panel-body">
        <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
            <?= $form->field($model, "translates[{$lang['id']}][name]")->textInput(['placeholder' => Yii::t('admin-side', 'Enter a name...', [], $lang['locale'])])->label($lang['name']) ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="row">
    <?= $form->field($model, 'price', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => TRUE, 'placeholder' => Yii::t('admin-side', 'Enter a price...')]) ?>
    <?= $form->field($model, 'currency_id', ['options' => ['class' => 'col-md-6']])->dropDownList(Currency::getDrDownList()) ?>
</div>
