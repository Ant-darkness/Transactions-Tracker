<?php
use yii\helpers\Html;
use app\models\Transaction;

$this->title = ucwords(str_replace('-', ' ', $service)) . ' Graphs Dashboard';
?>

<div class="transaction-service">
    <h1 class="text-center">
        <?php
        $logoPath = Yii::getAlias('@web') . '../../web/images/';
        if ($service == 'mix-by-yas') {
            echo '<img src="' . $logoPath . 'mixx.png" alt="Mix by Yas Logo" style="height: 60px;">';
        } elseif ($service == 'halopesa') {
            echo '<img src="' . $logoPath . 'halo.png" alt="HaloPesa Logo" style="height: 60px;">';
        } elseif ($service == 'airtel-money') {
            echo '<img src="' . $logoPath . 'airtel.png" alt="Airtel Money Logo" style="height: 60px;">';
        } elseif ($service == 'm-pesa') {
            echo '<img src="' . $logoPath . 'm-pesa.png" alt="M-Pesa Logo" style="height: 60px;">';
        }
        ?>
    </h1>
        <div class="col-md-4">
            <div class="card text-center <?= strtolower(str_replace('-', '-', $service)) ?>">
                <div class="card-body">
                     <img src="<?= Yii::$app->request->baseUrl ?>/images/graph1.png" class="custom-icon" alt="logo" />
                    <h5 class="card-title">Per day(in week)</h5>
                    <?= Html::a('view', ['transaction/daily-graphs', 'service' => $service], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center <?= strtolower(str_replace('-', '-', $service)) ?>">
                <div class="card-body">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/graph1.png" class="custom-icon" alt="logo" />
                    <h5 class="card-title">per week(in month)</h5>
                    <?= Html::a('View', ['transaction/weekly-graphs', 'service' => $service], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center <?= strtolower(str_replace('-', '-', $service)) ?>">
                <div class="card-body">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/graph1.png" class="custom-icon" alt="logo" />
                    <h5 class="card-title">Per Month(in Year)</h5>
                    <?= Html::a('View', ['transaction/monthly-graphs', 'service' => $service], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

   

    <p><?= Html::a('Back to Dashboard', ['transaction/' . $this->context->mapServiceToAction($service)], ['class' => 'btn btn-secondary']) ?></p>
</div>

<?php
$this->registerJsFile('@web/js/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/Chart3d.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>