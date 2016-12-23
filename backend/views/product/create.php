<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('admin-side', 'Create Product');

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
