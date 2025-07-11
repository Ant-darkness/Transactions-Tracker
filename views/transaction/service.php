<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use app\models\Transaction;
use app\models\DailySummary; 

?>
<?php

    $serviceClass = "";
    $bodyClass = "";

    switch (strtolower($service)) {
        case 'mix-by-yas':
            $serviceClass = 'bg-purple text-dark';
            $bodyClass = 'bg-mix';
            break;
        
        case 'halopesa':
            $serviceClass = 'bg-yellow text-dark';
            $bodyClass = 'bg-ha';
            break;

        case 'airtel-money':
            $serviceClass = 'bg-indigo text-yellow';
            $bodyClass = 'bg-airtel';
            break;

        case 'm-pesa':
            $serviceClass = 'bg-danger text-white';
            $bodyClass = 'bg-m-pesa';
            break;

        default:
            $serviceClass = 'bg-secondary text-white';
            $bodyClass = 'bg-default';
            break;
    }
?>

<div style="<?= $bodyClass ?>" class="transaction-service">
    <h1 class="text-center"> <?php
    $logoPath = Yii::getAlias('@web') . '../../web/images/';
    

    if ($service == 'mix-by-yas') {

        echo '<img src="' . $logoPath .'mixx.png" alt="Mix by Yas Logo" style="height: 60px;">';

    } elseif ($service == 'Halopesa') {

        echo '<img src="' . $logoPath .'halo.png" alt="HaloPesa Logo" style="height: 60px;">';

    } elseif ($service == 'airtel-money') {

         echo '<img src="' . $logoPath .'airtel.png" alt="Airtel Money Logo" style="height: 60px;">';
    } elseif ($service == 'M-Pesa') {

         echo '<img src="' . $logoPath .'m-pesa.png" alt="M-Pesa Logo" style="height: 60px;">';
    } else {

        echo '<h1>Daily Transactions - ' . Html::encode($service) . '</h1>';
    }
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center <?= $serviceClass ?>  <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/02-rec.png" class="custom-icon" alt="logo" />

                     <p class="card-text"></p>
                    <?= Html::a('Record', ['transaction/receive', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center <?= $serviceClass ?>  <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/02-sen.png" class="custom-icon" alt="logo" />

                     <p class="card-text"></p> 
                    <?= Html::a('Record', ['transaction/send', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center <?= $serviceClass ?>  <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                   <img src="<?= Yii::$app->request->baseUrl ?>/images/transaction.png" class="custom-icon" alt="logo" />

                    <?= Html::a('View', ['transaction/daily', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    
         <div class="col-md-4">
            <div class="card text-center <?= $serviceClass ?>  <?= strtolower(str_replace(' ', '-', $service)) ?>">
                <div class="card-body">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/graph2.png" class="custom-icon" alt="logo" />

                     <?= Html::a('View', ['transaction/graphs-dashboard', 'service' => strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-info']) ?> 
                </div> 
            </div>
        </div>
    </div> 
    
    
    <?php
        $bodyClas = "";
        $textClas = "";

        switch(strtolower($service)) {
            case 'm-pesa':
                $bodyClas = 'mpesa-bg';
                $textClas = 'mpesa-text';
                break;

            case 'halopesa':
                $bodyClas = 'halopesa-bg';
                $textClas = 'halopesa-text';
                break;

            case 'mix-by-yas':
                $bodyClas = 'mix-bg';
                $textClas = 'mix-text';
                break;

            case 'airtel-money':
                $bodyClas = 'airtel-bg';
                $textClas = 'airtel-text';
                break;

            default:
                $bodyClas = 'default-bg';
                $textClas = 'default-text';
        }
        ?>
    

            <h2 class="mt-5  <?= $textClas ?>">Summary Report</h2>

            <?php
            use yii\helpers\Url;


            $date = Yii::$app->request->get('summary_date', date('Y-m-d'));
            ?>
            <form method="get" class="form-inline mb-4">
                <label for="summary_date">Choose Date:</label>
                <input type="date" name="summary_date" id="summary_date" value="<?= Html::encode($date) ?>" class="form-control mx-2">
                <input type="hidden" name="service" value="<?= Html::encode($service) ?>">
                <button type="submit" class="btn btn-primary">View</button>
            </form>

            <?php
            $summary = DailySummary::find()
                ->where([
                    'user_id' => Yii::$app->user->id,
                    'service' => $service,
                    'date' => $date
                ])
                ->one();

            $totalReceived = $summary->total_received ?? 0;
            $totalSent = $summary->total_sent ?? 0;
            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card <?= $serviceClass ?>  <?= strtolower(str_replace(' ', '-', $service)) ?>">
                        <div class="card-body">
                            <h5 class="card-title">Total Received (<?= Html::encode($date) ?>)</h5>
                            <p class="card-text"><?= 'TZS ' . number_format($totalReceived, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card <?= $serviceClass ?> <?= strtolower(str_replace(' ', '-', $service)) ?>">
                        <div class="card-body">
                            <h5 class="card-title">Total Sent (<?= Html::encode($date) ?>)</h5>
                            <p class="card-text"><?= 'TZS ' . number_format($totalSent, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

    <h2>Transaction History</h2>
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin([
                'method' => 'get', 
                'action' => Url::to(['transaction/daily-transactions',
                'service' => $service]),
                ]); ?>

                <?= $form->field($searchModel, 'type')->dropDownList(['' => 'All', 'Received' => 'Received', 'Sent' => 'Sent'], ['prompt' => 'Select Type']) ?>
                <?= $form->field($searchModel, 'date_from')->textInput(['type' => 'date']) ?>
                <?= $form->field($searchModel, 'date_to')->textInput(['type' => 'date']) ?>
                <div class="form-group">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
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
    <p><?= Html::a('Back to Dashboard', ['site/dashboard'], ['class' => 'btn btn-secondary <?= $serviceClass ?>']) ?></p>
</div>