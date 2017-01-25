<?php
namespace backend\controllers;

use backend\models\User;
use xz1mefx\multilang\actions\translation\IndexAction;
use xz1mefx\multilang\actions\translation\UpdateAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * Class TranslationController
 * @package backend\controllers
 */
class TranslationController extends BaseController
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
                        'roles' => [User::PERM_TRANSLATE_CAN_VIEW_LIST],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => TRUE,
                        'roles' => [User::PERM_TRANSLATE_CAN_UPDATE],
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
                'canUpdate' => Yii::$app->user->identity->userActivated && Yii::$app->user->can([
                        User::ROLE_ROOT,
                        User::PERM_TRANSLATE_CAN_UPDATE,
                    ]),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'theme' => UpdateAction::THEME_ADMINLTE,
            ],
        ];
    }

}
