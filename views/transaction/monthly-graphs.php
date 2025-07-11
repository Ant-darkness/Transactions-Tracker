<?php
use yii\helpers\Html;
use app\models\Transaction;
use app\models\TransactionTrend;

$this->title = ucwords(str_replace('-', ' ', $service)) . ' Monthly Graphs';
$startMonth = date('Y-m-01', strtotime('first day of January ' . date('Y'))); 
$endMonth = date('Y-m-d', strtotime('+11 months', strtotime($startMonth)));
$transactions = Transaction::find()
    ->where(['user_id' => Yii::$app->user->identity->id, 'service' => ucwords(str_replace('-', ' ', $service))])
    ->andWhere(['between', 'transaction_date', $startMonth . ' 00:00:00', $endMonth . ' 23:59:59'])
    ->all();

$receivedData = array_fill(0, 12, 0); 
$sentData = array_fill(0, 12, 0);
$labels = [];
for ($month = 0; $month < 12; $month++) {
    $monthDate = date('Y-m-d', strtotime($startMonth . ' + ' . $month . ' months'));
    $labels[] = date('M', strtotime($monthDate));
    foreach ($transactions as $transaction) {
        if (date('Y-m', strtotime($transaction->transaction_date)) == date('Y-m', strtotime($monthDate))) {
            if ($transaction->type == 'Received') {
                $receivedData[$month] += $transaction->amount;
            } elseif ($transaction->type == 'Sent') {
                $sentData[$month] += $transaction->amount;
            }
        }
    }
}

if (date('d-m') == '01-01' && date('H:i') == '00:00') {
    $trend = new TransactionTrend();
    $trend->service = ucwords(str_replace('-', ' ', $service));
    $trend->period = 'monthly';
    $trend->data = json_encode(['labels' => $labels, 'received' => $receivedData, 'sent' => $sentData]);
    $trend->created_at = date('Y-m-d H:i:s');
    $trend->save();
}
?>

<div class="transaction-service">
    <h1 class="text-center"><?= $this->title ?></h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Received Amount (Monthly)</h3>
            <canvas id="monthly-received-graph"></canvas>
            
        </div>
        <div class="col-md-6">
            <h3>Sent Amount (Monthly)</h3>
            <canvas id="monthly-sent-graph"></canvas>
            
        </div>
    </div>
    <p><?= Html::a('Back to Graphs Dashboard', ['transaction/graphs-dashboard', 'service' => $service], ['class' => 'btn btn-secondary']) ?></p>
</div>

<?php
$this->registerJsFile('@web/js/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs("
    var ctxReceived = document.getElementById('monthly-received-graph').getContext('2d');
    var ctxSent = document.getElementById('monthly-sent-graph').getContext('2d');
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
        $.get('/transaction/get-latest-data?service=" . $service . "&period=monthly', function(response) {
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
    setInterval(updateCharts, 2629800000); // Update kila mwezi (~30.44 days)
    updateCharts(); // Update ya mara ya kwanza
", \yii\web\View::POS_END);
?>