<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
<div class="transaction-send">
    <h1>Send Money - <?= Html::encode($service) ?></h1>
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
    <p><?= Html::a('Back to ' . $service, ['transaction/' . strtolower(str_replace(' ', '-', $service))], ['class' => 'btn btn-secondary']) ?></p>
</div>