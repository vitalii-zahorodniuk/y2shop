<?php

namespace backend\controllers;

use xz1mefx\multilang\actions\lang\CreateAction;
use xz1mefx\multilang\actions\lang\DeleteAction;
use xz1mefx\multilang\actions\lang\IndexAction;
use xz1mefx\multilang\actions\lang\UpdateAction;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class LangController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
//                'theme' => IndexAction::THEME_ADMINLTE,
//                'canAdd' => false,
//                'canUpdate' => false,
//                'canDelete' => false,
            ],
            'create' => [
                'class' => CreateAction::className(),
//                'theme' => CreateAction::THEME_ADMINLTE,
            ],
            'update' => [
                'class' => UpdateAction::className(),
//                'theme' => UpdateAction::THEME_ADMINLTE,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
//                'theme' => DeleteAction::THEME_ADMINLTE,
            ],
        ];
    }
}
