<?php
use yii\helpers\Html;
use yii\helpers\Json;

/** @var \app\models\TransactionTrend $model */

$this->title = 'Saved Trend View';


$data = Json::decode($model->data);


$labels = $data['labels'] ?? [];
$receivedData = $data['received'] ?? [];
$sentData = $data['sent'] ?? [];

?>

<div class="transaction-trend-view">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p>
        <strong>Service:</strong> <?= Html::encode($model->service) ?><br>
        <strong>Period:</strong> <?= ucfirst(Html::encode($model->period)) ?><br>
        <strong>Saved On:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <h4>Received Amount (<?= ucfirst($model->period) ?>)</h4>
            <canvas id="received-graph"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Sent Amount (<?= ucfirst($model->period) ?>)</h4>
            <canvas id="sent-graph"></canvas>
        </div>
    </div>

    <div class="mt-3">
        <?= Html::a('Back to Trend History', ['transaction/trend-history', 'service' => $model->service], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Download PDF', ['transaction/download-graph', 'service' => $model->service, 'type' => 'both', 'period' => $model->period, 'trend_id' => $model->id], ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => yii\web\View::POS_HEAD]);

$this->registerJs("
    const labels = " . json_encode($labels) . ";
    const receivedData = " . json_encode($receivedData) . ";
    const sentData = " . json_encode($sentData) . ";

    const receivedChart = new Chart(document.getElementById('received-graph').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Received',
                data: receivedData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            }
        }
    });

    const sentChart = new Chart(document.getElementById('sent-graph').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sent',
                data: sentData,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            }
        }
    });
", yii\web\View::POS_END);
?>