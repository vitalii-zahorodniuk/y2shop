<?php
use common\models\Filter;
use kartik\select2\Select2;
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\base\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $productFilter \common\models\ProductFilter */
/* @var $form yii\widgets\ActiveForm */

$isNew = isset($isNew);
$uniq = str_replace('.', '_', microtime(TRUE));
$filterAjaxUrl = Url::to(['product/get-filters']);
?>

<tr>
    <td class="col-md-4">
        <?= Select2::widget([
            'id' => 'w_fg_' . $uniq,
            'name' => 'filterGroups[]',
            'data' => Filter::getGroupDrDownList(),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => Yii::t('admin-side', 'Select filter group...'),
            ],
            'pluginOptions' => [
                'allowClear' => TRUE,
            ],
        ]); ?>
    </td>
    <td class="col-md-7">
        <?= Select2::widget([
            'id' => 'w_f_' . $uniq,
            'name' => 'filters[]',
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'placeholder' => Yii::t('admin-side', 'Select filters...'),
                'multiple' => TRUE,
            ],
            'pluginOptions' => [
//                'allowClear' => TRUE,
                'tags' => TRUE,
                'tokenSeparators' => [',', ' '],
                'ajax' => [
                    'url' => new JsExpression("function() { return '{$filterAjaxUrl}?p=' + $(this).closest('tr').find('select:first-child option:selected').val(); }"),
                    'dataType' => 'json',
//                    'data' => new JsExpression("function(params) { return { q: params.term }; }"),
//                    'processResults' => new JsExpression("function(data, page) { return data; }"),
                ],
            ],
        ]); ?>
    </td>
    <td class="text-center col-md-1">
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
