<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use app\models\Transaction; // Add this line

?>
< class="transaction-service">
    <h1 class="text-center"><?= Html::encode($service) ?> Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <i class="fas fa-arrow-down fa-icon"></i>
                    <h5 class="card-title">Receive Money</h5>
                    <p class="card-text">Record money received via <?= Html::encode($service) ?>.</p>
                    <?= Html::a('Receive', ['transaction/receive', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <i class="fas fa-arrow-up fa-icon"></i>
                    <h5 class="card-title">Send Money</h5>
                    <p class="card-text">Record money sent via <?= Html::encode($service) ?>.</p>
                    <?= Html::a('Send', ['transaction/send', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-icon"></i>
                    <h5 class="card-title">Daily Transactions</h5>
                    <p class="card-text">View today's transactions.</p>
                    <?= Html::a('View', ['transaction/daily', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <!-- ... (Summary Report and Transaction History remain the same) ... -->

    <h2>Summary Report</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <h5 class="card-title">Total Received</h5>
                    <p class="card-text">
                        <?php
                        $totalReceived = Transaction::find()
                            ->where(['user_id' => Yii::$app->user->identity->id, 'service' => $service, 'type' => 'Received'])
                            ->sum('amount') ?? 0;
                        echo 'TZS ' . number_format($totalReceived, 2);
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <h5 class="card-title">Total Sent</h5>
                    <p class="card-text">
                        <?php
                        $totalSent = Transaction::find()
                            ->where(['user_id' => Yii::$app->user->identity->id, 'service' => $service, 'type' => 'Sent'])
                            ->sum('amount') ?? 0;
                        echo 'TZS ' . number_format($totalSent, 2);
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <h2>Transaction History</h2>
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['transaction/' . strtolower(str_replace(' ', '-', $service))]]); ?>
                <?= $form->field($searchModel, 'type')->dropDownList(['' => 'All', 'Received' => 'Received', 'Sent' => 'Sent'], ['prompt' => 'Select Type']) ?>
                <?= $form->field($searchModel, 'date_from')->textInput(['type' => 'date']) ?>
                <?= $form->field($searchModel, 'date_to')->textInput(['type' => 'date']) ?>
                <div class="form-group">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Reset', ['transaction/' . strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-secondary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'type',
            'amount',
            'transaction_date:datetime',
        ],
    ]); ?>
    <p><?= Html::a('Back to Dashboard', ['site/dashboard'], ['class' => 'btn btn-secondary']) ?></p>
</div>