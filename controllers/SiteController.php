<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\filters\AccessControl;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['dashboard', 'logout'],
                'rules' => [
                    [
                        'actions' => ['dashboard', 'logout'],
                        'allow' => true,
                        'roles' => ['@'], // Logged-in users only
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

   public function actionSignup()
{
    $model = new User();
    if ($model->load(Yii::$app->request->post())) {
        $post = Yii::$app->request->post('User');
        if ($post['password_hash'] === $post['retype_password']) {
            $model->setPassword($post['password_hash']); // Hash the password
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Successfully registered!');
                return $this->redirect(['login']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to register. Please try again.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Passwords do not match.');
        }
    }
    return $this->render('signup', ['model' => $model]);
}

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['dashboard']);
        }

        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findOne(['email' => $model->email]);
            if ($user && $user->validatePassword($model->password_hash)) {
                Yii::$app->user->login($user);
                return $this->redirect(['dashboard']);
            } else {
                Yii::$app->session->setFlash('error', 'Invalid email or password.');
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['index']);
    }

    public function actionProfile()
{
    $model = User::findOne(Yii::$app->user->identity->id);
    if ($model->load(Yii::$app->request->post())) {
        $model->profilePictureFile = UploadedFile::getInstance($model, 'profilePictureFile');
        if ($model->profilePictureFile && $model->uploadProfilePicture()) {
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Profile picture updated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update profile picture.');
            }
        }
        return $this->redirect(['dashboard']);
    }
    return $this->render('profile', ['model' => $model]);
}

public function actionAbout()
{
    return $this->render('about');
}
}