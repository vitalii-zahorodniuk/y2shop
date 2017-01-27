<?php
namespace backend\controllers;

use backend\models\Product;
use backend\models\search\ProductSearch;
use backend\models\User;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ProductController
 * @package backend\controllers
 */
class ProductController extends BaseController
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
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => TRUE,
                        'roles' => [User::PERM_PRODUCT_CAN_VIEW_LIST],
                    ],
                    [
                        'actions' => [
                            'create',
                            'update',
                            'delete',
                            'main-image-upload',
                            'main-image-delete',
                            'gallery-image-upload',
                            'gallery-image-delete',
                        ],
                        'allow' => TRUE,
                        'roles' => [User::PERM_PRODUCT_CAN_UPDATE],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->userOnHold) {
                                throw new ForbiddenHttpException(Yii::t('admin-side', 'Your account is waiting for confirmation!'));
                            }
                            return TRUE;
                        },
                    ],
                    [
                        'actions' => [
                            'activate',
                        ],
                        'allow' => TRUE,
                        'roles' => [User::ROLE_MANAGER],
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
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'put', 'post'],
                    'delete' => ['post', 'delete'],
                    'main-image-upload' => ['get', 'post'],
                    'main-image-delete' => ['post'],
                    'gallery-image-upload' => ['get', 'post'],
                    'gallery-image-delete' => ['post'],
                    'activate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        if (Yii::$app->user->cannot(User::ROLE_MANAGER)) {
            $searchModel->seller_id = Yii::$app->user->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $id
     *
     * @return Product
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== NULL) {
            if (Yii::$app->user->cannot(User::ROLE_MANAGER) && $model->seller_id != Yii::$app->user->id) {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
    }

    /**
     * @return array|string|Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->request->get('t')) {
            return $this->redirect(['create', 't' => Yii::$app->security->generateRandomString()]);
        }

        $model = new Product();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     *
     * @return array|string|Response
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->status == Product::STATUS_DELETED && Yii::$app->user->cannot(User::ROLE_MANAGER)) {
            throw new ForbiddenHttpException(Yii::t('admin-side', 'You have no rights to edit deleted product!'));
        }

        $postData = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $model->load($postData)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($postData) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);

        if ($model->status == Product::STATUS_DELETED && Yii::$app->user->cannot(User::ROLE_MANAGER)) {
            throw new ForbiddenHttpException(Yii::t('admin-side', 'You have no rights to edit deleted product!'));
        }

        $model->status = Product::STATUS_ACTIVE;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if ($model = $this->findModel($id)) {
            $model->status = Product::STATUS_DELETED;
            $model->save();
            return $this->redirect(['index']);
        }
        throw new NotFoundHttpException();
    }

    /**
     * @return string
     */
    public function actionMainImageUpload()
    {
        if (Yii::$app->request->post()) {
            return Product::uploadTmpImage('mainImage');
        }
        return Product::getImage('mainImage');
    }

    /**
     * @param $name string
     *
     * @return string
     */
    public function actionMainImageDelete($name)
    {
        return Product::deleteImageByName('mainImage', $name);
    }

    /**
     * @return string
     */
    public function actionGalleryImageUpload()
    {
        if (Yii::$app->request->post()) {
            return Product::uploadTmpImage('galleryImage');
        }
        return Product::getImage('galleryImage');
    }

    /**
     * @param $name string
     *
     * @return string
     */
    public function actionGalleryImageDelete($name)
    {
        return Product::deleteImageByName('galleryImage', $name);
    }
}
