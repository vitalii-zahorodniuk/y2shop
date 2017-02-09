<?php
/* @var $this yii\web\View */
/* @var $model common\models\Attribute */

$this->title = Yii::t('admin-side', 'Create Attribute');

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
