<?php
use backend\models\Product;
use backend\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin-side', 'Products');
$this->params['title'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="box box-primary">
    <div class="box-header">
        <?php if (Yii::$app->user->identity->userActivated): ?>
            <?= Html::a(Yii::t('admin-side', 'Create product'), ['create'], ['class' => 'btn btn-success']) ?>
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
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'col-xs-1 col-sm-1'],
                    ],
                    'name:raw',
                    [
                        'attribute' => 'price',
                        'headerOptions' => ['class' => 'col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-right col-xs-1 col-sm-1'],
                    ],
                    [
                        'attribute' => 'currency.code',
                        'headerOptions' => ['class' => 'col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-left col-xs-1 col-sm-1'],
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => Product::statusesLabels(),
                        'headerOptions' => ['class' => 'col-md-1 col-sm-1'],
                        'contentOptions' => ['class' => 'col-md-1 col-sm-1'],
                        'content' => function ($model) {
                            /* @var $model User */
                            return $model->statusHtmlLabel;
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'headerOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'template' => '{view} {update} {delete}',
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) {
                                /* @var $model Product */
                                return Yii::$app->user->identity->userActivated
                                    && Yii::$app->user->can(User::PERM_PRODUCT_CAN_UPDATE)
                                    && (
                                        Yii::$app->user->can(User::ROLE_MANAGER)
                                        || $model->status != Product::STATUS_DELETED
                                    );
                            },
                            'delete' => function ($model, $key, $index) {
                                /* @var $model Product */
                                return Yii::$app->user->identity->userActivated
                                    && Yii::$app->user->can(User::PERM_PRODUCT_CAN_UPDATE)
                                    && $model->status != Product::STATUS_DELETED;
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
