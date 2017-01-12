<?php
namespace backend\controllers;

use backend\models\User;
use xz1mefx\ufu\actions\category\CreateAction;
use xz1mefx\ufu\actions\category\DeleteAction;
use xz1mefx\ufu\actions\category\IndexAction;
use xz1mefx\ufu\actions\category\UpdateAction;
use xz1mefx\ufu\actions\category\ViewAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class CategoryController
 * @package backend\controllers
 */
class CategoryController extends BaseController
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
                    ['allow' => TRUE, 'roles' => [User::ROLE_ROOT]], // default rule
                    [
                        'actions' => ['index'],
                        'allow' => TRUE,
                        'roles' => [User::PERM_CATEGORY_CAN_VIEW_LIST],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => TRUE,
                        'roles' => [User::PERM_CATEGORY_CAN_UPDATE],
                    ],
                    ['allow' => FALSE], // default rule
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
        $canEdit = Yii::$app->user->can([
            User::ROLE_ROOT,
            User::PERM_CATEGORY_CAN_UPDATE,
        ]);
        return [
            'index' => [
                'class' => IndexAction::className(),
                'theme' => IndexAction::THEME_ADMINLTE,
//                'type' => NULL,
                'canAdd' => $canEdit,
                'canUpdate' => $canEdit,
                'canDelete' => $canEdit,
            ],
            'create' => [
                'class' => CreateAction::className(),
                'theme' => CreateAction::THEME_ADMINLTE,
//                'type' => NULL,
                'canSetSection' => Yii::$app->user->can(User::ROLE_ROOT),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'theme' => UpdateAction::THEME_ADMINLTE,
//                'type' => NULL,
                'canSetSection' => Yii::$app->user->can(User::ROLE_ROOT),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'theme' => DeleteAction::THEME_ADMINLTE,
//                'type' => NULL,
            ],
            'view' => [
                'class' => ViewAction::className(),
                'theme' => DeleteAction::THEME_ADMINLTE,
//                'type' => NULL,
                'canUpdate' => $canEdit,
                'canDelete' => $canEdit,
            ],
        ];
    }

}
