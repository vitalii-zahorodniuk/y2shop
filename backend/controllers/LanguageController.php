<?php
namespace backend\controllers;

use backend\models\User;
use xz1mefx\multilang\actions\language\CreateAction;
use xz1mefx\multilang\actions\language\DeleteAction;
use xz1mefx\multilang\actions\language\IndexAction;
use xz1mefx\multilang\actions\language\UpdateAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * Class LanguageController
 * @package backend\controllers
 */
class LanguageController extends BaseController
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
                        'actions' => ['index'],
                        'allow' => TRUE,
                        'roles' => [User::PERM_LANGUAGE_CAN_VIEW_LIST],
                    ],
                    [
                        'actions' => [
                            'create',
                            'update',
                            'delete'],
                        'allow' => TRUE,
                        'roles' => [User::PERM_LANGUAGE_CAN_UPDATE],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->userOnHold) {
                                throw new ForbiddenHttpException(Yii::t('admin-side', 'Your account is waiting for confirmation!'));
                            }
                            return TRUE;
                        },
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
        $canEdit = Yii::$app->user->identity->userActivated && Yii::$app->user->can([
                User::ROLE_ROOT,
                User::PERM_LANGUAGE_CAN_UPDATE,
            ]);
        return [
            'index' => [
                'class' => IndexAction::className(),
                'theme' => IndexAction::THEME_ADMINLTE,
                'canAdd' => $canEdit,
                'canUpdate' => $canEdit,
                'canDelete' => $canEdit,
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
