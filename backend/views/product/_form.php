<?php
use common\models\Currency;
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\ufu\widgets\UfuWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box">
    <div class="box-header">
        &nbsp;
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <?= Html::icon('minus', ['prefix' => 'fa fa-']) ?>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => TRUE, 'validateOnType' => TRUE]); ?>

        <?= UfuWidget::widget([
            'model' => $model,
            'form' => $form,
            'type' => $model::TYPE_ID,
            'categoryMultiselect' => TRUE,
        ]) ?>

        <div class="row">
            <?= $form->field($model, 'price', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => TRUE]) ?>
            <?= $form->field($model, 'currency_id', ['options' => ['class' => 'col-md-6']])->dropDownList(Currency::getDrDownList()) ?>
        </div>

        <?= $form->field($model, 'image_src')->textInput(['maxlength' => TRUE]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
