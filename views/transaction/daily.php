<?php
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
?>
<div class="transaction-daily">
    <h1>Daily Transactions - <?= Html::encode($service) ?></h1>
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
    <p><?= Html::a('Back to ' . $service, ['transaction/' . strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-secondary']) ?></p>
</div>