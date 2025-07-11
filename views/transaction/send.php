<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
<div class="transaction-send">
     <?php
    echo '<h1>Send Transaction </h1> ';
    $logoPath = Yii::getAlias('@web') . '../../web/images/';

    if ($service == 'mix-by-yas') {

        echo '<img src="' . $logoPath .'mixx.png" alt="Mix by Yas Logo" style="height: 60px;">';

    } elseif ($service == 'halopesa') {

        echo '<img src="' . $logoPath .'halo.png" alt="HaloPesa Logo" style="height: 60px;">';

    } elseif ($service == 'airtel-money') {

         echo '<img src="' . $logoPath .'airtel.png" alt="Airtel Money Logo" style="height: 60px;">';
    } elseif ($service == 'm-pesa') {

         echo '<img src="' . $logoPath .'m-pesa.png" alt="M-Pesa Logo" style="height: 60px;">';
    } else {

        echo '<h1>Daily Transactions - ' . Html::encode($service) . '</h1>';
    }
    ?>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'send-form']); ?>
                <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                <div class="form-group">
                    <?= Html::submitButton('Record Transaction', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
     <?php
        $serviceRoute = [
            'm-pesa' => 'mpesa',
            'halopesa' => 'halopesa',
            'mix-by-yas' => 'mix-by-yas',
                    'airtel-money' => 'airtel-money',
        ];
        $route = $serviceRoute[$service] ?? strtolower(str_replace(' ', '-', $service));
        ?>
    <p><?= Html::a('Back to ' . $service, ['transaction/' . $route], ['class' => 'btn btn-secondary']) ?></p>
</div>