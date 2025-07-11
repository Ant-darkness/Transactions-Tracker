<?php
use yii\helpers\Html;
?>
<div class="site-index2">
    <img src="<?= Yii::$app->request->baseUrl ?>/images/icon2.png" class="custom-icon" alt="logo" />
    <p><?= Html::a('Sign Up', ['site/signup']) ?> 
    OR
    <?= Html::a('Sign In', ['site/login']) ?> 
    to continue with your Account
</p>
</div>