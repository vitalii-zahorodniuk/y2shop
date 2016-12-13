<?php
namespace backend\controllers;

use xz1mefx\multilang\actions\translation\IndexAction;
use xz1mefx\multilang\actions\translation\UpdateAction;
use yii\web\Controller;

/**
 * Class TranslationController
 * @package backend\controllers
 */
class TranslationController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'theme' => IndexAction::THEME_ADMINLTE,
//                'canUpdate' => false,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'theme' => UpdateAction::THEME_ADMINLTE,
            ],
        ];
    }

}
