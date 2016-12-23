<?php
/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = Yii::t('admin-side', 'Create Currency');

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
