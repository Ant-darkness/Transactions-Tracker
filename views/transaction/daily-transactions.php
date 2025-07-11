<?php
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TransactionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<div class="transaction-date-results">
    <h1><?= Html::encode($this->title) ?></h1>

        
    <p>
        <?= Html::a('Back to Service Page', ['transaction/' . strtolower(str_replace(' ', '-', $searchModel->service))], ['class' => 'btn btn-secondary']) ?>
    </p>
    
    <?php if ($dataProvider->getCount() > 0): ?>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'type',
            'amount',
            'transaction_date:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('Delete', ['transaction/delete', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => 'Are you sure you want to delete this transaction?',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php else: ?>
        <div class="alert alert-info">
            <p>No transactions found for the selected date range.</p>
        </div>
    
    <?php endif; ?>
</div>