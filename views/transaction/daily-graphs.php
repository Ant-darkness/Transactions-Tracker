<?php
use yii\helpers\Html;
use app\models\Transaction;
use app\models\TransactionTrend;

$this->title = ucwords(str_replace('-', ' ', $service)) . ' Daily Graphs';
$startDate = date('Y-m-d', strtotime('last Sunday')); 
$endDate = date('Y-m-d', strtotime('+6 days', strtotime($startDate)));

$latestTrendId = TransactionTrend::find()
->where(['service' => ucwords(str_replace('-', ' ', $service)),'period' => 'daily'])
->orderBy(['created_at' => SORT_DESC])
->one();

$latestTrendId = $latestTrendId ? $latestTrendId->id : null;

$transactions = Transaction::find()
    ->where(['user_id' => Yii::$app->user->identity->id, 'service' => ucwords(str_replace('-', ' ', $service))])
    ->andWhere(['between', 'transaction_date', $startDate . ' 00:00:00', $endDate . ' 23:59:59'])
    ->all();

$receivedData = array_fill(0, 7, 0); 
$sentData = array_fill(0, 7, 0);
$labels = [];
for ($day = 0; $day < 7; $day++) {
    $date = date('Y-m-d', strtotime($startDate . ' + ' . $day . ' days'));
    $labels[] = date('D', strtotime($date));
    foreach ($transactions as $transaction) {
        if (date('Y-m-d', strtotime($transaction->transaction_date)) == $date) {
            if ($transaction->type == 'Received') {
                $receivedData[$day] += $transaction->amount;
            } elseif ($transaction->type == 'Sent') {
                $sentData[$day] += $transaction->amount;
            }
        }
    }
}

if (date('w') == 6 && date('H:i') == '23:59') {
   
    $trend = new TransactionTrend();
    $trend->service = ucwords(str_replace('-', ' ', $service));
    $trend->period = 'daily';
    $trend->data = json_encode([
        'labels' => $labels,
        'received' => $receivedData,
        'sent' => $sentData
    ]);
    $trend->created_at = date('Y-m-d H:i:s');
    $trend->save();
}
?>

<div class="transaction-service">
    <h1 class="text-center"><?= $this->title ?></h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Received Amount (Daily)</h3>
            <canvas id="daily-received-graph"></canvas>
            
        </div>
        <div class="col-md-6">
            <h3>Sent Amount (Daily)</h3>
            <canvas id="daily-sent-graph"></canvas>
            
        </div>
    </div>
    <p><?= Html::a('Back to Graphs Dashboard', ['transaction/graphs-dashboard', 'service' => $service], ['class' => 'btn btn-secondary']) ?></p>
</div>

<?php
// $this->registerJsFile('@web/js/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs("
    var ctxReceived = document.getElementById('daily-received-graph').getContext('2d');
    var ctxSent = document.getElementById('daily-sent-graph').getContext('2d');
    var receivedChart = new Chart(ctxReceived, {
        type: 'bar',
        data: {
            labels: " . json_encode($labels) . ",
            datasets: [{
                label: 'Received Amount',
                data: " . json_encode($receivedData) . ",
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: true } },
            
        }
    });
    var sentChart = new Chart(ctxSent, {
        type: 'bar',
        data: {
            labels: " . json_encode($labels) . ",
            datasets: [{
                label: 'Sent Amount',
                data: " . json_encode($sentData) . ",
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: true } },
           
        }
    });

   function updateCharts() {
    $.get('/transaction/daily-data?service=<?= $service ?>', function(response) {
        if (response.labels && response.received && response.sent) {
            receivedChart.data.labels = response.labels;
            receivedChart.data.datasets[0].data = response.received;
            receivedChart.update();

            sentChart.data.labels = response.labels;
            sentChart.data.datasets[0].data = response.sent;
            sentChart.update();
        }
    });
    }
    setInterval(updateCharts, 86400000);
    updateCharts();
", \yii\web\View::POS_END);
?>