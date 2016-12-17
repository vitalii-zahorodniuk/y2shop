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
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ROOT,
                            User::ROLE_ADMIN,
                        ],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [
                            User::PERMISSION_VIEW_ALL_USERS_LIST,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
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
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
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
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
     * @return \yii\web\Response
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

        $model->delete();

        return $this->redirect(['index']);
    }
}
