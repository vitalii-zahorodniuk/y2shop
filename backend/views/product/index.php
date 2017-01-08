<?php
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

<div class="box">
    <div class="box-header">
        <?= Html::a(Yii::t('admin-side', 'Create product'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                    ],
//                    'status',
                    'currency.code',
//                    'seller_id',
//                    'image_src',
//                    'price',
//                    'viewed_count',
//                    'viewed_date',
//                    'created_by',
//                    'updated_by',
//                    'created_at',
//                    'updated_at',

                    [
                        'class' => ActionColumn::className(),
                        'headerOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'template' => '{view} {update} {delete}',
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) {
                                /* @var $model \common\models\Product */
                                return Yii::$app->user->can(User::PERM_PRODUCT_CAN_UPDATE);
                            },
                            'delete' => function ($model, $key, $index) {
                                /* @var $model \common\models\Product */
                                return Yii::$app->user->can(User::PERM_PRODUCT_CAN_UPDATE);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
