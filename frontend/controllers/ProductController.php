<?php
namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class ProductController
 * @package frontend\controllers
 */
class ProductController extends Controller
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
                        'actions' => ['item-view', 'category-view'],
                        'allow' => TRUE,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'item-view' => ['get'],
                    'category-view' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function actionItemView($id)
    {
        return $this->render('item-view');
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function actionCategoryView($id)
    {
        return $this->render('category-view');
    }

}
