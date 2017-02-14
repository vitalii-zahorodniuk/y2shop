<?php
use xz1mefx\adminlte\helpers\Html;
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
                <li class="">
                    <a href="#tab_4" data-toggle="tab" aria-expanded="false">
                        <?= Yii::t('common', 'Product options') ?>
                    </a>
                </li>
                <li class="">
                    <a href="#tab_5" data-toggle="tab" aria-expanded="false">
                        <?= Yii::t('common', 'Product attributes') ?>
                    </a>
                </li>
                <li class="">
                    <a href="#tab_6" data-toggle="tab" aria-expanded="false">
                        <?= Yii::t('common', 'Product filters') ?>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <?= $this->render('_form_common', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>

                <div class="tab-pane" id="tab_2">
                    <?= $this->render('_form_description', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>

                <div class="tab-pane" id="tab_3">
                    <?= $this->render('_form_images', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>

                <div class="tab-pane" id="tab_4">
                    <?= $this->render('_form_options', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>

                <div class="tab-pane" id="tab_5">
                    <?= $this->render('_form_attributes', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>

                <div class="tab-pane" id="tab_6">
                    <?= $this->render('_form_filters', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
