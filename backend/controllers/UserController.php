<?php
namespace backend\controllers;

use backend\models\forms\ChangeUserPasswordForm;
use backend\models\forms\CreateUserForm;
use backend\models\search\UserSearch;
use backend\models\User;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UserController
 * @package backend\controllers
 */
class UserController extends BaseController
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
                            'index',
                            'view',
                        ],
                        'allow' => TRUE,
                        'roles' => [User::PERM_USER_CAN_VIEW_LIST],
                    ],
                    [
                        'actions' => [
                            'create',
                            'update',
                            'delete',
                        ],
                        'allow' => TRUE,
                        'roles' => [User::PERM_USER_CAN_UPDATE],
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
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
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
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return array|string|Response
     */
    public function actionCreate()
    {
        $model = new CreateUserForm();

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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->youCanEdit) {
            Yii::$app->session->setFlash('danger', Yii::t('common', 'You do not have permission to edit this user'));
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'changePasswordModel' => new ChangeUserPasswordForm($id),
            ]);
        }
    }

    /**
     * @param $id
     *
     * @return array|Response
     * @throws ForbiddenHttpException
     */
    public function actionUpdatePassword($id)
    {
        $model = new ChangeUserPasswordForm($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        // Check rights
        foreach ($this->findModel($id)->rolesArray as $role) {
            if (!Yii::$app->user->can($role)) {
                throw new ForbiddenHttpException(Yii::t('admin-side', "You have no rights to change this user!"));
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->change()) {
            Yii::$app->session->setFlash('success', Yii::t('admin-side', 'Password changed successfully'));
        } else {
            $errors = '';
            foreach ($model->errors as $field) {
                foreach ($field as $error) {
                    $errors .= empty($errors) ? '' : '<br>';
                    $errors .= $error;
                }
            }
            if (!empty($errors)) {
                Yii::$app->session->setFlash('danger', Yii::t('admin-side', "The password is not changed!<br>{errors}", ['errors' => $errors]));
            }
        }

        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('admin-side', "You have no rights to delete yourself!"));
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        // Check rights
        foreach ($this->findModel($id)->rolesArray as $role) {
            if (!Yii::$app->user->can($role)) {
                throw new ForbiddenHttpException(Yii::t('admin-side', "You have no rights to delete this user!"));
            }
        }

        $model->status = User::STATUS_DELETED;
        $model->save();

        return $this->redirect(['index']);
    }

}
