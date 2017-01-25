<?php
use backend\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = $model->id;

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header">
        <?php if (Yii::$app->user->identity->userActivated && Yii::$app->user->can(User::PERM_CURRENCY_CAN_UPDATE)): ?>
            <?= Html::a(Yii::t('admin-side', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php if ($model->canDelete): ?>
                <?= Html::a(Yii::t('admin-side', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('ufu-tools', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <?= Html::icon('minus', ['prefix' => 'fa fa-']) ?>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?php if (Yii::$app->user->identity->userActivated && Yii::$app->user->can(User::PERM_CURRENCY_CAN_UPDATE) && !$model->canDelete): ?>
            <p class="text-info">
                <strong><?= Html::icon('info-sign') ?> <?= Yii::t('admin-side', 'Warning:') ?></strong>
                <?= Yii::t('admin-side', 'You can delete the currency only without relations') ?>
            </p>
        <?php endif; ?>
        <div class="box-body-overflow">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'code',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'symbolLeft',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'symbolRight',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'relationsCount',
                    ],
                    [
                        'attribute' => 'created_by',
                        'format' => 'raw',
                        'value' => $model->createdBy ? Html::a($model->createdBy->name, ['user/view', 'id' => $model->createdBy->id]) : '',
                        'visible' => !empty($model->createdBy),
                    ],
                    [
                        'attribute' => 'updated_by',
                        'format' => 'raw',
                        'value' => $model->updatedBy ? Html::a($model->updatedBy->name, ['user/view', 'id' => $model->updatedBy->id]) : '',
                        'visible' => !empty($model->updatedBy),
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>
