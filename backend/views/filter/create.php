<?php
/* @var $this yii\web\View */
/* @var $model common\models\Filter */

$this->title = Yii::t('admin-side', 'Create Filter');

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
