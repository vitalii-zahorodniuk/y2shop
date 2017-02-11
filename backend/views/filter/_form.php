<?php
use xz1mefx\adminlte\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Filter */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {
    $model->order = 0;
}
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

        <?php /*
        <?php if (Yii::$app->user->can(User::ROLE_MANAGER)): ?>
            <?= $form->field($model, 'status')->dropDownList($model::statusesLabels()) ?>
        <?php endif; ?>
        */ ?>

        <?= $form->field($model, 'order')->textInput() ?>

        <?= $form->field($model, 'parent_id')->dropDownList($model::getGroupDrDownList())->label($model->getAttributeLabel('parentName')) ?>

        <div class="">
            <h5><strong><?= $model->getAttributeLabel('name') ?></strong></h5>
            <div class="panel panel-default" style="background-color: #f6f8fa;">
                <div class="panel-body">
                    <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                        <?= $form->field($model, "translates[{$lang['id']}][name]")->textInput(['placeholder' => Yii::t('admin-side', 'Enter name...', [], $lang['locale'])])->label($lang['name']) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
