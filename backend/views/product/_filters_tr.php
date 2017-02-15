<?php
use common\models\Filter;
use kartik\select2\Select2;
use xz1mefx\adminlte\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $productFilter \common\models\ProductFilter */
/* @var $form yii\widgets\ActiveForm */

$isNew = isset($isNew);
?>

<tr>
    <td>
        <?= Select2::widget([
            'id'=>'fg-'.time(),
            'name' => 'groups[]',
            'data' => Filter::getGroupDrDownList(),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => Yii::t('admin-side', 'Select filter group...'),
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </td>
    <td class="text-right col-md-1">
        <?= Html::a(Html::icon('trash'), '#', ['class' => 'btn btn-danger remove-pf-btn', 'title' => Yii::t('admin-side', 'Delete')]) ?>
    </td>

    <?php /*
    <td style="padding-top: 15px; font-weight: bold;"><?= $pageFileName . ($isNew ? ' <span class="label label-danger">new</span>' : '') ?></td>
    <td style="padding-top: 15px;">
        <?= Html::a($pageFileUrl, $pageFileUrl, ['target' => '_blank']) ?>
    </td>
    <td class="text-right col-md-2">
        <input type="hidden" name="Product[attachmentsArray][]"
               value="<?= Html::encode(Json::encode(['name' => $pageFileName, 'url' => $pageFileUrl])) ?>">
        <?= Html::a(Html::icon('eye-open'), $pageFileUrl, ['class' => 'btn btn-success', 'title' => 'Просмотреть / Скачать', 'target' => '_blank']) ?>
        <?= Html::a(Html::icon('trash'), '#', ['class' => 'btn btn-danger remove-pf-btn', 'title' => 'Удалить']) ?>
    </td>
    */ ?>
</tr>
