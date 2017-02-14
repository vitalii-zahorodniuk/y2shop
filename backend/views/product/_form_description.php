<?php
use xz1mefx\wysihtml5\Wysihtml5;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<h5><strong><?= $model->getAttributeLabel('description') ?></strong></h5>
<div class="panel panel-default" style="background-color: #f6f8fa;">
    <div class="panel-body">
        <?php foreach (Yii::$app->lang->getLangList() as $lang): ?>
            <?= $form->field($model, "translates[{$lang['id']}][description]")->widget(Wysihtml5::className(), [
                'widgetToolbar' => [
                    'fa' => TRUE,
                    'image' => FALSE,
                ],
                'options' => [
                    'class' => 'col-md-12',
                    'style' => 'margin-bottom: 20px; height: 250px;',
                    'placeholder' => Yii::t('admin-side', 'Enter a description...', [], $lang['locale']),
                ],
            ])->label($lang['name']) ?>
        <?php endforeach; ?>
    </div>
</div>
