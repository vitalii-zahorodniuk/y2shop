<?php
use backend\models\User;
use xz1mefx\adminlte\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin-side', 'Currencies');
$this->params['title'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="box box-primary">
    <div class="box-header">
        <?php if (Yii::$app->user->identity->userActivated && Yii::$app->user->can(User::PERM_CURRENCY_CAN_UPDATE)): ?>
            <?= Html::a(Yii::t('admin-side', 'Create currency'), ['create'], ['class' => 'btn btn-success']) ?>
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
        <?php if (Yii::$app->user->identity->userActivated && Yii::$app->user->can(User::PERM_CURRENCY_CAN_UPDATE)): ?>
            <p class="text-info">
                <strong><?= Html::icon('info-sign') ?> <?= Yii::t('ufu-tools', 'Warning:') ?></strong>
                <?= Yii::t('admin-side', 'You can delete the currency only without relations') ?>
            </p>
        <?php endif; ?>
        <div class="box-body-overflow">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'is_default',
                        'headerOptions' => ['class' => 'col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'format' => 'raw',
//                        'filter' => FALSE,
                        'content' => function ($model) {
                            /* @var $model \common\models\Currency */
                            return $model->is_default ? Html::icon('ok', ['class' => 'text-success']) : '';
                        },
                    ],
                    'code',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'rates',
                        'format' => 'raw',
                        'filter' => FALSE,
                        'content' => function ($model) {
                            /* @var $model \common\models\Currency */
                            $currencyRates = '';
                            foreach ($model->currencyRates as $currencyRate) {
                                $currencyRates .= strtr("<strong style=\"opacity: 0.8\">{currencyFrom}&nbsp;{arrow}&nbsp;{currencyTo}:</strong>&nbsp;&nbsp;{coefficient}<br>", [
                                    '{currencyFrom}' => $model->code,
                                    '{arrow}' => Html::icon('arrow-right'),
                                    '{currencyTo}' => $currencyRate->currencyTo->code,
                                    '{coefficient}' => $currencyRate->coefficient,
                                ]);
                            }
                            if (count($model->currencyRates) < count($model::getAll($model->id))) {
                                $currencyRates .= strtr("<p class=\"text-danger\"><strong>{icon} {title}</strong> {msg}</p><br>", [
                                    '{icon}' => Html::icon('exclamation-sign'),
                                    '{title}' => Yii::t('admin-side', 'Warning:'),
                                    '{msg}' => Yii::t('admin-side', 'Not all currency rates are filled!'),
                                ]);
                            }
                            return $currencyRates;
                        },
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'datetime',
                        'filter' => FALSE,
                    ],
                    [
                        'attribute' => 'relationsCount',
                        'filter' => FALSE,
                        'headerOptions' => ['class' => 'col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'col-xs-1 col-sm-1'],
                    ],

                    [
                        'class' => ActionColumn::className(),
                        'headerOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'contentOptions' => ['class' => 'text-center col-xs-1 col-sm-1'],
                        'template' => '{view} {update} {delete}',
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) {
                                /* @var $model \common\models\Currency */
                                return Yii::$app->user->identity->userActivated && Yii::$app->user->can(User::PERM_CURRENCY_CAN_UPDATE);
                            },
                            'delete' => function ($model, $key, $index) {
                                /* @var $model \common\models\Currency */
                                return Yii::$app->user->identity->userActivated && Yii::$app->user->can(User::PERM_CURRENCY_CAN_UPDATE) && $model->canDelete;
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
$('.grid-view td .text-danger').closest('tr').css('background-color', '#f2dede');
JS
);
?>
