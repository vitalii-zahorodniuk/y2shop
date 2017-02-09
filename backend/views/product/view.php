<?php
use backend\models\Product;
use backend\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */

$this->title = $model->name;

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$imageStyles = 'max-width: 400px; max-height: 400px; display: inline-block; margin: 3px; box-shadow: 0 0px 5px rgba(0, 0, 0, 0.5);';
$productImages = '';
foreach ($model->productImages as $productImage) {
    $productImages .= Html::img("/img/product/$model->id/galleryImage/{$productImage->image_src}", [
        'style' => $imageStyles,
    ]);
}
?>

<div class="box box-primary">
    <div class="box-header">
        <?php if (
            Yii::$app->user->identity->userActivated
            && Yii::$app->user->can(User::PERM_PRODUCT_CAN_UPDATE)
            && (
                Yii::$app->user->can(User::ROLE_MANAGER)
                || $model->status != Product::STATUS_DELETED
            )
        ): ?>
            <?= Html::a(Yii::t('admin-side', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php if ($model->status != Product::STATUS_DELETED): ?>
                <?= Html::a(Yii::t('admin-side', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('admin-side', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can(User::ROLE_MANAGER) && $model->status != Product::STATUS_ACTIVE): ?>
                <?= Html::a(Yii::t('admin-side', 'Activate'), ['activate', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => Yii::t('admin-side', 'Are you sure you want to activate this item?'),
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
//                    'seller_id',
                    'name:raw',
                    [
                        'attribute' => 'image_src',
                        'format' => 'raw',
                        'value' => $model->image_src ? Html::img("/img/product/$model->id/mainImage/{$model->image_src}", ['style' => $imageStyles]) : '',
                        'visible' => !empty($model->image_src),
                    ],
                    [
                        'attribute' => 'productImages.image_src',
                        'format' => 'raw',
                        'value' => $productImages,
                        'visible' => !empty($productImages),
                    ],
                    'price',
                    'currency.name',
                    'viewed_count',
                    [
                        'attribute' => 'viewed_date',
                        'visible' => $model->viewed_count > 0,
                    ],
                    [
                        'attribute' => 'created_by',
                        'format' => 'raw',
                        'value' => $model->createdBy ? Html::a($model->createdBy->name, ['user/view', 'id' => $model->createdBy->id]) : '',
                        'visible' => !empty($model->createdBy) && Yii::$app->user->can(User::ROLE_MANAGER),
                    ],
                    [
                        'attribute' => 'updated_by',
                        'format' => 'raw',
                        'value' => $model->updatedBy ? Html::a($model->updatedBy->name, ['user/view', 'id' => $model->updatedBy->id]) : '',
                        'visible' => !empty($model->updatedBy) && Yii::$app->user->can(User::ROLE_MANAGER),
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>
