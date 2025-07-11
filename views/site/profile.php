<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
<div class="site-profile">
    <h1>Profile</h1>
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
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['readonly' => true]) ?>
        <?php if ($model->profile_picture): ?>
            <img src="<?= $model->profile_picture ?>" alt="Profile Picture" style="max-width: 200px;">
        <?php endif; ?>
         <p><?= Html::a('Back to Dashboard', ['site/dashboard'], ['class' => 'btn btn-primary']) ?></p>
    <?php ActiveForm::end(); ?>
</div>