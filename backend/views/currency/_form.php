<?php
use xz1mefx\adminlte\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */
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
        <div class="<!--box-body-overflow-->">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => TRUE, 'validateOnType' => TRUE]); ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => TRUE, 'placeholder' => Yii::t('admin-side', 'Enter a code...')]) ?>

            <div class="row">
                <div class="col-md-6">
                    <h5><strong><?= $model->getAttributeLabel('name') ?></strong></h5>
                    <div class="panel panel-default" style="background-color: #f6f8fa;">
                        <div class="panel-body">
                            <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                                <?= $form->field($model, "translates[{$lang['id']}][name]")->textInput(['placeholder' => Yii::t('admin-side', 'Enter a name...', [], $lang['locale'])])->label($lang['name']) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <h5><strong><?= $model->getAttributeLabel('symbol_left') ?></strong></h5>
                    <div class="panel panel-default" style="background-color: #f6f8fa;">
                        <div class="panel-body">
                            <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                                <?= $form->field($model, "translates[{$lang['id']}][symbol_left]")->textInput(['placeholder' => Yii::t('admin-side', 'Enter left symbol...', [], $lang['locale'])])->label($lang['name']) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <h5><strong><?= $model->getAttributeLabel('symbol_right') ?></strong></h5>
                    <div class="panel panel-default" style="background-color: #f6f8fa;">
                        <div class="panel-body">
                            <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
                                <?= $form->field($model, "translates[{$lang['id']}][symbol_right]")->textInput(['placeholder' => Yii::t('admin-side', 'Enter a right symbol...', [], $lang['locale'])])->label($lang['name']) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(
                    $model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
