<?php

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = Yii::t('admin-side', 'Create User');

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
