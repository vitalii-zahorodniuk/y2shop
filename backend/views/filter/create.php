<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Filter */

$this->title = Yii::t('admin-side', 'Create Filter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
