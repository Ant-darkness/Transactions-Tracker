<?php

namespace console\controllers;

use yii\console\Controller;
use app\models\Transaction;
use app\models\DailySumary;


class SummaryController extends Controller
{

    public function mapServiceToAction($service) {
        $map = [
            'm-pesa' => 'mpesa',
            'halopesa' => 'halopesa',
            'mix-by-yas' => 'mix-by-yas',
            'airtel-money' => 'airtel-money',
        ];
        return $map[$service] ?? $service;
    }

    public function actionUpdate()
    {
        $today = date('Y-m-d');

        $users = \app\models\User::find()->all();
        $services = ['M-Pesa', 'Halopesa', 'mix by yas', 'airtel money'];

            foreach ($users as $user) {
                foreach ($services as $service) {
                    $totalReceived = Transaction::find()
                        ->where([
                            'user_id' => $user->id,
                            'service' => $service,
                            'type' => 'Received'
                        ])
                        ->andwhere(['between', 'created_at', "$today 00:00:00", "$today 23:59:59"])
                        ->sum('amount') ?? 0;

                    $totalSent = Transaction::find()
                        ->where([
                            'user_id' => $user->id,
                            'service' => $service,
                            'type' => 'Sent',
                        ])
                        ->andWhere(['between', 'created_at', "$today 00:00:00", "$today 23:59:59"])
                        ->sum('amount') ?? 0;

                    $summary = DailySummary::findOne([
                        'user_id' => $user->id,
                        'service' => $service,
                        'date' => $today
                    ]) ?? new DailySummary();

                    $summary->user_id = $user->id;
                    $summary->service = $service;
                    $summary->date = $today;
                    $summary->total_received = $totalReceived;
                    $summary->total_sent = $totalSent;
                    $summary->save();
                }
            }

            echo 'Daily summaries update\n';
    }
}