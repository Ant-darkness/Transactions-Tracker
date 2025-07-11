<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $service */

$this->title = ucwords(str_replace('-', ' ', $service)) . ' Trend History';
?><div class="trend-history">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1><?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'columns' => [
        [
            'attribute' => 'created_at',
            'format' => ['datetime'],
            'label' => 'Saved On',
        ],
        [
            'attribute' => 'period',
            'label' => 'Trend Period',
            'value' => function ($model) {
                return ucfirst($model->period);
            }
        ],
        [
            'label' => 'View Graph',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('View', ['transaction/view-trend', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']);
            }
        ],
        [
            'label' => 'Download',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('Download PDF', ['transaction/download-graph', 'service' => $model->service, 'type' => 'both', 'period' => $model->period, 'trend_id' => $model->id], ['class' => 'btn btn-success btn-sm']);
            }
        ]
    ],
]) ?>

<div class="mt-3">
    <?= Html::a('Back to Graphs Dashboard', ['transaction/graphs-dashboard', 'service' => $service], ['class' => 'btn btn-secondary']) ?>
</div>

</div>