<?php

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $changePasswordModel \backend\models\forms\ChangeUserPasswordForm */

$this->title = Yii::t('admin-side', 'Update {modelClass}: ', [
        'modelClass' => 'User',
    ]) . $model->name;

$this->params['title'] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin-side', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin-side', 'Update');
?>

<?= $this->render('_form', [
    'model' => $model,
    'changePasswordModel' => $changePasswordModel,
]) ?>
