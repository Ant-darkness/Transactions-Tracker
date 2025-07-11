<?php
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
?>
<div class="transaction-daily">
    <?php
    echo '<h1>DAILY TRANSACTIONS</h1> ';
    $logoPath = Yii::getAlias('@web') . '../../web/images/';

    if ($service == 'mix-by-yas') {

        echo '<img src="' . $logoPath .'mixx.png" alt="Mix by Yas Logo" style="height: 60px;">';

    } elseif ($service == 'halopesa') {

        echo '<img src="' . $logoPath .'halo.png" alt="HaloPesa Logo" style="height: 60px;">';

    } elseif ($service == 'airtel-money') {

         echo '<img src="' . $logoPath .'airtel.png" alt="Airtel Money Logo" style="height: 60px;">';
    } elseif ($service == 'm-pesa') {

         echo '<img src="' . $logoPath .'m-pesa.png" alt="M-Pesa Logo" style="height: 60px;">';
    } else {

        echo '<h1>Daily Transactions - ' . Html::encode($service) . '</h1>';
    }
    ?>
   
    <h3>Received Today</h3>
    <?php
    $today = date('Y-m-d');
    $receivedProvider = new ActiveDataProvider([
        'query' => Transaction::find()
            ->where(['user_id' => Yii::$app->user->identity->id, 'service' => $service, 'type' => 'Received'])
            ->andWhere(['like', 'transaction_date', $today]),
    ]);
    $receivedTotal = Transaction::find()
        ->where(['user_id' => Yii::$app->user->identity->id, 'service' => $service, 'type' => 'Received'])
        ->andWhere(['like', 'transaction_date', $today])
        ->sum('amount') ?? 0;
    echo \yii\grid\GridView::widget([
        'dataProvider' => $receivedProvider,
        'columns' => [
            'amount',
            'transaction_date:datetime',
        ],
        'summary' => 'Total Received: ' . $receivedTotal,
    ]);
    ?>
    <h3>Sent Today</h3>
    <?php
    $sentProvider = new ActiveDataProvider([
        'query' => Transaction::find()
            ->where(['user_id' => Yii::$app->user->identity->id, 'service' => $service, 'type' => 'Sent'])
            ->andWhere(['like', 'transaction_date', $today]),
    ]);
    $sentTotal = Transaction::find()
        ->where(['user_id' => Yii::$app->user->identity->id, 'service' => $service, 'type' => 'Sent'])
        ->andWhere(['like', 'transaction_date', $today])
        ->sum('amount') ?? 0;
    echo \yii\grid\GridView::widget([
        'dataProvider' => $sentProvider,
        'columns' => [
            'amount',
            'transaction_date:datetime',
        ],
        'summary' => 'Total Sent: ' . $sentTotal,
    ]);
    ?>
    <?php
        $serviceRoute = [
            'm-pesa' => 'mpesa',
            'halopesa' => 'halopesa',
            'mix-by-yas' => 'mix-by-yas',
                    'airtel-money' => 'airtel-money',
        ];
        $route = $serviceRoute[$service] ?? strtolower(str_replace(' ', '-', $service));
        ?>

    <p><?= Html::a('Back to ' . $service, ['transaction/' . $route], ['class' => 'btn btn-secondary']) ?></p>
</div>