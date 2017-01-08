<?php
use common\models\Currency;
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\ufu\widgets\UfuWidget;
use xz1mefx\widgets\wysihtml5\Wysihtml5;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
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
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => TRUE, 'validateOnType' => TRUE]); ?>

        <?= UfuWidget::widget([
            'model' => $model,
            'form' => $form,
            'type' => $model::TYPE_ID,
            'categoryMultiselect' => TRUE,
        ]) ?>

        <h5><strong><?= $model->getAttributeLabel('name') ?></strong></h5>
        <div class="panel panel-default" style="background-color: #f6f8fa;">
            <div class="panel-body">
                <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                    <?= $form->field($model, "translates[{$lang['id']}][name]")->textInput()->label($lang['name']) ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row">
            <?= $form->field($model, 'price', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => TRUE]) ?>
            <?= $form->field($model, 'currency_id', ['options' => ['class' => 'col-md-6']])->dropDownList(Currency::getDrDownList()) ?>
        </div>

        <?= $form->field($model, 'image_src')->textInput(['maxlength' => TRUE]) ?>

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
                            'style' => 'margin-bottom: 20px;',
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
