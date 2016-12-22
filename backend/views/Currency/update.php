<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = Yii::t('admin-side', 'Update {modelClass}: ', [
        'modelClass' => 'Currency',
    ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin-side', 'Update');
?>
<div class="currency-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
