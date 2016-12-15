<?php
use common\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin-side', 'Users');
$this->params['title'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="box">
    <div class="box-header">
        <?= Html::a(Yii::t('admin-side', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
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
                        'headerOptions' => ['class' => 'text-center col-md-1'],
                        'contentOptions' => ['class' => 'text-center col-md-1'],
                    ],
                    'img',
                    'email:email',
                    'name',
                    'phone',

                    [
                        'class' => ActionColumn::className(),
                        'headerOptions' => ['class' => 'text-center col-md-1'],
                        'contentOptions' => ['class' => 'text-center col-md-1'],
                        'template' => '{view} {update}',
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) {
                                /* @var $model User */
                                return
                                    in_array($model->status, [User::STATUS_ACTIVE, User::STATUS_ON_HOLD])
                                    && $model->youCanEdit;
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
