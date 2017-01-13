<?php
use backend\models\User;
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

<div class="box box-primary">
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
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                    ],
                    [
                        'attribute' => 'name',
                        'content' => function ($model) {
                            /* @var $model User */
                            return $model->name . ($model->id == Yii::$app->user->id ? ' ' . Html::infoLabel('bg-yellow-gradient', Yii::t('admin-side', 'It\'s you')) : '');
                        },
                    ],
                    'email:email',
                    'phone',
                    [
                        'attribute' => 'roles',
                        'filter' => User::rolesLabels(),
                        'headerOptions' => ['class' => 'col-md-2 col-sm-2'],
                        'contentOptions' => ['class' => 'col-md-2 col-sm-2'],
                        'content' => function ($model) {
                            /* @var $model User */
                            $res = '';
                            foreach ($model->rolesArray as $role) {
                                $res .= empty($res) ? '' : '&nbsp;';
                                $res .= Html::infoLabel('bg-light-blue-gradient', User::rolesLabels($role));
                            }
//                            foreach ($model->childrenRolesArray as $role) {
//                                $res .= empty($res) ? '' : '&nbsp;';
//                                $res .= Html::tag('span', User::rolesLabels($role), ['class' => 'label label-info', 'title' => 'Унаследованное право']);
//                            }

                            return $res;
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => User::statusesLabels(),
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
                        'template' => '{view} {update}',
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) {
                                /* @var $model User */
                                return
                                    in_array($model->status, [User::STATUS_ACTIVE, User::STATUS_ON_HOLD])
                                    && $model->youCanEdit;
                                // TODO: ?!?!?!?!?
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
