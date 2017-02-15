<?php
use xz1mefx\adminlte\helpers\Html;
use xz1mefx\base\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<table id="filtersTable" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?= Yii::t('admin-side', 'Filter group') ?></th>
            <th colspan="2"><?= Yii::t('admin-side', 'Filter') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($model->productFilters): ?>
            <?php foreach ($model->productFilters as $productFilter): ?>
                <?= $this->render('_filters_tr', [
                    'model' => $model,
                    'productFilter' => $productFilter,
                    'form' => $form,
                ]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">
                    <i><?= Yii::t('admin-side', 'No filters yet') ?></i>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= Html::a(Html::icon('plus') . ' ' . Yii::t('admin-side', 'Add new filter'), NULL, ['id' => 'addNewFilter', 'class' => 'btn btn-primary']) ?>

<?php
$filtersTrUrl = Url::to(['get-filter-tr']);

$this->registerJs(<<<JS
var filtersTable = $('#filtersTable');
var addNewFilter = $('#addNewFilter');

addNewFilter.on('click', function (e) {
    e.preventDefault();

    $.post(
        "{$filtersTrUrl}",
        {},
        function (data) {
            filtersTable.find('tbody').append(data);
        }
    );
});
JS
);
?>
