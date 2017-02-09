<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Filter */

$this->title = Yii::t('admin-side', 'Update {modelClass}: ', [
        'modelClass' => 'Filter',
    ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin-side', 'Update');
?>
<div class="filter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
