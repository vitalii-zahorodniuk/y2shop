<?php
namespace backend\controllers;

use backend\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;


/**
 * Class SiteController
 * @package backend\controllers
 */
class SiteController extends BaseController
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
                        'actions' => [
                            'error',
                            'index',
                        ],
                        'allow' => TRUE,
                        'roles' => ['@'],
                    ],
                    ['allow' => FALSE], // default rule
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'error' => ['get'],
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
            'error' => [
                'class' => ErrorAction::className(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['product/index']);
        return $this->render('index');
    }

}
