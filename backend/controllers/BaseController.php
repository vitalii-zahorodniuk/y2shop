<?php
namespace backend\controllers;

use backend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class BaseController
 * @package backend\controllers
 */
class BaseController extends Controller
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
                    ['allow' => TRUE, 'roles' => [User::ROLE_ROOT]],
                    ['allow' => FALSE],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
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
    public function beforeAction($action)
    {
        if (
            !Yii::$app->user->isGuest
            &&
            Yii::$app->user->cannot([
                User::ROLE_ROOT,
                User::ROLE_ADMIN,
                User::ROLE_MANAGER,
                User::ROLE_SELLER,
            ])
        ) {
            Yii::$app->user->logout();
            Yii::$app->session->addFlash('error', Yii::t('admin-side', 'You have insufficient privileges!'));
        }
        return parent::beforeAction($action);
    }

}
