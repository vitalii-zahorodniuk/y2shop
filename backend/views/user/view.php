<?php
use backend\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->name;

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$userRoles = '';
foreach ($model->rolesArray as $role) {
    $userRoles .= empty($userRoles) ? '' : '&nbsp;';
    $userRoles .= Html::tag('span', User::rolesLabels($role), ['class' => 'label label-primary']);
}
?>

<div class="box box-primary">
    <div class="box-header">
        <?php if ($model->youCanEdit): ?>
            <?= Html::a(Yii::t('admin-side', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php if ($model->id !== (int)Yii::$app->user->id): ?>
                <?= Html::a(Yii::t('admin-side', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('admin-side', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        &nbsp;
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <?= Html::icon('minus', ['prefix' => 'fa fa-']) ?>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="box-body-overflow">
            <?= DetailView::widget([
                'options' => ['class' => 'table table-striped table-bordered table-hover'],
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => $model->statusHtmlLabel,
                    ],
//                  'img',
                    'email:email',
                    'name',
                    'phone',
                    [
                        'label' => Yii::t('admin-side', 'User roles'),
                        'attribute' => 'role',
                        'format' => 'raw',
                        'value' => $userRoles,
                    ],
                    [
                        'attribute' => 'created_by',
                        'format' => 'raw',
                        'value' => $model->createdBy ? Html::a($model->createdBy->name, ['user/view', 'id' => $model->createdBy->id]) : '',
                    ],
                    [
                        'attribute' => 'updated_by',
                        'format' => 'raw',
                        'value' => $model->updatedBy ? Html::a($model->updatedBy->name, ['user/view', 'id' => $model->updatedBy->id]) : '',
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>
