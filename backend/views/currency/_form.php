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

            <?php if ($model->is_default): ?>
                <p class="text-info">
                    <strong><?= Html::icon('info-sign') ?> <?= Yii::t('ufu-tools', 'Warning:') ?></strong>
                    <?= Yii::t('admin-side', 'This is default currency!') ?>
                </p>
            <?php endif; ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => TRUE, 'placeholder' => Yii::t('admin-side', 'Enter a code...')]) ?>

            <?php if (!$model->is_default): ?>
                <?= $form->field($model, 'is_default')->checkbox() ?>

                <p id="isDefaultCheckBoxMsg" class="text-warning" style="margin-top: -20px; display: none;">
                    <strong><?= Html::icon('info-sign') ?> <?= Yii::t('ufu-tools', 'Be careful:') ?></strong>
                    <?= Yii::t('admin-side', 'This flag will be reset for all other currencies!') ?>
                    <br>
                    <strong><?= Html::icon('info-sign') ?> <?= Yii::t('ufu-tools', 'Be careful:') ?></strong>
                    <?= Yii::t('admin-side', 'Sales will be conducted in this currency!') ?>
                </p>
            <?php endif; ?>

            <div class="">
                <h5><strong><?= $model->getAttributeLabel('rates') ?></strong></h5>
                <div class="panel panel-default" style="background-color: #f6f8fa;">
                    <div class="panel-body">
                        <?php foreach ($model::getAll($model->id) as $currency): ?>
                            <?= $form
                                ->field($model, "rates[{$currency->id}]")
                                ->textInput([
                                    'placeholder' => Yii::t(
                                        'admin-side',
                                        'Enter a coefficient to convert {from} to {to}...',
                                        [
                                            'from' => $model->code ?: Yii::t('admin-side', 'your currency'),
                                            'to' => $currency->code,
                                        ]
                                    ),
                                ])
                                ->label($model->code . ' ' . Html::icon('arrow-right') . ' ' . $currency->code) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($model->isNewRecord): ?>
                <div class="">
                    <h5><strong><?= $model->getAttributeLabel('inverseRates') ?></strong></h5>
                    <div class="panel panel-default" style="background-color: #f6f8fa;">
                        <div class="panel-body">
                            <?php foreach ($model::getAll($model->id) as $currency): ?>
                                <?= $form
                                    ->field($model, "inverseRates[{$currency->id}]")
                                    ->textInput([
                                        'placeholder' => Yii::t(
                                            'admin-side',
                                            'Enter a coefficient to convert from {from} to {to}...',
                                            [
                                                'from' => $currency->code,
                                                'to' => $model->code ?: Yii::t('admin-side', 'your currency'),
                                            ]
                                        ),
                                    ])
                                    ->label($currency->code . ' ' . Html::icon('arrow-right') . ' ' . $model->code) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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

<?php
$this->registerJs(<<<JS
var currencyIsDefault = $('#currency-is_default');
var isDefaultCheckBoxMsg = $('#isDefaultCheckBoxMsg');

currencyIsDefault.on('click', function () {
    if ($(this).is(':checked')) {
        isDefaultCheckBoxMsg.stop(true).slideDown();
    }
    else {
        isDefaultCheckBoxMsg.stop(true).slideUp();
    }
});
JS
);
?>
