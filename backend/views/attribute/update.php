<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Attribute */

$this->title = Yii::t('admin-side', 'Update {modelClass}: ', [
    'modelClass' => 'Attribute',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin-side', 'Update');
?>
<div class="attribute-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
