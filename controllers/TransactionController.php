<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Transaction;
use app\models\TransactionSearch;
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
        $this->view->params['service'] = 'M-Pesa';
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
        $this->view->params['service'] = 'Halopesa';
        return $this->render('service', [
            'service' => 'Halopesa',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMixByYas()
    {
        $searchModel = new \app\models\TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'mix-by-yas');
        $this->view->params['service'] = 'mix-by-yas';
        return $this->render('service', [
            'service' => 'mix-by-yas',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAirtelMoney()
    {
        $searchModel = new \app\models\TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'airtel-money');
        $this->view->params['service'] = 'airtel-money';
        return $this->render('service', [
            'service' => 'airtel-money',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function mapServiceToAction($service) {
        $map = [
            'm-pesa' => 'mpesa',
            'halopesa' => 'halopesa',
            'mix-by-yas' => 'mix-by-yas',
            'airtel-money' => 'airtel-money',
        ];
        return $map[$service] ?? $service;
    }

    public function actionReceive($service)
{
    $model = new Transaction();

    if (Yii::$app->request->isPost) {
        $model->amount = Yii::$app->request->post('Transaction')['amount']; // manually extract

        $model->user_id = Yii::$app->user->identity->id;
        $model->service = $service;
        $model->type = 'Received';
        $model->transaction_date = date('Y-m-d H:i:s');
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Transaction recorded.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to record transaction: ' . json_encode($model->errors));
        }

        return $this->redirect(['transaction/' . $this->mapServiceToAction($service)]);
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
            return $this->redirect(['transaction/' . $this->mapServiceToAction($service)]);
        }
        return $this->render('send', ['model' => $model, 'service' => $service]);
    }



    public function actionDaily($service)
    {
        return $this->render('daily', ['service' => $service]);

    }

    public function actionGraphsDashboard($service)

    {
        return $this->render('graphs-dashboard', [
            'service' => $service,
        ]);
    }



    public function actionDownloadGraph($service, $type, $period)
    {
        $transactions = Transaction::find()
            ->where(['user_id' => Yii::$app->user->identity->id, 'service' => ucwords(str_replace('-', ' ', $service))])
            ->all();

        $labels = [];
        $data = [];
        if ($period == 'weekly') {
            for ($week = 0; $week < 4; $week++) {
                $startDate = date('Y-m-d', strtotime("-$week weeks"));
                $endDate = date('Y-m-d', strtotime("-$week weeks +6 days"));
                $amount = 0;
                foreach ($transactions as $transaction) {
                    $transDate = date('Y-m-d', strtotime($transaction->transaction_date));
                    if ($transDate >= $startDate && $transDate <= $endDate && $transaction->type == $type) {
                        $amount += $transaction->amount;
                        }
                }
                $labels[] = 'Week ' . ($week + 1);
                $data[] = $amount;
                }
            $labels = array_reverse($labels);
                $data = array_reverse($data);
        }

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>' . ucwords($type) . ' Amount (' . $period . ')</h1><canvas id="chart"></canvas>');
        $mpdf->Output(ucwords($type) . '_Graph_' . date('Ymd') . '.pdf', 'D');

        return $this->render('download-graph', [
            'service' => $service,
            'type' => $type,
            'period' => $period,
        ]);
    }

    public function actionDailyGraphs($service)
    {
        return $this->render('daily-graphs', [
            'service' => $service,
        ]);
    }


    public function actionWeeklyGraphs($service)
    {
        return $this->render('weekly-graphs', [
            'service' => $service,
        ]);
    }

    public function actionMonthlyGraphs($service)
    {
       return $this->render('monthly-graphs', [
        'service' => $service,
       ]);
    }

    public function actionDelete($id)
    {
        $model = Transaction::findOne($id);

        if ($model && $model->user_id == Yii::$app->user->id) {
            $model->delete();
            Yii::$app->session->setFlash('Success', 'Transaction deleted successfully!.');
        } else {
            Yii::$app->session->setFlash('error', 'Transaction not found or access denied.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['site/dashboard']);
    }

    public function actionDailyTransactions($service)

    {
           
      
            $searchModel = new TransactionSearch();
            $searchModel->service = $service;

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $service);

            
            return $this->render('daily-transactions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'service' => $service,
        ]);
        
       
    }


    public function actionTrendHistrory($service = null)
    {
        $searchModel = new TransactionTrendSearch();

        $searchModel->service = ucwords(str_replace("-", ' ', $service));

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('trend-history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataprovider,
            'service' => $service,
        ]);
    }


    public function actionWeeklyData($service)
    {
        $userId = Yii::$app->user->id;
        $serviceName = ucwords(str_replace('-', ' ', $service));

        $startOfMonth = new \DateTime('first day of this month');
        $startOfMonth->setTime(0, 0);

        $transactions = Transaction::find()
            ->where([
                'user_id' => $userId,
                'service' => $serviceName
            ])
            ->andWhere(['>=', 'transaction_date', $startOfMonth->format('Y-m-d H:i:s')])
            ->orderBy(['transaction_date' => SORT_ASC])
            ->all();

        $received = array_fill(0, 4, 0);
        $sent = array_fill(0, 4, 0);
        $labels = [];

        for ($week = 0; $week < 4; $week++) {
            $weekStart = clone $startOfMonth;
            $weekStart->modify("+{$week} week");
            $weekEnd = clone $weekStart;
            $weekEnd->modify('+6 days');

            $labels[] = 'Week ' . ($week + 1);

            foreach ($transactions as $transaction) {
                $transDate = new \DateTime($transaction->transaction_date);
                if ($transDate >= $weekStart && $transDate <= $weekEnd) {
                    if ($transaction->type == 'Received') {
                        $received[$week] += $transaction->amount;
                    } elseif ($transaction->type == 'Sent') {
                        $sent[$week] += $transaction->amount;
                    }
                }
            }
        }

        return $this->asJson([
            'labels' => $labels,
            'received' => $received,
            'sent' => $sent,
        ]);
    }
        

    public function actionDailyData($service)
    {
        $userId = Yii::$app->user->id;
        $serviceName = ucwords(str_replace('-', ' ', $service));

        $start = new \DateTime('last sunday');
        $start->setTime(0, 0);
        $end = clone $start;
        $end->modify('+6 days')->setTime(23, 59, 59);

        $transactions = Transaction::find()
            ->where([
                'user_id' => $userId,
                'service' => $serviceName
            ])
            ->andWhere(['between', 'transaction_date', $start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')])
            ->all();

        $labels = [];
        $received = array_fill(0, 7, 0);
        $sent = array_fill(0, 7, 0);

        for ($i = 0; $i < 7; $i++) {
            $day = clone $start;
            $day->modify("+{$i} days");
            $labels[] = $day->format('D'); 

            foreach ($transactions as $transaction) {
                $transDate = new \DateTime($transaction->transaction_date);
                if ($transDate->format('Y-m-d') === $day->format('Y-m-d')) {
                    if ($transaction->type === 'Received') {
                        $received[$i] += $transaction->amount;
                    } elseif ($transaction->type === 'Sent') {
                        $sent[$i] += $transaction->amount;
                    }
                }
            }
        }

        return $this->asJson([
            'labels' => $labels,
            'received' => $received,
            'sent' => $sent
        ]);
    }

    public function actionMonthlyData($service)
    {
        $userId = Yii::$app->user->id;
        $serviceName = ucwords(str_replace('-', ' ', $service));
        $year = date('Y');

        $transactions = Transaction::find()
            ->where([
                'user_id' => $userId,
                'service' => $serviceName
            ])
            ->andWhere(['YEAR(transaction_date)' => $year])
            ->all();

        $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $received = array_fill(0, 12, 0);
        $sent = array_fill(0, 12, 0);

        foreach ($transactions as $transaction) {
            $monthIndex = (int)date('n', strtotime($transaction->transaction_date)) - 1;
            if ($transaction->type === 'Received') {
                $received[$monthIndex] += $transaction->amount;
            } elseif ($transaction->type === 'Sent') {
                $sent[$monthIndex] += $transaction->amount;
            }
        }

        return $this->asJson([
            'labels' => $labels,
            'received' => $received,
            'sent' => $sent
        ]);
    }

}