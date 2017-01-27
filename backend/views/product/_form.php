<?php
use backend\models\User;
use common\models\Currency;
use dosamigos\fileupload\FileUploadUI;
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\ufu\widgets\UfuWidget;
use xz1mefx\wysihtml5\Wysihtml5;
use yii\helpers\ArrayHelper;
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

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                        <?= Yii::t('admin-side', 'Product common info') ?>
                    </a>
                </li>
                <li class="">
                    <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                        <?= Yii::t('admin-side', 'Product description') ?>
                    </a>
                </li>
                <li class="">
                    <a href="#tab_3" data-toggle="tab" aria-expanded="false">
                        <?= Yii::t('common', 'Product images') ?>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
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
                </div>

                <div class="tab-pane" id="tab_2">
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
                                        'placeholder' => Yii::t('admin-side', 'Enter a description...', [], $lang['locale']),
                                    ],
                                ])->label($lang['name']) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab_3">
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
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
