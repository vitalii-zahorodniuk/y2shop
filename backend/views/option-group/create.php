<?php
/* @var $this yii\web\View */
/* @var $model common\models\Option */

$this->title = Yii::t('admin-side', 'Create Option Group');

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Option groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
