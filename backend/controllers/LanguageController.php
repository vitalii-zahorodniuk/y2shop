<?php
namespace backend\controllers;

use backend\models\User;
use xz1mefx\multilang\actions\language\CreateAction;
use xz1mefx\multilang\actions\language\DeleteAction;
use xz1mefx\multilang\actions\language\IndexAction;
use xz1mefx\multilang\actions\language\UpdateAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class LanguageController
 * @package backend\controllers
 */
class LanguageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => TRUE,
                        'roles' => [
                            User::ROLE_ROOT,
                            User::PERMISSION_EDIT_LANGUAGES,
                        ],
                    ],
                    [
                        'allow' => FALSE,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'put', 'post'],
                    'delete' => ['post', 'delete'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'theme' => IndexAction::THEME_ADMINLTE,
//                'canAdd' => false,
//                'canUpdate' => false,
//                'canDelete' => false,
            ],
            'create' => [
                'class' => CreateAction::className(),
                'theme' => CreateAction::THEME_ADMINLTE,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'theme' => UpdateAction::THEME_ADMINLTE,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'theme' => DeleteAction::THEME_ADMINLTE,
            ],
        ];
    }

}
