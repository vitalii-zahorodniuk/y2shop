<?php
use backend\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $changePasswordModel \backend\models\forms\ChangeUserPasswordForm */
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
        <div class="box-body-overflow">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => TRUE, 'validateOnType' => TRUE]); ?>

            <?= $form->field($model, 'status')->dropDownList($model::statusesLabels(), ['options' => [$model::STATUS_ON_HOLD => ["selected" => TRUE]]]) ?>

            <?php /*
            <?= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>
            */ ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => TRUE, 'placeholder' => Yii::t('admin-side', 'Enter a email...')]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => TRUE, 'placeholder' => Yii::t('admin-side', 'Enter a name...')]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => TRUE, 'placeholder' => Yii::t('admin-side', 'Enter a phone...')]) ?>

            <?php if ($model->isNewRecord): ?>
                <hr>

                <?= $form->field($model, 'newPassword')->passwordInput(['placeholder' => Yii::t('admin-side', 'Enter a password...')]) ?>

                <?= $form->field($model, 'newPasswordConfirm')->passwordInput(['placeholder' => Yii::t('admin-side', 'Confirm password...')]) ?>

                <br>
            <?php endif; ?>

            <?= $form->field($model, 'rolesArray')->checkboxList(User::availableRolesLabels(), ['separator' => '<br />']) ?>

            <div class="form-group">
                <?php if (!$model->isNewRecord) : ?>
                    <?= Html::a(Yii::t('admin-side', 'Change password'), '#cp-modal', ['class' => 'btn btn-danger', 'data-toggle' => 'modal',]) ?>
                <?php endif; ?>
                <?= Html::submitButton(
                    $model->isNewRecord ? Yii::t('admin-side', 'Create') : Yii::t('admin-side', 'Update'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php if (!$model->isNewRecord && isset($changePasswordModel)) : ?>
    <?php
    Modal::begin([
        'id' => 'cp-modal',
        'header' => '<b>' . Yii::t('admin-side', 'Change user password') . '</b>',
        'clientOptions' => FALSE,
    ]);
    ?>
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => TRUE,
        'validateOnType' => TRUE,
        'action' => Url::toRoute(['user/update-password', 'id' => $model->id]),
        'options' => ['role' => 'form'],
    ]); ?>
    <?= $form->field($changePasswordModel, 'newPassword')->passwordInput(['placeholder' => Yii::t('admin-side', 'Enter a password...')]) ?>
    <?= $form->field($changePasswordModel, 'newPasswordConfirm')->passwordInput(['placeholder' => Yii::t('admin-side', 'Confirm password...')]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('admin-side', 'Update'), ['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Modal::end(); ?>
<?php endif; ?>
