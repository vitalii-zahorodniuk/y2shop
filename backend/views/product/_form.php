<?php
use common\models\Currency;
use dosamigos\fileupload\FileUploadUI;
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\ufu\widgets\UfuWidget;
use xz1mefx\widgets\wysihtml5\Wysihtml5;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $form yii\widgets\ActiveForm */

$this->registerCss(<<<CSS
span.preview img {
    max-height: 250px;
    max-width: 250px;
}
CSS
);
?>

<div class="box box-primary">
    <div class="box-header">
        &nbsp;
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <?= Html::icon('minus', ['prefix' => 'fa fa-']) ?>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
            'enableAjaxValidation' => TRUE,
            'validateOnType' => TRUE,
        ]); ?>

        <?= UfuWidget::widget([
            'model' => $model,
            'form' => $form,
            'type' => $model::TYPE_ID,
            'categoryMultiselect' => TRUE,
        ]) ?>

        <div class="row">
            <div class="col-md-6">
                <label class="control-label"><?= $model->getAttributeLabel('mainImage') ?></label>
                <?/*= FileUploadUI::widget([
                    'model' => $model,
                    'attribute' => 'mainImage',
                    'url' => ['main-image-upload'],
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
                ]);
                */?>
            </div>
            <div class="col-md-6">
                <label class="control-label"><?= $model->getAttributeLabel('name') ?></label>
                <div class="panel panel-default" style="background-color: #f6f8fa;">
                    <div class="panel-body">
                        <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                            <?= $form->field($model, "translates[{$lang['id']}][name]")->textInput()->label($lang['name']) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <?= $form->field($model, 'price', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => TRUE]) ?>
            <?= $form->field($model, 'currency_id', ['options' => ['class' => 'col-md-6']])->dropDownList(Currency::getDrDownList()) ?>
        </div>


        <h5><strong><?= $model->getAttributeLabel('description') ?></strong></h5>
        <div class="panel panel-default" style="background-color: #f6f8fa;">
            <div class="panel-body">
                <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                    <?= $form->field($model, "translates[{$lang['id']}][description]")->widget(Wysihtml5::className(), [
                        'widgetToolbar' => [
                            'fa' => TRUE,
                            'image' => FALSE,
                        ],
                        'options' => [
                            'class' => 'col-md-12',
                            'style' => 'margin-bottom: 20px; height: 250px;',
                            'placeholder' => "Placeholder text ...",
                        ],
                    ])->label($lang['name']) ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
