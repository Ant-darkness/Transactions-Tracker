<?php
use yii\helpers\Html;
use app\models\Transaction;
use app\models\TransactionTrend;

$this->title = ucwords(str_replace('-', ' ', $service)) . ' Weekly Graphs';


$startWeek = (new DateTime('first day of this month'))->setTime(0,0);
$endWeek = (new DateTime('last day of this month'))->setTime(23,59,59);


$transactions = Transaction::find()
    ->where(['user_id' => Yii::$app->user->id, 'service' => ucwords(str_replace('-', ' ', $service))])
    ->andWhere(['between', 'transaction_date', $startWeek->format('Y-m-d H:i:s'), $endWeek->format('Y-m-d H:i:s')])
    ->all();


$receivedData = array_fill(0, 4, 0);
$sentData = array_fill(0, 4, 0);
$labels = [];

for ($week = 0; $week < 4; $week++) {
    $weekStart = clone $startWeek;
    $weekStart->modify("+{$week} week");
    $weekEnd = clone $weekStart;
    $weekEnd->modify('+6 days');

    $labels[] = 'Week ' . ($week + 1);

    foreach ($transactions as $transaction) {
        $transDate = new DateTime($transaction->transaction_date);
        if ($transDate >= $weekStart && $transDate <= $weekEnd) {
            if ($transaction->type == 'Received') {
                $receivedData[$week] += $transaction->amount;
            } elseif ($transaction->type == 'Sent') {
                $sentData[$week] += $transaction->amount;
            }
        }
    }
}


if (date('d') === '01' && date('H:i') === '00:00') {
    $trend = new TransactionTrend();
    $trend->user_id = Yii::$app->user->id;
    $trend->service = ucwords(str_replace('-', ' ', $service));
    $trend->period = 'weekly';
    $trend->month = (int) date('m') - 1 ?: 12;
    $trend->year = (int) date('Y');
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
            <h3>Received Amount (Weekly)</h3>
            <canvas id="weekly-received-graph"></canvas>
           
        </div>
        <div class="col-md-6">
            <h3>Sent Amount (Weekly)</h3>
            <canvas id="weekly-sent-graph"></canvas>
            
        </div>
    </div>
    <p><?= Html::a('Back to Graphs Dashboard', ['transaction/graphs-dashboard', 'service' => $service], ['class' => 'btn btn-secondary']) ?></p>
</div>

<?php
$this->registerJsFile('@web/js/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs("
    var ctxReceived = document.getElementById('weekly-received-graph').getContext('2d');
    var ctxSent = document.getElementById('weekly-sent-graph').getContext('2d');
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
    $.get('/transaction/weekly-data?service=<?= $service ?>', function(response) {
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

// Update mara moja kwa wiki (ms = 7 * 24 * 60 * 60 * 1000)
setInterval(updateCharts, 604800000);
updateCharts();
", \yii\web\View::POS_END);
?>