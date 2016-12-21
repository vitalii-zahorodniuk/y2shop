<?php
namespace backend\controllers;

use backend\models\User;
use xz1mefx\multilang\actions\translation\IndexAction;
use xz1mefx\multilang\actions\translation\UpdateAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
                            User::PERMISSION_EDIT_TRANSLATES,
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
                    'update' => ['get', 'put', 'post'],
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
//                'canUpdate' => false,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'theme' => UpdateAction::THEME_ADMINLTE,
            ],
        ];
    }

}
