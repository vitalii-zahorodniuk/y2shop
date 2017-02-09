<?php
/* @var $this yii\web\View */
/* @var $model common\models\Attribute */

$this->title = Yii::t('admin-side', 'Update {modelClass}: ', [
        'modelClass' => 'Attribute',
    ]) . $model->id;

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin-side', 'Update');
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
