<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
<div class="custom-signup" style="background-color: #f8f9fa; padding: 20px; border-radius: 10px;">
    <h1 style="color: #e74c3c; text-align: center;">Create Your Account</h1>
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success text-center">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger text-center">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
    <div class="row justify-content-center"> 
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control', 'placeholder' => 'Username']) ?>
                <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => 'Email']) ?>
                <?= $form->field($model, 'password_hash')->passwordInput(['class' => 'form-control', 'placeholder' => 'Password']) ?>
                <?= $form->field($model, 'retype_password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Re-enter Password']) ?>
                <div class="form-group text-center">
                    <?= Html::submitButton('Sign Up', ['class' => 'btn btn-danger btn-lg', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>