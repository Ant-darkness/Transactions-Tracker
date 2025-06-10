<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Transaction;
use yii\data\ActiveDataProvider;

class TransactionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionMpesa()
    {
        $searchModel = new \app\models\TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'M-Pesa');
        return $this->render('service', [
            'service' => 'M-Pesa',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHalopesa()
    {
        $searchModel = new \app\models\TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Halopesa');
        return $this->render('service', [
            'service' => 'Halopesa',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMixByYas()
    {
        $searchModel = new \app\models\TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Mix by Yas');
        return $this->render('service', [
            'service' => 'Mix by Yas',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAirtelMoney()
    {
        $searchModel = new \app\models\TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Airtel Money');
        return $this->render('service', [
            'service' => 'Airtel Money',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReceive($service)
    {
        $model = new Transaction();
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->identity->id;
            $model->service = $service;
            $model->type = 'Received';
            $model->transaction_date = date('Y-m-d H:i:s');
            $model->created_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Transaction recorded.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to record transaction.');
            }
            return $this->redirect([$service]);
        }
        return $this->render('receive', ['model' => $model, 'service' => $service]);
    }

    public function actionSend($service)
    {
        $model = new Transaction();
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->identity->id;
            $model->service = $service;
            $model->type = 'Sent';
            $model->transaction_date = date('Y-m-d H:i:s');
            $model->created_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Transaction recorded.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to record transaction.');
            }
            return $this->redirect([$service]);
        }
        return $this->render('send', ['model' => $model, 'service' => $service]);
    }

    public function actionDaily($service)
    {
        return $this->render('daily', ['service' => $service]);
    }
}